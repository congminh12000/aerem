<?php
/**
 * Automatic.css Breakdance class file.
 *
 * @package Automatic_CSS
 */

namespace Automatic_CSS\Framework\Platforms;

use Automatic_CSS\CSS_Engine\CSS_File;
use Automatic_CSS\Framework\Base;
use Automatic_CSS\Model\Config\Classes;
use Automatic_CSS\Helpers\Logger;
use Automatic_CSS\Model\Database_Settings;
use Automatic_CSS\Traits\Builder as Builder_Trait;

/**
 * Automatic.css Breakdance class.
 */
class Breakdance extends Base implements Platform, Builder {

	use Builder_Trait {
		in_builder_context as in_builder_context_common;
		in_preview_context as in_preview_context_common;
		in_frontend_context as in_frontend_context_common;
	}

	/**
	 * Instance of the CSS file
	 *
	 * @var CSS_File
	 */
	private $css_file;

	/**
	 * Constructor
	 *
	 * @param array $database_settings The database settings.
	 */
	public function __construct( $database_settings ) {
		$this->builder_prefix = 'breakdance'; // for the Builder trait.
		$this->css_file = $this->add_css_file(
			new CSS_File(
				'automaticcss-breakdance',
				'automatic-breakdance.css',
				array(
					'source_file' => 'platforms/breakdance/automatic-breakdance.scss',
					'imports_folder' => 'platforms/breakdance'
				),
				array(
					'deps' => apply_filters( 'automaticcss_breakdance_deps', array() )
				)
			)
		);
		if ( is_admin() ) {
			add_filter( 'automaticcss_framework_variables', array( $this, 'inject_scss_enabler_option' ) ); // no need for now?
		} else {
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_builder_assets' ) );
		}
	}

	/**
	 * Enqueue the Breakdance assets.
	 *
	 * @return void
	 */
	public function enqueue_builder_assets() {
		if ( self::is_builder_context() ) {
			$this->in_builder_context();
		} else if ( self::is_preview_context() ) {
			$this->in_preview_context();
		} else {
			$this->in_frontend_context();
		}
	}

	/**
	 * Execute code in this Builder's builder context.
	 *
	 * @return void
	 */
	public function in_builder_context() {
		// Call the in builder context actions.
		// This will remove the ACSS scripts & styles from the builder context.
		$this->in_builder_context_common();
	}

	/**
	 * Execute code in this Builder's frontend context.
	 *
	 * @return void
	 */
	public function in_preview_context() {
		// Enqueue the overrides stylesheet.
		$this->css_file->enqueue_stylesheet();
		// Call the preview context actions.
		$this->in_preview_context_common();
	}

	/**
	 * Execute code in this Builder's frontend context.
	 *
	 * @return void
	 */
	public function in_frontend_context() {
		// Enqueue the overrides stylesheet.
		$this->css_file->enqueue_stylesheet();
		// Call the frontend context actions.
		$this->in_frontend_context_common();
	}

	/**
	 * Inject an SCSS variable in the CSS generation process to enable this module.
	 *
	 * @param array $variables The values for the framework's variables.
	 * @return array
	 */
	public function inject_scss_enabler_option( $variables ) {
		$variables['option-breakdance'] = 'on';
		return $variables;
	}

	/**
	 * Check if the plugin is installed and activated.
	 *
	 * @return boolean
	 */
	public static function is_active() {
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		return is_plugin_active( 'breakdance/plugin.php' );
	}

	/**
	 * Are we in Breakdance's builder context?
	 * That means we're in the builder, but not in the preview's iframe.
	 *
	 * @return bool
	 */
	public static function is_builder_context() {
		$is_builder = 'builder' === filter_input( INPUT_GET, 'breakdance' );
		$is_preview = self::is_preview_context();
		return $is_builder && ! $is_preview;
	}

	/**
	 * Are we in Breakdance's iframe context?
	 * That means we're in NOT in the builder, just in the preview's iframe.
	 *
	 * @return bool
	 */
	public static function is_preview_context() {
		$is_preview = (bool) filter_input( INPUT_GET, 'breakdance_iframe' );
		return $is_preview;
	}

	/**
	 * Are we in Breakdance's frontend context?
	 * That means we're in neither in the builder nor in the preview's iframe.
	 *
	 * @return bool
	 */
	public static function is_frontend_context() {
		return ! is_admin() && ! self::is_builder_context() && ! self::is_preview_context();
	}

}
