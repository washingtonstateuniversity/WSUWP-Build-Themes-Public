<?php
/**
 * @package Make
 */

// Bail if this isn't being included inside of a MAKE_Customizer_ControlsInterface.
if ( ! isset( $this ) || ! $this instanceof MAKE_Customizer_ControlsInterface ) {
	return;
}

// Panel ID
$panel = $this->prefix . 'color';

// Global
$this->add_section_definitions( 'color', array(
	'panel'   => $panel,
	'title'   => __( 'Global', 'make' ),
	'description' => sprintf(
		'<a href="https://thethemefoundry.com/docs/make-docs/customizer/color-scheme/" target="_blank">%s</a>',
		esc_html__( 'Need help?', 'make' )
	),
	'controls' => array(
		'color-group-color-scheme'      => array(
			'control' => array(
				'control_type' => 'MAKE_Customizer_Control_Html',
				'html'         => '<h4 class="make-group-title">' . esc_html__( 'Color Scheme', 'make' ) . '</h4>',
			),
		),
		'color-primary'                 => array(
			'setting' => true,
			'control' => array(
				'control_type' => 'WP_Customize_Color_Control',
				'label'        => __( 'Primary Color', 'make' ),
				'description'  => sprintf(
					__( 'Used for: %s', 'make' ),
					__( 'links', 'make' )
				),
			),
		),
		'color-secondary'               => array(
			'setting' => true,
			'control' => array(
				'control_type' => 'WP_Customize_Color_Control',
				'label'        => __( 'Secondary Color', 'make' ),
				'description'  => sprintf(
					__( 'Used for: %s', 'make' ),
					__( 'form inputs, table borders, ruled lines, slider buttons', 'make' )
				),
			),
		),
		'color-text'                    => array(
			'setting' => true,
			'control' => array(
				'control_type' => 'WP_Customize_Color_Control',
				'label'        => __( 'Text Color', 'make' ),
				'description'  => sprintf(
					__( 'Used for: %s', 'make' ),
					__( 'most text', 'make' )
				),
			),
		),
		'color-detail'                  => array(
			'setting' => true,
			'control' => array(
				'control_type' => 'WP_Customize_Color_Control',
				'label'        => __( 'Detail Color', 'make' ),
				'description'  => sprintf(
					__( 'Used for: %s', 'make' ),
					__( 'UI icons', 'make' )
				),
			),
		),
		'color-group-global-link'       => array(
			'control' => array(
				'control_type' => 'MAKE_Customizer_Control_Html',
				'html'         => '<h4 class="make-group-title">' . esc_html__( 'Links', 'make' ) . '</h4>',
			),
		),
		'color-primary-link'            => array(
			'setting' => true,
			'control' => array(
				'control_type' => 'WP_Customize_Color_Control',
				'label'        => __( 'Link Hover/Focus Color', 'make' ),
				'description'  => __( 'The default link color is controlled by the "Primary Color" option above.', 'make' ),
			),
		),
		'color-group-global-button'     => array(
			'control' => array(
				'control_type' => 'MAKE_Customizer_Control_Html',
				'html'         => '<h4 class="make-group-title">' . esc_html__( 'Buttons', 'make' ) . '</h4>',
			),
		),
		'color-button-text'             => array(
			'setting' => true,
			'control' => array(
				'control_type' => 'WP_Customize_Color_Control',
				'label'        => __( 'Button Text Color', 'make' ),
			),
		),
		'color-button-background'       => array(
			'setting' => true,
			'control' => array(
				'control_type' => 'WP_Customize_Color_Control',
				'label'        => __( 'Button Background Color', 'make' ),
			),
		),
		'color-button-text-hover'       => array(
			'setting' => true,
			'control' => array(
				'control_type' => 'WP_Customize_Color_Control',
				'label'        => __( 'Button Text Hover/Focus Color', 'make' ),
			),
		),
		'color-button-background-hover' => array(
			'setting' => true,
			'control' => array(
				'control_type' => 'WP_Customize_Color_Control',
				'label'        => __( 'Button Background Hover/Focus Color', 'make' ),
			),
		),
		'color-group-global-background' => array(
			'control' => array(
				'control_type' => 'MAKE_Customizer_Control_Html',
				'html'         => '<h4 class="make-group-title">' . esc_html__( 'Background', 'make' ) . '</h4>',
			),
		),
		/**
		 * Site Background Color gets inserted here.
		 */
		'main-background-color-heading' => array(
			'control' => array(
				'control_type' => 'MAKE_Customizer_Control_Html',
				'label'        => __( 'Main Column Background Color', 'make' ),
			),
		),
		'main-background-color'         => array(
			'setting' => true,
			'control' => array(
				'control_type' => 'WP_Customize_Color_Control',
			),
		),
		'main-background-color-opacity' => array(
			'setting' => true,
			'control' => array(
				'control_type' => 'MAKE_Customizer_Control_Range',
				'label'        => __( 'Opacity', 'make' ),
				'input_attrs'  => array(
					'min'  => 0,
					'max'  => 1.01, // Needs to be slightly over 1 to handle rounding error.
					'step' => 0.05,
				),
			),
		),
	),
) );

