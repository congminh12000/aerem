<?php 
/**
 * Register/enqueue custom scripts and styles
 */
add_action( 'wp_enqueue_scripts', function() {
	wp_enqueue_style( 'bricks-child', get_stylesheet_uri(), ['bricks-frontend'], filemtime( get_stylesheet_directory() . '/style.css' ) );
} );

/**
 * Register custom elements
 */
add_action( 'init', function() {
  $element_files = [
    __DIR__ . '/elements/title.php',
  ];

  foreach ( $element_files as $file ) {
    \Bricks\Elements::register_element( $file );
  }
}, 11 );

/**
 * Filter which elements to show in the builder
 * 
 * Simple outcomment (prefix: //) the elements you don't want to use in Bricks
 */
function bricks_filter_builder_elements( $elements ) {
	$elements = [
		// Basic
		// 'container', // since 1.2
		// 'heading',
		'text',
		'button',
		'icon',
		'image',
		'video',

		// General
		'divider',
		'icon-box',
		'list',
		'accordion',
		'tabs',
		'form',
		'map',
		'alert',
		'animated-typing',
		'countdown',
		'counter',
		'pricing-tables',
		'progress-bar',
		'pie-chart',
		'team-members',
		'testimonials',
		'html',
		'code',
		'logo',

		// Media
		'image-gallery',
		'audio',
		'carousel',
		'slider',
		'svg',

		// Social
		'social-icons',
		'facebook-page',
		'instagram-feed',

		// WordPress
		'wordpress',
		'posts',
		'nav-menu',
		'sidebar',
		'search',
		'shortcode',

		// Single
		'post-title',
		'post-excerpt',
		'post-meta',
		'post-content',
		'post-sharing',
		'post-related-posts',
		'post-author',
		'post-comments',
		'post-taxonomy',
		'post-navigation',

		// Hidden in builder panel
		'section',
		'row',
		'column',
	];

	return $elements;
}
// add_filter( 'bricks/builder/elements', 'bricks_filter_builder_elements' );

/**
 * Add text strings to builder
 */
add_filter( 'bricks/builder/i18n', function( $i18n ) {
  // For element category 'custom'
  $i18n['custom'] = esc_html__( 'Custom', 'bricks' );

  return $i18n;
} );

/**
 * Custom save messages
 */
add_filter( 'bricks/builder/save_messages', function( $messages ) {
	// First option: Add individual save message
	$messages[] = 'Yasss';

	// Second option: Replace all save messages
	$messages = [
		'Done',
		'Cool',
		'High five!',
	];

  return $messages;
} );

/**
 * Customize standard fonts
 */
// add_filter( 'bricks/builder/standard_fonts', function( $standard_fonts ) {
// 	// First option: Add individual standard font
// 	$standard_fonts[] = 'Verdana';

// 	// Second option: Replace all standard fonts
// 	$standard_fonts = [
// 		'Georgia',
// 		'Times New Roman',
// 		'Verdana',
// 	];

//   return $standard_fonts;
// } );

/** 
 * Add custom map style
 */
// add_filter( 'bricks/builder/map_styles', function( $map_styles ) {
//   // Shades of grey (https://snazzymaps.com/style/38/shades-of-grey)
//   $map_styles['shadesOfGrey'] = [
//     'label' => esc_html__( 'Shades of grey', 'bricks' ),
//     'style' => '[ { "featureType": "all", "elementType": "labels.text.fill", "stylers": [ { "saturation": 36 }, { "color": "#000000" }, { "lightness": 40 } ] }, { "featureType": "all", "elementType": "labels.text.stroke", "stylers": [ { "visibility": "on" }, { "color": "#000000" }, { "lightness": 16 } ] }, { "featureType": "all", "elementType": "labels.icon", "stylers": [ { "visibility": "off" } ] }, { "featureType": "administrative", "elementType": "geometry.fill", "stylers": [ { "color": "#000000" }, { "lightness": 20 } ] }, { "featureType": "administrative", "elementType": "geometry.stroke", "stylers": [ { "color": "#000000" }, { "lightness": 17 }, { "weight": 1.2 } ] }, { "featureType": "landscape", "elementType": "geometry", "stylers": [ { "color": "#000000" }, { "lightness": 20 } ] }, { "featureType": "poi", "elementType": "geometry", "stylers": [ { "color": "#000000" }, { "lightness": 21 } ] }, { "featureType": "road.highway", "elementType": "geometry.fill", "stylers": [ { "color": "#000000" }, { "lightness": 17 } ] }, { "featureType": "road.highway", "elementType": "geometry.stroke", "stylers": [ { "color": "#000000" }, { "lightness": 29 }, { "weight": 0.2 } ] }, { "featureType": "road.arterial", "elementType": "geometry", "stylers": [ { "color": "#000000" }, { "lightness": 18 } ] }, { "featureType": "road.local", "elementType": "geometry", "stylers": [ { "color": "#000000" }, { "lightness": 16 } ] }, { "featureType": "transit", "elementType": "geometry", "stylers": [ { "color": "#000000" }, { "lightness": 19 } ] }, { "featureType": "water", "elementType": "geometry", "stylers": [ { "color": "#000000" }, { "lightness": 17 } ] } ]'
//   ];

