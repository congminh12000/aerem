<?php
namespace Advanced_Themer_Bricks;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class AT__Global_Variables{

    public static function clamp_builder($pixelsPerRem, $minWidthPx, $maxWidthPx, $minFontSize, $maxFontSize ) {

        global $brxc_acf_fields;

        $unit = $brxc_acf_fields['clamp_unit'];
        $minWidth = $minWidthPx / $pixelsPerRem;
        $maxWidth = $maxWidthPx / $pixelsPerRem;
      
        $slope = ( $maxFontSize - $minFontSize ) / ( $maxWidth - $minWidth );
        $yAxisIntersection = -$minWidth * $slope + $minFontSize;
      
        return 'clamp(' . $minFontSize / $pixelsPerRem . 'rem, ' . round($yAxisIntersection / $pixelsPerRem, 4) . 'rem + ' . round($slope / $pixelsPerRem * 100, 4) . $unit . ', ' . $maxFontSize / $pixelsPerRem. 'rem)';
    }

    public static function load_spacing_variables_on_frontend() {

        global $brxc_acf_fields;
        
        $custom_css = '';
        if ( have_rows( 'field_6445ab9f3d498', 'bricks-advanced-themer' ) ) :
            while ( have_rows( 'field_6445ab9f3d498', 'bricks-advanced-themer' ) ) :
                the_row();
                if ( have_rows( 'field_63a6a51731bbb', 'bricks-advanced-themer' ) ) :

                    $prefix = $brxc_acf_fields['global_prefix'];
                    $base_font = $brxc_acf_fields['base_font'];
                    $min_vw = $brxc_acf_fields['min_vw'];
                    $max_vw = $brxc_acf_fields['max_vw'];

                    while ( have_rows( 'field_63a6a51731bbb', 'bricks-advanced-themer' ) ) :
                        the_row();

                        $label = get_sub_field('brxc_spacing_label', 'bricks-advanced-themer' );
                        $min_value = get_sub_field('brxc_spacing_min_value', 'bricks-advanced-themer' );
                        $max_value = get_sub_field('brxc_spacing_max_value', 'bricks-advanced-themer' );

                        if ( isset($prefix) && $prefix ) {
                            
                            $custom_css .= '--' . $prefix . '-' . strtolower( preg_replace( '/\s+/', '-', $label ) ) . ': ' . self::clamp_builder($base_font, $min_vw, $max_vw, (float) $min_value, (float) $max_value) . ';';
                        
                        } else {

                        $custom_css .= '--' . strtolower( preg_replace( '/\s+/', '-', $label ) ) . ': ' . self::clamp_builder($base_font, $min_vw, $max_vw, (float) $min_value, (float) $max_value) . ';';

                        }
                        
                    endwhile;
                    
                endif;

            endwhile;
            
        endif;

        return $custom_css;

    }

    public static function load_border_radius_variables_on_frontend() {

        global $brxc_acf_fields;
        
        $custom_css = '';

        if ( have_rows( 'field_6445ab9f3d498', 'bricks-advanced-themer' ) ) :
            while ( have_rows( 'field_6445ab9f3d498', 'bricks-advanced-themer' ) ) :
                the_row();
                if ( have_rows( 'field_63c8f17f5e2ed', 'bricks-advanced-themer' ) ) :

                    $prefix = $brxc_acf_fields['global_prefix'];
                    $base_font = $brxc_acf_fields['base_font'];
                    $min_vw = $brxc_acf_fields['min_vw'];
                    $max_vw = $brxc_acf_fields['max_vw'];

                    while ( have_rows( 'field_63c8f17f5e2ed', 'bricks-advanced-themer' ) ) :
                        the_row();

                        $label = get_sub_field('brxc_border_label', 'bricks-advanced-themer' );
                        $min_value = get_sub_field('brxc_border_min_value', 'bricks-advanced-themer' );
                        $max_value = get_sub_field('brxc_border_max_value', 'bricks-advanced-themer' );

                        if ( isset($prefix) && $prefix ) {
                            
                            $custom_css .= '--' . $prefix . '-' . strtolower( preg_replace( '/\s+/', '-', $label ) ) . ': ' . self::clamp_builder($base_font, $min_vw, $max_vw, (float) $min_value, (float) $max_value) . ';';
                        
                        } else {

                        $custom_css .= '--' . strtolower( preg_replace( '/\s+/', '-', $label ) ) . ': ' . self::clamp_builder($base_font, $min_vw, $max_vw, (float) $min_value, (float) $max_value) . ';';

                        }
                        
                    endwhile;
                    
                endif;

            endwhile;
            
        endif;

        return $custom_css;

    }

