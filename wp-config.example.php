<?php
/**
 * Local bootstrap config for cloned environments.
 *
 * Copy this file to wp-config.php and adjust only via environment variables
 * or by editing the defaults below for a disposable local setup.
 *
 * @package WordPress
 */

function aerem_env( $key, $default = '' ) {
	$value = getenv( $key );

	if ( false === $value || '' === $value ) {
		return $default;
	}

	return $value;
}

define( 'DB_NAME', aerem_env( 'WP_DB_NAME', 'local' ) );
define( 'DB_USER', aerem_env( 'WP_DB_USER', 'root' ) );
define( 'DB_PASSWORD', aerem_env( 'WP_DB_PASSWORD', 'root' ) );
define( 'DB_HOST', aerem_env( 'WP_DB_HOST', 'localhost' ) );
define( 'DB_CHARSET', 'utf8' );
define( 'DB_COLLATE', '' );

define( 'AUTH_KEY', aerem_env( 'WP_AUTH_KEY', 'change-me-local-auth-key' ) );
define( 'SECURE_AUTH_KEY', aerem_env( 'WP_SECURE_AUTH_KEY', 'change-me-local-secure-auth-key' ) );
define( 'LOGGED_IN_KEY', aerem_env( 'WP_LOGGED_IN_KEY', 'change-me-local-logged-in-key' ) );
define( 'NONCE_KEY', aerem_env( 'WP_NONCE_KEY', 'change-me-local-nonce-key' ) );
define( 'AUTH_SALT', aerem_env( 'WP_AUTH_SALT', 'change-me-local-auth-salt' ) );
define( 'SECURE_AUTH_SALT', aerem_env( 'WP_SECURE_AUTH_SALT', 'change-me-local-secure-auth-salt' ) );
define( 'LOGGED_IN_SALT', aerem_env( 'WP_LOGGED_IN_SALT', 'change-me-local-logged-in-salt' ) );
define( 'NONCE_SALT', aerem_env( 'WP_NONCE_SALT', 'change-me-local-nonce-salt' ) );
define( 'WP_CACHE_KEY_SALT', aerem_env( 'WP_CACHE_KEY_SALT', 'change-me-local-cache-key-salt' ) );

$table_prefix = aerem_env( 'WP_TABLE_PREFIX', 'wp_' );

define( 'WP_HOME', aerem_env( 'WP_HOME', 'http://aerem.local' ) );
define( 'WP_SITEURL', aerem_env( 'WP_SITEURL', WP_HOME ) );
define( 'WP_ENVIRONMENT_TYPE', aerem_env( 'WP_ENVIRONMENT_TYPE', 'local' ) );
define( 'WP_DEBUG', filter_var( aerem_env( 'WP_DEBUG', '1' ), FILTER_VALIDATE_BOOLEAN ) );
define( 'WP_DEBUG_LOG', filter_var( aerem_env( 'WP_DEBUG_LOG', '1' ), FILTER_VALIDATE_BOOLEAN ) );
define( 'WP_DEBUG_DISPLAY', filter_var( aerem_env( 'WP_DEBUG_DISPLAY', '0' ), FILTER_VALIDATE_BOOLEAN ) );

if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

require_once ABSPATH . 'wp-settings.php';
