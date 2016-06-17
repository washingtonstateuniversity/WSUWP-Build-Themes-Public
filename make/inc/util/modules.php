<?php
/**
 * @package Make
 */

/**
 * Class MAKE_Util_Modules
 *
 * An object for managing object dependencies.
 *
 * This is an abstract class, so it is unusable on its own. It must be extended by another class.
 *
 * This class provides the extending class with properties and methods for loading and storing other objects as
 * dependencies/modules. The __construct method has two parameters that represent two different ways of injecting
 * these modules:
 *
 * - $api is a specialized instance of this class that contains all of the modules that make up the theme's API. If it
 *   is provided as a parameter (instead of null), any items in the class's $dependencies array with the same name as
 *   an API module can be loaded from the $api instance.
 * - $modules is an associative array where the key is the module name and the value is either an object instance or
 *   a class name. This can be used to provide modules that are not included in the $api instance. Only module names
 *   that are also in the dependencies array will be considered. Modules in this array will be preferenced over modules
 *   of the same name in the $api instance.
 *
 * This class uses the __call() magic method to allow access to each of its modules via a method of the same name. So
 * for example, to access the 'error' module, you can use $this->error() instead of $this->get_module( 'error' ).
 *
 * @since 1.7.0.
 */
abstract class MAKE_Util_Modules implements MAKE_Util_ModulesInterface {
	/**
	 * An associative array of required modules.
	 *
	 * Format:
	 * 'module name' => 'module interface or class name'
	 *
	 * @since 1.7.0.
	 *
	 * @var array
	 */
	protected $dependencies = array();

	/**
	 * Container for module objects.
	 *
	 * @since 1.7.0.
	 *
	 * @var array
	 */
	protected $modules = array();

	/**
	 * MAKE_Util_Modules constructor.
	 *
	 * @since 1.7.0.
	 *
	 * @param MAKE_APIInterface|null $api
	 * @param array                  $modules
	 */
	public function __construct( MAKE_APIInterface $api = null, array $modules = array() ) {
		if ( ! empty( $this->dependencies ) ) {
			$this->load_dependencies( $api, $modules );
		}
	}

	/**
	 * Allow modules to be accessed simply as a method with the same name.
	 *
	 * @since 1.7.0.
	 *
	 * @param string $name
	 * @param array  $arguments
	 *
	 * @return mixed|bool
	 */
	public function __call( $name, $arguments ) {
		if ( $this->has_module( $name ) ) {
			return $this->get_module( $name );
		} else {
			return trigger_error(
				sprintf(
					esc_html__( 'Call to undefined method %1$s::%2$s()', 'make' ),
					get_class( $this ),
					esc_html( $name )
				),
				E_USER_ERROR
			);
		}
	}

	/**
	 * Add a module and run its hook routine if it has one.
	 *
	 * @since 1.7.0.
	 *
	 * @param string $module_name
	 * @param object $module
	 *
	 * @return bool
	 */
	protected function add_module( $module_name, $module ) {
		// Module doesn't exist yet.
		if ( ! $this->has_module( $module_name ) ) {
			$this->modules[ $module_name ] = $module;
			if ( $this->modules[ $module_name ] instanceof MAKE_Util_HookInterface ) {
				if ( ! $this->modules[ $module_name ]->is_hooked() ) {
					$this->modules[ $module_name ]->hook();
				}
			}

			return true;
		}
		// Module already exists. Generate a warning.
		else {
			trigger_error(
				sprintf(
					esc_html__( 'The %1$s module cannot be added to %2$s because it already exists.', 'make' ),
					esc_html( $module_name ),
					get_class( $this )
				),
				E_USER_WARNING
			);
		}

		return false;
	}

	/**
	 * Add modules required by the dependencies array, either from the optional $modules parameter or from the
	 * $api parameter.
	 *
	 * @since 1.7.0.
	 *
	 * @param MAKE_APIInterface|null $api
	 * @param array                  $modules
	 */
	protected function load_dependencies( MAKE_APIInterface $api = null, array $modules = array() ) {
		foreach ( $this->dependencies as $dependency_name => $dependency_type ) {
			// Provided by modules array
			if ( isset( $modules[ $dependency_name ] ) ) {
				// Module is an object already.
				if ( is_object( $modules[ $dependency_name ] ) ) {
					$module_instance = $modules[ $dependency_name ];
				}
				// Module is a name string. Create an instance.
				else {
					$class_parents = class_parents( $modules[ $dependency_name ] );

					if ( $class_parents && in_array( 'MAKE_Util_Modules', $class_parents ) ) {
						$module_instance = $this->create_instance( $modules[ $dependency_name ], array( $api ) );
					} else {
						$module_instance = $this->create_instance( $modules[ $dependency_name ] );
					}
				}

				if ( is_a( $module_instance, $dependency_type ) ) {
					$this->add_module( $dependency_name, $module_instance );
					continue;
				}
			}
			// Provided by API
			else if ( ! is_null( $api ) && $api->has_module( $dependency_name ) && is_a( $api->inject_module( $dependency_name ), $dependency_type ) ) {
				$this->add_module( $dependency_name, $api->inject_module( $dependency_name ) );
				continue;
			}

			// Dependency is missing
			trigger_error(
				sprintf(
					esc_html__( '%1$s does not have a valid %2$s dependency', 'make' ),
					get_class( $this ),
					$dependency_name
				),
				E_USER_ERROR
			);
		}
	}

	/**
	 * Create a new instance of a class, given the class's name as a string, in a way that is compatible with PHP 5.2.
	 *
	 * @since 1.7.0.
	 *
	 * @param string $class_name       The name of the class to create.
	 * @param array  $instance_args    The class's __construct parameters, as an array.
	 *
	 * @return object
	 */
	protected function create_instance( $class_name, $instance_args = array() ) {
		$reflection = new ReflectionClass( $class_name );

		if ( ! empty( $instance_args ) ) {
			return $reflection->newInstanceArgs( $instance_args );
		} else {
			return $reflection->newInstance();
		}
	}

	/**
	 * Return the specified module and run its load routine.
	 *
	 * @since 1.7.0.
	 *
	 * @param string $module_name
	 *
	 * @return mixed
	 */
	public function get_module( $module_name ) {
		// Module exists.
		if ( $this->has_module( $module_name ) ) {
			if ( $this->modules[ $module_name ] instanceof MAKE_Util_LoadInterface ) {
				if ( ! $this->modules[ $module_name ]->is_loaded() ) {
					$this->modules[ $module_name ]->load();
				}
			}

			return $this->modules[ $module_name ];
		}

		// Module doesn't exist. Generate an error if possible.
		else if ( $this->has_module( 'error' ) && $this->modules['error'] instanceof MAKE_Error_CollectorInterface ) {
			$this->modules['error']->add_error( 'make_util_module_not_valid', sprintf( __( 'The "%1$s" module can\'t be retrieved from %2$s because it doesn\'t exist.', 'make' ), esc_html( $module_name ), get_class( $this ) ) );
		}

		return null;
	}

	/**
	 * Check if a module exists.
	 *
	 * @since 1.7.0.
	 *
	 * @param string $module_name
	 *
	 * @return bool
	 */
	public function has_module( $module_name ) {
		return isset( $this->modules[ $module_name ] );
	}
}