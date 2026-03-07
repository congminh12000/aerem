<?php

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'mb_detect_encoding' ) ) :

function mb_detect_encoding( $str, $encoding_list = null, $strict = null ) {
    return false;
}

endif;

if ( ! function_exists( 'mb_convert_encoding' ) ) :

function mb_convert_encoding( $str, $to_encoding, $from_encoding ) {
    if ( function_exists( 'iconv' ) ) {
        return iconv( $from_encoding, $to_encoding, $str );
    }

    return $str;
}

endif;

if ( ! function_exists( 'mb_ord' ) ) :

function mb_ord( $string ) {
    if ( extension_loaded( 'mbstring' ) === true ) {
        mb_language( 'Neutral' );
        mb_internal_encoding( 'UTF-8' );
        mb_detect_order( [ 'UTF-8', 'ISO-8859-15', 'ISO-8859-1', 'ASCII' ] );

        $result = unpack( 'N', mb_convert_encoding( $string, 'UCS-4BE', 'UTF-8' ) );

        if ( is_array( $result ) === true ) {
            return $result[ 1 ];
        }
    }

    return ord( $string );
}

endif;