// Site Header
$this->add_section_definitions( 'color-header', array(
	'panel'   => $panel,
	'title'   => __( 'Site Header', 'make' ),
	'controls' => array(
		'header-text-color'           => array(
			'setting' => true,
			'control' => array(
				'control_type' => 'WP_Customize_Color_Control',
				'label'        => __( 'Text Color', 'make' ),
			),
		),
		'header-background-color-heading' => array(
			'control' => array(
				'control_type' => 'MAKE_Customizer_Control_Html',
				'label'   => __( 'Background Color', 'make' ),
			),
		),
		'header-background-color'     => array(
			'setting' => true,
			'control' => array(
				'control_type' => 'WP_Customize_Color_Control',
			),
		),
		'header-background-color-opacity'     => array(
			'setting' => true,
			'control' => array(
				'control_type' => 'MAKE_Customizer_Control_Range',
				'label'   => __( 'Opacity', 'make' ),
				'input_attrs' => array(
					'min'  => 0,
					'max'  => 1.01, // Needs to be slightly over 1 to handle rounding error.
					'step' => 0.05,
				),
			),
		),
	),
) );

// Site Title & Tagline
$this->add_section_definitions( 'color-site-title-tagline', array(
	'panel'   => $panel,
	'title'   => __( 'Site Title &amp; Tagline', 'make' ),
	'description' => sprintf(
		__( 'These options override the %s option in the %s section.', 'make' ),
		__( 'Text Color', 'make' ),
		__( 'Site Header', 'make' )
	),
	'controls' => array(
		'color-site-title'            => array(
			'setting' => true,
			'control' => array(
				'control_type' => 'WP_Customize_Color_Control',
				'label'        => __( 'Site Title Color', 'make' ),
			),
		),
		'color-site-tagline'            => array(
			'setting' => true,
			'control' => array(
				'control_type' => 'WP_Customize_Color_Control',
				'label'        => __( 'Tagline Color', 'make' ),
			),
		),
	),
) );

