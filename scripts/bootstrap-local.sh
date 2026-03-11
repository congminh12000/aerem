#!/usr/bin/env bash

set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
BACKUP_DIR="${BACKUP_DIR:-$ROOT_DIR/private-backups}"
DB_HOST="${DB_HOST:-localhost}"
DB_PORT="${DB_PORT:-3306}"
DB_NAME="${DB_NAME:-local}"
DB_USER="${DB_USER:-root}"
DB_PASSWORD="${DB_PASSWORD:-root}"
LOCAL_URL="${LOCAL_URL:-http://aerem.local}"
PRODUCTION_URL="${PRODUCTION_URL:-https://aerem.com.vn}"
WP_PATH="${WP_PATH:-$ROOT_DIR}"
LOCAL_ADMIN_USER="${LOCAL_ADMIN_USER:-localadmin}"
LOCAL_ADMIN_EMAIL="${LOCAL_ADMIN_EMAIL:-localadmin@example.test}"
LOCAL_ADMIN_PASSWORD="${LOCAL_ADMIN_PASSWORD:-LocalAdmin#ChangeMe123!}"

SQL_ARCHIVE="${SQL_ARCHIVE:-$BACKUP_DIR/database.sql.gz}"
UPLOADS_ARCHIVE="${UPLOADS_ARCHIVE:-$BACKUP_DIR/uploads.tar.gz}"

require_command() {
  if ! command -v "$1" >/dev/null 2>&1; then
    echo "Missing required command: $1" >&2
    exit 1
  fi
}

mysql_exec() {
  MYSQL_PWD="$DB_PASSWORD" mysql \
    --host="$DB_HOST" \
    --port="$DB_PORT" \
    --user="$DB_USER" \
    --default-character-set=utf8mb4 \
    "$@"
}

php_eval() {
  php -r "$1"
}

require_command php
require_command mysql
require_command gzip
require_command tar

if [[ ! -f "$SQL_ARCHIVE" ]]; then
  echo "Database archive not found: $SQL_ARCHIVE" >&2
  exit 1
fi

if [[ ! -f "$UPLOADS_ARCHIVE" ]]; then
  echo "Uploads archive not found: $UPLOADS_ARCHIVE" >&2
  exit 1
fi

if [[ ! -f "$WP_PATH/wp-config.php" ]]; then
  echo "Missing wp-config.php. Copy wp-config.example.php to wp-config.php first." >&2
  exit 1
fi

echo "Creating database if needed: $DB_NAME"
mysql_exec --database=mysql -e "CREATE DATABASE IF NOT EXISTS \`$DB_NAME\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

echo "Importing sanitized database from $SQL_ARCHIVE"
gzip -dc "$SQL_ARCHIVE" | mysql_exec --database="$DB_NAME"

echo "Restoring uploads into wp-content/"
mkdir -p "$WP_PATH/wp-content"
tar -xzf "$UPLOADS_ARCHIVE" -C "$WP_PATH/wp-content"

if [[ -d "$WP_PATH/wp-content/uploads/uploads" ]]; then
  echo "Flattening nested uploads directory"
  rsync -a "$WP_PATH/wp-content/uploads/uploads/" "$WP_PATH/wp-content/uploads/"
  rm -rf "$WP_PATH/wp-content/uploads/uploads"
fi

echo "Running domain replacement and local bootstrap"
php_eval '
$_SERVER["HTTP_HOST"] = parse_url(getenv("LOCAL_URL"), PHP_URL_HOST) ?: "aerem.local";
$_SERVER["SERVER_NAME"] = $_SERVER["HTTP_HOST"];
$_SERVER["REQUEST_URI"] = "/";
$_SERVER["HTTPS"] = (0 === strpos(getenv("LOCAL_URL"), "https://")) ? "on" : "";
require getenv("WP_PATH") . "/wp-load.php";

global $wpdb;

$production = rtrim(getenv("PRODUCTION_URL"), "/");
$local = rtrim(getenv("LOCAL_URL"), "/");

function aerem_recursive_replace($value, $search, $replace) {
  if (is_array($value)) {
    foreach ($value as $key => $item) {
      $value[$key] = aerem_recursive_replace($item, $search, $replace);
    }
    return $value;
  }

  if (is_object($value)) {
    foreach ($value as $key => $item) {
      $value->$key = aerem_recursive_replace($item, $search, $replace);
    }
    return $value;
  }

  if (is_string($value)) {
    return str_replace($search, $replace, $value);
  }

  return $value;
}

function aerem_replace_option_urls($search, $replace) {
  global $wpdb;
  $rows = $wpdb->get_results("SELECT option_id, option_value FROM {$wpdb->options}", ARRAY_A);

  foreach ($rows as $row) {
    $original = maybe_unserialize($row["option_value"]);
    $updated = aerem_recursive_replace($original, $search, $replace);

    if ($updated !== $original) {
      update_option($wpdb->get_var($wpdb->prepare("SELECT option_name FROM {$wpdb->options} WHERE option_id = %d", $row["option_id"])), $updated, false);
    }
  }
}

update_option("siteurl", $local, false);
update_option("home", $local, false);
aerem_replace_option_urls($production, $local);

$posts = $wpdb->get_results("SELECT ID, post_content, post_excerpt, guid FROM {$wpdb->posts}", ARRAY_A);
foreach ($posts as $post) {
  $fields = array();
  if (is_string($post["post_content"]) && str_contains($post["post_content"], $production)) {
    $fields["post_content"] = str_replace($production, $local, $post["post_content"]);
  }
  if (is_string($post["post_excerpt"]) && str_contains($post["post_excerpt"], $production)) {
    $fields["post_excerpt"] = str_replace($production, $local, $post["post_excerpt"]);
  }
  if (is_string($post["guid"]) && str_contains($post["guid"], $production)) {
    $fields["guid"] = str_replace($production, $local, $post["guid"]);
  }
  if ($fields) {
    $fields["ID"] = (int) $post["ID"];
    wp_update_post(wp_slash($fields));
  }
}

$meta_rows = $wpdb->get_results("SELECT meta_id, meta_value FROM {$wpdb->postmeta}", ARRAY_A);
foreach ($meta_rows as $meta) {
  $original = maybe_unserialize($meta["meta_value"]);
  $updated = aerem_recursive_replace($original, $production, $local);
  if ($updated !== $original) {
    update_metadata_by_mid("post", (int) $meta["meta_id"], $updated);
  }
}

$local_user = getenv("LOCAL_ADMIN_USER");
$local_email = getenv("LOCAL_ADMIN_EMAIL");
$local_password = getenv("LOCAL_ADMIN_PASSWORD");

$user = get_user_by("login", $local_user);
if (!$user) {
  $user_id = wp_create_user($local_user, $local_password, $local_email);
  if (is_wp_error($user_id)) {
    fwrite(STDERR, "Failed to create local admin: " . $user_id->get_error_message() . PHP_EOL);
    exit(1);
  }
  $user = get_user_by("id", $user_id);
}

$user->set_role("administrator");
wp_set_password($local_password, $user->ID);
wp_update_user(array(
  "ID" => $user->ID,
  "user_email" => $local_email,
  "display_name" => "Local Administrator",
  "nickname" => "Local Administrator",
));

flush_rewrite_rules();
'

echo
echo "Local bootstrap completed."
echo "Site URL: $LOCAL_URL"
echo "Admin user: $LOCAL_ADMIN_USER"
echo "Admin email: $LOCAL_ADMIN_EMAIL"
echo "Admin password: $LOCAL_ADMIN_PASSWORD"
