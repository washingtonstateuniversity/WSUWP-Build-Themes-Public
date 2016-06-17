<?php
/**
 * @package Make
 */

/**
 * Interface MAKE_Integration_ManagerInterface
 *
 * @since 1.7.0.
 */
interface MAKE_Integration_ManagerInterface extends MAKE_Util_ModulesInterface {
	public function add_integration( $integration_name, $integration );

	public function get_integration( $integration_name );

	public function has_integration( $integration_name );

	public function is_plugin_active( $plugin_relative_path );
}