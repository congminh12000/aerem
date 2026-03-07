<?php
namespace Advanced_Themer_Bricks;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class AT__Ajax{
    public static function save_section_template_ajax_function(){
        // Only Full Access users can create new templates (@since 1.5.4)
		if ( ! \Bricks\Capabilities::current_user_has_full_access() ) {
			wp_send_json_error( 'verify_request: Sorry, you are not allowed to perform this action.' );
		}

        // Verify nonce
        if ( ! wp_verify_nonce( $_POST['nonce'], 'openai_ajax_nonce' ) ) {
            die( 'Invalid nonce' );
        }

        // create featured image
        $data = \Bricks\Ajax::decode($_POST['data_template'] ?? []);

        $title = preg_replace('/[^a-zA-Z0-9]+/', '-', $data["title"]);
        $title = strtolower($title);
        $title = $title . "_" . AT__Helpers::generate_unique_string( 6 );
        $upload_dir  = wp_upload_dir();
        $upload_path = str_replace( '/', DIRECTORY_SEPARATOR, $upload_dir['path'] ) . DIRECTORY_SEPARATOR;

        $img             = str_replace( 'data:image/png;base64,', '', $data["img"] );
        $img             = str_replace( ' ', '+', $img );
        $decoded         = base64_decode( $img );
        $filename        = $title . '.png';
        $file_type       = 'image/png';

        // Save the image in the uploads directory.
        $upload_file = file_put_contents( $upload_path . $filename , $decoded );
        $target_file = trailingslashit($upload_dir['path']) . $filename ;

        $attachment = array(
            'post_mime_type' => $file_type,
            'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename  ) ),
            'post_content'   => '',
            'post_status'    => 'inherit',
            'guid'           => $upload_dir['url'] . '/' . basename( $filename  )
        );

        $attach_id = wp_insert_attachment( $attachment, $upload_dir['path'] . '/' . $filename  );
        $attachment_data = wp_generate_attachment_metadata($attach_id, $target_file);
        wp_update_attachment_metadata($attach_id, $attachment_data);


		// Insert new template into db
		$insert_post_data = [
			'post_status' => current_user_can( 'publish_posts' ) ? 'publish' : 'pending',
			'post_title'  => ! empty( $data["title"] ) ? $data["title"] : esc_html__( '(no title)', 'bricks' ),
			'post_type'   => BRICKS_DB_TEMPLATE_SLUG,
		];

		$insert_post_data['tax_input'] = [];

		// Save template bundle term
		if ( isset( $data["bundle"] ) ) {
			$insert_post_data['tax_input'][ BRICKS_DB_TEMPLATE_TAX_BUNDLE ] = $data["bundle"];
		}

		// Save template tags
		if ( isset( $data["tags"] ) ) {
			$insert_post_data['tax_input'][ BRICKS_DB_TEMPLATE_TAX_TAG ] = $data["tags"];
		}

		$template_id = wp_insert_post( $insert_post_data );

        set_post_thumbnail($template_id, $attach_id);

        switch ( $data["templateType"] ) {
			case 'header':
				$meta_key          = BRICKS_DB_PAGE_HEADER;
				break;

			case 'footer':
				$meta_key          = BRICKS_DB_PAGE_FOOTER;
				break;

			default:
				$meta_key          = BRICKS_DB_PAGE_CONTENT;
				break;
		}

        // Save data
		update_post_meta( $template_id, $meta_key, $data["elements"] );

		// Save template type in post meta
		if ( isset( $data["type"] ) ) {
			update_post_meta(
				$template_id,
				BRICKS_DB_TEMPLATE_TYPE,
				$data["type"]
			);
		}

		wp_send_json_success($data["elements"]);
    }
}