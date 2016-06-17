<?php
/**
 * @package Make
 */

/**
 * Class MAKE_Settings_Base
 *
 * An object for defining and managing settings and their values.
 *
 * This is an abstract class, so it is unusable on its own. It must be extended by another class.
 *
 * The extending class is required to define the following methods:
 * - set_value
 * - unset_value
 * - get_raw_value
 *
 * Additionally, the extending class should:
 * - Supply a string value for the type property, e.g. 'thememod'
 * - Update the array of required setting properties, if necessary
 *
 * @since 1.7.0.
 */
abstract class MAKE_Settings_Base extends MAKE_Util_Modules implements MAKE_Settings_BaseInterface {
	/**
	 * An associative array of required modules.
	 *
	 * @since 1.7.0.
	 *
	 * @var array
	 */
	protected $dependencies = array(
		'error' => 'MAKE_Error_CollectorInterface',
	);

	/**
	 * The collection of settings and their properties.
	 *
	 * @since 1.7.0.
	 *
	 * @var array
	 */
	protected $settings = array();

	/**
	 * The value returned for an undefined setting.
	 *
	 * @since 1.7.0.
	 *
	 * @var null
	 */
	protected $undefined = null;

	/**
	 * The type of settings. Should be defined in the child class.
	 *
	 * @since 1.7.0.
	 *
	 * @var string
	 */
	protected $type = '';

	/**
	 * Required properties for each setting added to the collection.
	 *
	 * @since 1.7.0.
	 *
	 * @var array
	 */
	protected $required_properties = array(
		'default',
		'sanitize'
	);

	/**
	 * Add or update settings definitions.
	 *
	 * Each setting definition is an item in the associative array.
	 * The item's array key is the setting ID. The item value is another
	 * array that contains setting properties.
	 *
	 * Example:
	 * array(
	 *     'social-twitter' => array(
	 *         'default'  => '',
	 *         'sanitize' => 'esc_url',
	 *     ),
	 * )
	 *
	 * @since 1.7.0.
	 *
	 * @param  array $settings         Array of setting definitions to add.
	 * @param  array $default_props    Array of default properties for each setting definition.
	 * @param  bool  $overwrite        True overwrites an existing setting definition.
	 *
	 * @return bool                    True if all settings were added or updated, false if there was an error.
	 */
	public function add_settings( array $settings, array $default_props = array(), $overwrite = false ) {
		$existing_settings = $this->settings;
		$new_settings = array();
		$return = true;

		// Check each setting definition for required properties before adding it.
		foreach ( $settings as $setting_id => $setting_props ) {
			$setting_id = sanitize_key( $setting_id );

			// Merge any defaults.
			if ( ! empty( $default_props ) ) {
				$setting_props = wp_parse_args( $setting_props, $default_props );
			}

			// Overwrite an existing setting.
			if ( isset( $existing_settings[ $setting_id ] ) && true === $overwrite ) {
				$new_settings[ $setting_id ] = wp_parse_args( $setting_props, $existing_settings[ $setting_id ] );
			}
			// Setting already exists, overwriting disabled.
			else if ( isset( $existing_settings[ $setting_id ] ) && true !== $overwrite ) {
				$this->error()->add_error( 'make_settings_already_exists', sprintf( __( 'The "%s" setting can\'t be added because it already exists.', 'make' ), esc_html( $setting_id ) ) );
				$return = false;
			}
			// Setting does not have required properties.
			else if ( ! $this->has_required_properties( $setting_props ) ) {
				$this->error()->add_error( 'make_settings_missing_required_properties', sprintf( __( 'The "%s" setting can\'t be added because it is missing required properties.', 'make' ), esc_html( $setting_id ) ) );
				$return = false;
			}
			// Add a new setting.
			else {
				$new_settings[ $setting_id ] = $setting_props;
			}
		}

		// Add the valid new settings to the existing settings array.
		if ( ! empty( $new_settings ) ) {
			$this->settings = array_merge( $existing_settings, $new_settings );
		}

		return $return;
	}

	/**
	 * Check an array of setting definition properties against another array of required ones.
	 *
	 * @since 1.7.0.
	 *
	 * @param array $properties    The array of properties to check.
	 *
	 * @return bool                True if all required properties are present.
	 */
	protected function has_required_properties( $properties ) {
		$properties = (array) $properties;
		$required_properties = $this->required_properties;
		$existing_properties = array_keys( $properties );

		// If there aren't any required properties, return true.
		if ( empty( $required_properties ) ) {
			return true;
		}

		// This variable will contain any array keys that aren't found in $existing_properties.
		$diff = array_diff_key( $required_properties, $existing_properties );

		return empty( $diff );
	}

