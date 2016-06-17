<?php
/**
 * @package Make
 */

/**
 * Interface MAKE_SocialIcons_ManagerInterface
 *
 * @since 1.7.0.
 */
interface MAKE_SocialIcons_ManagerInterface extends MAKE_Util_ModulesInterface {
	public function add_icons( array $icons, $overwrite = false );

	public function remove_icons( $icons );

	public function get_icons();

	public function find_match( $item );

	public function has_icon_data();

	public function render_icons();
}