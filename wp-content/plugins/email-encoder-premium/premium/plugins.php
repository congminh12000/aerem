<?php

defined( 'ABSPATH' ) || exit;

$eaeSearchIn = get_option( 'eae_search_in', 'filters' );

if ( $eaeSearchIn === 'output' ) {

    /**
     * WooCommerce
     * https://woocommerce.com/
     */
    if ( class_exists( 'WooCommerce' ) ) {
        add_filter( 'woocommerce_structured_data_product', function ( $markup ) {
            return eae_encode_json_recursive( $markup );
        }, EAE_FILTER_PRIORITY );
    }

    /**
     * Schema Pro
     * https://wpschema.com/
     */
    if ( class_exists( 'BSF_AIOSRS_Pro' ) ) {
        require_once __DIR__ . '/../plugins/schema-pro.php';
    }

    /**
     * Elementor
     * https://elementor.com
     */
    if ( defined( 'ELEMENTOR_VERSION' ) ) {
        require_once __DIR__ . '/../plugins/elementor.php';
    }

    /**
     * WP Structuring Markup
     * https://wordpress.org/plugins/wp-structuring-markup/
     */
    if ( class_exists( 'Structuring_Markup' ) ) {
        require_once __DIR__ . '/../plugins/wp-structuring-markup.php';
    }

    /**
     * WP Google Maps
     * https://www.wpgmaps.com/
     */
    if ( defined( 'WPGMAPS' ) ) {
        require_once __DIR__ . '/../plugins/wp-google-maps.php';
    }

    /**
     * WPSSO Schema JSON-LD
     * https://wpsso.com/
     */
    if ( class_exists( 'WpssoJson' ) && defined( 'WPSSO_HEAD_PRIORITY' ) ) {
        require_once __DIR__ . '/../plugins/wpsso.php';
    }

    /**
     * Rank Math SEO
     * https://rankmath.com/wordpress/plugin/seo-suite/
     */
    if ( class_exists( 'RankMath\RichSnippet\JsonLD' ) || class_exists( 'RankMath\Schema\JsonLD' ) ) {
        add_filter( 'rank_math/json_ld', function ( $data ) {
            return eae_encode_json_recursive( $data );
        }, EAE_FILTER_PRIORITY );
    }

    /**
     * Minimal Coming Soon & Maintenance Mode
     * https://wordpress.org/plugins/minimal-coming-soon-maintenance-mode/
     */
    if ( function_exists( 'csmm_plugin_init' ) ) {
        require_once __DIR__ . '/../plugins/csmm.php';
    }

    /**
     * Ginger (EU Cookie Law)
     * http://www.ginger-cookielaw.com/
     */
    if ( function_exists( 'ginger_run' ) ) {
        add_filter( 'eae_buffer_action', '__return_false' );
        add_filter( 'final_output', 'eae_buffer_callback', EAE_FILTER_PRIORITY );
    }

}

/**
 * Register plugin filters when full-page scanning is not an option.
 */
if ( $eaeSearchIn === 'filters' ) {

    // Advanced Custom Fields
    add_filter( 'acf/load_value', function ( $value ) {
        return eae_encode_emails( $value );
    }, EAE_FILTER_PRIORITY );

    // Jetpack
    add_filter( 'jetpack_open_graph_tags', function ( $tags ) {
        return array_map( function ( $tag ) {
            return eae_encode_emails( $tag );
        }, $tags );
    }, EAE_FILTER_PRIORITY );

    // Webdados’ Open Graph
    add_filter( 'fb_og_output', function ( $html ) {
        return eae_encode_emails( $html );
    }, 100 );
}
