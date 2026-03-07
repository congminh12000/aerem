<?php

if (!defined('ABSPATH')) { die();
}

$prefix = $brxc_acf_fields['global_prefix'];
$typography = [];
$spacing = [];
$border = [];
$border_radius = [];
$box_shadow = [];
$width = [];
$misc = [];


if ( have_rows( 'field_6445ab9f3d498', 'bricks-advanced-themer' ) ) :
    while ( have_rows( 'field_6445ab9f3d498', 'bricks-advanced-themer' ) ) :

        the_row();

        // Typography

        if ( Advanced_Themer_Bricks\AT__Helpers::is_typography_tab_activated() && have_rows( 'field_63a6a58831bbe', 'bricks-advanced-themer' ) ) :

            while ( have_rows( 'field_63a6a58831bbe', 'bricks-advanced-themer' ) ) :
                the_row();

                $label = get_sub_field('brxc_typography_label', 'bricks-advanced-themer' );

                $min = get_sub_field('brxc_typography_min_value', 'bricks-advanced-themer' );

                $max = get_sub_field('brxc_typography_max_value', 'bricks-advanced-themer' );

                $item = [];

                $key = strtolower( preg_replace( '/\s+/', '-', esc_attr($label) ) );

                if ( isset($prefix) && $prefix ) {

                    $value = 'var(--' . esc_attr($prefix) . '-' . $key . ')'; 
                
                } else {

                    $value = 'var(--' . $key . ')'; 

                }

                $item['key'] = $key;
                $item['value'] = $value;
                $item['min'] = $min;
                $item['max'] = $max;
                $typography[] = $item;
                
            endwhile;
            
        endif;

        // Spacing

        if ( Advanced_Themer_Bricks\AT__Helpers::is_spacing_tab_activated() && have_rows( 'field_63a6a51731bbb', 'bricks-advanced-themer' ) ) :

            while ( have_rows( 'field_63a6a51731bbb', 'bricks-advanced-themer' ) ) :

                the_row();

                $label = get_sub_field('brxc_spacing_label', 'bricks-advanced-themer' );

                $min = get_sub_field('brxc_spacing_min_value', 'bricks-advanced-themer' );

                $max = get_sub_field('brxc_spacing_max_value', 'bricks-advanced-themer' );

                $item = [];

                $key = strtolower( preg_replace( '/\s+/', '-', esc_attr($label) ) );

                if ( isset($prefix) && $prefix ) {

                    $value = 'var(--' . esc_attr($prefix) . '-' . $key . ')'; 
                
                } else {

                    $value = 'var(--' . $key . ')'; 

                }

                $item['key'] = $key;
                $item['value'] = $value;
                $item['min'] = $min;
                $item['max'] = $max;
                $spacing[] = $item;
                
            endwhile;
            
        endif;

        // Border
        if ( Advanced_Themer_Bricks\AT__Helpers::is_border_tab_activated() && have_rows( 'field_63c8f17ytr545', 'bricks-advanced-themer' ) ) :

            while ( have_rows( 'field_63c8f17ytr545', 'bricks-advanced-themer' ) ) :
                the_row();

                $label = get_sub_field('brxc_border_simple_label', 'bricks-advanced-themer' );

                $static_value = get_sub_field('brxc_border_simple_value', 'bricks-advanced-themer' );

                $item = [];

                $key = strtolower( preg_replace( '/\s+/', '-', esc_attr($label) ) );

                if ( isset($prefix) && $prefix ) {

                    $value = 'var(--' . esc_attr($prefix) . '-' . $key . ')'; 
                
                } else {

                    $value = 'var(--' . $key . ')'; 

                }

                $item['key'] = $key;
                $item['min'] = $static_value;
                $item['value'] = $value;
                $border[] = $item;
                
            endwhile;
            
        endif;

        // Border Radius
        if ( Advanced_Themer_Bricks\AT__Helpers::is_border_radius_tab_activated() && have_rows( 'field_63c8f17f5e2ed', 'bricks-advanced-themer' ) ) :

            while ( have_rows( 'field_63c8f17f5e2ed', 'bricks-advanced-themer' ) ) :
                the_row();

                $label = get_sub_field('brxc_border_label', 'bricks-advanced-themer' );

                $min = get_sub_field('brxc_border_min_value', 'bricks-advanced-themer' );

                $max = get_sub_field('brxc_border_max_value', 'bricks-advanced-themer' );

                $item = [];

                $key = strtolower( preg_replace( '/\s+/', '-', esc_attr($label) ) );

                if ( isset($prefix) && $prefix ) {

                    $value = 'var(--' . esc_attr($prefix) . '-' . $key . ')'; 
                
                } else {

                    $value = 'var(--' . $key . ')'; 

                }

                $item['key'] = $key;
                $item['value'] = $value;
                $item['min'] = $min;
                $item['max'] = $max;
                $border_radius[] = $item;
                
            endwhile;
            
        endif;

        // Box-Shadow
        if ( Advanced_Themer_Bricks\AT__Helpers::is_box_shadow_tab_activated() && have_rows( 'field_63c8f17s4stt6', 'bricks-advanced-themer' ) ) :

            while ( have_rows( 'field_63c8f17s4stt6', 'bricks-advanced-themer' ) ) :
                the_row();

                $label = get_sub_field('brxc_box_shadow_label', 'bricks-advanced-themer' );

                $static_value = get_sub_field('brxc_box_shadow_value', 'bricks-advanced-themer' );

                $item = [];

                $key = strtolower( preg_replace( '/\s+/', '-', esc_attr($label) ) );

                if ( isset($prefix) && $prefix ) {

                    $value = 'var(--' . esc_attr($prefix) . '-' . $key . ')'; 
                
                } else {

                    $value = 'var(--' . $key . ')'; 

                }

                $item['key'] = $key;
                $item['min'] = $static_value;
                $item['value'] = $value;
                $box_shadow[] = $item;
                
            endwhile;
            
        endif;

        // Width
        if ( Advanced_Themer_Bricks\AT__Helpers::is_width_tab_activated() && have_rows( 'field_63c8f17ppo69i', 'bricks-advanced-themer' ) ) :

            while ( have_rows( 'field_63c8f17ppo69i', 'bricks-advanced-themer' ) ) :
                the_row();

                $label = get_sub_field('brxc_width_label', 'bricks-advanced-themer' );

                $min = get_sub_field('brxc_width_min_value', 'bricks-advanced-themer' );

                $max = get_sub_field('brxc_width_max_value', 'bricks-advanced-themer' );

                $item = [];

                $key = strtolower( preg_replace( '/\s+/', '-', esc_attr($label) ) );

                if ( isset($prefix) && $prefix ) {

                    $value = 'var(--' . esc_attr($prefix) . '-' . $key . ')'; 
                
                } else {

                    $value = 'var(--' . $key . ')'; 

                }

                $item['key'] = $key;
                $item['value'] = $value;
                $item['min'] = $min;
                $item['max'] = $max;
                $width[] = $item;
                
            endwhile;
            
        endif;

        // Misc

        if ( Advanced_Themer_Bricks\AT__Helpers::is_custom_variables_tab_activated() && have_rows( 'field_64066a105f7ec', 'bricks-advanced-themer' ) ) :

            $cat = []; 
            $index = 0;
            
            while ( have_rows( 'field_64066a105f7ec', 'bricks-advanced-themer' ) ) :
                
                the_row();
                
                $cat_label = get_sub_field('brxc_misc_category_label', 'bricks-advanced-themer' );

                $cat[$index]['label'] = $cat_label;

                if ( have_rows( 'field_63dd12891d1d9', 'bricks-advanced-themer' ) ) :

                    while ( have_rows( 'field_63dd12891d1d9', 'bricks-advanced-themer' ) ) :
                        the_row();

                        if( get_row_layout() == 'brxc_misc_fluid_variable' ){

                            $label = get_sub_field('brxc_misc_fluid_label', 'bricks-advanced-themer' );

                            $min = get_sub_field('brxc_misc_fluid_min_value', 'bricks-advanced-themer' );

                            $max = get_sub_field('brxc_misc_fluid_max_value', 'bricks-advanced-themer' );

                        } else {

                            $label = get_sub_field('brxc_misc_static_label', 'bricks-advanced-themer' );

                            $min = get_sub_field('brxc_misc_static_value', 'bricks-advanced-themer' );

                            $max = false;

                        }

                        $item = [];

                        $key = strtolower( preg_replace( '/\s+/', '-', esc_attr($label) ) );

                        if ( isset($prefix) && $prefix ) {

                            $value = 'var(--' . esc_attr($prefix) . '-' . $key . ')'; 
                        
                        } else {

                            $value = 'var(--' . $key . ')'; 

                        }

                        $item['key'] = $key;
                        $item['value'] = $value;
                        $item['min'] = $min;
                        $item['max'] = $max;
                        $cat[$index]['items'][] = $item;
                        
                    endwhile;
                    
                endif;

                $misc[] = $cat;

                $index++;

            endwhile;
                    
        endif;

    endwhile;
                    
