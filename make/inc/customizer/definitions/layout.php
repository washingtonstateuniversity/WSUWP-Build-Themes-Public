<?php
/**
 * @package Make
 */

// Bail if this isn't being included inside of a MAKE_Customizer_ControlsInterface.
if ( ! isset( $this ) || ! $this instanceof MAKE_Customizer_ControlsInterface ) {
	return;
}

// Panel ID
$panel = $this->prefix . 'layout';

// Global
$this->add_section_definitions( 'layout-global', array(
	'panel'    => $panel,
	'title'    => __( 'Global', 'make' ),
	'controls' => array(
		'general-layout' => array(
			'setting' => array(
				'transport' => 'postMessage',
			),
			'control' => array(
				'control_type' => 'MAKE_Customizer_Control_Radio',
				'label'        => __( 'Site Layout', 'make' ),
				'mode'         => 'buttonset',
				'choices'      => $this->thememod()->get_choice_set( 'general-layout' ),
			),
		),
	),
) );

// Header
$this->add_section_definitions( 'header', array(
	'panel'    => $panel,
	'title'    => __( 'Header', 'make' ),
	'controls' => array(
		'header-layout-group'        => array(
			'control' => array(
				'control_type' => 'MAKE_Customizer_Control_Html',
				'html'  => '<h4 class="make-group-title">' . esc_html__( 'Layout', 'make' ) . '</h4>',
			),
		),
		'header-layout'              => array(
			'setting' => true,
			'control' => array(
				'label'   => __( 'Header Layout', 'make' ),
				'type'    => 'select',
				'choices' => $this->thememod()->get_choice_set( 'header-layout' ),
			),
		),
		'header-branding-position'   => array(
			'setting' => array(
				'transport' => 'postMessage'
			),
			'control' => array(
				'control_type' => 'MAKE_Customizer_Control_Radio',
				'label'        => __( 'Show Title/Logo On', 'make' ),
				'mode'         => 'buttonset',
				'choices'      => $this->thememod()->get_choice_set( 'header-branding-position' ),
			),
		),
		'header-bar-content-layout'  => array(
			'setting' => array(
				'transport' => 'postMessage'
			),
			'control' => array(
				'control_type' => 'MAKE_Customizer_Control_Radio',
				'label'        => __( 'Header Bar Layout', 'make' ),
				'mode'         => 'buttonset',
				'choices'      => $this->thememod()->get_choice_set( 'header-bar-content-layout' ),
			),
		),
		'header-padding-heading'     => array(
			'control' => array(
				'control_type' => 'MAKE_Customizer_Control_Html',
				'label'        => __( 'Padding', 'make' ),
			),
		),
		'header-hide-padding-bottom' => array(
			'setting' => true,
			'control' => array(
				'label' => __( 'Remove padding beneath header', 'make' ),
				'type'  => 'checkbox',
			),
		),
		'header-options-group'       => array(
			'control' => array(
				'control_type' => 'MAKE_Customizer_Control_Html',
				'html'  => '<h4 class="make-group-title">' . esc_html__( 'Options', 'make' ) . '</h4>',
			),
		),
		'header-text'                => array(
			'setting' => true,
			'control' => array(
				'label'       => __( 'Header Bar Text', 'make' ),
				'description' => __( 'This text only appears if a custom menu has not been assigned to the Header Bar Menu location in the Navigation section.', 'make' ),
				'type'        => 'text',
			),
		),
		'header-options-heading'     => array(
			'control' => array(
				'control_type' => 'MAKE_Customizer_Control_Html',
				'label'        => __( 'Optional Header Elements', 'make' ),
			),
		),
		'header-show-search'         => array(
			'setting' => true,
			'control' => array(
				'label' => __( 'Show search field', 'make' ),
				'type'  => 'checkbox',
			),
		),
		'header-show-social'         => array(
			'setting' => true,
			'control' => array(
				'label' => __( 'Show social icons', 'make' ),
				'type'  => 'checkbox',
			),
		),
		'font-size-header-bar-icon'  => array(
			'setting' => true,
			'control' => array(
				'control_type' => 'MAKE_Customizer_Control_Range',
				'label'        => __( 'Icon Size (px)', 'make' ),
				'input_attrs'  => array(
					'min'  => 6,
					'max'  => 100,
					'step' => 1,
				),
			),
		),
	),
) );

