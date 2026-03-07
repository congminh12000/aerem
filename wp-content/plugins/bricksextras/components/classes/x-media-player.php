<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class X_Media_Player extends \Bricks\Element {

  // Element properties
  public $category     = 'extras';
	public $name         = 'xmediaplayer';
	public $icon         = 'ti-video-camera';
	//public $css_selector = '.x-media-player_inner';
	public $scripts      = ['xDoMediaPlayer'];
  public $loop_index = 0;
  public $nestable = true;

  
  public function get_label() {
	  return esc_html__( 'Media Player', 'extras' );
  }
  public function set_control_groups() {

   
   $this->control_groups['poster'] = [
      'title' => esc_html__( 'Poster Image', 'bricks' ),
      'required' => [
        ['playlistMode', '!=', true]
      ]
   ];

   $this->control_groups['controls'] = [
    'title' => esc_html__( 'Player UI', 'bricks' ),
    'required' => [
      ['provider','!=','audio'],
      ['uiType','!=','custom']
     ]
    ];

    $this->control_groups['controlsAudio'] = [
      'title' => esc_html__( 'Player UI', 'bricks' ),
      'required' => [
        ['provider','=','audio'],
        ['uiType','!=','custom']
        ]
    ];

    $this->control_groups['controlsSmall'] = [
      'title' => esc_html__( 'Small Player UI', 'bricks' ),
      'required' => [
        ['provider','!=','audio'],
        ['uiType','!=','custom']
        ]
    ];

    $this->control_groups['styleControls'] = [
      'title' => esc_html__( 'Player Styling', 'bricks' ),
    ];

    $this->control_groups['behaviour'] = [
        'title' => esc_html__( 'Config / Behaviour', 'bricks' ),
    ];

    $this->control_groups['loading'] = [
      'title' => esc_html__( 'Load strategy', 'bricks' ),
  ];

    $this->control_groups['tooltips'] = [
      'title' => esc_html__( 'Tooltips', 'bricks' ),
      //'required' => ['uiType','!=','custom']
    ];

    $this->control_groups['noticeOverlay'] = [
      'title' => esc_html__( 'Notice Overlay', 'bricks' ),
      'required' => [
        ['provider','!=','audio'],
        ['uiType','!=','custom']
        ]
    ];

    $this->control_groups['textTracks'] = [
      'title' => esc_html__( 'Text Tracks', 'bricks' ),
      'required' => ['playlistMode', '!=', true]
    ];

    $this->control_groups['chapters'] = [
      'title' => esc_html__( 'Chapters', 'bricks' ),
      'required' => ['playlistMode', '!=', true]
    ];

    $this->control_groups['playLists'] = [
      'title' => esc_html__( 'Playlists', 'bricks' ),
    ];


  }

  public function set_controls() {


    $this->controls['_background']['css'][0]['selector'] = '&[data-view-type=video] media-provider';
    $this->controls['_background']['css'][1]['selector'] = '&[data-view-type=audio]';
		$this->controls['_border']['css'][0]['selector'] = '&[data-view-type=video] media-provider';
    $this->controls['_border']['css'][1]['selector'] = '&[data-view-type=audio]';

    

    $this->controls['provider'] = [
			'label'    => esc_html__( 'Media type', 'bricks' ),
			'type'        => 'select',
			'options'     => [
				'video' => esc_html__( 'Video', 'bricks' ),
				'audio'  => esc_html__( 'Audio', 'bricks' ),
			],
			'placeholder' => esc_html__( 'Video', 'bricks' ),
			'inline'      => true,
			'small' => true,
      //'group' => 'mediaSource'
		];

    $this->controls['src'] = [
			'label'    => esc_html__( 'Media source', 'bricks' ),
			'type'        => 'text',
			'inline'      => true,
      'required' => ['playlistMode', '!=', true],
      //'group' => 'mediaSource'
		];

    $this->controls['title'] = [
			'label'    => esc_html__( 'Title', 'bricks' ),
			'type'        => 'text',
			'inline'      => true,
      'required' => ['playlistMode', '!=', true],
      //'group' => 'mediaSource'
		];

    $this->controls['clipStartTime'] = [
			'label'    => esc_html__( 'Clip start', 'bricks' ),
			'type'        => 'text',
			'inline'      => true,
      'required' => ['playlistMode', '!=', true],
      
		];

    $this->controls['clipEndTime'] = [
			'label'    => esc_html__( 'Clip end', 'bricks' ),
			'type'        => 'text',
			'inline'      => true,
      'required' => ['playlistMode', '!=', true],
      
		];


    $this->controls['aspectRatio'] = [
			'label'    => esc_html__( 'Aspect-ratio', 'bricks' ),
			'type'        => 'text',
			'placeholder' => esc_html__( '16/9', 'bricks' ),
			'inline'      => true,
      'info' => esc_html__( 'Format w/h', 'bricks' ),
      'hasDynamicData' => false,
			'small' => true,
      'css'    => [
        [
          'property' => 'aspect-ratio',
          'selector' => '&[data-view-type=video]',
        ],
        [
          'property' => 'aspect-ratio',
          'selector' => '&[data-view-type=video] video',
        ],
      ],
      'required' => ['provider', '!=', 'audio']
		];



    /* controls bottom */

    $activeControls = [
      '',
      'play',
      'play-large',
      'pip',
      'fullscreen',
      'captions'
    ];

    $inactiveControls = [
      '',
      'play',
      'play-large',
      'seek-forward',
      'seek-backward',
      'pip',
      'fullscreen',
      'settings',
      'captions'
    ];

    $placementOptions = [
      'top' => esc_html__('Top', 'bricks' ), 
      'bottom' => esc_html__( 'Bottom', 'bricks' ), 
      'top-start' => esc_html__( 'Top Start', 'bricks' ), 
      'top-end' => esc_html__( 'Top End', 'bricks' ),
      'bottom-start' => esc_html__( 'Bottom Start', 'bricks' ), 
      'bottom-end' => esc_html__( 'Bottom End', 'bricks' ),
    ];

    $optionControls = 
      [
        'play-large'  => 'Play / Pause (large)',
        'play'   => 'Play / Pause',
        'seek-backward' => 'Seek Backward',
        'seek-forward' => 'Seek Forward',
        'mute' => 'Volume / Mute',
        'spacer' => 'Spacer',
        'time'  => 'Time',
        'pip' => 'PIP *',
        'fullscreen' => 'Fullscreen *',
        ',xMediaPlayer.vidstackDir + "assets/' => 'Time Slider',
        'settings' => 'Settings Menu',
        'captions' => 'Caption Toggle *',
        'title' => 'Title',
        'chapter-title' => 'Chapter Title *',
        'custom-text' => 'Custom Text',
        'image' => 'Image',
        
        //'airplay' => 'Airplay *',
        //'chromecast' => 'Chromecast *',
        //'live-button' => 'Live Button'
      ];

    $controlFields = [
      'control'    => [
            'label'     => esc_html__( 'Control', 'bricks' ),
            'type'      => 'select',
            'clearable' => false,
            'searchable' => true,
            'inline'    => true,
            'options'   => $optionControls
        ],
        'image' => [
          'tab' => 'content',
          'label' => esc_html__( 'Image', 'bricks' ),
          'type' => 'image',
          'required'  => ['control', '=', ['image']],
        ],
        'altText' => [
          'tab'      => 'content',
          'label'    => esc_html__( 'Custom alt text', 'bricks' ),
          'type'     => 'text',
          'inline'   => true,
          'rerender' => false,
          'required'  => ['control', '=', ['image']],
        ],
        'playLabel'    => [
          'label'     => esc_html__( 'Play label', 'bricks' ),
          'type'      => 'text',
          'required'  => ['control', '=', ['play','play-large']],
          'placeholder' => 'Play',
          'inline'    => true
        ],
        'pauseLabel'    => [
          'label'     => esc_html__( 'Pause label', 'bricks' ),
          'type'      => 'text',
          'required'  => ['control', '=', ['play','play-large']],
          'inline'    => true,
          'placeholder' => 'Pause'
        ],
        'captionsOnLabel'    => [
          'label'     => esc_html__('Captions On Label', 'bricks' ),
          'type'      => 'text',
          'required'  => ['control', '=', ['captions']],
          'placeholder' => 'Closed-Captions On',
          'inline'    => true
        ],
        'captionsOffLabel'    => [
          'label'     => esc_html__('Captions Off Label', 'bricks' ),
          'type'      => 'text',
          'required'  => ['control', '=', ['captions']],
          'placeholder' => 'Closed-Captions Off',
          'inline'    => true
        ],
        'seekAmountBack'    => [
          'label'     => esc_html__( 'Seek Distance', 'bricks' ),
          'type'      => 'select',
          'inline'    => true,
          'options'   => [
            '-10' => '-10',
            '-15' => '-15',
            '-30' => '-30',
          ],
          'required'  => ['control', '=', 'seek-backward'],
          'placeholder' => '-10'
        ],
        'seekLabelBack'    => [
          'label'     => esc_html__( 'Seek Label', 'bricks' ),
          'type'      => 'text',
          'inline'    => true,
          'required'  => ['control', '=', 'seek-backward'],
          'placeholder' => 'Rewind'
        ],
        'seekAmountForward'    => [
          'label'     => esc_html__( 'Seek Distance', 'bricks' ),
          'type'      => 'select',
          'inline'    => true,
          'options'   => [
            '+10' => '+10',
            '+15' => '+15',
            '+30' => '+30',
          ],
          'required'  => ['control', '=', 'seek-forward'],
          'placeholder' => '+10'
        ],
        'seekLabelForward'    => [
          'label'     => esc_html__( 'Seek Label', 'bricks' ),
          'type'      => 'text',
          'inline'    => true,
          'required'  => ['control', '=', 'seek-forward'],
          'placeholder' => 'Forward'
        ],
        'volumeSlider'    => [
          'label'     => esc_html__( 'Include volume slider', 'bricks' ),
          'type'      => 'select',
          'options'   => [
            'focus' => esc_html__( 'Enable (visible on focus)', 'bricks' ),
            'visible' => esc_html__( 'Enable (always visible)', 'bricks' ),
            'disable' => esc_html__( 'Disable', 'bricks' ),
          ],
          'placeholder' => esc_html__( 'Enable (visible on focus)', 'bricks' ),
          'required'  => ['control', '=', 'mute'],
        ],
        'muteLabel'    => [
          'label'     => esc_html__( 'Mute Label', 'bricks' ),
          'type'      => 'text',
          'required'  => ['control', '=', 'mute'],
          'inline'    => true,
          'placeholder' => 'Mute'
        ],
        'unmuteLabel'    => [
          'label'     => esc_html__( 'Unmute Label', 'bricks' ),
          'type'      => 'text',
          'required'  => ['control', '=', 'mute'],
          'inline'    => true,
          'placeholder' => 'Unmute'
        ],
        'currentTime'    => [
          'label'     => esc_html__( 'Current time', 'bricks' ),
          'type'      => 'select',
          'options'   => [
            'enable' => esc_html__( 'Enable', 'bricks' ),
            'disable' => esc_html__( 'Disable', 'bricks' ),
            'remainder' => esc_html__( 'Show remaining time', 'bricks' ),
          ],
          'inline'    => true,
          'required'  => ['control', '=', 'time'],
          'placeholder' => esc_html__( 'Enable', 'bricks' ),
        ],
        'timeDivider'    => [
          'label'     => esc_html__( 'Divider', 'bricks' ),
          'type'      => 'select',
          'inline'    => true,
          'options'   => [
            'enable' => esc_html__( 'Enable', 'bricks' ),
            'disable' => esc_html__( 'Disable', 'bricks' ),
          ],
          'required'  => ['control', '=', 'time'],
          'placeholder' => esc_html__( 'Enable', 'bricks' ),
        ],
        'timeDividerText'    => [
          'label'     => esc_html__( 'Divider', 'bricks' ),
          'type'      => 'text',
          'inline'    => true,
          'required'  => [
            ['control', '=', 'time'],
            ['timeDivider', '!=', 'disable'],
          ],
          'placeholder' => '/',
        ],
        'duration'    => [
          'label'     => esc_html__( 'Duration', 'bricks' ),
          'type'      => 'select',
          'inline'    => true,
          'options'   => [
            'enable' => esc_html__( 'Enable', 'bricks' ),
            'disable' => esc_html__( 'Disable', 'bricks' ),
          ],
          'required'  => ['control', '=', 'time'],
          'placeholder' => esc_html__( 'Enable', 'bricks' ),
        ],

        'enterPipLabel'    => [
          'label'     => esc_html__( 'Enter PIP Label', 'bricks' ),
          'type'      => 'text',
          'inline'    => true,
          'required'  => ['control', '=', 'pip'],
          'placeholder' => 'Enter PIP'
        ],
        'exitPipLabel'    => [
          'label'     => esc_html__( 'Exit PIP Label', 'bricks' ),
          'type'      => 'text',
          'inline'    => true,
          'required'  => ['control', '=', 'pip'],
          'placeholder' => 'Exit PIP'
        ],
        'enterFullscreenLabel'    => [
          'label'     => esc_html__( 'Enter Fullscreen Label', 'bricks' ),
          'type'      => 'text',
          'inline'    => true,
          'required'  => ['control', '=', 'fullscreen'],
          'placeholder' => 'Enter Fullscreen'
        ],
        'exitFullscreenLabel'    => [
          'label'     => esc_html__( 'Exit Fullscreen Label', 'bricks' ),
          'type'      => 'text',
          'inline'    => true,
          'required'  => ['control', '=', 'fullscreen'],
          'placeholder' => 'Exit Fullscreen'
        ],
        'settingsLabel'    => [
          'label'     => esc_html__( 'Settings Label', 'bricks' ),
          'type'      => 'text',
          'required'  => ['control', '=', 'settings'],
          'inline'    => true,
          'placeholder' => 'Settings'
        ],
        'speedLabel'    => [
          'label'     => esc_html__( 'Speed Label', 'bricks' ),
          'type'      => 'text',
          'required'  => ['control', '=', 'settings'],
          'inline'    => true,
          'placeholder' => 'Speed'
        ],
        'qualityLabel'    => [
          'label'     => esc_html__( 'Quality Label', 'bricks' ),
          'type'      => 'text',
          'required'  => ['control', '=', 'settings'],
          'inline'    => true,
          'placeholder' => 'Quality'
        ],
        'captionsLabel'    => [
          'label'     => esc_html__( 'Captions Label', 'bricks' ),
          'type'      => 'text',
          'required'  => ['control', '=', 'settings'],
          'inline'    => true,
          'placeholder' => 'Captions'
        ],
        'chaptersLabel'    => [
          'label'     => esc_html__( 'Chapters Label', 'bricks' ),
          'type'      => 'text',
          'required'  => ['control', '=', 'settings'],
          'inline'    => true,
          'placeholder' => 'Chapters'
        ],
        'settingsPlacement'    => [
          'label'     => esc_html__( 'Placement', 'bricks' ),
          'type'      => 'select',
          'options' => $placementOptions,
          'required'  => ['control', '=', 'settings'],
          'inline'    => true,
          'placeholder' => 'Auto'
        ],
        'visibility'    => [
          'label'     => esc_html__( 'Visibility', 'bricks' ),
          'type'      => 'select',
          'inline'    => true,
          'options'   => [
            'startHidden' => esc_html__( 'Invisible until play', 'bricks' ),
            'startDisplayNone' => esc_html__( 'Hidden until play (display none)', 'bricks' ),
            'default' => esc_html__( 'Default', 'bricks' ),
          ],
          'required'  => ['control', '!=', 'spacer'],
          'placeholder' => esc_html__( 'Default', 'bricks' )
        ],
        'tooltipPlacement'    => [
          'label'     => esc_html__( 'Tooltip Placement', 'bricks' ),
          'type'      => 'select',
          'inline'    => true,
          'options' => $placementOptions,
          'required'  => ['control', '!=', ['spacer','title','custom-text','image','live-button','time'] ],
          'placeholder' => esc_html__( 'Default', 'bricks' )
        ],
        'text'    => [
          'label'     => esc_html__( 'Text', 'bricks' ),
          'type'      => 'text',
          'required'  => ['control', '=', 'custom-text'],
          'inline'    => true,
          'placeholder' => ''
        ],
        'inactiveIconSep' => [
          'tab'      => 'content',
          'group' => 'buttonIcons',
          'label'    => esc_html__( 'Icon', 'bricks' ),
          'type'     => 'separator',
          'required'  => ['control', '=', $inactiveControls],
        ],
       'inactiveIcon' => [
          'tab'      => 'content',
          'group' => 'buttonIcons',
          'label'    => esc_html__( 'Icon', 'bricks' ),
          'type'     => 'icon',
          'css'      => [
            [
              'selector' => '.vds-icon.vds-inactive-icon',
            ],
          ],
          'required'  => ['control', '=', $inactiveControls],
        ],
        'activeIconSep' => [
          'tab'      => 'content',
          'group' => 'buttonIcons',
          'label'    => esc_html__( 'Icon - active state', 'bricks' ),
          'type'     => 'separator',
          'required'  => ['control', '=', $activeControls],
        ],
        'activeIcon' => [
          'tab'      => 'content',
          'group' => 'buttonIcons',
          'label'    => esc_html__( 'Icon', 'bricks' ),
          'type'     => 'icon',
          'css'      => [
            [
              'selector' => '.vds-icon.vds-active-icon',
            ],
          ],
          'required'  => ['control', '=', $activeControls],
        ],
        /* mute icons */

        'muteIconSep' => [
          'tab'      => 'content',
          'group' => 'buttonIcons',
          'label'    => esc_html__( 'Icon - muted', 'bricks' ),
          'type'     => 'separator',
          'required'  => ['control', '=', 'mute'],
        ],
       'muteIcon' => [
          'tab'      => 'content',
          'group' => 'buttonIcons',
          'label'    => esc_html__( 'Icon', 'bricks' ),
          'type'     => 'icon',
          'css'      => [
            [
              'selector' => '.vds-icon.vds-inactive-icon',
            ],
          ],
          'required'  => ['control', '=', 'mute'],
        ],
        'highVolumeIconSep' => [
          'tab'      => 'content',
          'group' => 'buttonIcons',
          'label'    => esc_html__( 'Icon - high volume', 'bricks' ),
          'type'     => 'separator',
          'required'  => ['control', '=', 'mute'],
        ],
        'highVolumeIcon' => [
          'tab'      => 'content',
          'group' => 'buttonIcons',
          'label'    => esc_html__( 'Icon', 'bricks' ),
          'type'     => 'icon',
          'css'      => [
            [
              'selector' => '.vds-icon.vds-active-icon',
            ],
          ],
          'required'  => ['control', '=', 'mute'],
        ],
        'lowVolumeIconSep' => [
          'tab'      => 'content',
          'group' => 'buttonIcons',
          'label'    => esc_html__( 'Icon - low volume', 'bricks' ),
          'type'     => 'separator',
          'required'  => ['control', '=', 'mute'],
        ],
        'lowVolumeIcon' => [
          'tab'      => 'content',
          'group' => 'buttonIcons',
          'label'    => esc_html__( 'Icon', 'bricks' ),
          'type'     => 'icon',
          'css'      => [
            [
              'selector' => '.vds-icon.vds-active-icon',
            ],
          ],
          'required'  => ['control', '=', 'mute'],
        ],

        'settingsSpeedIcon' => [
          'tab'      => 'content',
          'group' => 'buttonIcons',
          'label'    => esc_html__( 'Speed setting Icon', 'bricks' ),
          'type'     => 'icon',
          'css'      => [
            [
              'selector' => '.vds-icon.vds-active-icon',
            ],
          ],
          'required'  => ['control', '=', 'settings'],
        ],

        'settingsQualityIcon' => [
          'tab'      => 'content',
          'group' => 'buttonIcons',
          'label'    => esc_html__( 'Quality setting icon', 'bricks' ),
          'type'     => 'icon',
          'css'      => [
            [
              'selector' => '.vds-icon.vds-active-icon',
            ],
          ],
          'required'  => ['control', '=', 'settings'],
        ],
        'settingsChaptersIcon' => [
          'tab'      => 'content',
          'group' => 'buttonIcons',
          'label'    => esc_html__( 'Chapters setting icon', 'bricks' ),
          'type'     => 'icon',
          'css'      => [
            [
              'selector' => '.vds-icon.vds-active-icon',
            ],
          ],
          'required'  => ['control', '=', 'settings'],
        ],
        'settingsCaptionsIcon' => [
          'tab'      => 'content',
          'group' => 'buttonIcons',
          'label'    => esc_html__( 'Caption setting icon', 'bricks' ),
          'type'     => 'icon',
          'css'      => [
            [
              'selector' => '.vds-icon.vds-active-icon',
            ],
          ],
          'required'  => ['control', '=', 'settings'],
        ],

        
      ];


      $this->controls['controlsTopSep'] = [
        'tab' => 'content',
        'label'    => esc_html__( 'Top UI controls', 'bricks' ),
        'description' => esc_html__( 'Add/Remove/Re-order the internal controls', 'bricks' ),
        'type' => 'separator',
        'group' => 'controls',
        'required' => ['uiType','!=','custom']
      ];
  
      $this->controls['controlsTop'] = [
          'group' => 'controls',
          //'label'    => esc_html__( 'Controls', 'bricks' ),
          'placeholder'   => esc_html__( 'Control', 'bricks' ),
          'titleProperty' => 'control',
          'type'  => 'repeater',
          'required' => ['uiType','!=','custom'],
          'fields'        => $controlFields,
          'default'       => [
              [ 'control' => 'title' ],
              [ 'control' => 'spacer' ],
          ],
      ];

      $this->controls['controlGapTop'] = [
        'tab'    => 'content',
        'type'   => 'number',
        'units'   => true,
        'group' => 'controls',
        'label'  => esc_html__( 'Gap', 'extras' ),
        'css'    => [
          [
            'property' => '--media-button-gap',
            'selector' => '.vds-controls-group_top',
          ],
        ],
        'required' => ['uiType','!=','custom'],
      ];


      $this->controls['controlsCenterSep'] = [
        'tab' => 'content',
        'label'    => esc_html__( 'Center UI controls', 'bricks' ),
        'description' => esc_html__( 'Add/Remove/Re-order the internal controls', 'bricks' ),
        'type' => 'separator',
        'group' => 'controls',
        'required' => ['uiType','!=','custom']
      ];

      
  
      $this->controls['controlsCenter'] = [
          'group' => 'controls',
          //'label'    => esc_html__( 'Controls', 'bricks' ),
          'placeholder'   => esc_html__( 'Control', 'bricks' ),
          'titleProperty' => 'control',
          'type'  => 'repeater',
          'required' => ['uiType','!=','custom'],
          'fields'        => $controlFields,
          'default'       => [],
      ];

      $this->controls['controlGapCenter'] = [
        'tab'    => 'content',
        'type'   => 'number',
        'units'   => true,
        'group' => 'controls',
        'label'  => esc_html__( 'Gap', 'extras' ),
        'css'    => [
          [
            'property' => '--media-button-gap',
            'selector' => '.vds-controls-group_center',
          ],
        ],
        'required' => ['uiType','!=','custom'],
      ];


    $this->controls['controlsSep'] = [
      'tab' => 'content',
      'label'    => esc_html__( 'Bottom UI Controls', 'bricks' ),
      'description' => esc_html__( 'Add/Remove/Re-order the internal controls', 'bricks' ),
      'type' => 'separator',
      'group' => 'controls',
      'required' => ['uiType','!=','custom']
    ];


    $this->controls['controls'] = [
        'group' => 'controls',
        //'label'    => esc_html__( 'Controls', 'bricks' ),
        'placeholder'   => esc_html__( 'Control', 'bricks' ),
        'titleProperty' => 'control',
        'type'  => 'repeater',
        'required' => ['uiType','!=','custom'],
        'fields'        => $controlFields,
        'default'       => [
            [ 'control' => 'play' ],
            [ 'control' => 'mute' ],
            [ 'control' => 'time' ],
            [ 'control' => 'spacer' ],
            [ 'control' => 'pip' ],
            [ 'control' => 'settings' ],
            [ 'control' => 'fullscreen' ],
        ],
    ];

    $this->controls['controlGapBottom'] = [
      'tab'    => 'content',
      'type'   => 'number',
      'units'   => true,
      'group' => 'controls',
      'label'  => esc_html__( 'Gap', 'extras' ),
      'css'    => [
        [
          'property' => '--media-button-gap',
          'selector' => '.vds-controls-group_bottom',
        ],
      ],
      'required' => ['uiType','!=','custom'],
    ];
    


    $this->controls['extraControls'] = [
      'group' => 'controls',
			'label'    => esc_html__( 'Extra controls', 'bricks' ),
			'type'        => 'separator',
		];




    /* audio UI */

    $this->controls['controlsTopSepAudio'] = [
      'tab' => 'content',
      'label'    => esc_html__( 'Top UI controls', 'bricks' ),
      'description' => esc_html__( 'Add/Remove/Re-order the internal controls', 'bricks' ),
      'type' => 'separator',
      'group' => 'controlsAudio',
      'required' => ['uiType','!=','custom']
    ];

    

    $this->controls['controlsTopAudio'] = [
        'group' => 'controlsAudio',
        //'label'    => esc_html__( 'Controls', 'bricks' ),
        'placeholder'   => esc_html__( 'Control', 'bricks' ),
        'titleProperty' => 'control',
        'type'  => 'repeater',
        'required' => ['uiType','!=','custom'],
        'fields'        => $controlFields,
        'default'       => [],
    ];


    $this->controls['controlsCenterSepAudio'] = [
      'tab' => 'content',
      'label'    => esc_html__( 'Center UI controls', 'bricks' ),
      'description' => esc_html__( 'Add/Remove/Re-order the internal controls', 'bricks' ),
      'type' => 'separator',
      'group' => 'controlsAudio',
      'required' => ['uiType','!=','custom']
    ];

    

    $this->controls['controlsCenterAudio'] = [
        'group' => 'controlsAudio',
        //'label'    => esc_html__( 'Controls', 'bricks' ),
        'placeholder'   => esc_html__( 'Control', 'bricks' ),
        'titleProperty' => 'control',
        'type'  => 'repeater',
        'required' => ['uiType','!=','custom'],
        'fields'        => $controlFields,
        'default'       => [
          [ 'control' => 'time-slider' ],
        ],
    ];


  $this->controls['controlsSepAudio'] = [
    'tab' => 'content',
    'label'    => esc_html__( 'Bottom UI Controls', 'bricks' ),
    'description' => esc_html__( 'Add/Remove/Re-order the internal controls', 'bricks' ),
    'type' => 'separator',
    'group' => 'controlsAudio',
    'required' => ['uiType','!=','custom']
  ];


  $this->controls['controlsBottomAudio'] = [
      'group' => 'controlsAudio',
      //'label'    => esc_html__( 'Controls', 'bricks' ),
      'placeholder'   => esc_html__( 'Control', 'bricks' ),
      'titleProperty' => 'control',
      'type'  => 'repeater',
      'required' => ['uiType','!=','custom'],
      'fields'        => $controlFields,
      'default'       => [
          [ 'control' => 'seek-backward' ],
          [ 'control' => 'play' ],
          [ 'control' => 'seek-forward' ],
          [ 'control' => 'time' ],
          [ 'control' => 'title' ],
          [ 'control' => 'spacer' ],
          [ 'control' => 'mute' ],
          [ 'control' => 'settings' ],
      ],
  ];



  /* small ui */  

    $this->controls['smallControlsSep'] = [
      'group' => 'controlsSmall',
			'label'    => esc_html__( 'Small UI', 'bricks' ),
      'description' => esc_html__( 'Optional: Create a new UI for the player if contained within a specific width', 'bricks' ),
			'type'        => 'separator',
		];

    $this->controls['queryType'] = [
      'group' => 'controlsSmall',
			'label'    => esc_html__( 'Query', 'bricks' ),
			'type'        => 'select',
			'options'     => [
				'media' => esc_html__( 'Media Query', 'bricks' ),
				'container'  => esc_html__( 'Container Query', 'bricks' ),
        'none' => esc_html__( 'None (always same layout)', 'bricks' ),
			],
			'placeholder' => esc_html__( 'Container Query', 'bricks' ),
      'inline'      => true,
		];

    $this->controls['smallControlsBreakpoint'] = [
      'group' => 'controlsSmall',
			'label'    => esc_html__( 'Width of player (px)', 'bricks' ),
			'type'        => 'number',
      'placeholder' => '478',
      'inline' => true,
      'required' => ['queryType','!=',['none','media']],
		];

    $this->controls['smallControlsMediaBreakpoint'] = [
      'group' => 'controlsSmall',
			'label'    => esc_html__( 'Use small UI from current breakpoint', 'bricks' ),
			'type'        => 'select',
      'placeholder' => 'false',
			'options'     => [
				'true' => esc_html__( 'True', 'bricks' ),
				'false'  => esc_html__( 'False', 'bricks' ),
			],
      'inline' => true,
      'required' => ['queryType','=',['media']],
      'css'    => [
        [
          'selector' => '&[data-x-media-player*=media] media-layout',
          'property' => '--x-media-layout',
          'value'    => 'var(--x-media-layout-small)',
          'required' => 'true',
        ],
        [
          'selector' => '&[data-x-media-player*=media] media-layout',
          'property' => '--x-media-layout',
          'value'    => 'var(--x-media-layout-large)',
          'required' => 'false',
        ],
        
      ],
		];

    $this->controls['smallControlsTopSep'] = [
      'tab' => 'content',
      'label'    => esc_html__( 'Top UI Controls', 'bricks' ),
      'description' => esc_html__( 'Add/Remove/Re-order the internal controls', 'bricks' ),
      'type' => 'separator',
      'group' => 'controlsSmall',
      'required' => [
        ['uiType','!=','custom'],
        ['queryType','!=','none']
      ]
    ];

    $this->controls['smallControlsTop'] = [
      'group' => 'controlsSmall',
      'label'    => esc_html__( 'Controls', 'bricks' ),
      'placeholder'   => esc_html__( 'Controls', 'bricks' ),
      'titleProperty' => 'control',
      'type'  => 'repeater',
      'required' => [
        ['uiType','!=','custom'],
        ['queryType','!=','none']
      ],
      'fields'        => $controlFields,
      'default'       => [
        [ 'control' => 'mute' ],
        [ 'control' => 'spacer' ],
        [ 'control' => 'fullscreen' ],
        [ 'control' => 'settings' ],
      ],
  ];

    
    $this->controls['smallcontrolsCenterSep'] = [
      'tab' => 'content',
      'label'    => esc_html__( 'Center UI controls', 'bricks' ),
      'description' => esc_html__( 'Add/Remove/Re-order the internal controls', 'bricks' ),
      'type' => 'separator',
      'group' => 'controlsSmall',
      'required' => [
        ['uiType','!=','custom'],
        ['queryType','!=','none']
      ]
    ];

    

    $this->controls['smallcontrolsCenter'] = [
        'group' => 'controlsSmall',
        //'label'    => esc_html__( 'Controls', 'bricks' ),
        'placeholder'   => esc_html__( 'Control', 'bricks' ),
        'titleProperty' => 'control',
        'type'  => 'repeater',
        'required' => [
          ['uiType','!=','custom'],
          ['queryType','!=','none']
        ],
        'fields'        => $controlFields,
        'default'       => [
          [ 'control' => 'spacer' ],
          [ 'control' => 'play-large' ],
          [ 'control' => 'spacer' ],
        ],
    ];



    

    $this->controls['smallControlsBottomSep'] = [
      'tab' => 'content',
      'label'    => esc_html__( 'Bottom UI Controls', 'bricks' ),
      'description' => esc_html__( 'Add/Remove/Re-order the internal controls', 'bricks' ),
      'type' => 'separator',
      'group' => 'controlsSmall',
      'required' => [
        ['uiType','!=','custom'],
        ['queryType','!=','none']
      ]
    ];

    

    $this->controls['smallControls'] = [
      'group' => 'controlsSmall',
      'label'    => esc_html__( 'Controls', 'bricks' ),
      'placeholder'   => esc_html__( 'Control', 'bricks' ),
      'titleProperty' => 'control',
      'type'  => 'repeater',
      'required' => [
        ['uiType','!=','custom'],
        ['queryType','!=','none']
      ],
      'fields'        => $controlFields,
      'default'       => [
          [ 'control' => 'title' ],
      ],
    ];

    



    $this->controls['maybeTimeSlider'] = [
      'group' => 'controls',
			'label'    => esc_html__( 'Full width time slider', 'bricks' ),
			'type'        => 'select',
			'options'     => [
				'enable' => esc_html__( 'Enable', 'bricks' ),
				'disable'  => esc_html__( 'Disable', 'bricks' ),
			],
			'placeholder' => esc_html__( 'Enable', 'bricks' ),
      'inline'      => true,
      'required' => ['uiType','!=','custom'],
      'info' => esc_html__( 'Above bottom UI controls', 'bricks' ),
		];

    $this->controls['maybeNotice'] = [
      'group' => 'noticeOverlay',
			'label'    => esc_html__( 'Add text notice', 'bricks' ),
			'type'        => 'select',
			'options'     => [
				'enable' => esc_html__( 'Enable', 'bricks' ),
				'disable'  => esc_html__( 'Disable', 'bricks' ),
			],
			'placeholder' => esc_html__( 'Disable', 'bricks' ),
      'inline'      => true,
      'required' => ['uiType','!=','custom'],
		];

    $this->controls['noticeText'] = [
      'group' => 'noticeOverlay',
			'label'    => esc_html__( 'Notice text', 'bricks' ),
			'type'        => 'text',
			'placeholder' => esc_html__( 'Notice: Your message here', 'bricks' ),
     // 'inline'      => true,
      'required' => [
        ['uiType','!=','custom'],
        ['maybeNotice','=','enable']
      ]
		];

    $this->controls['showNotice'] = [
      'group' => 'noticeOverlay',
			'label'    => esc_html__( 'Show notice', 'bricks' ),
			'type'        => 'select',
			'options'     => [
				'idle' => esc_html__( 'When other controls show', 'bricks' ),
				'first'  => esc_html__( 'Only once before play', 'bricks' ),
        'interact'  => esc_html__( 'Only once before user clicks', 'bricks' ),
			],
			'placeholder' => esc_html__( 'Only once before play', 'bricks' ),
      'inline'      => true,
      'required' => [
        ['uiType','!=','custom'],
        ['maybeNotice','=','enable']
      ]
		];


    /*Notice text */

    $noticeText = '.x-notice-text';

    $this->controls['noticeTextBg'] = [
      'tab'    => 'content',
      'type'   => 'color',
      'group' => 'noticeOverlay',
      'label'  => esc_html__( 'Background', 'extras' ),
      'css'    => [
        [
          'property' => 'background-color',
          'selector' => $noticeText,
        ],
      ],
      'required' => [
        ['uiType','!=','custom'],
        ['maybeNotice','=','enable']
      ]
    ];

    $this->controls['noticeTextBorder'] = [
      'tab'    => 'content',
      'type'   => 'border',
      'group' => 'noticeOverlay',
      'label'  => esc_html__( 'Border', 'extras' ),
      'css'    => [
        [
          'property' => 'border',
          'selector' => $noticeText
        ],
      ],
      'required' => [
        ['uiType','!=','custom'],
        ['maybeNotice','=','enable']
      ]
    ];
    

    $this->controls['noticeTextTypography'] = [
      'tab'    => 'content',
      'type'   => 'typography',
      'group' => 'noticeOverlay',
      'label'  => esc_html__( 'Typography', 'extras' ),
      'css'    => [
        [
          'property' => 'font',
          'selector' => $noticeText
        ],
      ],
      'required' => [
        ['uiType','!=','custom'],
        ['maybeNotice','=','enable']
      ]
    ];

    $this->controls['noticeTextPadding'] = [
      'tab'    => 'content',
      'type'   => 'dimensions',
      'group' => 'noticeOverlay',
      'label'  => esc_html__( 'Padding', 'extras' ),
      'css'    => [
        [
          'property' => 'padding',
          'selector' => $noticeText
        ],
      ],
      'required' => [
        ['uiType','!=','custom'],
        ['maybeNotice','=','enable']
      ]
    ];

    $this->controls['noticeTextWidth'] = [
      'tab'    => 'content',
      'type'   => 'number',
      'units'   => true,
      'group' => 'noticeOverlay',
      'label'  => esc_html__( 'Width', 'extras' ),
      'css'    => [
        [
          'property' => 'width',
          'selector' => $noticeText
        ],
      ],
      'required' => [
        ['uiType','!=','custom'],
        ['maybeNotice','=','enable']
      ]
    ];

    $this->controls['noticeTextHeight'] = [
      'tab'    => 'content',
      'type'   => 'number',
      'units'   => true,
      'group' => 'noticeOverlay',
      'label'  => esc_html__( 'Height', 'extras' ),
      'css'    => [
        [
          'property' => 'height',
          'selector' => $noticeText
        ],
      ],
      'required' => [
        ['uiType','!=','custom'],
        ['maybeNotice','=','enable']
      ]
    ];


    $this->controls['noticeJustifyContent'] = [
			'tab'      => 'content',
			'label'    => esc_html__( 'Align main axis', 'bricks' ),
			'group'		  => 'noticeOverlay',
			'tooltip'  => [
				'content'  => 'justify-content',
				'position' => 'top-left',
			],
			'type'     => 'justify-content',
			'css'      => [
				[
					'property' => 'justify-content',
					'selector' => '.vds-custom-overlay',
				],
        [
					'property' => 'justify-content',
					'selector' => $noticeText
				],
			],
			'required' => [
        ['uiType','!=','custom'],
        ['maybeNotice','=','enable']
      ]
		];

		$this->controls['noticeAlignItems'] = [
			'tab'      => 'content',
			'label'    => esc_html__( 'Align cross axis', 'bricks' ),
			'group'		  => 'noticeOverlay',
			'tooltip'  => [
				'content'  => 'align-items',
				'position' => 'top-left',
			],
			'type'     => 'align-items',
			'css'      => [
				[
					'property' => 'align-items',
					'selector' => '.vds-custom-overlay',
				],
			],
			'required' => [
        ['uiType','!=','custom'],
        ['maybeNotice','=','enable']
      ]
		];


    $this->controls['extraControlsSmall'] = [
      'group' => 'controlsSmall',
      'label'    => esc_html__( 'Extra controls', 'bricks' ),
      'type'        => 'separator',
      'required' => [
        ['uiType','!=','custom'],
        ['queryType','!=','none']
      ]
    ];
  
      $this->controls['maybeTimeSliderSmall'] = [
        'group' => 'controlsSmall',
        'label'    => esc_html__( 'Bottom time slider', 'bricks' ),
        'info'    => esc_html__( 'Below bottom UI', 'bricks' ),
        'type'        => 'select',
        'options'     => [
          'enable' => esc_html__( 'Enable', 'bricks' ),
          'disable'  => esc_html__( 'Disable', 'bricks' ),
        ],
        'placeholder' => esc_html__( 'Enable', 'bricks' ),
        'inline'      => true,
        'required' => [
          ['uiType','!=','custom'],
          ['queryType','!=','none']
        ]
      ];

    
   

   $this->controls['controlsAdvancedSep'] = [
    'tab' => 'content',
    'label'    => esc_html__( 'Custom UI', 'bricks' ),
    'description' => esc_html__( 'All default UI is removed, add a block element inside the media player for the layout. Add "media controls" element inside block to build custom UI. The style settings below can still be used to style the controls globally for the player', 'bricks' ),
    'type' => 'separator',
    'group' => 'controls',
    'required' => ['uiType','=','custom']
  ];



    /* style controls */

    $this->controls['playerStyleSep'] = [
      'group' => 'styleControls',
			'label'    => esc_html__( 'Player', 'bricks' ),
			'type'        => 'separator',
		];

    $this->controls['playerColor'] = [
      'tab'    => 'content',
      'type'   => 'color',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Player background', 'extras' ),
      'css'    => [
        [
          'property' => 'background-color',
          'selector' => '&[data-view-type=video]',
        ],
        [
          'property' => 'background-color',
          'selector' => '&[data-view-type=audio]',
        ],
      ],
    ];

    
    $this->controls['brandColor'] = [
      'tab'    => 'content',
      'type'   => 'color',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Primary brand color', 'extras' ),
      'css'    => [
        [
          'property' => '--media-brand',
          'selector' => '',
        ],
      ],
    ];

    $this->controls['focusRingColor'] = [
      'tab'    => 'content',
      'type'   => 'color',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Focus ring color', 'extras' ),
      'info'  => esc_html__( 'When tabbing through controls', 'extras' ),
      'css'    => [
        [
          'property' => '--media-focus-ring-color',
          'selector' => '',
        ],
      ],
    ];

    /*$this->controls['pausedVideoOpacity'] = [
      'tab'    => 'content',
      'type'   => 'number',
      'step'   => 0.05,
      'group' => 'styleControls',
      'label'  => esc_html__( 'Video opacity (when paused)', 'extras' ),
      'css'    => [
        [
          'property' => 'opacity',
          'selector' => '&[data-paused] media-provider',
        ],
      ],
      'placeholder' => '0.85',
      'required' => [
        ['provider','!=','audio'],
      ],
    ];*/

    $this->controls['controlsVideoOpacity'] = [
      'tab'    => 'content',
      'type'   => 'number',
      'step'   => 0.05,
      'group' => 'styleControls',
      'label'  => esc_html__( 'Video opacity (when controls show)', 'extras' ),
      'css'    => [
        [
          'property' => 'opacity',
          'selector' => '&[data-controls] media-provider',
        ],
        [
          'property' => 'opacity',
          'selector' => '&[data-buffering] media-provider',
        ],
        [
          'property' => 'opacity',
          'selector' => '&[data-paused] media-provider',
        ],
      ],
      'placeholder' => '.8',
      'required' => [
        ['provider','!=','audio'],
      ],
    ];
    
    
    $this->controls['playerBorder'] = [
      'tab'    => 'content',
      'type'   => 'number',
      'units'   => true,
      'group' => 'styleControls',
      'label'  => esc_html__( 'Player border radius', 'extras' ),
      'css'    => [
        [
          'property' => 'border-radius',
          'selector' => '&[data-view-type=video] media-provider',
        ],
        [
          'property' => 'border-radius',
          'selector' => '',
        ],
        [
          'property' => 'border-radius',
          'selector' => '&::before',
        ],
        
      ],
    ];

    $this->controls['controlsPadding'] = [
      'tab'    => 'content',
      'type'   => 'number',
      'units'   => true,
      'group' => 'styleControls',
      'label'  => esc_html__( 'Inner spacing', 'extras' ),
      'info'  => esc_html__( 'Move controls away from edge', 'extras' ),
      'css'    => [
        [
          'property' => '--media-controls-padding',
          'selector' => '',
        ],
      ],
      'placeholder' => '0',
      'required' => ['uiType','!=','custom'],
    ];

    $this->controls['controlsStyleSep'] = [
      'group' => 'styleControls',
			'label'    => esc_html__( 'Button Controls', 'bricks' ),
			'type'        => 'separator',
		];


    $this->controls['iconSize'] = [
      'tab'    => 'content',
      'type'   => 'number',
      'units'   => true,
      'group' => 'styleControls',
      'label'  => esc_html__( 'Icon size', 'extras' ),
      'info'  => esc_html__( 'Increase control size also if need to go larger', 'extras' ),
      'css'    => [
        [
          'property' => '--media-button-icon-size',
          'selector' => '',
        ],
      ],
      'placeholder' => '24px'
    ];

    $this->controls['controlSize'] = [
      'tab'    => 'content',
      'type'   => 'number',
      'units'   => true,
      'group' => 'styleControls',
      'label'  => esc_html__( 'Control size', 'extras' ),
      'css'    => [
        [
          'property' => '--media-button-size',
          'selector' => '',
        ],
      ],
      'placeholder' => '40px'
    ];

    $controlButton = '.vds-button:not(.vds-settings-icon)';
    $controlButtonActive = '[data-pressed].vds-button:not(.vds-settings-icon)';

    $this->controls['iconColor'] = [
      'tab'    => 'content',
      'type'   => 'color',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Color', 'extras' ),
      'css'    => [
        [
          'property' => 'color',
          'selector' => $controlButton
        ],
      ],
    ];

    $this->controls['controlBg'] = [
      'tab'    => 'content',
      'type'   => 'color',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Background color', 'extras' ),
      'css'    => [
        [
          'property' => 'background-color',
          'selector' => $controlButton
        ],
      ],
    ];

    $this->controls['controlBorder'] = [
      'tab'    => 'content',
      'type'   => 'border',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Border', 'extras' ),
      'css'    => [
        [
          'property' => 'border',
          'selector' => $controlButton
        ],
      ],
    ];

    $this->controls['buttonActiveStart'] = [
      'group' => 'styleControls',
			'type'        => 'separator',
		];

    $this->controls['iconColorActive'] = [
      'tab'    => 'content',
      'type'   => 'color',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Color (active)', 'extras' ),
      'css'    => [
        [
          'property' => 'color',
          'selector' => $controlButtonActive
        ],
      ],
    ];

    $this->controls['controlBgActive'] = [
      'tab'    => 'content',
      'type'   => 'color',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Background color (active)', 'extras' ),
      'css'    => [
        [
          'property' => 'background-color',
          'selector' => $controlButtonActive
        ],
      ],
    ];

    $this->controls['controlBorderActive'] = [
      'tab'    => 'content',
      'type'   => 'border',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Border (active)', 'extras' ),
      'css'    => [
        [
          'property' => 'border',
          'selector' => $controlButtonActive
        ],
      ],
    ];

    $this->controls['buttonActiveEnd'] = [
      'group' => 'styleControls',
			'type'        => 'separator',
		];

    

    $this->controls['controlPadding'] = [
      'tab'    => 'content',
      'type'   => 'dimensions',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Padding', 'extras' ),
      'css'    => [
        [
          'property' => 'padding',
          'selector' => $controlButton
        ],
        [
          'property' => 'padding',
          'selector' => '.vds-time-group'
        ],
      ],

      
    ];

    

    $this->controls['controlsStyleSepPlay'] = [
      'group' => 'styleControls',
			'label'    => esc_html__( 'Play (large)', 'bricks' ),
			'type'        => 'separator',
		];

    $largePlay = '.vds-button-large:not(.vds-settings-icon)';

    $this->controls['iconSizePlay'] = [
      'tab'    => 'content',
      'type'   => 'number',
      'units'   => true,
      'group' => 'styleControls',
      'label'  => esc_html__( 'Icon size', 'extras' ),
      'css'    => [
        [
          'property' => '--media-button-icon-size',
          'selector' => $largePlay,
        ],
        
      ],
      'placeholder' => '40px'
    ];

    $this->controls['controlSizePlay'] = [
      'tab'    => 'content',
      'type'   => 'number',
      'units'   => true,
      'group' => 'styleControls',
      'label'  => esc_html__( 'Control size', 'extras' ),
      'css'    => [
        [
          'property' => '--media-button-size',
          'selector' => $largePlay,
        ],
      ],
      'placeholder' => '60px'
    ];

    

    $this->controls['controlBgPlay'] = [
      'tab'    => 'content',
      'type'   => 'color',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Background', 'extras' ),
      'css'    => [
        [
          'property' => 'background-color',
          'selector' => $largePlay,
        ],
      ],
    ];

    $this->controls['iconColorPlay'] = [
      'tab'    => 'content',
      'type'   => 'color',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Color', 'extras' ),
      'css'    => [
        [
          'property' => 'color',
          'selector' => $largePlay
        ],
      ],
    ];

    $this->controls['controlBorderPlay'] = [
      'tab'    => 'content',
      'type'   => 'border',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Border', 'extras' ),
      'css'    => [
        [
          'property' => 'border',
          'selector' => $largePlay,
        ],
      ],
    ];

    $this->controls['controlPaddingPlay'] = [
      'tab'    => 'content',
      'type'   => 'dimensions',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Padding', 'extras' ),
      'css'    => [
        [
          'property' => 'padding',
          'selector' => $largePlay
        ],
      ],
    ];

    
    $this->controls['timeSliderStyleSep'] = [
      'group' => 'styleControls',
			'label'    => esc_html__( 'Time slider', 'bricks' ),
			'type'        => 'separator',
		];

    $timeSliderSelector = '.vds-time-slider.vds-slider';

    $this->controls['trackHeight'] = [
      'tab'    => 'content',
      'type'   => 'number',
      'units'   => true,
      'group' => 'styleControls',
      'label'  => esc_html__( 'Slider Track height', 'extras' ),
      'css'    => [
        [
          'property' => '--track-height',
          'selector' => $timeSliderSelector
        ],
      ],
      'placeholder' => '5px'
    ];

    $this->controls['sliderHeight'] = [
      'tab'    => 'content',
      'type'   => 'number',
      'units'   => true,
      'group' => 'styleControls',
      'label'  => esc_html__( 'Control height', 'extras' ),
      'css'    => [
        [
          'property' => '--media-slider-height',
          'selector' => $timeSliderSelector
        ],
      ],
      'placeholder' => '30px'
    ];

    

    $this->controls['timeSliderTrackSep'] = [
      'group' => 'styleControls',
			'type'        => 'separator',
		];


    $this->controls['trackFillBackground'] = [
      'tab'    => 'content',
      'type'   => 'color',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Track progress color', 'extras' ),
      'css'    => [
        [
          'property' => '--media-slider-track-fill-bg',
          'selector' => $timeSliderSelector
        ],
      ],
    ];

    $this->controls['trackProgressBackground'] = [
      'tab'    => 'content',
      'type'   => 'color',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Track buffered color', 'extras' ),
      'css'    => [
        [
          'property' => '--media-slider-track-progress-bg',
          'selector' => $timeSliderSelector
        ],
      ],
    ];

    $this->controls['trackBackground'] = [
      'tab'    => 'content',
      'type'   => 'color',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Track background color', 'extras' ),
      'css'    => [
        [
          'property' => '--media-slider-track-bg',
          'selector' => $timeSliderSelector,
        ],
      ],
    ];

    $this->controls['trackLiveBackground'] = [
      'tab'    => 'content',
      'type'   => 'color',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Track background (live)', 'extras' ),
      'css'    => [
        [
          'property' => '--media-slider-track-fill-live-bg',
          'selector' => $timeSliderSelector,
        ],
      ],
    ];

    

    $this->controls['timeSliderThumbSep'] = [
      'group' => 'styleControls',
			'type'        => 'separator',
		];

    $this->controls['thumbColor'] = [
      'tab'    => 'content',
      'type'   => 'color',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Thumb color', 'extras' ),
      'css'    => [
        [
          'property' => '--media-slider-thumb-bg',
          'selector' => $timeSliderSelector
        ],
      ],
    ];

    $this->controls['thumbSize'] = [
      'tab'    => 'content',
      'type'   => 'number',
      'units'   => true,
      'group' => 'styleControls',
      'label'  => esc_html__( 'Thumb size', 'extras' ),
      'css'    => [
        [
          'property' => '--thumb-size',
          'selector' => $timeSliderSelector
        ],
      ],
      'placeholder' => '15px'
    ];

    $this->controls['thumbBorder'] = [
      'tab'    => 'content',
      'type'   => 'border',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Thumb border', 'extras' ),
      'css'    => [
        [
          'property' => 'border',
          'selector' => '.vds-time-slider .vds-slider-thumb'
        ],
      ],
    ];

    $this->controls['focusThumbSize'] = [
      'tab'    => 'content',
      'type'   => 'number',
      'units'   => true,
      'group' => 'styleControls',
      'label'  => esc_html__( 'Thumb size (focus)', 'extras' ),
      'css'    => [
        [
          'property' => '--thumb-focus-size',
          'selector' => $timeSliderSelector
        ],
      ],
      'placeholder' => '17px'
    ];

    $this->controls['sliderValueSep'] = [
      'group' => 'styleControls',
			'label'    => esc_html__( 'Slider time value', 'bricks' ),
			'type'        => 'separator',
		];
    
    $sliderValueSelector = '.vds-time-slider .vds-slider-value';

    $this->controls['sliderValueDisplay'] = [
      'tab'    => 'content',
      'type'   => 'select',
      'inline' => true,
      'options' => [
        'block' => 'Block',
        'none' => 'None',
      ],
      'group' => 'styleControls',
      'placeholder' => 'Block',
      'label'  => esc_html__( 'Value display', 'extras' ),
      'css'    => [
        [
          'property' => 'display',
          'selector' => '.vds-slider-value',
        ],
      ],
    ];

    $this->controls['sliderOffset'] = [
      'tab'    => 'content',
      'type'   => 'number',
      'units' => true,
      'group' => 'styleControls',
      'label'  => esc_html__( 'Value offset', 'extras' ),
      'css'    => [
        [
          'property' => '--media-slider-preview-offset',
          'selector' => '.vds-time-slider',
        ],
      ],
    ];

    $this->controls['sliderValueBg'] = [
      'tab'    => 'content',
      'type'   => 'color',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Background', 'extras' ),
      'css'    => [
        [
          'property' => '--media-slider-value-bg',
          'selector' => $sliderValueSelector,
        ],
      ],
    ];

    $this->controls['sliderValueBorder'] = [
      'tab'    => 'content',
      'type'   => 'border',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Border', 'extras' ),
      'css'    => [
        [
          'property' => 'border',
          'selector' => $sliderValueSelector
        ],
      ],
    ];
    

    $this->controls['sliderValueTypography'] = [
      'tab'    => 'content',
      'type'   => 'typography',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Typography', 'extras' ),
      'css'    => [
        [
          'property' => 'font',
          'selector' => $sliderValueSelector
        ],
      ],
    ];

    $this->controls['sliderValuePadding'] = [
      'tab'    => 'content',
      'type'   => 'dimensions',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Padding', 'extras' ),
      'css'    => [
        [
          'property' => 'padding',
          'selector' => $sliderValueSelector
        ],
      ],
    ];

    /* slider chapter title*/

    $this->controls['sliderChapterTitleSep'] = [
      'group' => 'styleControls',
			'label'    => esc_html__( 'Slider chapter value', 'bricks' ),
			'type'        => 'separator',
		];
    
    $sliderChapterTitle = '.vds-slider-chapter-title';

    $this->controls['sliderChapterDisplay'] = [
      'tab'    => 'content',
      'type'   => 'select',
      'inline' => true,
      'options' => [
        'block' => 'Block',
        'none' => 'None',
      ],
      'group' => 'styleControls',
      'placeholder' => 'Block',
      'label'  => esc_html__( 'Chapter display', 'extras' ),
      'css'    => [
        [
          'property' => 'display',
          'selector' => '.vds-slider-chapter-title',
        ],
      ],
    ];

    $this->controls['sliderChapterTitleBg'] = [
      'tab'    => 'content',
      'type'   => 'color',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Background', 'extras' ),
      'css'    => [
        [
          'property' => 'background-color',
          'selector' => $sliderChapterTitle,
        ],
      ],
    ];

    $this->controls['sliderChapterTitleBorder'] = [
      'tab'    => 'content',
      'type'   => 'border',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Border', 'extras' ),
      'css'    => [
        [
          'property' => 'border',
          'selector' => $sliderChapterTitle
        ],
      ],
    ];
    

    $this->controls['sliderChapterTitleTypography'] = [
      'tab'    => 'content',
      'type'   => 'typography',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Typography', 'extras' ),
      'css'    => [
        [
          'property' => 'font',
          'selector' => $sliderChapterTitle
        ],
      ],
    ];

    $this->controls['sliderChapterTitlePadding'] = [
      'tab'    => 'content',
      'type'   => 'dimensions',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Padding', 'extras' ),
      'css'    => [
        [
          'property' => 'padding',
          'selector' => $sliderChapterTitle
        ],
      ],
    ];

    


    /* cues */


    $this->controls['cuesSep'] = [
      'group' => 'styleControls',
			'label'    => esc_html__( 'Caption Cues', 'bricks' ),
			'type'        => 'separator',
		];
    
    $cue = '.vds-captions [data-part=cue]';

    $this->controls['cueBg'] = [
      'tab'    => 'content',
      'type'   => 'color',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Background', 'extras' ),
      'css'    => [
        [
          'property' => 'background-color',
          'selector' => $cue,
        ],
      ],
    ];

    $this->controls['cueBorder'] = [
      'tab'    => 'content',
      'type'   => 'border',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Border', 'extras' ),
      'css'    => [
        [
          'property' => 'border',
          'selector' => $cue
        ],
      ],
    ];
    

    $this->controls['cueTypography'] = [
      'tab'    => 'content',
      'type'   => 'typography',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Typography', 'extras' ),
      'css'    => [
        [
          'property' => 'font',
          'selector' => $cue
        ],
      ],
    ];

    $this->controls['cuePadding'] = [
      'tab'    => 'content',
      'type'   => 'dimensions',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Padding', 'extras' ),
      'css'    => [
        [
          'property' => 'padding',
          'selector' => $cue
        ],
      ],
    ];


    /* time */


    $this->controls['timeSep'] = [
      'group' => 'styleControls',
			'label'    => esc_html__( 'Time', 'bricks' ),
			'type'        => 'separator',
		];
    
    $time = '.vds-time';
    $currentTime = '.vds-time[data-type=current]';
    $divider = '.vds-time-divider';
    $duration = '.vds-time[data-type=duration]';

    $this->controls['timeTypography'] = [
      'tab'    => 'content',
      'type'   => 'typography',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Typography', 'extras' ),
      'css'    => [
        [
          'property' => 'font',
          'selector' => $time,
        ],
      ],
    ];

    $this->controls['dividerTypography'] = [
      'tab'    => 'content',
      'type'   => 'typography',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Divider Typography', 'extras' ),
      'css'    => [
        [
          'property' => 'font',
          'selector' => $divider,
        ],
      ],
    ];
    

    $this->controls['timeGap'] = [
      'tab'    => 'content',
      'type'   => 'number',
      'units'   => true,
      'group' => 'styleControls',
      'label'  => esc_html__( 'Gap', 'extras' ),
      'css'    => [
        [
          'property' => 'gap',
          'selector' => '.vds-time-group'
        ],
      ],
    ];



    $this->controls['volumeSliderStyleSep'] = [
      'group' => 'styleControls',
			'label'    => esc_html__( 'Volume slider', 'bricks' ),
			'type'        => 'separator',
		];

    $volumeSliderSelector = '.vds-volume-slider.vds-slider';

    $this->controls['sliderHeightVolume'] = [
      'tab'    => 'content',
      'type'   => 'number',
      'units'   => true,
      'group' => 'styleControls',
      'label'  => esc_html__( 'Control height', 'extras' ),
      'css'    => [
        [
          'property' => '--media-slider-height',
          'selector' => $volumeSliderSelector
        ],
      ],
      'placeholder' => '40px'
    ];

    $this->controls['trackHeightVolume'] = [
      'tab'    => 'content',
      'type'   => 'number',
      'units'   => true,
      'group' => 'styleControls',
      'label'  => esc_html__( 'Slider Track height', 'extras' ),
      'css'    => [
        [
          'property' => '--track-height',
          'selector' => $volumeSliderSelector
        ],
      ],
      'placeholder' => '5px'
    ];

    $this->controls['trackWidthVolume'] = [
      'tab'    => 'content',
      'type'   => 'number',
      'units'   => true,
      'group' => 'styleControls',
      'label'  => esc_html__( 'Max width', 'extras' ),
      'css'    => [
        [
          'property' => 'max-width',
          'selector' => '[data-hocus] + .vds-volume-slider'
        ],
        [
          'property' => 'max-width',
          'selector' => '.vds-volume-slider[data-active]'
        ],
        [
          'property' => 'max-width',
          'selector' => '.vds-slider.vds-visible-volume'
        ],
      ],
      'placeholder' => '80px'
    ];

    

    $this->controls['timeSliderTrackSep'] = [
      'group' => 'styleControls',
			'type'        => 'separator',
		];


    $this->controls['trackFillBackgroundVolume'] = [
      'tab'    => 'content',
      'type'   => 'color',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Track progress color', 'extras' ),
      'css'    => [
        [
          'property' => '--media-slider-track-fill-bg',
          'selector' => $volumeSliderSelector
        ],
      ],
    ];

    $this->controls['trackProgressBackgroundVolume'] = [
      'tab'    => 'content',
      'type'   => 'color',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Track buffered color', 'extras' ),
      'css'    => [
        [
          'property' => '--media-slider-track-progress-bg',
          'selector' => $volumeSliderSelector
        ],
      ],
    ];

    $this->controls['trackBackgroundVolume'] = [
      'tab'    => 'content',
      'type'   => 'color',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Track background color', 'extras' ),
      'css'    => [
        [
          'property' => '--media-slider-track-bg',
          'selector' => $volumeSliderSelector,
        ],
      ],
    ];

    $this->controls['timeSliderThumbSepVolume'] = [
      'group' => 'styleControls',
			'type'        => 'separator',
		];

    $this->controls['thumbColorVolume'] = [
      'tab'    => 'content',
      'type'   => 'color',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Thumb color', 'extras' ),
      'css'    => [
        [
          'property' => '--media-slider-thumb-bg',
          'selector' => $volumeSliderSelector
        ],
      ],
    ];

    $this->controls['thumbSizeVolume'] = [
      'tab'    => 'content',
      'type'   => 'number',
      'units'   => true,
      'group' => 'styleControls',
      'label'  => esc_html__( 'Thumb size', 'extras' ),
      'css'    => [
        [
          'property' => '--thumb-size',
          'selector' => $volumeSliderSelector
        ],
      ],
      'placeholder' => '15px'
    ];

    $this->controls['thumbBorderVolume'] = [
      'tab'    => 'content',
      'type'   => 'border',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Thumb border', 'extras' ),
      'css'    => [
        [
          'property' => 'border',
          'selector' => '.vds-volume-slider .vds-slider-thumb'
        ],
      ],
    ];

    $this->controls['focusThumbSizeVolume'] = [
      'tab'    => 'content',
      'type'   => 'number',
      'units'   => true,
      'group' => 'styleControls',
      'label'  => esc_html__( 'Thumb size (focus)', 'extras' ),
      'css'    => [
        [
          'property' => '--thumb-focus-size',
          'selector' => $volumeSliderSelector
        ],
      ],
      'placeholder' => '17px'
    ];

    $this->controls['sliderValueSepVolume'] = [
      'group' => 'styleControls',
			'label'    => esc_html__( 'Volume time value', 'bricks' ),
			'type'        => 'separator',
		];
    
    $volumeValueSelector = '.vds-volume-slider .vds-slider-value';

    
    $this->controls['volumeValueOffset'] = [
      'tab'    => 'content',
      'type'   => 'number',
      'units' => true,
      'group' => 'styleControls',
      'label'  => esc_html__( 'Volume time value offset', 'extras' ),
      'css'    => [
        [
          'property' => '--media-slider-preview-offset',
          'selector' => '.vds-volume-slider',
        ],
      ],
    ];

    $this->controls['sliderValueBgVolume'] = [
      'tab'    => 'content',
      'type'   => 'color',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Background', 'extras' ),
      'css'    => [
        [
          'property' => '--media-slider-value-bg',
          'selector' => $volumeValueSelector,
        ],
      ],
    ];

    $this->controls['sliderValueBorderVolume'] = [
      'tab'    => 'content',
      'type'   => 'border',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Border', 'extras' ),
      'css'    => [
        [
          'property' => 'border',
          'selector' => $volumeValueSelector
        ],
      ],
    ];
    

    $this->controls['sliderValueTypographyVolume'] = [
      'tab'    => 'content',
      'type'   => 'typography',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Typography', 'extras' ),
      'css'    => [
        [
          'property' => 'font',
          'selector' => $volumeValueSelector
        ],
      ],
    ];

    $this->controls['sliderValuePaddingVolume'] = [
      'tab'    => 'content',
      'type'   => 'dimensions',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Padding', 'extras' ),
      'css'    => [
        [
          'property' => 'padding',
          'selector' => $volumeValueSelector
        ],
      ],
    ];







    /* tooltips */

    $this->controls['maybeToolTips'] = [
      'group' => 'tooltips',
			'label'    => esc_html__( 'Control Tooltips', 'bricks' ),
			'type'        => 'select',
			'options'     => [
				'enable' => esc_html__( 'Enable', 'bricks' ),
				'disable'  => esc_html__( 'Disable', 'bricks' ),
			],
			'placeholder' => esc_html__( 'Enable', 'bricks' ),
			'inline'      => true,
			'small' => true,
      'required' => ['uiType','!=','custom']
		];


    $this->controls['tooltipBg'] = [
      'tab'    => 'content',
      'type'   => 'color',
      'group' => 'tooltips',
      'label'  => esc_html__( 'Background', 'extras' ),
      'css'    => [
        [
          'property' => '--media-tooltip-bg-color',
          'selector' => '',
        ],
      ],
    ];

    $this->controls['tooltipBorder'] = [
      'tab'    => 'content',
      'type'   => 'border',
      'group' => 'tooltips',
      'label'  => esc_html__( 'Border', 'extras' ),
      'css'    => [
        [
          'property' => 'border',
          'selector' => '.vds-tooltip-content',
        ],
      ],
    ];
    

    $this->controls['tooltipTypography'] = [
      'tab'    => 'content',
      'type'   => 'typography',
      'group' => 'tooltips',
      'label'  => esc_html__( 'Typography', 'extras' ),
      'css'    => [
        [
          'property' => 'font',
          'selector' => '.vds-tooltip-content',
        ],
      ],
    ];

    $this->controls['tooltipPadding'] = [
      'tab'    => 'content',
      'type'   => 'dimensions',
      'group' => 'tooltips',
      'label'  => esc_html__( 'Padding', 'extras' ),
      'css'    => [
        [
          'property' => 'padding',
          'selector' => '.vds-tooltip-content',
        ],
      ],
    ];

    $this->controls['tooltipXOffset'] = [
      'tab'    => 'content',
      'type'   => 'number',
      'units'   => true,
      'group' => 'tooltips',
      'label'  => esc_html__( 'X Offset', 'extras' ),
      'css'    => [
        [
          'property' => '--media-tooltip-x-offset',
          'selector' => '',
        ],
      ],
      'placeholder' => '0',
    ];

    $this->controls['tooltipYOffset'] = [
      'tab'    => 'content',
      'type'   => 'number',
      'units'   => true,
      'group' => 'tooltips',
      'label'  => esc_html__( 'Y Offset', 'extras' ),
      'css'    => [
        [
          'property' => '--media-tooltip-y-offset',
          'selector' => '',
        ],
      ],
      'placeholder' => '20px',
    ];

    $this->controls['defaultTooltipPlacement'] = [
      'tab'    => 'content',
      'group' => 'tooltips',
      'label'  => esc_html__( 'Default Tooltip Placement', 'extras' ),
      'type'      => 'select',
      'inline'  => true,
        'options' => $placementOptions,
        'required' => ['uiType','!=','custom'],
      'placeholder' => esc_html__('Top', 'bricks' ), 
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
      'placeholder'   => esc_html__( 'Text Track', 'bricks' ),
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
          'placeholder' => 'Subtitles'
				],
        'language' => [
          'type'    => 'text',
          'label'   => esc_html__( 'Language (en, fr, etc.)', 'bricks' ),
          'inline' => true,
          'placeholder' => 'en-US'
        ],
			],
		];

    /*

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

    */



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


    /* Live button 

    $this->controls['liveButtonSep'] = [
      'group' => 'styleControls',
			'label'    => esc_html__( 'Live button', 'bricks' ),
			'type'        => 'separator',
		];
    
    $liveButton = '.vds-live-button-text';

    $this->controls['liveButtonBg'] = [
      'tab'    => 'content',
      'type'   => 'color',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Background', 'extras' ),
      'css'    => [
        [
          'property' => 'background-color',
          'selector' => $liveButton,
        ],
      ],
    ];

    $this->controls['liveButtonBorder'] = [
      'tab'    => 'content',
      'type'   => 'border',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Border', 'extras' ),
      'css'    => [
        [
          'property' => 'border',
          'selector' => $liveButton
        ],
      ],
    ];
    

    $this->controls['liveButtonTypography'] = [
      'tab'    => 'content',
      'type'   => 'typography',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Typography', 'extras' ),
      'css'    => [
        [
          'property' => 'font',
          'selector' => $liveButton
        ],
      ],
    ];

    $this->controls['liveButtonPadding'] = [
      'tab'    => 'content',
      'type'   => 'dimensions',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Padding', 'extras' ),
      'css'    => [
        [
          'property' => 'padding',
          'selector' => $liveButton
        ],
      ],
    ]; */
    

    /* settings menu */


    $settingsMenuSelector = ".vds-menu-items:not([data-submenu])";
    $settingsMenuItemSelector = ".vds-menu-items [role=menuitemradio]";
    $settingsMenuItemRadioSelector = ".vds-menu-items [role=menuitem]";

    $this->controls['settingsMenuSep'] = [
      'group' => 'styleControls',
			'label'    => esc_html__( 'Settings Menu', 'bricks' ),
			'type'        => 'separator',
      
		];

    $this->controls['menuBg'] = [
      'tab'    => 'content',
      'type'   => 'color',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Background', 'extras' ),
      'css'    => [
        [
          'property' => 'background-color',
          'selector' => $settingsMenuSelector,
        ],
      ],
      
    ];

    $this->controls['menuBorder'] = [
      'tab'    => 'content',
      'type'   => 'border',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Border', 'extras' ),
      'css'    => [
        [
          'property' => 'border',
          'selector' => $settingsMenuSelector,
        ],
      ],
      
    ];

    $this->controls['menuPadding'] = [
      'tab'    => 'content',
      'type'   => 'dimensions',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Padding', 'extras' ),
      'css'    => [
        [
          'property' => 'padding',
          'selector' => $settingsMenuSelector,
        ],
      ],
      
    ];

    $this->controls['menuMaxHeight'] = [
      'tab'    => 'content',
      'type'   => 'number',
      'units'   => true,
      'group' => 'styleControls',
      'label'  => esc_html__( 'Max height', 'extras' ),
      'css'    => [
        [
          'property' => '--media-menu-max-height',
          'selector' => '',
        ],
      ],
      'placeholder' => '250px',
      
    ];

    $this->controls['menuMinWidth'] = [
      'tab'    => 'content',
      'type'   => 'number',
      'units'   => true,
      'group' => 'styleControls',
      'label'  => esc_html__( 'Min width', 'extras' ),
      'css'    => [
        [
          'property' => '--media-menu-min-width',
          'selector' => '',
        ],
      ],
      'placeholder' => '220px',
      
    ];

    $this->controls['menuXOffset'] = [
      'tab'    => 'content',
      'type'   => 'number',
      'units'   => true,
      'group' => 'styleControls',
      'label'  => esc_html__( 'x offset', 'extras' ),
      'css'    => [
        [
          'property' => '--media-menu-x-offset',
          'selector' => '',
        ],
      ],
      'placeholder' => '0',
      
    ];

    $this->controls['menuYOffset'] = [
      'tab'    => 'content',
      'type'   => 'number',
      'units'   => true,
      'group' => 'styleControls',
      'label'  => esc_html__( 'y offset', 'extras' ),
      'css'    => [
        [
          'property' => '--media-menu-y-offset',
          'selector' => '',
        ],
      ],
      'placeholder' => '0',
      
    ];
    
    

    $this->controls['settingsMenuItemsSep'] = [
      'group' => 'styleControls',
			'label'    => esc_html__( 'Settings Menu Items', 'bricks' ),
			'type'        => 'separator',
      
		];

    

    

    
    $this->controls['menuItemBg'] = [
      'tab'    => 'content',
      'type'   => 'color',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Background', 'extras' ),
      'css'    => [
        [
          'property' => 'background-color',
          'selector' => $settingsMenuItemSelector,
        ],
        [
          'property' => 'background-color',
          'selector' => $settingsMenuItemRadioSelector,
        ],
      ],
      
    ];

   

    $this->controls['menuItemTypography'] = [
      'tab'    => 'content',
      'type'   => 'typography',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Setting label Typography', 'extras' ),
      'css'    => [
        [
          'property' => 'font',
          'selector' => $settingsMenuItemSelector,
        ],
        [
          'property' => 'font',
          'selector' => $settingsMenuItemRadioSelector,
        ],
      ],
      
    ];

    $this->controls['menuItemHitTypography'] = [
      'tab'    => 'content',
      'type'   => 'typography',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Setting hint Typography', 'extras' ),
      'css'    => [
        [
          'property' => 'font',
          'selector' => '.vds-menu-button-hint',
        ],
      ],
      
    ];

    $this->controls['menuItemBorder'] = [
      'tab'    => 'content',
      'type'   => 'border',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Border', 'extras' ),
      'css'    => [
        [
          'property' => 'border',
          'selector' => $settingsMenuItemSelector,
        ],
        [
          'property' => 'border',
          'selector' => $settingsMenuItemRadioSelector,
        ],
      ],
      
    ];
    

    $this->controls['menuItemsGap'] = [
      'tab'    => 'content',
      'units'   => true,
      'type'   => 'number',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Menu items Gap', 'extras' ),
      'css'    => [
        [
          'property' => 'gap',
          'selector' => '.vds-menu-items:not(.vds-menu-items .vds-menu-items)'
        ],
        [
          'property' => 'gap',
          'selector' => '.vds-radio-group'
        ],
      ],
      
    ];

    

    $this->controls['menuItemPadding'] = [
      'tab'    => 'content',
      'type'   => 'dimensions',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Menu item Padding', 'extras' ),
      'css'    => [
        [
          'property' => 'padding',
          'selector' => '.vds-menu-items [role=menuitem]',
        ],
        [
          'property' => 'padding',
          'selector' => '.vds-menu-items [role=menuitemradio]',
        ],
      ],
      
    ];


    $this->controls['settingsMenuItemsSelectedSep'] = [
      'group' => 'styleControls',
			'label'    => esc_html__( 'Settings Menu Items (selected)', 'bricks' ),
			'type'        => 'separator',
      
		];

    $this->controls['menuItemBgSelected'] = [
      'tab'    => 'content',
      'type'   => 'color',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Background', 'extras' ),
      'css'    => [
        [
          'property' => 'background-color',
          'selector' => '.vds-menu-items [role=menuitemradio][aria-checked=true]'
        ],
      ],
      
    ];

   

    $this->controls['menuItemTypographySelected'] = [
      'tab'    => 'content',
      'type'   => 'typography',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Menu item typography', 'extras' ),
      'css'    => [
        [
          'property' => 'font',
          'selector' => '.vds-menu-items [role=menuitemradio][aria-checked=true]',
        ],
      ],
      
    ];

    $this->controls['menuItemHitTypographySelected'] = [
      'tab'    => 'content',
      'type'   => 'typography',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Setting hint typography', 'extras' ),
      'css'    => [
        [
          'property' => 'font',
          'selector' => '.vds-menu-items [role=menuitemradio][aria-checked=true] .vds-menu-button-hint',
        ],
      ],
      
    ];

    $this->controls['chapterItemsSep'] = [
      'group' => 'styleControls',
			'label'    => esc_html__( 'Chaper Menu Items', 'bricks' ),
			'type'        => 'separator',
		];



    $this->controls['chapterLabel'] = [
      'tab'    => 'content',
      'type'   => 'typography',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Chapter label typography', 'extras' ),
      'css'    => [
        [
          'property' => 'font',
          'selector' => '.vds-chapter-radio-label',
        ],
      ],
      
    ];

    $this->controls['chapterStartTime'] = [
      'tab'    => 'content',
      'type'   => 'typography',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Chapter start time typography', 'extras' ),
      'css'    => [
        [
          'property' => 'font',
          'selector' => '.vds-chapter-radio-start-time',
        ],
      ],
      
    ];

    $this->controls['chapterDuration'] = [
      'tab'    => 'content',
      'type'   => 'typography',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Chapter duration typography', 'extras' ),
      'css'    => [
        [
          'property' => 'font',
          'selector' => '.vds-chapter-radio-duration',
        ],
      ],
      
    ];


    $this->controls['chapterItemsHocusSep'] = [
      'group' => 'styleControls',
			'label'    => esc_html__( 'Chapter Menu Items (hover/focus)', 'bricks' ),
			'type'        => 'separator',
		];

    $this->controls['chapterProgressColor'] = [
      'tab'    => 'content',
      'type'   => 'color',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Chapter progress bar color', 'extras' ),
      'css'    => [
        [
          'property' => '--media-chapters-progress-bg',
          'selector' => ''
        ],
      ],
    ];

    $this->controls['chapterProgressHeight'] = [
      'tab'    => 'content',
      'type'   => 'number',
      'units' => true,
      'group' => 'styleControls',
      'placeholder' => '3px',
      'label'  => esc_html__( 'Chapter progress height', 'extras' ),
      'css'    => [
        [
          'property' => '--media-chapters-progress-height',
          'selector' => ''
        ],
      ],
    ];

    

    


    $this->controls['chapterLabelHocus'] = [
      'tab'    => 'content',
      'type'   => 'typography',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Chapter label typography', 'extras' ),
      'css'    => [
        [
          'property' => 'font',
          'selector' => '.vds-radio[data-hocus] .vds-chapter-radio-label',
        ],
      ],
      
    ];

    $this->controls['chapterStartTimeHocus'] = [
      'tab'    => 'content',
      'type'   => 'typography',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Chapter start time typography', 'extras' ),
      'css'    => [
        [
          'property' => 'font',
          'selector' => '.vds-radio[data-hocus] .vds-chapter-radio-start-time',
        ],
      ],
      
    ];

    $this->controls['chapterDurationHocus'] = [
      'tab'    => 'content',
      'type'   => 'typography',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Chapter duration typography', 'extras' ),
      'css'    => [
        [
          'property' => 'font',
          'selector' => '.vds-radio[data-hocus] .vds-chapter-radio-duration',
        ],
      ],
      
    ];


    $this->controls['chapterItemsSelectedSep'] = [
      'group' => 'styleControls',
			'label'    => esc_html__( 'Chapter Menu Items (selected)', 'bricks' ),
			'type'        => 'separator',
		];



    $this->controls['chapterLabelSelected'] = [
      'tab'    => 'content',
      'type'   => 'typography',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Chapter label typography', 'extras' ),
      'css'    => [
        [
          'property' => 'font',
          'selector' => '.vds-radio[aria-checked=true] .vds-chapter-radio-label',
        ],
      ],
      
    ];

    $this->controls['chapterStartTimeSelected'] = [
      'tab'    => 'content',
      'type'   => 'typography',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Chapter start time typography', 'extras' ),
      'css'    => [
        [
          'property' => 'font',
          'selector' => '.vds-radio[aria-checked=true] .vds-chapter-radio-start-time',
        ],
      ],
      
    ];

    $this->controls['chapterDurationSelected'] = [
      'tab'    => 'content',
      'type'   => 'typography',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Chapter duration typography', 'extras' ),
      'css'    => [
        [
          'property' => 'font',
          'selector' => '.vds-radio[aria-checked=true] .vds-chapter-radio-duration',
        ],
      ],
      
    ];

    
    /* behaviour */

    

   

    

    $this->controls['playsinline'] = [
      'group' => 'behaviour',
			'label'    => esc_html__( 'Playsinline', 'bricks' ),
			'type'        => 'select',
			'options'     => [
				'enable' => esc_html__( 'Enable', 'bricks' ),
				'disable'  => esc_html__( 'Disable', 'bricks' ),
			],
			'placeholder' => esc_html__( 'Enable', 'bricks' ),
			'inline'      => true,
			'small' => true,
      'required' => ['provider', '!=', 'audio'],
      'rerender' => false,
		];

    /*
    $this->controls['loop'] = [
      'group' => 'behaviour',
			'label'    => esc_html__( 'Loop', 'bricks' ),
			'type'        => 'select',
			'options'     => [
				'enable' => esc_html__( 'Enable', 'bricks' ),
				'disable'  => esc_html__( 'Disable', 'bricks' ),
			],
			'placeholder' => esc_html__( 'Disable', 'bricks' ),
			'inline'      => true,
			'small' => true,
		];
    */

    $this->controls['muted'] = [
      'group' => 'behaviour',
			'label'    => esc_html__( 'Muted', 'bricks' ),
			'type'        => 'select',
			'options'     => [
				'enable' => esc_html__( 'Enable', 'bricks' ),
				'disable'  => esc_html__( 'Disable', 'bricks' ),
			],
			'placeholder' => esc_html__( 'Disable', 'bricks' ),
			'inline'      => true,
			'small' => true,
		];

    $this->controls['autoplay'] = [
      'group' => 'behaviour',
			'label'    => esc_html__( 'Autoplay', 'bricks' ),
			'type'        => 'select',
			'options'     => [
				'enable' => esc_html__( 'Enable', 'bricks' ),
				'disable'  => esc_html__( 'Disable', 'bricks' ),
			],
			'placeholder' => esc_html__( 'Disable', 'bricks' ),
			'inline'      => true,
			'small' => true,
      'required' => ['muted', '=', 'enable'],
      'rerender' => false,
		];

    /*

    $this->controls['fullscreenOrientation'] = [
      'group' => 'behaviour',
			'label'    => esc_html__( 'Fullscreen Orientation', 'bricks' ),
			'type'        => 'select',
			'options'     => [
				'landscape' => esc_html__( 'Landscape', 'bricks' ),
				'any' => esc_html__( 'any', 'bricks' ),
        'landscape-primary' => esc_html__( 'landscape-primary', 'bricks' ),
        'landscape-secondary' => esc_html__( 'landscape-secondary', 'bricks' ),
        'natural' => esc_html__( 'natural', 'bricks' ),
        'portrait' => esc_html__( 'portrait', 'bricks' ),
        'portrait-primary' => esc_html__( 'portrait-primary', 'bricks' ),
        'portrait-secondary' => esc_html__( 'portrait-secondary', 'bricks' )
			],
			'placeholder' => esc_html__( 'Landscape', 'bricks' ),
			'inline'      => true,
			'small' => true,
      'required' => ['provider', '!=', 'audio']
		];
    */


    $this->controls['controlsDelay'] = [
      'group' => 'behaviour',
			'label'    => esc_html__( 'Controls hiding delay (ms)', 'bricks' ),
			'type'        => 'number',
			'placeholder' => '2000',
			'inline'      => true,
			'small' => true,
      'required' => ['provider', '!=', 'audio']
		];

  
    $this->controls['pauseOutOfView'] = [
      'group' => 'behaviour',
			'label'    => esc_html__( 'Pause if scroll out of view', 'bricks' ),
			'type'        => 'select',
			'options'     => [
				'enable' => esc_html__( 'Enable', 'bricks' ),
				'disable'  => esc_html__( 'Disable', 'bricks' ),
			],
			'placeholder' => esc_html__( 'Disable', 'bricks' ),
			'inline'      => true,
			'small' => true,
      'rerender' => false,
		];


    $this->controls['posterReshow'] = [
      'group' => 'behaviour',
			'label'    => esc_html__( 'Reshow poster image on pause', 'bricks' ),
			'type'        => 'select',
			'options'     => [
				'enable' => esc_html__( 'Enable', 'bricks' ),
				'disable'  => esc_html__( 'Disable', 'bricks' ),
			],
			'placeholder' => esc_html__( 'Disable', 'bricks' ),
			'inline'      => true,
			'small' => true,
      'rerender' => false,
		];

    
    
    $this->controls['uiType'] = [
      'group' => 'behaviour',
      'label'    => esc_html__( 'UI Type', 'bricks' ),
      'type'        => 'select',
      'options'     => [
          //'standard' => esc_html__( 'Standard Layout', 'bricks' ),
          'default' => esc_html__( 'Standard (recommended)', 'bricks' ),
          'custom'  => esc_html__( 'Custom (advanced)', 'bricks' ),
      ],
      'placeholder' => esc_html__( 'Standard (recommended)', 'bricks' ),
      'inline'      => true,
      'small' => true,
   ];


    /* loading */

    

    $this->controls['loadingType'] = [
      'group' => 'loading',
      'label'    => esc_html__( 'Loading type', 'bricks' ),
      'type'        => 'select',
      'options'     => [
          'eager' => esc_html__( 'Eager', 'bricks' ),
          //'idle'  => esc_html__( 'Idle', 'bricks' ),
          'visible'  => esc_html__( 'When visible (lazy)', 'bricks' ),
          'play' => esc_html__( 'On play', 'bricks' ),
          'custom' => esc_html__( 'On click', 'bricks' ),
      ],
      'placeholder' => esc_html__( 'When visible', 'bricks' ),
      'inline'      => true,
      'small' => true,
   ];

   $this->controls['autoLocalPoster'] = [
    'label'    => esc_html__( 'Auto local poster image', 'bricks' ),
    'type'        => 'select',
    'options'     => [
      'enable' => esc_html__( 'Enable', 'bricks' ),
      'disable'  => esc_html__( 'Disable', 'bricks' ),
    ],
    'placeholder' => esc_html__( 'Disable', 'bricks' ),
    'inline'      => true,
    'group' => 'loading',
    'info' => esc_html__( 'Save Youtube/Vimeo poster images locally', 'bricks' ),
    //'required' => ['loadingType', '=', ['custom','play']]
  ];

    $this->controls['gestures'] = [
      'tab' => 'content',
      'label' => esc_html__( 'Gestures', 'bricks' ),
      'type' => 'separator',
      'group' => 'behaviour',
    ];

    $this->controls['clickToPlay'] = [
      'label'    => esc_html__( 'Click to play', 'bricks' ),
      'type'        => 'select',
      'options'     => [
        'enable' => esc_html__( 'Enable', 'bricks' ),
        'disable'  => esc_html__( 'Disable', 'bricks' ),
      ],
      'placeholder' => esc_html__( 'Disable', 'bricks' ),
      'inline'      => true,
      'group' => 'behaviour',
    ];

    $this->controls['doubleClickToFullScreen'] = [
      'label'    => esc_html__( 'Double click for fullscreen', 'bricks' ),
      'type'        => 'select',
      'options'     => [
        'enable' => esc_html__( 'Enable', 'bricks' ),
        'disable'  => esc_html__( 'Disable', 'bricks' ),
      ],
      'placeholder' => esc_html__( 'Disable', 'bricks' ),
      'inline'      => true,
      'group' => 'behaviour',
    ];



    /* playlist */

    $this->controls['playListSep'] = [
      'tab' => 'content',
      'label' => esc_html__( 'Playlists', 'bricks' ),
      'description' => esc_html__( 'Get media source dynamically from active playlist element', 'bricks' ),
      'type' => 'separator',
      'group' => 'playLists',
    ];

    $this->controls['playlistMode'] = [
      'tab' => 'content',
      'label' => esc_html__( 'Playlist mode', 'bricks' ),
      'inline'  => true,
      'type' => 'checkbox',
      'group' => 'playLists',
      
    ];

    
     $this->controls['playListNext'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Move to next in playlist when reaches end', 'bricks' ),
        'inline'  => true,
        'type'        => 'select',
        'options'     => [
            'enable' => esc_html__( 'Enable', 'bricks' ),
            'disable'  => esc_html__( 'Disable', 'bricks' ),
        ],
        'group' => 'playLists',
        'required' => ['playlistMode', '=', true],
        'placeholder' => esc_html__( 'Disable', 'bricks' ),
    ];

    
    $this->controls['playlistPlayOnClick'] = [
      'tab' => 'content',
      'label' => esc_html__( 'Play media after clicking', 'bricks' ),
      'inline'  => true,
      'type'        => 'select',
        'options'     => [
            'enable' => esc_html__( 'Enable', 'bricks' ),
            'disable'  => esc_html__( 'Disable', 'bricks' ),
        ],
      'group' => 'playLists',
      'placeholder' => esc_html__( 'Enable', 'bricks' ),
      'required' => ['playlistMode', '=', true]
    ];

    
    $this->controls['playListLoop'] = [
      'tab' => 'content',
      'label' => esc_html__( 'Loop back to first in playlist when reaches end', 'bricks' ),
      'inline'  => true,
      'type'        => 'select',
        'options'     => [
            'enable' => esc_html__( 'Enable', 'bricks' ),
            'disable'  => esc_html__( 'Disable', 'bricks' ),
        ],
        'placeholder' => esc_html__( 'Disable', 'bricks' ),
      'group' => 'playLists',
      'required' => ['playlistMode', '=', true]
    ];


    /* poster image */

    $this->controls['image'] = [
      'tab' => 'content',
      'label' => esc_html__( 'Poster Image', 'bricks' ),
      'type' => 'image',
      'group' => 'poster'
    ];

    $this->controls['altText'] = [
      'tab'      => 'content',
      'label'    => esc_html__( 'Custom alt text', 'bricks' ),
      'type'     => 'text',
      'inline'   => true,
      'rerender' => false,
      'group' => 'poster',
      'required' => [ 'image', '!=', '' ],
  ];

    $this->controls['objectFit'] = [
      'tab'         => 'content',
      'label'       => esc_html__( 'Object fit', 'bricks' ),
      'group' => 'poster',
      'type'        => 'select',
      'options'     => [
        'fill'       => esc_html__( 'Fill', 'bricks' ),
        'contain'    => esc_html__( 'Contain', 'bricks' ),
        'cover'      => esc_html__( 'Cover', 'bricks' ),
        'none'       => esc_html__( 'None', 'bricks' ),
        'scale-down' => esc_html__( 'Scale down', 'bricks' ),
        'fill'       => esc_html__( 'Fill', 'bricks' ),
      ],
      'css'         => [
        [
          'property' => 'object-fit',
          'selector' => '.vds-poster img',
        ],
      ],
      'inline'      => true,
      'placeholder' => esc_html__( 'Cover', 'bricks' ),
    ];

  }

  // Methods: Frontend-specific
  public function enqueue_scripts() {

    wp_enqueue_script( 'x-media-player',  BRICKSEXTRAS_URL . 'components/assets/vidstack/dist/assets/mediaplayer.js', '', '1.8.1', true );
    wp_enqueue_script( 'x-media-player-init',  BRICKSEXTRAS_URL . 'components/assets/js/mediaplayer.js', '', '1.0.0', true );

    if (! \BricksExtras\Helpers::elementCSSAdded($this->name) ) {
      wp_enqueue_style( 'x-media-player', BRICKSEXTRAS_URL . 'components/assets/css/mediaplayer.css', [], '' );
    }

    wp_localize_script(
      'x-media-player',
      'xMediaPlayer', 
      [
          'Instances' => [],
          'TextTracks' => [],
          'vidstackDir' 	=> 'wp-content/plugins/BricksExtras/components/assets/vidstack/dist/',
          'pluginDir' => BRICKSEXTRAS_URL . 'components/assets/js/'
      ]
  );

  

    wp_localize_script(
      'x-media-player-init',
      'xMediaPlayer', 
      [
          'Instances' => [],
          'vidstackDir' 	=> 'wp-content/plugins/BricksExtras/components/assets/vidstack/dist/',
          'pluginDir' => BRICKSEXTRAS_URL . 'components/assets/js/',
          'ajaxurl' => admin_url( 'admin-ajax.php' ),
      ]
  );

    add_filter('script_loader_tag', array( $this, 'vidstack_module') , 10, 3);

  }

    function vidstack_module($tag, $handle, $src) {
      if ( 'x-media-player' !== $handle ) { return $tag; }
     
      $tag = '<script type="module" src="' . esc_url( $src ) . '"></script>';

      return $tag;
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

    $provider = isset( $settings['provider'] ) ? $settings['provider'] : 'video';
    $defaultSrc = 'audio' === $provider ? 'https://media-files.vidstack.io/sprite-fight/audio.mp3' : 'https://media-files.vidstack.io/720p.mp4';

    $srcSetting = isset( $settings['src'] ) ? esc_url( $settings['src'] ) : $defaultSrc;

    $src = strstr( $srcSetting, '{') ? $this->render_dynamic_data_tag( $srcSetting, 'text' ) : $srcSetting;

    $positioning = isset( $settings['positioning'] ) ? $settings['positioning'] : 'default';
    $altText = isset( $settings['altText'] ) ? esc_attr( $settings['altText'] ) : 'video cover image';
    $layout = isset( $settings['layout'] ) ? $settings['layout'] : 'default';
    $playsinline = isset( $settings['playsinline'] ) ? 'enable' === $settings['playsinline'] : true;
    $controlsDelay = isset( $settings['controlsDelay'] ) ? intval($settings['controlsDelay']) : 2000;
    $loop = isset( $settings['loop'] ) ? 'enable' === $settings['loop'] : false;
    $muted = isset( $settings['muted'] ) ? 'enable' === $settings['muted'] : false;
    $autoplay = isset( $settings['autoplay'] ) ? 'enable' === $settings['autoplay'] : false;
    $fullscreenOrientation = isset( $settings['fullscreenOrientation'] ) ? $settings['fullscreenOrientation'] : 'landscape';
    $hideNested = isset( $settings['hideNested'] ) ? $settings['hideNested'] : 'firstplay';
    $uiType = isset( $settings['uiType'] ) ? $settings['uiType'] : 'default';
    $loadingType = isset( $settings['loadingType'] ) ? $settings['loadingType'] : 'visible';
    $title = isset( $settings['title'] ) ? $settings['title'] : '';
    $maybeToolTips = isset( $settings['maybeToolTips'] ) ? 'enable' === $settings['maybeToolTips'] : true;
    $defaultTooltipPlacement = isset( $settings['defaultTooltipPlacement'] ) ? $settings['defaultTooltipPlacement'] : 'top';

    $clipStart = isset( $settings['clipStartTime'] ) ? $settings['clipStartTime'] : false;
    $clipEnd = isset( $settings['clipEndTime'] ) ? $settings['clipEndTime'] : false;

    $clipStartTime =  $clipStart ? \BricksExtras\Helpers::timeToSeconds( $this->render_dynamic_data( $clipStart ) ) : '0';
	  $clipEndTime =  $clipEnd ? \BricksExtras\Helpers::timeToSeconds( $this->render_dynamic_data( $clipEnd ) ) : '0';

    $textTracks = ! empty( $settings['textTracks'] ) ? $settings['textTracks'] : false;

    if ( BricksExtras\Helpers::maybePreview() ) {
      $loadingType = 'visible';
    }

    $image  = $this->get_normalized_image_settings( $this->settings );
    $image_id   = $image['id'];
    $image_url  = $image['url'];
    $image_size = $image['size'];

    $posterURL = $image_url ? $image_url : '';

    if ( isset( $settings['playlistMode'] ) ) {
      $posterURL = '';
    }

    $controls = ! empty( $this->settings['controls'] ) ? $this->settings['controls'] : false;
    $controlsTop = ! empty( $this->settings['controlsTop'] ) ? $this->settings['controlsTop'] : false;
    $controlsCenter = ! empty( $this->settings['controlsCenter'] ) ? $this->settings['controlsCenter'] : false;
    $smallcontrolsCenter = ! empty( $this->settings['smallcontrolsCenter'] ) ? $this->settings['smallcontrolsCenter'] : false;
    $smallControls = ! empty( $this->settings['smallControls'] ) ? $this->settings['smallControls'] : false;
    $smallControlsTop = ! empty( $this->settings['smallControlsTop'] ) ? $this->settings['smallControlsTop'] : false;

    $controlsTopAudio = ! empty( $this->settings['controlsTopAudio'] ) ? $this->settings['controlsTopAudio'] : false;
    $controlsCenterAudio = ! empty( $this->settings['controlsCenterAudio'] ) ? $this->settings['controlsCenterAudio'] : false;
    $controlsBottomAudio = ! empty( $this->settings['controlsBottomAudio'] ) ? $this->settings['controlsBottomAudio'] : false;

    $chapters = ! empty( $this->settings['chapters'] ) ? $this->settings['chapters'] : false;
    $chapterText = isset( $settings['chapterText'] ) ? esc_attr( $settings['chapterText'] ) : '';
    $chapterStart = isset( $settings['chapterStart'] ) ? esc_attr( $settings['chapterStart'] ) : '';
    $chapterEnd = isset( $settings['chapterEnd'] ) ? esc_attr( $settings['chapterEnd'] ) : '';

    $thumbnailAttr = isset( $settings['thumbnailFile'] ) ? 'src="' . esc_attr( $settings['thumbnailFile'] ) . '"' : '';

    $queryType = isset( $settings['queryType'] ) ? $settings['queryType'] : 'container';
    $smallControlsBreakpoint = isset( $settings['smallControlsBreakpoint'] ) ? intval( $settings['smallControlsBreakpoint'] ) : 478;

    $maybeTimeSlider = isset( $settings['maybeTimeSlider'] ) ? 'enable' === $this->settings['maybeTimeSlider'] : true;
    $maybeTimeSliderSmall = isset( $settings['maybeTimeSliderSmall'] ) ? 'enable' === $this->settings['maybeTimeSliderSmall'] : true;
    
    $maybeNotice = isset( $settings['maybeNotice'] ) ? 'enable' === $this->settings['maybeNotice'] : false;
    $noticeText = ! empty( $this->settings['noticeText'] ) ? esc_html( $this->settings['noticeText'] ) : 'Notice: Your message here';
    $showNotice = isset( $settings['showNotice'] ) ? $this->settings['showNotice'] : 'first';
    $posterReshow = isset( $settings['posterReshow'] ) ? 'enable' === $this->settings['posterReshow'] : false;

    $autoLocalPoster = isset( $settings['autoLocalPoster'] ) ? 'enable' === $this->settings['autoLocalPoster'] : false;

    $clickToPlay = isset( $settings['clickToPlay'] ) ? 'enable' === $this->settings['clickToPlay'] : false;
    $doubleClickToFullScreen = isset( $settings['doubleClickToFullScreen'] ) ? 'enable' === $this->settings['doubleClickToFullScreen'] : false;

    $config = [
      'load' =>  $loadingType,
      'pauseOutOfView' => isset( $settings['pauseOutOfView'] ) ? 'enable' === $this->settings['pauseOutOfView'] : false,
      'autoLocalPoster' => $autoLocalPoster,
      'queryType' => $queryType
    ];

    if ($posterReshow) {
      $config += [
        'reshowPoster' => 'true',
      ];
    }

    if ( isset( $settings['playlistMode'] ) ) {
      $config += [
        'playlist' => isset( $settings['playlistMode'] ),
        'playListLoop' => isset( $settings['playListLoop'] ) ? 'enable' === $settings['playListLoop'] : false,
        'playlistPlayOnClick' => isset( $settings['playlistPlayOnClick'] ) ? 'enable' === $settings['playlistPlayOnClick'] : true,
        'playListNext' => isset( $settings['playListNext'] ) ? 'enable' === $settings['playListNext'] : false,
      ];
    }

    if ( 'container' === $queryType ) {
      $config += [
        'breakpoint' => $smallControlsBreakpoint,
      ];
    }

    $playerIndentifier = $this->id;
    
    $loopIndex = false;

		if ( method_exists('\Bricks\Query','is_any_looping') ) {

			$query_id = \Bricks\Query::is_any_looping();
	
			if ( $query_id ) {

        $config += [ 'isLooping' => \Bricks\Query::get_query_element_id( $query_id ) ];
				
				if ( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(2) ) {
					$loopIndex = \Bricks\Query::get_query_for_element_id( \Bricks\Query::get_query_element_id( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(2) ) )->loop_index . '_' . \Bricks\Query::get_query_for_element_id( \Bricks\Query::get_query_element_id( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(1) ) )->loop_index . '_' . \Bricks\Query::get_loop_index();
				} else {
					if ( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(1) ) {
						$loopIndex = \Bricks\Query::get_query_for_element_id( \Bricks\Query::get_query_element_id( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(1) ) )->loop_index . '_' . \Bricks\Query::get_loop_index();
					} else {
						$loopIndex = \Bricks\Query::get_loop_index();
					}
				}			
	
        $playerIndentifier = $this->id . '_' . $loopIndex;
				
			} 
	
		} 

    $this->set_attribute( '_root', 'data-x-id', $playerIndentifier );


    if ($posterURL && !isset( $settings['playlistMode'] ) ) {
      $config += [ 'poster' => $posterURL ];
    }

    if ($clipStartTime) {
      $this->set_attribute( '_root', 'clip-start-time', $clipStartTime );
    }

    if ($clipEndTime) {
      $this->set_attribute( '_root', 'clip-end-time', $clipEndTime );
    }

    //$this->set_attribute( '_root', 'posterLoad', 'custom');
    $this->set_attribute( '_root', 'data-x-view-type', $provider );
    $this->set_attribute( '_root', 'data-view-type', $provider );
    $this->set_attribute( '_root', 'data-x-media-player', wp_json_encode( $config ) );

    if ('bottom' === $positioning && 'audio' === $provider) {
      $this->set_attribute( '_root', 'data-x-positioning', 'bottom' );
    }


    $chaptersArray = [];

    

    /* chapters from dynamic data */

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


    /* media-player */
    $this->set_attribute( '_root', 'title', $title );
    $this->set_attribute( '_root', 'src', $src );
    $this->set_attribute( 'x-media-player_inner', 'class', 'x-media-player_inner' ); 
    

    /* props */
    $this->set_attribute( '_root', 'load', $loadingType );
    $this->set_attribute( '_root', 'controls-delay', $controlsDelay );
    
    
    if ( $loop ) {  $this->set_attribute( '_root', 'loop', $loop ); }

    if ( $muted ) {
      $this->set_attribute( '_root', 'muted', $muted );
      if ($autoplay) {
        $this->set_attribute( '_root', 'autoPlay', $autoplay );
      }
    }

    if ( $playsinline ) { $this->set_attribute( '_root', 'playsInline', $playsinline ); }

   
    

    /* media-poster */
    $this->set_attribute( 'media-poster', 'class', 'vds-poster' );
    $this->set_attribute( 'media-poster', 'alt', $altText );
   // $this->set_attribute( 'media-poster', 'src', '' );

    /* media-controls-group */
    $this->set_attribute( 'media-controls-group', 'class', 'vds-controls-group' );
    $this->set_attribute( 'media-controls', 'class', 'vds-controls' );


    /* find if poster image has been saved, load from server if so */

    if ( !BricksExtras\Helpers::maybePreview() ) {

      $this->set_attribute( '_root', 'poster-load', 'custom' );

      if ( $autoLocalPoster && get_option( 'bricksextras_media_poster_url') ) {

        $savedPosterImages = json_decode( get_option( 'bricksextras_media_poster_url'), TRUE);
        $videoID = \BricksExtras\Helpers::get_video_id( $src );

        if ($savedPosterImages) {

          if ( array_key_exists($videoID, $savedPosterImages) ) {
            $this->set_attribute( '_root', 'data-x-local-poster', $savedPosterImages[$videoID] );
          }

        }

      } 

    } else {
      if ( $posterURL ) {
        $this->set_attribute( '_root', 'poster-load', 'custom' );
      } else {
        $this->set_attribute( '_root', 'poster-load', 'eager' );
      }
    }


    $output = '';

    $output .= "<media-player {$this->render_attributes( '_root' )}>";
    
    $output .= "<media-provider>";

    
    if ('video' === $provider) {

        if ( $posterURL ) {

          if ( 'default' !== $uiType && BricksExtras\Helpers::maybePreview() ) {
              /* in builder */
              $output .= "<media-poster {$this->render_attributes( 'media-poster' )}>";
              $output .= "<img src='" . $posterURL . "'>";
              $output .= "</media-poster>";
          } else {
            $output .= "<media-poster {$this->render_attributes( 'media-poster' )}></media-poster>";
          }
  
          
        } else {
          $output .= "<media-poster alt='" . $altText . "' class='vds-poster'></media-poster>";
        }

      }

			if ( $textTracks ) {		

        $subtitleFirst = false;
        $default = false;

				foreach ( $textTracks as $index => $textTrack ) {

					$label = ! empty( $textTrack['label'] ) ? $textTrack['label'] : '';
          $src = ! empty( $textTrack['src'] ) ? $textTrack['src'] : '';
          $kind = ! empty( $textTrack['kind'] ) ? $textTrack['kind'] : 'subtitles';
          $language = ! empty( $textTrack['language'] ) ? $textTrack['language'] : 'en-US';

          if ('subtitles' === $kind) { 
            if (!$subtitleFirst) {
              $default = true;
              $subtitleFirst = true;
            } else {
              $default = false;
            }
          }

          if ('chapters' === $kind) {
            $default = true;
          }

          $defaultAttr = $default ? 'default' : '';

          $output .= "<track label='" . $label . "' src='" . $src . "' srclang='" . $language . "' kind='" . $kind . "' " . $defaultAttr . "/>";

				}

			}


    $output .= "</media-provider>";

    if ( $maybeNotice && 'custom' !== $uiType && 'audio' !== $provider ) {
      $output .= '<div data-x-visible="' . $showNotice . '" class="vds-custom-overlay"><div class="x-notice-text"><span>' . $noticeText . '</span></div></div>';
    }

    

    $output .= '<media-captions class="vds-captions"></media-captions>';

    if ('custom' !== $uiType) {

    if ('audio' !== $provider ) {

    if (0 !== $smallControlsBreakpoint) {

      $output .=  '<media-layout class="media-layout_large-video">';  
      //$output .= 'container' === $queryType && !BricksExtras\Helpers::maybePreview() ? '<template>' : '';

    }

    $playerControls = '';

    if ('default' === $uiType) {

      $playerControls .= "<media-controls  {$this->render_attributes( 'media-controls' )}>";

      if ( !!$controlsTop && ('default' === $uiType || 'audio' === $provider ) ) {

        $playerControls .= '<media-controls-group class="vds-controls-group vds-controls-group_top">';
        foreach ( $controlsTop as $control ) {
            $playerControls .= $this->renderControl( $control , $provider, $maybeToolTips, $defaultTooltipPlacement, 'top', $title, $thumbnailAttr );
        }
        $playerControls .= '</media-controls-group>';
      }



      if (!!$controlsCenter) {

         $playerControls .= 'video' === $provider ? '<div class="vds-controls-spacer"></div>' : '';

          $playerControls .= '<media-controls-group class="vds-controls-group vds-controls-group_center">';
          foreach ( $controlsCenter as $control ) {
              $playerControls .= $this->renderControl( $control , $provider, $maybeToolTips, $defaultTooltipPlacement, 'top', $title, $thumbnailAttr );
          }
          $playerControls .= '</media-controls-group>';

      }


      
      $playerControls .= 'video' === $provider ? '<div class="vds-controls-spacer"></div>' : '';

      
      

      
      if ($maybeTimeSlider) {

        $playerControls .= '<media-controls-group class="vds-controls-group vds-controls-group_time-slider">
                   <media-time-slider class="vds-time-slider vds-slider">
                        <media-slider-chapters class="vds-slider-chapters">
                          <template>
                            <div class="vds-slider-chapter">
                              <div class="vds-slider-track"></div>
                              <div class="vds-slider-track-fill vds-slider-track"></div>
                              <div class="vds-slider-progress vds-slider-track"></div>
                            </div>
                          </template>
                        </media-slider-chapters>
                        <div class="vds-slider-thumb"></div>
                        <media-slider-preview class="vds-slider-preview">
                          <media-slider-thumbnail ' . $thumbnailAttr . '
                            class="vds-slider-thumbnail vds-thumbnail"
                          ></media-slider-thumbnail>
                          <div
                            class="vds-slider-chapter-title"
                            data-part="chapter-title"
                          ></div>
                          <media-slider-value
                            class="vds-slider-value"
                          ></media-slider-value>
                        </media-slider-preview>
                    </media-time-slider>
             </media-controls-group>';

      }

      
      
      $playerControls .= '<media-controls-group class="vds-controls-group vds-controls-group_bottom">';

      if ( !!$controls && ('default' === $uiType || 'audio' === $provider ) ) {
        foreach ( $controls as $control ) {
            $playerControls .= $this->renderControl( $control , $provider, $maybeToolTips, $defaultTooltipPlacement, 'bottom', $title, $thumbnailAttr );
        }
      }

      $playerControls .= "</media-controls-group>";

      $playerControls .= "</media-controls>";

    }

    $output .= $playerControls;

    if (0 !== $smallControlsBreakpoint) {

     // $output .= 'container' === $queryType  && !BricksExtras\Helpers::maybePreview() ? "</template>" : "";
      $output .= "</media-layout>";

    }



    /* small layout */

    if ('none' !== $queryType) {

    $output .= '<media-layout class="media-layout_small-video">'; 
    //$output .= 'container' === $queryType  && !BricksExtras\Helpers::maybePreview() ? '<template>' : '';


    $playerControls = '';

    //$playerControls .= '<div class="vds-buffering-indicator"><media-spinner class="vds-buffering-spinner"></media-spinner></div>';

    if ('default' === $uiType) {

      $playerControls .= "<media-controls  {$this->render_attributes( 'media-controls' )}>";

       if ( !!$smallControlsTop && ('default' === $uiType || 'audio' === $provider ) ) {

        $playerControls .= '<media-controls-group class="vds-controls-group vds-controls-group_top">';
        foreach ( $smallControlsTop as $control ) {
            $playerControls .= $this->renderControl( $control , $provider, $maybeToolTips, $defaultTooltipPlacement, 'top', $title, $thumbnailAttr );
        }
        $playerControls .= '</media-controls-group>';
      }


      if (!!$smallcontrolsCenter) {

        $playerControls .= 'video' === $provider ? '<div class="vds-controls-spacer"></div>' : '';

         $playerControls .= '<media-controls-group class="vds-controls-group vds-controls-group_center">';
         foreach ( $smallcontrolsCenter as $control ) {
             $playerControls .= $this->renderControl( $control , $provider, $maybeToolTips, $defaultTooltipPlacement, 'top', $title, $thumbnailAttr );
         }
         $playerControls .= '</media-controls-group>';

     }

    
      $playerControls .= 'video' === $provider ? '<div class="vds-controls-spacer"></div>' : '';

      
      
      $playerControls .= "<media-controls-group {$this->render_attributes( 'media-controls-group' )}>";

      if ( !!$smallControls && ('default' === $uiType || 'audio' === $provider ) ) {
        foreach ( $smallControls as $control ) {
            $playerControls .= $this->renderControl( $control , $provider, $maybeToolTips, $defaultTooltipPlacement, 'bottom', $title, $thumbnailAttr );
        }
      }

      

      $playerControls .= "</media-controls-group>";

  
      if ($maybeTimeSliderSmall) {

        $playerControls .= '<media-controls-group class="vds-controls-group">
                   <media-time-slider class="vds-time-slider vds-slider">
                        <media-slider-chapters class="vds-slider-chapters">
                          <template>
                            <div class="vds-slider-chapter">
                              <div class="vds-slider-track"></div>
                              <div class="vds-slider-track-fill vds-slider-track"></div>
                              <div class="vds-slider-progress vds-slider-track"></div>
                            </div>
                          </template>
                        </media-slider-chapters>
                        <div class="vds-slider-thumb"></div>
                        <media-slider-preview class="vds-slider-preview">
                        <media-slider-thumbnail ' . $thumbnailAttr . '
                          class="vds-slider-thumbnail vds-thumbnail"
                        ></media-slider-thumbnail>
                        <div
                          class="vds-slider-chapter-title"
                          data-part="chapter-title"
                        ></div>
                        <media-slider-value
                          class="vds-slider-value"
                        ></media-slider-value>
                      </media-slider-preview>
                    </media-time-slider>
             </media-controls-group>';

      }

      $playerControls .= "</media-controls>";

    }

    $output .= $playerControls;

    //$output .=  'container' === $queryType  && !BricksExtras\Helpers::maybePreview() ? "</template>" : "";
    $output .= "</media-layout>";

  }
  } 


    /* audio layout */

    if ('audio' === $provider ) {

    $output .= '<media-layout class="x-media-player_audio">';
   // $output .= '<template>';


    $playerControlsAudio = '';

    if ('default' === $uiType) {

      $playerControlsAudio .= "<media-controls  {$this->render_attributes( 'media-controls' )}>";


      if ( !!$controlsTopAudio && ('default' === $uiType || 'audio' === $provider ) ) {

        $playerControlsAudio .= '<media-controls-group class="vds-controls-group vds-controls-group_top">';
        foreach ( $controlsTopAudio as $control ) {
            $playerControlsAudio .= $this->renderControl( $control , $provider, $maybeToolTips, $defaultTooltipPlacement, 'top', $title, $thumbnailAttr );
        }
        $playerControlsAudio .= '</media-controls-group>';
      }


      if (!!$controlsCenterAudio) {

         $playerControlsAudio .= '<media-controls-group class="vds-controls-group vds-controls-group_center">';
         foreach ( $controlsCenterAudio as $control ) {
             $playerControlsAudio .= $this->renderControl( $control , $provider, $maybeToolTips, $defaultTooltipPlacement, 'top', $title, $thumbnailAttr );
         }
         $playerControlsAudio .= '</media-controls-group>';

     }
      
      $playerControlsAudio .= '<media-controls-group class="vds-controls-group vds-controls-group_bottom">';

      if ( !!$controlsBottomAudio && ('default' === $uiType || 'audio' === $provider ) ) {
        foreach ( $controlsBottomAudio as $control ) {
            $playerControlsAudio .= $this->renderControl( $control , $provider, $maybeToolTips, $defaultTooltipPlacement, 'bottom', $title, $thumbnailAttr );
        }
      }

      $playerControlsAudio .= "</media-controls-group>";

      $playerControlsAudio .= "</media-controls>";

    }

    $output .= $playerControlsAudio;

    //$output .= "</template>";
    $output .= "</media-layout>";

    }

  }

    if ( 'custom' === $uiType ) {

      $output .= "<div {$this->render_attributes( 'x-media-player_inner' )}>";

      if ( method_exists('\Bricks\Frontend','render_children') ) {
        $output .=  \Bricks\Frontend::render_children( $this );
      }

      $output .= "</div>";

    } 


    /* gestures */

    if ( $doubleClickToFullScreen ) {
      $output .= '<media-gesture event="dblpointerup" action="toggle:fullscreen"></media-gesture>';
    }

    if ( $clickToPlay ) {
      $output .= '<media-gesture event="pointerup" action="toggle:paused"></media-gesture>';
    }

    if ( BricksExtras\Helpers::maybePreview() ) {

      if ('container' === $queryType) {

        $inlinecss = "
          [data-x-id=" . $playerIndentifier . "],
          .brxe-" . $playerIndentifier . " {
            container-name: " . $playerIndentifier . ";
            container-type: inline-size;
          }

          @container " . $playerIndentifier . " (max-width: " . $smallControlsBreakpoint . "px) {
            .media-layout_large-video {
              display: none;
            }
          }
          
          @container " . $playerIndentifier . " (min-width: " . ( $smallControlsBreakpoint + 1 ) . "px) {
            .media-layout_small-video {
              display: none;
            }
          }

        ";

      } 

      if ( method_exists( '\Bricks\Helpers', 'file_get_contents' ) ) {
        $inlinecss = \Bricks\Assets::minify_css( $inlinecss );
      } 

      $output .= '<style type="text/css">' . $inlinecss . '</style>';

    }


    $output .= "</media-player>";
    
    echo $output;
    //echo $default;
    
  }

  
	public function renderControl($control,$provider,$maybeToolTips, $defaultTooltipPlacement, $position, $title, $thumbnailAttr) {

    $output = '';
    $button = '';
    $tooltip = '';
    $isToolTip = true;
    $spacer = false;
    $volumeSlider = 'disable';

    $inactiveIcon = empty( $control['inactiveIcon'] ) ? false : self::render_icon( $control['inactiveIcon'] );
    $activeIcon = empty( $control['activeIcon'] ) ? false : self::render_icon( $control['activeIcon'] );

    switch ( $control['control'] ) {

      case 'spacer':

        $spacer = '<div class="vds-controls-spacer"></div>';

        break;

      case 'play':

        $playLabel = !empty( $control['playLabel'] ) ? esc_attr__( $control['playLabel'] ) : esc_attr__( 'Play' );
        $pauseLabel = !empty( $control['pauseLabel'] ) ? esc_attr__( $control['pauseLabel'] ) : esc_attr__( 'Pause' );
        $visibility = isset( $control['visibility'] ) ? esc_attr( $control['visibility'] ) : "default";
        
        $button .= '<media-play-button class="vds-button" data-x-control-visibility="' . $visibility . '">';
        $button .= $inactiveIcon ? '<div class="vds-play-icon vds-icon vds-icon-custom vds-icon-custom-inactive">'  . $inactiveIcon . '</div>' : '<media-icon class="vds-play-icon" type="play"></media-icon>';
        $button .= $activeIcon ? '<div class="vds-pause-icon vds-icon vds-icon-custom vds-icon-custom-active">' . $activeIcon . '</div>' : '<media-icon class="vds-pause-icon" type="pause"></media-icon>';
        $button .= ' </media-play-button>';

        $tooltip .= '<span class="vds-play-tooltip-text">' . $playLabel . '</span>';
        $tooltip .= '<span class="vds-pause-tooltip-text">' . $pauseLabel . '</span>';

        break;

      case 'play-large':

        $playLabel = !empty( $control['playLabel'] ) ? esc_attr( $control['playLabel'] ) : esc_attr__( 'Play' );
        $pauseLabel = !empty( $control['pauseLabel'] ) ? esc_attr( $control['pauseLabel'] ) : esc_attr__( 'Pause' );
        $visibility = isset( $control['visibility'] ) ? esc_attr( $control['visibility'] ) : "default";
        
        $button .= '<media-play-button class="vds-button vds-button-large"  data-x-control-visibility="' . $visibility . '">';
        $button .= $inactiveIcon ? '<div class="vds-play-icon vds-icon vds-icon-custom vds-icon-custom-inactive">'  . $inactiveIcon . '</div>' : '<media-icon class="vds-play-icon" type="play"></media-icon>';
        $button .= $activeIcon ? '<div class="vds-pause-icon vds-icon vds-icon-custom vds-icon-custom-active">' . $activeIcon . '</div>' : '<media-icon class="vds-pause-icon" type="pause"></media-icon>';
        $button .= ' </media-play-button>';

        $tooltip .= '<span class="vds-play-tooltip-text">' . $playLabel . '</span>';
        $tooltip .= '<span class="vds-pause-tooltip-text">' . $pauseLabel . '</span>';

        break;

      case 'seek-forward':

        $seekAmountForward = !empty( $control['seekAmountForward'] ) ? esc_attr( $control['seekAmountForward'] ) : '+10';
        $seekLabelForward = !empty( $control['seekLabelForward'] ) ? esc_attr__( $control['seekLabelForward'] ) : esc_attr__('Forward');
        $visibility = isset( $control['visibility'] ) ? esc_attr( $control['visibility'] ) : "default";

        switch ( $seekAmountForward ) {

          case '+10':
            $icon = 'seek-forward-10';
            break;

          case '+15':
            $icon = 'seek-forward-15';
            break;

          case '+30': 
            $icon = 'seek-forward-30';
            break;

        }

        $button .= '<media-seek-button class="vds-button" seconds="' . $seekAmountForward .'" data-x-control-visibility="' . $visibility . '">';
        $button .= $inactiveIcon ? '<div class="vds-icon vds-icon-custom vds-icon-custom-inactive">'  . $inactiveIcon . '</div>' : '<media-icon type="' . $icon . '"></media-icon>';
        $button .= '</media-seek-button>';

        $tooltip .= '<span class="vds-seek-forward-tooltip-text">' . $seekLabelForward .'</span>';

        break;

      case 'seek-backward':

        $seekAmountBack = !empty( $control['seekAmountBack'] ) ? esc_attr( $control['seekAmountBack'] ) : '-10';
        $seekLabelBack = !empty( $control['seekLabelBack'] ) ? esc_attr__( $control['seekLabelBack'] ) : esc_attr__('Rewind');
        $visibility = isset( $control['visibility'] ) ? esc_attr( $control['visibility'] ) : "default";

        switch ( $seekAmountBack ) {

          case '-10':
            $icon = 'seek-backward-10';
            break;

          case '-15':
            $icon = 'seek-backward-15';
            break;

          case '-30': 
            $icon = 'seek-backward-30';
            break;

        }

        $button .= '<media-seek-button class="vds-button" seconds="' . $seekAmountBack . '" data-x-control-visibility="' . $visibility . '">';
        $button .= $inactiveIcon ? '<div class="vds-icon vds-icon-custom vds-icon-custom-inactive">'  . $inactiveIcon . '</div>' : '<media-icon type="' . $icon . '"></media-icon>';
        $button .= '</media-seek-button>';

        $tooltip .= '<span class="vds-seek-backward-tooltip-text">' . $seekLabelBack . '</span>';

        break;

      case 'mute':

        $unmuteLabel = !empty( $control['unmuteLabel'] ) ? esc_attr__( $control['unmuteLabel'] ) : esc_attr__('Unmute');
        $muteLabel = !empty( $control['muteLabel'] ) ? esc_attr__( $control['muteLabel'] ) : esc_attr__('Mute');
        $volumeSlider = isset( $control['volumeSlider'] ) ? esc_attr( $control['volumeSlider'] ) : 'focus';
        $visibility = isset( $control['visibility'] ) ? esc_attr( $control['visibility'] ) : "default";

        $muteIcon = empty( $control['muteIcon'] ) ? false : self::render_icon( $control['muteIcon'] );
        $highVolumeIcon = empty( $control['highVolumeIcon'] ) ? false : self::render_icon( $control['highVolumeIcon'] );
        $lowVolumeIcon = empty( $control['lowVolumeIcon'] ) ? false : self::render_icon( $control['lowVolumeIcon'] );

        $button .= '<media-mute-button class="vds-button" data-x-control-visibility="' . $visibility . '">';

        $button .= $muteIcon ? '<div class="vds-mute-icon vds-icon vds-icon-custom">'  . $muteIcon . '</div>' : '<media-icon class="vds-mute-icon" aria-label="Mute" type="mute"></media-icon>';
        $button .= $highVolumeIcon ? '<div class="vds-volume-high-icon vds-icon vds-icon-custom vds-icon-custom-inactive">'  . $highVolumeIcon . '</div>' : '<media-icon class="vds-volume-high-icon" type="volume-high"></media-icon>';
        $button .= $lowVolumeIcon ? '<div class="vds-volume-low-icon vds-icon vds-icon-custom vds-icon-custom-active">' . $lowVolumeIcon . '</div>' : '<media-icon class="vds-volume-low-icon" type="volume-low"></media-icon>';

        $button .= '</media-mute-button>';

        $tooltip .= '<span class="vds-mute-tooltip-text">' . $unmuteLabel . '</span>';
        $tooltip .= '<span class="vds-unmute-tooltip-text">' . $muteLabel .'</span>';

        break;


      case 'time':

        $isToolTip = false;

        $currentTime = isset( $control['currentTime'] ) ? $control['currentTime'] : 'enable';
        $duration = isset( $control['duration'] ) ? 'enable' === $control['duration'] : true;
        $timeDivider = isset( $control['timeDivider'] ) ? 'enable' === $control['timeDivider'] : true;
        $timeDividerText = isset( $control['timeDividerText'] ) ? esc_attr( $control['timeDividerText'] ) : "/";
        $visibility = isset( $control['visibility'] ) ? esc_attr( $control['visibility'] ) : "default";

        $remainder = 'remainder' === $currentTime ? 'remainder' : '';

        $button .= '<div class="vds-time-group" data-x-control-visibility="' . $visibility . '">';
        $button .= 'enable' === $currentTime || 'remainder' === $currentTime ? '<media-time class="vds-time" ' . $remainder . ' type="current" data-type="current">0:00</media-time>' : '';
        $button .= $timeDivider ? '<div class="vds-time-divider">' . $timeDividerText . '</div>' : '';
        $button .= $duration ? '<media-time class="vds-time" type="duration" data-type="duration"></media-time>' : '';
        $button .= '</div>';

        break;

      case 'pip':

        $enterPipLabel = isset( $control['enterPipLabel'] ) ? esc_attr__( $control['enterPipLabel'] ) : esc_attr__("Enter PIP");
        $exitPipLabel = isset( $control['exitPipLabel'] ) ? esc_attr__( $control['exitPipLabel'] ) : esc_attr__("Exit PIP");
        $visibility = isset( $control['visibility'] ) ? esc_attr( $control['visibility'] ) : "default";

        $button .= '<media-pip-button class="vds-button" data-x-control-visibility="' . $visibility . '">';
        $button .= $inactiveIcon ? '<div class="vds-pip-enter-icon vds-icon vds-icon-custom vds-icon-custom-inactive">'  . $inactiveIcon . '</div>' : '<media-icon class="vds-pip-enter-icon" type="picture-in-picture"></media-icon>';
        $button .= $activeIcon ? '<div class="vds-pip-exit-icon vds-icon vds-icon-custom vds-icon-custom-active">' . $activeIcon . '</div>' : '<media-icon class="vds-pip-exit-icon" type="picture-in-picture-exit"></media-icon>';
        $button .= '</media-pip-button>';

        $tooltip .= '<span class="vds-pip-enter-tooltip-text">' . $enterPipLabel . '</span>';
        $tooltip .= '<span class="vds-pip-exit-tooltip-text">' . $exitPipLabel . '</span>';

        break;

      case 'fullscreen':

        if ('audio' === $provider) {
          return;
        }

        $enterFullscreenLabel = isset( $control['enterFullscreenLabel'] ) ? esc_attr__( $control['enterFullscreenLabel'] ) : esc_attr__("Enter Fullscreen");
        $exitFullscreenLabel = isset( $control['exitFullscreenLabel'] ) ? esc_attr__( $control['exitFullscreenLabel'] ) : esc_attr__("Exit Fullscreen");
        $visibility = isset( $control['visibility'] ) ? esc_attr( $control['visibility'] ) : "default";

        $button .= '<media-fullscreen-button aria-label="full screen button" class="vds-button" data-x-control-visibility="' . $visibility . '">';
        $button .= $inactiveIcon ? '<div class="vds-fs-enter-icon vds-icon vds-icon-custom vds-icon-custom-inactive">'  . $inactiveIcon . '</div>' : '<media-icon class="vds-fs-enter-icon" type="fullscreen"></media-icon>';
        $button .= $activeIcon ? '<div class="vds-fs-exit-icon vds-icon vds-icon-custom vds-icon-custom-active">' . $activeIcon . '</div>' : '<media-icon class="vds-fs-exit-icon" type="fullscreen-exit"></media-icon>';
        $button .= ' </media-fullscreen-button>';

        $tooltip .= '<span class="vds-fs-enter-tooltip-text">' . $enterFullscreenLabel . '</span>';
        $tooltip .= '<span class="vds-fs-exit-tooltip-text">' . $exitFullscreenLabel . '</span>';

        break;

      case 'captions':

        $visibility = isset( $control['visibility'] ) ? esc_attr( $control['visibility'] ) : "default";

        $captionsOnLabel = isset( $control['captionsOnLabel'] ) ? esc_attr__( $control['captionsOnLabel'] ) : esc_attr__("Closed-Captions On");
        $captionsOffLabel = isset( $control['captionsOffLabel'] ) ? esc_attr__( $control['captionsOffLabel'] ) : esc_attr__("Closed-Captions Off");

        $button .= '<media-caption-button class="vds-button" data-x-control-visibility="' . $visibility . '">';
        $button .= $inactiveIcon ? '<div class="vds-cc-on-icon vds-icon vds-icon-custom vds-icon-custom-inactive">'  . $inactiveIcon . '</div>' : '<media-icon class="vds-cc-on-icon" type="closed-captions-on"></media-icon>';
        $button .= $activeIcon ? '<div class="vds-cc-off-icon vds-icon vds-icon-custom vds-icon-custom-active">' . $activeIcon . '</div>' : '<media-icon class="vds-cc-off-icon" type="closed-captions"></media-icon>';
        $button .= '</media-caption-button>';

        $tooltip .= '<span class="vds-cc-on-tooltip-text">' . $captionsOnLabel  . '</span>';
        $tooltip .= '<span class="vds-cc-off-tooltip-text">' . $captionsOffLabel  . '</span>';

        break;

      case 'title':

        $isToolTip = false;
        $visibility = isset( $control['visibility'] ) ? esc_attr( $control['visibility'] ) : "default";

        $button .= '<media-title class="vds-title" data-x-control-visibility="' . $visibility . '"></media-title>';

        break;

      case 'chapter-title':

        $isToolTip = false;
        $visibility = isset( $control['visibility'] ) ? esc_attr( $control['visibility'] ) : "default";

        $button .= '<media-chapter-title class="vds-chapter-title" data-x-control-visibility="' . $visibility . '"></media-chapter-title>';
        break;


      case 'settings':

        $isToolTip = false;
        $visibility = isset( $control['visibility'] ) ? esc_attr( $control['visibility'] ) : "default";
        $settingsLabel = isset( $control['settingsLabel'] ) ? esc_attr__( $control['settingsLabel'] ) : esc_attr__("Settings");
        $captionsLabel = isset( $control['captionsLabel'] ) ? esc_attr__( $control['captionsLabel'] ) : esc_attr__("Captions");
        $chaptersLabel = isset( $control['chaptersLabel'] ) ? esc_attr__( $control['chaptersLabel'] ) : esc_attr__("Chapters");
        $speedLabel = isset( $control['speedLabel'] ) ? esc_attr__( $control['speedLabel'] ) : esc_attr__("Speed");
        $qualityLabel = isset( $control['qualityLabel'] ) ? esc_attr__( $control['qualityLabel'] ) : esc_attr__("Quality");

        $settingsPlacement = 'top' === $position ? 'bottom end' : 'top end';
        $settingsPlacementOption = isset( $control['settingsPlacement'] ) ? esc_attr( $control['settingsPlacement'] ) : $settingsPlacement;

        $settingstooltipPlacementSetting = isset( $control['tooltipPlacement'] ) ? esc_attr( $control['tooltipPlacement'] ) : "default";
        $settingstooltipPlacement = 'default' !== $settingstooltipPlacementSetting ? $settingstooltipPlacementSetting : $defaultTooltipPlacement;


        
        $settingsSpeedIcon = empty( $control['settingsSpeedIcon'] ) ? false : self::render_icon( $control['settingsSpeedIcon'] );
        $settingsQualityIcon = empty( $control['settingsQualityIcon'] ) ? false : self::render_icon( $control['settingsQualityIcon'] );
        $settingsChaptersIcon = empty( $control['settingsChaptersIcon'] ) ? false : self::render_icon( $control['settingsChaptersIcon'] );
        $settingsCaptionsIcon = empty( $control['settingsCaptionsIcon'] ) ? false : self::render_icon( $control['settingsCaptionsIcon'] );
        

        $button .= '<media-menu class="vds-menu" data-x-control-visibility="' . $visibility . '">';

        if ($maybeToolTips) {
          $button .= '<media-tooltip>
                        <media-tooltip-trigger>';
        }


        $button .= '<media-menu-button class="vds-menu-button vds-button" aria-label="' . $settingsLabel . '">';

        $button .= $inactiveIcon ? '<div class="vds-icon vds-icon-custom vds-icon-custom-inactive">'  . $inactiveIcon . '</div>' : '<media-icon class="vds-rotate-icon" type="settings"></media-icon>';
        $button .= '</media-menu-button>';

        if ($maybeToolTips) {

        $button .= '</media-tooltip-trigger>
                        <media-tooltip-content class="vds-tooltip-content" placement="' . $settingstooltipPlacement . '">
                        ' . $settingsLabel . '
                        </media-tooltip-content>
                      </media-tooltip>';
        }
                     
        $button .= '<media-menu-items class="vds-menu-items" placement="' . $settingsPlacementOption . '">';


        //$settingsSpeedIcon

        /* speed */
        $button .= '<media-menu class="vds-speed-menu vds-menu">
                          <media-menu-button class="vds-menu-button" aria-label="'. $speedLabel . '">
                           <media-icon class="vds-menu-button-close-icon" type="chevron-left"></media-icon>';
                          
        $button .= $settingsSpeedIcon ? '<div class="vds-icon vds-icon-custom vds-menu-button-icon">'  . $settingsSpeedIcon . '</div>' : '<media-icon class="vds-icon vds-menu-button-icon" type="odometer"></media-icon>';

          $button .= '<span class="vds-menu-button-label">' . $speedLabel . '</span>
                      <span class="vds-menu-button-hint" data-part="hint"></span>
                      <media-icon class="vds-menu-button-open-icon" type="chevron-right"></media-icon> </media-menu-button>

                          <media-menu-items class="vds-menu-items">
                              <media-speed-radio-group class="vds-captions-radio-group vds-radio-group">
                              <template>
                                <media-radio class="vds-radio">
                                  <span class="vds-radio-label" data-part="label"></span>
                                </media-radio>
                              </template>
                            </media-speed-radio-group>
                          </media-menu-items>
                        </media-menu>';

         /* captions */
         $button .=  ' <media-menu class="vds-menu">
                    <media-menu-button class="vds-menu-button" aria-label="'. $captionsLabel . '">
                      <media-icon class="vds-menu-button-close-icon" type="chevron-left"></media-icon>';

        $button .= $settingsCaptionsIcon ? '<div class="vds-icon vds-icon-custom vds-menu-button-icon">'  . $settingsCaptionsIcon . '</div>' : '<media-icon class="vds-icon vds-menu-button-icon" type="closed-captions"></media-icon>';              


        $button .=  '<span class="vds-menu-button-label">'. $captionsLabel . '</span>
                      <span class="vds-menu-button-hint" data-part="hint"></span>
                      <media-icon class="vds-menu-button-open-icon" type="chevron-right"></media-icon>
                    </media-menu-button>

                    <media-menu-items class="vds-menu-items">
                      <media-captions-radio-group class="vds-captions-radio-group vds-radio-group">
                        <template>
                          <media-radio class="vds-radio">
                            <div class="vds-radio-check"></div>
                            <span class="vds-radio-label" data-part="label"></span>
                          </media-radio>
                        </template>
                      </media-captions-radio-group>
                    </media-menu-items>
                  </media-menu>';


           /* quality */
           $button .= '<media-menu class="vds-quality-menu vds-menu">

                          <media-menu-button class="vds-menu-button" aria-label="'. $qualityLabel . '">
                          <media-icon class="vds-menu-button-close-icon" type="chevron-left"></media-icon>';


           
          $button .= $settingsQualityIcon ? '<div class="vds-icon vds-icon-custom vds-menu-button-icon">'  . $settingsQualityIcon . '</div>' : '<media-icon class="vds-icon vds-menu-button-icon " type="settings-menu"></media-icon>';             
                           
           $button .=  '<span class="vds-menu-button-label">'.$qualityLabel . '</span>
                        <span class="vds-menu-button-hint" data-part="hint"></span>
                        <media-icon class="vds-menu-button-open-icon" type="chevron-right"></media-icon>
                          </media-menu-button>

                          <media-menu-items class="vds-menu-items">
                              <media-quality-radio-group class="vds-audio-quality-group vds-radio-group">
                              <template>
                                <media-radio class="vds-radio">
                                  <span class="vds-radio-label" data-part="label"></span>
                                </media-radio>
                              </template>
                            </media-quality-radio-group>
                          </media-menu-items>
                        </media-menu>';

           /* chapters */
           $button .= '<media-menu class="vds-chapters-menu vds-menu">

                        <media-menu-button  class="vds-menu-button" aria-label="'. $chaptersLabel . '">
                         <media-icon class="vds-menu-button-close-icon" type="chevron-left"></media-icon>';


            $button .= $settingsChaptersIcon ? '<div class="vds-icon vds-icon-custom vds-menu-button-icon">'  . $settingsChaptersIcon . '</div>' :  '<media-icon class="vds-icon vds-menu-button-icon "type="chapters"></media-icon>';
            
            $button .=  '<span class="vds-menu-button-label"> '. $chaptersLabel . '</span>
                          <span class="vds-menu-button-hint" data-part="hint"></span>
                          <media-icon class="vds-menu-button-open-icon" type="chevron-right"></media-icon>
                        </media-menu-button>

                        <media-menu-items class="vds-menu-items">
                          <media-chapters-radio-group class="vds-chapters-radio-group vds-radio-group">
                            <template>
                              <media-radio class="vds-chapter-radio vds-radio">
                                <media-thumbnail class="vds-thumbnail" ' . $thumbnailAttr . '></media-thumbnail>
                                <div class="vds-chapter-radio-content">
                                  <span class="vds-chapter-radio-label" data-part="label"></span>
                                  <span class="vds-chapter-radio-start-time" data-part="start-time"></span>
                                  <span class="vds-chapter-radio-duration" data-part="duration"></span>
                                </div>
                              </media-radio>
                            </template>
                          </media-chapters-radio-group>
                          </media-menu-items>
                       </media-menu>';

          $button .=  '</media-menu-items>

                    </media-menu>';

        break;

        case 'time-slider':

          $isToolTip = false;
  
          $visibility = isset( $control['visibility'] ) ? esc_attr( $control['visibility'] ) : "default";
  
          $button .= '<media-time-slider class="vds-time-slider vds-slider" data-x-control-visibility="' . $visibility . '">

                        <media-slider-chapters class="vds-slider-chapters">
                          <template>
                            <div class="vds-slider-chapter">
                              <div class="vds-slider-track"></div>
                              <div class="vds-slider-track-fill vds-slider-track"></div>
                              <div class="vds-slider-progress vds-slider-track"></div>
                            </div>
                          </template>
                        </media-slider-chapters>

                        <div class="vds-slider-thumb"></div>

                      <media-slider-preview class="vds-slider-preview">
                          <media-slider-thumbnail ' . $thumbnailAttr . '
                            class="vds-slider-thumbnail vds-thumbnail"
                          ></media-slider-thumbnail>
                          <div
                            class="vds-slider-chapter-title"
                            data-part="chapter-title"
                          ></div>
                          <media-slider-value
                            class="vds-slider-value"
                          ></media-slider-value>
                        </media-slider-preview>

                    </media-,xMediaPlayer.vidstackDir + "assets/>';
  
          break;

          case 'custom-text':

            $isToolTip = false;
    
            $visibility = isset( $control['visibility'] ) ? esc_attr( $control['visibility'] ) : "default";

            $text = isset( $control['text'] ) ? esc_attr( $control['text'] ) : '';
    
            $button .= '<div class="vds-custom vds-custom-text">' . $text . '</div>';
    
            break;


          case 'live-button';
          
          $isToolTip = false;
          
          $visibility = isset( $control['visibility'] ) ? esc_attr( $control['visibility'] ) : "default";
  
          $button .= '<media-live-button class="vds-live-button" data-x-control-visibility="' . $visibility . '">';
          $button .= '<span class="vds-live-button-text">LIVE</span>';
          $button .= '</media-live-button>';

          break;

          case 'airplay';
            
            $airplayLabel = isset( $control['airplayLabel'] ) ? esc_attr__( $control['airplayLabel'] ) : esc_attr__("Airplay");
            $visibility = isset( $control['visibility'] ) ? esc_attr( $control['visibility'] ) : "default";
    
            $button .= '<media-airplay-button class="vds-button" data-x-control-visibility="' . $visibility . '">';
            $button .= $inactiveIcon ? '<div class="vds-airplay-icon vds-icon vds-icon-custom vds-icon-custom-inactive">'  . $inactiveIcon . '</div>' : '<media-icon type="airplay" class="vds-icon"></media-icon>';
            $button .= '</media-airplay-button>';
    
            $tooltip .= '<span class="airplay-tooltip-text">' . $airplayLabel . '</span>';

            break;


          case 'chromecast';
            
            $airplayLabel = isset( $control['enterPipLabel'] ) ? esc_attr__( $control['enterPipLabel'] ) : esc_attr__("Airplay");
            $visibility = isset( $control['visibility'] ) ? esc_attr( $control['visibility'] ) : "default";
    
            $button .= '<media-google-cast-button aria-hidden=false class="vds-button" data-x-control-visibility="' . $visibility . '">';
            $button .= $inactiveIcon ? '<div class="vds-chrome-cast-icon vds-icon vds-icon-custom vds-icon-custom-inactive">'  . $inactiveIcon . '</div>' : '<media-icon type="chromecast" class="vds-icon"></media-icon>';
            $button .= '</media-google-cast-button>';
    
            $tooltip .= '<span class="airplay-tooltip-text">' . $airplayLabel . '</span>';

            break;
            

          case 'image':

            $image      = $this->get_normalized_image_settings( $control );
            $image_id   = $image['id'];
            $image_url  = $image['url'];
            $image_size = $image['size'];

            $isToolTip = false;

            $image_placeholder_url = \Bricks\Builder::get_template_placeholder_image();

            $button .= '<div class="vds-image">';

            if ( isset( $control['image'] ) ) {

              if ( ! empty( $control['altText'] ) ) {
                $imageAttr = [
                  'loading' => 'eager',
                  'alt' => esc_attr( $control['altText'] )
                ]; 
              } else {
                $imageAttr = ['loading' => 'eager'];
              }
        
              $button .= wp_get_attachment_image( 
                $image_id, 
                $image_size, 
                false,
                $imageAttr
              );
        
            } else {
              $button .= '<img src="'. $image_placeholder_url  .'">';
            }

            $button .= '</div>';

            break;


    }

    

    if ( !!$spacer ) {
      $output .= $spacer;
    }
    
    else {

      $visibility = isset( $control['visibility'] ) ? esc_attr( $control['visibility'] ) : "default";

      $tooltipPlacementSetting = isset( $control['tooltipPlacement'] ) ? esc_attr( $control['tooltipPlacement'] ) : "default";

      $tooltipPlacement = 'default' !== $tooltipPlacementSetting ? $tooltipPlacementSetting : $defaultTooltipPlacement;


      if ($isToolTip && $maybeToolTips) {
        $output .= '<media-tooltip data-x-control-visibility="' . $visibility . '" showDelay=2000><media-tooltip-trigger>';
        $output .= $button;
        $output .= '</media-tooltip-trigger><media-tooltip-content class="vds-tooltip-content" placement="' . $tooltipPlacement . '">';
        $output .= $tooltip;
        $output .= '</media-tooltip-content></media-tooltip>';

        if ('disable' !== $volumeSlider) {
          $volumeVisibility = 'visible' === $volumeSlider ? 'vds-visible-volume' : '';
          $output .= '<media-volume-slider class="vds-volume-slider vds-slider ' . $volumeVisibility . '">';
          $output .= '<div class="vds-slider-track"></div>';
          $output .= '<div class="vds-slider-track-fill vds-slider-track"></div>';
          $output .= '<media-slider-preview class="vds-slider-preview" no-clamp>';
          $output .= '<media-slider-value
                        class="vds-slider-value"
                        type="pointer"
                        format="percent"
                      ></media-slider-value>';

          $output .= '</media-slider-preview>';
          $output .= '<div class="vds-slider-thumb"></div>';
          $output .= '</media-volume-slider>';
        }

      } else {
        $output .= $button;
      }

	  }

    return $output;

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