<?php
/**
 * @package Make
 */

/**
 * Class MAKE_Formatting_Manager
 *
 * Adds TinyMCE plugins for formatting options and tools in the editor.
 *
 * @since 1.4.1.
 * @since 1.7.0. Changed class name from TTFMAKE_Formatting
 */
class MAKE_Formatting_Manager extends MAKE_Util_Modules implements MAKE_Formatting_ManagerInterface, MAKE_Util_HookInterface {
	/**
	 * An associative array of required modules.
	 *
	 * @since 1.7.0.
	 *
	 * @var array
	 */
	protected $dependencies = array(
		'error'    => 'MAKE_Error_CollectorInterface',
		'thememod' => 'MAKE_Settings_ThemeModInterface',
		'scripts'  => 'MAKE_Setup_ScriptsInterface',
	);

	/**
	 * An associative array of definitions for the Format Builder.
	 *
	 * @since 1.7.0.
	 *
	 * @var array
	 */
	private $formats = array();

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

		// Register styles and scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'register_styles_scripts' ), 9 );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_styles_scripts' ), 9 );

		if ( is_admin() && ( current_user_can( 'edit_posts' ) || current_user_can( 'edit_pages' ) ) ) {
			// Add formats
			add_action( 'admin_enqueue_scripts', array( $this, 'add_formats' ), 8 );

			// Enqueue admin styles and scripts for plugin functionality
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

			// Add plugins and buttons
			add_filter( 'mce_external_plugins', array( $this, 'register_plugins' ) );
			add_filter( 'mce_buttons', array( $this, 'register_buttons_1' ) );
			add_filter( 'mce_buttons_2', array( $this, 'register_buttons_2' ) );

			// Reposition the hr button
			add_filter( 'tiny_mce_before_init', array( $this, 'reposition_hr' ), 20, 2 );

			// Add translations for plugins
			add_filter( 'wp_mce_translation', array( $this, 'add_translations' ), 10, 2 );

			// Add items to the Formats dropdown
			add_filter( 'tiny_mce_before_init', array( $this, 'formats_dropdown_items' ) );
		}

		// Enqueue front end scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_scripts' ) );

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
	 * Add a format definition for use with the Format Builder.
	 *
	 * See one of the built-in format models for an example of what the script file should contain.
	 *
	 * @since 1.7.0.
	 *
	 * @param string $format_name
	 * @param string $script_uri
	 * @param string $script_version
	 *
	 * @return bool
	 */
	public function add_format( $format_name, $script_uri, $script_version = '' ) {
		$format_name = sanitize_key( $format_name );
		$return = true;

		if ( isset( $this->formats[ $format_name ] ) ) {
			$this->error()->add_error( 'make_format_already_exists', sprintf(
				__( 'The "%s" format can\'t be added because it already exists.', 'make' ),
				esc_html( $format_name )
			) );
			$return = false;
		} else {
			$format_data = array(
				'uri'     => esc_url( $script_uri ),
				'version' => esc_html( $script_version ),
			);

			$this->formats[ $format_name ] = $format_data;
		}

		return $return;
	}

	/**
	 * Remove a format definition from the Format Builder.
	 *
	 * @since 1.7.0.
	 *
	 * @param string $format_name
	 *
	 * @return bool
	 */
	public function remove_format( $format_name ) {
		$format_name = sanitize_key( $format_name );
		$return = true;

		if ( isset( $this->formats[ $format_name ] ) ) {
			unset( $this->formats[ $format_name ] );
		} else {
			$this->error()->add_error( 'make_format_does_not_exist', sprintf(
				__( 'The "%s" format can\'t be removed because it doesn\'t exist.', 'make' ),
				esc_html( $format_name )
			) );
			$return = false;
		}

		return $return;
	}