// Main Menu
$this->add_section_definitions( 'color-main-menu', array(
	'panel'   => $panel,
	'title'   => __( 'Main Menu', 'make' ),
	'controls' => array(
		'color-group-nav-item' => array(
			'control' => array(
				'control_type' => 'MAKE_Customizer_Control_Html',
				'html'  => '<h4 class="make-group-title">' . esc_html__( 'Menu Items', 'make' ) . '</h4>',
			),
		),
		'color-nav-text'            => array(
			'setting' => true,
			'control' => array(
				'control_type' => 'WP_Customize_Color_Control',
				'label'        => __( 'Text Color', 'make' ),
				'description' => sprintf(
					__( 'This option overrides the %s option in the %s section.', 'make' ),
					__( 'Text Color', 'make' ),
					__( 'Site Header', 'make' )
				),
			),
		),
		'color-nav-text-hover'            => array(
			'setting' => true,
			'control' => array(
				'control_type' => 'WP_Customize_Color_Control',
				'label'        => __( 'Hover/Focus Text Color', 'make' ),
			),
		),
		'color-group-subnav-item' => array(
			'control' => array(
				'control_type' => 'MAKE_Customizer_Control_Html',
				'html'  => '<h4 class="make-group-title">' . esc_html__( 'Sub-Menu Items', 'make' ) . '</h4>',
			),
		),
		'color-subnav-text'            => array(
			'setting' => true,
			'control' => array(
				'control_type' => 'WP_Customize_Color_Control',
				'label'        => __( 'Text Color', 'make' ),
				'description' => sprintf(
					__( 'This option overrides the %s option in the %s section.', 'make' ),
					__( 'Text Color', 'make' ),
					__( 'Site Header', 'make' )
				),
			),
		),
		'color-subnav-text-hover'            => array(
			'setting' => true,
			'control' => array(
				'control_type' => 'WP_Customize_Color_Control',
				'label'        => __( 'Hover/Focus Text Color', 'make' ),
			),
		),
		'color-subnav-detail'            => array(
			'setting' => true,
			'control' => array(
				'control_type' => 'WP_Customize_Color_Control',
				'label'        => __( 'Detail Color', 'make' ),
				'description' => sprintf(
					__( 'This option overrides the %s option in the %s section.', 'make' ),
					__( 'Detail Color', 'make' ),
					__( 'Global', 'make' )
				),
			),
		),
		'color-subnav-background'            => array(
			'setting' => true,
			'control' => array(
				'control_type' => 'WP_Customize_Color_Control',
				'label'        => __( 'Background Color', 'make' ),
				'description' => sprintf(
					__( 'This option overrides the %s option in the %s section.', 'make' ),
					__( 'Secondary Color', 'make' ),
					__( 'Global', 'make' )
				),
			),
		),
		'color-subnav-background-opacity'     => array(
			'setting' => true,
			'control' => array(
				'control_type' => 'MAKE_Customizer_Control_Range',
				'label'   => __( 'Opacity', 'make' ),
				'input_attrs' => array(
					'min'  => 0,
					'max'  => 1.01, // Needs to be slightly over 1 to handle rounding error.
					'step' => 0.05,
				),
			),
		),
		'color-subnav-background-hover'            => array(
			'setting' => true,
			'control' => array(
				'control_type' => 'WP_Customize_Color_Control',
				'label'        => __( 'Hover/Focus Background Color', 'make' ),
				'description' => sprintf(
					__( 'This option overrides the %s option in the %s section.', 'make' ),
					__( 'Primary Color', 'make' ),
					__( 'Global', 'make' )
				),
			),
		),
		'color-subnav-background-hover-opacity'     => array(
			'setting' => true,
			'control' => array(
				'control_type' => 'MAKE_Customizer_Control_Range',
				'label'   => __( 'Opacity', 'make' ),
				'input_attrs' => array(
					'min'  => 0,
					'max'  => 1.01, // Needs to be slightly over 1 to handle rounding error.
					'step' => 0.05,
				),
			),
		),
		'color-group-current-item' => array(
			'control' => array(
				'control_type' => 'MAKE_Customizer_Control_Html',
				'html'  => '<h4 class="make-group-title">' . esc_html__( 'Current Item', 'make' ) . '</h4>',
			),
		),
		'color-nav-current-item-background'            => array(
			'setting' => true,
			'control' => array(
				'control_type' => 'WP_Customize_Color_Control',
				'label'        => __( 'Background Color', 'make' ),
			),
		),
		'color-nav-current-item-background-opacity'     => array(
			'setting' => true,
			'control' => array(
				'control_type' => 'MAKE_Customizer_Control_Range',
				'label'   => __( 'Opacity', 'make' ),
				'input_attrs' => array(
					'min'  => 0,
					'max'  => 1.01, // Needs to be slightly over 1 to handle rounding error.
					'step' => 0.05,
				),
			),
		),
	),
) );

// Header Bar
$this->add_section_definitions( 'color-header-bar', array(
	'panel'   => $panel,
	'title'   => __( 'Header Bar', 'make' ),
	'controls' => array(
		'header-bar-text-color'       => array(
			'setting' => true,
			'control' => array(
				'control_type' => 'WP_Customize_Color_Control',
				'label'        => __( 'Text Color', 'make' ),
			),
		),
		'header-bar-link-color'       => array(
			'setting' => true,
			'control' => array(
				'control_type' => 'WP_Customize_Color_Control',
				'label'        => __( 'Link Color', 'make' ),
			),
		),
		'header-bar-link-hover-color'       => array(
			'setting' => true,
			'control' => array(
				'control_type' => 'WP_Customize_Color_Control',
				'label'        => __( 'Link Hover/Focus Color', 'make' ),
				'description' => sprintf(
					__( 'This option overrides the %s option in the %s section.', 'make' ),
					__( 'Link Hover/Focus Color', 'make' ),
					__( 'Global', 'make' )
				),
			),
		),
		'header-bar-border-color'     => array(
			'setting' => true,
			'control' => array(
				'control_type' => 'WP_Customize_Color_Control',
				'label'        => __( 'Border Color', 'make' ),
			),
		),
		'header-bar-background-color-heading' => array(
			'control' => array(
				'control_type' => 'MAKE_Customizer_Control_Html',
				'label'   => __( 'Background Color', 'make' ),
			),
		),
		'header-bar-background-color' => array(
			'setting' => true,
			'control' => array(
				'control_type' => 'WP_Customize_Color_Control',
			),
		),
		'header-bar-background-color-opacity'     => array(
			'setting' => true,
			'control' => array(
				'control_type' => 'MAKE_Customizer_Control_Range',
				'label'   => __( 'Opacity', 'make' ),
				'input_attrs' => array(
					'min'  => 0,
					'max'  => 1.01, // Needs to be slightly over 1 to handle rounding error.
					'step' => 0.05,
				),
			),
		),
	),
) );

