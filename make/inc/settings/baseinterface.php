<?php
/**
 * @package Make
 */

/**
 * Interface MAKE_SettingsInterface
 *
 * @since 1.7.0.
 */
interface MAKE_Settings_BaseInterface extends MAKE_Util_ModulesInterface {
	public function add_settings( array $settings, array $default_props = array(), $overwrite = false );

	public function remove_settings( $setting_ids );

	public function get_settings( $property = 'all' );

	public function setting_exists( $setting_id, $property = 'all' );

	// The abstract functions can't be included in the interface because of a bug in PHP versions
	// prior to 5.3.9. @link https://bugs.php.net/bug.php?id=43200

	//public function set_value( $setting_id, $value );

	//public function unset_value( $setting_id );

	//public function get_raw_value( $setting_id );

	public function get_value( $setting_id, $context = '' );

	public function get_default( $setting_id );

	public function is_default( $setting_id, $value = null );

	public function get_sanitize_callback( $setting_id, $context = '' );

	public function has_sanitize_callback( $setting_id, $context );

	public function sanitize_value( $value, $setting_id, $context = '' );
}