	/**
	 * Add the built-in formats to the Format Builder.
	 *
	 * @since 1.7.0.
	 *
	 * @hooked action admin_enqueue_scripts
	 *
	 * @return void
	 */
	public function add_formats() {
		$formats_uri = $this->scripts()->get_js_directory_uri() . '/formatting/format-builder/models';

		// Button
		$this->add_format( 'button', $formats_uri . '/button.js' );

		// List
		$this->add_format( 'list', $formats_uri . '/list.js' );

		// Notice
		$this->add_format( 'notice', $formats_uri . '/notice.js' );

		// Check for deprecated filter
		if ( has_filter( 'make_format_builder_format_models' ) ) {
			$this->compatibility()->deprecated_hook(
				'make_format_builder_format_models',
				'1.7.0',
				sprintf(
					__( 'To add or modify Format Builder formats, use the %1$s method instead. See %2$s.', 'make' ),
					'<code>add_format()</code>',
					'<code>MAKE_Formatting_Manager</code>'
				)
			);

			/**
			 * Filter the format model definitions and their script locations.
			 *
			 * model => URI of the model's script file
			 *
			 * @since 1.4.1
			 * @deprecated 1.7.0
			 *
			 * @param array    $models    The array of format models.
			 */
			$this->formats = apply_filters( 'make_format_builder_format_models', $this->formats );
		}

		/**
		 * Action: Fires at the end of the Formatting object's add_formats method.
		 *
		 * This action gives a developer the opportunity to add or remove formats.
		 *
		 * @since 1.7.0.
		 *
		 * @param MAKE_Formatting_Manager $formatting     The Formatting object.
		 */
		do_action( 'make_add_formats', $this );
	}

	/**
	 * Add plugins to TinyMCE.
	 *
	 * @since 1.4.1.
	 *
	 * @hooked filter mce_external_plugins
	 *
	 * @param array $plugins
	 *
	 * @return mixed
	 */
	public function register_plugins( $plugins ) {
		// Format Builder
		$plugins['ttfmake_format_builder'] = $this->scripts()->get_url( 'make-format-builder-plugin', 'script' );

		// Dynamic Stylesheet
		$plugins['ttfmake_dynamic_stylesheet'] = $this->scripts()->get_url( 'make-dynamic-stylesheet-plugin', 'script' );

		// Icon Picker
		$plugins['ttfmake_icon_picker'] = $this->scripts()->get_url( 'make-icon-picker-plugin', 'script' );

		// Non-Editable
		$plugins['noneditable'] = $this->scripts()->get_url( 'noneditable-plugin', 'script' );

		// HR
		$plugins['ttfmake_hr'] = $this->scripts()->get_url( 'make-hr-plugin', 'script' );

		return $plugins;
	}

	/**
	 * Add buttons to the TinyMCE toolbar row 1.
	 *
	 * @since 1.4.1.
	 *
	 * @hooked filter mce_buttons
	 *
	 * @param array $buttons
	 *
	 * @return array
	 */
	public function register_buttons_1( $buttons ) {
		// Format Builder
		$buttons[] = 'ttfmake_format_builder';

		// Icon Picker
		$buttons[] = 'ttfmake_icon_picker';

		// HR
		$buttons[] = 'ttfmake_hr';

		return $buttons;
	}

	/**
	 * Add buttons to the TinyMCE toolbar row 2.
	 *
	 * @since 1.4.1.
	 *
	 * @hooked filter mce_buttons_2
	 *
	 * @param array $buttons
	 *
	 * @return array
	 */
	public function register_buttons_2( $buttons ) {
		// Add the Formats dropdown
		array_unshift( $buttons, 'styleselect' );

		return $buttons;
	}

	/**
	 * Position the new hr button in the place that the old hr usually resides.
	 *
	 * @since 1.0.0.
	 *
	 * @hooked filter tiny_mce_before_init
	 *
	 * @param array  $mceInit      The configuration for the current editor.
	 * @param string $editor_id    The ID for the current editor.
	 *
	 * @return array               The modified configuration array.
	 */
	public function reposition_hr( $mceInit, $editor_id ) {
		if ( ! empty( $mceInit['toolbar1'] ) ) {
			if ( in_array( 'hr', explode( ',', $mceInit['toolbar1'] ) ) ) {
				// Remove the current positioning of the new hr button
				$mceInit['toolbar1'] = str_replace( ',hr,', ',ttfmake_hr,', $mceInit['toolbar1'] );

				// Remove the duplicated new hr button
				$pieces              = explode( ',', $mceInit['toolbar1'] );
				$pieces              = array_unique( $pieces );
				$mceInit['toolbar1'] = implode( ',', $pieces );
			}
		}

		return $mceInit;
	}