//   return $map_styles;
// } );


function translate_post_type($post_type) {
	switch($post_type) {
		case 'post':
			return 'Tin tức';
		case 'product':
			return 'Sản phẩm';
		case 'service':
			return 'Dịch vụ';
		default:
			return '';
	}
}

add_filter( 'bricks/code/echo_function_names', function() {
	return [
	  'hover', // function does not exist
	  'get_job_listing',
	  'translate_post_type',
	  'get_post_type',
	  'date',
	  'get_post_field',
	  'get_post',
	  'get_solution_url_by_brand'
	];
  } );

function get_solution_url_by_brand() {
	global $post;

	return "/giai-phap/?filter=". get_post_field('post_name',$post->ID);
}
  

function get_job_listing() {
	// Bước 1: Thiết lập các tham số cho WP_Query
	$args = array(
		'post_type' => 'job', // Chỉ lấy các bài đăng có post_type là 'job'
		'posts_per_page' => -1, // Lấy tất cả bài đăng, sử dụng số lượng cụ thể để giới hạn số bài đăng
		'post_status'    => 'publish' // Chỉ lấy các bài đăng có trạng thái là 'publish'
	);

	// Bước 2: Tạo một đối tượng WP_Query mới
	$query = new WP_Query($args);

	$result = [];

	// Bước 3: Sử dụng vòng lặp của WP_Query để duyệt qua các bài đăng
	if ($query->have_posts()) {
		while ($query->have_posts()) {
			$query->the_post();
			
			$result[] = get_the_title();
		}
	}

	// Bước 5: Đặt lại post data
	wp_reset_postdata();

	return $result;
}

function pbd_marker_listing_heading( $index = 0 ) {
	$lang = get_simple_language_parameter();

	$pbd_marker_listing = get_field('pbd_marker_listing', 'option')[$index] ?? [];

	if ($lang == 'en') {
		return $pbd_marker_listing['heading_en'] ?? '';
	} else {
		return $pbd_marker_listing['heading'] ?? '';
	}
}

function pbd_marker_listing_content( $index = 0 ) {
	$lang = get_simple_language_parameter();

	$pbd_marker_listing = get_field('pbd_marker_listing', 'option')[$index] ?? [];

	if ($lang == 'en') {
		return $pbd_marker_listing['content_en'] ?? '';
	} else {
		return $pbd_marker_listing['content'] ?? '';
	}
}

function get_simple_language_parameter() {
    // Lấy phần URI của URL hiện tại
    $uri = trim($_SERVER['REQUEST_URI'], '/');

    // Tách URI thành các phần dựa trên dấu gạch chéo
    $parts = explode('/', $uri);

    // Giả định mã ngôn ngữ luôn ở phần đầu tiên của URI sau khi tách
    $language = $parts[0];

    // Kiểm tra xem mã ngôn ngữ có phải là 2 ký tự
    if(strlen($language) == 2) {
        return $language;
    }

    // Trả về false nếu mã ngôn ngữ không hợp lệ
    return false;
}

function p($args) {
	echo '<pre>';
	print_r($args);
	die;
}