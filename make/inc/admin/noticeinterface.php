<?php
/**
 * @package Make
 */

/**
 * Interface MAKE_Admin_NoticeInterface
 *
 * @since 1.7.0.
 */
interface MAKE_Admin_NoticeInterface {
	public function register_admin_notice( $id, $message, array $args = array() );

	public function register_one_time_admin_notice( $message, WP_User $user = null, array $args = array() );
}