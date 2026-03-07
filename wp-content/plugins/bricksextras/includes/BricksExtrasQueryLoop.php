<?php

namespace BricksExtras;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( class_exists( 'BricksExtrasQueryLoop' ) ) {
	return;
}

class BricksExtrasQueryLoop {

	public function init() {

		add_action( 'init', [ $this, 'add_queryLoopExtras_controls' ], 40 );
		add_filter( 'bricks/setup/control_options', [ $this, 'setup_queryLoopExtras_controls' ]);
		add_filter( 'bricks/query/run', [ $this, 'run_queryLoopExtras' ], 10, 2 );
		add_filter( 'bricks/query/loop_object', [ $this, 'extras_setup_post_data' ], 10, 3);

	}


	function add_queryLoopExtras_controls() {

		$elements = [ 
			'container', 
			'block', 
			'div', 

			'xdynamictable'
		];
	
		foreach ( $elements as $element ) {
			add_filter( "bricks/elements/{$element}/controls", [ $this, 'queryLoopExtras_controls' ], 40 );
		}

	
	}



	public function queryLoopExtras_controls( $controls ) {

		$taxonomies = \Bricks\Setup::$control_options['taxonomies'];
	 	 unset( $taxonomies['Wp_template_part'] );

		$newControls['extrasQuery'] = [
			'tab'         => 'content',
			'label'       => esc_html__( 'Query type', 'bricks' ),
			'type'        => 'select',
			'inline'      => true,
			'options'     => [
				'adjacent'  => 'Adjacent Posts',
				'related'  => 'Related Posts'
			],
			'placeholder' => esc_html__( 'Select...', 'bricks' ),
			'required'    => array(
				[ 'query.objectType', '=', 'queryLoopExtras' ],
				[ 'hasLoop', '!=', false ]
			),
			'rerender'    => true,
			'multiple'    => false,
		];
	
	
	  /* adjacentPost */
	
	  $newControls['adjacentPost'] = [
			'tab'         => 'content',
			'label'       => esc_html__( 'Adjacent Post', 'bricks' ),
			'type'        => 'select',
			'inline'      => true,
			'options'     => [
				'prev'  => esc_html__( 'Previous', 'bricks' ),
				'next'  => esc_html__( 'Next', 'bricks' ),
			],
			'placeholder' => esc_html__( 'Previous', 'bricks' ),
			'required' => [
				[ 'extrasQuery', '=', 'adjacent'],
				[ 'query.objectType', '=', 'queryLoopExtras' ],
				[ 'hasLoop', '!=', false ]
			],
			'rerender'    => true,
			'multiple'    => false,
		];

		$newControls['adjacentPostSameTerm'] = [
			'tab'      => 'content',
			'label'    => esc_html__( 'Post should be in the same taxonomy term', 'bricks' ),
			'type'     => 'checkbox',
			'rerender'  => true,
			'required' => [
				[ 'extrasQuery', '=', 'adjacent'],
				[ 'query.objectType', '=', 'queryLoopExtras' ],
				[ 'hasLoop', '!=', false ]
			]
		  ];

		  $newControls['adjacentTaxonomy'] = [
			'tab'      => 'content',
			'label'    => esc_html__( 'Taxonomy', 'bricks' ),
			'type'        => 'select',
			'options'     => $taxonomies,
			'multiple'    => false,
			'description' => esc_html__( 'Taxonomy adjacent posts must have in common.', 'bricks' ),
			'placeholder' => [
				'category',
			],
			'required' => [
				[ 'extrasQuery', '=', 'adjacent'],
				[ 'query.objectType', '=', 'queryLoopExtras' ],
				[ 'hasLoop', '!=', false ],
				['adjacentPostSameTerm', '=', true]
			]
		  ];

		 $newControls['adjacentPostExcludedTerms'] = [
			'tab'      => 'content',
			'label'    => esc_html__( 'Excluded Term IDs', 'bricks' ),
			'type'     => 'text',
			'required' => [
				[ 'extrasQuery', '=', 'adjacent'],
				[ 'query.objectType', '=', 'queryLoopExtras' ],
				[ 'hasLoop', '!=', false ],
				['adjacentPostSameTerm', '=', true]
			]
		  ];
	
	
	
	  /* related posts */
	
	  $newControls['post_type'] = [
		'tab'         => 'content',
		'label'       => esc_html__( 'Post type', 'bricks' ),
		'type'        => 'select',
		'options'     => bricks_is_builder() ? \Bricks\Helpers::get_registered_post_types() : [],
		'clearable'   => true,
		'inline'      => true,
		'searchable'  => true,
		'placeholder' => esc_html__( 'Default', 'bricks' ),
		'required' => [
		  [ 'extrasQuery', '=', 'related'],
		  [ 'query.objectType', '=', 'queryLoopExtras' ],
				[ 'hasLoop', '!=', false ]
		],
	  ];
	
	  $newControls['count'] = [
		'tab'         => 'content',
		'label'       => esc_html__( 'Max. related posts', 'bricks' ),
		'type'        => 'number',
		'min'         => 1,
		'max'         => 4,
		'placeholder' => 3,
		'required' => [
		  [ 'extrasQuery', '=', 'related'],
		  [ 'query.objectType', '=', 'queryLoopExtras' ],
				[ 'hasLoop', '!=', false ]
		],
	  ];
	
	  $newControls['order'] = [
		'tab'         => 'content',
		'label'       => esc_html__( 'Order', 'bricks' ),
		'type'        => 'select',
		'options'     => [
		  'ASC' => esc_html__( 'Ascending', 'bricks' ),
		  'DESC' => esc_html__( 'Descending', 'bricks' ),
		],
		'inline'      => true,
		'placeholder' => esc_html__( 'Descending', 'bricks' ),
		'required' => [
		  [ 'extrasQuery', '=', 'related'],
		  [ 'query.objectType', '=', 'queryLoopExtras' ],
				[ 'hasLoop', '!=', false ]
		],
	  ];
	
	  $newControls['orderby'] = [
		'tab'         => 'content',
		'label'       => esc_html__( 'Order by', 'bricks' ),
		'type'        => 'select',
		'options'     => \Bricks\Setup::$control_options['queryOrderBy'],
		'inline'      => true,
		'placeholder' => esc_html__( 'Random', 'bricks' ),
		'required' => [
		  [ 'extrasQuery', '=', 'related'],
		  [ 'query.objectType', '=', 'queryLoopExtras' ],
		  [ 'hasLoop', '!=', false ]
		],
	  ];
	
	  $newControls['taxonomies'] = [
		'tab'         => 'content',
		'label'       => esc_html__( 'Common taxonomies', 'bricks' ),
		'type'        => 'select',
		'options'     => $taxonomies,
		'multiple'    => true,
		'default'     => [
		  'category',
		  'post_tag'
		],
		'required' => [
		  [ 'extrasQuery', '=', 'related'],
		  [ 'query.objectType', '=', 'queryLoopExtras' ],
		  [ 'hasLoop', '!=', false ]
		],
	  ];
	
	
	
	  /* related posts */
	
	  $newControls['extrasCustomQueryCode'] = [
		'tab' => 'content',
		'label' => esc_html__( 'WPQuery args', 'bricks' ),
		'type' => 'code',
		'mode' => 'php',
		'clearable' => false, // Required to always have 'mode' set for CodeMirror
		'default'   => "<style>\nh1.my-heading {\n  color: crimson;\n}\n</style>\n\n<h1 class='my-heading'>Just some custom HTML</h1>",
		'rerender'  => false,
		'required' => [
		  [ 'extrasQuery', '=', 'custom'],
		  [ 'query.objectType', '=', 'queryLoopExtras' ],
				[ 'hasLoop', '!=', false ]
		]
	  ];
	
	  $newControls['executeCode'] = [
		'tab'      => 'content',
		'label'    => esc_html__( 'Execute', 'bricks' ),
		'type'     => 'checkbox',
		'rerender'  => true,
		'required' => [
		  [ 'extrasQuery', '=', 'custom'],
		  [ 'query.objectType', '=', 'queryLoopExtras' ],
				[ 'hasLoop', '!=', false ]
		]
	  ];
	
		$query_key_index = absint( array_search( 'query', array_keys( $controls ) ) );
		$new_controls    = array_slice( $controls, 0, $query_key_index + 1, true ) + $newControls + array_slice( $controls, $query_key_index + 1, null, true );
	
		return $new_controls;
	
	}
	


