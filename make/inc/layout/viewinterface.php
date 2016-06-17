<?php
/**
 * @package Make
 */

/**
 * Interface MAKE_Layout_ViewInterface
 *
 * @since 1.7.0.
 */
interface MAKE_Layout_ViewInterface extends MAKE_Util_ModulesInterface {
	public function add_view( $view_id, array $args = array(), $overwrite = false );

	public function remove_view( $view_id );

	public function get_views( $property = 'all' );

	public function get_view( $view_id );

	public function get_sorted_views();

	public function view_exists( $view_id );

	public function get_view_label( $view_id );

	public function get_view_callback( $view_id, $context = '' );

	public function get_current_view( $context = '' );
}