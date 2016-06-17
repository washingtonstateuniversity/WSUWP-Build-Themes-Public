<?php
/**
 * @package Make
 */

/**
 * Class MAKE_Choices_Manager
 *
 * Define and manage choice sets.
 *
 * @since 1.7.0.
 */
class MAKE_Choices_Manager extends MAKE_Util_Modules implements MAKE_Choices_ManagerInterface, MAKE_Util_LoadInterface {
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
	 * The collection of choice sets.
	 *
	 * @since 1.7.0.
	 *
	 * @var array
	 */
	protected $choice_sets = array();

	/**
	 * Indicator of whether the load routine has been run.
	 *
	 * @since 1.7.0.
	 *
	 * @var bool
	 */
	protected $loaded = false;

	/**
	 * Load data files.
	 *
	 * @since 1.7.0.
	 *
	 * @return void
	 */
	public function load() {
		if ( true === $this->is_loaded() ) {
			return;
		}

		// Load the default choices definitions
		$file = dirname( __FILE__ ) . '/definitions/choices.php';
		if ( is_readable( $file ) ) {
			include $file;
		}

		// Loading has occurred.
		$this->loaded = true;

		/**
		 * Action: Fires at the end of the choices object's load method.
		 *
		 * This action gives a developer the opportunity to add or modify choice sets
		 * and run additional load routines.
		 *
		 * @since 1.7.0.
		 *
		 * @param MAKE_Choices_Manager $choices    The choices object that has just finished loading.
		 */
		do_action( 'make_choices_loaded', $this );
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
	 * Add choice sets to the collection.
	 *
	 * Each choice set is an item in an associative array. The item's array key is the set ID. The item value
	 * is another associative array that contains individual choices where the key is the HTML option value and
	 * the value is the HTML option label.
	 *
	 * Example:
	 * array(
	 *     'horizontal-alignment' => array(
	 *         'left'   => __( 'Left', 'make' ),
	 *         'center' => __( 'Center', 'make' ),
	 *         'right'  => __( 'Right', 'make' ),
	 *     ),
	 * )
	 *
	 * @since 1.7.0.
	 *
	 * @param array $sets         Array of choice sets to add.
	 * @param bool  $overwrite    True overwrites an existing choice set with the same ID.
	 *
	 * @return bool               True if addition was successful, false if there was an error.
	 */
	public function add_choice_sets( array $sets, $overwrite = false ) {
		$existing_sets = $this->choice_sets;
		$new_sets = array();
		$return = true;

		// Validate each choice set before adding it.
		foreach ( $sets as $set_id => $choices ) {
			$set_id = sanitize_key( $set_id );

			// Choice set already exists, overwriting disabled.
			if ( isset( $existing_sets[ $set_id ] ) && true !== $overwrite ) {
				$this->error()->add_error( 'make_choices_set_already_exists', sprintf( __( 'The "%s" choice set can\'t be added because it already exists.', 'make' ), esc_html( $set_id ) ) );
				$return = false;
			}
			// Add a new choice set.
			else {
				$new_sets[ $set_id ] = $choices;
			}
		}

		// Add the valid new choices sets to the existing choices array.
		if ( ! empty( $new_sets ) ) {
			$this->choice_sets = array_merge( $existing_sets, $new_sets );
		}

		return $return;
	}

	/**
	 * Remove choice sets from the collection.
	 *
	 * @since 1.7.0.
	 *
	 * @param array|string $set_ids    The array of choice set IDs to remove, or 'all'.
	 *
	 * @return bool                    True if removal was successful, false if there was an error.
	 */
	public function remove_choice_sets( $set_ids ) {
		if ( 'all' === $set_ids ) {
			// Clear the entire choice sets array.
			$this->choice_sets = array();
			return true;
		}

		$return = true;

		foreach ( (array) $set_ids as $set_id ) {
			if ( isset( $this->choice_sets[ $set_id ] ) ) {
				unset( $this->choice_sets[ $set_id ] );
			} else {
				$this->error()->add_error( 'make_choices_cannot_remove', sprintf( __( 'The "%s" choice set can\'t be removed because it doesn\'t exist.', 'make' ), esc_html( $set_id ) ) );
				$return = false;
			}
		}

		return $return;
	}

	/**
	 * Getter for the choice sets that ensures the load routine has run first.
	 *
	 * @since 1.7.0.
	 *
	 * @return array
	 */
	protected function get_choice_sets() {
		if ( ! $this->is_loaded() ) {
			$this->load();
		}

		return $this->choice_sets;
	}

	/**
	 * Check if a choice set exists.
	 *
	 * @since 1.7.0.
	 *
	 * @param string $set_id
	 *
	 * @return bool
	 */
	public function choice_set_exists( $set_id ) {
		$choice_sets = $this->get_choice_sets();
		return isset( $choice_sets[ $set_id ] );
	}

	/**
	 * Get a particular choice set, using the set ID.
	 *
	 * @since 1.7.0.
	 *
	 * @param string $set_id    The ID of the choice set to retrieve.
	 *
	 * @return array            The array of choices.
	 */
	public function get_choice_set( $set_id ) {
		$choice_set = array();

		if ( $this->choice_set_exists( $set_id ) ) {
			$choice_sets = $this->get_choice_sets();
			$choice_set = $choice_sets[ $set_id ];
		}
		
		return $choice_set;
	}

	/**
	 * Get the label of an individual choice in a choice set.
	 *
	 * @since 1.7.0.
	 *
	 * @param string $value     The array key representing the value of the choice.
	 * @param string $set_id    The ID of the choice set.
	 *
	 * @return string           The choice label, or empty string if not a valid choice.
	 */
	public function get_choice_label( $value, $set_id ) {
		if ( ! $this->is_valid_choice( $value, $set_id ) ) {
			$this->error()->add_error( 'make_choices_not_valid_choice', sprintf( __( '"%1$s" is not a valid choice in the "%2$s" set.', 'make' ), esc_html( $value ), esc_html( $set_id ) ) );
			return '';
		}

		// Get the choice set.
		$choices = $this->get_choice_set( $set_id );

		return $choices[ $value ];
	}

	/**
	 * Determine if a value is a valid choice.
	 *
	 * @since 1.7.0.
	 *
	 * @param string $value     The array key representing the value of the choice.
	 * @param string $set_id    The ID of the choice set.
	 *
	 * @return bool             True if the choice exists in the set.
	 */
	public function is_valid_choice( $value, $set_id ) {
		$choices = $this->get_choice_set( $set_id );
		return isset( $choices[ $value ] );
	}

	/**
	 * Sanitize a value from a list of allowed values in a choice set.
	 *
	 * @since 1.7.0.
	 *
	 * @param mixed  $value      The value given to sanitize.
	 * @param string $set_id     The ID of the choice set to search for the given value.
	 * @param mixed  $default    The value to return if the given value is not valid.
	 *
	 * @return mixed             The sanitized value.
	 */
	public function sanitize_choice( $value, $set_id, $default = '' ) {
		if ( true === $this->is_valid_choice( $value, $set_id ) ) {
			return $value;
		} else {
			return $default;
		}
	}
}