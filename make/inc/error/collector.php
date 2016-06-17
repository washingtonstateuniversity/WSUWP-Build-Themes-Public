<?php
/**
 * @package Make
 */

/**
 * Class MAKE_Error_Collector
 *
 * A tool for collecting Make-related error messages and outputting them all in one place.
 *
 * @since 1.7.0.
 */
final class MAKE_Error_Collector extends MAKE_Util_Modules implements MAKE_Error_CollectorInterface {
	/**
	 * An associative array of required modules.
	 *
	 * @since 1.7.0.
	 *
	 * @var array
	 */
	protected $dependencies = array(
		'errors'        => 'WP_Error',
		'error_display' => 'MAKE_Error_DisplayInterface'
	);

	/**
	 * Switch for showing errors.
	 *
	 * @since 1.7.0.
	 *
	 * @var bool
	 */
	private $show_errors = true;

	/**
	 * MAKE_Error_Collector constructor.
	 *
	 * @since 1.7.0.
	 *
	 * @param MAKE_APIInterface|null $api
	 * @param array                  $modules
	 */
	public function __construct( MAKE_APIInterface $api = null, array $modules = array() ) {
		/**
		 * Filter: Toggle for showing Make errors.
		 *
		 * @since 1.7.0.
		 *
		 * @param bool    $show_errors    True to show errors.
		 */
		$this->show_errors = apply_filters( 'make_show_errors', current_user_can( 'install_themes' ) );

		// Module defaults.
		$modules = wp_parse_args( $modules, array(
			'errors' => 'WP_Error',
		) );

		// Only load the display module if errors should be shown.
		if ( true === $this->show_errors ) {
			$modules['error_display'] = new MAKE_Error_Display( $api, array( 'error' => $this ) );
		} else {
			unset( $this->dependencies['error_display'] );
		}

		// Load dependencies.
		parent::__construct( $api, $modules );
	}

	/**
	 * Wrapper to add an error to the injected instance of WP_Error.
	 *
	 * @since 1.7.0.
	 *
	 * @param string $code
	 * @param string $message
	 * @param string $data
	 */
	public function add_error( $code, $message, $data = '' ) {
		$this->errors()->add( $code, $message, $data );
	}

	/**
	 * Check if a particular error code has been added.
	 *
	 * @since 1.7.0.
	 *
	 * @param string $code
	 *
	 * @return bool
	 */
	public function has_code( $code ) {
		return in_array( $code, $this->get_codes() );
	}

	/**
	 * Check if any errors have been added.
	 *
	 * @since 1.7.0.
	 *
	 * @return bool
	 */
	public function has_errors() {
		return ! empty( $this->errors()->errors );
	}

	/**
	 * Wrapper to get the list of error codes from the WP_Error object.
	 *
	 * @since 1.7.0.
	 *
	 * @return array
	 */
	public function get_codes() {
		return $this->errors()->get_error_codes();
	}

	/**
	 * Wrapper to get the list of error messages for a particular code from the WP_Error object.
	 *
	 * @since 1.7.0.
	 *
	 * @param string $code
	 *
	 * @return array
	 */
	public function get_messages( $code = '' ) {
		return $this->errors()->get_error_messages( $code );
	}

	/**
	 * Generate a backtrace and return either as an ordered list or a raw array.
	 *
	 * Based on wp_debug_backtrace_summary() in Core.
	 *
	 * @since 1.7.0.
	 *
	 * @param array  $ignore_class    An array of class names to ignore in the call stack.
	 * @param string $output          'list' outputs an HTML ordered list. Otherwise an array.
	 *
	 * @return array|string
	 */
	public function generate_backtrace( array $ignore_class = array(), $output = 'list' ) {
		if ( version_compare( PHP_VERSION, '5.2.5', '>=' ) ) {
			$trace = debug_backtrace( false );
		} else {
			$trace = debug_backtrace();
		}

		// Add the error collector to the ignore class list.
		$ignore_class[] = get_class( $this );

		/**
		 * Filter: Change the number of steps shown in a Make Error backtrace.
		 *
		 * @since 1.7.0.
		 *
		 * @param int $limit    The number of backtrace steps to show.
		 */
		$limit = absint( apply_filters( 'make_error_backtrace_limit', 1 ) );

		// Start the stack
		$stack = array();
		$count = 0;

		foreach ( $trace as $call ) {
			if ( $count >= $limit ) {
				break;
			}

			if ( isset( $call['class'] ) ) {
				// Skip calls from classes in the ignore class array.
				if ( in_array( $call['class'], $ignore_class ) ) {
					continue;
				}
				$caller = "{$call['class']}{$call['type']}{$call['function']}";
			} else {
				if ( in_array( $call['function'], array( 'do_action', 'apply_filters' ) ) ) {
					$caller = "{$call['function']}( '{$call['args'][0]}' )";
				} else if ( in_array( $call['function'], array( 'include', 'include_once', 'require', 'require_once' ) ) ) {
					$caller = $call['function'] . "( '" . str_replace( array( WP_CONTENT_DIR, ABSPATH ) , '', $call['args'][0] ) . "' )";
				} else {
					$caller = $call['function'];
				}
			}

			if ( isset( $call['file'] ) && isset( $call['line'] ) ) {
				$caller .= " in <strong>{$call['file']}</strong> on line <strong>{$call['line']}</strong>";
			}

			$stack[] = $caller;
			$count++;
		}

		if ( 'list' === $output ) {
			if ( ! empty( $stack ) ) {
				return '<ol><li>' . implode( '</li><li>', $stack ) . '</li></ol>';
			} else {
				return '';
			}
		} else {
			return $stack;
		}
	}
}
