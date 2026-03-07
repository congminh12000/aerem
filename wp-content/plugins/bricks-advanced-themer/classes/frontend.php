<?php
namespace Advanced_Themer_Bricks;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class AT__Frontend{

    public static function generate_css_for_frontend(){

        global $brxc_acf_fields;

        global $brxc_custom_inline_styles;


        if(!isset($brxc_acf_fields['theme_settings_tabs']) || empty($brxc_acf_fields['theme_settings_tabs']) || !is_array($brxc_acf_fields['theme_settings_tabs'])) {

            return;

        }

        $custom_css = ':root,.brxc-light-colors{';

        if ($brxc_acf_fields['color_cpt_deprecated'] && AT__Helpers::is_global_colors_category_activated() ){
            

            $global_colors = AT__Global_Colors::load_converted_colors_variables_on_frontend();

            if($global_colors && is_array($global_colors) && !empty($global_colors)){

                $custom_css .= $global_colors[0];

            }


        }

        $custom_css .= '}:root{';

        if ( AT__Helpers::is_spacing_tab_activated()){

            $custom_css .= AT__Global_Variables::load_spacing_variables_on_frontend();

        }

        if ( AT__Helpers::is_border_tab_activated()){

            $custom_css .= AT__Global_Variables::load_border_variables_on_frontend();

        }

        if ( AT__Helpers::is_border_radius_tab_activated()){

            $custom_css .= AT__Global_Variables::load_border_radius_variables_on_frontend();

        }

        if ( AT__Helpers::is_box_shadow_tab_activated()){

            $custom_css .= AT__Global_Variables::load_box_shadow_variables_on_frontend();

        }

        if ( AT__Helpers::is_width_tab_activated()){

            $custom_css .= AT__Global_Variables::load_width_variables_on_frontend();

        }

        if ( AT__Helpers::is_typography_tab_activated()){

            $custom_css .= AT__Global_Variables::load_typography_variables_on_frontend();

        }

        if ( AT__Helpers::is_custom_variables_tab_activated()){

            $custom_css .= AT__Global_Variables::load_misc_variables_on_frontend();

        }

        // Theme Variables

        if(AT__Helpers::is_theme_variables_tab_activated()){
            
            $custom_css .= self::generate_theme_variables();
        }

        $custom_css .= '}';

        if ($brxc_acf_fields['color_cpt_deprecated'] && AT__Helpers::is_global_colors_category_activated() && isset($brxc_acf_fields['enable_dark_mode_on_frontend']) && $brxc_acf_fields['enable_dark_mode_on_frontend']){

            $global_colors = AT__Global_Colors::load_converted_colors_variables_on_frontend();

            if($global_colors && is_array($global_colors) && !empty($global_colors)){

                $custom_css .= 'html[data-theme="dark"],.brxc-dark-colors{';

                $custom_css .= $global_colors[1];

                $custom_css .= '}';
            
            }

        }

        return $custom_css;

    }