	/**
	 * Add translatable strings for the Format Builder UI.
	 *
	 * @since 1.4.1.
	 *
	 * @hooked filter wp_mce_translation
	 *
	 * @param array $translations
	 *
	 * @return array
	 */
	public function add_translations( $translations ) {
		$formatting_translations = array(
			// Format Builder
			'Format Builder' => __( 'Format Builder', 'make' ),
			'Choose a format' => __( 'Choose a format', 'make' ),
			'Insert' => __( 'Insert', 'make' ),
			'Update' => __( 'Update', 'make' ),
			'Remove' => __( 'Remove', 'make' ),
			'Add icon' => __( 'Add icon', 'make' ),
			'Notice' => __( 'Notice', 'make' ),
			'Background Color' => __( 'Background Color', 'make' ),
			'Text Color' => __( 'Text Color', 'make' ),
			'Font Size (px)' => __( 'Font Size (px)', 'make' ),
			'Icon' => __( 'Icon', 'make' ),
			'Icon Size (px)' => __( 'Icon Size (px)', 'make' ),
			'Icon Color' => __( 'Icon Color', 'make' ),
			'Icon Position' => __( 'Icon Position', 'make' ),
			'left' => __( 'left', 'make' ),
			'right' => __( 'right', 'make' ),
			'Horizontal Padding (px)' => __( 'Horizontal Padding (px)', 'make' ),
			'Vertical Padding (px)' => __( 'Vertical Padding (px)', 'make' ),
			'Border Style' => __( 'Border Style', 'make' ),
			'none' => __( 'none', 'make' ),
			'solid' => __( 'solid', 'make' ),
			'dotted' => __( 'dotted', 'make' ),
			'dashed' => __( 'dashed', 'make' ),
			'double' => __( 'double', 'make' ),
			'groove' => __( 'groove', 'make' ),
			'ridge' => __( 'ridge', 'make' ),
			'inset' => __( 'inset', 'make' ),
			'outset' => __( 'outset', 'make' ),
			'Border Width (px)' => __( 'Border Width (px)', 'make' ),
			'Border Color' => __( 'Border Color', 'make' ),
			'Button' => __( 'Button', 'make' ),
			'URL' => __( 'URL', 'make' ),
			'Open link in a new window/tab' => __( 'Open link in a new window/tab', 'make' ),
			'Font Weight' => __( 'Font Weight', 'make' ),
			'normal' => __( 'normal', 'make' ),
			'bold' => __( 'bold', 'make' ),
			'Background Color (hover)' => __( 'Background Color (hover)', 'make' ),
			'Text Color (hover)' => __( 'Text Color (hover)', 'make' ),
			'Border Radius (px)' => __( 'Border Radius (px)', 'make' ),
			'List' => __( 'List', 'make' ),
			// Icon Picker
			'Insert Icon' => __( 'Insert Icon', 'make' ),
			'Choose an icon' => __( 'Choose an icon', 'make' ),
			'Web Application Icons' => __( 'Web Application Icons', 'make' ),
			'Text Editor Icons' => __( 'Text Editor Icons', 'make' ),
			'Spinner Icons' => __( 'Spinner Icons', 'make' ),
			'File Type Icons' => __( 'File Type Icons', 'make' ),
			'Directional Icons' => __( 'Directional Icons', 'make' ),
			'Video Player Icons' => __( 'Video Player Icons', 'make' ),
			'Form Control Icons' => __( 'Form Control Icons', 'make' ),
			'Chart Icons' => __( 'Chart Icons', 'make' ),
			'Brand Icons' => __( 'Brand Icons', 'make' ),
			'Payment Icons' => __( 'Payment Icons', 'make' ),
			'Currency Icons' => __( 'Currency Icons', 'make' ),
			'Medical Icons' => __( 'Medical Icons', 'make' ),
			'Choose' => __( 'Choose', 'make' ),
			// HR
			'Insert Horizontal Line' => __( 'Insert Horizontal Line', 'make' ),
			'Choose a line style' => __( 'Choose a line style', 'make' ),
		);

		return array_merge( $translations, $formatting_translations );
	}

