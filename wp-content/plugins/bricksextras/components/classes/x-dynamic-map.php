<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class X_Dynamic_Map extends \Bricks\Element {

  // Element properties
  public $category     = 'extras';
	public $name         = 'xdynamicmap';
	public $icon         = 'ti-map-alt';
	public $css_selector = '';
	public $scripts      = ['xDynamicMap'];
  public $loop_index = 0;

  
  public function get_label() {
	  return esc_html__( 'Dynamic Map', 'extras' );
  }
  public function set_control_groups() {

    $this->control_groups['locations'] = [
      'title' => esc_html__( 'Add Locations', 'bricks' ),
    ];

    $this->control_groups['markers'] = [
      'title' => esc_html__( 'Markers', 'bricks' ),
    ];

    $this->control_groups['theme'] = [
      'title' => esc_html__( 'Theme', 'bricks' ),
    ];

    $this->control_groups['map'] = [
      'title' => esc_html__( 'Controls', 'bricks' ),
    ];

    $this->control_groups['behaviour'] = [
      'title' => esc_html__( 'Behaviour', 'bricks' ),
    ];

  }

  public function set_controls() {

    
    /* locations */

    $this->controls['hasLoop'] = [
			'tab'   => 'content',
			'label' => esc_html__( 'Add locations with query loop', 'bricks' ),
			'type'  => 'checkbox',
      'group' => 'locations',
      'required' => [ 
				[ 'externalLoop', '!=', true ],
			],
		];

    $this->controls['externalLoop'] = [
			'tab'   => 'content',
			'label' => esc_html__( 'Add locations with external query loop', 'bricks' ),
			'type'  => 'checkbox',
      'group' => 'locations',
      'required' => [ 
				[ 'hasLoop', '=', '' ],
			],
		];

		$this->controls['query'] = [
			'tab'      => 'content',
			'label'    => esc_html__( 'Query', 'bricks' ),
			'type'     => 'query',
      'group' => 'locations',
			'popup'    => true,
			'inline'   => true,
			'required' => [ 
				[ 'hasLoop', '!=', '' ],
        [ 'externalLoop', '!=', true ],
			],
		];

    $this->controls['locationsRepeater'] = [
			'tab'         => 'content',
			'placeholder' => esc_html__( 'Location', 'bricks' ),
			'type'        => 'repeater',
      'titleProperty' => 'label',
      'group' => 'locations',
			'fields'      => [
				'label'    => [
					'label' => esc_html__( 'Label', 'bricks' ),
					'type'  => 'text',
          'inline' => true,
          'hasDynamicData' => false,
				],
        'lattitude'    => [
					'label' => esc_html__( 'Lattitude', 'bricks' ),
					'type'  => 'text',
          'hasDynamicData' => false,
          'inline' => true,
				],
        'longitude'    => [
					'label' => esc_html__( 'Longitude', 'bricks' ),
					'type'  => 'text',
          'hasDynamicData' => false,
          'inline' => true,
				],
        'details'    => [
					'label' => esc_html__( 'Details', 'bricks' ),
					'type'  => 'editor',
				],
        'image' => [
          'label' => esc_html__( 'Marker image', 'bricks' ),
          'type' => 'image'
        ]
			],
      'required' => [
        ['hasLoop', '!=', true],
        [ 'externalLoop', '!=', true ],
      ]
		];

    $this->controls['label'] = [
			'tab'   => 'content',
			'label' => esc_html__( 'Label', 'bricks' ),
			'type'  => 'text',
      'group' => 'locations',
      'inline' => true,
      'required' => [
        ['hasLoop', '=', true]
      ]
		];

    $this->controls['lattitude'] = [
			'tab'   => 'content',
			'label' => esc_html__( 'Lattitude', 'bricks' ),
			'type'  => 'text',
      'group' => 'locations',
      'inline' => true,
      'required' => [
        ['hasLoop', '=', true]
      ]
		];

    $this->controls['longitude'] = [
			'tab'   => 'content',
			'label' => esc_html__( 'Longitude', 'bricks' ),
			'type'  => 'text',
      'group' => 'locations',
      'inline' => true,
      'required' => [
        ['hasLoop', '=', true]
      ]
		];

    $this->controls['details'] = [
			'tab'   => 'content',
			'label' => esc_html__( 'Details', 'bricks' ),
			'type'  => 'editor',
      'group' => 'locations',
      'required' => [
        ['hasLoop', '=', true]
      ]
		];


    /* markers */

    $markers = '.leaflet-marker-pane img';

    $this->controls['markerWidth'] = [
      'group' => 'markers',
			'label'    => esc_html__( 'Width', 'bricks' ),
			'type'        => 'number',
      'units'        => true,
      'rerender'  => true,
      'inline'      => true,
      'css' => [
				[
				'property' => '--x-map-marker-width',
				'selector' => '.leaflet-map-pane',
				],
			],
		];

    $this->controls['markerHeight'] = [
      'group' => 'markers',
			'label'    => esc_html__( 'Height', 'bricks' ),
			'type'        => 'number',
      'units'        => true,
      'inline'      => true,
      'rerender'  => true,
      'css' => [
				[
				'property' => '--x-map-marker-height',
				'selector' => '.leaflet-map-pane',
				],
			],
		];


    $this->controls['maybeCluster'] = [
      'group' => 'markers',
			'label'    => esc_html__( 'Group markers into clusters', 'bricks' ),
			'type'        => 'select',
			'options'     => [
				'enable' => esc_html__( 'Enable', 'bricks' ),
				'disable'  => esc_html__( 'Disable', 'bricks' ),
			],
      'inline'      => true,
      'placeholder' => esc_html__( 'Disable', 'bricks' ),
		];


    /* theme */

    $this->controls['themeSep'] = [
      'group' => 'theme',
			'label'    => esc_html__( 'Providers', 'bricks' ),
			'type'        => 'separator',
			'description' => esc_html__( 'A collection of free to use providers', 'bricks' ),
      'inline'      => true,
		];

    $providers = [
      //'Stadia.AlidadeSmooth' => 'Alidade Smooth (Stadia)',
      //'Stadia.AlidadeSmoothDark' => 'Alidade Smooth Dark (Stadia)',
      'OpenStreetMap.Mapnik' => 'Mapnik (OpenStreetMap)',
      'OpenTopoMap' => 'Open Topo Map',
      //'Stadia.OSMBright' => 'OSMBright (Stadia)',
      //'Stadia.Outdoors' => 'Outdoors (Stadia)',
      //'Stadia.StamenToner' => 'Stamen Toner (Stadia)',
      //'Stadia.StamenTonerBackground' => 'Stamen Toner Background (Stadia)',
      //'Stadia.StamenTonerLite' => 'Stamen Toner Lite (Stadia)',
      //'Stadia.StamenWatercolor' => 'Stamen Watercolor (Stadia)',
      //'Stadia.StamenTerrain' => 'Stamen Terrain (Stadia)',
      //'Stadia.StamenTerrainBackground' => 'Stamen Terrain Background (Stadia)',
      'Esri.WorldStreetMap' => 'WorldStreetMap (Esri)',
      'Esri.DeLorme' => 'DeLorme (Esri)',
      'Esri.WorldImagery' => 'World Imagery (Esri)',
      'Esri.WorldTerrain' => 'World Terrain (Esri)',
      'Esri.WorldGrayCanvas' => 'World Gray Canvas (Esri)',
      'CartoDB.Positron' => 'Positron (Carto)',
      'CartoDB.PositronNoLabels' => 'PositronNoLabels (Carto)',
      'CartoDB.PositronOnlyLabels' => 'PositronOnlyLabels (Carto)',
      'CartoDB.DarkMatter' => 'DarkMatter (Carto)',
      'CartoDB.DarkMatterNoLabels' => 'DarkMatterNoLabels (Carto)',
      'CartoDB.DarkMatterOnlyLabels' => 'DarkMatterOnlyLabels (Carto)',
      'CartoDB.Voyager' => 'Voyager (Carto)',
      'CartoDB.VoyagerNoLabels' => 'VoyagerNoLabels (Carto)',

      'CartoDB.VoyagerNoLabels' => 'VoyagerNoLabels (Carto)',
      'CartoDB.VoyagerOnlyLabels' => 'VoyagerOnlyLabels (Carto)',
      'CartoDB.VoyagerLabelsUnder' => 'VoyagerLabelsUnder (Carto)',
      'CartoDB.VoyagerNoLabels' => 'VoyagerNoLabels (Carto)',


    ];

    $this->controls['theme'] = [
      'group' => 'theme',
			'label'    => esc_html__( 'Theme', 'bricks' ),
			'type'        => 'select',
      'searchable' => true,
			'options'     => $providers,
			'placeholder' => esc_html__( 'Default', 'bricks' ),
      //'inline'      => true,
		];



    /* map */

    $this->controls['zoomControl'] = [
      'group' => 'map',
			'label'    => esc_html__( 'Zoom', 'bricks' ),
			'type'        => 'select',
			'options'     => [
				'enable' => esc_html__( 'Enable', 'bricks' ),
				'disable'  => esc_html__( 'Disable', 'bricks' ),
			],
      'inline'      => true,
      'placeholder' => esc_html__( 'Enable', 'bricks' ),
		];

    $this->controls['scale'] = [
      'group' => 'map',
			'label'    => esc_html__( 'Scale', 'bricks' ),
			'type'        => 'select',
			'options'     => [
				'enable' => esc_html__( 'Enable', 'bricks' ),
				'disable'  => esc_html__( 'Disable', 'bricks' ),
			],
      'inline'      => true,
      'placeholder' => esc_html__( 'Disable', 'bricks' ),
		];


    /* behaviours */

    $this->controls['dragging'] = [
      'group' => 'behaviour',
			'label'    => esc_html__( 'Map Draggable', 'bricks' ),
			'type'        => 'select',
			'options'     => [
				'enable' => esc_html__( 'Enable', 'bricks' ),
				'disable'  => esc_html__( 'Disable', 'bricks' ),
			],
      'inline'      => true,
      'placeholder' => esc_html__( 'Enable', 'bricks' ),
		];

   

    /*
    $this->controls['autoZoom'] = [
      'group' => 'behaviour',
			'label'    => esc_html__( 'Auto default zoom', 'bricks' ),
      'info'    => esc_html__( 'Will set based on marker positions ', 'bricks' ),
			'type'        => 'select',
			'options'     => [
				'enable' => esc_html__( 'Enable', 'bricks' ),
				'disable'  => esc_html__( 'Disable', 'bricks' ),
			],
      'inline'      => true,
      'placeholder' => esc_html__( 'Enable', 'bricks' ),
		];
    */

    
    $this->controls['mapPadding'] = [
      'group' => 'behaviour',
			'tab'   => 'content',
			'label' => esc_html__( 'Map padding', 'bricks' ),
			'type'  => 'number',
		];


    $this->controls['defaultZoom'] = [
      'group' => 'behaviour',
			'label'    => esc_html__( 'Default zoom', 'bricks' ),
			'type'        => 'number',
      'units'       => false,
      'inline'      => true,
      'placeholder' => '9',
		];



  }

  
  public function enqueue_scripts() {

    wp_enqueue_script( 'x-leaflet',  BRICKSEXTRAS_URL . 'components/assets/leaflet/leaflet.js', '', '1.9.4', true );
    wp_enqueue_script( 'x-leaflet-providers',  BRICKSEXTRAS_URL . 'components/assets/leaflet/leaflet-providers.js', ['x-leaflet'], '1.0.0', true );
    wp_enqueue_script( 'x-dynamic-map',  BRICKSEXTRAS_URL . 'components/assets/js/dynamicmap.js', ['x-leaflet'], '1.0.0', true );

    if (! \BricksExtras\Helpers::elementCSSAdded($this->name) ) {
      wp_enqueue_style( 'x-dynamic-map', BRICKSEXTRAS_URL . 'components/assets/css/dynamicmap.css', [], '' );
    }

    wp_localize_script(
      'x-dynamic-map',
      'xMap', 
      [
          'Instances' => [],
          'leafletDir' 	=> BRICKSEXTRAS_URL . 'components/assets/leaflet/',
      ]
  );

  }

  public function get_normalized_image_settings( $settings ) {
		if ( empty( $settings['image'] ) ) {
			return [
				'id'   => 0,
				'url'  => false,
				'size' => BRICKS_DEFAULT_IMAGE_SIZE,
			];
		}

		$image = $settings['image'];

    // Size
		$image['size'] = empty( $image['size'] ) ? BRICKS_DEFAULT_IMAGE_SIZE : $settings['image']['size'];

		// Image ID or URL from dynamic data
		if ( ! empty( $image['useDynamicData'] ) ) {

			$images = $this->render_dynamic_data_tag( $image['useDynamicData'], 'image', [ 'size' => $image['size'] ] );

			if ( ! empty( $images[0] ) ) {
				if ( is_numeric( $images[0] ) ) {
					$image['id'] = $images[0];
				} else {
					$image['url'] = $images[0];
				}
			}
		}

		$image['id'] = empty( $image['id'] ) ? 0 : $image['id'];

		// If External URL, $image['url'] is already set
		if ( ! isset( $image['url'] ) ) {
			$image['url'] = ! empty( $image['id'] ) ? wp_get_attachment_image_url( $image['id'], $image['size'] ) : false;
		}

		return $image;

	}

  
  public function render() {

    $settings = $this->settings;

    $locationsRepeater = ! empty( $settings['locationsRepeater'] ) ? $settings['locationsRepeater'] : false;
    $maybeCluster = isset( $settings['maybeCluster'] ) ? 'enable' === $settings['maybeCluster'] : false;

    if ( isset( $settings['externalLoop'] ) ) {
      $locationsRepeater = false;
    }

    $locationsArray = [];


     // Query Loop
			if ( isset( $this->settings['hasLoop'] ) ) {

        $query = new \Bricks\Query( [
					'id'       => $this->id,
					'settings' => $settings,
				] );

				
        $label = isset( $this->settings['label'] ) ? esc_attr( $this->settings['label'] ) : '';
        $details = isset( $this->settings['details'] ) ? esc_attr( $this->settings['details'] ) : '';
        $lattitude = isset( $this->settings['lattitude'] ) ? $this->settings['lattitude'] : '';
        $longitude = isset( $this->settings['longitude'] ) ? $this->settings['longitude'] : '';

				$locationsArray = $query->render( [ $this, 'location_repeater_item' ], [ $label, $details, $lattitude, $longitude ], true );


				$query->destroy();
				unset( $query );

			}
      
      else {

        if ( !!$locationsRepeater ) {

          foreach ( $locationsRepeater as $index => $location ) {

            $image      = $this->get_normalized_image_settings( $location );
            $image_url  = $image['url'];

            $locationData = [
              'index' => $index,
              'label' => isset( $location['label'] ) ? $location['label'] : '',
              'details' => isset( $location['details'] ) ? $location['details'] : '',
              'lattitude' => isset( $location['lattitude'] ) ? $location['lattitude'] : '',
              'longitude' => isset( $location['longitude'] ) ? $location['longitude'] : '',
              'markerImage' => $image_url,
            ];

            array_push( $locationsArray, $locationData );

        }

      }

     }



     $config = [
      'maybeCluster' => $maybeCluster,
      'scale' => isset( $this->settings['scale'] ) ? 'enable' === $this->settings['scale'] : false,
      'theme' =>  isset( $this->settings['theme'] ) ? $this->settings['theme'] : 'CartoDB.Positron',
      'mapPadding' => isset( $this->settings['mapPadding'] ) ? $this->settings['mapPadding'] : '0',
     ];

     if ( !isset( $settings['externalLoop'] ) ) {
      $config['locations'] = $locationsArray;
     } else {
      $config['externalLoop'] = true;
     }

     if ( isset( $settings['autoZoom'] ) ) {
      $config['autoZoom'] = 'enable' === $this->settings['autoZoom'];
     }

     if ( isset( $settings['dragging'] ) ) {
      $config['dragging'] = 'enable' === $this->settings['dragging'];
     }

     if ( isset( $settings['zoomControl'] ) ) {
      $config['zoomControl'] = 'enable' === $this->settings['zoomControl'];
     }
     


    $this->set_attribute( '_root', 'data-x-map', wp_json_encode( $config ) );

    

    $loopIndex = false;

		if ( method_exists('\Bricks\Query','is_any_looping') ) {

			$query_id = \Bricks\Query::is_any_looping();
	
			if ( $query_id ) {
				
				if ( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(2) ) {
					$loopIndex = \Bricks\Query::get_query_for_element_id( \Bricks\Query::get_query_element_id( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(2) ) )->loop_index . '_' . \Bricks\Query::get_query_for_element_id( \Bricks\Query::get_query_element_id( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(1) ) )->loop_index . '_' . \Bricks\Query::get_loop_index();
				} else {
					if ( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(1) ) {
						$loopIndex = \Bricks\Query::get_query_for_element_id( \Bricks\Query::get_query_element_id( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(1) ) )->loop_index . '_' . \Bricks\Query::get_loop_index();
					} else {
						$loopIndex = \Bricks\Query::get_loop_index();
					}
				}			
	
				$this->set_attribute( '_root', 'data-x-id', $this->id . '_' . $loopIndex );
				
			} else {
				$this->set_attribute( '_root', 'data-x-id', $this->id );
			}
	
		} 

    echo "<div {$this->render_attributes( '_root' )}></div>";





    if ($maybeCluster || \BricksExtras\Helpers::maybePreview() ) {
      wp_enqueue_script( 'x-leaflet-cluster',  BRICKSEXTRAS_URL . 'components/assets/leaflet/leaflet.markercluster.js', ['x-leaflet'], '1.9.4', true );
    }

  }



  public function location_repeater_item( $label, $details, $lattitude, $longitude ) {

      $settings = $this->settings;
      $index    = $this->loop_index;

      
      // Render
      ob_start();

      $locationData = [
        'index' => $index,
        'label' => $label,
        'details' => $details,
        'lattitude' => $lattitude,
        'longitude' => $longitude
      ];

      $html = ob_get_clean();
  
      $this->loop_index++;
    
      return $locationData;
  
    }

}