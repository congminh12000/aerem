<?php

namespace BricksExtras;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists("\Bricks\Integrations\Dynamic_Data\Providers\Base") ) {
	return;
}

class Provider_Extras extends \Bricks\Integrations\Dynamic_Data\Providers\Base {

	public function register_tags() {
		$tags = $this->get_tags_config();

		foreach ( $tags as $key => $tag ) {
			
			$this->tags[ $key ] = [
				'name'     => '{' . $key . '}',
				'label'    => $tag['label'],
				'group'    => $tag['group'],
				'provider' => $this->name
			];

			if ( ! empty( $tag['render'] ) ) {
				$this->tags[ $key ]['render'] = $tag['render'];
			}
		}
	}

	public function get_tags_config() {

		$extras_group = esc_html__( 'Extras', 'bricks' );

		$tags = [

			'x_post_reading_time' => [
				'label' => esc_html__( 'Post reading time [extras]', 'bricks' ),
				'group' => esc_html__( 'post', 'bricks' )
			],
			'x_post_terms_list' => [
				'label' => esc_html__( 'Post terms list [extras]', 'bricks' ),
				'group' => esc_html__( 'post', 'bricks' )
			],
			'x_url_parameter' => [
				'label' => esc_html__( 'URL parameter [extras]', 'bricks' ),
				'group' => $extras_group
			],
			'x_est_year_current_year' => [
				'label' => esc_html__( 'Est. year - current year [extras]', 'bricks' ),
				'group' => $extras_group
			],
			'x_loop_index' => [
				'label' => esc_html__( 'Loop index [extras]', 'bricks' ),
				'group' => $extras_group
			],
			'x_parent_loop_index' => [
				'label' => esc_html__( 'Parent loop index [extras]', 'bricks' ),
				'group' => $extras_group
			],

		];

		return $tags;
	}

	/**
	 * Main function to render the tag value for WordPress provider
	 *
	 * @param [type] $tag
	 * @param [type] $post
	 * @param [type] $args
	 * @param [type] $context
	 * @return void
	 */
	public function get_tag_value( $tag, $post, $args, $context ) {
		
		$post_id = isset( $post->ID ) ? $post->ID : '';

		// STEP: Check for filter args
		$filters = $this->get_filters_from_args( $args );

		// STEP: Get the value
		$value = '';

		$render = isset( $this->tags[ $tag ]['render'] ) ? $this->tags[ $tag ]['render'] : $tag;

		switch ( $render ) {

			/* est reading time */
			case 'x_post_reading_time':
				if ( ! empty( $filters['meta_key'] ) ) {
					
					$meta_keys = explode( ",", $filters['meta_key'] ); 
					
					$text_after_singular = isset( $meta_keys[0] ) ? $meta_keys[0] : 'minute';
					$text_after_plural = isset( $meta_keys[1] ) ? $meta_keys[1] : 'minutes';
					$wpm = isset( $meta_keys[2] ) ? $meta_keys[2] : 225;

				} else {
					$text_after_singular = 'minute';
					$text_after_plural = 'minutes';
					$wpm = 225;
				}	

				if ( null != $post ) {
					
					$content = get_post_field( 'post_content', $post->ID );
					$word_count = str_word_count( strip_tags( $content ) );
					
					$readingtime = ceil( $word_count / $wpm );
					if ($readingtime == 1) {
						$timer = " ". $text_after_singular;
					} else {
						$timer = " ". $text_after_plural;
					}
					
					$value = $readingtime . $timer;

				}
				
			break;


			/* post terms list */
			case 'x_post_terms_list':

				if ( ! empty( $filters['meta_key'] ) ) {
					$taxonomy = isset( $filters['meta_key'] ) ? $filters['meta_key'] : '';
				} else {
					$taxonomy =  'category';
				}

				$value = strip_tags( get_the_term_list( $post->ID, $taxonomy, '', ', ' ) );
				
			break;	


			/* URL Parameter */
			case 'x_url_parameter':

				if ( ! empty( $filters['meta_key'] ) ) {
					$variable = isset( $filters['meta_key'] ) ? $filters['meta_key'] : '';
				} else {
					$variable =  's';
				}

				$value = isset( $_GET[ $variable ] ) ? sanitize_text_field( $_GET[ $variable ] ) : null;

				
			break;	

			/* URL Parameter */
			case 'x_est_year_current_year':

				if ( ! empty( $filters['num_words'] ) ) {
					$est_year = isset( $filters['num_words'] ) ? $filters['num_words'] : false;
					if (false !== $est_year) {
						$value = $filters['num_words'] == date( 'Y' ) ? date( 'Y' ) : $filters['num_words'] . ' - ' . date( 'Y' );
					}
				} else {
					$value = date( 'Y' );
				}

				
			break;	

			/* Loop index */
			case 'x_loop_index':

				if ( method_exists( '\Bricks\Query', 'get_query_object' ) ) {

					$query_object = \Bricks\Query::get_query_object();

					if( $query_object ) {

						$offset = isset( $filters['num_words'] ) ? $filters['num_words'] : 0;
						$value = intval( $query_object::get_loop_index() ) + $offset;
					}

				}
				
			break;	

			/* Parent Loop index */
			case 'x_parent_loop_index':

				if ( method_exists( '\Bricks\Query', 'get_query_for_element_id' ) ) {

					$level = isset( $filters['num_words'] ) ? $filters['num_words'] : 1;

					$value = \BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level($level) ? \Bricks\Query::get_query_for_element_id( \Bricks\Query::get_query_element_id( \BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level($level) ) )->loop_index : '';

				}
				
			break;	

			
		}

		// STEP: Apply context (text, link, image, media)
		$value = $this->format_value_for_context( $value, $tag, $post_id, $filters, $context );

		return $value;
	}

}
