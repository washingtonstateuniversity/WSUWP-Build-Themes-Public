<?php
/**
 * @package Make
 */

/**
 * Interface MAKE_Style_DataHelperInterface
 *
 * @since 1.7.0.
 */
interface MAKE_Style_DataHelperInterface extends MAKE_Util_ModulesInterface {
	public function parse_font_properties( $element, $force = false );

	public function parse_link_underline( $element, $selectors );

	public function get_cached_font_stack( $font );

	public function hex_to_rgb( $value );

	public function get_relative_size( $key = null );

	public function get_relative_font_size( $value, $percentage );

	public function convert_px_to_rem( $px );
}