	/**
	 * Remove one or more setting definitions from the collection.
	 *
	 * @since 1.7.0.
	 *
	 * @param array|string $setting_ids    The array of settings to remove, or 'all'.
	 *
	 * @return bool                        True if all setting definitions were successfully removed, false if there was an error.
	 */
	public function remove_settings( $setting_ids ) {
		if ( 'all' === $setting_ids ) {
			// Clear the entire settings array.
			$this->settings = array();
			return true;
		}

		$return = true;

		foreach ( (array) $setting_ids as $setting_id ) {
			if ( isset( $this->settings[ $setting_id ] ) ) {
				unset( $this->settings[ $setting_id ] );
			} else {
				$this->error()->add_error( 'make_settings_cannot_remove', sprintf( __( 'The "%s" setting can\'t be removed because it doesn\'t exist.', 'make' ), esc_html( $setting_id ) ) );
				$return = false;
			}
		}

		return $return;
	}

	/**
	 * Get the setting definitions, or a specific property of each one.
	 *
	 * If the setting definition doesn't have the specified property, it will be omitted.
	 *
	 * @since 1.7.0.
	 *
	 * @param  string $property    The property to get, or 'all'.
	 *
	 * @return array               An array of setting definitions and their specified properties.
	 */
	public function get_settings( $property = 'all' ) {
		if ( 'all' === $property ) {
			return $this->settings;
		}

		$settings = array();

		foreach ( $this->settings as $setting_id => $properties ) {
			if ( isset( $properties[ $property ] ) ) {
				$settings[ $setting_id ] = $properties[ $property ];
			}
		}

		return $settings;
	}

	/**
	 * Check if a setting definition exists.
	 *
	 * Can also be used to check if a setting definition has a specified property.
	 *
	 * @since 1.7.0.
	 *
	 * @param string $setting_id
	 * @param string $property
	 *
	 * @return bool
	 */
	public function setting_exists( $setting_id, $property = 'all' ) {
		$settings = $this->get_settings( $property );
		return isset( $settings[ $setting_id ] );
	}

	/**
	 * Get a specific setting definition.
	 *
	 * @since 1.7.0.
	 *
	 * @param string $setting_id
	 *
	 * @return array|null
	 */
	protected function get_setting( $setting_id ) {
		$setting = $this->undefined;

		if ( $this->setting_exists( $setting_id ) ) {
			$settings = $this->get_settings();
			$setting = $settings[ $setting_id ];
		}

		return $setting;
	}

	/**
	 * Set a new value for a particular setting.
	 *
	 * Must be defined by the child class.
	 * - Should use the sanitize_value method before storing the value.
	 * - Should return true if value was successfully stored, otherwise false.
	 *
	 * @since 1.7.0.
	 *
	 * @param string $setting_id    The ID of the setting to set.
	 * @param mixed  $value         The value to assign to the setting.
	 *
	 * @return bool                 True if value was successfully set.
	 */
	abstract function set_value( $setting_id, $value );

	/**
	 * Unset the value for a particular setting.
	 *
	 * Must be defined by the child class.
	 * - Should return true if value was successfully removed, otherwise false.
	 *
	 * @since 1.7.0.
	 *
	 * @param string $setting_id    The ID of the setting to unset.
	 *
	 * @return bool                 True if the value was successfully unset.
	 */
	abstract function unset_value( $setting_id );

	/**
	 * Get the stored value of a setting, unaltered.
	 *
	 * Must be defined by the child class.
	 * - Should return the $undefined class property if the setting isn't found.
	 *
	 * @since 1.7.0.
	 *
	 * @param string $setting_id    The ID of the setting to retrieve.
	 *
	 * @return mixed|null           The value of the setting as it is in the database, or undefined if the setting doesn't exist.
	 */
	abstract function get_raw_value( $setting_id );
	
	/**
	 * Get the current value of a setting. Sanitize it first.
	 *
	 * This will return the default value for the setting if nothing is stored yet.
	 *
	 * @since 1.7.0.
	 *
	 * @param  string    $setting_id    The ID of the setting to retrieve.
	 * @param  string    $context       Optional. The context in which a setting needs to be sanitized.
	 *
	 * @return mixed|null
	 */
	public function get_value( $setting_id, $context = '' ) {
		$value = $this->undefined;

		if ( $this->setting_exists( $setting_id ) ) {
			$raw_value = $this->get_raw_value( $setting_id );

			// Sanitize the raw value.
			if ( $this->undefined !== $raw_value ) {
				$value = $this->sanitize_value( $raw_value, $setting_id, $context );
			}

			// Use the default if the value is still undefined.
			if ( $this->undefined === $value ) {
				$value = $this->get_default( $setting_id );
			}
		}

		/**
		 * Filter: Modify the current value for a particular setting.
		 *
		 * @since 1.7.0.
		 *
		 * @param mixed  $value         The current value of the setting.
		 * @param string $setting_id    The id of the setting.
		 * @param string $context       Optional. The context in which a setting needs to be sanitized.
		 */
		return apply_filters( "make_settings_{$this->type}_current_value", $value, $setting_id, $context );
	}

