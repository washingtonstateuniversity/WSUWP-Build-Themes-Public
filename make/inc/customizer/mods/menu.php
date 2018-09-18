<?php
/**
 * @package Make
 */

// Bail if this isn't being included inside of a MAKE_Customizer_ControlsInterface.
if ( ! isset( $this ) || ! $this instanceof MAKE_Customizer_ControlsInterface ) {
    return;
}

// Section ID
$section_id = 'menu_locations';

// Section object
$section = $wp_customize->get_section( $section_id );

// Bail if the section isn't registered
if ( ! $section instanceof WP_Customize_Section ) {
	return;
}

$priority = new MAKE_Util_Priority( 10, 5 );

$controls = array(
	'mobile-menu' => array(
		'setting' => true,
		'control' => array(
			'control_type' => 'MAKE_Customizer_Control_Select',
			'label'   => __( 'Mobile Menu', 'make' ),
			'choices' => $this->thememod()->get_choice_set( 'mobile-menu' ),
		),
	),
);
$this->add_section_controls( $wp_customize, $section_id, $controls );