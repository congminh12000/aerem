<?php

defined( 'ABSPATH' ) || exit;

$wp_structuring_markup_filters = [
    'structuring_markup_meta_article',
    'structuring_markup_meta_blog_posting',
    'structuring_markup_meta_breadcrumb',
    'structuring_markup_meta_event',
    'structuring_markup_meta_local_business',
    'structuring_markup_meta_news_article',
    'structuring_markup_meta_organization',
    'structuring_markup_meta_person',
    'structuring_markup_meta_site_navigation',
    'structuring_markup_meta_video',
    'structuring_markup_meta_website',
];

foreach ( $wp_structuring_markup_filters as $filter ) {
    add_filter( $filter, function ( $schema ) {
        return eae_encode_json_recursive( $schema );
    }, EAE_FILTER_PRIORITY );
}