    public static function generate_theme_variables(){
        $settings = \Bricks\Theme_Styles::$active_settings;
        if(!isset($settings) || !is_array($settings) || !isset($settings['general']) || !is_array($settings['general']) || !isset($settings['general']['_cssVariables']) || !is_array($settings['general']['_cssVariables'])) return '';
        
        global $brxc_acf_fields;

        if (!isset($brxc_acf_fields['global_prefix'], $brxc_acf_fields['base_font'], $brxc_acf_fields['min_vw'], $brxc_acf_fields['max_vw'])) {
            return '';
        }

        $prefix = $brxc_acf_fields['global_prefix'];
        $base_font = $brxc_acf_fields['base_font'];
        $min_vw = $brxc_acf_fields['min_vw'];
        $max_vw = $brxc_acf_fields['max_vw'];

        $variables = $settings['general']['_cssVariables'];
        $custom_css = '';

        foreach($variables as $variable ){
            $name = preg_replace('/[^a-zA-Z0-9_-]+/', '-', strtolower(trim($variable['name'])));
            $type = $variable['type'];

            if(!$name || !$type) continue;

            if($type === "static" && isset($variable['value'])){
                $value = $variable['value'];

                if(!$value) continue;

                $final_prefix = isset($prefix) && !empty($prefix) ? $prefix . '-' : '';
                $custom_css .= '--' . $final_prefix . $name . ':' . $value . ';';

            } elseif ($type === "clamp" && isset($variable['min']) && isset($variable['max'])){
                $min = $variable['min'];
                $max = $variable['max'];

                if(!$min || !$max) continue;
                
                $clamp = AT__Global_Variables::clamp_builder($base_font, $min_vw, $max_vw, (float) $min, (float) $max);

                if(!$clamp) continue;

                $final_prefix = isset($prefix) && !empty($prefix) ? $prefix . '-' : '';
                $custom_css .= '--' . $final_prefix . $name . ':' . $clamp . ';';
            }
        }  

        return $custom_css;
    }

    public static function load_variables_on_frontend() {

        global $brxc_acf_fields;
        
        $custom_css = '';

        if( AT__Helpers::is_grids_tab_activated() ) {
            
            $custom_css = AT__Grid_Builder::grid_builder_classes();
        
        }

        // Don't enqueue inside the builder for Full Access only
        if ((bricks_is_builder() || bricks_is_builder_iframe()) && (class_exists('Bricks\Capabilities') && \Bricks\Capabilities::current_user_has_full_access() === true)){

            wp_add_inline_style( 'bricks-advanced-themer', wp_strip_all_tags(trim($custom_css) ) );

            return;
            
        }

        $custom_css .= self::generate_css_for_frontend();

        if ( AT__Helpers::is_global_colors_category_activated() === true && isset( $brxc_acf_fields['replace_gutenberg_palettes'] ) && $brxc_acf_fields['replace_gutenberg_palettes'] ){

            $custom_css .= self::bricks_colors_gutenberg();

        }

        if($custom_css !== ''){

            wp_add_inline_style( 'bricks-advanced-themer', wp_strip_all_tags(trim($custom_css) ) );

        }

    }

    public static function enqueue_gutenberg_colors_in_iframe(){
        global $brxc_acf_fields;
        
        if ( AT__Helpers::is_global_colors_category_activated() === false || !isset( $brxc_acf_fields['replace_gutenberg_palettes'] ) || !$brxc_acf_fields['replace_gutenberg_palettes'] ){

            return;

        }

        wp_enqueue_style('bricks-advanced-themer');

        
        $custom_css = '';

        if( AT__Helpers::is_grids_tab_activated() ) {
            
            $custom_css = AT__Grid_Builder::grid_builder_classes();
        
        }

        $custom_css .= self::generate_css_for_frontend();

        if ( AT__Helpers::is_global_colors_category_activated() === true && isset( $brxc_acf_fields['replace_gutenberg_palettes'] ) && $brxc_acf_fields['replace_gutenberg_palettes'] ){

            $custom_css .= self::bricks_colors_gutenberg();

        }

        if($custom_css !== ''){

            wp_add_inline_style( 'bricks-advanced-themer', wp_strip_all_tags(trim($custom_css) ) );

        }
    }