// Footer
$this->add_section_definitions( 'footer', array(
	'panel'    => $panel,
	'title'    => __( 'Footer', 'make' ),
	'controls' => array(
		'footer-widgets-group'    => array(
			'control' => array(
				'control_type' => 'MAKE_Customizer_Control_Html',
				'html'  => '<h4 class="make-group-title">' . esc_html__( 'Widgets', 'make' ) . '</h4>',
			),
		),
		'footer-widget-areas'     => array(
			'setting' => true,
			'control' => array(
				'control_type' => 'MAKE_Customizer_Control_Radio',
				'label'        => __( 'Number of Widget Areas', 'make' ),
				'mode'         => 'buttonset',
				'choices'      => $this->thememod()->get_choice_set( 'footer-widget-areas' ),
			),
		),
		'footer-layout-group'     => array(
			'control' => array(
				'control_type' => 'MAKE_Customizer_Control_Html',
				'html'  => '<h4 class="make-group-title">' . esc_html__( 'Layout', 'make' ) . '</h4>',
			),
		),
		'footer-layout'           => array(
			'setting' => true,
			'control' => array(
				'label'   => __( 'Footer Layout', 'make' ),
				'type'    => 'select',
				'choices' => $this->thememod()->get_choice_set( 'footer-layout' ),
			),
		),
		'footer-padding-heading'  => array(
			'control' => array(
				'control_type' => 'MAKE_Customizer_Control_Html',
				'label'        => __( 'Padding', 'make' ),
			),
		),
		'footer-hide-padding-top' => array(
			'setting' => true,
			'control' => array(
				'label' => __( 'Remove padding above footer', 'make' ),
				'type'  => 'checkbox',
			),
		),
		'footer-options-group'    => array(
			'control' => array(
				'control_type' => 'MAKE_Customizer_Control_Html',
				'html'  => '<h4 class="make-group-title">' . esc_html__( 'Options', 'make' ) . '</h4>',
			),
		),
		'footer-text'             => array(
			'setting' => true,
			'control' => array(
				'label' => __( 'Footer Text', 'make' ),
				'type'  => 'text',
			),
		),
		'footer-options-heading'  => array(
			'control' => array(
				'control_type' => 'MAKE_Customizer_Control_Html',
				'label'        => __( 'Optional Footer Elements', 'make' ),
			),
		),
		'footer-show-social'      => array(
			'setting' => true,
			'control' => array(
				'label' => __( 'Show social icons', 'make' ),
				'type'  => 'checkbox',
			),
		),
		'font-size-footer-icon'   => array(
			'setting' => true,
			'control' => array(
				'control_type' => 'MAKE_Customizer_Control_Range',
				'label'        => __( 'Icon Size (px)', 'make' ),
				'input_attrs'  => array(
					'min'  => 6,
					'max'  => 100,
					'step' => 1,
				),
			),
		),
	),
) );

// Views
$views = array(
	'blog'    => __( 'Blog (Posts Page)', 'make' ),
	'archive' => __( 'Archives', 'make' ),
	'search'  => __( 'Search Results', 'make' ),
	'post'    => __( 'Posts', 'make' ),
	'page'    => __( 'Pages', 'make' ),
);

