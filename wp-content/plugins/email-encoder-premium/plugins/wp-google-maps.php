<?php

defined( 'ABSPATH' ) || exit;

add_action( 'wp_print_footer_scripts', function () {
    global $wp_scripts;

    $data = $wp_scripts->get_data( 'wpgmaps_core', 'data' );

    if ( ! $data ) {
        return;
    }

    $wp_scripts->registered[ 'wpgmaps_core' ]->extra[ 'data' ] = preg_replace_callback(
        '/^var wpgmaps_localize_marker_data = (.+?);$/m',
        function ( $matches ) {
            $markers = json_decode( $matches[ 1 ], true );

            return sprintf(
                'var wpgmaps_localize_marker_data = %s;',
                json_encode( eae_encode_json_recursive( $markers ) )
            );
        },
        $data
    );

}, 0 );