    public static function load_border_variables_on_frontend() {

        global $brxc_acf_fields;
        
        $custom_css = '';

        if ( have_rows( 'field_6445ab9f3d498', 'bricks-advanced-themer' ) ) :
            while ( have_rows( 'field_6445ab9f3d498', 'bricks-advanced-themer' ) ) :
                the_row();
                if ( have_rows( 'field_63c8f17ytr545', 'bricks-advanced-themer' ) ) :

                    $prefix = $brxc_acf_fields['global_prefix'];

                    while ( have_rows( 'field_63c8f17ytr545', 'bricks-advanced-themer' ) ) :
                        the_row();

                        $label = get_sub_field('brxc_border_simple_label', 'bricks-advanced-themer' );
                        $value = get_sub_field('brxc_border_simple_value', 'bricks-advanced-themer' );

                        if ( isset($prefix) && $prefix ) {
                            
                            $custom_css .= '--' . $prefix . '-' . strtolower( preg_replace( '/\s+/', '-', $label ) ) . ': ' . esc_attr($value) . ';';
                        
                        } else {

                        $custom_css .= '--' . strtolower( preg_replace( '/\s+/', '-', $label ) ) . ': ' .esc_attr($value) . ';';

                        }
                        
                    endwhile;
                    
                endif;

            endwhile;
            
        endif;

        return $custom_css;

    }

    public static function load_box_shadow_variables_on_frontend() {

        global $brxc_acf_fields;
        
        $custom_css = '';

        if ( have_rows( 'field_6445ab9f3d498', 'bricks-advanced-themer' ) ) :
            while ( have_rows( 'field_6445ab9f3d498', 'bricks-advanced-themer' ) ) :
                the_row();
                if ( have_rows( 'field_63c8f17s4stt6', 'bricks-advanced-themer' ) ) :

                    $prefix = $brxc_acf_fields['global_prefix'];

                    while ( have_rows( 'field_63c8f17s4stt6', 'bricks-advanced-themer' ) ) :
                        the_row();

                        $label = get_sub_field('brxc_box_shadow_label', 'bricks-advanced-themer' );
                        $value = get_sub_field('brxc_box_shadow_value', 'bricks-advanced-themer' );

                        if ( isset($prefix) && $prefix ) {
                            
                            $custom_css .= '--' . $prefix . '-' . strtolower( preg_replace( '/\s+/', '-', $label ) ) . ': ' . esc_attr($value) . ';';
                        
                        } else {

                        $custom_css .= '--' . strtolower( preg_replace( '/\s+/', '-', $label ) ) . ': ' . esc_attr($value) . ';';

                        }
                        
                    endwhile;
                    
                endif;

            endwhile;
            
        endif;

        return $custom_css;

    }

    public static function load_width_variables_on_frontend() {

        global $brxc_acf_fields;

        $custom_css = '';
        if ( have_rows( 'field_6445ab9f3d498', 'bricks-advanced-themer' ) ) :
            while ( have_rows( 'field_6445ab9f3d498', 'bricks-advanced-themer' ) ) :

                the_row();
                if ( have_rows( 'field_63c8f17ppo69i', 'bricks-advanced-themer' ) ) :

                    $prefix = $brxc_acf_fields['global_prefix'];
                    $base_font = $brxc_acf_fields['base_font'];
                    $min_vw = $brxc_acf_fields['min_vw'];
                    $max_vw = $brxc_acf_fields['max_vw'];

                    while ( have_rows( 'field_63c8f17ppo69i', 'bricks-advanced-themer' ) ) :
                        the_row();

                        $label = get_sub_field('brxc_width_label', 'bricks-advanced-themer' );
                        $min_value = get_sub_field('brxc_width_min_value', 'bricks-advanced-themer' );
                        $max_value = get_sub_field('brxc_width_max_value', 'bricks-advanced-themer' );

                        if ( isset($prefix) && $prefix ) {
                            
                            $custom_css .= '--' . $prefix . '-' . strtolower( preg_replace( '/\s+/', '-', $label ) ) . ': ' . self::clamp_builder($base_font, $min_vw, $max_vw, (float) $min_value, (float) $max_value) . ';';
                        
                        } else {

                        $custom_css .= '--' . strtolower( preg_replace( '/\s+/', '-', $label ) ) . ': ' . self::clamp_builder($base_font, $min_vw, $max_vw, (float) $min_value, (float) $max_value) . ';';

                        }
                        
                    endwhile;

                endif;

            endwhile;

        endif;

        return $custom_css;

    }