foreach ( $views as $view => $label ) {
	$prefix = "layout-$view-";
	$controls = array();

	// Header, footer, sidebars
	$controls = array_merge( $controls, array(
		$prefix . 'sidebars-heading'          => array(
			'control' => array(
				'control_type' => 'MAKE_Customizer_Control_Html',
				'html'  => '<h4 class="make-group-title">' . esc_html__( 'Header, Footer, Sidebars', 'make' ) . '</h4>',
			),
		),
		$prefix . 'hide-header'               => array(
			'setting' => true,
			'control' => array(
				'label' => __( 'Hide site header', 'make' ),
				'type'  => 'checkbox',
			),
		),
		$prefix . 'hide-footer'               => array(
			'setting' => true,
			'control' => array(
				'label' => __( 'Hide site footer', 'make' ),
				'type'  => 'checkbox',
			),
		),
		$prefix . 'sidebar-left'              => array(
			'setting' => true,
			'control' => array(
				'label' => __( 'Show left sidebar', 'make' ),
				'type'  => 'checkbox',
			),
		),
		$prefix . 'sidebar-right'             => array(
			'setting' => true,
			'control' => array(
				'label' => __( 'Show right sidebar', 'make' ),
				'type'  => 'checkbox',
			),
		),
	) );

	// Note about sidebars for pages
	if ( 'page' === $view ) {
		$controls = array_merge( $controls, array(
			$prefix . 'sidebars-text' => array(
				'control' => array(
					'control_type' => 'MAKE_Customizer_Control_Html',
					'description'  => __( 'Sidebars are not available on pages using the Builder Template.', 'make' ),
				),
			),
		) );
	}

	// Page & Post title
	if ( in_array( $view, array( 'page', 'post' ) ) ) {
		$controls = array_merge( $controls, array(
			$prefix . 'pagetitle-heading' => array(
				'control' => array(
					'control_type' => 'MAKE_Customizer_Control_Html',
					'html'  => '<h4 class="make-group-title">' . esc_html__( ucfirst( $view ) . ' Title', 'make' ) . '</h4>',
				),
			),
			$prefix . 'hide-title'        => array(
				'setting' => true,
				'control' => array(
					'label' => __( 'Hide title', 'make' ),
					'type'  => 'checkbox',
				),
			),
		) );
	}

	// Featured images, post date, post author
	$controls = array_merge( $controls, array(
		'featured-images-group-' . $view      => array(
			'control' => array(
				'control_type' => 'MAKE_Customizer_Control_Html',
				'html'  => '<h4 class="make-group-title">' . esc_html__( 'Featured Images', 'make' ) . '</h4>',
			),
		),
		$prefix . 'featured-images'           => array(
			'setting' => true,
			'control' => array(
				'label'   => __( 'Location', 'make' ),
				'type'    => 'select',
				'choices' => $this->thememod()->get_choice_set( $prefix . 'featured-images' ),
			),
		),
		$prefix . 'featured-images-alignment' => array(
			'setting' => true,
			'control' => array(
				'control_type' => 'MAKE_Customizer_Control_Radio',
				'label'        => __( 'Alignment', 'make' ),
				'mode'         => 'buttonset',
				'choices'      => $this->thememod()->get_choice_set( $prefix . 'featured-images-alignment' ),
			),
		),
		'post-date-group-' . $view            => array(
			'control' => array(
				'control_type' => 'MAKE_Customizer_Control_Html',
				'html'  => '<h4 class="make-group-title">' . esc_html__( ucfirst( $view ) . ' Date', 'make' ) . '</h4>',
			),
		),
		$prefix . 'post-date'                 => array(
			'setting' => true,
			'control' => array(
				'label'   => __( 'Style', 'make' ),
				'type'    => 'select',
				'choices' => $this->thememod()->get_choice_set( $prefix . 'post-date' ),
			),
		),
		$prefix . 'post-date-location'        => array(
			'setting' => true,
			'control' => array(
				'control_type' => 'MAKE_Customizer_Control_Radio',
				'label'        => __( 'Location', 'make' ),
				'mode'         => 'buttonset',
				'choices'      => $this->thememod()->get_choice_set( $prefix . 'post-date-location' ),
			),
		),
		'post-author-group-' . $view          => array(
			'control' => array(
				'control_type' => 'MAKE_Customizer_Control_Html',
				'html'  => '<h4 class="make-group-title">' . esc_html__( ucfirst( $view ) . ' Author', 'make' ) . '</h4>',
			),
		),
		$prefix . 'post-author'               => array(
			'setting' => true,
			'control' => array(
				'label'   => __( 'Style', 'make' ),
				'type'    => 'select',
				'choices' => $this->thememod()->get_choice_set( $prefix . 'post-author' ),
			),
		),
		$prefix . 'post-author-location'      => array(
			'setting' => true,
			'control' => array(
				'control_type' => 'MAKE_Customizer_Control_Radio',
				'label'        => __( 'Location', 'make' ),
				'mode'         => 'buttonset',
				'choices'      => $this->thememod()->get_choice_set( $prefix . 'post-author-location' ),
			),
		),
	) );

	// Content
	if ( in_array( $view, array( 'blog', 'archive', 'search' ) ) ) {
		$controls = array_merge( $controls, array(
			'content-group-' . $view => array(
				'control' => array(
					'control_type' => 'MAKE_Customizer_Control_Html',
					'html'  => '<h4 class="make-group-title">' . esc_html__( 'Content', 'make' ) . '</h4>',
				),
			),
			$prefix . 'auto-excerpt' => array(
				'setting' => true,
				'control' => array(
					'label' => __( 'Generate excerpts automatically', 'make' ),
					'type'  => 'checkbox',
				),
			),
		) );
	}

	// Post meta
	if ( in_array( $view, array( 'blog', 'archive', 'search', 'post' ) ) ) {
		$controls = array_merge( $controls, array(
			'post-meta-group-' . $view  => array(
				'control' => array(
					'control_type' => 'MAKE_Customizer_Control_Html',
					'html'  => '<h4 class="make-group-title">' . esc_html__( 'Post Meta', 'make' ) . '</h4>',
				),
			),
			$prefix . 'show-categories' => array(
				'setting' => true,
				'control' => array(
					'label' => __( 'Show categories', 'make' ),
					'type'  => 'checkbox',
				),
			),
			$prefix . 'show-tags'       => array(
				'setting' => true,
				'control' => array(
					'label' => __( 'Show tags', 'make' ),
					'type'  => 'checkbox',
				),
			),
		) );
	}

	// Comment count
	$controls = array_merge( $controls, array(
		'comment-count-group-' . $view     => array(
			'control' => array(
				'control_type' => 'MAKE_Customizer_Control_Html',
				'html'  => '<h4 class="make-group-title">' . esc_html__( 'Comment Count', 'make' ) . '</h4>',
			),
		),
		$prefix . 'comment-count'          => array(
			'setting' => true,
			'control' => array(
				'label'   => __( 'Style', 'make' ),
				'type'    => 'select',
				'choices' => $this->thememod()->get_choice_set( $prefix . 'comment-count' ),
			),
		),
		$prefix . 'comment-count-location' => array(
			'setting' => true,
			'control' => array(
				'control_type' => 'MAKE_Customizer_Control_Radio',
				'label'        => __( 'Location', 'make' ),
				'mode'         => 'buttonset',
				'choices'      => $this->thememod()->get_choice_set( $prefix . 'comment-count-location' ),
			),
		),
	) );

	if ( 'post' === $view ) {
		$controls = array_merge( $controls, array(
			$prefix . 'postnavigation-heading' => array(
				'control' => array(
					'control_type' => 'MAKE_Customizer_Control_Html',
					'html'  => '<h4 class="make-group-title">' . esc_html__( 'Post Navigation', 'make' ) . '</h4>',
				),
			),
			$prefix . 'hide-navigation' => array(
				'setting' => true,
				'control' => array(
					'label' => __( 'Hide navigation', 'make' ),
					'type'  => 'checkbox',
				),
			),
		) );
	}

	// Breadcrumbs
	/**
	 * Filter: Allow override of breadcrumb settings, controls and output
	 *
	 * @since 1.7.4.
	 *
	 * @param boolean $override         Wether third party breadcrumbs should be overriden.
	 */
	$breadcrumb_override = apply_filters( 'make_breadcrumb_override', false );
	if ( ( true === $breadcrumb_override ) && in_array( $view, array( 'blog', 'archive', 'search', 'post', 'page' ) ) ) {
		$controls = array_merge( $controls, array(
			$prefix . 'breadcrumb-heading' => array(
				'control' => array(
					'control_type' => 'MAKE_Customizer_Control_Html',
					'html'  => '<h4 class="make-group-title">' . esc_html__( 'Breadcrumbs', 'make' ) . '</h4>',
				),
			),

			$prefix . 'breadcrumb'       => array(
				'setting' => true,
				'control' => array(
					'label' => __( 'Show breadcumbs', 'make' ),
					'type'  => 'checkbox',
				),
			),
		) );
	}

	// Add the definitions
	$this->add_section_definitions( 'layout-' . $view, array(
		'panel'   => $panel,
		'title'   => $label,
		'controls' => $controls,
	) );
}

// Check for deprecated filters
foreach ( array( 'make_customizer_contentlayout_sections', 'make_customizer_header_sections', 'make_customizer_footer_sections' ) as $filter ) {
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