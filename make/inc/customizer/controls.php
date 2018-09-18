<?php
/**
 * @package Make
 */

/**
 * Class MAKE_Customizer_Controls
 *
 * Set up the Customizer interface with the theme's settings.
 *
 * @since 1.7.0.
 */
final class MAKE_Customizer_Controls extends MAKE_Util_Modules implements MAKE_Customizer_ControlsInterface, MAKE_Util_HookInterface {
	/**
	 * An associative array of required modules.
	 *
	 * @since 1.7.0.
	 *
	 * @var array
	 */
	protected $dependencies = array(
		'error'         => 'MAKE_Error_CollectorInterface',
		'compatibility' => 'MAKE_Compatibility_MethodsInterface',
		'font'          => 'MAKE_Font_ManagerInterface',
		'thememod'      => 'MAKE_Settings_ThemeModInterface',
		'scripts'       => 'MAKE_Setup_ScriptsInterface',
		'logo'          => 'MAKE_Logo_MethodsInterface',
		'socialicons'   => 'MAKE_SocialIcons_ManagerInterface',
		'helper'        => 'MAKE_Customizer_DataHelperInterface'
	);

	/**
	 * Array to hold the Panel definitions.
	 *
	 * @since 1.7.0.
	 *
	 * @var array
	 */
	private $panel_definitions = array();

	/**
	 * Array to hold the Section definitions.
	 *
	 * @since 1.7.0.
	 *
	 * @var array
	 */
	private $section_definitions = array();

	/**
	 * Prefix string for panels, sections, and controls.
	 *
	 * @since 1.7.0.
	 *
	 * @var string
	 */
	private $prefix = 'ttfmake_';

	/**
	 * Indicator of whether the hook routine has been run.
	 *
	 * @since 1.7.0.
	 *
	 * @var bool
	 */
	private static $hooked = false;

	/**
	 * MAKE_Customizer_Controls constructor.
	 *
	 * @since 1.7.0.
	 *
	 * @param MAKE_APIInterface|null $api
	 * @param array                  $modules
	 */
	public function __construct( MAKE_APIInterface $api = null, array $modules = array() ) {
		// Module defaults.
		$modules = wp_parse_args( $modules, array(
			'helper' => 'MAKE_Customizer_DataHelper',
		) );

		// Load dependencies.
		parent::__construct( $api, $modules );
	}

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

		// Register control types
		add_action( 'customize_register', array( $this, 'setup_control_types' ), 1 );

		// Load section definitions
		add_action( 'customize_register', array( $this, 'load_definitions' ), 5 );

		// Add panels
		add_action( 'customize_register', array( $this, 'add_panels' ) );

		// Add sections
		add_action( 'customize_register', array( $this, 'add_sections' ) );

		// Load section mods
		add_action( 'customize_register', array( $this, 'load_mods' ), 50 );

		// Control scripts
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_control_scripts' ) );

		// Print additional JS templates
		add_action( 'customize_controls_print_footer_scripts', array( $this, 'render_templates' ) );

		// Font choices ajax
		add_action( 'wp_ajax_make-font-choices', array( $this, 'get_font_choices_ajax' ) );

		// Social icons ajax
		add_action( 'wp_ajax_make-social-icons', array( $this, 'get_socialicons_ajax' ) );
		add_action( 'wp_ajax_make-social-icons-list', array( $this, 'get_socialicons_list_ajax' ) );

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
	 * Preliminary setup for Make's custom control classes.
	 *
	 * @since 1.7.0.
	 *
	 * @hooked action customize_register
	 *
	 * @param WP_Customize_Manager $wp_customize
	 *
	 * @return void
	 */
	public function setup_control_types( WP_Customize_Manager $wp_customize ) {
		// The control types with JS templates
		$types = array(
			'MAKE_Customizer_Control_BackgroundPosition',
			'MAKE_Customizer_Control_Html',
			'MAKE_Customizer_Control_Radio',
			'MAKE_Customizer_Control_Select',
			'MAKE_Customizer_Control_Range',
			'MAKE_Customizer_Control_SocialIcons',
		);

		// Register each type
		foreach ( $types as $type ) {
			$wp_customize->register_control_type( $type );
		}

		// Add a dummy setting for MAKE_Customize_Control_Html
		// This is no longer needed in WP 4.5, which is also when the get_previewable_devices() method was added.
		// TODO remove this when 4.4 support is dropped.
		if ( ! method_exists( $wp_customize, 'get_previewable_devices' ) ) {
			$wp_customize->add_setting( 'make-customize-control-html', array(
				'type'                 => 'number',
				'default'              => 0,
				'sanitize_callback'    => 'absint',
			) );
		}

		// Register the error display section, if supported
		if ( method_exists( $wp_customize, 'register_section_type' ) ) {
			$wp_customize->register_section_type( 'MAKE_Error_Section' );
		}
	}

