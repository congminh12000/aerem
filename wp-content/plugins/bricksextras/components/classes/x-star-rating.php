<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class X_Star_Rating extends \Bricks\Element {

  // Element properties
  public $category     = 'extras';
	public $name         = 'xstarrating';
	public $icon         = 'ion-md-star-outline';
	public $css_selector = '';

  
  public function get_label() {
	  return esc_html__( 'Star Rating', 'extras' );
  }
  public function set_control_groups() {

  }

  public function set_controls() {

    $this->controls['starRating'] = [
			'tab' => 'content',
			'label' => esc_html__( 'Star Rating', 'bricks' ),
			'type' => 'text',
			'min' => 0,
			'step' => 1, 
			'inline' => true,
			'placeholder' => esc_html__( '4', 'bricksextras' ),
		  ];

	$this->controls['totalStars'] = [
		'tab' => 'content',
		'label' => esc_html__( 'Total stars', 'bricks' ),
		'type' => 'text',
		'min' => 0,
		'step' => 1, 
		'inline' => true,
		'placeholder' => esc_html__( '5', 'bricksextras' ),
		];	  

    $this->controls['markedIcon'] = [
			'tab'     => 'content',
			'label'   => esc_html__( 'Marked icon', 'bricks' ),
			'type'    => 'icon',
			'css'     => [
				[
					'selector' => '.x-star-rating_star-marked > *',
				],
			],
			'default' => [
				'library' => 'fontawesomeSolid',
				'icon'    => 'fas fa-star',
			],
		];

    $this->controls['halfmarkedIcon'] = [
			'tab'     => 'content',
			'label'   => esc_html__( 'Half marked icon', 'bricks' ),
			'type'    => 'icon',
			'css'     => [
				[
					'selector' => '.x-star-rating_star-half-marked > *', 
				],
			],
			'default' => [
				'library' => 'fontawesomeSolid',
				'icon'    => 'fas fa-star-half-stroke',
			],
		];

    $this->controls['icon'] = [
			'tab'     => 'content',
			'label'   => esc_html__( 'Empty Icon', 'bricks' ),
			'type'    => 'icon',
			'css'     => [
				[
					'selector' => '.x-star-rating_star > *', 
				],
			],
			'default' => [
				'library' => 'fontawesomeRegular',
				'icon'    => 'fa fa-star',
			],
		];

		$this->controls['iconColor'] = [
			'tab'      => 'content',
			'label'    => esc_html__( 'Color', 'bricks' ),
			'type'     => 'color',
			'css'      => [
				[
					'property' => 'color',
				],
			],
			
		];

		$this->controls['iconSize'] = [
			'tab'         => 'content',
			'label'       => esc_html__( 'Icon size', 'bricks' ),
			'type'        => 'number',
			'units'       => true,
			'css'         => [
				[
					'property' => 'font-size',
          			'selector' => 'i'
				],
			],
			'placeholder' => '20px',
			
		];

		$this->controls['iconGap'] = [
			'tab'         => 'content',
			'label'       => esc_html__( 'Gap between stars', 'bricks' ),
			'type'        => 'number',
			'units'       => true,
			'css'         => [
				[
					'property' => 'gap',
          			'selector' => ''
				],
			],
			'placeholder' => '0',
			
		];


    $this->controls['iconMargin'] = [
			'tab'   => 'content',
			'label' => esc_html__( 'Star margin', 'extras' ),
			'type'  => 'dimensions',
			'css'   => [
				[
					'property' => 'margin',
					'selector' => 'i'
				],
			],
		];

  }

  public function enqueue_scripts() {
	if (! \BricksExtras\Helpers::elementCSSAdded($this->name) ) {
		wp_enqueue_style( 'x-star-rating', BRICKSEXTRAS_URL . 'components/assets/css/starrating.css', [], '' );
	}
  }
  
  public function render() {

	$this->set_attribute( 'x-star-rating_star-marked', 'class', 'x-star-rating_star-marked' );
	$this->set_attribute( 'x-star-rating_star-half-marked', 'class', 'x-star-rating_star-half-marked' );
	$this->set_attribute( 'x-star-rating_star', 'class', 'x-star-rating_star' );

    $settings = $this->settings;
	$icon     = ! empty( $settings['icon'] ) ? "<div {$this->render_attributes( 'x-star-rating_star' )}>" . self::render_icon( $settings['icon'] ) . "</div>" : false;
    $markedIcon     = ! empty( $settings['markedIcon'] ) ? "<div {$this->render_attributes( 'x-star-rating_star-marked' )}>" . self::render_icon( $settings['markedIcon'] ) . "</div>" : false;
    $halfmarkedIcon = ! empty( $settings['halfmarkedIcon'] ) ? "<div {$this->render_attributes( 'x-star-rating_star-half-marked' )}>" . self::render_icon( $settings['halfmarkedIcon'] ) . "</div>" : false;

    $starRatingSetting = isset( $settings['starRating'] ) ? $settings['starRating'] : 4;
    $totalStarsSetting = isset( $settings['totalStars'] ) ? $settings['totalStars'] : 5;

	$starRating = strstr( $starRatingSetting, '{') ? $this->render_dynamic_data_tag( $starRatingSetting, 'text' ) : $starRatingSetting;
	$totalStars = strstr( $totalStarsSetting, '{') ? $this->render_dynamic_data_tag( $totalStarsSetting, 'text' ) : $totalStarsSetting;

    $ariaLabel = esc_attr__( "Rating: " . $starRating . " out of " . $totalStars . " stars" );

    $this->set_attribute( '_root', 'aria-label', $ariaLabel );
    $this->set_attribute( '_root', 'role', 'img' );
	$this->set_attribute( '_root', 'data-x-star-rating', $starRating );
	
	$starRating = round( floatval( $starRating  ) * 2 ) / 2;

    echo "<div {$this->render_attributes( '_root' )}>";

	if ( is_numeric( $starRating ) && is_numeric( $totalStars ) ) {

		echo $totalStars >= $starRating ? str_repeat( $markedIcon, floor($starRating) ) : str_repeat( $markedIcon, $totalStars );

		echo ( $starRating * 2 ) % 2 != 0 ? $halfmarkedIcon : '';

		if ( $totalStars - $starRating > 0 ) {
			echo str_repeat( $icon, $totalStars - round($starRating ));
		} 
	}

    echo "</div>";
    
  }

}