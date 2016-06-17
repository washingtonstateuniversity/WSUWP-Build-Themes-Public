<?php
/**
 * @package Make
 */

/**
 * Interface MAKE_Settings_ThemeModInterface
 *
 * @since 1.7.0.
 */
interface MAKE_Settings_ThemeModInterface extends MAKE_Settings_BaseInterface {
	public function get_choice_set( $setting_id, $id_only = false );
}