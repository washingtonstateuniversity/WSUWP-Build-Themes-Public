<?php
/**
 * @package Make
 */


interface MAKE_Customizer_ControlsInterface extends MAKE_Util_ModulesInterface {
	public function get_panel_definitions();

	public function get_section_definitions();

	public function get_panel_sections( WP_Customize_Manager $wp_customize, $panel_id );

	public function get_section_controls( WP_Customize_Manager $wp_customize, $section_id );

	public function get_last_priority( array $items );
}