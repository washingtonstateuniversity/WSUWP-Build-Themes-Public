<?php
/**
 * @package Make
 */

/**
 * Interface MAKE_Customizer_DataHelperInterface
 *
 * @since 1.7.0.
 */
interface MAKE_Customizer_DataHelperInterface extends MAKE_Util_ModulesInterface {
	public function get_typography_group_definitions( $element, $label, $description = '' );
}