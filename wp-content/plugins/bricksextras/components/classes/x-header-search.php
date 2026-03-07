<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class X_Header_Search extends \Bricks\Element {

  public $category     = 'extras';
  public $name         = 'xheadersearch';
  public $icon         = 'ti-search';
  public $css_selector = '';
  //public $scripts      = ['xHeaderSearch'];

  public function get_label() {
    return esc_html__( 'Header Search', 'extras' );
  }

  // Set builder control groups
  public function set_control_groups() {

    $this->control_groups['form'] = [
			'title' => esc_html__( 'Form styles', 'extras' ),
			'tab' => 'content',
		];

    $this->control_groups['icons'] = [
			'title' => esc_html__( 'Icons', 'extras' ),
			'tab' => 'content',
		];

  }

  // Set builder controls
  public function set_controls() {


    $this->controls['builderSearchOpen'] = [
      'tab'   => 'content',
      'inline' => true,
      'small' => true,
      //'default' => true,
      'label' => esc_html__( 'Reveal form in builder', 'bricks' ),
      'type'  => 'checkbox',
    ];

    $this->controls['layout'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Search layout', 'bricks' ),
			'type' => 'select',
			'options' => [
			  'header_overlay' => esc_html__( 'Header overlay', 'bricks' ),
			  'below_header' => esc_html__( 'Below header', 'bricks' ),
			  'full_screen' => esc_html__( 'Full screen', 'bricks' ),
			  'expand' => esc_html__( 'Expand', 'bricks' ),
			],
			'inline'      => true,
			'clearable' => false,
			'placeholder' => esc_html__( 'Select', 'bricks' ),
			'default' => 'header_overlay',
		  ];

      $this->controls['expandWidth'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Expand width', 'extras' ),
        'type' => 'number',
        'units' => 'px',
        'inline' => true,
        'css' => [
          [
            'selector' => '.x-search-form',  
            'property' => '--x-headersearch-expand-width',
            ],
        ],
        'placeholder' => esc_html__( '260px', 'bricks' ),
        'required' => ['layout', '=', 'expand'],
      ];

      $this->controls['belowAnimation'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Reveal style', 'bricks' ),
        'type' => 'select',
        'options' => [
          'slide' => esc_html__( 'Slide', 'bricks' ),
          'fade' => esc_html__( 'Fade' ),
        ],
        'inline'      => true,
        //'clearable' => false,
        'placeholder' => esc_html__( 'Slide', 'bricks' ),
        'required' => ['layout', '=', 'below_header'],
        ];


      $this->controls['belowHeaderHeight'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Below header form height', 'extras' ),
        'type' => 'number',
        'units'    => true,
        'css' => [
          [
          'selector' => '[data-type=below_header][aria-expanded=true] + .x-search-form',  
          'property' => '--x-header-slide-height',
          ],
          [
            'selector' => '[data-type=below_header][data-reveal=fade] + .x-search-form',  
            'property' => '--x-header-slide-height',
          ]
        ],
        'placeholder' => '80px',
        'required' => ['layout', '=', 'below_header'],
        ];

        $this->controls['transitionDuration'] = [
          'tab' => 'content',
          'label' => esc_html__( 'Transition duration', 'bricks' ),
          'inline'      => true,
          'small'		  => true,
          'type' => 'number',
          'units'    => true,
          'css' => [
            [
              'selector' => '.x-search-form',  
              'property' => '--x-header-transiton',
            ],
          ],
          //'inlineEditing' => true,
          'placeholder' => '300ms',
          ];

        

        $this->controls['searchWidth'] = [
          'tab' => 'content',
          'label' => esc_html__( 'Search max width', 'bricks' ),
          'type' => 'select',
          'options' => [
            'contentWidth' => esc_html__( 'Content width', 'bricks' ),
            'fullWidth' => esc_html__( 'Full width', 'bricks' ),
            'customWidth' => esc_html__( 'Custom width', 'bricks' )
          ],
          'inline'      => true,
          'clearable' => false,
          'placeholder' => esc_html__( 'Content width', 'bricks' ),
          ];



          $this->controls['searchWidthCustom'] = [
            'tab' => 'content',
            'label' => esc_html__( 'Custom search width', 'extras' ),
            'type' => 'number',
            'units' => 'px',
            'inline' => true,
            'css' => [
              [
                'selector' => '.brxe-container[data-search-width=customWidth]',  
                'property' => 'width',
                ],
            ],
            'placeholder' => esc_html__( '1140px', 'bricks' ),
            'required' => ['searchWidth', '=', 'customWidth'],
          ];

        $this->controls['placeholder'] = [
          'tab' => 'content',
          'label' => esc_html__( 'Placeholder text', 'bricks' ),
          'type' => 'text',
          'placeholder' => esc_attr__( 'Search...', 'bricks' ),
        ];



        /* form styles */

        $form = '.x-search-form';
    
        $this->controls['formStart'] = [
          'tab'   => 'content',
          'group'  => 'form',
          'type'  => 'separator',
          'label'  => esc_html__( 'Form', 'extras' ),
        ];
    
        $this->controls['formBackgroundColor'] = [
          'tab'    => 'content',
          'group'  => 'form',
          'type'   => 'color',
          'label'  => esc_html__( 'Background', 'extras' ),
          'css'    => [
            [
              'property' => 'background-color',
              'selector' => $form,
            ],
          ],
        ];
    
        $this->controls['formBorder'] = [
          'tab'    => 'content',
          'group'  => 'form',
          'type'   => 'border',
          'label'  => esc_html__( 'Border', 'extras' ),
          'css'    => [
            [
              'property' => 'border',
              'selector' => $form,
            ],
          ],
        ];
    
        $this->controls['formBoxShadow'] = [
          'tab'    => 'content',
          'group'  => 'form',
          'label'  => esc_html__( 'Box Shadow', 'extras' ),
          'type'   => 'box-shadow',
          'css'    => [
            [
              'property' => 'box-shadow',
              'selector' => $form,
            ],
          ],
        ];

        $this->controls['formPadding'] = [
          'tab'   => 'content',
          'group' => 'form',
          'label' => esc_html__( 'Padding', 'extras' ),
          'type'  => 'dimensions',
          'placeholder' => [
            'top' => '0',
            'right' => '0',
            'bottom' => '0',
            'left' => '0',
          ],
          'css'   => [
            [
              'property' => 'padding',
              'selector' => $form,
            ],
          ],
        ];




        $this->controls['formInput'] = [
          'tab'   => 'content',
          'group'  => 'form',
          'type'  => 'separator',
          'label'  => esc_html__( 'Input', 'extras' ),
        ];

       

        $this->controls['inputTypography'] = [
          'tab'    => 'content',
          'group'  => 'form',
          'type'   => 'typography',
          'label'  => esc_html__( 'Typography', 'extras' ),
          'css'    => [
            [
              'property' => 'font',
              'selector' => $form . ' input[type=search]',
            ],
            [
              'property' => 'font',
              'selector' => $form,
            ],
          ],
        ];

        $this->controls['inputTypographyPlaceholder'] = [
          'tab'    => 'content',
          'group'  => 'form',
          'type'   => 'typography',
          'label'  => esc_html__( 'Placeholder typography', 'extras' ),
          'css'    => [
            [
              'property' => 'font',
              'selector' => $form . ' input[type=search]::placeholder',
            ],
            [
              'property' => 'font',
              'selector' => $form . ' input[type=search]::-webkit-placeholder',
            ],
          ],
        ];

        $this->controls['inputBackgroundColor'] = [
          'tab'    => 'content',
          'group'  => 'form',
          'type'   => 'color',
          'label'  => esc_html__( 'Background', 'extras' ),
          'css'    => [
            [
              'property' => 'background-color',
              'selector' => $form . ' input[type=search]',
            ],
          ],
        ];
    
        $this->controls['inputBorder'] = [
          'tab'    => 'content',
          'group'  => 'form',
          'type'   => 'border',
          'label'  => esc_html__( 'Border', 'extras' ),
          'css'    => [
            [
              'property' => 'border',
              'selector' => $form . ' input[type=search]',
            ],
          ],
        ];
    
        $this->controls['inputBoxShadow'] = [
          'tab'    => 'content',
          'group'  => 'form',
          'label'  => esc_html__( 'Box Shadow', 'extras' ),
          'type'   => 'box-shadow',
          'css'    => [
            [
              'property' => 'box-shadow',
              'selector' => $form . ' input[type=search]',
            ],
          ],
        ];

        $this->controls['inputPadding'] = [
          'tab'   => 'content',
          'group' => 'form',
          'label' => esc_html__( 'Padding', 'extras' ),
          'type'  => 'dimensions',
          'placeholder' => [
            'top' => '0',
            'right' => '0',
            'bottom' => '0',
            'left' => '0',
          ],
          'css'   => [
            [
              'property' => 'padding',
              'selector' => $form . ' input[type=search]',
            ],
          ],
        ];




        /* icons */

        $searchButton = 'button.x-header-search_toggle-open';
        $closeButton = 'button.x-header-search_toggle-close';

        $this->controls['searchIconStart'] = [
          'tab'   => 'content',
          'group'  => 'icons',
          'type'  => 'separator',
          'label'  => esc_html__( 'Open search icon', 'extras' ),
        ];

      $this->controls['search_icon'] = [
        'tab'      => 'content',
        'group' => 'icons',
        'label'    => esc_html__( 'Choose icon', 'bricks' ),
        'type'     => 'icon',
        'css'      => [
          [
            'selector' => '.x-header-search_toggle-open svg',
          ],
        ],
        'default'  => [
          'library' => 'themify',
          'icon'    => 'ti-search',
        ],
      ];


      $this->controls['aria_label'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Aria label', 'bricks' ),
        'type' => 'text',
        'group' => 'icons',
        'inline' => true,
        'placeholder' => esc_attr__( 'Open search', 'bricks' ),
        ];
    

        $this->controls['iconSize'] = [
          'tab'      => 'content',
          'group'    => 'icons',
          'label'    => esc_html__( 'Icon size', 'bricks' ),
          'type'     => 'number',
          'units'    => true,
          'css'      => [
            [
              'property' => 'font-size',
              'selector' => $searchButton
            ],
          ],
        ];
    
        $this->controls['iconColor'] = [
          'tab'      => 'content',
          'group' => 'icons',
          'label'    => esc_html__( 'Color', 'bricks' ),
          'type'     => 'color',
          'css'      => [
            [
              'property' => 'color',
              'selector' => $searchButton
            ],
          ],
        ];
    
        $this->controls['iconBackgroundColor'] = [
          'tab'   => 'content',
          'group' => 'icons',
          'label' => esc_html__( 'Background color', 'bricks' ),
          'type'  => 'color',
          'css'   => [
            [
              'property' => 'background-color',
              'selector' => $searchButton
            ],
          ],
        ];
    
        $this->controls['iconBorder'] = [
          'tab'   => 'content',
          'label' => esc_html__( 'Border', 'bricks' ),
          'group' => 'icons',
          'type'  => 'border',
          'css'   => [
            [
              'property' => 'border',
              'selector' => $searchButton,
            ],
          ],
        ];
    
        $this->controls['iconBoxShadow'] = [
          'tab'   => 'content',
          'label' => esc_html__( 'Box shadow', 'bricks' ),
          'group' => 'icons',
          'type'  => 'box-shadow',
          'css'   => [
            [
              'property' => 'box-shadow',
              'selector' => $searchButton
            ],
          ],
        ];
    
    
        $this->controls['button_padding'] = [
          'tab' => 'content',
          'group' => 'icons',
          'label' => esc_html__( 'Padding', 'bricks' ),
          'type' => 'dimensions',
          'css' => [
            [
            'property' => 'padding',
            'selector' => $searchButton
            ]
          ],
          'placeholder' => [
            'top' => '10px',
            'right' => '10px',
            'bottom' => '10px',
            'left' => '10px',
          ],
          ];
    
          $this->controls['button_margin'] = [
          'tab' => 'content',
          'group' => 'icons',
          'label' => esc_html__( 'Margin', 'bricks' ),
          'type' => 'dimensions',
          'css' => [
            [
            'property' => 'margin',
            'selector' => $searchButton
            ]
          ],
        ];





      $this->controls['closeIconStart'] = [
        'tab'   => 'content',
        'group'  => 'icons',
        'type'  => 'separator',
        'label'  => esc_html__( 'Close search icon', 'extras' ),
      ];

      $this->controls['maybe_remove_close'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Remove close icon', 'bricks' ),
        'type'  => 'checkbox',
        'group' => 'icons',
      ];



      $this->controls['close_icon'] = [
        'tab'      => 'content',
        'group' => 'icons',
        'label'    => esc_html__( 'Choose icon', 'bricks' ),
        'type'     => 'icon',
        'css'      => [
          [
            'selector' => '.x-header-search_toggle-close svg',
          ],
        ],
        'default'  => [
          'library' => 'themify',
          'icon'    => 'ti-close',
        ],
        'required' => ['maybe_remove_close', '!=', true]
      ];



      $this->controls['close_aria_label'] = [
        'tab' => 'content',
        'label' => esc_html__( 'Aria label', 'bricks' ),
        'type' => 'text',
        'group' => 'icons',
        'inline' => true,
        'placeholder' => esc_attr__( 'Close search', 'bricks' ),
        'required' => ['maybe_remove_close', '!=', true]
        ];
    

        $this->controls['closeIconSize'] = [
          'tab'      => 'content',
          'group'    => 'icons',
          'label'    => esc_html__( 'Icon size', 'bricks' ),
          'type'     => 'number',
          'units'    => true,
          'css'      => [
            [
              'property' => 'font-size',
              'selector' => $closeButton
            ],
          ],
          'required' => ['maybe_remove_close', '!=', true]
        ];
    
        $this->controls['closeIconColor'] = [
          'tab'      => 'content',
          'group' => 'icons',
          'label'    => esc_html__( 'Color', 'bricks' ),
          'type'     => 'color',
          'css'      => [
            [
              'property' => 'color',
              'selector' => $closeButton
            ],
          ],
          'required' => ['maybe_remove_close', '!=', true]
        ];
    
        $this->controls['closeIconBackgroundColor'] = [
          'tab'   => 'content',
          'group' => 'icons',
          'label' => esc_html__( 'Background color', 'bricks' ),
          'type'  => 'color',
          'css'   => [
            [
              'property' => 'background-color',
              'selector' => $closeButton
            ],
          ],
          'required' => ['maybe_remove_close', '!=', true]
        ];
    
        $this->controls['closeIconBorder'] = [
          'tab'   => 'content',
          'label' => esc_html__( 'Border', 'bricks' ),
          'group' => 'icons',
          'type'  => 'border',
          'css'   => [
            [
              'property' => 'border',
              'selector' => $closeButton,
            ],
          ],
          'required' => ['maybe_remove_close', '!=', true]
        ];
    
        $this->controls['closeIconBoxShadow'] = [
          'tab'   => 'content',
          'label' => esc_html__( 'Box shadow', 'bricks' ),
          'group' => 'icons',
          'type'  => 'box-shadow',
          'css'   => [
            [
              'property' => 'box-shadow',
              'selector' => $closeButton
            ],
          ],
          'required' => ['maybe_remove_close', '!=', true]
        ];
    
    
        $this->controls['closeButtonPadding'] = [
          'tab' => 'content',
          'group' => 'icons',
          'label' => esc_html__( 'Padding', 'bricks' ),
          'type' => 'dimensions',
          'css' => [
            [
            'property' => 'padding',
            'selector' => $closeButton
            ]
          ],
          'placeholder' => [
            'top' => '10px',
            'right' => '10px',
            'bottom' => '10px',
            'left' => '10px',
          ],
          'required' => ['maybe_remove_close', '!=', true]
        ];
    
          $this->controls['closeButtonMargin'] = [
          'tab' => 'content',
          'group' => 'icons',
          'label' => esc_html__( 'Margin', 'bricks' ),
          'type' => 'dimensions',
          'css' => [
            [
            'property' => 'margin',
            'selector' => $closeButton
            ]
          ],
          'required' => ['maybe_remove_close', '!=', true]
        ];
    
  }

  // Methods: Frontend-specific
	public function enqueue_scripts() {
		wp_enqueue_script( 'x-header-search', BRICKSEXTRAS_URL . 'components/assets/js/headersearch.js', '', '1.0.1', true );
    if (! \BricksExtras\Helpers::elementCSSAdded($this->name) ) {
			wp_enqueue_style( 'x-header-search', BRICKSEXTRAS_URL . 'components/assets/css/headersearch.css', [], '' );
		  }
	}


  /** 
   * Render element HTML on frontend
   */
  public function render() {

  
    $layout = isset( $this->settings['layout'] ) ? $this->settings['layout'] : '';
    $reveal = isset( $this->settings['belowAnimation'] ) ? $this->settings['belowAnimation'] : 'slide';

    $this->set_attribute( 'x-header-search_toggle-open', 'class', 'x-header-search_toggle-open' );
    $this->set_attribute( 'x-header-search_toggle-open', 'data-type', $layout );
    $this->set_attribute( 'x-header-search_toggle-open', 'data-reveal', $reveal );

    $this->set_attribute( 'x-header-search_toggle-open', 'aria-label', isset( $this->settings['aria_label'] ) ? esc_attr__( $this->settings['aria_label'] ) : esc_attr__( 'Open Search' ) );
    $this->set_attribute( 'x-header-search_toggle-open', 'aria-controls', 'x-header-search_form-' . $this->id );
    $this->set_attribute( 'x-header-search_toggle-open', 'aria-expanded', 'false' );

    $this->set_attribute( 'x-header-search_toggle-close', 'class', 'x-header-search_toggle-close' );
    $this->set_attribute( 'x-header-search_toggle-close', 'aria-label', isset( $this->settings['close_aria_label'] ) ? esc_attr__( $this->settings['close_aria_label'] ) : esc_attr__( 'Close Search' ) );
    $this->set_attribute( 'x-header-search_toggle-close', 'aria-controls', 'x-header-search_form-' . $this->id ); 
    $this->set_attribute( 'x-header-search_toggle-close', 'aria-expanded', 'false' );

    $this->set_attribute( 'x-header-search_toggle-text', 'class', 'x-header-search_toggle-text' );

    $this->set_attribute( '_root', 'data-type', $layout );

    echo "<div {$this->render_attributes( '_root' )}>";

    $search_icon = empty( $this->settings['search_icon'] ) ? false : self::render_icon( $this->settings['search_icon'] );
    $close_icon = empty( $this->settings['close_icon'] ) ? false : self::render_icon( $this->settings['close_icon'] );
    $placeholder = isset( $this->settings['placeholder'] ) ? esc_attr__( $this->settings['placeholder'] ) : esc_attr__( 'Search...' );

    $searchWidth = isset( $this->settings['searchWidth'] ) ? $this->settings['searchWidth'] : 'contentWidth';


   

    if ( $search_icon ) {

      echo "<button {$this->render_attributes( 'x-header-search_toggle-open' )}>";
      echo $search_icon;
			echo '</button>';

		}

      $html = '<form role="search" method="get" class="x-search-form" id="x-header-search_form-' . $this->id . '" action="' . esc_url( home_url( '/' ) ) . '">
                <div data-search-width="' . $searchWidth . '" class="brxe-container">
                  <label>
                    <span class="screen-reader-text">' . $placeholder . '</span>
                    <input type="search" placeholder="' . $placeholder . '" value="' . get_search_query() . '" name="s"> 
                    </label>
                    <input type="submit" class="search-submit" value="Search">';

                  if ( $close_icon && !isset($this->settings['maybe_remove_close'] ) ) {

                    $html .=  "<button {$this->render_attributes( 'x-header-search_toggle-close' )}>";
                    $html .=  $close_icon;
                    $html .=  '</button>';
              
                  }

      $html .=  '</div>
              </form>';

    $output = apply_filters( 'get_search_form', $html );

    echo $output;

    echo "</div>";

  }
    
  

  /**
   * Render element HTML in builder (optional)
   */
  public static function render_builder() { ?>

		<script type="text/x-template" id="tmpl-bricks-element-xheadersearch">
			<component
				class="brxe-xheadersearch"
        :data-type="settings.layout"
			>
				
      <button 
        aria-label="Open search" 
        class="x-header-search_toggle-open"
        
        :data-type="settings.layout"
        :data-reveal="null != settings.belowAnimation ? settings.belowAnimation : 'slide'"
        :aria-expanded="settings.builderSearchOpen ? 'true' : 'false'"
      ><span class="oxy-header-search_toggle-text"></span>

      <icon-svg 
        :iconSettings="settings.search_icon"
      />
      </button>
      <form role="search" method="get" class="x-search-form" action="/">
               <div 
                :data-search-width="settings.searchWidth" 
                class="brxe-container"
                >
                <label>
                  <span class="screen-reader-text">Search ...</span>
                  <input 
                    type="search" 
                    :placeholder="null != settings.placeholder ? settings.placeholder : 'Search...'"
                    value="" 
                    name="s"> 
                  </label>
                  

                  <button 
                      v-if="!settings.maybe_remove_close"
                      aria-label="close search" 
                      class="x-header-search_toggle-close"
                      :data-type="settings.layout"
                      :aria-expanded="settings.builderSearchOpen ? 'true' : 'false'"
                    >

                    <icon-svg 
                      :iconSettings="settings.close_icon"
                    />
                    </button>
              
                 <input type="submit" class="search-submit" value="Search">
                 </div>
              </form>
			</component>	
		</script>

	<?php }
  

}