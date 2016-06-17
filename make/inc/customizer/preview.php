<?php
/**
 * @package Make
 */

/**
 * Class MAKE_Customizer_Preview
 *
 * Configure the Customizer's preview pane.
 *
 * @since 1.7.0.
 */
final class MAKE_Customizer_Preview extends MAKE_Util_Modules implements MAKE_Customizer_PreviewInterface, MAKE_Util_HookInterface {
	/**
	 * An associative array of required modules.
	 *
	 * @since 1.7.0.
	 *
	 * @var array
	 */
	protected $dependencies = array(
		'font'     => 'MAKE_Font_ManagerInterface',
		'thememod' => 'MAKE_Settings_ThemeModInterface',
		'scripts'  => 'MAKE_Setup_ScriptsInterface',
	);

	/**
	 * Indicator of whether the hook routine has been run.
	 *
	 * @since 1.7.0.
	 *
	 * @var bool
	 */
	private static $hooked = false;

	/**
	 * Hook into WordPress.
	 *
	 * @since 1.7.0.
	 *
	 * @return void
	 */
	public function hook() {
		if ( $this->is_hooked() ) {
			return;
		}

		// Setting mods
		add_action( 'customize_register', array( $this, 'setting_mods' ) );

		// Preview pane scripts
		add_action( 'customize_preview_init', array( $this, 'enqueue_preview_scripts' ) );

		// Preview theme mod values
		add_action( 'make_style_before_load', array( $this, 'preview_thememods' ) );
		add_action( 'wp_ajax_make-font-json', array( $this, 'preview_thememods' ), 1 );

		// Register Ajax
		add_action( 'wp_ajax_make-font-json', array( $this, 'get_font_json_ajax' ) );

		// Hooking has occurred.
		self::$hooked = true;
	}

	/**
	 * Check if the hook routine has been run.
	 *
	 * @since 1.7.0.
	 *
	 * @return bool
	 */
	public function is_hooked() {
		return self::$hooked;
	}

	/**
	 * Modifications to core/existing settings.
	 *
	 * @since 1.7.0.
	 *
	 * @hooked action customize_register
	 *
	 * @param WP_Customize_Manager $wp_customize
	 *
	 * @return void
	 */
	public function setting_mods( WP_Customize_Manager $wp_customize ) {
		// Change transport for some core settings
		$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
		$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
	}

	/**
	 * Enqueue scripts for the Customizer preview pane.
	 *
	 * @since 1.7.0.
	 *
	 * @hooked action customize_preview_init
	 *
	 * @return void
	 */
	public function enqueue_preview_scripts() {
		wp_enqueue_script(
			'make-customizer-preview',
			$this->scripts()->get_js_directory_uri() . '/customizer/preview.js',
			array( 'customize-preview' ),
			TTFMAKE_VERSION,
			true
		);

		$data = array(
			'ajaxurl'       => admin_url( 'admin-ajax.php' ),
			'webfonturl'    => esc_url( $this->scripts()->get_url( 'web-font-loader', 'script' ) ),
			'fontSettings'  => array_keys( $this->thememod()->get_settings( 'is_font' ), true ),
			'styleSettings' => array_keys( $this->thememod()->get_settings( 'is_style' ), true ),
		);

		wp_localize_script(
			'make-customizer-preview',
			'MakePreview',
			$data
		);
	}

	/**
	 * Wrapper function for substituting preview values in theme mod settings.
	 *
	 * @since 1.7.0.
	 *
	 * @hooked action make_style_before_load
	 * @hooked action wp_ajax_make-font-json
	 *
	 * @return void
	 */
	public function preview_thememods() {
		if ( ! isset( $_POST['make-preview'] ) ) {
			return;
		}

		$preview = (array) $_POST['make-preview'];

		foreach ( $preview as $setting_id => $value ) {
			add_filter( 'theme_mod_' . sanitize_key( $setting_id ), array( $this, 'preview_thememod_value' ) );
		}
	}

	/**
	 * Return a preview value for a particular theme mod setting.
	 *
	 * @since 1.7.0.
	 *
	 * @hooked filter theme_mod_{$key}
	 *
	 * @param $value
	 *
	 * @return mixed
	 */
	public function preview_thememod_value( $value ) {
		if ( ! isset( $_POST['make-preview'] ) ) {
			return $value;
		}

		$preview = (array) $_POST['make-preview'];
		$setting_id = str_replace( 'theme_mod_', '', current_filter() );

		if ( isset( $preview[ $setting_id ] ) ) {
			return $preview[ $setting_id ];
		}

		return $value;
	}

	/**
	 * Return a JSON string that can be fed into the Web Font Loader in the Customizer preview pane.
	 *
	 * @since 1.7.0.
	 *
	 * @hooked action wp_ajax_make-font-json
	 *
	 * @return void
	 */
	public function get_font_json_ajax() {
		// Only run this during an Ajax request.
		if ( 'wp_ajax_make-font-json' !== current_action() ) {
			return;
		}

		// Get the font values to preview.
		$font_keys = array_keys( $this->thememod()->get_settings( 'is_font' ) );
		$fonts = array();
		foreach ( $font_keys as $font_key ) {
			$font = $this->thememod()->get_value( $font_key );
			if ( $font ) {
				$fonts[] = $font;
			}
		}

		$response = array();

		// Google
		if ( $this->font()->has_source( 'google' ) ) {
			$google_subsets = (array) $this->thememod()->get_value( 'font-subset' );
			$google_data = $this->font()->get_source( 'google' )->build_loader_array( $fonts, $google_subsets );
			if ( ! empty( $google_data ) ) {
				$response = array_merge( $response, $google_data );
			}
		}

		/**
		 * Filter: Modify the preview font data array before it is converted to JSON and sent as an Ajax response.
		 *
		 * @since 1.7.0.
		 *
		 * @param array $response    The array of font data.
		 * @param array $fonts       The font values to preview.
		 */
		$response = apply_filters( 'make_preview_font_data', $response, $fonts );

		// Send the data.
		wp_send_json_success( $response );
	}
}