	function setup_queryLoopExtras_controls( $control_options ) {

		$control_options['queryTypes']['queryLoopExtras'] = esc_html__( 'Extras', 'bricks' );
		return $control_options;
	
	}


	public function run_queryLoopExtras( $results, $query_obj ) {

		if ( $query_obj->object_type !== 'queryLoopExtras' ) {
			return $results;
		}
	
		$settings = $query_obj->settings;
	
		if ( ! $settings['hasLoop'] ) {
			return [];
		}
	
		$extrasQuery = isset( $settings['extrasQuery'] ) ? $settings['extrasQuery'] : false;
	
	  if ('adjacent' === $extrasQuery) {
	
		/* adjacent posts */
	
		$adjacentPost = isset( $settings['adjacentPost'] ) ? $settings['adjacentPost'] : 'previous';
		$adjacentPostSameTerm = isset( $settings['adjacentPostSameTerm'] );
		$excludedTerms = isset( $settings['adjacentPostExcludedTerms'] ) ? $settings['adjacentPostExcludedTerms'] : '';
		$adjacentTaxonomy = isset( $settings['adjacentTaxonomy'] ) ? $settings['adjacentTaxonomy'] : 'category';
	
		if ( 'prev' === $adjacentPost && !empty( get_previous_post($adjacentPostSameTerm, $excludedTerms, $adjacentTaxonomy) ) ) {
		  return [ get_previous_post($adjacentPostSameTerm, $excludedTerms, $adjacentTaxonomy) ];
		}
	
		if ( 'next' === $adjacentPost && !empty( get_next_post($adjacentPostSameTerm, $excludedTerms, $adjacentTaxonomy) ) ) {
		  return [ get_next_post($adjacentPostSameTerm, $excludedTerms, $adjacentTaxonomy) ];
		}
	
	  } elseif ( 'related' === $extrasQuery ) {
	
		/* related posts */
	
		global $post;
	
		$post_id = $post->ID;
	
			$args = [
				'posts_per_page' => isset( $settings['count'] ) ? $settings['count'] : 3,
				'post__not_in'   => [ $post_id ],
				'no_found_rows'  => true, // No pagination
				'orderby'        => isset( $settings['orderby'] ) ? $settings['orderby'] : 'rand',
				'order'          => isset( $settings['order'] ) ? $settings['order'] : 'DESC',
			];
	
			if ( ! empty( $settings['post_type'] ) ) {
				$args['post_type'] = $settings['post_type'];
			}
	
			$taxonomies = ! empty( $settings['taxonomies'] ) ? $settings['taxonomies'] : [];
	
			foreach ( $taxonomies as $taxonomy ) {
				$terms_ids = wp_get_post_terms(
					$post_id,
					$taxonomy,
					[ 'fields' => 'ids' ]
				);
	
				if ( ! empty( $terms_ids ) ) {
					$args['tax_query'][] = [
						'taxonomy' => $taxonomy,
						'field'    => 'term_id',
						'terms'    => $terms_ids,
					];
				}
			}
	
			if ( count( $taxonomies ) > 1 && isset( $args['tax_query'] ) ) {
				$args['tax_query']['relation'] = 'OR';
			}
	
		$args['post_status'] = 'publish';
	
		$posts_query = new \WP_Query( $args );
	
		return $posts_query->posts;
	
	  } else {
		return [];
	  }
	
	
	}


	function extras_setup_post_data( $loop_object, $loop_key, $query_obj ) {
    
		if ( $query_obj->object_type !== 'queryLoopExtras' ) {
			return $loop_object;
		}
	
		 global $post;
		 $post = get_post( $loop_object );
		 setup_postdata( $post );
		
		 return $loop_object;
	
	}
	

}