	/**
	 * Get the default value of a setting.
	 *
	 * @since 1.7.0.
	 *
	 * @param string $setting_id    The ID of the setting to retrieve.
	 *
	 * @return mixed|null
	 */
	public function get_default( $setting_id ) {
		$default_value = $this->undefined;

		if ( $this->setting_exists( $setting_id, 'default' ) ) {
			$setting = $this->get_setting( $setting_id );
			$default_value = $setting['default'];
		}

		/**
		 * Filter: Modify the default value for a particular setting.
		 *
		 * @since 1.7.0.
		 *
		 * @param string|array $default_value    The default value of the setting.
		 * @param string       $setting_id       The id of the setting.
		 */
		return apply_filters( "make_settings_{$this->type}_default_value", $default_value, $setting_id );
	}

	/**
	 * Determine if a setting is currently set to its default value.
	 *
	 * @since 1.7.0.
	 *
	 * @param  string     $setting_id    The ID of the setting to retrieve.
	 * @param  mixed|null $value         Optional. A value to compare to the default.
	 *
	 * @return bool
	 */
	public function is_default( $setting_id, $value = null ) {
		if ( $this->setting_exists( $setting_id, 'default' ) ) {
			$current_value = ( is_null( $value ) ) ? $this->get_value( $setting_id ) : $value;
			$default_value = $this->get_default( $setting_id );

			return $current_value === $default_value;
		}

		return false;
	}

	/**
	 * Get the name of the callback function used to sanitize a particular setting, in a particular context.
	 *
	 * @since 1.7.0.
	 *
	 * @param string $setting_id    The ID of the setting to retrieve.
	 * @param string $context       Optional. The context in which a setting needs to be sanitized.
	 *
	 * @return string|null
	 */
	public function get_sanitize_callback( $setting_id, $context = '' ) {
		$callback = $this->undefined;

		if ( $this->setting_exists( $setting_id ) ) {
			$setting = $this->get_setting( $setting_id );

			if ( $context && isset( $setting[ 'sanitize_' . $context ] ) ) {
				$callback = $setting[ 'sanitize_' . $context ];
			} else if ( isset( $setting['sanitize'] ) ) {
				$callback = $setting['sanitize'];
			}
		}

		/**
		 * Filter: Modify the name of the sanitize callback function for a particular setting.
		 *
		 * @since 1.7.0.
		 *
		 * @param string|array $callback      The name of the callback function.
		 * @param string       $setting_id    The id of the setting.
		 * @param string       $context       The context in which the setting needs to be sanitized.
		 */
		return apply_filters( "make_settings_{$this->type}_sanitize_callback", $callback, $setting_id, $context );
	}

	/**
	 * Determine if a setting has a sanitize callback for a particular context.
	 *
	 * All settings must have a generic 'sanitize' callback. This function is only looking for extra, context-specific ones.
	 *
	 * @since 1.7.0.
	 *
	 * @param string $setting_id
	 * @param string $context
	 *
	 * @return bool
	 */
	public function has_sanitize_callback( $setting_id, $context ) {
		if ( $this->setting_exists( $setting_id ) ) {
			$setting = $this->get_setting( $setting_id );

			if ( isset( $setting[ 'sanitize_' . $context ] ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Sanitize the value for a setting using the setting's specified callback function.
	 *
	 * @since 1.7.0.
	 *
	 * @param mixed  $value         The value to sanitize.
	 * @param string $setting_id    The ID of the setting to retrieve.
	 * @param string $context       Optional. The context in which a setting needs to be sanitized.
	 *
	 * @return mixed
	 */
	public function sanitize_value( $value, $setting_id, $context = '' ) {
		$sanitized_value = $this->undefined;

		if ( $this->setting_exists( $setting_id ) ) {
			$callback = $this->get_sanitize_callback( $setting_id, $context );

			if ( $callback && is_callable( $callback ) ) {
				/**
				 * Filter: Prepare the array of parameters to feed into the sanitize callback function.
				 *
				 * Some callbacks may require more than one parameter. This filter provides an opportunity
				 * to add additional items to the array that will become the callback's parameters.
				 *
				 * @since 1.7.0.
				 *
				 * @param array  $value         The array of parameters, initially containing only the value to be sanitized.
				 * @param string $setting_id    The id of the setting being sanitized.
				 * @param string $callback      The callable that will accept parameters.
				 */
				$prepared_value = apply_filters( "make_settings_{$this->type}_sanitize_callback_parameters", (array) $value, $setting_id, $callback );

				// Run the callback
				$sanitized_value = call_user_func_array( $callback, $prepared_value );
			} else {
				$this->error()->add_error( 'make_settings_callback_not_valid', sprintf( __( 'The sanitize callback (%1$s) for "%2$s" is not valid.', 'make' ), esc_html( print_r( $callback, true ) ), esc_html( $setting_id ) ) );
			}
		}

		return $sanitized_value;
	}
}