	/**
	 * Load data files for defining Make's sections.
	 *
	 * @since 1.7.0.
	 *
	 * @hooked action customize_register
	 *
	 * @param WP_Customize_Manager $wp_customize
	 *
	 * @return void
	 */
	public function load_definitions( WP_Customize_Manager $wp_customize ) {
		// Panel definitions.
		$this->panel_definitions = array(
			'general'           => array(
				'title'    => __( 'General', 'make' ),
				'priority' => 10
			),
			'typography'        => array(
				'title'    => __( 'Typography', 'make' ),
				'priority' => 20
			),
			'color'             => array(
				'title'    => __( 'Color', 'make' ),
				'priority' => 30
			),
			'background-images' => array(
				'title'    => __( 'Background Images', 'make' ),
				'priority' => 40
			),
			'layout'            => array(
				'title'    => __( 'Layout', 'make' ),
				'priority' => 50
			),
		);

		$file_bases = array(
			'general',
			'typography',
			'color',
			'background-images',
			'layout',
		);

		// Section/Control definitions
		foreach ( $file_bases as $name ) {
			$file = dirname( __FILE__ ) . '/definitions/' . $name . '.php';
			if ( is_readable( $file ) ) {
				include_once $file;
			}
		}
	}

	/**
	 * Load data files for modifying core elements.
	 *
	 * @since 1.7.0.
	 *
	 * @hooked action customize_register
	 *
	 * @param WP_Customize_Manager $wp_customize
	 *
	 * @return void
	 */
	public function load_mods( WP_Customize_Manager $wp_customize ) {
		$file_bases = array(
			'background',
			'site-title-tagline',
			'static-front-page',
			'widgets',
			'menu',
		);

		foreach ( $file_bases as $name ) {
			$file = dirname( __FILE__ ) . '/mods/' . $name . '.php';
			if ( is_readable( $file ) ) {
				include_once $file;
			}
		}
	}

	/**
	 * Get the array of panel definitions.
	 *
	 * @since 1.7.0.
	 *
	 * @return array
	 */
	public function get_panel_definitions() {
		/**
		 * Filter: Modify the array of panel definitions for the Customizer.
		 *
		 * @since 1.3.0.
		 *
		 * @param array $panels    The array of panel definitions.
		 */
		return apply_filters( 'make_customizer_panels', $this->panel_definitions );
	}

	/**
	 * Register Customizer panels from the panel definitions array.
	 *
	 * @since 1.7.0.
	 *
	 * @hooked action customize_register
	 *
	 * @param WP_Customize_Manager $wp_customize
	 *
	 * @return void
	 */
	public function add_panels( WP_Customize_Manager $wp_customize ) {
		$priority = new MAKE_Util_Priority( 1000, 100 );

		// Add panels.
		foreach ( $this->get_panel_definitions() as $panel => $data ) {
			// Determine priority.
			if ( ! isset( $data['priority'] ) || ! is_int( $data['priority'] ) ) {
				$data['priority'] = $priority->add();
			}

			// Add panel.
			$wp_customize->add_panel( $this->prefix . $panel, $data );
		}
	}

	/**
	 * Add a section definition to the array.
	 *
	 * @since 1.7.0.
	 *
	 * @param string $section_id
	 * @param array  $data
	 * @param bool   $overwrite
	 *
	 * @return bool
	 */
	private function add_section_definitions( $section_id, array $data, $overwrite = false ) {
		$section_id = sanitize_key( $section_id );

		// Section already exists, overwriting disabled.
		if ( isset( $this->section_definitions[ $section_id ] ) && true !== $overwrite ) {
			$this->error()->add_error( 'make_section_already_exists', sprintf( __( 'The "%s" section can\'t be added because it already exists.', 'make' ), $section_id ) );
			return false;
		}
		// Section already exists, overwriting enabled.
		else if ( isset( $this->section_definitions[ $section_id ] ) && true === $overwrite ) {
			$this->section_definitions[ $section_id ] = array_merge_recursive( $this->section_definitions[ $section_id ], $data );
		}
		// Add a new section
		else {
			$this->section_definitions[ $section_id ] = $data;
		}

		return true;
	}

