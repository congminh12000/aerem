<?php
/**
 * Plugin Name: Aerem Local Hardening
 * Description: Disable remote license and update checks for disposable local clones.
 */

if ( 'local' !== wp_get_environment_type() ) {
	return;
}

add_filter(
	'automatic_updater_disabled',
	static function () {
		return true;
	}
);

add_filter(
	'auto_update_plugin',
	static function () {
		return false;
	}
);

add_filter(
	'auto_update_theme',
	static function () {
		return false;
	}
);

add_filter(
	'pre_site_transient_update_plugins',
	static function () {
		return (object) array(
			'last_checked'    => time(),
			'checked'         => array(),
			'response'        => array(),
			'translations'    => array(),
			'no_update'       => array(),
		);
	}
);

add_filter(
	'pre_site_transient_update_themes',
	static function () {
		return (object) array(
			'last_checked' => time(),
			'checked'      => array(),
			'response'     => array(),
			'no_update'    => array(),
		);
	}
);

add_filter(
	'pre_site_transient_update_core',
	static function () {
		return (object) array(
			'updates'        => array(),
			'version_checked'=> get_bloginfo( 'version' ),
			'last_checked'   => time(),
		);
	}
);

add_filter(
	'pre_http_request',
	static function ( $preempt, $parsed_args, $url ) {
		$blocked_hosts = array(
			'api.wpcode.com',
			'api.bricksbuilder.io',
			'my.bricksbuilder.io',
			'connect.advancedcustomfields.com',
			'wpmailsmtp.com',
			'api.rankmath.com',
			'rankmath.com',
			'wsform.com',
			'updates.wpcode.com',
			'easydigitaldownloads.com',
		);

		$host = wp_parse_url( $url, PHP_URL_HOST );

		if ( ! $host ) {
			return $preempt;
		}

		foreach ( $blocked_hosts as $blocked_host ) {
			if ( $host === $blocked_host || str_ends_with( $host, '.' . $blocked_host ) ) {
				return array(
					'headers'  => array(),
					'body'     => '',
					'response' => array(
						'code'    => 204,
						'message' => 'Blocked in local environment',
					),
					'cookies'  => array(),
					'filename' => null,
				);
			}
		}

		return $preempt;
	},
	10,
	3
);
