<?php
/**
 * @package Make
 */

/**
 * Interface MAKE_Style_CSSInterface
 *
 * @since 1.7.0.
 */
interface MAKE_Style_CSSInterface {
	public function add( array $data );

	public function has_rules();

	public function build();
}