<?php
/**
 * @package Make
 */

// Bail if this isn't being included inside of a MAKE_Customizer_ControlsInterface.
if ( ! isset( $this ) || ! $this instanceof MAKE_Customizer_ControlsInterface ) {
	return;
}

// Section ID
$section_id   = 'static_front_page';

// Section object
$section      = $wp_customize->get_section( $section_id );

// Bail if the section isn't registered
if ( ! $section instanceof WP_Customize_Section ) {
	return;
}

// Move Static Front Page section to General panel
$section->panel = $this->prefix . 'general';

// Set Static Front Page section priority
$section->priority = $wp_customize->get_section( $this->prefix . 'social' )->priority + 5;