endif;

ob_start();
if (Advanced_Themer_Bricks\AT__Helpers::is_classes_and_styles_tab_activated() && isset($brxc_acf_fields['class_features']) && !empty($brxc_acf_fields['class_features']) && is_array($brxc_acf_fields['class_features']) && in_array("variable-picker", $brxc_acf_fields['class_features'])):
    include_once \BRICKS_ADVANCED_THEMER_PATH . '/inc/builderPanels/css_variable_pickr_new.php';
endif;

if (Advanced_Themer_Bricks\AT__Helpers::is_elements_tab_activated() && isset($brxc_acf_fields['element_features']) && !empty($brxc_acf_fields['element_features']) && is_array($brxc_acf_fields['element_features']) && in_array("grid-builder", $brxc_acf_fields['element_features'])):
    include_once \BRICKS_ADVANCED_THEMER_PATH . '/inc/builderPanels/grid_ui.php';
endif;

if (Advanced_Themer_Bricks\AT__Helpers::is_elements_tab_activated() && isset($brxc_acf_fields['element_features']) && !empty($brxc_acf_fields['element_features']) && is_array($brxc_acf_fields['element_features']) && in_array("box-shadow-generator", $brxc_acf_fields['element_features'])):
    include_once \BRICKS_ADVANCED_THEMER_PATH . '/inc/builderPanels/box_shadow_generator.php';