	/**
	 * Get the array of section/control definitions.
	 *
	 * @since 1.7.0.
	 *
	 * @return mixed|void
	 */
	public function get_section_definitions() {
		/**
		 * Filter: Modify the array of section/control definitions for the Customizer.
		 *
		 * @since 1.3.0.
		 *
		 * @param array $sections    The array of section definitions.
		 */
		return apply_filters( 'make_customizer_sections', $this->section_definitions );
	}

	/**
	 * Register Customizer sections and controls from the section definitions array.
	 *
	 * @since 1.7.0.
	 *
	 * @hooked action customize_register
	 *
	 * @param WP_Customize_Manager $wp_customize
	 *
	 * @return void
	 */
	public function add_sections( WP_Customize_Manager $wp_customize ) {
		// Bucket so each panel can have its own priority class.
		$priority = array();

		// Section definitions.
		foreach ( $this->get_section_definitions() as $section => $data ) {
			// Get the ID of the current section's panel
			$panel = ( isset( $data['panel'] ) ) ? $data['panel'] : 'none';

			// Store the control definitions for later
			if ( isset( $data['controls'] ) ) {
				$controls = $data['controls'];
				unset( $data['controls'] );
			}

			// Determine the priority
			if ( ! isset( $data['priority'] ) ) {
				$panel_priority = $this->get_last_priority( $wp_customize->panels() ) + 100;
				if ( 'none' !== $panel && isset( $wp_customize->get_panel( $panel )->priority ) ) {
					$panel_priority = $wp_customize->get_panel( $panel )->priority;
				}

				// Create a separate priority counter for each panel, and one for sections without a panel
				if ( ! isset( $priority[ $panel ] ) ) {
					$priority[ $panel ] = new MAKE_Util_Priority( $panel_priority, 10 );
				}

				$data['priority'] = $priority[ $panel ]->add();
			}

			// Add the section.
			$wp_customize->add_section( $this->prefix . $section, $data );

			// Add controls to the section
			if ( isset( $controls ) ) {
				$this->add_section_controls( $wp_customize, $this->prefix . $section, $controls );
				unset( $controls );
			}
		}
	}

	/**
	 * Register settings, controls, and partials for a section from the controls array in a section definition.
	 *
	 * @since 1.7.0.
	 *
	 * @param WP_Customize_Manager $wp_customize
	 * @param string               $section
	 * @param array                $args
	 * @param int                  $initial_priority
	 *
	 * @return int
	 */
	private function add_section_controls( WP_Customize_Manager $wp_customize, $section, array $args, $initial_priority = 10 ) {
		$priority = new MAKE_Util_Priority( $initial_priority, 5 );

		// Check for deprecated filter.
		if ( has_filter( 'make_customizer_control_path' ) ) {
			$this->compatibility()->deprecated_hook(
				'make_customizer_control_path',
				'1.7.0'
			);
		}

		foreach ( $args as $setting_id => $definition ) {
			// Add setting
			if ( isset( $definition['setting'] ) && ( is_array( $definition['setting'] ) || true === $definition['setting'] ) ) {
				$defaults = array(
					'type'                 => 'theme_mod',
					'capability'           => 'edit_theme_options',
					'theme_supports'       => '',
					'default'              => $this->thememod()->get_default( $setting_id ),
					'transport'            => $this->get_default_transport( $setting_id ),
					'sanitize_callback'    => array( $this, 'sanitize' ),
					'sanitize_js_callback' => ( $this->thememod()->has_sanitize_callback( $setting_id, 'to_customizer' ) ) ? array( $this, 'sanitize_js' ) : '',
				);
				$setting = wp_parse_args( $definition['setting'], $defaults );

				// Add the setting arguments inline so Theme Check can verify the presence of sanitize_callback
				$wp_customize->add_setting( $setting_id, array(
					'type'                 => $setting['type'],
					'capability'           => $setting['capability'],
					'theme_supports'       => $setting['theme_supports'],
					'default'              => $setting['default'],
					'transport'            => $setting['transport'],
					'sanitize_callback'    => $setting['sanitize_callback'],
					'sanitize_js_callback' => $setting['sanitize_js_callback'],
				) );
			}

			// Add control
			if ( isset( $definition['control'] ) ) {
				$control_id = $this->prefix . $setting_id;

				$defaults = array(
					'settings' => $setting_id,
					'section'  => $section,
					'priority' => $priority->add(),
				);

				// If this control is not linked to a specific setting, remove settings from defaults.
				if ( ! isset( $definition['setting'] ) || false === $definition['setting'] ) {
					unset( $defaults['settings'] );
				}

				$control = wp_parse_args( $definition['control'], $defaults );

				// Check for a specialized control class
				if ( isset( $control['control_type'] ) ) {
					$class = $control['control_type'];

					// Attempt to autoload the class
					$reflection = new ReflectionClass( $class );

					// If the class successfully loaded, create an instance in a PHP 5.2 compatible way.
					if ( class_exists( $class ) ) {
						unset( $control['control_type'] );

						// Dynamically generate a new class instance
						$class_instance = $reflection->newInstanceArgs( array( $wp_customize, $control_id, $control ) );

						$wp_customize->add_control( $class_instance );
					}
				} else {
					$wp_customize->add_control( $control_id, $control );
				}
			}

			// Add partial, if selective refresh is supported
			if ( isset( $definition['partial'] ) && isset( $wp_customize->selective_refresh ) ) {
				$partial_id = $this->prefix . 'partial_' . $setting_id;

				$defaults = array(
					'settings' => array( $setting_id ),
				);

				$partial = wp_parse_args( $definition['partial'], $defaults );

				$wp_customize->selective_refresh->add_partial( $partial_id, $partial );
			}
		}

		// Return the final priority.
		return $priority->get();
	}

