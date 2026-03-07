<?php

defined( 'ABSPATH' ) || exit;

add_filter( 'mylisting/single/output_schema', function ( $output ) {
    $json = preg_replace_callback(
        '/<script type="application\/ld\+json">(.+?)<\/script>/s',
        function ( $matches ) {
            return preg_replace( '/[[:cntrl:]]/', '', trim( $matches[ 1 ] ) );
        },
        $output
    );

    $data = eae_encode_json_recursive( json_decode( $json, true ) );

    return sprintf(
        '<script type="application/ld+json">%s</script>',
        json_encode( $data, JSON_PRETTY_PRINT )
    );
}, EAE_FILTER_PRIORITY );
