<?php
namespace Advanced_Themer_Bricks;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class AT__Builder{
    public static function setup_query_controls( $control_options ) {
        $get_option = get_option('bricks_advanced_themer_builder_settings');

        if (!isset($get_option) || !is_array($get_option) || !isset($get_option['query_manager']) || !is_array($get_option['query_manager'])) {
            return $control_options;
        }

        $options = $get_option['query_manager'];

        foreach ($options as $settings){
            if (isset($settings['id']) && isset($settings['title'])) {
                $control_options['queryTypes'][$settings['id']] = esc_html__( $settings['title'] );
            }
        }

        return $control_options;

    }

    public static function maybe_run_new_queries( $results, $query_obj ) {
        $get_option = get_option('bricks_advanced_themer_builder_settings');
    
        if (!isset($get_option) || !is_array($get_option) || !isset($get_option['query_manager']) || !is_array($get_option['query_manager'])) {
            return $results;
        }
    
        $options = $get_option['query_manager'];
    
        foreach ($options as $settings){
            if (isset($settings['id']) && isset($settings['args'])) {
                if ($query_obj->object_type === $settings['id']) {
                    
                    $php_query_raw = $settings['args'];
    
                    $execute_user_code = function () use ( $php_query_raw ) {
                        $user_result = null; // Initialize a variable to capture the result of user code
        
                        // Capture user code output using output buffering
                        ob_start();
                        $user_result = eval( $php_query_raw ); // Execute the user code
                        ob_get_clean(); // Get the captured output
        
                        return $user_result; // Return the user code result
                    };
        
                    ob_start();
        
                    // Prepare & set error reporting
                    $error_reporting = error_reporting( E_ALL );
                    $display_errors  = ini_get( 'display_errors' );
                    ini_set( 'display_errors', 1 );
        
                    try {
                        $php_query = $execute_user_code();
                    } catch ( \Exception $error ) {
                        echo 'Exception: ' . $error->getMessage();
                        return $results;
                    } catch ( \ParseError $error ) {
                        echo 'ParseError: ' . $error->getMessage();
                        return $results;
                    } catch ( \Error $error ) {
                        echo 'Error: ' . $error->getMessage();
                        return $results;
                    }
        
                    // Reset error reporting
                    ini_set( 'display_errors', $display_errors );
                    error_reporting( $error_reporting );
        
                    // @see https://www.php.net/manual/en/function.eval.php
                    if ( version_compare( PHP_VERSION, '7', '<' ) && $php_query === false || ! empty( $error ) ) {
                        ob_end_clean();
                    } else {
                        ob_get_clean();
                    }
        
                    $posts_query = new \WP_Query( $php_query );
        
                    $results = $posts_query->posts;
                }
            }
        }
        
        return $results;
    }
    
    
    public static function setup_post_data( $loop_object, $loop_key, $query_obj ) {
        $get_option = get_option('bricks_advanced_themer_builder_settings');

        if (!isset($get_option) || !is_array($get_option) || !isset($get_option['query_manager']) || !is_array($get_option['query_manager'])) {
            return $loop_object;
        }

        $options = $get_option['query_manager'];

        foreach ($options as $settings){
            if (isset($settings['id'])) {
                if ($query_obj->object_type === $settings['id']) {
                    global $post;

                    if (isset($loop_object)) {
                        $post = get_post($loop_object);
                        setup_postdata($post);
                    }
                }
            }
        }

        return $loop_object;
    }

    public static function populate_grid_classes(){

        $grid_classes = [];

        if ( have_rows( 'field_63b59j871b209' , 'bricks-advanced-themer' ) ) :

            while ( have_rows( 'field_63b59j871b209' , 'bricks-advanced-themer' ) ) : the_row();

                if ( have_rows( 'field_63b48c6f1b20a', 'bricks-advanced-themer' ) ) :

                    while ( have_rows( 'field_63b48c6f1b20a', 'bricks-advanced-themer' ) ) :

                        the_row();

                        $name = get_sub_field('field_63b48c6f1b20b', 'bricks-advanced-themer' );

                        $grid_classes[] = $name;

                    endwhile;

                endif;

            endwhile;

        endif;

        return $grid_classes;

    }

    public static function populate_class_importer(){

        $total_classes = [];
        if ( have_rows( 'field_63b59j871b209' , 'bricks-advanced-themer' ) ) :

            while ( have_rows( 'field_63b59j871b209' , 'bricks-advanced-themer' ) ) : the_row();

                if ( have_rows( 'field_63b4bd5c16ac1', 'bricks-advanced-themer' ) ) :

                    while ( have_rows( 'field_63b4bd5c16ac1', 'bricks-advanced-themer' ) ) :

                        the_row();

                        $id_stylesheet = get_sub_field('field_63b4bd5c16ac2', 'bricks-advanced-themer' );

                        $is_url = get_sub_field('field_6406649wdr55cx', 'bricks-advanced-themer' );

                        $file = $is_url ? get_sub_field('field_63b4bd5drd51x', 'bricks-advanced-themer' ) : get_sub_field('field_63b4bdf216ac7', 'bricks-advanced-themer' );

                        $classes = AT__Class_Importer::extract_selectors_from_css($file);


                        if (isset($classes) && !empty($classes) && is_array($classes) ) {

                            foreach ( $classes as $class) {
            
                                $total_classes[] = str_replace(['.', '#'],'', esc_attr($class));
            
                            }

                        }


                    endwhile;

                endif;

            endwhile;

        endif;

        // Filter to add class: UNDOCUMENTED
        $value = '';
        $imported_classes_from_filter = apply_filters( 'at/imported_classes/import_classes', $value );
        if(isset($imported_classes_from_filter) && !empty($imported_classes_from_filter) && is_array($imported_classes_from_filter) ){
            $classes = array_unique($imported_classes_from_filter);
            foreach ( $classes as $class ) {
                if(isset($class) && !empty($class) && is_string($class)){
                    $total_classes[] = esc_attr($class);
                }
            }
        }
        return $total_classes;
    }

