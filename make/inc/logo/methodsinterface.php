<?php
/**
 * @package Make
 */

/**
 * Interface MAKE_Logo_MethodsInterface
 *
 * @since 1.7.0.
 */
interface MAKE_Logo_MethodsInterface extends MAKE_Util_ModulesInterface {
	public function custom_logo_is_supported();

	public function has_logo();

	public function get_logo();
}