endif;

if (Advanced_Themer_Bricks\AT__Helpers::is_classes_and_styles_tab_activated() && isset($brxc_acf_fields['class_features']) && !empty($brxc_acf_fields['class_features']) && is_array($brxc_acf_fields['class_features']) && in_array("plain-classes", $brxc_acf_fields['class_features'])):
    include_once \BRICKS_ADVANCED_THEMER_PATH . '/inc/builderPanels/plain_classes_new.php';
endif;
if (Advanced_Themer_Bricks\AT__Helpers::is_structure_panel_tab_activated() && isset( $brxc_acf_fields['structure_panel_contextual_menu']) && !empty( $brxc_acf_fields['structure_panel_contextual_menu']) && is_array( $brxc_acf_fields['structure_panel_contextual_menu']) && in_array("class-converter",  $brxc_acf_fields['structure_panel_contextual_menu'])):
    include_once \BRICKS_ADVANCED_THEMER_PATH . '/inc/builderPanels/class_converter.php';
endif;
if (Advanced_Themer_Bricks\AT__Helpers::is_ai_category_activated() && isset($brxc_acf_fields['openai_api_key']) && $brxc_acf_fields['openai_api_key'] === '0'):
    include_once \BRICKS_ADVANCED_THEMER_PATH . '/inc/builderPanels/openai_text_new.php';
    include_once \BRICKS_ADVANCED_THEMER_PATH . '/inc/builderPanels/global_openai_text_new.php';
endif;

if ((Advanced_Themer_Bricks\AT__Helpers::is_structure_panel_tab_activated() && isset( $brxc_acf_fields['structure_panel_contextual_menu']) && !empty( $brxc_acf_fields['structure_panel_contextual_menu']) && is_array( $brxc_acf_fields['structure_panel_contextual_menu']) && in_array("style-overview",  $brxc_acf_fields['structure_panel_contextual_menu'])) 
|| (Advanced_Themer_Bricks\AT__Helpers::is_elements_tab_activated() && isset($brxc_acf_fields['element_features']) && !empty($brxc_acf_fields['element_features']) && is_array($brxc_acf_fields['element_features']) && in_array("style-overview-shortcut", $brxc_acf_fields['element_features']))) :
    include_once \BRICKS_ADVANCED_THEMER_PATH . '/inc/builderPanels/style_overview.php';
endif;

// Global Features
if (Advanced_Themer_Bricks\AT__Helpers::is_global_features_tab_activated() && isset( $brxc_acf_fields['enable_global_features']) && !empty( $brxc_acf_fields['enable_global_features']) && is_array( $brxc_acf_fields['enable_global_features']) && in_array("global-query",  $brxc_acf_fields['enable_global_features'])):
    include_once \BRICKS_ADVANCED_THEMER_PATH . '/inc/builderPanels/query_manager.php';
endif;
if (Advanced_Themer_Bricks\AT__Helpers::is_templates_tab_activated() && isset($brxc_acf_fields['templates_features']) && !empty($brxc_acf_fields['templates_features']) && is_array($brxc_acf_fields['templates_features']) && in_array("quick-save", $brxc_acf_fields['templates_features'])):
    include_once \BRICKS_ADVANCED_THEMER_PATH . '/inc/builderPanels/save_template.php';
