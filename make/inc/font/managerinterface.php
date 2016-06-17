<?php
/**
 * @package Make
 */

/**
 * Interface MAKE_Font_ManagerInterface
 *
 * @since 1.7.0.
 */
interface MAKE_Font_ManagerInterface extends MAKE_Util_ModulesInterface {
	public function add_source( $source_id, $source );

	public function get_source( $source_id );

	public function has_source( $source_id );

	public function get_font_source( $font, $return = 'object' );

	public function get_font_data( $font, $source_id = null );

	public function get_font_stack( $font, $default = 'sans-serif', $source_id = null );

	public function get_font_choices( $source_id = null, $headings = true );

	public function sanitize_font_choice( $value, $source = null, $default = '' );
}