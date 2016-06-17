<?php
/**
 * @package Make
 */

// Bail if this isn't being included inside of a MAKE_Customizer_ControlsInterface.
if ( ! isset( $this ) || ! $this instanceof MAKE_Customizer_ControlsInterface ) {
	return;
}

// Section ID
$section_id = 'title_tagline';

// Section object
$section = $wp_customize->get_section( $section_id );

$priority = new MAKE_Util_Priority( 10, 5 );

// Move Site Title & Tagline section to General panel
$section->panel = $this->prefix . 'general';

// Add note beneath Logo control if it is supported and if a retina logo was previously set
if ( $this->logo()->custom_logo_is_supported() && ! is_null( $this->thememod()->get_raw_value( 'logo-retina' ) ) ) {
	$logo_control = $wp_customize->get_control( 'custom_logo' );
	if ( $logo_control instanceof WP_Customize_Control ) {
		$controls = array(
			'make-custom-logo-notice' => array(
				'control' => array(
					'control_type' => 'MAKE_Customizer_Control_Html',
					'html'         => sprintf(
						'<span class="description">%s</span>',
						sprintf(
							wp_kses( __( 'To continue displaying a retina version of your logo, use a plugin such as <a href="%1$s" target="_blank">%2$s</a>.', 'make' ), array( 'a' => array( 'href' => true, 'target' => true ) ) ),
							'https://wordpress.org/plugins/wp-retina-2x/',
							'WP Retina 2x'
						)
					),
					'priority' => $logo_control->priority + 1,
				),
			),
		);
		$this->add_section_controls( $wp_customize, $section_id, $controls );
	}
}

// Reset priorities on Site Title control
$wp_customize->get_control( 'blogname' )->priority = $priority->add();

// Hide Site Title option
$controls = array(
	'hide-site-title' => array(
		'setting' => array(
			'transport'         => 'postMessage',
		),
		'control' => array(
			'label' => __( 'Hide Site Title', 'make' ),
			'type'  => 'checkbox',
		),
	),
);
$new_priority = $this->add_section_controls( $wp_customize, $section_id, $controls, $priority->add() );
$priority->set( $new_priority );

// Reset priorities on Tagline control
$wp_customize->get_control( 'blogdescription' )->priority = $priority->add();

// Hide Tagline option
$controls = array(
	'hide-tagline' => array(
		'setting' => array(
			'transport'         => 'postMessage',
		),
		'control' => array(
			'label' => __( 'Hide Tagline', 'make' ),
			'type'  => 'checkbox',
		),
	),
);
$new_priority = $this->add_section_controls( $wp_customize, $section_id, $controls, $priority->add() );
$priority->set( $new_priority );