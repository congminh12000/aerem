<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class X_Shortcode_Wrapper extends \Bricks\Element {

  // Element properties
  public $category     = 'extras';
	public $name         = 'xshortcodewrapper';
	public $icon         = 'ti-shortcode';
	public $css_selector = '';
  public $nestable = true;

  
  public function get_label() {
	return esc_html__( 'Shortcode Wrapper', 'extras' );
  }
  public function set_control_groups() {

    $this->control_groups['content'] = [
      'title' => esc_html__( 'Inner Content', 'extras' ),
      'tab' => 'content',
    ];

  }

  public function set_controls() {

    $this->controls['builderHidden'] = [
			'tab'   => 'content',
			'inline' => true,
			'small' => true,
			'label' => esc_html__( 'Hide content in builder', 'bricks' ),
			'type'  => 'checkbox',
		];

    $this->controls['fullShortcode'] = [
      'tab' => 'content',
      'label' => esc_html__( 'Full shortcode', 'bricks' ),
      'description' => esc_html__( 'Enter both the opening and closing shortcode tags', 'bricks' ),
      'type' => 'text',
      'placeholder' => '[shortcode_open_tag][/shortcode_close_tag]',
    ];

    $this->controls['shortcodeContent'] = [
      'tab' => 'content',
      'group' => 'content',
      'label' => esc_html__( 'Content', 'bricks' ),
      'type' => 'select',
      'options' => [
        'template' => esc_html__( 'Bricks Template', 'bricks' ),
        'wysiwyg' => esc_html__( 'Editor', 'bricks' ),
        'nestable' => esc_html__( 'Nest elements', 'bricks' ),
      ],
      'inline'      => true,
      'placeholder' => esc_html__( 'Editor', 'bricks' ),
      'clearable' => false,
      'default' => 'wysiwyg',
      ];

      if ( !class_exists( '\Bricks\Popups' ) ) {
        $templatesList = bricks_is_builder() ? \Bricks\Templates::get_templates_list( get_the_ID() ) : [];
        } else {
        $templatesList = bricks_is_builder() ? \Bricks\Templates::get_templates_list( [ 'section', 'content' ], get_the_ID() ) : [];
        }

    $this->controls['templateId'] = [
      'tab'         => 'content',
      'group' => 'content',
      'label'       => esc_html__( 'Template', 'bricks' ),
      'type'        => 'select',
      'options'     => $templatesList,
      'searchable'  => true,
      'placeholder' => esc_html__( 'Select template', 'bricks' ),
      'required' => ['shortcodeContent', '!=', ['nestable','wysiwyg']]
    ];

    $this->controls['shortcodeWysiwyg'] = [
      'tab' => 'content',
      'label' => esc_html__( 'Content editor', 'bricks' ),
      'type' => 'editor',
      'group' => 'content',
      'inlineEditing' => [
        'selector' => '.text-editor', // Mount inline editor to this CSS selector
        'toolbar' => true, // Enable/disable inline editing toolbar
      ],
      'default' => '<h4>Conditional content</h4><p>Edit me. Aenean commodo ligula egget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.</p>',
      'required' => ['shortcodeContent', '=', 'wysiwyg']
      ];

  }

  
  public function render() {

    echo "<div {$this->render_attributes( '_root' )}>";

    echo isset( $_SERVER['HTTP_REFERER'] ) && strstr( $_SERVER['HTTP_REFERER'], 'brickspreview' ) && isset( $this->settings['builderHidden'] ) ? '<div class="x_hide_in_builder">' : '';

      $shortcodeContent = isset ( $this->settings['shortcodeContent'] ) ? $this->settings['shortcodeContent'] : 'template';

      $output = ''; 

      $templateId = isset ( $this->settings['templateId'] ) ? intval( $this->settings['templateId'] ) : false;
      $template = do_shortcode( '[bricks_template id="'. $templateId .'"]' );

      $full_shortcode = isset( $this->settings['fullShortcode'] ) ? $this->settings['fullShortcode'] : false;

      $inner_content = '';

      if ( 'template' === $shortcodeContent ) {
        $inner_content = $template;
      } elseif ( 'wysiwyg' === $shortcodeContent ) {     
        $inner_content = isset ( $this->settings['shortcodeWysiwyg'] ) ? $this->settings['shortcodeWysiwyg'] : '';
      } else {
        if ( method_exists('\Bricks\Frontend','render_children') ) {
          $inner_content = \Bricks\Frontend::render_children( $this );
        }
      }

      $shortcode_wrap = [];

      preg_match('/\[([^\s\]]{1,})[^\]]*\]/i', $full_shortcode, $shortcode_wrap);

      $output = '';

      if ( $full_shortcode ) {
        $output = $shortcode_wrap > 0 ? do_shortcode($shortcode_wrap[0] . $inner_content . '[/' . $shortcode_wrap[1] . ']') : '';
      } else {
        $output = $inner_content;
      }
      echo $output;

      echo isset( $_SERVER['HTTP_REFERER'] ) && strstr( $_SERVER['HTTP_REFERER'], 'brickspreview' ) && isset( $this->settings['builderHidden'] ) ? '</div>' : '';  

    echo "</div>";
    
  }

  /*
  public static function render_builder() { ?>

		<script type="text/x-template" id="tmpl-bricks-element-xshortcodewrapper">
   
    <component v-if="!settings.builderHidden">
    
        <component
            :is="'template-static'"
            :templateId="settings.templateId"
            v-if="'template' === settings.shortcodeContent"
          >
          </component>

          <contenteditable
            tag="div"
            v-else-if="'wysiwyg' === settings.shortcodeContent"
            :name="name"
            controlKey="shortcodeWysiwyg"
            toolbar="true"
            :settings="settings"
				/>

          <div
          v-else
          > Nesting elements not yet available </div>
          
       </component>
        
		</script>

	<?php }
  */

}