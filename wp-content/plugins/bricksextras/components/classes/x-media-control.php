<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class X_Media_Control extends \Bricks\Element {

  // Element properties
    public $category     = 'extras';
    public $name         = 'xmediacontrol';
    public $icon         = 'ti-control-play';
    public $css_selector = '';
   // public $scripts      = ['xMediaControl'];
   //public $nestable = true;

  
  public function get_label() {
	  return esc_html__( 'Media Control', 'extras' );
  }
  public function set_control_groups() {

    $this->control_groups['styleControls'] = [
        'title' => esc_html__( 'Styling', 'bricks' ),
      ];

    $this->control_groups['buttonIcons'] = [
      'title' => esc_html__( 'Icons', 'bricks' ),
      'required' => ['controlType', '!=', 
        ['time','title','custom-text','nest','time-slider','live-button']
      ]
    ];

      $this->control_groups['tooltips'] = [
        'title' => esc_html__( 'Tooltips', 'bricks' ),
        'required' => ['controlType', '!=', 
          ['time','time-slider','title','custom-text','nest','live-button']
        ]
      ];

  }

  public function set_controls() {

    $placementOptions = [
      'top' => esc_html__('Top', 'bricks' ), 
      'bottom' => esc_html__( 'Bottom', 'bricks' ), 
      'top-start' => esc_html__( 'Top Start', 'bricks' ), 
      'top-end' => esc_html__( 'Top End', 'bricks' ),
      'bottom-start' => esc_html__( 'Bottom Start', 'bricks' ), 
      'bottom-end' => esc_html__( 'Bottom End', 'bricks' ),
    ];


    $this->controls['controlType'] = [
        'label'    => esc_html__( 'Control type', 'bricks' ),
        'type'        => 'select',
        'rerender' => true,
        'options'     => [
          'play-large'  => 'Play / Pause (large)',
          'play'   => 'Play / Pause',
          'seek-backward' => 'Seek Backward',
          'seek-forward' => 'Seek Forward',
          'mute' => 'Volume / Mute',
          'spacer' => 'Spacer',
          'time'  => 'Time',
          'pip' => 'PIP *',
          'fullscreen' => 'Fullscreen *',
          'time-slider' => 'Time Slider',
          'settings' => 'Settings Menu',
          'captions' => 'Caption Toggle *',
          'title' => 'Title',
          'chapter-title' => 'Chapter Title *',
          'custom-text' => 'Custom Text',
          //'image' => 'Image',
          //'airplay' => 'Airplay *',
          //'chromecast' => 'Chromecast *',
        ],
        'placeholder' => esc_html__( 'Play/pause', 'bricks' ),
        'inline'      => true,
        'small' => true,
    ];

   $this->controls['playLabel']    = [
      'label'     => esc_html__( 'Play label', 'bricks' ),
      'type'      => 'text',
      'required'  => ['controlType', '=', ['play','play-large','']],
      'placeholder' => 'Play',
      'inline'    => true
    ];

   $this->controls['pauseLabel']    = [
      'label'     => esc_html__( 'Pause label', 'bricks' ),
      'type'      => 'text',
      'required'  => ['controlType', '=', ['play','play-large','']],
      'inline'    => true,
      'placeholder' => 'Pause'
    ];

    $this->controls['captionsOnLabel']    = [
      'label'     => esc_html__('Captions On Label', 'bricks' ),
      'type'      => 'text',
      'required'  => ['controlType', '=', ['captions']],
      'placeholder' => 'Closed-Captions On',
      'inline'    => true
    ];

    $this->controls['captionsOffLabel']    = [
      'label'     => esc_html__('Captions Off Label', 'bricks' ),
      'type'      => 'text',
      'required'  => ['controlType', '=', ['captions']],
      'placeholder' => 'Closed-Captions Off',
      'inline'    => true
    ];

    $this->controls['seekAmountBack']    = [
      'label'     => esc_html__( 'Seek Distance', 'bricks' ),
      'type'      => 'select',
      'inline'    => true,
      'options'   => [
        '-10' => '-10',
        '-15' => '-15',
        '-30' => '-30',
      ],
      'required'  => ['controlType', '=', 'seek-backward'],
      'placeholder' => '-10'
    ];

    $this->controls['seekLabelBack']    = [
      'label'     => esc_html__( 'Seek Label', 'bricks' ),
      'type'      => 'text',
      'inline'    => true,
      'required'  => ['controlType', '=', 'seek-backward'],
      'placeholder' => 'Rewind'
    ];

    $this->controls['seekAmountForward']    = [
      'label'     => esc_html__( 'Seek Distance', 'bricks' ),
      'type'      => 'select',
      'inline'    => true,
      'options'   => [
        '+10' => '+10',
        '+15' => '+15',
        '+30' => '+30',
      ],
      'required'  => ['controlType', '=', 'seek-forward'],
      'placeholder' => '+10'
    ];

    $this->controls['seekLabelForward']    = [
      'label'     => esc_html__( 'Seek Label', 'bricks' ),
      'type'      => 'text',
      'inline'    => true,
      'required'  => ['controlType', '=', 'seek-forward'],
      'placeholder' => 'Forward'
    ];

    $this->controls['volumeSlider' ]   = [
      'label'     => esc_html__( 'Include volume slider', 'bricks' ),
      'type'      => 'select',
      'options'   => [
        'focus' => esc_html__( 'Enable (visible on focus)', 'bricks' ),
        'visible' => esc_html__( 'Enable (always visible)', 'bricks' ),
        'disable' => esc_html__( 'Disable', 'bricks' ),
      ],
      'placeholder' => esc_html__( 'Enable (visible on focus)', 'bricks' ),
      'required'  => ['controlType', '=', 'mute'],
    ];

    $this->controls['muteLabel']    = [
      'label'     => esc_html__( 'Mute Label', 'bricks' ),
      'type'      => 'text',
      'required'  => ['controlType', '=', 'mute'],
      'inline'    => true,
      'placeholder' => 'Mute'
    ];

    $this->controls['unmuteLabel']    = [
      'label'     => esc_html__( 'Unmute Label', 'bricks' ),
      'type'      => 'text',
      'required'  => ['controlType', '=', 'mute'],
      'inline'    => true,
      'placeholder' => 'Unmute'
    ];

    $this->controls['currentTime']    = [
      'label'     => esc_html__( 'Current time', 'bricks' ),
      'type'      => 'select',
      'options'   => [
        'enable' => esc_html__( 'Enable', 'bricks' ),
        'disable' => esc_html__( 'Disable', 'bricks' ),
      ],
      'inline'    => true,
      'required'  => ['controlType', '=', 'time'],
      'placeholder' => esc_html__( 'Enable', 'bricks' ),
    ];

    $this->controls['timeDivider']    = [
      'label'     => esc_html__( 'Divider', 'bricks' ),
      'type'      => 'select',
      'inline'    => true,
      'options'   => [
        'enable' => esc_html__( 'Enable', 'bricks' ),
        'disable' => esc_html__( 'Disable', 'bricks' ),
      ],
      'required'  => ['controlType', '=', 'time'],
      'placeholder' => esc_html__( 'Enable', 'bricks' ),
    ];

    $this->controls['timeDividerText']    = [
      'label'     => esc_html__( 'Divider', 'bricks' ),
      'type'      => 'text',
      'inline'    => true,
      'required'  => [
        ['controlType', '=', 'time'],
        ['timeDivider', '!=', 'disable'],
      ],
      'placeholder' => '/',
    ];

    $this->controls['duration']    = [
      'label'     => esc_html__( 'Duration', 'bricks' ),
      'type'      => 'select',
      'inline'    => true,
      'options'   => [
        'enable' => esc_html__( 'Enable', 'bricks' ),
        'disable' => esc_html__( 'Disable', 'bricks' ),
      ],
      'required'  => ['controlType', '=', 'time'],
      'placeholder' => esc_html__( 'Enable', 'bricks' ),
    ];


    $this->controls['enterPipLabel']    = [
      'label'     => esc_html__( 'Enter PIP Label', 'bricks' ),
      'type'      => 'text',
      'inline'    => true,
      'required'  => ['controlType', '=', 'pip'],
      'placeholder' => 'Enter PIP'
    ];

    $this->controls['exitPipLabel' ]   = [
      'label'     => esc_html__( 'Exit PIP Label', 'bricks' ),
      'type'      => 'text',
      'inline'    => true,
      'required'  => ['controlType', '=', 'pip'],
      'placeholder' => 'Exit PIP'
    ];


    $this->controls['enterFullscreenLabel']    = [
      'label'     => esc_html__( 'Enter Fullscreen Label', 'bricks' ),
      'type'      => 'text',
      'inline'    => true,
      'required'  => ['controlType', '=', 'fullscreen'],
      'placeholder' => 'Enter Fullscreen'
    ];

    $this->controls['exitFullscreenLabel']    = [
      'label'     => esc_html__( 'Exit Fullscreen Label', 'bricks' ),
      'type'      => 'text',
      'inline'    => true,
      'required'  => ['controlType', '=', 'fullscreen'],
      'placeholder' => 'Exit Fullscreen'
    ];

    $this->controls['settingsLabel']    = [
      'label'     => esc_html__( 'Settings Label', 'bricks' ),
      'type'      => 'text',
      'required'  => ['controlType', '=', 'settings'],
      'inline'    => true,
      'placeholder' => 'Settings'
    ];

    $this->controls['speedLabel']    = [
      'label'     => esc_html__( 'Speed Label', 'bricks' ),
      'type'      => 'text',
      'required'  => ['controlType', '=', 'settings'],
      'inline'    => true,
      'placeholder' => 'Speed'
    ];

    $this->controls['qualityLabel' ]   = [
      'label'     => esc_html__( 'Quality Label', 'bricks' ),
      'type'      => 'text',
      'required'  => ['controlType', '=', 'settings'],
      'inline'    => true,
      'placeholder' => 'Quality'
    ];

    $this->controls['captionsLabel']    = [
      'label'     => esc_html__( 'Captions Label', 'bricks' ),
      'type'      => 'text',
      'required'  => ['controlType', '=', 'settings'],
      'inline'    => true,
      'placeholder' => 'Captions'
    ];

    $this->controls['chaptersLabel']    = [
      'label'     => esc_html__( 'Chapters Label', 'bricks' ),
      'type'      => 'text',
      'required'  => ['controlType', '=', 'settings'],
      'inline'    => true,
      'placeholder' => 'Chapters'
    ];

    $this->controls['settingsPlacement']    = [
      'label'     => esc_html__( 'Menu Placement', 'bricks' ),
      'type'      => 'select',
      'options' => $placementOptions,
      'required'  => ['controlType', '=', 'settings'],
      'inline'    => true,
      'placeholder' => esc_html__( 'Top End', 'bricks' )
    ];


    $this->controls['controlText'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Text', 'bricks' ),
        'type' => 'text',
        'default' => 'Text here',
        'required' => ['controlType', '=', 'custom-text'],
      ];


    $this->controls['controlHide'] = [
        'label'    => esc_html__( 'Show control.. (for video only)', 'bricks' ),
        'type'        => 'select',
        'rerender' => true,
        'options'     => [
            'controls' => esc_html__( 'When user interacting', 'bricks' ),
            'first'  => esc_html__( 'Only visible before first play', 'bricks' ),
            'always' => esc_html__( 'Always visible', 'bricks' ),
        ],
        'placeholder' => esc_html__( 'When user interacting', 'bricks' ),
    ];


    $this->controls['controlsStyleSep'] = [
        'group' => 'styleControls',
        'label'    => esc_html__( 'Button Controls', 'bricks' ),
        'type'        => 'separator',
        'required' => ['controlType', '!=', ['custom-text','time-slider','time','title','nest','live-button']],
    ];
  
  
      $this->controls['iconSize'] = [
        'tab'    => 'content',
        'type'   => 'number',
        'units'   => true,
        'group' => 'styleControls',
        'label'  => esc_html__( 'Icon size', 'extras' ),
        'css'    => [
          [
            'property' => '--media-button-icon-size',
            'selector' => '',
          ],
          [
            'property' => '--media-button-icon-size',
            'selector' => '.vds-button-large',
          ],
          
        ],
        'placeholder' => '24px',
        'required' => ['controlType', '!=', ['custom-text','time-slider','time','title','nest','live-button']],
      ];

      $this->controls['iconColor'] = [
        'tab'    => 'content',
        'type'   => 'color',
        'group' => 'styleControls',
        'label'  => esc_html__( 'Icon color', 'extras' ),
        'css'    => [
          [
            'property' => 'color',
            'selector' => '.vds-button:not(.vds-settings-icon)',
          ],
        ],
        'placeholder' => '24px',
        'required' => ['controlType', '!=', ['custom-text','time-slider','time','title','nest','live-button']],
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
          [
            'property' => '--media-button-size',
            'selector' => '.vds-button-large',
          ],
        ],
        'placeholder' => '40px',
        'required' => ['controlType', '!=', ['custom-text','time-slider','time','title','nest','live-button']],
      ];
  
      $this->controls['controlBg'] = [
        'tab'    => 'content',
        'type'   => 'color',
        'group' => 'styleControls',
        'label'  => esc_html__( 'Background', 'extras' ),
        'css'    => [
          [
            'property' => 'background-color',
            'selector' => '.vds-button:not(.vds-settings-icon)',
          ],
        ],
        'required' => ['controlType', '!=', ['custom-text','time-slider','time','title','nest','live-button']],
      ];
  
      $this->controls['controlBorder'] = [
        'tab'    => 'content',
        'type'   => 'border',
        'group' => 'styleControls',
        'label'  => esc_html__( 'Border', 'extras' ),
        'css'    => [
          [
            'property' => 'border',
            'selector' => '.vds-button:not(.vds-settings-icon)',
          ],
        ],
        'required' => ['controlType', '!=', ['custom-text','time-slider','time','title','nest','live-button']],
      ];

      $this->controls['controlTypography'] = [
        'tab'    => 'content',
        'type'   => 'typography',
        'group' => 'styleControls',
        'label'  => esc_html__( 'Typography', 'extras' ),
        'css'    => [
          [
            'property' => 'font',
            'selector' => '& > *',
          ],
        ],
        'required' => ['controlType', '!=', ['custom-text','time-slider','time','title','nest','live-button']],
      ];

      $buttonsWithOutActiveState = [
        'custom-text',
        'time-slider',
        'time',
        'title',
        'nest',
        'seek-backward',
        'seek-forward',
        'live-button'
      ];

      $this->controls['controlsStyleActiveSep'] = [
        'group' => 'styleControls',
        'label'    => esc_html__( 'Button Controls (active)', 'bricks' ),
        'type'        => 'separator',
        'required' => ['controlType', '!=', $buttonsWithOutActiveState],
      ];



      $this->controls['iconSizeActive'] = [
        'tab'    => 'content',
        'type'   => 'number',
        'units'   => true,
        'group' => 'styleControls',
        'label'  => esc_html__( 'Icon size', 'extras' ),
        'css'    => [
          [
            'property' => '--media-button-icon-size',
            'selector' => '[data-pressed]',
          ],
          [
            'property' => '--media-button-icon-size',
            'selector' => '[data-pressed].vds-button-large',
          ],
          
        ],
        'placeholder' => '24px',
        'required' => ['controlType', '!=', $buttonsWithOutActiveState],
      ];

      $this->controls['iconColorActive'] = [
        'tab'    => 'content',
        'type'   => 'color',
        'group' => 'styleControls',
        'label'  => esc_html__( 'Icon color', 'extras' ),
        'css'    => [
          [
            'property' => 'color',
            'selector' => '[data-pressed].vds-button:not(.vds-settings-icon)',
          ],
        ],
        'placeholder' => '24px',
        'required' => ['controlType', '!=', $buttonsWithOutActiveState],
      ];
  
      $this->controls['controlSizeActive'] = [
        'tab'    => 'content',
        'type'   => 'number',
        'units'   => true,
        'group' => 'styleControls',
        'label'  => esc_html__( 'Control size', 'extras' ),
        'css'    => [
          [
            'property' => '--media-button-size',
            'selector' => '[data-pressed]',
          ],
          [
            'property' => '--media-button-size',
            'selector' => '[data-pressed].vds-button-large',
          ],
        ],
        'placeholder' => '40px',
        'required' => ['controlType', '!=', $buttonsWithOutActiveState],
      ];
  
      $this->controls['controlBgActive'] = [
        'tab'    => 'content',
        'type'   => 'color',
        'group' => 'styleControls',
        'label'  => esc_html__( 'Background', 'extras' ),
        'css'    => [
          [
            'property' => 'background-color',
            'selector' => '[data-pressed].vds-button:not(.vds-settings-icon)',
          ],
        ],
        'required' => ['controlType', '!=', $buttonsWithOutActiveState],
      ];
  
      $this->controls['controlBorderActive'] = [
        'tab'    => 'content',
        'type'   => 'border',
        'group' => 'styleControls',
        'label'  => esc_html__( 'Border', 'extras' ),
        'css'    => [
          [
            'property' => 'border',
            'selector' => '[data-pressed].vds-button:not(.vds-settings-icon)',
          ],
        ],
        'required' => ['controlType', '!=', $buttonsWithOutActiveState],
      ];

      $this->controls['controlTypographyActive'] = [
        'tab'    => 'content',
        'type'   => 'typography',
        'group' => 'styleControls',
        'label'  => esc_html__( 'Typography', 'extras' ),
        'css'    => [
          [
            'property' => 'font',
            'selector' => '& [data-pressed] > *',
          ],
          [
            'property' => 'font',
            'selector' => '[data-pressed] .vds-time',
          ],
        ],
        'required' => ['controlType', '!=', $buttonsWithOutActiveState],
      ];

      /* Live button 

      $this->controls['liveButtonSep'] = [
        'group' => 'styleControls',
        'label'    => esc_html__( 'Live button', 'bricks' ),
        'type'        => 'separator',
        'required' => ['controlType', '=', ['live-button']],
      ];
      
      $liveButton = '.vds-live-button-text';

      $this->controls['liveButtonBg'] = [
        'tab'    => 'content',
        'type'   => 'color',
        'group' => 'styleControls',
        'label'  => esc_html__( 'Background', 'extras' ),
        'required' => ['controlType', '=', ['live-button']],
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
        'required' => ['controlType', '=', ['live-button']],
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
        'required' => ['controlType', '=', ['live-button']],
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
        'required' => ['controlType', '=', ['live-button']],
        'css'    => [
          [
            'property' => 'padding',
            'selector' => $liveButton
          ],
        ],
      ];
 */

      /* time slider */

      $timeSliderSelector = '.vds-time-slider.vds-slider';

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
      'required' => ['controlType', '=', ['time-slider']],
      'placeholder' => '40px'
    ];

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
      'required' => ['controlType', '=', ['time-slider']],
      'placeholder' => '5px'
    ];


    $this->controls['timeSliderTrackSep'] = [
      'group' => 'styleControls',
			'type'        => 'separator',
      'required' => ['controlType', '=', ['time-slider']],
		];
    

    $this->controls['trackFillBackground'] = [
      'tab'    => 'content',
      'type'   => 'color',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Track progress color', 'extras' ),
      'css'    => [
        [
          'property' => '--media-slider-track-fill-bg',
          'selector' => $timeSliderSelector,
        ],
      ],
      'required' => ['controlType', '=', ['time-slider']],
    ];

    $this->controls['trackProgressBackground'] = [
      'tab'    => 'content',
      'type'   => 'color',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Track buffered color', 'extras' ),
      'css'    => [
        [
          'property' => '--media-slider-track-progress-bg',
          'selector' => $timeSliderSelector,
        ],
      ],
      'required' => ['controlType', '=', ['time-slider']],
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
      'required' => ['controlType', '=', ['time-slider']],
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
      'required' => ['controlType', '=', ['time-slider']],
    ];

    $this->controls['timeSliderThumbSep'] = [
      'group' => 'styleControls',
			'type'        => 'separator',
      'required' => ['controlType', '=', ['time-slider']],
		];

    $this->controls['thumbColor'] = [
      'tab'    => 'content',
      'type'   => 'color',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Thumb color', 'extras' ),
      'css'    => [
        [
          'property' => '--media-slider-thumb-bg',
          'selector' => $timeSliderSelector,
        ],
      ],
      'required' => ['controlType', '=', ['time-slider']],
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
      'required' => ['controlType', '=', ['time-slider']],
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
          'selector' => '.vds-slider-thumb'
        ],
      ],
      'required' => ['controlType', '=', ['time-slider']],
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
      'required' => ['controlType', '=', ['time-slider']],
      'placeholder' => '17px'
    ];

    $this->controls['sliderValueSep'] = [
      'group' => 'styleControls',
			'label'    => esc_html__( 'Slider time value', 'bricks' ),
			'type'        => 'separator',
      'required' => ['controlType', '=', ['time-slider']],
		];
    
    $sliderValueSelector = '.vds-time-slider .vds-slider-value';

    $this->controls['sliderOffset'] = [
      'tab'    => 'content',
      'type'   => 'number',
      'units' => true,
      'group' => 'styleControls',
      'label'  => esc_html__( 'Value offest', 'extras' ),
      'css'    => [
        [
          'property' => '--media-slider-preview-offset',
          'selector' => $sliderValueSelector
        ],
      ],
      'required' => ['controlType', '=', ['time-slider']],
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
      'required' => ['controlType', '=', ['time-slider']],
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
      'required' => ['controlType', '=', ['time-slider']],
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
      'required' => ['controlType', '=', ['time-slider']],
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
      'required' => ['controlType', '=', ['time-slider']],
    ];

    /* slider chapter title*/

    $this->controls['sliderChapterTitleSep'] = [
      'group' => 'styleControls',
			'label'    => esc_html__( 'Slider chapter value', 'bricks' ),
			'type'        => 'separator',
      'required' => ['controlType', '=', ['time-slider']],
		];
    
    $sliderChapterTitle = '.vds-slider-chapter-title';

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
      'required' => ['controlType', '=', ['time-slider']],
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
      'required' => ['controlType', '=', ['time-slider']],
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
      'required' => ['controlType', '=', ['time-slider']],
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
      'required' => ['controlType', '=', ['time-slider']],
    ];



    /* volume slider */

    $this->controls['volumeSliderStyleSep'] = [
      'group' => 'styleControls',
			'label'    => esc_html__( 'Volume slider', 'bricks' ),
			'type'        => 'separator',
      'required' => ['controlType', '=', ['mute']],
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
      'placeholder' => '40px',
      'required' => ['controlType', '=', ['mute']],
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
      'placeholder' => '5px',
      'required' => ['controlType', '=', ['mute']],
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
      'placeholder' => '80px',
      'required' => ['controlType', '=', ['mute']],
    ];

    

    $this->controls['timeSliderTrackSep'] = [
      'group' => 'styleControls',
			'type'        => 'separator',
      'required' => ['controlType', '=', ['mute']],
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
      'required' => ['controlType', '=', ['mute']],
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
      'required' => ['controlType', '=', ['mute']],
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
      'required' => ['controlType', '=', ['mute']],
    ];

    $this->controls['timeSliderThumbSepVolume'] = [
      'group' => 'styleControls',
			'type'        => 'separator',
      'required' => ['controlType', '=', ['mute']],
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
      'required' => ['controlType', '=', ['mute']],
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
      'placeholder' => '15px',
      'required' => ['controlType', '=', ['mute']],
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
      'required' => ['controlType', '=', ['mute']],
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
      'placeholder' => '17px',
      'required' => ['controlType', '=', ['mute']],
    ];

    $this->controls['sliderValueSepVolume'] = [
      'group' => 'styleControls',
			'label'    => esc_html__( 'Volume time value', 'bricks' ),
			'type'        => 'separator',
      'required' => ['controlType', '=', ['mute']],
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
      'required' => ['controlType', '=', ['mute']],
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
      'required' => ['controlType', '=', ['mute']],
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
      'required' => ['controlType', '=', ['mute']],
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
      'required' => ['controlType', '=', ['mute']],
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
      'required' => ['controlType', '=', ['mute']],
    ];



    /* settings menu */


    $settingsMenuSelector = ".vds-menu-items:not([data-submenu])";
    $settingsMenuItemSelector = ".vds-menu-items [role=menuitemradio]";
    $settingsMenuItemRadioSelector = ".vds-menu-items [role=menuitem]";

    $this->controls['settingsMenuSep'] = [
      'required' => ['controlType', '=', ['settings']],
      'group' => 'styleControls',
			'label'    => esc_html__( 'Settings Menu', 'bricks' ),
			'type'        => 'separator',
      
		];

    $this->controls['menuBg'] = [
      'required' => ['controlType', '=', ['settings']],
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
      'required' => ['controlType', '=', ['settings']],
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
      'required' => ['controlType', '=', ['settings']],
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
      'required' => ['controlType', '=', ['settings']],
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
      'required' => ['controlType', '=', ['settings']],
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
      'required' => ['controlType', '=', ['settings']],
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
      'required' => ['controlType', '=', ['settings']],
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
      'required' => ['controlType', '=', ['settings']],
      'group' => 'styleControls',
			'label'    => esc_html__( 'Settings Menu Items', 'bricks' ),
			'type'        => 'separator',
      
		];

    

    

    
    $this->controls['menuItemBg'] = [
      'required' => ['controlType', '=', ['settings']],
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
      'required' => ['controlType', '=', ['settings']],
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
      'required' => ['controlType', '=', ['settings']],
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
      'required' => ['controlType', '=', ['settings']],
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
      'required' => ['controlType', '=', ['settings']],
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
      'required' => ['controlType', '=', ['settings']],
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
      'required' => ['controlType', '=', ['settings']],
      'group' => 'styleControls',
			'label'    => esc_html__( 'Settings Menu Items (selected)', 'bricks' ),
			'type'        => 'separator',
      
		];

    $this->controls['menuItemBgSelected'] = [
      'required' => ['controlType', '=', ['settings']],
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
      'required' => ['controlType', '=', ['settings']],
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
      'required' => ['controlType', '=', ['settings']],
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
      'required' => ['controlType', '=', ['settings']],
      'group' => 'styleControls',
			'label'    => esc_html__( 'Chaper Menu Items', 'bricks' ),
			'type'        => 'separator',
		];



    $this->controls['chapterLabel'] = [
      'required' => ['controlType', '=', ['settings']],
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
      'required' => ['controlType', '=', ['settings']],
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
      'required' => ['controlType', '=', ['settings']],
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
      'required' => ['controlType', '=', ['settings']],
      'group' => 'styleControls',
			'label'    => esc_html__( 'Chapter Menu Items (hover/focus)', 'bricks' ),
			'type'        => 'separator',
		];

    $this->controls['chapterProgressColor'] = [
      'tab'    => 'content',
      'type'   => 'color',
      'required' => ['controlType', '=', ['settings']],
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
      'required' => ['controlType', '=', ['settings']],
      'css'    => [
        [
          'property' => '--media-chapters-progress-height',
          'selector' => ''
        ],
      ],
    ];


    $this->controls['chapterLabelHocus'] = [
      'required' => ['controlType', '=', ['settings']],
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
      'required' => ['controlType', '=', ['settings']],
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
      'required' => ['controlType', '=', ['settings']],
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
      'required' => ['controlType', '=', ['settings']],
      'group' => 'styleControls',
			'label'    => esc_html__( 'Chapter Menu Items (selected)', 'bricks' ),
			'type'        => 'separator',
		];



    $this->controls['chapterLabelSelected'] = [
      'required' => ['controlType', '=', ['settings']],
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
      'required' => ['controlType', '=', ['settings']],
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
      'required' => ['controlType', '=', ['settings']],
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



    /* time */


    $this->controls['timeSep'] = [
      'group' => 'styleControls',
			'label'    => esc_html__( 'Time', 'bricks' ),
			'type'        => 'separator',
      'required' => ['controlType', '=', ['time']],
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
      'required' => ['controlType', '=', ['time']],
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
      'required' => ['controlType', '=', ['time']],
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
        [
          'property' => 'gap',
          'selector' => '.vds-chapter-title'
        ],
      ],
      'required' => ['controlType', '=', ['time']],
    ];

    

    /* custom text */

    $this->controls['Typography'] = [
      'tab'    => 'content',
      'type'   => 'typography',
      'group' => 'styleControls',
      'label'  => esc_html__( 'Typography', 'extras' ),
      'css'    => [
        [
          'property' => 'font',
          'selector' => '.vds-custom'
        ],
        [
          'property' => 'font',
          'selector' => '.vds-chapter-title'
        ],
        
      ],
      'required' => ['controlType', '=', ['custom-text', 'title']],
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



      /* button icons */

      $inactiveControls = [
        '',
        'play',
        'play-large',
        'seek-forward',
        'seek-backward',
        //'mute',
        'pip',
        'fullscreen',
        'settings',
        'captions'
      ];


      $this->controls['inactiveIconSep'] = [
        'tab'      => 'content',
		    'group' => 'buttonIcons',
        'label'    => esc_html__( 'Icon', 'bricks' ),
        'type'     => 'separator',
        'required'  => ['controlType', '=', $inactiveControls],
      ];

      $this->controls['inactiveIcon'] = [
        'tab'      => 'content',
		    'group' => 'buttonIcons',
        'label'    => esc_html__( 'Icon', 'bricks' ),
        'type'     => 'icon',
        'css'      => [
          [
            'selector' => '.vds-icon.vds-inactive-icon',
          ],
        ],
        'required'  => ['controlType', '=', $inactiveControls],
      ];

      $this->controls['inactiveIconSize'] = [
        'tab'      => 'content',
		    'group' => 'buttonIcons',
        'label'    => esc_html__( 'Icon size', 'bricks' ),
        'type'     => 'number',
        'units'     => true,
        'css'      => [
          [
            'property' => '--media-button-icon-size',
            'selector' => '.vds-icon.vds-icon-custom-inactive',
          ],
        ],
        'required'  => ['controlType', '=', $inactiveControls],
      ];


      $activeControls = [
        '',
        'play',
        'play-large',
        //'mute',
        'pip',
        'fullscreen',
        'captions'
      ];

      $this->controls['activeIconSep'] = [
        'tab'      => 'content',
		    'group' => 'buttonIcons',
        'label'    => esc_html__( 'Icon - active state', 'bricks' ),
        'type'     => 'separator',
        'required'  => ['controlType', '=', $activeControls],
      ];

      $this->controls['activeIcon'] = [
        'tab'      => 'content',
		    'group' => 'buttonIcons',
        'label'    => esc_html__( 'Icon', 'bricks' ),
        'type'     => 'icon',
        'css'      => [
          [
            'selector' => '.vds-icon.vds-active-icon',
          ],
        ],
        'required'  => ['controlType', '=', $activeControls],
      ];

      $this->controls['activeIconSize'] = [
        'tab'      => 'content',
		    'group' => 'buttonIcons',
        'label'    => esc_html__( 'Icon Size', 'bricks' ),
        'type'     => 'number',
        'units'     => true,
        'css'      => [
          [
            'property' => '--media-button-icon-size',
            'selector' => '.vds-icon.vds-icon-custom-active',
          ],
        ],
        'required'  => ['controlType', '=', $activeControls],
      ];


      /* mute icons */

      $this->controls['muteIcon'] = [
        'tab'      => 'content',
		    'group' => 'buttonIcons',
        'label'    => esc_html__( 'Muted Icon', 'bricks' ),
        'type'     => 'icon',
        'css'      => [
          [
            'selector' => '.vds-mute-icon',
          ],
        ],
        'required'  => ['controlType', '=', 'mute'],
      ];

      $this->controls['muteIconSize'] = [
        'tab'      => 'content',
		    'group' => 'buttonIcons',
        'label'    => esc_html__( 'Muted Icon Size', 'bricks' ),
        'type'     => 'number',
        'units'     => true,
        'css'      => [
          [
            'property' => '--media-button-icon-size',
            'selector' => '.vds-mute-icon',
          ],
        ],
        'required'  => ['controlType', '=', 'mute']
      ];

      $this->controls['highVolumeIcon'] = [
        'tab'      => 'content',
		    'group' => 'buttonIcons',
        'label'    => esc_html__( 'High Volume Icon', 'bricks' ),
        'type'     => 'icon',
        'css'      => [
          [
            'selector' => '.vds-volume-high-icon',
          ],
        ],
        'required'  => ['controlType', '=', 'mute'],
      ];

      $this->controls['highVolumeIconSize'] = [
        'tab'      => 'content',
		    'group' => 'buttonIcons',
        'label'    => esc_html__( 'High Volume Icon Size', 'bricks' ),
        'type'     => 'number',
        'units'     => true,
        'css'      => [
          [
            'property' => '--media-button-icon-size',
            'selector' => '.vds-volume-high-icon',
          ],
        ],
        'required'  => ['controlType', '=', 'mute']
      ];

      $this->controls['lowVolumeIcon'] = [
        'tab'      => 'content',
		    'group' => 'buttonIcons',
        'label'    => esc_html__( 'Low Volume Icon', 'bricks' ),
        'type'     => 'icon',
        'css'      => [
          [
            'selector' => '.vds-icon',
          ],
        ],
        'required'  => ['controlType', '=', 'mute'],
      ];

      $this->controls['lowVolumeIconSize'] = [
        'tab'      => 'content',
		    'group' => 'buttonIcons',
        'label'    => esc_html__( 'Low Volume Icon Size', 'bricks' ),
        'type'     => 'number',
        'units'     => true,
        'css'      => [
          [
            'property' => '--media-button-icon-size',
            'selector' => '.vds-low-high-icon',
          ],
        ],
        'required'  => ['controlType', '=', 'mute']
      ];

      
      $this->controls['settingsSpeedIcon'] = [
        'tab'      => 'content',
        'group' => 'buttonIcons',
        'label'    => esc_html__( 'Speed setting Icon', 'bricks' ),
        'type'     => 'icon',
        'css'      => [
          [
            'selector' => '.vds-icon.vds-active-icon',
          ],
        ],
        'required'  => ['controlType', '=', 'settings'],
      ];

      $this->controls['settingsQualityIcon'] = [
        'tab'      => 'content',
        'group' => 'buttonIcons',
        'label'    => esc_html__( 'Quality setting icon', 'bricks' ),
        'type'     => 'icon',
        'css'      => [
          [
            'selector' => '.vds-icon.vds-active-icon',
          ],
        ],
        'required'  => ['controlType', '=', 'settings'],
      ];

      $this->controls['settingsChaptersIcon'] = [
        'tab'      => 'content',
        'group' => 'buttonIcons',
        'label'    => esc_html__( 'Chapters setting icon', 'bricks' ),
        'type'     => 'icon',
        'css'      => [
          [
            'selector' => '.vds-icon.vds-active-icon',
          ],
        ],
        'required'  => ['controlType', '=', 'settings'],
      ];

      $this->controls['settingsCaptionsIcon'] = [
        'tab'      => 'content',
        'group' => 'buttonIcons',
        'label'    => esc_html__( 'Caption setting icon', 'bricks' ),
        'type'     => 'icon',
        'css'      => [
          [
            'selector' => '.vds-icon.vds-active-icon',
          ],
        ],
        'required'  => ['controlType', '=', 'settings'],
      ];
      



      


  }
  
  public function render() {

    $settings = $this->settings;
    $controlType = isset( $settings['controlType'] ) ? $settings['controlType'] : 'play';
    $controlHide = isset( $settings['controlHide'] ) ? $settings['controlHide'] : 'controls';

    $maybeToolTips = isset( $settings['maybeToolTips'] ) ? 'enable' === $settings['maybeToolTips'] : true;
    $defaultTooltipPlacement = isset( $settings['defaultTooltipPlacement'] ) ? $settings['defaultTooltipPlacement'] : 'top';

    $this->set_attribute( '_root', 'data-x-visible', $controlHide );
    $this->set_attribute( '_root', 'data-x-control-type', $controlType );

    $inactiveIcon = empty( $this->settings['inactiveIcon'] ) ? false : self::render_icon( $this->settings['inactiveIcon'] );
    $activeIcon = empty( $this->settings['activeIcon'] ) ? false : self::render_icon( $this->settings['activeIcon'] );

    $output = '';
    $button = '';
    $tooltip = '';
    $isToolTip = false;
    $tag = 'media-play-button';
    $buttonClass = 'vds-button';
    $volumeSlider = 'disable';
		
        switch ( $controlType ) {

            case 'play':

                $tag = 'media-play-button';

                $playLabel = !empty( $settings['playLabel'] ) ? esc_attr__( $settings['playLabel'] ) : esc_attr__( 'Play' );
                $pauseLabel = !empty( $settings['pauseLabel'] ) ? esc_attr__( $settings['pauseLabel'] ) : esc_attr__( 'Pause' );

                $isToolTip = $maybeToolTips;
                
                $button .= $inactiveIcon ? '<div class="vds-play-icon vds-icon vds-icon-custom vds-icon-custom-inactive">'  . $inactiveIcon . '</div>' : '<media-icon class="vds-play-icon" type="play"></media-icon>';
                $button .= $activeIcon ? '<div class="vds-pause-icon vds-icon vds-icon-custom vds-icon-custom-active">' . $activeIcon . '</div>' : '<media-icon class="vds-pause-icon" type="pause"></media-icon>';

                $tooltip .= '<span class="vds-play-tooltip-text">' . $playLabel . '</span>';
                $tooltip .= '<span class="vds-pause-tooltip-text">' . $pauseLabel . '</span>';
        
        
                break;

              case 'play-large':

                $tag = 'media-play-button';
                $buttonClass = 'vds-button vds-button-large';

                $isToolTip = $maybeToolTips;

                $playLabel = !empty( $settings['playLabel'] ) ? esc_attr__( $settings['playLabel'] ) : esc_attr__('Play');
                $pauseLabel = !empty( $settings['pauseLabel'] ) ? esc_attr__( $settings['pauseLabel'] ) : esc_attr__('Pause');
                $visibility = isset( $settings['visibility'] ) ? esc_attr( $settings['visibility'] ) : "default";

                $button .= $inactiveIcon ? '<div class="vds-play-icon vds-icon vds-icon-custom vds-icon-custom-inactive">'  . $inactiveIcon . '</div>' : '<media-icon class="vds-play-icon" type="play"></media-icon>';
                $button .= $activeIcon ? '<div class="vds-pause-icon vds-icon vds-icon-custom vds-icon-custom-active">' . $activeIcon . '</div>' : '<media-icon class="vds-pause-icon" type="pause"></media-icon>';
        
                $tooltip .= '<span class="vds-play-tooltip-text">' . $playLabel . '</span>';
                $tooltip .= '<span class="vds-pause-tooltip-text">' . $pauseLabel . '</span>';
        
                break;
        
            case 'seek-forward':

                $tag = 'media-seek-button';
        
                $seekAmountForward = !empty( $settings['seekAmountForward'] ) ? esc_attr( $settings['seekAmountForward'] ) : '+10';
                $seekLabelForward = !empty( $settings['seekLabelForward'] ) ? esc_attr( $settings['seekLabelForward'] ) : 'Forward';

                $isToolTip = $maybeToolTips;

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

                $this->set_attribute( 'mediaButton', 'seconds', $seekAmountForward );
        
                $button .= $inactiveIcon ? '<div class="vds-icon vds-icon-custom vds-icon-custom-inactive">'  . $inactiveIcon . '</div>' : '<media-icon type="' . $icon . '"></media-icon>';
        
                $tooltip .= '<span class="vds-seek-forward-tooltip-text">' . $seekLabelForward .'</span>';
        
                break;
        
            case 'seek-backward':

                $tag = 'media-seek-button';
        
                $seekAmountBack = !empty( $settings['seekAmountBack'] ) ? esc_attr( $settings['seekAmountBack'] ) : '-10';
                $seekLabelBack = !empty( $settings['seekLabelBack'] ) ? esc_attr( $settings['seekLabelBack'] ) : 'Rewind';

                $isToolTip = $maybeToolTips;

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

                $this->set_attribute( 'mediaButton', 'seconds', $seekAmountBack );
        
                $button .= $inactiveIcon ? '<div class="vds-icon vds-icon-custom vds-icon-custom-inactive">'  . $inactiveIcon . '</div>' : '<media-icon type="' . $icon . '"></media-icon>';
        
                $tooltip .= '<span class="vds-seek-backward-tooltip-text">' . $seekLabelBack . '</span>';
        
                break;
        
            case 'mute':

                $tag = 'media-mute-button';
        
                $unmuteLabel = !empty( $settings['unmuteLabel'] ) ? esc_attr__( $settings['unmuteLabel'] ) : esc_attr__('Unmute');
                $muteLabel = !empty( $settings['muteLabel'] ) ? esc_attr__( $settings['muteLabel'] ) : esc_attr__('Mute');
                $volumeSlider = isset( $settings['volumeSlider'] ) ? esc_attr( $settings['volumeSlider'] ) : 'focus';

                $muteIcon = empty( $settings['muteIcon'] ) ? false : self::render_icon( $settings['muteIcon'] );
                $highVolumeIcon = empty( $settings['highVolumeIcon'] ) ? false : self::render_icon( $settings['highVolumeIcon'] );
                $lowVolumeIcon = empty( $settings['lowVolumeIcon'] ) ? false : self::render_icon( $settings['lowVolumeIcon'] );

              
                $isToolTip = $maybeToolTips;
        
                $button .= $muteIcon ? '<div class="vds-mute-icon vds-icon vds-icon-custom">'  . $muteIcon . '</div>' : '<media-icon class="vds-mute-icon" aria-label="Mute" type="mute"></media-icon>';
                $button .= $highVolumeIcon ? '<div class="vds-volume-high-icon vds-icon vds-icon-custom vds-icon-custom-inactive">'  . $highVolumeIcon . '</div>' : '<media-icon class="vds-volume-high-icon" type="volume-high"></media-icon>';
                $button .= $lowVolumeIcon ? '<div class="vds-volume-low-icon vds-icon vds-icon-custom vds-icon-custom-active">' . $lowVolumeIcon . '</div>' : '<media-icon class="vds-volume-low-icon" type="volume-low"></media-icon>';
        
                $tooltip .= '<span class="vds-mute-tooltip-text">' . $unmuteLabel . '</span>';
                $tooltip .= '<span class="vds-unmute-tooltip-text">' . $muteLabel .'</span>';
        
                break;
        
        
            case 'time':

                $tag = 'div';
                $buttonClass = 'vds-time-group';
        
                $currentTime = isset( $settings['currentTime'] ) ? 'enable' === $settings['currentTime'] : true;
                $duration = isset( $settings['duration'] ) ? 'enable' === $settings['duration'] : true;
                $timeDivider = isset( $settings['timeDivider'] ) ? 'enable' === $settings['timeDivider'] : true;
                $timeDividerText = isset( $settings['timeDividerText'] ) ? esc_attr__( $settings['timeDividerText'] ) : "/";

                $isToolTip = false;
        
                $button .= $currentTime ? '<media-time class="vds-time" type="current" data-type="current">0:00</media-time>' : '';
                $button .= $timeDivider ? '<div class="vds-time-divider">' . $timeDividerText . '</div>' : '';
                $button .= $duration ? '<media-time class="vds-time" type="duration" data-type="duration"></media-time>' : '';
        
                break;
        
        
            case 'pip':

                $tag = 'media-pip-button';
                $buttonClass = 'vds-button';
        
                $enterPipLabel = isset( $settings['enterPipLabel'] ) ? esc_attr__( $settings['enterPipLabel'] ) : esc_attr__( "Enter PIP" );
                $exitPipLabel = isset( $settings['exitPipLabel'] ) ? esc_attr__( $settings['exitPipLabel'] ) : esc_attr__( "Exit PIP" );

                $isToolTip = $maybeToolTips;

                $button .= $inactiveIcon ? '<div class="vds-pip-enter-icon vds-icon vds-icon-custom vds-icon-custom-inactive">'  . $inactiveIcon . '</div>' : '<media-icon class="vds-pip-enter-icon" type="picture-in-picture"></media-icon>';
                $button .= $activeIcon ? '<div class="vds-pip-exit-icon vds-icon vds-icon-custom vds-icon-custom-active">' . $activeIcon . '</div>' : '<media-icon class="vds-pip-exit-icon" type="picture-in-picture-exit"></media-icon>';
        
                $tooltip .= '<span class="vds-pip-enter-tooltip-text">' . $enterPipLabel . '</span>';
                $tooltip .= '<span class="vds-pip-exit-tooltip-text">' . $exitPipLabel . '</span>';
        
                break;
        
            case 'fullscreen':

                $tag = 'media-fullscreen-button';
                $buttonClass = 'vds-button';
        
                $enterFullscreenLabel = isset( $settings['enterFullscreenLabel'] ) ? esc_attr__( $settings['enterFullscreenLabel'] ) : esc_attr__( "Enter Fullscreen" );
                $exitFullscreenLabel = isset( $settings['exitFullscreenLabel'] ) ? esc_attr__( $settings['exitFullscreenLabel'] ) : esc_attr__( "Exit Fullscreen" );

                $isToolTip = $maybeToolTips;

                $button .= $inactiveIcon ? '<div class="vds-fs-enter-icon vds-icon vds-icon-custom vds-icon-custom-inactive">'  . $inactiveIcon . '</div>' : '<media-icon class="vds-fs-enter-icon" type="fullscreen"></media-icon>';
                $button .= $activeIcon ? '<div class="vds-fs-exit-icon vds-icon vds-icon-custom vds-icon-custom-active">' . $activeIcon . '</div>' : '<media-icon class="vds-fs-exit-icon" type="fullscreen-exit"></media-icon>';
        
                $tooltip .= '<span class="vds-fs-enter-tooltip-text">' . $enterFullscreenLabel . '</span>';
                $tooltip .= '<span class="vds-fs-exit-tooltip-text">' . $exitFullscreenLabel . '</span>';
        
                break;

              case 'captions':

                $tag = 'media-caption-button';
                $buttonClass = 'vds-button';

                $isToolTip = $maybeToolTips;

                $button .= $inactiveIcon ? '<div class="vds-cc-on-icon vds-icon vds-icon-custom vds-icon-custom-inactive">'  . $inactiveIcon . '</div>' : '<media-icon class="vds-cc-on-icon" type="closed-captions-on"></media-icon>';
                $button .= $activeIcon ? '<div class="vds-cc-off-icon vds-icon vds-icon-custom vds-icon-custom-active">' . $activeIcon . '</div>' : '<media-icon class="vds-cc-off-icon" type="closed-captions"></media-icon>';
        
                $tooltip .= '<span class="vds-cc-on-tooltip-text">Closed-Captions Off</span>';
                $tooltip .= '<span class="vds-cc-off-tooltip-text">Closed-Captions On</span>';
        
                break;
        
              case 'title':
        
                $isToolTip = false;

                $buttonClass = 'vds-title';

                if ( \BricksExtras\Helpers::maybePreview() ) {
                  $tag = 'media-title';
                  $button .= 'Title Here';
                } else {
                  $tag = 'media-title';
                }

                break;

              case 'chapter-title':
      
                $isToolTip = false;

                $buttonClass = 'vds-chapter-title';

                if ( \BricksExtras\Helpers::maybePreview() ) {
                  $tag = 'medias-chapter-title';
                  $button .= 'Chapter Titles Here';
                } else {
                  $tag = 'media-chapter-title';
                }

                break;
          
              case 'live-button';
          
                $isToolTip = false;

                $tag = 'media-live-button';
                $buttonClass = 'vds-live-button';
        
                $button .= '<span class="vds-live-button-text">LIVE</span>';
      
                break;
        
            case 'settings':
        
                $tag = 'media-menu';
                $buttonClass = 'vds-menu';
        
                $settingsLabel = isset( $settings['settingsLabel'] ) ? esc_attr__( $settings['settingsLabel'] ) : "Settings";

                $isToolTip = false;
                $visibility = isset( $settings['visibility'] ) ? esc_attr( $settings['visibility'] ) : "default";
                $settingsLabel = isset( $settings['settingsLabel'] ) ? esc_attr__( $settings['settingsLabel'] ) : esc_attr__( "Settings" );
                $captionsLabel = isset( $settings['captionsLabel'] ) ? esc_attr__( $settings['captionsLabel'] ) : esc_attr__( "Captions" );
                $chaptersLabel = isset( $settings['chaptersLabel'] ) ? esc_attr__( $settings['chaptersLabel'] ) : esc_attr__( "Chapters" );
                $speedLabel = isset( $settings['speedLabel'] ) ? esc_attr__( $settings['speedLabel'] ) : esc_attr__( "Speed" );
                $qualityLabel = isset( $settings['qualityLabel'] ) ? esc_attr__( $settings['qualityLabel'] ) : esc_attr__( "Quality" );
                $settingsPlacement = isset( $settings['settingsPlacement'] ) ? esc_attr( $settings['settingsPlacement'] ) : "top-end";

                $settingsSpeedIcon = empty( $settings['settingsSpeedIcon'] ) ? false : self::render_icon( $settings['settingsSpeedIcon'] );
                $settingsQualityIcon = empty( $settings['settingsQualityIcon'] ) ? false : self::render_icon( $settings['settingsQualityIcon'] );
                $settingsChaptersIcon = empty( $settings['settingsChaptersIcon'] ) ? false : self::render_icon( $settings['settingsChaptersIcon'] );
                $settingsCaptionsIcon = empty( $settings['settingsCaptionsIcon'] ) ? false : self::render_icon( $settings['settingsCaptionsIcon'] );
        
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
                                <media-tooltip-content class="vds-tooltip-content" placement="' . $defaultTooltipPlacement . '">
                                ' . $settingsLabel . '
                                </media-tooltip-content>
                              </media-tooltip>';
                }
                             
                $button .= '<media-menu-items class="vds-menu-items" placement="' . $settingsPlacement . '">';
        
        
               
                /* speed */
                  $button .= '<media-menu class="vds-speed-menu vds-menu">
                          <media-menu-button class="vds-menu-button" aria-label="'. $speedLabel . '">';
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

                          <media-menu-button class="vds-menu-button" aria-label="'. $qualityLabel . '">';



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
                          <media-icon class="vds-icon vds-menu-button-icon "type="chapters"></media-icon><span class="vds-menu-button-label"> '. $chaptersLabel . '</span>
                          <span class="vds-menu-button-hint" data-part="hint"></span>
                          <media-icon class="vds-menu-button-open-icon" type="chevron-right"></media-icon>
                        </media-menu-button>

                        <media-menu-items class="vds-menu-items">
                          <media-chapters-radio-group class="vds-chapters-radio-group vds-radio-group">
                            <template>
                              <media-radio class="vds-chapter-radio vds-radio">
                                <media-thumbnail></media-thumbnail>
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

              $tag = 'media-time-slider';
              $buttonClass = "vds-time-slider vds-slider";
        
              $isToolTip = false;

              $button .= '<media-slider-chapters class="vds-slider-chapters">
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
                        <div class="vds-slider-chapter-title" data-part="chapter-title"></div>
                        <media-slider-value class="vds-slider-value"></media-slider-value>
                      </media-slider-preview>';


              break;


            case 'custom-text':

                $tag = 'div';
                $buttonClass = 'vds-custom';
        
                $text = isset( $settings['controlText'] ) ? esc_html( $settings['controlText'] ) : "";

                $button = $text;
        
                break;


        }

        //$this->set_attribute( '_root', 'class',  );


    if ($isToolTip) {
        $output .= "<media-tooltip><media-tooltip-trigger>";
        if ('settings' !== $controlType) {
          $output .= "<{$tag} {$this->render_attributes( 'mediaButton' )} class='" . $buttonClass . "'>" ;
          $output .= $button;
          $output .= "</{$tag}>";
        } else {
          $output .= $button;
        }

        $output .= '</media-tooltip-trigger><media-tooltip-content class="vds-tooltip-content" placement="' . $defaultTooltipPlacement . '">';
        $output .= $tooltip;
        $output .= '</media-tooltip-content></media-tooltip>';

        if ('disable' !== $volumeSlider) {
          $volumeVisibility = 'visible' === $volumeSlider ? 'vds-visible-volume' : '';
          $output .= '<media-volume-slider class="vds-slider vds-volume-slider ' . $volumeVisibility . '">';
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

      } elseif ('settings' !== $controlType) {
        $output .= "<{$tag} {$this->render_attributes( 'mediaButton' )} class='" . $buttonClass . "' >" ;
        $output .= $button;
        $output .= "</{$tag}>";
      } else {
        $output .= $button;
      }

      echo "<div {$this->render_attributes( '_root' )}>" . $output . "</div>";
    
  }

  
  /*
  public static function render_builder() { ?>

        <script type="text/x-template" id="tmpl-bricks-element-xmediacontrol">


                <component
                    is="media-seek-button" 
                    v-if="'seek-forward' === settings.controlType" 
                    class="vds-button"
                    seconds="10"
                >
                <media-icon type="seek-forward"></media-icon>
                </component>

                <component
                    is="media-seek-button" 
                    v-else-if="'seek-backward' === settings.controlType" 
                    class="vds-button"
                    seconds="-10"
                >
                <media-icon type="seek-backward"></media-icon>
                </component>

                <component
                    is="media-mute-button" 
                    v-else-if="'mute' === settings.controlType" 
                    class="vds-button"
                >
                <media-icon class="vds-mute-icon" aria-label="Mute" type="mute"></media-icon>
                <media-icon class="vds-volume-low-icon" type="volume-low"></media-icon>
                <media-icon class="vds-volume-high-icon" type="volume-high"></media-icon>
                </component>

                <component
                    is="media-volume-slider" 
                    v-else-if="'volume-slider' === settings.controlType" 
                    class="vds-slider"
                >
                <div class="vds-slider-track"></div>
                <div class="vds-slider-track-fill vds-slider-track"></div>
                <media-slider-preview class="vds-slider-preview">
                <media-slider-value
                                class="vds-slider-value"
                                type="pointer"
                                format="percent"
                ></media-slider-value>
                </media-slider-preview>
                <div class="vds-slider-thumb"></div>
                </component>



                <component
                    is="div" 
                    v-else-if="'time' === settings.controlType" 
                    class="vds-time-group"
                >
                <media-time class="vds-time" type="current" data-type="current">0:00</media-time>
                <div class="vds-time-divider">/</div>
                <media-time class="vds-time" type="duration" data-type="duration"></media-time>
                </component>


                <component
                    is="media-pip-button" 
                    v-else-if="'pip' === settings.controlType" 
                    class="vds-button"
                >
                <media-icon class="vds-pip-enter-icon" type="picture-in-picture"></media-icon>
                <media-icon class="vds-pip-exit-icon" type="picture-in-picture-exit"></media-icon>
                </component>

                <component
                    is="media-fullscreen-button" 
                    v-else-if="'fullscreen' === settings.controlType" 
                    class="vds-button"
                >
                <media-icon class="vds-fs-enter-icon" type="fullscreen"></media-icon>
                <media-icon class="vds-fs-exit-icon" type="fullscreen-exit"></media-icon>
                </component>


                <component
                    is="media-menu" 
                    v-else-if="'settings' === settings.controlType" 
                    class="vds-menu"
                >

                    <!-- Settings Menu Button -->
                        <media-menu-button class="vds-menu-button vds-button">
                          <media-icon class="vds-rotate-icon" type="settings"></media-icon>
                        </media-menu-button>

                    <!-- Settings Menu Items -->
                    <media-menu-items class="vds-menu-items" placement="top end">
                      <media-speed-radio-group class="vds-captions-radio-group vds-radio-group">
                        <template>
                          <media-radio class="vds-radio">
                            <span class="vds-radio-label" data-part="label"></span>
                          </media-radio>
                        </template>
                      </media-speed-radio-group>
                    </media-menu-items>

                </component>

                <component
                    is="div" 
                    v-else-if="'text' === settings.controlType"
                    class="vds-custom"
                >
                    <contenteditable
                        tag="div"
                        class="vds-custom"
                        :name="name"
                        controlKey="controlText"
                        toolbar="style"
                        :settings="settings"
                    />
                </component>

                <component
                    is="media-play-button" 
                    v-else
                    class="vds-button"
                >
                <media-icon class="vds-play-icon" type="play"></media-icon>
                <media-icon class="vds-pause-icon" type="pause"></media-icon>
                </component>
                

                

        </script>

        <?php
    }

    */

}