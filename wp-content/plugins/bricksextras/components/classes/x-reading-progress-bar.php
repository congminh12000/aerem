<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class X_Reading_Progress_Bar extends \Bricks\Element {

  // Element properties
  public $category     = 'extras';
	public $name         = 'xreadingprogressbar';
	public $icon         = 'ti-layout-line-solid';
	public $css_selector = '';

  
  public function get_label() {
	  return esc_html__( 'Reading Progress Bar', 'extras' );
  }
  public function set_control_groups() {

  }

  public function set_controls() {

    $this->controls['containerSelector'] = [
			'tab'         => 'content',
			'type'        => 'text',
			'label' => esc_html__( 'Container selector', 'bricks' ),
			'placeholder' => esc_html__( 'body', 'bricks' ),
			'hasDynamicData' => false,
			'inline'      => true,
		];

    $this->controls['progressPosition'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Progress bar position', 'bricks' ),
			'type' => 'select',
      'inline'      => true,
			'options' => [
			  'positionTop' => esc_html__( 'Top', 'bricks' ),
			  'positionBottom' => esc_html__( 'Bottom', 'bricks' ),
			  'custom' => esc_html__( 'Custom', 'bricks' ),
			],
			'default' => 'positionTop'
		];

    $this->controls['mainsep'] = [
      'tab'   => 'content',
      'type'  => 'separator',
      
    ];


    $this->controls['start'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Start when top of container reaches..', 'bricks' ),
			'type' => 'select',
			'options' => [
			  'top' => esc_html__( 'Top of viewport', 'bricks' ),
			  'middle' => esc_html__( 'Middle of viewport', 'bricks' ),
			  'bottom' => esc_html__( 'Bottom of viewport', 'bricks' ),
			],
			'placeholder' => esc_html__( 'Top of viewport', 'bricks' ),
		];

    $this->controls['end'] = [
			'tab' => 'content',
			'label' => esc_html__( 'End when bottom of container reaches..', 'bricks' ),
			'type' => 'select',
			'options' => [
			  'top' => esc_html__( 'Top of viewport', 'bricks' ),
			  'middle' => esc_html__( 'Middle of viewport', 'bricks' ),
			  'bottom' => esc_html__( 'Bottom of viewport', 'bricks' ),
			],
			'placeholder' => esc_html__( 'Bottom of viewport', 'bricks' ),
		];

    $this->controls['styleSep'] = [
      'tab'   => 'content',
      'type'  => 'separator',
      'label' => esc_html__( 'Styles', 'bricks' ),
    ];


    $this->controls['progressHeight'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Height', 'bricks' ),
			'inline'      => true,
			'type' => 'number',
			'units'    => true,
			'css' => [
			  [
				'selector' => '',  
				'property' => 'height',
			  ],
			],
			'placeholder' => '4px',
		  ];

      $this->controls['background'] = [
        'tab'    => 'content',
        'type'   => 'background',
        'label'  => esc_html__( 'Background', 'bricks' ),
        'css'    => [
          [
            'property' => 'background',
            'selector' => '',
          ],
        ],
      ];


      $this->controls['progressBackground'] = [
        'tab'    => 'content',
        'type'   => 'background',
        'label'  => esc_html__( 'Progress background', 'bricks' ),
        'css'    => [
          [
            'property' => 'background',
            'selector' => '.x-reading-progress-bar_progress',
          ],
        ],
      ];

      $this->controls['progressBorder'] = [
        'tab'    => 'content',
        'type'   => 'border',
        'label'  => esc_html__( 'Progress Border', 'bricks' ),
        'css'    => [
          [
            'property' => 'border',
            'selector' => '.x-reading-progress-bar_progress',
          ],
        ],
      ];

      
  }

  // Methods: Frontend-specific
  public function enqueue_scripts() {
    wp_enqueue_script( 'x-reading-progress-bar', BRICKSEXTRAS_URL . 'components/assets/js/readingprogressbar.js', '', '1.0.0', true );
    if (! \BricksExtras\Helpers::elementCSSAdded($this->name) ) {
			wp_enqueue_style( 'x-reading-progress-bar', BRICKSEXTRAS_URL . 'components/assets/css/readingprogressbar.css', [], '' );
		}
  }
  
  public function render() {

    $config = [
      'position' => isset( $this->settings['progressPosition'] ) ? $this->settings['progressPosition'] : 'positionTop',
    ];

    if ( isset( $this->settings['containerSelector'] ) ) {
      $config['containerSelector'] = $this->settings['containerSelector'];
    }

    if ( isset( $this->settings['start'] ) ) {
      $config['start'] = $this->settings['start'];
    }

    if ( isset( $this->settings['end'] ) ) {
      $config['end'] = $this->settings['end'];
    }

    $this->set_attribute( '_root', 'class', 'x-reading-progress-bar' );

    if ( is_array( $config ) ) {
      $this->set_attribute( '_root', 'data-x-progress', wp_json_encode( $config ) );
		}
   
    $this->set_attribute( 'x-reading-progress-bar_progress', 'class', 'x-reading-progress-bar_progress' );

    

    echo "<div {$this->render_attributes( '_root' )}>";
      echo "<div {$this->render_attributes( 'x-reading-progress-bar_progress' )}>";
      echo "</div>";
    echo "</div>";

    
  }

  public static function render_builder() { ?>

		<script type="text/x-template" id="tmpl-bricks-element-xreadingprogressbar">

    <component
					class="x-reading-progress-bar"
          :data-x-progress="settings.progressPosition"
			>
      <div class="x-reading-progress-bar_progress"></div>
    </component>  
			
		</script>

	<?php }

}