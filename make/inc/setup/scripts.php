<?php
/**
 * @package Make
 */

/**
 * Class MAKE_Setup_Scripts
 *
 * Methods for managing and enqueueing script and style assets.
 *
 * @since 1.7.0.
 */
final class MAKE_Setup_Scripts extends MAKE_Util_Modules implements MAKE_Setup_ScriptsInterface, MAKE_Util_HookInterface {
	/**
	 * An associative array of required modules.
	 *
	 * @since 1.7.0.
	 *
	 * @var array
	 */
	protected $dependencies = array(
		'compatibility' => 'MAKE_Compatibility_MethodsInterface',
		'plus'          => 'MAKE_Plus_MethodsInterface',
		'font'          => 'MAKE_Font_ManagerInterface',
		'thememod'      => 'MAKE_Settings_ThemeModInterface',
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

		// Register style and script libs
		add_action( 'wp_enqueue_scripts', array( $this, 'register_libs' ), 1 );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_libs' ), 1 );
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'register_libs' ), 1 );
		add_action( 'customize_preview_init', array( $this, 'register_libs' ), 1 );

		// Enqueue front end styles and scripts.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_scripts' ) );

		// Enqueue admin styles and scripts.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ), 20 );
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_admin_styles' ), 20 );
		add_action( 'admin_enqueue_scripts', array( $this, 'add_editor_styles' ) );

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
	 * Wrapper for getting the path to the theme's CSS directory.
	 *
	 * @since 1.7.0.
	 *
	 * @return string
	 */
	public function get_css_directory() {
		return get_template_directory() . '/css';
	}

	/**
	 * Wrapper for getting the URL for the theme's CSS directory.
	 *
	 * @since 1.7.0.
	 *
	 * @return string
	 */
	public function get_css_directory_uri() {
		return get_template_directory_uri() . '/css';
	}

	/**
	 * Wrapper for getting the path to the theme's JS directory.
	 *
	 * @since 1.7.0.
	 *
	 * @return string
	 */
	public function get_js_directory() {
		return get_template_directory() . '/js';
	}

	/**
	 * Wrapper for getting the URL for the theme's JS directory.
	 *
	 * @since 1.7.0.
	 *
	 * @return string
	 */
	public function get_js_directory_uri() {
		return get_template_directory_uri() . '/js';
	}

	/**
	 * Wrapper function to register style and script libraries for usage throughout the site.
	 *
	 * @since 1.7.0.
	 *
	 * @hooked action wp_enqueue_scripts
	 * @hooked action admin_enqueue_scripts
	 * @hooked action customize_controls_enqueue_scripts
	 * @hooked action customize_preview_init
	 *
	 * @return void
	 */
	public function register_libs() {
		$this->register_style_libs();
		$this->register_script_libs();
	}

	/**
	 * Register style libraries.
	 *
	 * @since 1.7.0.
	 *
	 * @return void
	 */
	private function register_style_libs() {
		// Chosen
		wp_register_style(
			'chosen',
			$this->get_css_directory_uri() . '/libs/chosen/chosen.min.css',
			array(),
			'1.5.1'
		);

		// Editor styles
		wp_register_style(
			'make-editor',
			$this->get_located_file_url( array( 'editor-style.css', 'css/editor-style.css' ) ),
			array(),
			TTFMAKE_VERSION,
			'screen'
		);

		// Font Awesome
		wp_register_style(
			'font-awesome',
			$this->get_css_directory_uri() . '/libs/font-awesome/css/font-awesome.min.css',
			array(),
			'4.6.1'
		);

		// Google Fonts
		if ( ! is_customize_preview() && $url = $this->get_google_url() ) {
			wp_register_style(
				'make-google-font',
				$url,
				array(),
				TTFMAKE_VERSION
			);
		}

		// jQuery UI
		wp_register_style(
			'make-jquery-ui-custom',
			$this->get_css_directory_uri() . '/libs/jquery-ui/jquery-ui-1.10.4.custom.css',
			array(),
			'1.10.4'
		);
	}

	/**
	 * Register JavaScript libraries.
	 *
	 * @since 1.7.0.
	 *
	 * @return void
	 */
	private function register_script_libs() {
		// Chosen
		wp_register_script(
			'chosen',
			$this->get_js_directory_uri() . '/libs/chosen/chosen.jquery.min.js',
			array( 'jquery' ),
			'1.5.1',
			true
		);

		// Cycle2
		if ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) {
			// Core script
			wp_register_script(
				'cycle2',
				$this->get_js_directory_uri() . '/libs/cycle2/jquery.cycle2.js',
				array( 'jquery' ),
				'2.1.6',
				true
			);

			// Vertical centering
			wp_register_script(
				'cycle2-center',
				$this->get_js_directory_uri() . '/libs/cycle2/jquery.cycle2.center.js',
				array( 'cycle2' ),
				'20140121',
				true
			);

			// Swipe support
			wp_register_script(
				'cycle2-swipe',
				$this->get_js_directory_uri() . '/libs/cycle2/jquery.cycle2.swipe.js',
				array( 'cycle2' ),
				'20121120',
				true
			);
		} else {
			wp_register_script(
				'cycle2',
				$this->get_js_directory_uri() . '/libs/cycle2/jquery.cycle2.min.js',
				array( 'jquery' ),
				'2.1.6',
				true
			);
		}

		// FitVids
		wp_register_script(
			'fitvids',
			$this->get_js_directory_uri() . '/libs/fitvids/jquery.fitvids.js',
			array( 'jquery' ),
			'1.1-d028a22',
			true
		);

		// Web Font Loader
		wp_register_script(
			'web-font-loader',
			'//ajax.googleapis.com/ajax/libs/webfont/1/webfont.js'
		);
	}

	/**
	 * Enqueue styles for the front end of the site.
	 *
	 * @since 1.7.0.
	 *
	 * @hooked action wp_enqueue_scripts
	 *
	 * @return void
	 */
	public function enqueue_frontend_styles() {
		// Parent stylesheet, if child theme is active
		// @link http://justintadlock.com/archives/2014/11/03/loading-parent-styles-for-child-themes
		if ( is_child_theme() && defined( 'TTFMAKE_CHILD_VERSION' ) && version_compare( TTFMAKE_CHILD_VERSION, '1.1.0', '>=' ) ) {
			/**
			 * Filter: Toggle whether the parent stylesheet loads along with the child one.
			 *
			 * @since 1.6.0.
			 *
			 * @param bool $enqueue    True enqueues the parent stylesheet.
			 */
			if ( true === apply_filters( 'make_enqueue_parent_stylesheet', true ) ) {
				wp_enqueue_style(
					'make-parent',
					get_template_directory_uri() . '/style.css',
					array(),
					TTFMAKE_VERSION
				);
			}
		}

		// Main stylesheet
		wp_enqueue_style(
			'make-main',
			get_stylesheet_uri(),
			array(),
			TTFMAKE_VERSION
		);

		// Add stylesheet dependencies
		$stylesheet = ( $this->is_registered( 'make-parent', 'style' ) ) ? 'make-parent' : 'make-main';
		$this->add_dependency( $stylesheet, 'make-google-font', 'style' );
		$this->add_dependency( $stylesheet, 'font-awesome', 'style' );

		// Print stylesheet
		if ( $url = $this->get_located_file_url( array( 'print.css', 'css/print.css' ) ) ) {
			wp_enqueue_style(
				'make-print',
				$url,
				array( 'make-main' ),
				TTFMAKE_VERSION,
				'print'
			);
		}
	}

	/**
	 * Enqueue styles for the site admin.
	 *
	 * @since 1.7.0.
	 *
	 * @hooked action admin_enqueue_scripts
	 * @hooked action customize_controls_enqueue_scripts
	 *
	 * @return void
	 */
	public function enqueue_admin_styles() {
		if ( ! $this->plus()->is_plus() ) {
			wp_enqueue_style(
				'make-plus',
				$this->get_css_directory_uri() . '/plus/plus.css',
				array(),
				TTFMAKE_VERSION
			);
		}
	}

	/**
	 * Add stylesheet URLs to be loaded into the Visual Editor's iframe.
	 *
	 * @since 1.7.0.
	 *
	 * @hooked action admin_enqueue_scripts
	 *
	 * @return void
	 */
	public function add_editor_styles() {
		$editor_styles = array();

		foreach ( array(
			'make-google-font',
			'font-awesome',
			'make-editor'
		) as $lib ) {
			if ( $this->is_registered( $lib, 'style' ) ) {
				$editor_styles[] = $this->get_url( $lib, 'style' );
			}
		}

		add_editor_style( $editor_styles );
	}

	/**
	 * Enqueue scripts for the front end of the site.
	 *
	 * @since 1.7.0.
	 *
	 * @hooked action wp_enqueue_scripts
	 *
	 * @return void
	 */
	public function enqueue_frontend_scripts() {
		// Main script
		wp_enqueue_script(
			'make-frontend',
			$this->get_located_file_url( array( 'frontend.js', 'js/frontend.js' ) ),
			array( 'jquery' ),
			TTFMAKE_VERSION,
			true
		);

		// Define JS data
		$data = array(
			'fitvids' => $this->get_fitvids_selectors()
		);

		// Add JS data
		wp_localize_script(
			'make-frontend',
			'MakeFrontEnd',
			$data
		);

		// Comment reply script
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}
	
	/**
	 * Get the URL of a theme file.
	 *
	 * Looks for the file in a child theme first, then in the parent theme.
	 *
	 * @since 1.7.0.
	 *
	 * @uses locate_template()
	 *
	 * @param string|array $file_names    File(s) to search for, in order.
	 *
	 * @return string                     The file URL if one is located.
	 */
	public function get_located_file_url( $file_names ) {
		$url = '';

		$located = locate_template( $file_names );
		if ( '' !== $located ) {
			if ( 0 === strpos( $located, get_stylesheet_directory() ) ) {
				$url = str_replace( get_stylesheet_directory(), get_stylesheet_directory_uri(), $located );
			} else if ( 0 === strpos( $located, get_template_directory() ) ) {
				$url = str_replace( get_template_directory(), get_template_directory_uri(), $located );
			}
		}

		/**
		 * Filter: Modify the URL the theme will use to attempt to access a particular file.
		 *
		 * This can be used to set the URL for a file if the get_located_file_url() method is not
		 * determining the correct URL.
		 *
		 * @since 1.7.0.
		 *
		 * @param string       $url
		 * @param string|array $file_names
		 */
		return apply_filters( 'make_located_file_url', $url, $file_names );
	}

	/**
	 * Return a specified style or script object.
	 *
	 * @since 1.7.0.
	 *
	 * @param string $dependency_id The ID of the dependency object.
	 * @param string $type          The type of dependency object. Valid options are style and script.
	 *
	 * @return _WP_Dependency|null
	 */
	private function get_dependency_object( $dependency_id, $type ) {
		switch ( $type ) {
			case 'style' :
				global $wp_styles;
				if ( $wp_styles instanceof WP_Styles ) {
					$style = $wp_styles->query( $dependency_id, 'registered' );
					if ( $style instanceof _WP_Dependency ) {
						return $style;
					}
				}
				break;

			case 'script' :
				global $wp_scripts;
				if ( $wp_scripts instanceof WP_Scripts ) {
					$script = $wp_scripts->query( $dependency_id, 'registered' );
					if ( $script instanceof _WP_Dependency ) {
						return $script;
					}
				}
				break;
		}

		return null;
	}

	/**
	 * Check if a specified style or script object has been registered.
	 *
	 * @since 1.7.0.
	 *
	 * @param string $dependency_id The ID of the dependency object.
	 * @param string $type          The type of dependency object. Valid options are style and script.
	 *
	 * @return bool                 True if the object has been registered.
	 */
	public function is_registered( $dependency_id, $type ) {
		return ! is_null( $this->get_dependency_object( $dependency_id, $type ) );
	}

	/**
	 * Add a dependency to a specified style or script object.
	 *
	 * @since 1.7.0.
	 *
	 * @param string $recipient_id  The ID of the object to add a dependency to.
	 * @param string $dependency_id The ID of the object to add as a dependency.
	 * @param string $type          The type of dependency object. Valid options are style and script.
	 *
	 * @return bool                 True if a dependency was successfully added. Otherwise false.
	 */
	public function add_dependency( $recipient_id, $dependency_id, $type ) {
		if ( $this->is_registered( $recipient_id, $type ) && $this->is_registered( $dependency_id, $type ) ) {
			$obj = $this->get_dependency_object( $recipient_id, $type );
			if ( ! in_array( $dependency_id, $obj->deps ) ) {
				$obj->deps[] = $dependency_id;
				return true;
			}
		}

		return false;
	}

	/**
	 * Remove a dependency from a specified style or script object.
	 *
	 * @since 1.7.0.
	 *
	 * @param string $recipient_id  The ID of the object to remove a dependency from.
	 * @param string $dependency_id The ID of the dependency object to remove.
	 * @param string $type          The type of dependency object. Valid options are style and script.
	 *
	 * @return bool                 True if the dependency existed and was removed. Otherwise false.
	 */
	public function remove_dependency( $recipient_id, $dependency_id, $type ) {
		if ( $this->is_registered( $recipient_id, $type ) ) {
			$obj = $this->get_dependency_object( $recipient_id, $type );
			if ( false !== $key = array_search( $dependency_id, $obj->deps ) ) {
				unset( $obj->deps[ $key ] );
				return true;
			}
		}

		return false;
	}

	/**
	 * Update the version property of a specified style or script object.
	 *
	 * @since 1.7.0.
	 *
	 * @param string $recipient_id The ID of the object to update.
	 * @param string $version      The new version to add to the object.
	 * @param string $type         The type of dependency object. Valid options are style and script.
	 *
	 * @return bool                True if the version was successfully updated. Otherwise false.
	 */
	public function update_version( $recipient_id, $version, $type ) {
		if ( $this->is_registered( $recipient_id, $type ) ) {
			$obj = $this->get_dependency_object( $recipient_id, $type );
			$obj->ver = $this->sanitize_version( $version );
			return true;
		}

		return false;
	}

	/**
	 * Return the URL of a specified registered style or script.
	 *
	 * @since 1.7.0.
	 *
	 * @param string $dependency_id The ID of the style or script to determine the URL of.
	 * @param string $type          The type of dependency object. Valid options are style and script.
	 *
	 * @return string               The URL, or an empty string.
	 */
	public function get_url( $dependency_id, $type ) {
		$url = '';

		if ( $this->is_registered( $dependency_id, $type ) ) {
			$obj = $this->get_dependency_object( $dependency_id, $type );
			$url = add_query_arg( 'ver', $obj->ver, $obj->src );
		}

		return $url;
	}

	/**
	 * Restrict the characters allowed in a version string.
	 *
	 * @since 1.7.0.
	 *
	 * @param string $version The version string to sanitize.
	 *
	 * @return string         The sanitized version string.
	 */
	private function sanitize_version( $version ) {
		return preg_replace( '/[^A-Za-z0-9\-_\.]+/', '', $version );
	}

	/**
	 * Return the URL for loading the Google fonts currently used in the theme.
	 *
	 * @since 1.7.0.
	 *
	 * @param bool $force   True to generate the URL from scratch, rather than preferencing a saved value in the database.
	 *
	 * @return string       The URL, or an empty string.
	 */
	public function get_google_url( $force = false ) {
		$setting_id = 'google-font-url';

		if ( ! $this->thememod()->setting_exists( $setting_id ) || ! $this->font()->has_source( 'google' ) ) {
			return '';
		} else if ( true !== $force && $this->thememod()->get_raw_value( $setting_id ) ) {
			return $this->thememod()->get_value( $setting_id );
		}

		// Get fonts
		$font_keys = array_keys( $this->thememod()->get_settings( 'is_font' ) );
		$fonts = array();

		foreach ( $font_keys as $font_key ) {
			$font = $this->thememod()->get_value( $font_key );
			if ( $font ) {
				$fonts[] = $font;
			}
		}

		// Get subsets
		$subsets = (array) $this->thememod()->get_value( 'font-subset' );

		// Generate the URL
		$url = $this->font()->get_source( 'google' )->build_url( $fonts, $subsets );

		// Cache the URL
		$this->thememod()->set_value( $setting_id, $url );

		return $url;
	}

	/**
	 * Generate a string of jQuery selectors used by the FitVids.js script.
	 *
	 * @since 1.7.0.
	 *
	 * @return array
	 */
	private function get_fitvids_selectors() {
		/**
		 * Filter: Allow customization of the selectors that are used to apply FitVids.
		 *
		 * @since 1.2.3.
		 *
		 * @param array $selector_array    The selectors used by FitVids.
		 */
		$selector_array = apply_filters( 'make_fitvids_custom_selectors', array(
			"iframe[src*='www.viddler.com']",
			"iframe[src*='money.cnn.com']",
			"iframe[src*='www.educreations.com']",
			"iframe[src*='//blip.tv']",
			"iframe[src*='//embed.ted.com']",
			"iframe[src*='//www.hulu.com']",
		) );

		// Compile selectors
		return array(
			'selectors' => implode( ',', $selector_array )
		);
	}
}