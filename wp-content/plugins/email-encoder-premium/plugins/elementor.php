<?php

defined( 'ABSPATH' ) || exit;

add_action( 'wp_print_footer_scripts', function () {
    global $wp_scripts;

    $data = $wp_scripts->get_data( 'elementor-frontend', 'before' );

    if ( empty( $data ) ) {
        return;
    }

    $wp_scripts->registered[ 'elementor-frontend' ]->extra[ 'before' ] = array_map( function ( $output ) {
        if ( ! is_string( $output ) ) {
            return $output;
        }

        return preg_replace_callback(
            '/^var elementorFrontendConfig = ([\w\W]+);$/',
            function ( $matches ) {
                $markers = json_decode( $matches[ 1 ], true );

                return sprintf(
                    'var elementorFrontendConfig = %s;',
                    json_encode( eae_encode_json_recursive( $markers ) )
                );
            },
            $output
        );
    }, $data);
}, 0 );
