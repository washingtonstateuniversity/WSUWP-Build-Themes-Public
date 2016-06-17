<?php
/**
 * @package Make
 */

/**
 * Class MAKE_Settings_ThemeMod
 *
 * A child class of MAKE_Settings_Base for defining and managing theme mod settings and their values.
 *
 * @since 1.7.0.
 */
final class MAKE_Settings_ThemeMod extends MAKE_Settings_Base implements MAKE_Settings_ThemeModInterface, MAKE_Util_HookInterface, MAKE_Util_LoadInterface {
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
		'choices'       => 'MAKE_Choices_ManagerInterface',
		'font'          => 'MAKE_Font_ManagerInterface',
	);

	/**
	 * The type of settings.
	 *
	 * @since 1.7.0.
	 *
	 * @var string
	 */
	protected $type = 'thememod';

	/**
	 * Indicator of whether the hook routine has been run.
	 *
	 * @since 1.7.0.
	 *
	 * @var bool
	 */
	private static $hooked = false;

	/**
	 * Indicator of whether the load routine has been run.
	 *
	 * @since 1.7.0.
	 *
	 * @var bool
	 */
	private $loaded = false;

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

		// Add filters to adjust sanitize callback parameters.
		add_filter( 'make_settings_thememod_sanitize_callback_parameters', array( $this, 'add_sanitize_choice_parameters' ), 10, 2 );
		add_filter( 'make_settings_thememod_sanitize_callback_parameters', array( $this, 'wrap_array_values' ), 10, 2 );

		// Handle cache settings
		add_action( 'customize_save_after', array( $this, 'clear_thememod_cache' ) );

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
	 * Load data files.
	 *
	 * @since 1.7.0.
	 *
	 * @return void
	 */
	public function load() {
		if ( $this->is_loaded() ) {
			return;
		}

		// Load the default setting definitions
		$file = dirname( __FILE__ ) . '/definitions/thememod.php';
		if ( is_readable( $file ) ) {
			include $file;
		}

		// Loading has occurred.
		$this->loaded = true;

		/**
		 * Action: Fires at the end of the ThemeMod settings object's load method.
		 *
		 * This action gives a developer the opportunity to add or modify setting definitions
		 * and run additional load routines.
		 *
		 * @since 1.7.0.
		 *
		 * @param MAKE_Settings_ThemeMod    $settings     The settings object that has just finished loading.
		 */
		do_action( 'make_settings_thememod_loaded', $this );
	}

	/**
	 * Check if the load routine has been run.
	 *
	 * @since 1.7.0.
	 *
	 * @return bool
	 */
	public function is_loaded() {
		return $this->loaded;
	}

	/**
	 * Extension of the parent class's get_settings method to account for a deprecated filter.
	 *
	 * @since 1.7.0.
	 *
	 * @param string $property
	 *
	 * @return array
	 */
	public function get_settings( $property = 'all' ) {
		if ( ! $this->is_loaded() ) {
			$this->load();
		}

		$settings = parent::get_settings( $property );

		// Check for deprecated filter.
		if ( 'default' === $property && has_filter( 'make_setting_defaults' ) ) {
			$this->compatibility()->deprecated_hook(
				'make_setting_defaults',
				'1.7.0',
				sprintf(
					wp_kses(
						__( 'To add or modify theme settings, use the %1$s function instead. See the <a href="%2$s" target="_blank">Theme Settings API documentation</a>.', 'make' ),
						array( 'a' => array( 'href' => true, 'target' => true ) )
					),
					'<code>make_update_thememod_setting_definition()</code>',
					'https://thethemefoundry.com/docs/make-docs/code/apis/theme-settings-api/'
				)
			);

			/**
			 * Deprecated: Filter the default values for the settings.
			 *
			 * @since 1.2.3.
			 * @deprecated 1.7.0.
			 *
			 * @param array    $defaults    The list of default settings.
			 */
			$settings = apply_filters( 'make_setting_defaults', $settings );
		}

		return $settings;
	}

	/**
	 * Set a new value for a particular theme_mod setting.
	 *
	 * @since 1.7.0.
	 *
	 * @param  string $setting_id    The name of the theme_mod to set.
	 * @param  mixed  $value         The value to assign to the theme_mod.
	 *
	 * @return bool                  True if value was successfully set.
	 */
	public function set_value( $setting_id, $value ) {
		if ( $this->setting_exists( $setting_id ) ) {
			// Sanitize the value before saving it.
			$sanitized_value = $this->sanitize_value( $value, $setting_id, 'database' );

			if ( $this->undefined !== $sanitized_value ) {
				// This function doesn't return anything, so we assume success here.
				set_theme_mod( $setting_id, $sanitized_value );
				return true;
			}
		}

		return false;
	}

	/**
	 * Remove the value for a particular theme_mod setting.
	 *
	 * @since 1.7.0.
	 *
	 * @param  string $setting_id    The name of the theme_mod to remove.
	 *
	 * @return bool                  True if the theme_mod was successfully removed.
	 */
	public function unset_value( $setting_id ) {
		if ( $this->setting_exists( $setting_id ) ) {
			// This function doesn't return anything, so we assume success here.
			remove_theme_mod( $setting_id );
			return true;
		}

		return false;
	}

	/**
	 * Get the stored value of a theme_mod, unaltered.
	 *
	 * @since 1.7.0.
	 *
	 * @param  string $setting_id    The name of the theme_mod to retrieve.
	 *
	 * @return mixed|null            The value of the theme_mod as it is in the database, or undefined if the theme_mod isn't set.
	 */
	public function get_raw_value( $setting_id ) {
		$value = $this->undefined;

		if ( $this->setting_exists( $setting_id ) ) {
			$value = get_theme_mod( $setting_id, $this->undefined );
		}

		return $value;
	}

	/**
	 * Extension of the parent class's get_default method to account for a deprecated filter.
	 *
	 * @since 1.7.0.
	 *
	 * @param string $setting_id
	 *
	 * @return mixed
	 */
	public function get_default( $setting_id ) {
		$default_value = parent::get_default( $setting_id );

		// Check for deprecated filter.
		if ( has_filter( 'make_get_default' ) ) {
			$this->compatibility()->deprecated_hook(
				'make_get_default',
				'1.7.0',
				sprintf(
					wp_kses(
						__( 'To add or modify theme settings, use the %1$s function instead. See the <a href="%2$s" target="_blank">Theme Settings API documentation</a>.', 'make' ),
						array( 'a' => array( 'href' => true, 'target' => true ) )
					),
					'<code>make_update_thememod_setting_definition()</code>',
					'https://thethemefoundry.com/docs/make-docs/code/apis/theme-settings-api/'
				)
			);

			/**
			 * Deprecated: Filter the retrieved default value.
			 *
			 * @since 1.2.3.
			 * @deprecated 1.7.0.
			 *
			 * @param mixed     $default    The default value.
			 * @param string    $option     The name of the default value.
			 */
			$default_value = apply_filters( 'make_get_default', $default_value, $setting_id );
		}

		return $default_value;
	}

	/**
	 * Extension of the parent class's sanitize_value method to account for how the Customizer handles sanitize callbacks.
	 *
	 * @since 1.7.0.
	 *
	 * @param  mixed  $value         The value to sanitize.
	 * @param  string $setting_id    The ID of the setting to retrieve.
	 * @param  string $context       Optional. The context in which a setting needs to be sanitized.
	 *
	 * @return mixed
	 */
	public function sanitize_value( $value, $setting_id, $context = '' ) {
		// Is this being called by the Customizer?
		if ( $setting_id instanceof WP_Customize_Setting ) {
			$setting_id = $setting_id->id;
		}

		return parent::sanitize_value( $value, $setting_id, $context );
	}

	/**
	 * Get the choice set for a particular setting.
	 *
	 * This can either return the set (an array of choices) or simply the ID of the choice set (string).
	 *
	 * @param string $setting_id
	 * @param bool   $id_only
	 *
	 * @return array|string|null
	 */
	public function get_choice_set( $setting_id, $id_only = false ) {
		$choice_set_id = $this->undefined;

		if ( $this->setting_exists( $setting_id, 'choice_set_id' ) ) {
			$setting = $this->get_setting( $setting_id );
			$choice_set_id = sanitize_key( $setting['choice_set_id'] );
		}

		// Return just the ID.
		if ( true === $id_only ) {
			return $choice_set_id;
		}

		// Get the choice set array.
		$choice_set = $this->choices()->get_choice_set( $choice_set_id );

		// Check for deprecated filter.
		if ( has_filter( 'make_setting_choices' ) ) {
			$this->compatibility()->deprecated_hook(
				'make_setting_choices',
				'1.7.0',
				sprintf(
					wp_kses(
						__( 'To add or modify setting choices, use the %1$s function instead. See the <a href="%2$s" target="_blank">Choices API documentation</a>.', 'make' ),
						array( 'a' => array( 'href' => true, 'target' => true ) )
					),
					'<code>make_update_choice_set()</code>',
					'https://thethemefoundry.com/docs/make-docs/code/apis/choices-api/'
				)
			);

			/**
			 * Filter the setting choices.
			 *
			 * @since 1.2.3.
			 * @deprecated 1.7.0.
			 *
			 * @param array     $choices       The choices for the setting.
			 * @param string    $setting_id    The setting name.
			 */
			$choice_set = apply_filters( 'make_setting_choices', $choice_set, $setting_id );
		}

		// Return the array of choices.
		return $choice_set;
	}

	/**
	 * Add items to the array of parameters to feed into the sanitize callback.
	 *
	 * @since 1.7.0.
	 *
	 * @hooked filter make_settings_thememod_sanitize_callback_parameters
	 *
	 * @param  mixed  $value
	 * @param  string $setting_id
	 *
	 * @return array
	 */
	public function add_sanitize_choice_parameters( $value, $setting_id ) {
		$choice_settings = array_merge(
			array_keys( $this->get_settings( 'choice_set_id' ), true ),
			array_keys( $this->get_settings( 'is_font' ), true )
		);

		if ( in_array( $setting_id, $choice_settings ) ) {
			$value = (array) $value;
			$value[] = $setting_id;
		}

		return $value;
	}

	/**
	 * Wrap setting values that are arrays in another array so that the data will remain intact
	 * when it passes through call_user_func_array().
	 *
	 * @since 1.7.0.
	 *
	 * @hooked filter make_settings_thememod_sanitize_callback_parameters
	 *
	 * @param mixed  $value
	 * @param string $setting_id
	 *
	 * @return array
	 */
	public function wrap_array_values( $value, $setting_id ) {
		if ( in_array( $setting_id, array_keys( $this->get_settings( 'is_array' ), true ) ) ) {
			$value = array( $value );
		}

		return $value;
	}

	/**
	 * Clear values for settings that have the is_cache property.
	 *
	 * @since 1.7.0.
	 *
	 * @hooked action customize_save_after
	 *
	 * @return void
	 */
	public function clear_thememod_cache() {
		$cache_settings = array_keys( $this->get_settings( 'is_cache' ), true );

		foreach ( $cache_settings as $setting_id ) {
			$this->unset_value( $setting_id );
		}
	}
}