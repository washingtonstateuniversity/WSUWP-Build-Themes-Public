<?php
/**
 * @package Make
 */

// Bail if this isn't being included inside of a MAKE_Customizer_ControlsInterface.
if ( ! isset( $this ) || ! $this instanceof MAKE_Customizer_ControlsInterface ) {
	return;
}

// Panel ID
$panel = $this->prefix . 'general';

// Labels
$this->add_section_definitions( 'labels', array(
	'panel'    => $panel,
	'title'    => __( 'Labels', 'make' ),
	'controls' => array(
		'label-search-field'      => array(
			'setting' => array(
				'transport' => 'postMessage',
			),
			'control' => array(
				'label' => __( 'Search Field Label', 'make' ),
				'type'  => 'text',
			),
		),
		'navigation-mobile-label' => array(
			'setting' => array(
				'theme_supports' => 'menus',
				'transport'      => 'postMessage',
			),
			'control' => array(
				'label'       => __( 'Mobile Menu Label', 'make' ),
				'type'        => 'text',
			),
		),
		'general-sticky-label'    => array(
			'setting' => array(
				'transport' => 'postMessage',
			),
			'control' => array(
				'label' => __( 'Sticky Label', 'make' ),
				'type'  => 'text',
			),
		),
	),
) );

// Only show the Read More label option if no filters have been added to the deprecated filter hook.
// has_filter() can't be used here because of the hook-prefixing filters added for back compatibility.
/** This filter is documented in inc/template-tags.php */
if ( false === apply_filters( 'make_read_more_text', false ) ) {
	$this->add_section_definitions( 'labels', array(
		'controls' => array(
			'label-read-more' => array(
				'setting' => array(
					'transport' => 'postMessage',
				),
				'control' => array(
					'label' => __( 'Read More Label', 'make' ),
					'type'  => 'text',
				),
			)
		)
	), true ); // Overwrite to add additional controls to the section
}

// Social Profiles
$this->add_section_definitions( 'social', array(
	'panel'       => $panel,
	'title'       => __( 'Social Icons', 'make' ),
	'controls'    => array(
		'social-icons'  => array(
			'setting' => array(
				'transport' => ( isset( $wp_customize->selective_refresh ) ) ? 'postMessage' : 'refresh',
			),
			'control' => array(
				'control_type' => 'MAKE_Customizer_Control_SocialIcons',
				'description' => __( 'Add a link to each of your social profiles and we&#8217;ll add the icon to match — it&#8217;s that simple. Drag and drop to rearrange.', 'make' )
			),
			'partial' => array(
				'selector'            => '.header-social-links, .footer-social-links',
				'render_callback'     => array( $this->socialicons(), 'render_icons' ),
				'container_inclusive' => false,
			),
		),
	),
) );

// Check for deprecated filters
foreach ( array( 'make_customizer_general_sections' ) as $filter ) {
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