// Sidebars
$this->add_section_definitions( 'color-sidebar', array(
	'panel'   => $panel,
	'title'   => __( 'Sidebars', 'make' ),
	'controls' => array(
		'color-group-sidebar-widget' => array(
			'control' => array(
				'control_type' => 'MAKE_Customizer_Control_Html',
				'html'  => '<h4 class="make-group-title">' . esc_html__( 'Widgets', 'make' ) . '</h4>',
			),
		),
		'color-widget-title-text'            => array(
			'setting' => true,
			'control' => array(
				'control_type' => 'WP_Customize_Color_Control',
				'label'        => __( 'Widget Title Color', 'make' ),
				'description' => sprintf(
					__( 'This option overrides the %s option in the %s section.', 'make' ),
					__( 'Text Color', 'make' ),
					__( 'Global', 'make' )
				),
			),
		),
		'color-widget-text'            => array(
			'setting' => true,
			'control' => array(
				'control_type' => 'WP_Customize_Color_Control',
				'label'        => __( 'Widget Body Color', 'make' ),
				'description' => sprintf(
					__( 'This option overrides the %s option in the %s section.', 'make' ),
					__( 'Text Color', 'make' ),
					__( 'Global', 'make' )
				),
			),
		),
		'color-widget-border'            => array(
			'setting' => true,
			'control' => array(
				'control_type' => 'WP_Customize_Color_Control',
				'label'        => __( 'Border Color', 'make' ),
				'description' => sprintf(
					__( 'This option overrides the %s option in the %s section.', 'make' ),
					__( 'Secondary Color', 'make' ),
					__( 'Global', 'make' )
				),
			),
		),
		'color-group-sidebar-link' => array(
			'control' => array(
				'control_type' => 'MAKE_Customizer_Control_Html',
				'html'  => '<h4 class="make-group-title">' . esc_html__( 'Links', 'make' ) . '</h4>',
			),
		),
		'color-widget-link'            => array(
			'setting' => true,
			'control' => array(
				'control_type' => 'WP_Customize_Color_Control',
				'label'        => __( 'Link Color', 'make' ),
				'description' => sprintf(
					__( 'This option overrides the %s option in the %s section.', 'make' ),
					__( 'Primary Color', 'make' ),
					__( 'Global', 'make' )
				),
			),
		),
		'color-widget-link-hover'            => array(
			'setting' => true,
			'control' => array(
				'control_type' => 'WP_Customize_Color_Control',
				'label'        => __( 'Link Hover/Focus Color', 'make' ),
			),
		),
	),
) );

// Footer
$this->add_section_definitions( 'color-footer', array(
	'panel'   => $panel,
	'title'   => __( 'Footer', 'make' ),
	'controls' => array(
		'footer-text-color'       => array(
			'setting' => true,
			'control' => array(
				'control_type' => 'WP_Customize_Color_Control',
				'label'        => __( 'Footer Text Color', 'make' ),
			),
		),
		'footer-link-color'       => array(
			'setting' => true,
			'control' => array(
				'control_type' => 'WP_Customize_Color_Control',
				'label'        => __( 'Link Color', 'make' ),
				'description' => sprintf(
					__( 'This option overrides the %s option in the %s section.', 'make' ),
					__( 'Primary Color', 'make' ),
					__( 'Global', 'make' )
				),
			),
		),
		'footer-link-hover-color'       => array(
			'setting' => true,
			'control' => array(
				'control_type' => 'WP_Customize_Color_Control',
				'label'        => __( 'Link Hover/Focus Color', 'make' ),
				'description' => sprintf(
					__( 'This option overrides the %s option in the %s section.', 'make' ),
					__( 'Link Hover/Focus Color', 'make' ),
					__( 'Global', 'make' )
				),
			),
		),
		'footer-border-color'     => array(
			'setting' => true,
			'control' => array(
				'control_type' => 'WP_Customize_Color_Control',
				'label'        => __( 'Border Color', 'make' ),
			),
		),
		'footer-background-color-heading' => array(
			'control' => array(
				'control_type' => 'MAKE_Customizer_Control_Html',
				'label'   => __( 'Background Color', 'make' ),
			),
		),
		'footer-background-color' => array(
			'setting' => true,
			'control' => array(
				'control_type' => 'WP_Customize_Color_Control',
			),
		),
		'footer-background-color-opacity'     => array(
			'setting' => true,
			'control' => array(
				'control_type' => 'MAKE_Customizer_Control_Range',
				'label'   => __( 'Opacity', 'make' ),
				'input_attrs' => array(
					'min'  => 0,
					'max'  => 1.01, // Needs to be slightly over 1 to handle rounding error.
					'step' => 0.05,
				),
			),
		),
	),
) );

// Check for deprecated filters
foreach ( array( 'make_customizer_colorscheme_sections' ) as $filter ) {
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