    public static function load_typography_variables_on_frontend() {

        global $brxc_acf_fields;

        $custom_css = '';
        if ( have_rows( 'field_6445ab9f3d498', 'bricks-advanced-themer' ) ) :
            while ( have_rows( 'field_6445ab9f3d498', 'bricks-advanced-themer' ) ) :

                the_row();
                if ( have_rows( 'field_63a6a58831bbe', 'bricks-advanced-themer' ) ) :

                    $prefix = $brxc_acf_fields['global_prefix'];
                    $base_font = $brxc_acf_fields['base_font'];
                    $min_vw = $brxc_acf_fields['min_vw'];
                    $max_vw = $brxc_acf_fields['max_vw'];

                    while ( have_rows( 'field_63a6a58831bbe', 'bricks-advanced-themer' ) ) :
                        the_row();

                        $label = get_sub_field('brxc_typography_label', 'bricks-advanced-themer' );
                        $min_value = get_sub_field('brxc_typography_min_value', 'bricks-advanced-themer' );
                        $max_value = get_sub_field('brxc_typography_max_value', 'bricks-advanced-themer' );

                        if ( isset($prefix) && $prefix ) {
                            
                            $custom_css .= '--' . $prefix . '-' . strtolower( preg_replace( '/\s+/', '-', $label ) ) . ': ' . self::clamp_builder($base_font, $min_vw, $max_vw, (float) $min_value, (float) $max_value) . ';';
                        
                        } else {

                        $custom_css .= '--' . strtolower( preg_replace( '/\s+/', '-', $label ) ) . ': ' . self::clamp_builder($base_font, $min_vw, $max_vw, (float) $min_value, (float) $max_value) . ';';

                        }
                        
                    endwhile;

                endif;

            endwhile;

        endif;

        return $custom_css;

    }

    public static function load_misc_variables_on_frontend() {

        global $brxc_acf_fields;

        $custom_css = '';

        //Category

        if ( have_rows( 'field_6445ab9f3d498', 'bricks-advanced-themer' ) ) :
            while ( have_rows( 'field_6445ab9f3d498', 'bricks-advanced-themer' ) ) :
                the_row();

                if ( have_rows( 'field_64066a105f7ec', 'bricks-advanced-themer' ) ) :

                    while ( have_rows( 'field_64066a105f7ec', 'bricks-advanced-themer' ) ) :

                        the_row();

                        // Flexible Content

                        if( have_rows('field_63dd12891d1d9', 'bricks-advanced-themer') ):

                            $prefix = $brxc_acf_fields['global_prefix'];
                            $base_font = $brxc_acf_fields['base_font'];
                            $min_vw = $brxc_acf_fields['min_vw'];
                            $max_vw = $brxc_acf_fields['max_vw'];

                            // Loop through rows.
                            while ( have_rows('field_63dd12891d1d9', 'bricks-advanced-themer') ) : the_row();
                        
                                // Case: Paragraph layout.
                                if( get_row_layout() == 'brxc_misc_fluid_variable' ):
                                    $label = get_sub_field('brxc_misc_fluid_label', 'bricks-advanced-themer' );
                                    $min_value = get_sub_field('brxc_misc_fluid_min_value', 'bricks-advanced-themer' );
                                    $max_value = get_sub_field('brxc_misc_fluid_max_value', 'bricks-advanced-themer' );

                                    if ( isset($prefix) && $prefix ) {
                                        
                                        $custom_css .= '--' . $prefix . '-' . strtolower( preg_replace( '/\s+/', '-', $label ) ) . ': ' . self::clamp_builder($base_font, $min_vw, $max_vw, (float) $min_value, (float) $max_value) . ';';
                                    
                                    } else {

                                    $custom_css .= '--' . strtolower( preg_replace( '/\s+/', '-', $label ) ) . ': ' . self::clamp_builder($base_font, $min_vw, $max_vw, (float) $min_value, (float) $max_value) . ';';

                                    }
                        
                                // Case: Download layout.
                                elseif( get_row_layout() == 'brxc_misc_static_variable' ): 
                                    $label = get_sub_field('brxc_misc_static_label', 'bricks-advanced-themer' );
                                    $value = get_sub_field('brxc_misc_static_value', 'bricks-advanced-themer' );

                                    if ( isset($prefix) && $prefix ) {
                                        
                                        $custom_css .= '--' . $prefix . '-' . strtolower( preg_replace( '/\s+/', '-', $label ) ) . ': ' . $value . ';';
                                    
                                    } else {

                                        $custom_css .= '--' . strtolower( preg_replace( '/\s+/', '-', $label ) ) . ': ' . $value . ';';

                                    }
                        
                                endif;
                        
                            // End Flexible Content
                            endwhile;

                        endif;

                    // End Repeater
                    endwhile;

                endif;

            endwhile;
            
        endif;
        
        return $custom_css;
    }