endif;
if(Advanced_Themer_Bricks\AT__Helpers::is_structure_panel_tab_activated() && isset( $brxc_acf_fields['structure_panel_icons']) && !empty( $brxc_acf_fields['structure_panel_icons']) && is_array( $brxc_acf_fields['structure_panel_icons']) && in_array("structure-helper",  $brxc_acf_fields['structure_panel_icons'])){
    include_once \BRICKS_ADVANCED_THEMER_PATH . '/inc/builderPanels/structure_helper.php';
}
if (Advanced_Themer_Bricks\AT__Helpers::is_global_features_tab_activated() && isset( $brxc_acf_fields['enable_global_features']) && !empty( $brxc_acf_fields['enable_global_features']) && is_array( $brxc_acf_fields['enable_global_features']) && in_array("variable-manager",  $brxc_acf_fields['enable_global_features'])):
    include_once \BRICKS_ADVANCED_THEMER_PATH . '/inc/builderPanels/css_variable_manager.php';
endif;
if (Advanced_Themer_Bricks\AT__Helpers::is_global_colors_category_activated()):
    include_once \BRICKS_ADVANCED_THEMER_PATH . '/inc/builderPanels/color_manager.php';
endif;


if (Advanced_Themer_Bricks\AT__Helpers::is_advanced_css_tab_activated()):
    include_once \BRICKS_ADVANCED_THEMER_PATH . '/inc/builderPanels/advanced_css_new.php';
endif;

if (
    (Advanced_Themer_Bricks\AT__Helpers::is_classes_and_styles_tab_activated() && isset($brxc_acf_fields['class_features']) && !empty($brxc_acf_fields['class_features']) && is_array($brxc_acf_fields['class_features']) && in_array("extend-classes", $brxc_acf_fields['class_features'])) ||
    (Advanced_Themer_Bricks\AT__Helpers::is_structure_panel_tab_activated() && isset( $brxc_acf_fields['structure_panel_contextual_menu']) && !empty( $brxc_acf_fields['structure_panel_contextual_menu']) && is_array( $brxc_acf_fields['structure_panel_contextual_menu']) && in_array("extend-classes-and-styles",  $brxc_acf_fields['structure_panel_contextual_menu']))
):
    include_once \BRICKS_ADVANCED_THEMER_PATH . '/inc/builderPanels/extend.php';
endif;
if (
    (Advanced_Themer_Bricks\AT__Helpers::is_classes_and_styles_tab_activated() && isset($brxc_acf_fields['class_features']) && !empty($brxc_acf_fields['class_features']) && is_array($brxc_acf_fields['class_features']) && in_array("find-and-replace", $brxc_acf_fields['class_features'])) ||
    (Advanced_Themer_Bricks\AT__Helpers::is_structure_panel_tab_activated() && isset( $brxc_acf_fields['structure_panel_contextual_menu']) && !empty( $brxc_acf_fields['structure_panel_contextual_menu']) && is_array( $brxc_acf_fields['structure_panel_contextual_menu']) && in_array("find-and-replace-styles",  $brxc_acf_fields['structure_panel_contextual_menu']))
):
    include_once \BRICKS_ADVANCED_THEMER_PATH . '/inc/builderPanels/find_replace.php';
endif;

if (Advanced_Themer_Bricks\AT__Helpers::is_global_features_tab_activated() && isset( $brxc_acf_fields['enable_global_features']) && !empty( $brxc_acf_fields['enable_global_features']) && is_array( $brxc_acf_fields['enable_global_features']) && in_array("class-manager",  $brxc_acf_fields['enable_global_features'])):
    include_once \BRICKS_ADVANCED_THEMER_PATH . '/inc/builderPanels/class_manager.php';
endif;

// Extras
if (Advanced_Themer_Bricks\AT__Helpers::is_extras_category_activated() && isset($brxc_acf_fields['enable_extras_features']) && !empty($brxc_acf_fields['enable_extras_features']) && is_array($brxc_acf_fields['enable_extras_features']) && in_array('resources', $brxc_acf_fields['enable_extras_features'])):
    include_once \BRICKS_ADVANCED_THEMER_PATH . '/inc/builderPanels/resources_new.php';
endif;

if (Advanced_Themer_Bricks\AT__Helpers::is_extras_category_activated() && isset($brxc_acf_fields['enable_extras_features']) && !empty($brxc_acf_fields['enable_extras_features']) && is_array($brxc_acf_fields['enable_extras_features']) && in_array('brickslabs', $brxc_acf_fields['enable_extras_features'])):
    include_once \BRICKS_ADVANCED_THEMER_PATH . '/inc/builderPanels/brickslabs.php';
endif;

$output = ob_get_clean();
echo $output;