    public static function add_modal_after_body_wrapper() {

        if (!class_exists('Bricks\Capabilities')) {

            return;
        }

        global $brxc_acf_fields;

        if( ! bricks_is_builder() || bricks_is_builder_iframe() || !\Bricks\Capabilities::current_user_has_full_access() === true) return;

        $css = '';

        if(AT__Helpers::is_builder_tweaks_category_activated() && isset($brxc_acf_fields['element_features']) && !empty($brxc_acf_fields['element_features']) && is_array($brxc_acf_fields['element_features']) && in_array('pseudo-shortcut', $brxc_acf_fields['element_features']) ){
        // Show Open in new tab Icon
        $css .= '#bricks-panel #bricks-panel-element #bricks-panel-header{
            gap: 2px;
            padding-top: var(--builder-spacing);
        }
        #bricks-panel #bricks-panel-element #bricks-panel-header .actions {
            width: 100%;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(24px, 1fr));
            justify-content: space-between;
            width: 100%;
            gap: 5px;
            margin-bottom: 22px;
        }
        #bricks-panel #bricks-panel-element #bricks-panel-header .actions li {
           border-radius: var(--builder-border-radius);
           background-color: var(--builder-bg-2);
           min-width: 24px;
           width: 100%;
        }
        #bricks-panel #bricks-panel-element #bricks-panel-header .actions li:nth-of-type(1):after,
        #bricks-panel #bricks-panel-element #bricks-panel-header .actions li:nth-of-type(2):after,
        #bricks-panel #bricks-panel-element #bricks-panel-header .actions li:nth-of-type(3):after {
            right: unset;
            left: 0;
        }
        #bricks-panel #bricks-panel-element #bricks-panel-header input {
            height: auto;
            line-height: var(--builder-input-height);
        }
        #bricks-panel #bricks-panel-element #bricks-panel-header .actions {
            flex-wrap: wrap;
        }
        #bricks-panel #bricks-panel-element #bricks-panel-header .actions li.brxc-header-icon__before svg {
            transform: rotate(90deg);
            scale: 1.1;
         }
         
         #bricks-panel #bricks-panel-element #bricks-panel-header .actions li.brxc-header-icon__after svg {
            transform: rotate(-90deg);
            scale: 1.1;
         }';
        }
        if (AT__Helpers::is_global_features_tab_activated() && isset($brxc_acf_fields['enable_global_features']) && !empty($brxc_acf_fields['enable_global_features']) && is_array($brxc_acf_fields['enable_global_features']) && in_array('Open in a New Tab', $brxc_acf_fields['enable_global_features']) ) {  

            $css .= '#bricks-toolbar li.new-tab{display:flex!important;}';
        }

        // Hide Bricks Elements inside the builder
        $settings = $brxc_acf_fields['disable_bricks_elements'];
        $all_elements = $brxc_acf_fields['builder_elements'];
        
        if(isset($settings) && is_array($settings)){
            foreach($all_elements as $key){
                if(!in_array($key, $settings, true)){
                    $css .= '#bricks-panel-elements-categories ul.sortable-wrapper li.bricks-add-element[data-element-name="' . esc_attr($key) . '"] {display:none !important;}';;
                }
            }
        }

        // Masonry Template View
        if (AT__Helpers::is_templates_tab_activated() && isset($brxc_acf_fields['templates_features']) && !empty($brxc_acf_fields['templates_features']) && is_array($brxc_acf_fields['templates_features']) && in_array("masonry-layout", $brxc_acf_fields['templates_features'])){
            $css .= '#bricks-popup ul.bricks-layout-wrapper{
                column-count: 1;
                display: block;
                gap: 0;
            }
            #bricks-popup li.bricks-layout-item {
                width: 100% !important;
                break-inside: avoid;
            }
            #bricks-popup .bricks-layout-item-inner.has-img {
                height: 100% !important;
                min-height: 200px;
            }
            #bricks-popup.templates img {
                height: 100% !important;
            }
            @media screen and (min-width: 768px){
                #bricks-popup ul.bricks-layout-wrapper {
                    column-count: 2;
                }
            }
            @media screen and (min-width: 1200px){
                #bricks-popup ul.bricks-layout-wrapper {
                    column-count: 3;
                }
            }';
        }
        

        wp_add_inline_style('bricks-advanced-themer-builder', $css, 'after');
        
        // SASS INTEGRATION
        //
        //ADMINBRXC.globalSettings.enableSASSinSuperPowerCSS = '" . $brxc_acf_fields['enable_sass_superpower_css'] . "';
        //

        $option = get_option('bricks_advanced_themer_builder_settings');
        // Grid Guides
        if(isset($option['gridGuide']) ){
            $grid_guide_output = "JSON.parse('" . json_encode($option['gridGuide']) . "')";
        } else {
            $grid_guide_output = "false";
        }
        // Query Manager
        if(isset($option['query_manager']) ){
            $query_manager_output = json_encode($option['query_manager']);
        } else {
            $query_manager_output = json_encode([]);
        }

        // Query Manager Cats
        if(isset($option['query_manager_cats']) ){
            $query_manager_cats_output = json_encode($option['query_manager_cats']);
        } else {
            $query_manager_cats_output = json_encode([]);
        }


        wp_add_inline_script('bricks-builder', preg_replace( '/\s+/', '', "window.addEventListener('DOMContentLoaded', () => {
            ADMINBRXC.globalSettings.generalCats.gridGuide = " . $grid_guide_output . ";
            ADMINBRXC.globalSettings.generalCats.globalColorsPrefix = '" . $brxc_acf_fields['color_prefix'] . "';
            ADMINBRXC.globalSettings.generalCats.baseFontSize = " . $brxc_acf_fields['base_font']  . ";
            ADMINBRXC.globalSettings.generalCats.globalPrefix = '" . $brxc_acf_fields['global_prefix'] . "';
            ADMINBRXC.globalSettings.generalCats.minViewportWidth = " . $brxc_acf_fields['min_vw'] . ";
            ADMINBRXC.globalSettings.generalCats.maxViewportWidth = " . $brxc_acf_fields['max_vw'] . ";
            ADMINBRXC.globalSettings.generalCats.clampUnit = '" . $brxc_acf_fields['clamp_unit']  . "';
            ADMINBRXC.globalSettings.generalCats.cssVariables = JSON.parse('" . json_encode($brxc_acf_fields['css_variables_general']) . "');
            ADMINBRXC.globalSettings.generalCats.classesAndStyles = JSON.parse('" . json_encode($brxc_acf_fields['classes_and_styles_general']) . "');
            ADMINBRXC.globalSettings.generalCats.builderTweaks = JSON.parse('" . json_encode($brxc_acf_fields['builder_tweaks_general']) . "');
            ADMINBRXC.globalSettings.generalCats.extras = JSON.parse('" . json_encode($brxc_acf_fields['enable_extras_features']) . "');
            ADMINBRXC.globalSettings.shortcutsTabs = JSON.parse('" . json_encode($brxc_acf_fields['enable_tabs_icons']) . "');
            ADMINBRXC.globalSettings.shortcutsIcons = JSON.parse('" . json_encode($brxc_acf_fields['enable_shortcuts_icons']) . "');
            ADMINBRXC.globalSettings.globalFeatures = JSON.parse('" . json_encode($brxc_acf_fields['enable_global_features']) . "');
            ADMINBRXC.globalSettings.structurePanelIcons = JSON.parse('" . json_encode($brxc_acf_fields['structure_panel_icons']) . "');
            ADMINBRXC.globalSettings.structurePanelTagDefaultView = '" . $brxc_acf_fields['structure_panel_default_tag_view'] . "';
            ADMINBRXC.globalSettings.structurePanelContextualMenu = JSON.parse('" . json_encode($brxc_acf_fields['structure_panel_contextual_menu']) . "');
            ADMINBRXC.globalSettings.structurePanelGeneralTweaks = JSON.parse('" . json_encode($brxc_acf_fields['structure_panel_general_tweaks']) . "');
            ADMINBRXC.globalSettings.structurePanelTagIndicatorColors = '" . $brxc_acf_fields['structure_panel_styles_and_classes_indicator_colors'] . "';
            ADMINBRXC.globalSettings.structurePanelWidth = '" .$brxc_acf_fields['structure_panel_width'] . "';
            ADMINBRXC.globalSettings.defaultElementsCol = '" .$brxc_acf_fields['default_elements_list_cols'] . "';
            ADMINBRXC.globalSettings.defaultSpacingControls = '" .$brxc_acf_fields['default_spacing_controls'] . "';
            ADMINBRXC.globalSettings.defaultElementFeatures = JSON.parse('" . json_encode($brxc_acf_fields['custom_default_settings']) . "');
            ADMINBRXC.globalSettings.classFeatures = JSON.parse('" . json_encode($brxc_acf_fields['class_features']) . "');
            ADMINBRXC.globalSettings.classFeatures.lockIdWithClasses = '" .$brxc_acf_fields['lock_id_styles_with_classes'] . "';
            ADMINBRXC.globalSettings.autoFormatFunctions = JSON.parse('" . json_encode($brxc_acf_fields['autoformat_control_values']) . "');
            ADMINBRXC.globalSettings.elementFeatures = JSON.parse('" . json_encode($brxc_acf_fields['element_features']) . "');
            ADMINBRXC.globalSettings.templateFeatures = JSON.parse('" . json_encode($brxc_acf_fields['templates_features']) . "');
            ADMINBRXC.globalSettings.themeSettingsTabs = JSON.parse('" . json_encode($brxc_acf_fields['theme_settings_tabs']) . "');
            ADMINBRXC.globalSettings.createElementsShortcuts = JSON.parse('" . json_encode($brxc_acf_fields['create_elements_shortcuts']) . "');
            ADMINBRXC.globalSettings.loremIpsumtype = '" . $brxc_acf_fields['lorem_type'] . "';
            ADMINBRXC.globalSettings.keyboardShortcuts.options = JSON.parse('" . json_encode($brxc_acf_fields['keyboard_sc_options']) . "');
            ADMINBRXC.globalSettings.keyboardShortcuts.cssVariableModal = '" . $brxc_acf_fields['keyboard_sc_open_css_variable_modal'] . "';
            ADMINBRXC.globalSettings.keyboardShortcuts.gridGuides = '" . $brxc_acf_fields['keyboard_sc_enable_grid_guides'] . "';
            ADMINBRXC.globalSettings.keyboardShortcuts.xMode = '" . $brxc_acf_fields['keyboard_sc_enable_xmode'] . "';
            ADMINBRXC.globalSettings.keyboardShortcuts.contrastChecker = '" . $brxc_acf_fields['keyboard_sc_enable_constrast_checker'] . "';
            ADMINBRXC.globalSettings.keyboardShortcuts.darkmode = '" . $brxc_acf_fields['keyboard_sc_enable_darkmode'] . "';
            ADMINBRXC.globalSettings.keyboardShortcuts.cssStylesheets = '" . $brxc_acf_fields['keyboard_sc_enable_css_stylesheets'] . "';
            ADMINBRXC.globalSettings.keyboardShortcuts.resources = '" . $brxc_acf_fields['keyboard_sc_enable_resources'] . "';
            ADMINBRXC.globalSettings.keyboardShortcuts.openai = '" . $brxc_acf_fields['keyboard_sc_enable_openai'] . "';
            ADMINBRXC.globalSettings.keyboardShortcuts.brickslabs = '" . $brxc_acf_fields['keyboard_sc_enable_brickslabs'] . "';
            ADMINBRXC.globalSettings.keyboardShortcuts.colorManager = '" . $brxc_acf_fields['keyboard_sc_enable_color_manager'] . "';
            ADMINBRXC.globalSettings.keyboardShortcuts.classManager = '" . $brxc_acf_fields['keyboard_sc_enable_class_manager'] . "';
            ADMINBRXC.globalSettings.keyboardShortcuts.variableManager = '" . $brxc_acf_fields['keyboard_sc_enable_variable_manager'] . "';
            ADMINBRXC.globalSettings.keyboardShortcuts.structureHelper = '" . $brxc_acf_fields['keyboard_sc_enable_structure_helper'] . "';
            ADMINBRXC.globalSettings.gridClasses = JSON.parse('" . json_encode(self::populate_grid_classes()) . "');
            ADMINBRXC.globalSettings.importedClasses = JSON.parse('" . json_encode(self::populate_class_importer()) . "');
            ADMINBRXC.globalSettings.isAIApiKeyEmpty = '" . $brxc_acf_fields['openai_api_key'] . "';
            ADMINBRXC.globalSettings.defaultAIModel = '" . $brxc_acf_fields['default_api_model'] . "';
            ") . 
            "ADMINBRXC.globalSettings.defaultVariables = JSON.parse('" . AT__Global_Variables::populate_variables_object() . "');
            ADMINBRXC.globalSettings.generalCats.queryManager = " . $query_manager_output . ";
            ADMINBRXC.globalSettings.generalCats.queryManagerCats = " . $query_manager_cats_output . ";
            })", 'after');

        

        require_once \BRICKS_ADVANCED_THEMER_PATH . '/inc/builder_modal.php';
    }
    
    // Create the AJAX function
    public static function openai_ajax_function() {
        // Verify the nonce
        if ( ! wp_verify_nonce( $_POST['nonce'], 'openai_ajax_nonce' ) ) {
            die( 'Invalid nonce' );
        }
    
        // Get the data from the wp_option table
        $my_option = get_option( 'bricks-advanced-themer__brxc_ai_api_key_skip_export' );
        $ciphering = "AES-128-CTR";
        $options = 0;
        $decryption_iv = 'UrsV9aENFT*IRfhr';
        $decryption_key = "#34x*R8zmVK^IFG4#a4B3BVYIb";
        $value = openssl_decrypt ($my_option, $ciphering, $decryption_key, $options, $decryption_iv);
    
        // Return the data as JSON
        wp_send_json( $value );
    }
    
    public static function openai_save_image_to_media_library() {
        // Verify the nonce
        if ( ! wp_verify_nonce( $_POST['nonce'], 'openai_ajax_nonce' ) ) {
            die( 'Invalid nonce' );
        }
    
        if (!current_user_can('edit_posts')) { 

            wp_send_json_error('You do not have permission to save images.'); 

        } 
        $base64_img= $_POST['image_url'];

        if(!$base64_img){
            wp_send_json_error('Could not retrieve image data.');
        }

        $title = 'ai-image-' . AT__Helpers::generate_unique_string( 6 );
        $upload_dir  = wp_upload_dir();
        $upload_path = str_replace( '/', DIRECTORY_SEPARATOR, $upload_dir['path'] ) . DIRECTORY_SEPARATOR;

        $img             = str_replace( 'data:image/png;base64,', '', $base64_img );
        $img             = str_replace( ' ', '+', $img );
        $decoded         = base64_decode( $img );
        $filename        = $title . '.png';
        $file_type       = 'image/png';
        $hashed_filename = md5( $filename . microtime() ) . '_' . $filename;

        // Save the image in the uploads directory.
        $upload_file = file_put_contents( $upload_path . $hashed_filename, $decoded );
        $target_file = trailingslashit($upload_dir['path']) . $hashed_filename;

        $attachment = array(
            'post_mime_type' => $file_type,
            'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $hashed_filename ) ),
            'post_content'   => '',
            'post_status'    => 'inherit',
            'guid'           => $upload_dir['url'] . '/' . basename( $hashed_filename )
        );

        $attach_id = wp_insert_attachment( $attachment, $upload_dir['path'] . '/' . $hashed_filename );
        $attachment_data = wp_generate_attachment_metadata($attach_id, $target_file);
        wp_update_attachment_metadata($attach_id, $attachment_data);
        wp_send_json_success('Image saved successfully.'); 

    }

    public static function export_advanced_options_callback() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error('You don\'t have permission to perform this action.');
        }
        // Verify nonce
        $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';
    
        if (!wp_verify_nonce($nonce, 'export_advanced_options_nonce')) {
            wp_die("Invalid nonce, please refresh the page and try again.");
        }
        $checked_data = $_POST['checked_data'];

        if(!is_array($checked_data)){
            return;
        }

        $json_data = array();
        global $wpdb;

        // AT Settings
        if(in_array('at-theme-settings', $checked_data)){
            $option_data = $wpdb->get_results("SELECT option_name, option_value FROM $wpdb->options WHERE option_name LIKE '%bricks-advanced-themer%' AND option_name NOT LIKE '%_variables_repeater%' AND option_name NOT LIKE '%_skip_export' AND option_name NOT LIKE '%\_api\_%'");
            
            if(isset($option_data) && is_array($option_data)){
                $json_data['at_settings'] = [];
                foreach ($option_data as $row) {
                    $json_data['at_settings'][$row->option_name] = maybe_unserialize($row->option_value);
                }
            }

        }

        // Global Variables
        if(in_array('global-variables', $checked_data)){
            $option_data = $wpdb->get_results("SELECT option_name, option_value FROM $wpdb->options WHERE option_name LIKE '%bricks-advanced-themer__brxc_%' AND option_name LIKE '%_variables_repeater%' AND option_name NOT LIKE '%_skip_export' AND option_name NOT LIKE '%\_api\_%'");
            
            if(isset($option_data) && is_array($option_data)){
                $json_data['global-variables'] = [];
                foreach ($option_data as $row) {
                    $json_data['global-variables'][$row->option_name] = maybe_unserialize($row->option_value);
                }
            }
        }

        // Global Colors
        if(in_array('global-colors', $checked_data)){
            $palette_arr = get_option( 'bricks_color_palette' );
            if( isset($palette_arr) && $palette_arr && is_array($palette_arr) && !empty($palette_arr) ) {
                $json_data['global-colors'] = $palette_arr;
            } 
        }

        // Global Classes
        if(in_array('global-classes', $checked_data)){
            $global_classes = get_option( 'bricks_global_classes' );
            if( isset($global_classes) && $global_classes && is_array($global_classes) && !empty($global_classes) ) {
                $json_data['global-classes'] = $global_classes;
            }

            $global_classes_locked = get_option( 'bricks_global_classes_locked' );
            if( isset($global_classes_locked) && $global_classes_locked && is_array($global_classes_locked) && !empty($global_classes_locked) ) {
                $json_data['global-classes-locked'] = $global_classes_locked;
            }
        }

        // Theme Styles
        if(in_array('theme-styles', $checked_data)){
            $theme_styles = get_option( 'bricks_theme_styles' );
            if( isset($theme_styles) && $theme_styles && is_array($theme_styles) && !empty($theme_styles) ) {
                $json_data['theme_styles'] = $theme_styles;
            } 
        }

        echo json_encode($json_data);
        
        wp_die(); // Required for AJAX callback 

    } 

    public static function reset_advanced_options_callback() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error('You don\'t have permission to perform this action.');
        }
        // Verify nonce
        $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';
    
        if (!wp_verify_nonce($nonce, 'export_advanced_options_nonce')) {
            wp_die("Invalid nonce, please refresh the page and try again.");
        }
        
        $checked_data = $_POST['checked_data'];

        if(!is_array($checked_data)){
            return;
        }

        $json_data = array();
        global $wpdb;

        // AT Settings
        if (in_array('at-theme-settings', $checked_data)) {
            $option_data = $wpdb->get_results("SELECT option_name, option_value FROM $wpdb->options WHERE option_name LIKE '%bricks-advanced-themer%' AND option_name NOT LIKE '%_variables_repeater%'");

            // Delete options
            foreach ($option_data as $option) {
                delete_option($option->option_name);
            }
        }

        // Global Variables
        if (in_array('global-variables', $checked_data)) {
            $option_data = $wpdb->get_results("SELECT option_name, option_value FROM $wpdb->options WHERE option_name LIKE '%bricks-advanced-themer__brxc_%' AND option_name LIKE '%_variables_repeater%'");

            // Delete options
            foreach ($option_data as $option) {
                delete_option($option->option_name);
            }
        }

        // Global Colors
        if(in_array('global-colors', $checked_data)){
            delete_option( 'bricks_color_palette' );
        }

        // Global Classes
        if(in_array('global-classes', $checked_data)){
            delete_option( 'bricks_global_classes' );
            delete_option( 'bricks_global_classes_locked' );
        }

        // Theme Styles
        if(in_array('theme-styles', $checked_data)){
            delete_option( 'bricks_theme_styles' );
        }

        wp_send_json_success();
        
        wp_die(); // Required for AJAX callback 

    } 
    public static function import_advanced_options_callback() {

        if (!current_user_can('manage_options')) {
            wp_send_json_error('You don\'t have permission to perform this action.');
        }


        // Verify nonce
        $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';
    
        if (!wp_verify_nonce($nonce, 'export_advanced_options_nonce')) {
            wp_die("Invalid nonce, please refresh the page and try again.");
        }

            
        if ( ! isset( $_FILES['file']['tmp_name'] ) ) { 
            wp_send_json_error( 'File not uploaded.' ); 
        } 

        $temp_path = $_FILES['file']['tmp_name']; 
        $checked_data = $_POST['checked_data'];
        $overwrite = $_POST['overwrite'];
        

        if ($checked_data === null) {
            wp_send_json_error('Invalid checked data.');
        }


        $json_file = AT__Helpers::read_file_contents($temp_path);

        if ($json_file !== false){

            $data = json_decode($json_file, true);

            if ($data === null) {
                wp_send_json_error('Invalid JSON file.');
            }

            global $wpdb;

            // AT Settings
            $pos = strpos($checked_data, 'at-theme-settings');
            if( $pos && isset($data['at_settings']) && is_array($data['at_settings']) ){
                
                $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '%bricks-advanced-themer%' AND option_name NOT LIKE '%_variables_repeater%'");
        
                foreach ($data['at_settings'] as $option_name => $option_value) {
                    if (is_array($option_value)) {
                        $option_value = maybe_serialize($option_value);
                    }

                    $wpdb->insert($wpdb->options, array('option_name' => $option_name, 'option_value' => $option_value));

                }
                
            }

            // Global Variables
            $pos = strpos($checked_data, 'global-variables');
            if( $pos && isset($data['global-variables']) && is_array($data['global-variables']) ){
                
                $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '%bricks-advanced-themer__brxc_%' AND option_name LIKE '%_variables_repeater%'");
        
                foreach ($data['global-variables'] as $option_name => $option_value) {
                    if (is_array($option_value)) {
                        $option_value = maybe_serialize($option_value);
                    }

                    $wpdb->insert($wpdb->options, array('option_name' => $option_name, 'option_value' => $option_value));
                }
                
            }

            // Global Classes
            $pos = strpos($checked_data, 'global-classes');
            if( $pos && isset($data['global-classes']) && is_array($data['global-classes']) ){
                
                $global_classes = get_option('bricks_global_classes');

                if( !isset($global_classes) || !$global_classes || !is_array($global_classes) || empty($global_classes) || $overwrite === true ) {
                    $global_classes = [];
                }

                foreach ($data['global-classes'] as $objectA) {
                    $nameA = $objectA['name'];
                    $found = false;
                
                    // Check if the object with the same name exists in arrayB
                    foreach ($global_classes as $objectB) {
                        $nameB = $objectB['name'];
                        if ($nameA === $nameB) {
                            $found = true;
                            break;
                        }
                    }
                
                    // If the object with the same name was not found in arrayB, add it
                    if (!$found) {
                        $global_classes[] = $objectA;
                    }
                }

                update_option('bricks_global_classes', $global_classes);
                
            }

            // Theme Styles
            $pos = strpos($checked_data, 'theme-styles');
            if( $pos && isset($data['theme_styles']) && is_array($data['theme_styles']) ){
                
                $theme_styles = get_option('bricks_theme_styles');

                if( !isset($theme_styles) || !$theme_styles || !is_array($theme_styles) || empty($theme_styles) || $overwrite === true ) {
                    $theme_styles = [];
                }

                foreach ($data['theme_styles'] as $objectA => $valueA) {
                    $nameA = $objectA;
                    $found = false;
                
                    // Check if the object with the same name exists in arrayB
                    foreach ($theme_styles as $objectB => $valueB) {
                        $nameB = $objectB;
                        if ($nameA === $nameB) {
                            $found = true;
                            break;
                        }
                    }
                
                    // If the object with the same name was not found in arrayB, add it
                    if (!$found) {
                        $theme_styles[$objectA] = $valueA;
                    }
                }

                update_option('bricks_theme_styles', $theme_styles);
                
            }

            // Global Colors
            $pos = strpos($checked_data, 'global-colors');
            if( $pos && isset($data['global-colors']) && is_array($data['global-colors']) ){
                
                $global_colors = get_option('bricks_color_palette');

                if( !isset($global_colors) || !$global_colors || !is_array($global_colors) || empty($global_colors) || $overwrite === true ) {
                    $global_colors = [];
                }

                foreach ($data['global-colors'] as $objectA) {
                    $nameA = $objectA['id'];
                    $found = false;
                
                    // Check if the object with the same name exists in arrayB
                    foreach ($global_colors as $objectB) {
                        $nameB = $objectB['id'];
                        if ($nameA === $nameB) {
                            $found = true;
                            break;
                        }
                    }
                
                    // If the object with the same name was not found in arrayB, add it
                    if (!$found) {
                        $global_colors[] = $objectA;
                    }
                }

                update_option('	bricks_color_palette', $global_colors);
                
            }
    
            wp_send_json_success($data);
        }


        wp_die(); // Required for AJAX callback 
    }

    private static function repositionArrayElement(array &$array, $key, int $order): void{
        if(($a = array_search($key, array_keys($array))) === false){
            throw new \Exception("The {$key} cannot be found in the given array.");
        }
        $p1 = array_splice($array, $a, 1);
        $p2 = array_splice($array, 0, $order);
        $array = array_merge($p2, $p1, $array);
    }
    
    public static function disable_bricks_elements() {
        global $brxc_acf_fields;
        $disable_on_server = $brxc_acf_fields['disable_bricks_elements_on_server'];
        $settings = $brxc_acf_fields['disable_bricks_elements'];
    
        if (!isset($disable_on_server) || !$disable_on_server || !isset($settings) || !is_array($settings)) {
            return;
        }
    
        add_filter('bricks/builder/elements', function ($elements) use ($settings) {
            $index = 0;
            foreach ($elements as $element) {
                if (!in_array($element, $settings)) {
                    unset($elements[$index]);
                }
                $index++;
            }
    
            return $elements;
        });
    }

    public static function set_custom_default_values_in_builder(){

        global $brxc_acf_fields;

        $settings = $brxc_acf_fields['custom_default_settings'];

        if (!class_exists('Bricks\Elements') || !AT__Helpers::is_elements_tab_activated() ) {
            return;
        }

        $elements = \Bricks\Elements::$elements;

        // SuperPower CSS
        if(isset($brxc_acf_fields['element_features']) && is_array($brxc_acf_fields['element_features']) && in_array("superpower-custom-css", $brxc_acf_fields['element_features'])){
            foreach($elements as $element){
                $element = $element['name'];
            
                add_filter( 'bricks/elements/' . $element . '/controls', function( $controls ) {
        
                    $controls['_cssSuperPowerCSS'] = [
                        'tab'         => 'style',
                        'group'       => '_css',
                        'label'       => esc_html__( 'SuperPower CSS', 'bricks' ),
                        'type'        => 'textarea',
                        'pasteStyles' => true,
                        'css'         => [],
                        'hasDynamicData' => false,
                        'description' => esc_html__( 'Use "%root%" to target the element wrapper.', 'bricks' ) 
                                        . '<br /><br /><u>' . esc_html__('Shortcuts', 'bricks' ) . '</u><br />' 
                                        . '<strong>' . esc_html__('r + TAB', 'bricks') . '</strong>' . esc_html__(' => %root%', 'bricks' ) . '<br />'
                                        . '<strong>' . esc_html__('rh + TAB', 'bricks') . '</strong>' . esc_html__(' => %root%:hover', 'bricks' ) . '<br />'
                                        . '<strong>' . esc_html__('rb + TAB', 'bricks') . '</strong>' . esc_html__(' => %root%::before', 'bricks' ) . '<br />'
                                        . '<strong>' . esc_html__('ra + TAB', 'bricks') . '</strong>' . esc_html__(' => %root%::after', 'bricks' ) . '<br />'
                                        . '<strong>' . esc_html__('rf + TAB', 'bricks') . '</strong>' . esc_html__(' => %root%:focus', 'bricks' ) . '<br />'
                                        . '<strong>' . esc_html__('rcf + TAB', 'bricks') . '</strong>' . esc_html__(' => %root%:first-child', 'bricks' ) . '<br />'
                                        . '<strong>' . esc_html__('rcl + TAB', 'bricks') . '</strong>' . esc_html__(' => %root%:last-child', 'bricks' ) . '<br />'
                                        . '<strong>' . esc_html__('rc + argument + TAB', 'bricks') . '</strong>' . esc_html__(' => %root%:nth-child({argument})', 'bricks' ) . '<br />'
                                        . '<strong>' . esc_html__('rtf + TAB', 'bricks') . '</strong>' . esc_html__(' => %root%:first-of-type', 'bricks' ) . '<br />'
                                        . '<strong>' . esc_html__('rtl + TAB', 'bricks') . '</strong>' . esc_html__(' => %root%:last-of-type', 'bricks' ) . '<br />'
                                        . '<strong>' . esc_html__('rt + argument + TAB', 'bricks') . '</strong>' . esc_html__(' => %root%:nth-of-type({argument})', 'bricks' ) . '<br />'
                                        . '<strong>' . esc_html__('q + width + TAB', 'bricks') . '</strong>' . esc_html__(' => @media screen and (max-width: {width}) {}', 'bricks' ) . '<br />'
                                        . '<strong>' . esc_html__('Q + width + TAB', 'bricks') . '</strong>' . esc_html__(' => @media screen and (max-width: {width}) { %root% {} }', 'bricks' ) . '<br />'
                                        . '<strong>' . esc_html__('q + c + TAB', 'bricks') . '</strong>' . esc_html__(' => @media screen and (max-width: {current viewport width}) {}', 'bricks' ) . '<br />'
                                        . '<strong>' . esc_html__('Q + c + TAB', 'bricks') . '</strong>' . esc_html__(' => @media screen and (max-width: {current viewport width}) { %root% {} }', 'bricks' ) . '<br />'
                                        . '<strong>' . esc_html__('CMD + SHIFT + 7', 'bricks') . '</strong>' . esc_html__(' => comment/uncomment the selected code', 'bricks' ) . '<br /><br />'
                                        . esc_html__('Replacing "r" by "R" (capitilized letter) will add the brackets and place the cursor inside of them.' , 'bricks' ) . '<br /><br />',
                        'placeholder' => "%root% {\n  color: firebrick;\n}",
                    ];

                    return $controls;
                });
            }
        }

        // Custom values

        if (isset($settings) && !empty($settings) && is_array($settings) ){
            // Basic Text: p as default HTML Tag
            if( in_array("text-basic-p",  $settings) ){
                add_filter( 'bricks/elements/text-basic/controls', function( $controls ) {
                    global $brxc_acf_fields;
                    $settings = $brxc_acf_fields['custom_default_settings'];
                    $controls['tag']['default'] = "p";
                    return $controls;
                } );
            }
            // Image: figure as default HTML Tag
            if( in_array("image-figure",  $settings) ){
                add_filter( 'bricks/elements/image/controls', function( $controls ) {
                    global $brxc_acf_fields;
                    $settings = $brxc_acf_fields['custom_default_settings'];
                    $controls['tag']['default'] = "figure";
                    return $controls;
                } );
            }
            // Image: caption off
            if( in_array("image-caption-off",  $settings) ){
                add_filter( 'bricks/elements/image/controls', function( $controls ) {
                    global $brxc_acf_fields;
                    $settings = $brxc_acf_fields['custom_default_settings'];
                    $controls['caption']['default'] = 'none';
                    return $controls;
                } );
            }
            // Button: button as default HTML Tag
            if( in_array("button-button",  $settings) ){
                add_filter( 'bricks/elements/button/controls', function( $controls ) {
                    global $brxc_acf_fields;
                    $settings = $brxc_acf_fields['custom_default_settings'];
                    $controls['tag']['default'] = 'button';
                    return $controls;
                } );
            }
            // Set SVG as default icon set for icon elements
            if( in_array("icon-svg",  $settings) ){
                add_filter( 'bricks/elements/icon/controls', function( $controls ) {
                    global $brxc_acf_fields;
                    $settings = $brxc_acf_fields['custom_default_settings'];
                    $controls['icon']['default'] = [
                        'library' => 'svg',
                        'icon'    => '',
                    ];
                    return $controls;
                } );
            }

            // Add fields to all elements

            $settings = $brxc_acf_fields['custom_default_settings'];
            foreach($elements as $element){
                $element = $element['name'];
                add_filter( 'bricks/elements/' . $element . '/control_groups', function( $control_groups ) {
                    global $brxc_acf_fields;
                    $settings = $brxc_acf_fields['custom_default_settings'];

                    if(in_array("filter-tab",  $settings) ){
                        $control_groups['_filter'] = [
                            'tab'      => 'style',
                            'title'    => esc_html__( 'Filters / Transitions', 'Bricks' ),
                        ];

                        self::repositionArrayElement($control_groups, "_filter", array_search('_css', array_keys($control_groups)));
                    }

                    if(in_array("classes-tab",  $settings) ){
                        $control_groups['_classesAndId'] = [
                            'tab'      => 'style',
                            'title'    => esc_html__( 'Classes / ID', 'Bricks' ),
                        ];

                        self::repositionArrayElement($control_groups, "_classesAndId", array_search('_css', array_keys($control_groups)) + 1);    
                    }

                    return $control_groups;
                } );
            
                add_filter( 'bricks/elements/' . $element . '/controls', function( $controls ) {
                    global $brxc_acf_fields;
                    $settings = $brxc_acf_fields['custom_default_settings'];

                    if(in_array("background-clip",  $settings) ){
                        $controls['_backgroundClip'] = [
                            'tab'      => 'style',
                            'group'    => '_background',
                            'label'    => esc_html__( 'Background clip' ),
                            'type'     => 'select',
                            'options'  => [
                                'border-box' => esc_html__( 'border-box', 'bricks' ),
                                'content-box' => esc_html__( 'content-box', 'bricks' ),
                                'padding-box' => esc_html__( 'padding-box', 'bricks' ),
                                'text' => esc_html__( 'text', 'bricks' ),
                            ],
                            'css'      => [
                                [
                                    'property' => '-webkit-background-clip',
                                    'selector' => '',
                                ],
                            ],
                        ];

                        self::repositionArrayElement($controls, "_backgroundClip", array_search('_background', array_keys($controls)) + 1);
                    }

                    if(in_array("white-space",  $settings) ){
                        $controls['_whiteSpace'] = [
                            'tab'      => 'style',
                            'group'    => '_layout',
                            'label'    => esc_html__( 'White space' ),
                            'type'     => 'select',
                            'options'  => [
                                'normal' => esc_html__( 'normal', 'bricks' ),
                                'nowrap' => esc_html__( 'nowrap', 'bricks' ),
                                'pre' => esc_html__( 'pre', 'bricks' ),
                                'pre-line' => esc_html__( 'pre-line', 'bricks' ),
                                'pre-wrap' => esc_html__( 'pre-wrap', 'bricks' ),
                            ],
                            'inline'   => true,
                            'css'      => [
                                [
                                    'property' => 'white-space',
                                    'selector' => '',
                                ],
                            ],
                        ];

                        self::repositionArrayElement($controls, "_whiteSpace", array_search('_overflow', array_keys($controls)) + 1);
                    }

                    if(in_array("content-visibility",  $settings) ){
                        $controls['_contentVisibility'] = [
                            'tab'      => 'style',
                            'group'    => '_layout',
                            'label'    => esc_html__( 'Content visibility' ),
                            'type'     => 'select',
                            'options'  => [
                                'auto' => esc_html__( 'auto', 'bricks' ),
                                'hidden' => esc_html__( 'hidden', 'bricks' ),
                                'visible' => esc_html__( 'visible', 'bricks' ),
                            ],
                            'inline'   => true,
                            'css'      => [
                                [
                                    'property' => 'content-visibility',
                                    'selector' => '',
                                ],
                            ],
                        ];
                        

                        $controls['_containIntrinsicSize'] = [
                            'tab'      => 'style',
                            'group'    => '_layout',
                            'label'    => esc_html__( 'Contain intrinsic size' ),
                            'type'     => 'number',
                            'units'    => true,
                            'inline'   => true,
                            'css'      => [
                                [
                                    'property' => 'contain-intrinsic-size',
                                    'selector' => '',
                                ],
                            ],
                        ];

                        self::repositionArrayElement($controls, "_contentVisibility", array_search('_overflow', array_keys($controls)));
                        self::repositionArrayElement($controls, "_containIntrinsicSize", array_search('_contentVisibility', array_keys($controls)) + 1);
                    }

                    if(in_array("overflow-dropdown",  $settings) ){
                        $controls['_overflow']['type'] = 'select';
                        $controls['_overflow']['options']  = [
                                'auto' => esc_html__( 'auto', 'bricks' ),
                                'clip' => esc_html__( 'clip', 'bricks' ),
                                'hidden' => esc_html__( 'hidden', 'bricks' ),
                                'overlay' => esc_html__( 'overlay', 'bricks' ),
                                'revert' => esc_html__( 'revert', 'bricks' ),
                                'scroll' => esc_html__( 'scroll', 'bricks' ),
                                'visible' => esc_html__( 'visible', 'bricks' ),
                        ];
                    }



                    if(in_array("break",  $settings) ){
                        $css_values = [
                            'always' => esc_html__( 'always', 'bricks' ),
                            'auto' => esc_html__( 'auto', 'bricks' ),
                            'avoid' => esc_html__( 'avoid', 'bricks' ),
                            'avoid-column' => esc_html__( 'avoid-column', 'bricks' ),
                            'avoid-page' => esc_html__( 'avoid-page', 'bricks' ),
                            'avoid-region' => esc_html__( 'avoid-region', 'bricks' ),
                            'column' => esc_html__( 'column', 'bricks' ),
                            'left' => esc_html__( 'left', 'bricks' ),
                            'page' => esc_html__( 'page', 'bricks' ),
                            'recto' => esc_html__( 'recto', 'bricks' ),
                            'region' => esc_html__( 'region', 'bricks' ),
                            'right' => esc_html__( 'right', 'bricks' ),
                            'verso' => esc_html__( 'verso', 'bricks' ),
                        ];

                        $controls['_breakBefore'] = [
                            'tab'      => 'style',
                            'group'    => '_layout',
                            'label'    => esc_html__( 'Break before' ),
                            'type'     => 'select',
                            'inline'   => true,
                            'options'  => $css_values,
                            'css'      => [
                                [
                                    'property' => 'break-before',
                                    'selector' => '',
                                ],
                            ],
                        ];

                        $controls['_breakInside'] = [
                            'tab'      => 'style',
                            'group'    => '_layout',
                            'label'    => esc_html__( 'Break inside' ),
                            'type'     => 'select',
                            'inline'   => true,
                            'options'  => $css_values,
                            'css'      => [
                                [
                                    'property' => 'break-inside',
                                    'selector' => '',
                                ],
                            ],
                        ];

                        $controls['_breakAfter'] = [
                            'tab'      => 'style',
                            'group'    => '_layout',
                            'label'    => esc_html__( 'Break after' ),
                            'type'     => 'select',
                            'inline'   => true,
                            'options'  => $css_values,
                            'css'      => [
                                [
                                    'property' => 'break-after',
                                    'selector' => '',
                                ],
                            ],
                        ];


                        self::repositionArrayElement($controls, "_breakBefore", array_search('_pointerEvents', array_keys($controls)) + 1 );
                        self::repositionArrayElement($controls, "_breakInside", array_search('_breakBefore', array_keys($controls)) + 1 );
                        self::repositionArrayElement($controls, "_breakAfter", array_search('_breakInside', array_keys($controls)) + 1 );
                    }
                    if(in_array("filter-tab",  $settings) ){
                        $controls['_cssFilters']['group'] = '_filter';
                        $controls['_cssTransition']['group'] = '_filter';
                    }

                    if(in_array("classes-tab",  $settings) ){
                        $controls['_cssClasses']['group'] = '_classesAndId';
                        $controls['_cssId']['group'] = '_classesAndId';
                    }

                    if(in_array("transform",  $settings) ){
                        $controls['_transform']['description'] = false;
                        $controls['_transformOrigin']['description'] = false;
                        $controls['_transformStyle'] = [
                            'tab'      => 'style',
                            'group'    => '_transform',
                            'label'    => esc_html__( 'Transform style' ),
                            'type'     => 'select',
                            'options'  => [
                                'flat' => esc_html__( 'flat', 'bricks' ),
                                'preserve-3d' => esc_html__( 'preserve-3d', 'bricks' ),
                            ],
                            'inline'   => true,
                            'css'      => [
                                [
                                    'property' => 'transform-style',
                                    'selector' => '',
                                ],
                            ],
                        ];

                        $controls['_transformBox'] = [
                            'tab'      => 'style',
                            'group'    => '_transform',
                            'label'    => esc_html__( 'Transform box' ),
                            'type'     => 'select',
                            'options'  => [
                                'border-box' => esc_html__( 'border-box', 'bricks' ),
                                'content-box' => esc_html__( 'content-box', 'bricks' ),
                                'fill-box' => esc_html__( 'fill-box', 'bricks' ),
                                'stroke-box' => esc_html__( 'stroke-box', 'bricks' ),
                                'view-box' => esc_html__( 'view-box', 'bricks' ),
                            ],
                            'inline'   => true,
                            'css'      => [
                                [
                                    'property' => 'transform-box',
                                    'selector' => '',
                                ],
                            ],
                        ];
                        $controls['_perspective'] = [
                            'tab'      => 'style',
                            'group'    => '_transform',
                            'label'    => esc_html__( 'Perspective' ),
                            'type'     => 'number',
                            'units'    => true,
                            'inline'   => true,
                            'css'      => [
                                [
                                    'property' => 'perspective',
                                    'selector' => '',
                                ],
                            ],
                        ];
                        $controls['_perspectiveOrigin'] = [
                            'tab'      => 'style',
                            'group'    => '_transform',
                            'label'    => esc_html__( 'Perspective origin' ),
                            'type'     => 'text',
                            'inline'   => true,
                            'css'      => [
                                [
                                    'property' => 'perspective-origin',
                                    'selector' => '',
                                ],
                            ],
                            'hasDynamicData' => false,
                            'placeholder'    => esc_html__( 'Center', 'bricks' ),
                        ];

                        $controls['_backfaceVisibility'] = [
                            'tab'      => 'style',
                            'group'    => '_transform',
                            'label'    => esc_html__( 'Backface visibility' ),
                            'type'     => 'select',
                            'options'  => [
                                'hidden' => esc_html__( 'hidden', 'bricks' ),
                                'visible' => esc_html__( 'visible', 'bricks' ),
                            ],
                            'inline'   => true,
                            'css'      => [
                                [
                                    'property' => 'backface-visibility',
                                    'selector' => '',
                                ],
                            ],
                        ];

                        self::repositionArrayElement($controls, "_transformStyle", array_search('_transformOrigin', array_keys($controls)) + 1);
                        self::repositionArrayElement($controls, "_transformBox", array_search('_transformStyle', array_keys($controls)) + 1);
                        self::repositionArrayElement($controls, "_perspective", array_search('_transformBox', array_keys($controls)) + 1);
                        self::repositionArrayElement($controls, "_perspectiveOrigin", array_search('_perspective', array_keys($controls)) + 1);
                        self::repositionArrayElement($controls, "_backfaceVisibility", array_search('_perspectiveOrigin', array_keys($controls)) + 1);
                    }

                    if(in_array("css-filters",  $settings) ){
                        $controls['_backdropFilter'] = [
                            'tab'      => 'style',
                            'group'    => '_filter',
                            'label'    => esc_html__( 'Backdrop filter' ),
                            'type'     => 'text',
                            'css'      => [
                                [
                                    'property' => 'backdrop-filter',
                                    'selector' => '',
                                ],
                            ],
                            'hasDynamicData' => false,
                            'placeholder'    => esc_html__( 'None', 'bricks' ),
                        ];

                        self::repositionArrayElement($controls, "_backdropFilter", array_search('_cssFilters', array_keys($controls)) + 1);
                    }
                    
                    return $controls;
                } );
                
                
                // Target Containers only
                if( in_array("column-count",  $settings) && ($element == 'div' || $element == 'block' || $element == 'container' || $element == 'section') ){
                    add_filter( 'bricks/elements/' . $element . '/controls', function( $controls ) {
                        $controls['_columnCount'] = [
                            'tab'      => 'content',
                            'label'    => esc_html__( 'Column count' ),
                            'type'     => 'number',
                            'units'    => false,
                            'css'      => [
                                [
                                    'property' => 'column-count',
                                    'selector' => '',
                                ],
                            ],
                            'required' => [ '_display', '=', 'block' ],
                        ];

                        $controls['_columnCountColumnGap'] = [
                            'tab'      => 'content',
                            'label'    => esc_html__( 'Column gap', 'bricks' ),
                            'type'     => 'number',
                            'units'    => true,
                            'css'      => [
                                [
                                    'property' => 'column-gap',
                                    'selector' => '',
                                ],
                            ],
                            'required' => [ '_display', '=', 'block' ],
                        ];
                        $controls['_columnFill'] = [
                            'tab'      => 'content',
                            'label'    => esc_html__( 'Column fill','bricks' ),
                            'type'     => 'select',
                            'inline'   => true,
                            'options'  => [
                                'auto' => 'auto',
                                'balance' => 'balance',
                                'balance-all' => 'balance-all',
                            ],
                            'css'      => [
                                [
                                    'property' => 'column-fill',
                                    'selector' => '',
                                ],
                            ],
                            'required' => [ '_display', '=', 'block' ],
                        ];
                        $controls['_columnWidth'] = [
                            'tab'      => 'content',
                            'label'    => esc_html__( 'Column width', 'bricks' ),
                            'type'     => 'number',
                            'units'    => true,
                            'css'      => [
                                [
                                    'property' => 'column-width',
                                    'selector' => '',
                                ],
                            ],
                            'required' => [ '_display', '=', 'block' ],
                        ];

                        self::repositionArrayElement($controls, "_columnCount", array_search('_display', array_keys($controls)) + 1);
                        self::repositionArrayElement($controls, "_columnCountColumnGap", array_search('_columnCount', array_keys($controls)) + 1);
                        self::repositionArrayElement($controls, "_columnFill", array_search('_columnCountColumnGap', array_keys($controls)) + 1);
                        self::repositionArrayElement($controls, "_columnWidth", array_search('_columnFill', array_keys($controls)) + 1);

                        return $controls;
                    } );
                }
                if( (AT__Helpers::is_structure_panel_tab_activated() && isset( $brxc_acf_fields['structure_panel_contextual_menu']) && !empty( $brxc_acf_fields['structure_panel_contextual_menu']) && is_array( $brxc_acf_fields['structure_panel_contextual_menu']) && in_array("class-converter",  $brxc_acf_fields['structure_panel_contextual_menu'])) && ($element == 'div' || $element == 'block' || $element == 'container' || $element == 'section') ){
                    add_filter( 'bricks/elements/' . $element . '/controls', function( $controls ) {
                        $controls['classConverterSeparator'] = [
                            'type'  => 'separator',
                            'label' => esc_html__( 'Class Converter', 'bricks' ),
                            'description' => esc_html__( 'When enabled, the class converter will process this element and their children as a standalone component with specific settings (basename, delimiter, convertion settings, etc...)', 'bricks' ),
                        ];
                        $controls['classConverterComponent'] = [
                            'tab'      => 'content',
                            'label'    => esc_html__( 'Set element as a root component' ),
                            'type'  => 'checkbox',
                        ];

                        return $controls;
                    } );
                }
            }
        }
    }

    public static function save_grid_guide_ajax_function(){

        if (!current_user_can('manage_options')) {
            wp_send_json_error('You don\'t have permission to perform this action.');
        }


        // Verify nonce
        if ( ! wp_verify_nonce( $_POST['nonce'], 'openai_ajax_nonce' ) ) {
            die( 'Invalid nonce' );
        }
        $option = get_option('bricks_advanced_themer_builder_settings');
        $option['gridGuide'] = $_POST['grid'];
        update_option('bricks_advanced_themer_builder_settings', $option);

        wp_send_json_success($option);
    }
    public static function save_query_manager_ajax_function(){

        if (!current_user_can('manage_options')) {
            wp_send_json_error('You don\'t have permission to perform this action.');
        }


        // Verify nonce
        if ( ! wp_verify_nonce( $_POST['nonce'], 'openai_ajax_nonce' ) ) {
            die( 'Invalid nonce' );
        }
     
        $option = get_option('bricks_advanced_themer_builder_settings');
        $post = $_POST['query_manager'];
        $cats = $_POST['query_manager_cats'];

		//$data = json_decode( $data, true );

        if (isset($post) && is_array($post)) {
            foreach ($post as &$item) {
                $item['args'] = stripslashes($item['args']);
                $item['description'] = stripslashes($item['description']);
            }
            // Remove the reference to avoid potential issues
            unset($item);
        }
        $option['query_manager'] = $post;
        $option['query_manager_cats'] = $cats;
        update_option('bricks_advanced_themer_builder_settings', $option);

        // wp_send_json_success($option);
    }

    public static function get_var_query_ajax_function(){
        if (!current_user_can('manage_options')) {
            wp_send_json_error('You don\'t have permission to perform this action.');
        }


        // Verify nonce
        if ( ! wp_verify_nonce( $_POST['nonce'], 'openai_ajax_nonce' ) ) {
            die( 'Invalid nonce' );
        }
        $settings = $_POST['settings'];
        $element_id = $_POST['element_id'];

        $query_vars = \Bricks\Query::prepare_query_vars_from_settings($settings, $element_id);
        wp_send_json_success($query_vars);

    }

    public static function save_global_css_ajax_function(){

        if (!current_user_can('manage_options')) {
            wp_send_json_error('You don\'t have permission to perform this action.');
        }


        // Verify nonce
        if ( ! wp_verify_nonce( $_POST['nonce'], 'openai_ajax_nonce' ) ) {
            die( 'Invalid nonce' );
        }
        $option = get_option('bricks_global_settings');
        $custom_css = $_POST['custom_css'];
        $option['customCss'] = $custom_css;
        update_option('bricks_global_settings', $option);

        wp_send_json_success($option);
    }

    public static function get_used_global_classes_id_on_site(){
        global $wpdb;

        $uniqueCssGlobalClasses = [];

        // Define the partial meta key you want to search for.
        $partialMetaKey = '_bricks_page_';

        // Create a custom SQL query to retrieve the relevant postmeta data.
        $sql = $wpdb->prepare(
            "SELECT post_id, meta_value
            FROM {$wpdb->postmeta}
            WHERE meta_key LIKE %s",
            '%' . $partialMetaKey . '%'
        );

        // Execute the query.
        $results = $wpdb->get_results($sql);

        // Loop through the results.
        foreach ($results as $result) {
            $metaValue = maybe_unserialize($result->meta_value);

            if (is_array($metaValue)) {
                foreach ($metaValue as $item) {
                    if (isset($item['settings']['_cssGlobalClasses']) && is_array($item['settings']['_cssGlobalClasses'])) {
                        $cssGlobalClasses = $item['settings']['_cssGlobalClasses'];

                        // Add the unique strings to the $uniqueCssGlobalClasses array.
                        $uniqueCssGlobalClasses = array_merge($uniqueCssGlobalClasses, $cssGlobalClasses);
                    }
                }
            }
        }

        // Remove duplicate values and reindex the array.
        $uniqueCssGlobalClasses = array_values(array_unique($uniqueCssGlobalClasses));
        var_dump($uniqueCssGlobalClasses);
        return $uniqueCssGlobalClasses;

    }

} 
