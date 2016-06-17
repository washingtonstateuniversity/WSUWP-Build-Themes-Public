<?php
/**
 * @package Make
 */

/**
 * Interface MAKE_Style_ManagerInterface
 *
 * @since 1.7.0.
 */
interface MAKE_Style_ManagerInterface extends MAKE_Util_ModulesInterface {
	public function get_styles_as_inline();

	public function get_file_url();
}