	/**
	 * Shortcut to programmatically determine the appropriate transport for a setting.
	 *
	 * @since 1.7.0.
	 *
	 * @param string $setting_id
	 *
	 * @return string
	 */
	private function get_default_transport( $setting_id ) {
		static $postMessage_settings = array();

		if ( empty( $postMessage_settings ) ) {
			$postMessage_settings = array_keys( $this->thememod()->get_settings( 'is_style' ), true );
		}

		if ( false !== array_search( $setting_id, $postMessage_settings ) ) {
			return 'postMessage';
		}

		return 'refresh';
	}

	/**
	 * Wrapper method to sanitize any setting before the Customizer sends it to the database.
	 *
	 * @since 1.7.0.
	 *
	 * @param mixed                $value
	 * @param WP_Customize_Setting $setting
	 *
	 * @return mixed
	 */
	public function sanitize( $value, WP_Customize_Setting $setting ) {
		return $this->thememod()->sanitize_value( $value, $setting->id, 'from_customizer' );
	}

	/**
	 * Wrapper method to sanitize any setting after the Customizer retrieves it from the database.
	 *
	 * @since 1.7.0.
	 *
	 * @param mixed                $value
	 * @param WP_Customize_Setting $setting
	 *
	 * @return mixed
	 */
	public function sanitize_js( $value, WP_Customize_Setting $setting ) {
		return $this->thememod()->sanitize_value( $value, $setting->id, 'to_customizer' );
	}

	/**
	 * Get an array of section objects for the specified panel.
	 *
	 * @since 1.7.0.
	 *
	 * @param WP_Customize_Manager $wp_customize
	 * @param string               $panel_id
	 *
	 * @return array
	 */
	public function get_panel_sections( WP_Customize_Manager $wp_customize, $panel_id ) {
		$all_sections = $wp_customize->sections();
		$panel_sections = array();

		if ( $wp_customize->get_panel( $panel_id ) instanceof WP_Customize_Panel ) {
			foreach ( $all_sections as $section ) {
				if ( $panel_id === $section->panel ) {
					$panel_sections[] = $section;
				}
			}
		}

		return $panel_sections;
	}

	/**
	 * Get an array of control objects for the specified section.
	 *
	 * @since 1.7.0.
	 *
	 * @param WP_Customize_Manager $wp_customize
	 * @param string               $section_id
	 *
	 * @return array
	 */
	public function get_section_controls( WP_Customize_Manager $wp_customize, $section_id ) {
		$all_controls = $wp_customize->controls();
		$section_controls = array();

		if ( $wp_customize->get_section( $section_id ) instanceof WP_Customize_Section ) {
			foreach ( $all_controls as $control ) {
				if ( $section_id === $control->section ) {
					$section_controls[] = $control;
				}
			}
		}

		return $section_controls;
	}

	/**
	 * Get the highest (last) priority from a collection of Customizer objects.
	 *
	 * Works with Panels, Sections, and Controls.
	 *
	 * @since 1.7.0.
	 *
	 * @param array $items
	 *
	 * @return int
	 */
	public function get_last_priority( array $items ) {
		// Get the IDs.
		$ids = wp_list_pluck( $items, 'id' );

		// Get the priorities.
		$priorities = wp_list_pluck( $items, 'priority' );

		// Combine.
		$parsed_items = array_combine( $ids, $priorities );

		// Sort.
		sort( $parsed_items );

		// Return highest (last) priority value.
		return absint( array_pop( $parsed_items ) );
	}

