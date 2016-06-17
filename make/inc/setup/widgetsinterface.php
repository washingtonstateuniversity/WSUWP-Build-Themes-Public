<?php
/**
 * @package Make
 */


interface MAKE_Setup_WidgetsInterface extends MAKE_Util_ModulesInterface {
	public function get_widget_display_args( $sidebar_id );

	public function has_sidebar( $location );
}