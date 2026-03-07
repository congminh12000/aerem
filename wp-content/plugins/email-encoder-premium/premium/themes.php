<?php

defined( 'ABSPATH' ) || exit;

$eaeSearchIn = get_option( 'eae_search_in', 'filters' );

if ( $eaeSearchIn === 'output' ) {

    /**
     * Thrive Architect
     * https://thrivethemes.com/architect/
     */
    add_action( 'tcb_landing_head', 'eae_buffer', EAE_FILTER_PRIORITY );

    /**
     * MyListing
     * https://mylistingtheme.com
     */
    require_once __DIR__ . '/../themes/mylisting.php';

}