	/**
	 * Enqueue styles and scripts for Customizer controls.
	 *
	 * @since 1.7.0.
	 *
	 * @hooked action customize_controls_enqueue_scripts
	 *
	 * @return void
	 */
	public function enqueue_control_scripts() {
		// jQuery UI styles are for our custom Range and Buttonset controls.
		wp_enqueue_style( 'make-jquery-ui-custom' );

		// Styles
		wp_enqueue_style(
			'make-customizer-controls',
			$this->scripts()->get_css_directory_uri() . '/customizer/controls.css',
			array( 'make-jquery-ui-custom', 'chosen' ),
			TTFMAKE_VERSION
		);

		// Scripts
		wp_enqueue_script(
			'make-customizer-controls',
			$this->scripts()->get_js_directory_uri() . '/customizer/controls.js',
			array( 'customize-controls', 'chosen', 'underscore', 'jquery-ui-button', 'jquery-ui-slider' ),
			TTFMAKE_VERSION,
			true
		);

		// Collect localization data
		$data = array(
			'ajaxurl'      => admin_url( 'admin-ajax.php' ),
			'fontSettings' => array_keys( $this->thememod()->get_settings( 'is_font' ) ),
			'l10n'         => array(
				'chosen_loading'          => esc_html__( 'Loading&hellip;', 'make' ),
				'chosen_no_results_fonts' => esc_html__( 'No matching fonts', 'make' ),
			),
		);

		// Localize the script
		wp_localize_script(
			'make-customizer-controls',
			'MakeControls',
			$data
		);
	}

	/**
	 * Render additional JS templates.
	 *
	 * @since 1.7.0.
	 *
	 * @hooked action customize_controls_print_footer_scripts
	 *
	 * @return void
	 */
	public function render_templates() {
		global $wp_customize;

		// Social Icons
		$control = new MAKE_Customizer_Control_SocialIcons(
			$wp_customize,
			'temp',
			array(
				// TODO modify this when 4.4 support is dropped.
				'settings' => ( ! method_exists( $wp_customize, 'get_previewable_devices' ) ) ? 'make-customize-control-html' : array()
			)
		);
		$control->print_sub_templates();
	}

	/**
	 * Ajax handler for retrieving HTML markup for the list of available fonts in Typography settings.
	 *
	 * @since 1.7.0.
	 *
	 * @hooked action wp_ajax_make-font-choices
	 *
	 * @return void
	 */
	public function get_font_choices_ajax() {
		// Only run this during an Ajax request.
		if ( 'wp_ajax_make-font-choices' !== current_action() ) {
			return;
		}

		$choices = $this->font()->get_font_choices();

		foreach ( $choices as $value => $label ) {
			$disabled = ( 0 === strpos( $value, 'make-choice-heading-' ) ) ? 'disabled="disabled"' : '';
			echo "<option value=\"$value\" $disabled>$label</option>";
		}

		// End the Ajax response.
		wp_die();
	}

	/**
	 * Ajax handler for retrieving a social icon for a URL.
	 *
	 * @since 1.7.0.
	 *
	 * @hooked action wp_ajax_make-social-icons
	 *
	 * @return void
	 */
	public function get_socialicons_ajax() {
		// Only run this during an Ajax request.
		if ( 'wp_ajax_make-social-icons' !== current_action() ) {
			return;
		}

		if ( ! isset( $_POST['type'] ) || ! isset( $_POST['content'] ) ) {
			wp_send_json_error();
		}

		$icon = $this->socialicons()->find_match( array( 'type' => $_POST['type'], 'content' => $_POST['content'] ) );

		if ( isset( $icon['class'] ) && is_array( $icon['class'] ) ) {
			wp_send_json_success( implode( ' ', array_map( 'sanitize_key', $icon['class'] ) ) );
		} else {
			wp_send_json_error( $icon );
		}
	}

	/**
	 * Ajax handler for retrieving all the social icon definitions.
	 *
	 * @since 1.7.0.
	 *
	 * @hooked action wp_ajax_make-social-icons-list
	 *
	 * @return void
	 */
	public function get_socialicons_list_ajax() {
		// Only run this during an Ajax request.
		if ( 'wp_ajax_make-social-icons-list' !== current_action() ) {
			return;
		}

		wp_send_json_success( $this->socialicons()->get_icons() );
	}
}
