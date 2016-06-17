<?php
/**
 * @package Make
 */

// Bail if this isn't being included inside of a MAKE_Customizer_ControlsInterface.
if ( ! isset( $this ) || ! $this instanceof MAKE_Customizer_ControlsInterface ) {
	return;
}

// Panel ID
$panel = $this->prefix . 'background-images';

$regions = array(
	'header' => __( 'Header', 'make' ),
	'main'   => __( 'Main Column', 'make' ),
	'footer' => __( 'Footer', 'make' ),
);

foreach ( $regions as $prefix => $title ) {
	$this->add_section_definitions( $prefix . '-background', array(
		'panel' => $panel,
		'title' => $title,
		'controls' => array(
			$prefix . '-background-image'    => array(
				'setting' => true,
				'control' => array(
					'control_type' => 'WP_Customize_Image_Control',
					'label'        => __( 'Background Image', 'make' ),
				),
			),
			$prefix . '-background-repeat'   => array(
				'setting' => true,
				'control' => array(
					'label'   => __( 'Repeat', 'make' ),
					'type'    => 'radio',
					'choices' => $this->thememod()->get_choice_set( $prefix . '-background-repeat' ),
				),
			),
			$prefix . '-background-position' => array(
				'setting' => true,
				'control' => array(
					'control_type' => 'MAKE_Customizer_Control_BackgroundPosition',
					'label'   => __( 'Position', 'make' ),
					'choices' => $this->thememod()->get_choice_set( $prefix . '-background-position' ),
				),
			),
			$prefix . '-background-attachment'     => array(
				'setting' => true,
				'control' => array(
					'control_type' => 'MAKE_Customizer_Control_Radio',
					'label'   => __( 'Attachment', 'make' ),
					'mode'    => 'buttonset',
					'choices' => $this->thememod()->get_choice_set( $prefix . '-background-attachment' ),
				),
			),
			$prefix . '-background-size'     => array(
				'setting' => true,
				'control' => array(
					'control_type' => 'MAKE_Customizer_Control_Radio',
					'label'   => __( 'Size', 'make' ),
					'mode'    => 'buttonset',
					'choices' => $this->thememod()->get_choice_set( $prefix . '-background-size' ),
				),
			),
		)
	) );
}

// Check for deprecated filters
foreach ( array( 'make_customizer_background_sections', 'make_customizer_background_image_group_definitions' ) as $filter ) {
	if ( has_filter( $filter ) ) {
		$this->compatibility->deprecated_hook(
			$filter,
			'1.7.0',
			sprintf(
				esc_html__( 'To add or modify Customizer sections and controls, use the %1$s hook instead, or the core %2$s methods.', 'make' ),
				'<code>make_customizer_sections</code>',
				'<code>$wp_customize</code>'
			)
		);
	}
}