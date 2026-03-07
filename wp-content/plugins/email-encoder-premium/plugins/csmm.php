<?php

defined( 'ABSPATH' ) || exit;

add_filter( 'eae_buffer_action', function () {
    return 'after_setup_theme';
} );

add_filter( 'eae_buffer_priority', function () {
    return 0;
} );

add_filter( 'eae_html_obfuscated', function ( $html ) {
    return str_replace(
        '</head>',
        eae_head() . '</head>',
        $html
    );
} );