    public static function populate_variables_object() {
        $obj = [];
        if ( have_rows( 'field_6445ab9f3d498', 'bricks-advanced-themer' ) ) :
            while ( have_rows( 'field_6445ab9f3d498', 'bricks-advanced-themer' ) ) :
                the_row();

                // Typography
                $order = 0;

                if (  AT__Helpers::is_typography_tab_activated() && have_rows( 'field_63a6a58831bbe', 'bricks-advanced-themer' ) ) :
                    while ( have_rows( 'field_63a6a58831bbe', 'bricks-advanced-themer' ) ) :
                        the_row();

                        $label = get_sub_field('brxc_typography_label', 'bricks-advanced-themer' );
                        $min_value = get_sub_field('brxc_typography_min_value', 'bricks-advanced-themer' );
                        $max_value = get_sub_field('brxc_typography_max_value', 'bricks-advanced-themer' );
                        $obj[] = [
                            'id'     => AT__Helpers::generate_unique_string(6),
                            'name'   => $label,
                            'order'  => $order,
                            'group'  => 'typography',
                            'type'   => 'clamp',
                            'min'    => $min_value,
                            'max'    => $max_value,
                        ];

                        $order++;
                        
                    endwhile;
                endif;
    
                // Spacing

                $order = 0;

                if ( AT__Helpers::is_spacing_tab_activated() && have_rows( 'field_63a6a51731bbb', 'bricks-advanced-themer' ) ) :
                    while ( have_rows( 'field_63a6a51731bbb', 'bricks-advanced-themer' ) ) :
                        the_row();
    
                        $label = get_sub_field('brxc_spacing_label', 'bricks-advanced-themer' );
                        $min_value = get_sub_field('brxc_spacing_min_value', 'bricks-advanced-themer' );
                        $max_value = get_sub_field('brxc_spacing_max_value', 'bricks-advanced-themer' );
                        $obj[] = [
                            'id'     => AT__Helpers::generate_unique_string(6),
                            'name'   => $label,
                            'order'  => $order,
                            'group'  => 'spacing',
                            'type'   => 'clamp',
                            'min'    => $min_value,
                            'max'    => $max_value,
                        ];

                        $order++;
                        
                    endwhile;
                endif;

                // Border-radius

                $order = 0;

                if ( AT__Helpers::is_border_radius_tab_activated() && have_rows( 'field_63c8f17f5e2ed', 'bricks-advanced-themer' ) ) :
                    while ( have_rows( 'field_63c8f17f5e2ed', 'bricks-advanced-themer' ) ) :
                        the_row();

                        $label = get_sub_field('brxc_border_label', 'bricks-advanced-themer' );
                        $min_value = get_sub_field('brxc_border_min_value', 'bricks-advanced-themer' );
                        $max_value = get_sub_field('brxc_border_max_value', 'bricks-advanced-themer' );
                        $obj[] = [
                            'id'     => AT__Helpers::generate_unique_string(6),
                            'name'   => $label,
                            'order'  => $order,
                            'group'  => 'border-radius',
                            'type'   => 'clamp',
                            'min'    => $min_value,
                            'max'    => $max_value,
                        ];

                        $order++;
                        
                    endwhile; 
                endif;

                // Border
                $order = 0;

                if ( AT__Helpers::is_border_tab_activated() && have_rows( 'field_63c8f17ytr545', 'bricks-advanced-themer' ) ) :
                    while ( have_rows( 'field_63c8f17ytr545', 'bricks-advanced-themer' ) ) :
                        the_row();

                        $label = get_sub_field('brxc_border_simple_label', 'bricks-advanced-themer' );
                        $value = get_sub_field('brxc_border_simple_value', 'bricks-advanced-themer' );
                        $obj[] = [
                            'id'     => AT__Helpers::generate_unique_string(6),
                            'name'   => $label,
                            'order'  => $order,
                            'group'  => 'border',
                            'type'   => 'static',
                            'value'  => $value,
                        ];

                        $order++;
                        
                    endwhile;
                endif;

                // Box-shadow

                $order = 0;

                if ( AT__Helpers::is_box_shadow_tab_activated() && have_rows( 'field_63c8f17s4stt6', 'bricks-advanced-themer' ) ) :
                    while ( have_rows( 'field_63c8f17s4stt6', 'bricks-advanced-themer' ) ) :
                        the_row();

                        $label = get_sub_field('brxc_box_shadow_label', 'bricks-advanced-themer' );
                        $value = get_sub_field('brxc_box_shadow_value', 'bricks-advanced-themer' );
                        $obj[] = [
                            'id'     => AT__Helpers::generate_unique_string(6),
                            'name'   => $label,
                            'order'  => $order,
                            'group'  => 'box-shadow',
                            'type'   => 'static',
                            'value'  => $value,
                        ];

                        $order++;
                        
                    endwhile;
                endif;

                // Width
                $order = 0;

                if ( AT__Helpers::is_width_tab_activated() && have_rows( 'field_63c8f17ppo69i', 'bricks-advanced-themer' ) ) :
                    while ( have_rows( 'field_63c8f17ppo69i', 'bricks-advanced-themer' ) ) :
                        the_row();

                        $label = get_sub_field('brxc_width_label', 'bricks-advanced-themer' );
                        $min_value = get_sub_field('brxc_width_min_value', 'bricks-advanced-themer' );
                        $max_value = get_sub_field('brxc_width_max_value', 'bricks-advanced-themer' );
                        $obj[] = [
                            'id'     => AT__Helpers::generate_unique_string(6),
                            'name'   => $label,
                            'order'  => $order,
                            'group'  => 'width',
                            'type'   => 'clamp',
                            'min'    => $min_value,
                            'max'    => $max_value,
                        ];

                        $order++;
                        
                    endwhile;
                endif;

                // Custom Variables

                if ( AT__Helpers::is_custom_variables_tab_activated() && have_rows( 'field_64066a105f7ec', 'bricks-advanced-themer' ) ) :
                    while ( have_rows( 'field_64066a105f7ec', 'bricks-advanced-themer' ) ) :
                        the_row();

                        $group = get_sub_field('brxc_misc_category_label', 'bricks-advanced-themer');
                        // Flexible Content
                        
                        $order = 0;

                        if( have_rows('field_63dd12891d1d9', 'bricks-advanced-themer') ):
                            while ( have_rows('field_63dd12891d1d9', 'bricks-advanced-themer') ) : the_row();
    
                                // Case: Fluid
                                if( get_row_layout() == 'brxc_misc_fluid_variable' ):
                                    $label = get_sub_field('brxc_misc_fluid_label', 'bricks-advanced-themer' );
                                    $min_value = get_sub_field('brxc_misc_fluid_min_value', 'bricks-advanced-themer' );
                                    $max_value = get_sub_field('brxc_misc_fluid_max_value', 'bricks-advanced-themer' );
                                    $obj[] = [
                                        'id'     => AT__Helpers::generate_unique_string(6),
                                        'name'   => $label,
                                        'order'  => $order,
                                        'group'  => $group,
                                        'type'   => 'clamp',
                                        'min'    => $min_value,
                                        'max'    => $max_value,
                                    ];
                        
                                // Case: Static
                                elseif( get_row_layout() == 'brxc_misc_static_variable' ): 
                                    $label = get_sub_field('brxc_misc_static_label', 'bricks-advanced-themer' );
                                    $value = get_sub_field('brxc_misc_static_value', 'bricks-advanced-themer' );
                                    $obj[] = [
                                        'id'     => AT__Helpers::generate_unique_string(6),
                                        'name'   => $label,
                                        'order'  => $order,
                                        'group'  => $group,
                                        'type'   => 'static',
                                        'value'  => $value,
                                    ];
                        
                                endif;

                                $order++;
                                
                            // End Flexible Content
                            endwhile;
                        endif;

                    // End Repeater
                    endwhile;
                endif;

                
                // Filters
                $value = '';
                $imported_vars_from_filter = apply_filters( 'at/css_var_framework/import_vars', $value);
                if(isset($imported_vars_from_filter) && !empty($imported_vars_from_filter) && is_array($imported_vars_from_filter) ){
                    foreach ( $imported_vars_from_filter as $cat ) {
                        if(!isset($cat) || empty($cat) || !is_array($cat)){
                            return;
                        }
                        
                        if(array_key_exists('label', $cat) && isset($cat['label']) && !empty($cat['label'])){
                            $group = $cat['label'];
                        }

                        if ( array_key_exists('items', $cat) && isset($cat['items']) && is_array( $cat['items'] ) && !empty( $cat['items'] ) ){ 
                            $order = 0;
                            foreach ( $cat['items'] as $value ) {
                                if(isset($value) && !empty($value)){
                                    $obj[] = [
                                        'id'     => AT__Helpers::generate_unique_string(6),
                                        'name'   => $value,
                                        'order'  => $order,
                                        'group'  => $group,
                                        'type'   => 'filters',
                                        'value'  => false,
                                    ];
                                }
                                $order++;
                            }
                        }
                    }
                } 
                
                // Import Framework
                if(AT__Helpers::is_import_framework_tab_activated()){
                    
                    // From Database
                    $custom_format = get_sub_field('field_6399a28440091', 'bricks-advanced-themer' );
                    $json_from_db_label = get_sub_field('field_63bdedscc0k3l', 'bricks-advanced-themer' );
                    $json_from_db = get_sub_field('field_64065d4ffp9c6', 'bricks-advanced-themer' );

                    if( $custom_format === "database" && isset($json_from_db) && !empty($json_from_db) ){
                        $jsonString = get_sub_field('field_64065d4ffp9c6', 'bricks-advanced-themer' );
                        $jsonObj = json_decode($jsonString);
                        if (isset($jsonObj) && !empty($jsonObj) && is_object($jsonObj)){
                            foreach ($jsonObj as $category => $items) {
                                if (isset($items) && !empty($items) && is_array($items)){
                                    $order = 0;
                                    foreach ($items as $item) {
                                        $obj[] = [
                                            'id'     => AT__Helpers::generate_unique_string(6),
                                            'name'   => $item,
                                            'order'  => $order,
                                            'group'  => $category,
                                            'type'   => 'custom',
                                            'value'  => false,
                                        ];
                                        $order++;
                                    }
                                }
                            }
                        }
                    }

                    // Repeater
                    else if ( $custom_format === "json" && have_rows('field_63b4600putac1', 'bricks-advanced-themer' ) ){
                        while( have_rows('field_63b4600putac1', 'bricks-advanced-themer' ) ) : the_row();
                            $label = get_sub_field('field_63bdeds216ac3', 'bricks-advanced-themer' );
                            $file = get_sub_field('field_6334dcx216ac7', 'bricks-advanced-themer' );
                            $jsonString = AT__Helpers::read_file_contents($file);
                            if ($jsonString !== false){

                                $jsonObj = json_decode($jsonString);

                                if (isset($jsonObj) && !empty($jsonObj) && is_object($jsonObj)){
                                    foreach ($jsonObj as $category => $items) {
                                        
                                        if (isset($items) && !empty($items) && is_array($items)){
                                            $order = 0;
                                            foreach ($items as $item) {
                                                $obj[] = [
                                                    'id'     => AT__Helpers::generate_unique_string(6),
                                                    'name'   => $item,
                                                    'order'  => $order,
                                                    'group'  => $category,
                                                    'type'   => 'custom',
                                                    'value'  => false,
                                                ];
                                                $order++;
                                            }
                                        }
                                    }
                                }
                            }
                        endwhile;
                    }
                }
                
   
    
            endwhile;
        endif;
        return json_encode($obj);

    }

}