	/**
	 * Add items to the Formats dropdown.
	 *
	 * @since 1.0.0.
	 *
	 * @hooked filter tiny_mce_before_init
	 *
	 * @param array $settings    TinyMCE settings array.
	 *
	 * @return array             Modified array.
	 */
	public function formats_dropdown_items( $settings ) {
		$style_formats = array(
			// Big (big)
			array(
				'title'  => __( 'Big', 'make' ),
				'inline' => 'big'
			),
			// Small (small)
			array(
				'title'  => __( 'Small', 'make' ),
				'inline' => 'small'
			),
			// Citation (cite)
			array(
				'title'  => __( 'Citation', 'make' ),
				'inline' => 'cite'
			),
			// Testimonial (blockquote)
			array(
				'title'   => __( 'Testimonial', 'make' ),
				'block'   => 'blockquote',
				'classes' => 'ttfmake-testimonial',
				'wrapper' => true
			),
		);

		/**
		 * Filter the styles that are added to the TinyMCE Formats dropdown.
		 *
		 * @since 1.2.3.
		 *
		 * @param array    $style_formats    The format items being added to TinyMCE.
		 */
		$style_formats = apply_filters( 'make_style_formats', $style_formats );

		// Encode
		$settings['style_formats'] = json_encode( $style_formats );

		return $settings;
	}

	/**
	 * Register styles and scripts.
	 *
	 * @since 1.7.0.
	 *
	 * @hooked action wp_enqueue_scripts
	 * @hooked action admin_enqueue_scripts
	 *
	 * @return void
	 */
	public function register_styles_scripts() {
		// Admin styles
		wp_register_style(
			'make-formatting',
			$this->scripts()->get_css_directory_uri() . '/formatting/formatting.css',
			array(),
			TTFMAKE_VERSION
		);

		// Dynamic Stylesheet
		wp_register_script(
			'make-dynamic-stylesheet',
			$this->scripts()->get_js_directory_uri() . '/formatting/dynamic-stylesheet/dynamic-stylesheet.js',
			array( 'jquery' ),
			TTFMAKE_VERSION,
			true
		);

		// Icon Picker
		wp_register_script(
			'make-icon-picker',
			$this->scripts()->get_js_directory_uri() . '/formatting/icon-picker/icon-picker.js',
			array( 'editor', 'jquery' ),
			TTFMAKE_VERSION
		);

		// Format Builder
		wp_register_script(
			'make-format-builder-core',
			$this->scripts()->get_js_directory_uri() . '/formatting/format-builder/format-builder.js',
			array( 'editor', 'backbone', 'underscore', 'jquery', 'make-icon-picker', 'make-dynamic-stylesheet' ),
			TTFMAKE_VERSION
		);
		wp_register_script(
			'make-format-builder-model-base',
			$this->scripts()->get_js_directory_uri() . '/formatting/format-builder/models/base.js',
			array( 'make-format-builder-core' ),
			TTFMAKE_VERSION
		);

		// Formats
		foreach ( $this->formats as $name => $data ) {
			$version = ( $data['version'] ) ? $data['version'] : TTFMAKE_VERSION;
			wp_register_script(
				'make-format-builder-model-' . $name,
				$data['uri'],
				array( 'make-format-builder-model-base' ),
				$version
			);
		}

		// TinyMCE plugins
		wp_register_script(
			'make-dynamic-stylesheet-plugin',
			$this->scripts()->get_js_directory_uri() . '/formatting/dynamic-stylesheet/plugin.js',
			array( 'editor', 'jquery', 'make-dynamic-stylesheet' ),
			TTFMAKE_VERSION
		);
		wp_register_script(
			'make-icon-picker-plugin',
			$this->scripts()->get_js_directory_uri() . '/formatting/icon-picker/plugin.js',
			array( 'editor', 'jquery', 'make-icon-picker' ),
			TTFMAKE_VERSION
		);
		wp_register_script(
			'make-format-builder-plugin',
			$this->scripts()->get_js_directory_uri() . '/formatting/format-builder/plugin.js',
			array( 'editor', 'jquery', 'make-format-builder-core' ),
			TTFMAKE_VERSION
		);
		wp_register_script(
			'make-hr-plugin',
			$this->scripts()->get_js_directory_uri() . '/formatting/hr/plugin.js',
			array( 'editor', 'jquery' ),
			TTFMAKE_VERSION
		);
		wp_register_script(
			'noneditable-plugin',
			$this->scripts()->get_js_directory_uri() . '/libs/tinymce/plugins/noneditable/plugin.js',
			array( 'editor', 'jquery' ),
			'4.2.8'
		);
	}

