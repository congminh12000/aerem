<?php

if (!defined('ABSPATH')) { die();
}

function uninstall_method() {
        
    global $brxc_acf_fields;

    $remove_data = $brxc_acf_fields['remove_data'];

    if ( isset($remove_data) && $remove_data ){

        global $wpdb;

        $all_post_ids = get_posts(array(
            'posts_per_page'  => -1,
            'post_type' => 'brxc_color_palette'
        ));

        $posts = get_posts($args);
        if(isset($posts) && is_array($posts) ){
            foreach ($posts as $post) {
                wp_delete_post($post->ID, true);
            }
        }

        // Delete postmeta data associated with 'brxc_color_palette'
        global $wpdb;
        $wpdb->query("DELETE FROM $wpdb->postmeta WHERE post_id IN (SELECT ID FROM $wpdb->posts WHERE post_type = 'brxc_color_palette')");

        // Delete options from wp_options table with 'bricks-advanced-themer' in option_name
        $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '%bricks-advanced-themer%'");

    }

}