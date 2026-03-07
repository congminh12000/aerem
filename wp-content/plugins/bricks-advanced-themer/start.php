<?php

/**
 * Load all application modules.
 *
 * @package Bricks_Advanced_Themer
 */

defined('ABSPATH') || die();

// Disable ACF Free if it's installed

if(in_array('advanced-custom-fields/acf.php', get_option( 'active_plugins', array() ), true )){
    add_action( 'admin_init', function(){
        deactivate_plugins('advanced-custom-fields/acf.php');
    } );
}

global $brxc_acf_already_exists;

// Include ACF PRO only if it's not installed yet

if(!class_exists('ACF')){
    $brxc_acf_already_exists = false;
    include_once( plugin_dir_path( __FILE__ ) . '/plugins/acf-pro/acf.php' );
    add_filter( 'acf/settings/show_updates', '__return_false', 100);
    add_filter( 'acf/settings/path', 'Advanced_Themer_Bricks\AT__ACF::acf_settings_path' );
    add_filter( 'acf/settings/dir', 'Advanced_Themer_Bricks\AT__ACF::acf_settings_dir' );
    add_filter( 'site_transient_update_plugins', 'Advanced_Themer_Bricks\AT__ACF::stop_acf_update_notifications', 11 );
}


function brxc_init_plugin()
{
    if ( defined( 'BRICKS_AREAS_INST' ) ) {

        return;

    }

    include_once __DIR__ . '/const.php';

    if (!class_exists('Advanced_Themer_Bricks\AT__Init')) {

        require_once plugin_dir_path( __FILE__ ) . 'classes/init.php';

        Advanced_Themer_Bricks\AT__Init::init_hooks();
    }
    
}

add_action( 'plugins_loaded', 'brxc_init_plugin' );