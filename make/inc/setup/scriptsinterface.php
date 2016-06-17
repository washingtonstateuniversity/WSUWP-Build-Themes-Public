<?php
/**
 * @package Make
 */

/**
 * Interface MAKE_Setup_ScriptsInterface
 *
 * @since 1.7.0.
 */
interface MAKE_Setup_ScriptsInterface extends MAKE_Util_ModulesInterface {
	public function get_css_directory();

	public function get_css_directory_uri();

	public function get_js_directory();

	public function get_js_directory_uri();

	public function get_located_file_url( $file_names );

	public function is_registered( $dependency_id, $type );

	public function add_dependency( $recipient_id, $dependency_id, $type );

	public function remove_dependency( $recipient_id, $dependency_id, $type );

	public function update_version( $recipient_id, $version, $type );

	public function get_url( $dependency_id, $type );

	public function get_google_url( $force = false );
}