<?php
/**
 * @package Make
 */

/**
 * Interface MAKE_Compatibility_MethodsInterface
 *
 * @since 1.7.0.
 */
interface MAKE_Compatibility_MethodsInterface extends MAKE_Util_ModulesInterface {
	public function deprecated_function( $function, $version, $replacement = null, $backtrace = true );

	public function deprecated_hook( $hook, $version, $message = null );

	public function doing_it_wrong( $function, $message, $version = null, $backtrace = true );
}