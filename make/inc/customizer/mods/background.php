<?php
/**
 * @package Make
 */

// Bail if this isn't being included inside of a MAKE_Customizer_ControlsInterface.
if ( ! isset( $this ) || ! $this instanceof MAKE_Customizer_ControlsInterface ) {
	return;
}

// Section ID
$section_id = 'background_image';

// Section object
$section = $wp_customize->get_section( $section_id );

// Bail if the section isn't registered
if ( ! $section instanceof WP_Customize_Section ) {
	return;
}

$priority = new MAKE_Util_Priority( 10, 5 );

// Move and rename Background Color control to Global section of Color panel
$wp_customize->get_control( 'background_color' )->section = $this->prefix . 'color';
$wp_customize->get_control( 'background_color' )->label = __( 'Site Background Color', 'make' );
$wp_customize->get_control( 'background_color' )->priority = (int) $wp_customize->get_control( $this->prefix . 'color-group-global-background' )->priority + 5;

// Move Background Image section to Background Images panel
$section->panel = $this->prefix . 'background-images';

// Set section title
$section->title = __( 'Site', 'make' );

// Set section priority
$header_priority = $wp_customize->get_section( $this->prefix . 'header-background' )->priority;
$section->priority = $header_priority - 5;

// Reconfigure image and repeat controls
$wp_customize->get_control( 'background_image' )->label = __( 'Background Image', 'make' );
$wp_customize->get_control( 'background_image' )->priority = $priority->add();
$wp_customize->get_control( 'background_repeat' )->label = __( 'Repeat', 'make' );
$wp_customize->get_control( 'background_repeat' )->priority = $priority->add();

// Remove position and attachment controls
$wp_customize->remove_control( 'background_preset' );
$wp_customize->remove_control( 'background_size' );
$wp_customize->remove_control( 'background_repeat' );
$wp_customize->remove_control( 'background_attachment' );

// Add replacement and new controls
$controls = array(
	'background_attachment'     => array(
		'setting' => true,
		'control' => array(
			'control_type' => 'MAKE_Customizer_Control_Radio',
			'label'   => __( 'Attachment', 'make' ),
			'mode'    => 'buttonset',
			'choices' => $this->thememod()->get_choice_set( 'background_attachment' ),
		),
	),
	'background_size' => array(
		'setting' => true,
		'control' => array(
			'control_type' => 'MAKE_Customizer_Control_Radio',
			'label'   => __( 'Size', 'make' ),
			'mode'    => 'buttonset',
			'choices' => $this->thememod()->get_choice_set( 'background_size' ),
		),
	),
);

$this->add_section_controls( $wp_customize, $section_id, $controls, $priority->add() );