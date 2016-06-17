<?php
/**
 * @package Make
 */

/**
 * Interface MAKE_Font_Source_GoogleInterface
 *
 * @since 1.7.0.
 */
interface MAKE_Font_Source_GoogleInterface extends MAKE_Font_Source_BaseInterface {
	public function build_url( array $fonts, array $subsets = array() );

	public function build_loader_array( array $fonts, array $subsets = array() );

	public function get_subsets();

	public function sanitize_subset( $value, $default = '' );
}