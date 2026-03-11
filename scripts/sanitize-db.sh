#!/usr/bin/env bash

set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
OUTPUT_DIR="${OUTPUT_DIR:-$ROOT_DIR/private-backups}"
DB_HOST="${DB_HOST:-localhost}"
DB_PORT="${DB_PORT:-3306}"
SOURCE_DB_NAME="${SOURCE_DB_NAME:-local}"
SANITIZED_DB_NAME="${SANITIZED_DB_NAME:-${SOURCE_DB_NAME}_sanitized}"
DB_USER="${DB_USER:-root}"
DB_PASSWORD="${DB_PASSWORD:-root}"
UPLOADS_SOURCE_DIR="${UPLOADS_SOURCE_DIR:-$ROOT_DIR/wp-content/uploads}"

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

mysqldump_exec() {
  MYSQL_PWD="$DB_PASSWORD" mysqldump \
    --host="$DB_HOST" \
    --port="$DB_PORT" \
    --user="$DB_USER" \
    --single-transaction \
    --default-character-set=utf8mb4 \
    --routines \
    --triggers \
    "$@"
}

require_command mysql
require_command mysqldump
require_command gzip
require_command tar

mkdir -p "$OUTPUT_DIR"

echo "Rebuilding sanitized database: $SANITIZED_DB_NAME"
mysql_exec --database=mysql -e "DROP DATABASE IF EXISTS \`$SANITIZED_DB_NAME\`; CREATE DATABASE \`$SANITIZED_DB_NAME\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysqldump_exec "$SOURCE_DB_NAME" | mysql_exec --database="$SANITIZED_DB_NAME"

echo "Removing runtime caches, transients, sessions, and update data"
mysql_exec --database="$SANITIZED_DB_NAME" <<'SQL'
DELETE FROM wp_options
WHERE option_name LIKE '\_transient\_%'
   OR option_name LIKE '\_site\_transient\_%'
   OR option_name IN (
     'active_plugins_hash',
     'auto_updater.lock',
     'rewrite_rules'
   );

DELETE FROM wp_options
WHERE option_name REGEXP '(license|licence|token|secret|smtp|mailgun|sendgrid|recaptcha|api[_-]?key|consumer[_-]?(key|secret)|access[_-]?token|refresh[_-]?token)';

DELETE FROM wp_usermeta
WHERE meta_key IN ('session_tokens', 'wp_user-settings', 'wp_user-settings-time');

DELETE FROM wp_options
WHERE option_name IN (
  'wpcode_activate_response',
  'wpcode_license',
  'rank_math_analytics_access_token',
  'rank_math_analytics_refresh_token',
  'rank_math_google_oauth_tokens',
  'wp_mail_smtp',
  'wp_mail_smtp_am_notifications',
  'bricks_license_key',
  'acf_pro_license',
  'automaticcss_license_key',
  'frames_plugin_license',
  'wsf_license'
);
SQL

echo "Exporting sanitized database archive"
mysqldump_exec "$SANITIZED_DB_NAME" | gzip -c > "$OUTPUT_DIR/database.sql.gz"

if [[ -d "$UPLOADS_SOURCE_DIR" ]]; then
  echo "Packaging uploads from $UPLOADS_SOURCE_DIR"
  tar -czf "$OUTPUT_DIR/uploads.tar.gz" -C "$(dirname "$UPLOADS_SOURCE_DIR")" "$(basename "$UPLOADS_SOURCE_DIR")"
else
  echo "Uploads directory not found, skipping archive: $UPLOADS_SOURCE_DIR" >&2
fi

if command -v shasum >/dev/null 2>&1; then
  (
    cd "$OUTPUT_DIR"
    shasum -a 256 database.sql.gz uploads.tar.gz 2>/dev/null > SHA256SUMS || true
  )
fi

echo
echo "Private backup package created in: $OUTPUT_DIR"
echo "- database.sql.gz"
if [[ -f "$OUTPUT_DIR/uploads.tar.gz" ]]; then
  echo "- uploads.tar.gz"
fi
if [[ -f "$OUTPUT_DIR/SHA256SUMS" ]]; then
  echo "- SHA256SUMS"
fi