    public static function bricks_colors_gutenberg() {

        global $brxc_acf_fields;

        if ( AT__Helpers::is_global_colors_category_activated() === false || !isset( $brxc_acf_fields['replace_gutenberg_palettes'] ) || !$brxc_acf_fields['replace_gutenberg_palettes'] ){

            return;

        }
    	
        $gutenberg_colors_frontend_css = ".has-text-color{color: var(--brxc-gutenberg-color)}.has-background,.has-background-color{background-color: var(--brxc-gutenberg-bg-color)}.has-border,.has-border-color{border-color: var(--brxc-gutenberg-border-color)}";
        
    	$bricks_palettes = get_option(\BRICKS_DB_COLOR_PALETTE, []);

        if ( isset( $bricks_palettes ) && is_array( $bricks_palettes ) && !empty($bricks_palettes) ){

            foreach( $bricks_palettes as $bricks_palette ) {

                if ( isset( $bricks_palette['colors'] ) && is_array( $bricks_palette['colors'] ) && !empty($bricks_palette['colors']) ){

                    foreach( $bricks_palette['colors'] as $color ) {

                        $name = preg_replace('/[^a-zA-Z0-9_-]+/', '-', strtolower(trim($color['name'])));
                        $final_color = '';

                        foreach(['hex', 'rgb','hsl','raw'] as $format){
                            if( isset($color[$format] )){
                                $final_color = $color[$format];
                            }
                        }

                        $gutenberg_colors_frontend_css .= '[class*="has-' . _wp_to_kebab_case($name) . '-color"]{--brxc-gutenberg-color:' . $final_color . ';}[class*="has-' . _wp_to_kebab_case($name) . '-background-color"]{--brxc-gutenberg-bg-color:' . $final_color . ';}[class*="has-' . _wp_to_kebab_case($name) . '-border-color"]{--brxc-gutenberg-border-color:' . $final_color . ';}';

                    }

                }
            }

            $gutenberg_colors_frontend_css = wp_strip_all_tags(trim($gutenberg_colors_frontend_css));

            return $gutenberg_colors_frontend_css;

        }
    
    }

    public static function remove_default_gutenberg_presets() {

        global $brxc_acf_fields;
        
        if ( !isset( $brxc_acf_fields['remove_default_gutenberg_presets'] ) || !$brxc_acf_fields['remove_default_gutenberg_presets'] ){

           return;

        }

        remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
        remove_action( 'wp_footer', 'wp_enqueue_global_styles', 1 );
        remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );

    }

    public static function meta_theme_color_tag() {

        // Set control in the page settings
        add_filter( 'builder/settings/page/controls_data', function( $data ) {
            $data['controls']['metaThemeColorSeparator'] = [
                'group'       => 'general',
                'type'        => 'separator',
                'label'       => esc_html__( 'Theme Color', 'bricks' ),
                'description' => esc_html__( 'Add <meta name="theme-color"> to the head of this page.', 'bricks' ),
            ];
            $data['controls']['metaThemeColor'] = [
                'group'       => 'general',
                'type'        => 'color',
                'label'       => esc_html__( 'Meta Theme Color', 'bricks' ),
                'description' => esc_html__( 'The meta tag doesn\'t support CSS variables - choose one of the following format: HEX, RGBA, HSLA.', 'bricks' ),
              ];
           
            return $data;
        } );

        // Add the meta tag
        add_action('bricks_meta_tags', function(){

            global $brxc_acf_fields;

            $color = false;

            // Global Value (ACF)

            if( $brxc_acf_fields['global_meta_theme_color'] && isset($brxc_acf_fields['global_meta_theme_color']) ) {

                $color = $brxc_acf_fields['global_meta_theme_color'];

            } 

            // Page Value (Builder)

            $settings = \bricks\Database::$page_data['settings'];
            
            if( isset($settings) && isset($settings['metaThemeColor']) ) {

                if ( isset($settings['metaThemeColor']['rgb'])){

                    $color = $settings['metaThemeColor']['rgb'];
    
                } elseif ( isset($settings['metaThemeColor']['hsl'])){
    
                    $color = $settings['metaThemeColor']['hsl'];
    
                } elseif( isset($settings['metaThemeColor']['hex'])){
    
                    $color = $settings['metaThemeColor']['hex'];
    
                }

            }

            if(!$color) return;
            
            echo '<meta name="theme-color" content="' . $color . '" />';
            
            return;
        });
    
    }

}