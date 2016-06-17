<?php
/**
 * @package Make
 */

/**
 * Interface MAKE_APIInterface
 *
 * @since 1.7.0.
 */
interface MAKE_APIInterface extends MAKE_Util_ModulesInterface {
	public function inject_module( $module_name );
}