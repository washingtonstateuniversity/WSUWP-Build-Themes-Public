<?php
/**
 * @package Make
 */

// Bail if this isn't being included inside of a MAKE_Customizer_ControlsInterface.
if ( ! isset( $this ) || ! $this instanceof MAKE_Customizer_ControlsInterface ) {
	return;
}

// Panel ID
$panel = $this->prefix . 'typography';

// Global
$this->add_section_definitions( 'font', array(
	'panel'   => $panel,
	'title'   => __( 'Global', 'make' ),
	'controls' => array_merge(
		$this->helper()->get_typography_group_definitions( 'body', __( 'Default', 'make' ) ),
		$this->helper()->get_typography_group_definitions( 'body-link', __( 'Links', 'make' ) ),
		$this->helper()->get_typography_group_definitions( 'button', __( 'Buttons', 'make' ) )
	),
) );

// Text headers
$this->add_section_definitions( 'font-headers', array(
	'panel'   => $panel,
	'title'   => __( 'Content Headings', 'make' ),
	'controls' => array_merge(
		$this->helper()->get_typography_group_definitions( 'h1', __( 'H1', 'make' ) ),
		$this->helper()->get_typography_group_definitions( 'h2', __( 'H2', 'make' ) ),
		$this->helper()->get_typography_group_definitions( 'h3', __( 'H3', 'make' ) ),
		$this->helper()->get_typography_group_definitions( 'h4', __( 'H4', 'make' ) ),
		$this->helper()->get_typography_group_definitions( 'h5', __( 'H5', 'make' ) ),
		$this->helper()->get_typography_group_definitions( 'h6', __( 'H6', 'make' ) )
	),
) );

// Site title & tagline
$this->add_section_definitions( 'font-site-title-tagline', array(
	'panel'   => $panel,
	'title'   => __( 'Site Title &amp; Tagline', 'make' ),
	'controls' => array_merge(
		$this->helper()->get_typography_group_definitions( 'site-title', __( 'Site Title', 'make' ) ),
		$this->helper()->get_typography_group_definitions( 'site-tagline', __( 'Tagline', 'make' ) )
	),
) );

// Main navigation
$this->add_section_definitions( 'font-main-menu', array(
	'panel'   => $panel,
	'title'   => __( 'Main Menu', 'make' ),
	'controls' => array_merge(
		$this->helper()->get_typography_group_definitions( 'nav', __( 'Menu Items', 'make' ) ),
		$this->helper()->get_typography_group_definitions( 'subnav', __( 'Sub-Menu Items', 'make' ) ),
		array(
			'font-nav-mobile-option-heading' => array(
				'control' => array(
					'control_type' => 'MAKE_Customizer_Control_Html',
					'label'        => __( 'Mobile', 'make' ),
				),
			),
			'font-subnav-mobile'         => array(
				'setting' => true,
				'control' => array(
					'label' => __( 'Use Menu Item styles in mobile view', 'make' ),
					'type'  => 'checkbox',
				),
			),
		),
		$this->helper()->get_typography_group_definitions( 'nav-current-item', __( 'Current Item', 'make' ) )
	),
) );

// Header Bar
$this->add_section_definitions( 'font-header-bar', array(
	'panel'   => $panel,
	'title'   => __( 'Header Bar', 'make' ),
	'controls' => $this->helper()->get_typography_group_definitions(
		'header-bar-text',
		__( 'Header Bar Text', 'make' ),
		__( 'Includes Header Text, Header Bar Menu items, and the search field.', 'make' )
	),
) );

// Sidebars
$this->add_section_definitions( 'font-sidebar', array(
	'panel'   => $panel,
	'title'   => __( 'Sidebars', 'make' ),
	'controls' => array_merge(
		$this->helper()->get_typography_group_definitions( 'widget-title', __( 'Widget Title', 'make' ) ),
		$this->helper()->get_typography_group_definitions( 'widget', __( 'Widget Body', 'make' ) )
	),
) );

// Footer
$this->add_section_definitions( 'font-footer', array(
	'panel'   => $panel,
	'title'   => __( 'Footer', 'make' ),
	'controls' => array_merge(
		$this->helper()->get_typography_group_definitions( 'footer-widget-title', __( 'Widget Title', 'make' ) ),
		$this->helper()->get_typography_group_definitions( 'footer-widget', __( 'Widget Body', 'make' ) ),
		$this->helper()->get_typography_group_definitions( 'footer-text', __( 'Footer Text', 'make' ) )
	),
) );

// Google fonts
if ( $this->font()->has_source( 'google' ) ) {
	$this->add_section_definitions( 'font-google', array(
		'panel'    => $panel,
		'title'    => __( 'Google Font Subsets', 'make' ),
		'controls' => array(
			'font-subset'      => array(
				'setting' => true,
				'control' => array(
					'label'   => __( 'Character Subset', 'make' ),
					'type'    => 'select',
					'choices' => array_combine( $this->font()->get_source( 'google' )->get_subsets(), $this->font()->get_source( 'google' )->get_subsets() ),
				),
			),
			'font-subset-text' => array(
				'control' => array(
					'control_type' => 'MAKE_Customizer_Control_Html',
					'description'  => sprintf(
						__( 'Not all fonts provide each of these subsets. Please visit the %s to see which subsets are available for each font.', 'make' ),
						sprintf(
							'<a href="%1$s" target="_blank">%2$s</a>',
							esc_url( 'https://www.google.com/fonts' ),
							__( 'Google Fonts website', 'make' )
						)
					),
				),
			),
		),
	) );
}

// Check for deprecated filters
foreach ( array( 'make_customizer_typography_sections' ) as $filter ) {
	if ( has_filter( $filter ) ) {
		$this->compatibility()->deprecated_hook(
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