	/**
	 * Enqueue formatting scripts for Post/Page editing screens in the admin.
	 *
	 * @since 1.4.1.
	 *
	 * @hooked action admin_enqueue_scripts
	 *
	 * @param string $hook_suffix
	 *
	 * @return void
	 */
	public function enqueue_admin_scripts( $hook_suffix ) {
		// Only enqueue for content editing screens
		if ( in_array( $hook_suffix, array( 'post.php', 'post-new.php' ) ) ) {
			/**
			 * Admin styles
			 */
			wp_enqueue_style( 'font-awesome' );
			wp_enqueue_style( 'make-formatting' );

			/**
			 * Dynamic Stylesheet
			 */
			wp_enqueue_script( 'make-dynamic-stylesheet' );
			wp_localize_script(
				'make-dynamic-stylesheet',
				'MakeDynamicStylesheet',
				array(
					'tinymce' => true
				)
			);
			// Add TinyMCE as a dependency on the Admin side.
			$this->scripts()->add_dependency( 'make-dynamic-stylesheet', 'editor', 'script' );

			/**
			 * Icon Picker
			 */
			wp_enqueue_script( 'make-icon-picker' );
			wp_localize_script(
				'make-icon-picker',
				'MakeIconPicker',
				array(
					'sources' => array(
						'fontawesome' => $this->scripts()->get_js_directory_uri() . '/formatting/icon-picker/fontawesome.json',
						'fontawesomecats' => $this->scripts()->get_js_directory_uri() . '/formatting/icon-picker/fontawesomecats.json'
					)
				)
			);

			/**
			 * Format Builder
			 */
			// Core
			wp_enqueue_script( 'make-format-builder-core' );
			wp_localize_script(
				'make-format-builder-core',
				'MakeFormatBuilder',
				array(
					'userSettings' => array(
						'fontSizeBody'               => $this->thememod()->get_value( 'font-size-body' ),
						'fontSizeButton'             => $this->thememod()->get_value( 'font-size-button' ),
						'colorPrimary'               => $this->thememod()->get_value( 'color-primary' ),
						'colorSecondary'             => $this->thememod()->get_value( 'color-secondary' ),
						'colorButtonText'            => $this->thememod()->get_value( 'color-button-text' ),
						'colorButtonTextHover'       => $this->thememod()->get_value( 'color-button-text-hover' ),
						'colorButtonBackground'      => $this->thememod()->get_value( 'color-button-background' ),
						'colorButtonBackgroundHover' => $this->thememod()->get_value( 'color-button-background-hover' ),
					)
				)
			);

			// Base model
			wp_enqueue_script( 'make-format-builder-model-base' );

			// Format models
			foreach ( $this->formats as $name => $data ) {
				wp_enqueue_script( 'make-format-builder-model-' . $name );
			}
		}
	}

	/**
	 * Enqueue scripts for the front end.
	 *
	 * @since 1.4.1.
	 *
	 * @hooked action wp_enqueue_scripts
	 *
	 * @return void
	 */
	public function enqueue_frontend_scripts() {
		// Dynamic styles
		wp_enqueue_script( 'make-dynamic-stylesheet' );
	}
}