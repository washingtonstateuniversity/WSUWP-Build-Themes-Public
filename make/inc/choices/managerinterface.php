<?php
/**
 * @package Make
 */

/**
 * Interface MAKE_Choices_ManagerInterface
 *
 * @since 1.7.0.
 */
interface MAKE_Choices_ManagerInterface extends MAKE_Util_ModulesInterface {
	public function add_choice_sets( array $sets, $overwrite = false );

	public function remove_choice_sets( $set_ids );

	public function choice_set_exists( $set_id );

	public function get_choice_set( $set_id );

	public function get_choice_label( $value, $set_id );

	public function is_valid_choice( $value, $set_id );

	public function sanitize_choice( $value, $set_id, $default = '' );
}