<?php
/**
 * @package Make
 */

/**
 * Interface MAKE_Font_Source_BaseInterface
 *
 * @since 1.7.0.
 */
interface MAKE_Font_Source_BaseInterface extends MAKE_Util_ModulesInterface {
	public function get_id();

	public function get_label();

	public function get_priority();

	public function get_font_data( $font = null );

	public function has_font( $font );

	public function get_font_choices();

	public function get_font_stack( $font, $default_stack = 'sans-serif' );
}