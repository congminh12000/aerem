<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class X_Media_Playlist extends \Bricks\Element {

  // Element properties
   public $category     = 'extras';
	public $name         = 'xmediaplaylist';
	public $icon         = 'ti-list';
	public $css_selector = '';
	public $vue_component = 'bricks-nestable';
	public $nestable      = true;
	public $loop_index = 0;

  
  public function get_label() {
	  return esc_html__( 'Media Playlist', 'extras' );
  }

  public function set_control_groups() {

	$this->control_groups['poster'] = [
		'title' => esc_html__( 'Poster Image', 'bricks' ),
	 ];

	$this->control_groups['activeStyles'] = [
        'title' => esc_html__( 'Item styles', 'bricks' ),
    ];

	$this->control_groups['layout'] = [
		'title' => esc_html__( 'Layout / Spacing', 'extras' ),
		'tab' => 'content',
	];

	$this->control_groups['chapters'] = [
		'title' => esc_html__( 'Chapters', 'bricks' ),
	  ];

	$this->control_groups['textTracks'] = [
		'title' => esc_html__( 'Text Tracks', 'bricks' ),
	];

  }

  public function set_controls() {

        $this->controls['src'] = [
            'label'    => esc_html__( 'Media source', 'bricks' ),
            'type'        => 'text',
            'inline'      => true,
            //'info'    => esc_html__( 'Youtube/Vimeo ID or mp3/mp4', 'bricks' ),
        ];

		$this->controls['title'] = [
            'tab' => 'content',
            'label' => esc_html__( 'Title', 'bricks' ),
            'type' => 'text',
			'inline'      => true,
        ];

		

		$this->controls['clipStart'] = [
			'label'    => esc_html__( 'Clip start time', 'bricks' ),
			'type'        => 'text',
			'inline'      => true,
			'placeholder' => '0:00'
		];

		$this->controls['clipEnd'] = [
			'label'    => esc_html__( 'Clip end time', 'bricks' ),
			'type'        => 'text',
			'inline'      => true,
		];

		$this->controls['image'] = [
            'tab' => 'content',
            'label' => esc_html__( 'Poster Image', 'bricks' ),
            'type' => 'image',
			'group' => 'poster'
        ];


		/* active styles */

		$activeItem = '&[data-x-item-active]';
		$activePlaying = $activeItem . '[data-x-item-playing]';
		$activePaused = $activeItem . '[data-x-item-paused]';

		
		$this->controls['activeSep'] = [
            'tab' => 'content',
            'label' => esc_html__( 'Active', 'bricks' ),
            'type' => 'separator',
			'group' => 'activeStyles'
        ];
	  
		  $this->controls['activeBackgroundColor'] = [
			'tab'    => 'content',
			'group'  => 'activeStyles',
			'type'   => 'color',
			'label'  => esc_html__( 'Background', 'extras' ),
			'css'    => [
			  [
				'property' => 'background-color',
				'selector' => $activeItem
			  ],
			],
		  ];
	  
		  $this->controls['activeBorder'] = [
			'tab'    => 'content',
			'group'  => 'activeStyles',
			'type'   => 'border',
			'label'  => esc_html__( 'Border', 'extras' ),
			'css'    => [
			  [
				'property' => 'border',
				'selector' => $activeItem
			  ],
			],
		  ];
	  
		  $this->controls['activeBoxShadow'] = [
			'tab'    => 'content',
			'group'  => 'activeStyles',
			'label'  => esc_html__( 'Box Shadow', 'extras' ),
			'type'   => 'box-shadow',
			'css'    => [
			  [
				'property' => 'box-shadow',
				'selector' => $activeItem
			  ],
			],
		  ];

		  $this->controls['activeTypography'] = [
			'tab'      => 'content',
			'label'    => esc_html__( 'Typography', 'bricks' ),
			'type'     => 'typography',
			'group' => 'activeStyles',
			'units'    => true,
			'css'      => [
				[
				'property' => 'font',
				'selector' => $activeItem
				],
			],
		];

		$this->controls['activePlayingSep'] = [
            'tab' => 'content',
            'label' => esc_html__( 'Playing', 'bricks' ),
            'type' => 'separator',
			'group' => 'activeStyles'
        ];


		$this->controls['activePlayingBackgroundColor'] = [
			'tab'    => 'content',
			'group'  => 'activeStyles',
			'type'   => 'color',
			'label'  => esc_html__( 'Background', 'extras' ),
			'css'    => [
			  [
				'property' => 'background-color',
				'selector' => $activePlaying
			  ],
			],
		  ];
	  
		  $this->controls['activePlayingBorder'] = [
			'tab'    => 'content',
			'group'  => 'activeStyles',
			'type'   => 'border',
			'label'  => esc_html__( 'Border', 'extras' ),
			'css'    => [
			  [
				'property' => 'border',
				'selector' => $activePlaying
			  ],
			],
		  ];
	  
		  $this->controls['activePlayingBoxShadow'] = [
			'tab'    => 'content',
			'group'  => 'activeStyles',
			'label'  => esc_html__( 'Box Shadow', 'extras' ),
			'type'   => 'box-shadow',
			'css'    => [
			  [
				'property' => 'box-shadow',
				'selector' => $activePlaying
			  ],
			],
		  ];

		  $this->controls['activePlayingTypography'] = [
			'tab'      => 'content',
			'label'    => esc_html__( 'Typography', 'bricks' ),
			'type'     => 'typography',
			'group' => 'activeStyles',
			'units'    => true,
			'css'      => [
				[
				'property' => 'font',
				'selector' => $activePlaying
				],
			],
		];


		$this->controls['activePausedSep'] = [
            'tab' => 'content',
            'label' => esc_html__( 'Paused', 'bricks' ),
            'type' => 'separator',
			'group' => 'activeStyles'
        ];

		$this->controls['activePausedBackgroundColor'] = [
			'tab'    => 'content',
			'group'  => 'activeStyles',
			'type'   => 'color',
			'label'  => esc_html__( 'Background', 'extras' ),
			'css'    => [
			  [
				'property' => 'background-color',
				'selector' => $activePaused
			  ],
			],
		  ];
	  
		  $this->controls['activePausedPlayingBorder'] = [
			'tab'    => 'content',
			'group'  => 'activeStyles',
			'type'   => 'border',
			'label'  => esc_html__( 'Border', 'extras' ),
			'css'    => [
			  [
				'property' => 'border',
				'selector' => $activePaused
			  ],
			],
		  ];
	  
		  $this->controls['activePausedPlayingBoxShadow'] = [
			'tab'    => 'content',
			'group'  => 'activeStyles',
			'label'  => esc_html__( 'Box Shadow', 'extras' ),
			'type'   => 'box-shadow',
			'css'    => [
			  [
				'property' => 'box-shadow',
				'selector' => $activePaused
			  ],
			],
		  ];

		  $this->controls['activePausedPlayingTypography'] = [
			'tab'      => 'content',
			'label'    => esc_html__( 'Typography', 'bricks' ),
			'type'     => 'typography',
			'group' => 'activeStyles',
			'units'    => true,
			'css'      => [
				[
				'property' => 'font',
				'selector' => $activePaused
				],
			],
		];


		  $this->controls['flexWrap'] = [
			'tab'         => 'content',
			'label'       => esc_html__( 'Flex wrap', 'bricks' ),
			'group'		  => 'layout',
			'tooltip'     => [
				'content'  => 'flex-wrap',
				'position' => 'top-left',
			],
			'type'        => 'select',
			'options'  => [
				'nowrap'       => esc_html__( 'No wrap', 'bricks' ),
				'wrap'         => esc_html__( 'Wrap', 'bricks' ),
				'wrap-reverse' => esc_html__( 'Wrap reverse', 'bricks' ),
			],
			'inline'      => true,
			'css'         => [
				[
					'property' => 'flex-wrap',
					'selector' => '',
				],
			],
			'placeholder' => esc_html__( 'No wrap', 'bricks' ),
		];

		$this->controls['direction'] = [
			'tab'      => 'content',
			'label'    => esc_html__( 'Direction', 'bricks' ),
			'group'		  => 'layout',
			'tooltip'  => [
				'content'  => 'flex-direction',
				'position' => 'top-left',
			],
			'type'     => 'direction',
			'css'      => [
				[
					'property' => 'flex-direction',
					'selector' => '',
				],
			],
			'inline'   => true,
			'rerender' => true,
		];

		$this->controls['justifyContent'] = [
			'tab'      => 'content',
			'label'    => esc_html__( 'Align main axis', 'bricks' ),
			'group'		  => 'layout',
			'tooltip'  => [
				'content'  => 'justify-content',
				'position' => 'top-left',
			],
			'type'     => 'justify-content',
			'css'      => [
				[
					'property' => 'justify-content',
					'selector' => '',
				],
			],
		];

		$this->controls['alignItems'] = [
			'tab'      => 'content',
			'label'    => esc_html__( 'Align cross axis', 'bricks' ),
			'group'		  => 'layout',
			'tooltip'  => [
				'content'  => 'align-items',
				'position' => 'top-left',
			],
			'type'     => 'align-items',
			'css'      => [
				[
					'property' => 'align-items',
					'selector' => '',
				],
			],
		];

		$this->controls['columnGap'] = [
			'tab'      => 'content',
			'label'    => esc_html__( 'Column gap', 'bricks' ),
			'group'		  => 'layout',
			'type'     => 'number',
			'units'    => true,
			'css'      => [
				[
					'property' => 'column-gap',
					'selector' => '',
				],
			],
		];

		$this->controls['rowGap'] = [
			'tab'      => 'content',
			'label'    => esc_html__( 'Row gap', 'bricks' ),
			'group'		  => 'layout',
			'type'     => 'number',
			'units'    => true,
			'css'      => [
				[
					'property' => 'row-gap',
					'selector' => ''
				],
			],
		];



		 /* chapters */

		 $this->controls['hasLoop'] = [
			'tab'   => 'content',
			'label' => esc_html__( 'Add chapters with query loop', 'bricks' ),
			'type'  => 'checkbox',
      		'group' => 'chapters',
		];

		$this->controls['query'] = [
			'tab'      => 'content',
			'label'    => esc_html__( 'Query', 'bricks' ),
			'type'     => 'query',
      		'group' => 'chapters',
			'popup'    => true,
			'inline'   => true,
			'required' => [ 
				[ 'hasLoop', '!=', '' ],
			],
		];

    	$this->controls['chapters'] = [
			'tab'         => 'content',
			'placeholder' => esc_html__( 'Chapter', 'bricks' ),
			'type'        => 'repeater',
      		'group' => 'chapters',
			'fields'      => [
				'text'    => [
					'label' => esc_html__( 'Title', 'bricks' ),
					'type'  => 'text',
          'hasDynamicData' => false,
				],
        	'startTime'    => [
					'label' => esc_html__( 'Start time', 'bricks' ),
					'type'  => 'text',
          'hasDynamicData' => false,
          'inline' => true,
				],
        	'endTime'    => [
					'label' => esc_html__( 'End time', 'bricks' ),
					'type'  => 'text',
          'hasDynamicData' => false,
          'inline' => true,
				],
			],
			'required' => [
				['hasLoop', '!=', true]
			]
		];


    	$this->controls['chapterText'] = [
			'tab'   => 'content',
			'label' => esc_html__( 'Text', 'bricks' ),
			'type'  => 'text',
			'group' => 'chapters',
			'required' => [
				['hasLoop', '=', true]
			]
		];

    		$this->controls['chapterStart'] = [
			'tab'   => 'content',
			'label' => esc_html__( 'Start time', 'bricks' ),
			'type'  => 'text',
			'group' => 'chapters',
			'inline' => true,
			'required' => [
				['hasLoop', '=', true]
			]
		];

    		$this->controls['chapterEnd'] = [
			'tab'   => 'content',
			'label' => esc_html__( 'End time', 'bricks' ),
			'type'  => 'text',
			'group' => 'chapters',
			'inline' => true,
			'required' => [
				['hasLoop', '=', true]
			]
		];

    		$this->controls['chaptersSep'] = [
			'tab'   => 'content',
			'type'  => 'seperator',
      		'group' => 'chapters',
		];


		/* textTracks */

		$this->controls['textTracksSep'] = [
			'group' => 'textTracks',
				  'label'    => esc_html__( 'Subtitles / Captions', 'bricks' ),
			'descriptions'    => esc_html__( 'Here you can add subtitles/captions to the media player.', 'bricks' ),
				  'type'        => 'separator',
			  ];

		$this->controls['textTracks'] = [
			'tab'           => 'content',
			'type'          => 'repeater',
			'group'			=> 'textTracks',
			'label'         => esc_html__( 'Text Tracks', 'bricks' ),
			'titleProperty' => 'label',
			'fields'        => [
				'label'       => [
					'type'    => 'text',
					'label'   => esc_html__( 'Label', 'bricks' ),
					'inline' => true
				],	
				'src'       => [
					'type'    => 'text',
					'label'   => esc_html__( 'Src (.vtt, .srt, .json)', 'bricks' ),
					'inline' => true
				],
				'kind'       => [
					'type'    => 'select',
					'label'   => esc_html__( 'Kind', 'bricks' ),
					'inline' => true,
					'options' => [
						'subtitles' => 'Subtitles',
						'chapters' => 'Chapters',
						'captions' => 'Captions'
					],
				],
				'language' => [
					'type'    => 'text',
					'label'   => esc_html__( 'Language (en, fr, etc.)', 'bricks' ),
					'inline' => true
				],
			],
		];

		/* */

		$this->controls['thumbnailSep'] = [
			'group' => 'textTracks',
				'label'    => esc_html__( 'Thumbnails', 'bricks' ),
				'type'        => 'separator',
			];
	  
		  $this->controls['thumbnailFile'] = [
			'group'			=> 'textTracks',
			'type'    => 'text',
			'label'   => esc_html__( 'Src (.vtt)', 'bricks' ),
			'inline' => true
		  ];

		  

  }


  public function get_nestable_children() {
		return [
				[
					'name'     => 'block',
					'settings' => [
						'text' => esc_html__( 'Item description goes here', 'bricks' ),
					],
					'children' => [
					[
					'name'     => 'text-basic',
					'settings' => [
						'text' => esc_html__( 'Item description goes here', 'bricks' ),
					],
					],
				],
			],
			
		];
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

	$srcSetting = isset( $settings['src'] ) ? $settings['src'] : false;
	$title = isset( $settings['title'] ) ? $settings['title'] : false;

	$src = strstr( $srcSetting, '{') ? $this->render_dynamic_data_tag( $srcSetting, 'text' ) : $srcSetting;

	$image  = $this->get_normalized_image_settings( $this->settings );
    $image_id   = $image['id'];
    $image_url  = $image['url'];
    $image_size = $image['size'];

	$textTracks = ! empty( $settings['textTracks'] ) ? $settings['textTracks'] : false;

	$clipStart = isset( $settings['clipStart'] ) ? $settings['clipStart'] : false;
	$clipEnd = isset( $settings['clipEnd'] ) ? $settings['clipEnd'] : false;

	$clipStartTime = $clipStart ? \BricksExtras\Helpers::timeToSeconds( $this->render_dynamic_data( $clipStart ) ) : false;
	$clipEndTime = $clipEnd ? \BricksExtras\Helpers::timeToSeconds( $this->render_dynamic_data( $clipEnd ) ) : false;

	$thumbnailFile = isset( $settings['thumbnailFile'] ) ? esc_attr( $settings['thumbnailFile'] ) : false;

	$this->set_attribute( 'x-media-player-playlist_inner', 'class', 'x-media-player-playlist_inner' ); 

	if (!!$src) {
		$this->set_attribute( '_root', 'data-x-src', $src );
	}

	if (!!$title) {
		$this->set_attribute( '_root', 'data-x-title', $title );
	}
	
	if ($image_url) {
		$this->set_attribute( '_root', 'data-x-poster', $image_url );
	}

	if ($clipStartTime) {
		$this->set_attribute( '_root', 'data-x-clip-start', $clipStartTime );
	}

	if ($clipEndTime) {
		$this->set_attribute( '_root', 'data-x-clip-end', $clipEndTime );
	}

	if ($thumbnailFile) {
		$this->set_attribute( '_root', 'data-x-thumbnails', $thumbnailFile );
	}

	 

	 /* find if poster image has been saved */

	 if ( get_option( 'bricksextras_media_poster_url') ) {

		$savedPosterImages = json_decode( get_option( 'bricksextras_media_poster_url'), TRUE);
		$videoID = \BricksExtras\Helpers::get_video_id( $src );

		if (str_contains($src, 'youtu') || str_contains($src, 'vimeo')) {
  
			if ($savedPosterImages) {
	
			if (array_key_exists($videoID, $savedPosterImages)) {
					if (!str_contains($savedPosterImages[$videoID], 'null')) {
					$this->set_attribute( '_root', 'data-x-local-poster', $savedPosterImages[$videoID] );
					}
				}
	
			}

		}
  
	  }



	$textTrackConfig = [];

	if ( $textTracks ) {		

		foreach ( $textTracks as $index => $textTrack ) {

			$label = ! empty( $textTrack['label'] ) ? $textTrack['label'] : 'English';
			$src = ! empty( $textTrack['src'] ) ? $textTrack['src'] : 'https://media-files.vidstack.io/sprite-fight/subs/english.vtt';
			$kind = ! empty( $textTrack['kind'] ) ? $textTrack['kind'] : 'subtitles';
			$language = ! empty( $textTrack['language'] ) ? $textTrack['language'] : 'English';

			$default = 0 === $index ? 'default' : '';

			$textTrackConfig[] = [
				'label' => $label,
				'src'	=> $src,
				'kind'	=> $kind,
				'language' => $language
			];

		}

	}

	if (count($textTrackConfig) !== 0) {
		$this->set_attribute( '_root', 'data-x-texttracks', wp_json_encode( $textTrackConfig ) );
	}


    

    /* chapters from dynamic data */

	$chapters = ! empty( $this->settings['chapters'] ) ? $this->settings['chapters'] : false;
    $chapterText = isset( $settings['chapterText'] ) ? esc_attr( $settings['chapterText'] ) : '';
    $chapterStart = isset( $settings['chapterStart'] ) ? esc_attr( $settings['chapterStart'] ) : '';
    $chapterEnd = isset( $settings['chapterEnd'] ) ? esc_attr( $settings['chapterEnd'] ) : '';

	$chaptersArray = [];

     // Query Loop
	if ( isset( $this->settings['hasLoop'] ) ) {

        $query = new \Bricks\Query( [
					'id'       => $this->id,
					'settings' => $settings,
				] );

				
			$chapterText = isset( $this->settings['chapterText'] ) ? esc_attr( $this->settings['chapterText'] ) : '';
			$chapterStart = isset( $this->settings['chapterStart'] ) ? esc_attr( $this->settings['chapterStart'] ) : '';
			$chapterEnd = isset( $this->settings['chapterEnd'] ) ? esc_attr( $this->settings['chapterEnd'] ) : '';

			$chaptersArray = $query->render( [ $this, 'chapter_repeater_item' ], [ $chapterText, $chapterStart, $chapterEnd ], true );

			$query->destroy();
			unset( $query );

		}
      
      else {

        if ( !!$chapters ) {

          foreach ( $this->settings['chapters'] as $index => $chapter ) {

            $chapterText = isset( $chapter['text'] ) ? esc_attr( $chapter['text'] ) : '';
            $chapterStart = isset( $chapter['startTime'] ) ? esc_attr( $chapter['startTime'] ) : '';
            $chapterEnd = isset( $chapter['endTime'] ) ? esc_attr( $chapter['endTime'] ) : '';

            $chapterData = [
              'text' => $chapterText,
              'startTime' => \BricksExtras\Helpers::timeToSeconds( $this->render_dynamic_data( $chapterStart ) ),
              'endTime' => \BricksExtras\Helpers::timeToSeconds( $this->render_dynamic_data( $chapterEnd ) )
            ];

            array_push( $chaptersArray, $chapterData );

        }

      }

     }
     
     /* ignore if less than 2 chapters */
     if( 2 <= count( $chaptersArray ) ) {
      $this->set_attribute( '_root', 'data-x-chapters', json_encode(array_values($chaptersArray)) );
     }

	

    echo "<div {$this->render_attributes( '_root' )}>";

	echo \Bricks\Frontend::render_children( $this );

	echo "</div>";
	
    
  }





  public function chapter_repeater_item( $chapterText,$chapterStart,$chapterEnd) {

	$settings = $this->settings;
	$index    = $this->loop_index;

	
	// Render
	ob_start();

	$startTime = \BricksExtras\Helpers::timeToSeconds( $this->render_dynamic_data( $chapterStart ) );
	$endTime = \BricksExtras\Helpers::timeToSeconds( $this->render_dynamic_data( $chapterEnd ) );

	$chapterData = [
	  'text' => $chapterText,
	  'startTime' => $startTime,
	  'endTime' => $endTime
	];

	
	
	$html = ob_get_clean();
  
	$this->loop_index++;
	return $chapterData;
  
	}
  
	

}