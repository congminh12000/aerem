<?php

defined( 'ABSPATH' ) || exit;

require_once __DIR__ . '/dom.php';
require_once __DIR__ . '/updates.php';

add_action( 'wp_head', 'eae_print_head' );

add_action( 'plugins_loaded', function () {
    if ( eae_license_was_revoked() ) {
        return;
    }

    require_once __DIR__ . '/themes.php';
    require_once __DIR__ . '/plugins.php';

    if ( ! $action = apply_filters( 'eae_buffer_action', 'template_include' ) ) {
        return;
    }

    if ( get_option( 'eae_buffer_priority' ) === 'early' ) {
        $action = 'template_redirect';
        $priority = 0;
    } else {
        $priority = EAE_FILTER_PRIORITY;
    }

    add_filter( $action, function ( $argument ) {
        eae_buffer();

        return $argument;
    }, $priority );
} );

add_action( 'admin_head', function () {
    $screen = get_current_screen();

    if ( ! isset( $screen->id ) || $screen->id !== 'settings_page_eep' ) {
        return;
    }

    echo <<<HTML
        <style>
            .description .license-success,
            .description .license-success a {
                color: #46b450;
            }
            .description .license-warning,
            .description .license-warning a {
                color: #ff9700;
            }
            .description .license-danger,
            .description .license-danger a {
                color: #dc3232;
            }
        </style>
HTML;
} );

function eae_head() {
    return eae_head_styles() . eae_head_scripts();
}

function eae_print_head() {
    echo eae_head();
}

function eae_head_styles() {
    $cssName = EAE_DOM_Encoder::instance()->cssName;

    $styles = <<<HTML
        <style data-eae-name="{$cssName}">
            .__eae_cssd, .{$cssName} {
                unicode-bidi: bidi-override;
                direction: rtl;
            }
        </style>
HTML;

    return sprintf(
        "\n%s\n",
        preg_replace( '/(\v|\s{2,})/', '', $styles )
    );
}

function eae_head_scripts() {
    $script = file_get_contents( __DIR__ . '/../includes/rot.js' );
    $script = str_replace( '__eae_r47', EAE_DOM_Encoder::instance()->jsName, $script );
    $script = preg_replace( '/(\v|\s{2,})/', ' ', $script );
    $script = preg_replace( '/\s+/', ' ', $script );

    return sprintf( "\n<script>%s</script>\n", $script );
}
