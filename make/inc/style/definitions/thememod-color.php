<?php
/**
 * @package Make
 */

// Bail if this isn't being included inside of a MAKE_Style_ManagerInterface.
if ( ! isset( $this ) || ! $this instanceof MAKE_Style_ManagerInterface ) {
	return;
}

$is_style_preview = isset( $_POST['make-preview'] );

/**
 * Global
 */

// Primary color
if ( $is_style_preview || ! $this->thememod()->is_default( 'color-primary' ) ) {
	$color = $this->thememod()->get_value( 'color-primary' );
	$this->css()->add( array(
		'selectors'    => array(
			'.color-primary-text',
			'a',
			'.entry-author-byline a.vcard',
			'.entry-footer a:hover',
			'.comment-form .required',
			'ul.ttfmake-list-dot li:before',
			'ol.ttfmake-list-dot li:before',
			'.entry-comment-count a:hover',
			'.comment-count-icon a:hover',
		),
		'declarations' => array(
			'color' => $color
		)
	) );
	$this->css()->add( array(
		'selectors'    => array(
			'.color-primary-background',
			'.ttfmake-button.color-primary-background',
		),
		'declarations' => array(
			'background-color' => $color
		)
	) );
	$this->css()->add( array(
		'selectors'    => array( '.color-primary-border' ),
		'declarations' => array(
			'border-color' => $color
		)
	) );
	$this->css()->add( array(
		'selectors'    => array(
			'.site-navigation ul.menu ul a:hover',
			'.site-navigation ul.menu ul a:focus',
			'.site-navigation .menu ul ul a:hover',
			'.site-navigation .menu ul ul a:focus',
		),
		'declarations' => array(
			'background-color' => $color
		),
		'media'        => 'screen and (min-width: 800px)'
	) );
}

// Secondary color
if ( $is_style_preview || ! $this->thememod()->is_default( 'color-secondary' ) ) {
	$color = $this->thememod()->get_value( 'color-secondary' );
	$this->css()->add( array(
		'selectors'    => array(
			'.color-secondary-text',
			'.builder-section-banner .cycle-pager',
			'.ttfmake-shortcode-slider .cycle-pager',
			'.builder-section-banner .cycle-prev:before',
			'.builder-section-banner .cycle-next:before',
			'.ttfmake-shortcode-slider .cycle-prev:before',
			'.ttfmake-shortcode-slider .cycle-next:before',
			'.ttfmake-shortcode-slider .cycle-caption',
		),
		'declarations' => array(
			'color' => $color
		)
	) );
	$this->css()->add( array(
		'selectors'    => array(
			'.color-secondary-background',
			'blockquote.ttfmake-testimonial',
			'tt',
			'kbd',
			'pre',
			'code',
			'samp',
			'var',
			'textarea',
			'input[type="date"]',
			'input[type="datetime"]',
			'input[type="datetime-local"]',
			'input[type="email"]',
			'input[type="month"]',
			'input[type="number"]',
			'input[type="password"]',
			'input[type="search"]',
			'input[type="tel"]',
			'input[type="text"]',
			'input[type="time"]',
			'input[type="url"]',
			'input[type="week"]',
			'.ttfmake-button.color-secondary-background',
			'button.color-secondary-background',
			'input[type="button"].color-secondary-background',
			'input[type="reset"].color-secondary-background',
			'input[type="submit"].color-secondary-background',
			'.sticky-post-label',
		),
		'declarations' => array(
			'background-color' => $color
		)
	) );
	$this->css()->add( array(
		'selectors'    => array(
			'.site-navigation .menu .sub-menu',
			'.site-navigation .menu .children',
		),
		'declarations' => array(
			'background-color' => $color
		),
		'media'        => 'screen and (min-width: 800px)'
	) );
	$this->css()->add( array(
		'selectors'    => array(
			'.color-secondary-border',
			'table',
			'table th',
			'table td',
			'.header-layout-3 .site-navigation .menu',
			'.widget_tag_cloud a',
			'.widget_product_tag_cloud a',
		),
		'declarations' => array(
			'border-color' => $color
		)
	) );
	$this->css()->add( array(
		'selectors'    => array(
			'hr',
			'hr.ttfmake-line-dashed',
			'hr.ttfmake-line-double',
			'blockquote.ttfmake-testimonial:after',
		),
		'declarations' => array(
			'border-top-color' => $color
		)
	) );
	$this->css()->add( array(
		'selectors'    => array(
			'.comment-body',
			'.post',
			'.page',
			'.search article.post',
			'.search article.page',
			'.widget li',
		),
		'declarations' => array(
			'border-bottom-color' => $color
		)
	) );
}

// Text color
if ( $is_style_preview || ! $this->thememod()->is_default( 'color-text' ) ) {
	$color = $this->thememod()->get_value( 'color-text' );
	$this->css()->add( array(
		'selectors'    => array(
			'.color-text',
			'body',
			'.entry-date a',
			'button',
			'input',
			'select',
			'textarea',
			'[class*="navigation"] .nav-previous a',
			'[class*="navigation"] .nav-previous span',
			'[class*="navigation"] .nav-next a',
			'[class*="navigation"] .nav-next span',
		),
		'declarations' => array(
			'color' => $color
		)
	) );
	// These placeholder selectors have to be isolated in individual rules.
	// See http://css-tricks.com/snippets/css/style-placeholder-text/#comment-96771
	$this->css()->add( array(
		'selectors'    => array( '::-webkit-input-placeholder' ),
		'declarations' => array(
			'color' => $color
		)
	) );
	$this->css()->add( array(
		'selectors'    => array( ':-moz-placeholder' ),
		'declarations' => array(
			'color' => $color
		)
	) );
	$this->css()->add( array(
		'selectors'    => array( '::-moz-placeholder' ),
		'declarations' => array(
			'color' => $color
		)
	) );
	$this->css()->add( array(
		'selectors'    => array( ':-ms-input-placeholder' ),
		'declarations' => array(
			'color' => $color
		)
	) );
}

// Detail color
if ( $is_style_preview || ! $this->thememod()->is_default( 'color-detail' ) ) {
	$color = $this->thememod()->get_value( 'color-detail' );
	$this->css()->add( array(
		'selectors'    => array(
			'.color-detail-text',
			'.builder-section-banner .cycle-pager .cycle-pager-active',
			'.ttfmake-shortcode-slider .cycle-pager .cycle-pager-active',
			'.entry-footer a',
			'.entry-footer .fa',
			'.post-categories li:after',
			'.post-tags li:after',
			'.comment-count-icon:before',
			'.entry-comment-count a',
			'.comment-count-icon a',
		),
		'declarations' => array(
			'color' => $color
		)
	) );
	$this->css()->add( array(
		'selectors'    => array(
			'.site-navigation .page_item_has_children a:after',
			'.site-navigation .menu-item-has-children a:after'
		),
		'declarations' => array(
			'color' => $color
		),
		'media'        => 'screen and (min-width: 800px)'
	) );
	$this->css()->add( array(
		'selectors'    => array(
			'.site-navigation .menu .sub-menu a',
			'.site-navigation .menu .children a',
		),
		'declarations' => array(
			'border-bottom-color' => $color
		),
		'media'        => 'screen and (min-width: 800px)'
	) );
	$this->css()->add( array(
		'selectors'    => array( '.color-detail-background' ),
		'declarations' => array(
			'background-color' => $color
		)
	) );
	$this->css()->add( array(
		'selectors'    => array( '.color-detail-border' ),
		'declarations' => array(
			'border-color' => $color
		)
	) );
}

// Link Hover/Focus Color
if ( $is_style_preview || ! $this->thememod()->is_default( 'color-primary-link' ) ) {
	$this->css()->add( array(
		'selectors'    => array(
			'a:hover',
			'a:focus',
			'.entry-author-byline a.vcard:hover',
			'.entry-author-byline a.vcard:focus',
		),
		'declarations' => array(
			'color' => $this->thememod()->get_value( 'color-primary-link' )
		)
	) );
}

// Button Text Color
if ( $is_style_preview || ! $this->thememod()->is_default( 'color-button-text' ) ) {
	$this->css()->add( array(
		'selectors'    => array(
			'button',
			'.ttfmake-button',
			'input[type="button"]',
			'input[type="reset"]',
			'input[type="submit"]',
			'.site-main .gform_wrapper .gform_footer input.button',
		),
		'declarations' => array(
			'color' => $this->thememod()->get_value( 'color-button-text' )
		)
	) );
}
// Button Text Hover/Focus Color
if ( $is_style_preview || ( ! $this->thememod()->is_default( 'color-button-text-hover' ) && $this->thememod()->get_value( 'color-button-text-hover' ) !== $this->thememod()->get_value( 'color-button-text' ) ) ) {
	$this->css()->add( array(
		'selectors'    => array(
			'button:hover', 'button:focus',
			'.ttfmake-button:hover', '.ttfmake-button:focus',
			'input[type="button"]:hover', 'input[type="button"]:focus',
			'input[type="reset"]:hover', 'input[type="reset"]:focus',
			'input[type="submit"]:hover', 'input[type="submit"]:focus',
			'.site-main .gform_wrapper .gform_footer input.button:hover', '.site-main .gform_wrapper .gform_footer input.button:focus',
		),
		'declarations' => array(
			'color' => $this->thememod()->get_value( 'color-button-text-hover' )
		)
	) );
}
// Button Background Color
if ( $is_style_preview || ! $this->thememod()->is_default( 'color-button-background' ) ) {
	$this->css()->add( array(
		'selectors'    => array(
			'button',
			'.ttfmake-button',
			'input[type="button"]',
			'input[type="reset"]',
			'input[type="submit"]',
			'.site-main .gform_wrapper .gform_footer input.button',
		),
		'declarations' => array(
			'background-color' => $this->thememod()->get_value( 'color-button-background' )
		)
	) );
}
// Button Background Hover/Focus Color
if ( $is_style_preview || ( ! $this->thememod()->is_default( 'color-button-background-hover' ) && $this->thememod()->get_value( 'color-button-background-hover' ) !== $this->thememod()->get_value( 'color-button-background' ) ) ) {
	$this->css()->add( array(
		'selectors'    => array(
			'button:hover', 'button:focus',
			'.ttfmake-button:hover', '.ttfmake-button:focus',
			'input[type="button"]:hover', 'input[type="button"]:focus',
			'input[type="reset"]:hover', 'input[type="reset"]:focus',
			'input[type="submit"]:hover', 'input[type="submit"]:focus',
			'.site-main .gform_wrapper .gform_footer input.button:hover', '.site-main .gform_wrapper .gform_footer input.button:focus',
		),
		'declarations' => array(
			'background-color' => $this->thememod()->get_value( 'color-button-background-hover' )
		)
	) );
}

// Main background color
if ( $is_style_preview || ! $this->thememod()->is_default( 'main-background-color' ) || ! $this->thememod()->is_default( 'main-background-color-opacity' ) ) {
	// Convert to RGBa
	$color = $this->helper()->hex_to_rgb( $this->thememod()->get_value( 'main-background-color' ) ) . ', ' . $this->thememod()->get_value( 'main-background-color-opacity' );

	$this->css()->add( array(
		'selectors'    => array(
			'.site-content',
			'body.mce-content-body',
		),
		'declarations' => array(
			'background-color' => 'rgba(' . $color . ')'
		)
	) );
}

/**
 * Header
 */

// Header text color
if ( $is_style_preview || ! $this->thememod()->is_default( 'header-text-color' ) ) {
	$this->css()->add( array(
		'selectors'    => array(
			'.site-header',
			'.site-title',
			'.site-title a',
			'.site-navigation .menu li a',
		),
		'declarations' => array(
			'color' => $this->thememod()->get_value( 'header-text-color' )
		)
	) );
}

// Header background color
if ( $is_style_preview || ! $this->thememod()->is_default( 'header-background-color' ) || ! $this->thememod()->is_default( 'header-background-color-opacity' ) ) {
	// Convert to RGBa
	$color = $this->helper()->hex_to_rgb( $this->thememod()->get_value( 'header-background-color' ) ) . ', ' . $this->thememod()->get_value( 'header-background-color-opacity' );

	$this->css()->add( array(
		'selectors'    => array( '.site-header-main' ),
		'declarations' => array(
			'background-color' => 'rgba(' . $color . ')'
		)
	) );
}

/**
 * Site Title & Tagline
 */

// Site title
if ( $is_style_preview || ( ! $this->thememod()->is_default( 'color-site-title' ) && $this->thememod()->get_value( 'header-text-color' ) !== $this->thememod()->get_value( 'color-site-title' ) ) ) {
	$this->css()->add( array(
		'selectors'    => array(
			'.site-header .site-title',
			'.site-header .site-title a',
		),
		'declarations' => array(
			'color' => $this->thememod()->get_value( 'color-site-title' )
		)
	) );
}

// Tagline
if ( $is_style_preview || ( ! $this->thememod()->is_default( 'color-site-tagline' ) && $this->thememod()->get_value( 'header-text-color' ) !== $this->thememod()->get_value( 'color-site-tagline' ) ) ) {
	$this->css()->add( array(
		'selectors'    => array( '.site-header .site-description' ),
		'declarations' => array(
			'color' => $this->thememod()->get_value( 'color-site-tagline' )
		)
	) );
}

/**
 * Main Menu
 */

// Menu Item Text
if ( $is_style_preview || ! $this->thememod()->is_default( 'color-nav-text' ) ) {
	$this->css()->add( array(
		'selectors'    => array( '.site-navigation .menu li a' ),
		'declarations' => array(
			'color' => $this->thememod()->get_value( 'color-nav-text' )
		)
	) );
}

// Menu Item Text hover
if ( $is_style_preview || ! $this->thememod()->is_default( 'color-nav-text-hover' ) ) {
	$this->css()->add( array(
		'selectors'    => array(
			'.site-navigation .menu li a:hover',
			'.site-navigation .menu li a:focus',
		),
		'declarations' => array(
			'color' => $this->thememod()->get_value( 'color-nav-text-hover' )
		)
	) );
}

// Sub-Menu Item Text
if ( $is_style_preview || ! $this->thememod()->is_default( 'color-subnav-text' ) ) {
	$this->css()->add( array(
		'selectors'    => array(
			'.site-navigation ul.menu ul a',
			'.site-navigation ul.menu ul a',
			'.site-navigation .menu ul ul a',
			'.site-navigation .menu ul ul a',
		),
		'declarations' => array(
			'color' => $this->thememod()->get_value( 'color-subnav-text' )
		),
		'media'        => 'screen and (min-width: 800px)'
	) );
}

// Sub-Menu Item Text hover
if ( $is_style_preview || ! $this->thememod()->is_default( 'color-subnav-text-hover' ) ) {
	$this->css()->add( array(
		'selectors'    => array(
			'.site-navigation ul.menu ul a:hover',
			'.site-navigation ul.menu ul a:focus',
			'.site-navigation .menu ul ul a:hover',
			'.site-navigation .menu ul ul a:focus',
		),
		'declarations' => array(
			'color' => $this->thememod()->get_value( 'color-subnav-text-hover' )
		),
		'media'        => 'screen and (min-width: 800px)'
	) );
}

// Sub-Menu Item Detail
if ( $is_style_preview || ! $this->thememod()->is_default( 'color-subnav-detail' ) ) {
	$color = $this->thememod()->get_value( 'color-subnav-detail' );
	$this->css()->add( array(
		'selectors'    => array(
			'.site-navigation .page_item_has_children a:after',
			'.site-navigation .menu-item-has-children a:after',
		),
		'declarations' => array(
			'color' => $color
		),
		'media'        => 'screen and (min-width: 800px)'
	) );
	$this->css()->add( array(
		'selectors'    => array(
			'.site-navigation .menu .sub-menu a',
			'.site-navigation .menu .children a',
		),
		'declarations' => array(
			'border-bottom-color' => $color
		),
		'media'        => 'screen and (min-width: 800px)'
	) );
}

// Sub-Menu Item Background
if ( $is_style_preview || ! $this->thememod()->is_default( 'color-subnav-background' ) || ! $this->thememod()->is_default( 'color-subnav-background-opacity' ) ) {
	// Convert to RGBa
	$color = $this->helper()->hex_to_rgb( $this->thememod()->get_value( 'color-subnav-background' ) ) . ', ' . $this->thememod()->get_value( 'color-subnav-background-opacity' );

	$this->css()->add( array(
		'selectors'    => array(
			'.site-navigation .menu .sub-menu',
			'.site-navigation .menu .children',
		),
		'declarations' => array(
			'background-color' => 'rgba(' . $color . ')'
		),
		'media'        => 'screen and (min-width: 800px)'
	) );
}

// Sub-Menu Item Background hover
if ( $is_style_preview || ! $this->thememod()->is_default( 'color-subnav-background-hover' ) || ! $this->thememod()->is_default( 'color-subnav-background-hover-opacity' ) ) {
	// Convert to RGBa
	$color = $this->helper()->hex_to_rgb( $this->thememod()->get_value( 'color-subnav-background-hover' ) ) . ', ' . $this->thememod()->get_value( 'color-subnav-background-hover-opacity' );

	$this->css()->add( array(
		'selectors'    => array(
			'.site-navigation ul.menu ul a:hover',
			'.site-navigation ul.menu ul a:focus',
			'.site-navigation .menu ul ul a:hover',
			'.site-navigation .menu ul ul a:focus',
		),
		'declarations' => array(
			'background-color' => 'rgba(' . $color . ')'
		),
		'media'        => 'screen and (min-width: 800px)'
	) );
}

// Mobile Menu Trigger Background
if ( $is_style_preview || ! $this->thememod()->is_default( 'color-nav-mobile-menu-trigger-background' ) ) {
	// Convert to RGBa
	$color = $this->helper()->hex_to_rgb( $this->thememod()->get_value( 'color-nav-mobile-menu-trigger-background' ) );

	$this->css()->add( array(
		'selectors'    => array(
			'.site-navigation .menu-toggle',
		),
		'declarations' => array(
			'background-color' => 'rgba(' . $color . ')'
		),
		'media'        => 'screen and (max-width: 800px)'
	) );
}

// Current Item Background
if ( $is_style_preview || ! $this->thememod()->is_default( 'color-nav-current-item-background' ) || ! $this->thememod()->is_default( 'color-nav-current-item-background-opacity' ) ) {
	// Convert to RGBa
	$color = $this->helper()->hex_to_rgb( $this->thememod()->get_value( 'color-nav-current-item-background' ) ) . ', ' . $this->thememod()->get_value( 'color-nav-current-item-background-opacity' );

	$this->css()->add( array(
		'selectors'    => array(
			'.site-navigation .menu li.current_page_item',
			'.site-navigation .menu .children li.current_page_item',
			'.site-navigation .menu li.current_page_ancestor',
			'.site-navigation .menu li.current-menu-item',
			'.site-navigation .menu .sub-menu li.current-menu-item',
			'.site-navigation .menu li.current-menu-ancestor',
		),
		'declarations' => array(
			'background-color' => 'rgba(' . $color . ')'
		),
		'media'        => 'screen and (min-width: 800px)'
	) );
}

/**
 * Header Bar
 */

// Header Bar text color
if ( $is_style_preview || ! $this->thememod()->is_default( 'header-bar-text-color' ) ) {
	$this->css()->add( array(
		'selectors'    => array(
			'.header-bar',
			'.header-bar a',
			'.header-bar .menu li a',
		),
		'declarations' => array(
			'color' => $this->thememod()->get_value( 'header-bar-text-color' )
		)
	) );
}

// Header Bar link color
if ( $is_style_preview || ! $this->thememod()->is_default( 'header-bar-link-color' ) ) {
	$this->css()->add( array(
		'selectors'    => array(
			'.header-bar a',
			'.header-bar .menu li a',
			'.header-bar .social-links a',
		),
		'declarations' => array(
			'color' => $this->thememod()->get_value( 'header-bar-link-color' )
		)
	) );
}

// Header Bar link hover color
if ( $is_style_preview || ! $this->thememod()->is_default( 'header-bar-link-hover-color' ) ) {
	$this->css()->add( array(
		'selectors'    => array(
			'.header-bar a:hover',
			'.header-bar a:focus',
			'.header-bar .menu li a:hover',
			'.header-bar .menu li a:focus',
		),
		'declarations' => array(
			'color' => $this->thememod()->get_value( 'header-bar-link-hover-color' )
		)
	) );
}

// Header Bar border color
if ( $is_style_preview || ! $this->thememod()->is_default( 'header-bar-border-color' ) ) {
	// Convert to RGBa
	$color = $this->helper()->hex_to_rgb( $this->thememod()->get_value( 'header-bar-border-color' ) ) . ', ' . $this->thememod()->get_value( 'header-bar-background-color-opacity' );

	$this->css()->add( array(
		'selectors'    => array(
			'.header-bar',
			'.header-bar .search-form input',
			'.header-social-links li:first-of-type',
			'.header-social-links li a',
		),
		'declarations' => array(
			'border-color' => 'rgba(' . $color . ')'
		)
	) );
}

// Header Bar background color
if ( $is_style_preview || ! $this->thememod()->is_default( 'header-bar-background-color' ) || ! $this->thememod()->is_default( 'header-bar-background-color-opacity' ) ) {
	// Convert to RGBa
	$color = $this->helper()->hex_to_rgb( $this->thememod()->get_value( 'header-bar-background-color' ) ) . ', ' . $this->thememod()->get_value( 'header-bar-background-color-opacity' );

	$this->css()->add( array(
		'selectors'    => array( '.header-bar' ),
		'declarations' => array(
			'background-color' => 'rgba(' . $color . ')'
		)
	) );
}

/**
 * Sidebars
 */

// Sidebar widget title
if ( $is_style_preview || ! $this->thememod()->is_default( 'color-widget-title-text' ) ) {
	$this->css()->add( array(
		'selectors'    => array(
			'.sidebar .widget-title',
			'.sidebar .widgettitle',
			'.sidebar .widget-title a',
			'.sidebar .widgettitle a',
		),
		'declarations' => array(
			'color' => $this->thememod()->get_value( 'color-widget-title-text' )
		),
	) );
}

// Sidebar widget body
if ( $is_style_preview || ! $this->thememod()->is_default( 'color-widget-text' ) ) {
	$this->css()->add( array(
		'selectors'    => array( '.sidebar .widget' ),
		'declarations' => array(
			'color' => $this->thememod()->get_value( 'color-widget-text' )
		),
	) );
}

// Sidebar link
if ( $is_style_preview || ! $this->thememod()->is_default( 'color-widget-link' ) ) {
	$this->css()->add( array(
		'selectors'    => array( '.sidebar a' ),
		'declarations' => array(
			'color' => $this->thememod()->get_value( 'color-widget-link' )
		),
	) );
}

// Sidebar link hover
if ( $is_style_preview || ! $this->thememod()->is_default( 'color-widget-link-hover' ) ) {
	$this->css()->add( array(
		'selectors'    => array(
			'.sidebar a:hover',
			'.sidebar a:focus',
		),
		'declarations' => array(
			'color' => $this->thememod()->get_value( 'color-widget-link-hover' )
		),
	) );
}

// Sidebar widget border
if ( $is_style_preview || ! $this->thememod()->is_default( 'color-widget-border' ) ) {
	$this->css()->add( array(
		'selectors'    => array(
			'.sidebar table',
			'.sidebar table th',
			'.sidebar table td',
			'.sidebar .widget li',
		),
		'declarations' => array(
			'border-color' => $this->thememod()->get_value( 'color-widget-border' )
		),
	) );
}

/**
 * Footer section
 */

// Footer text color
if ( $is_style_preview || ! $this->thememod()->is_default( 'footer-text-color' ) ) {
	$this->css()->add( array(
		'selectors'    => array(
			'.site-footer',
			'.site-footer .social-links a',
		),
		'declarations' => array(
			'color' => $this->thememod()->get_value( 'footer-text-color' )
		)
	) );
}

// Footer link color
if ( $is_style_preview || ! $this->thememod()->is_default( 'footer-link-color' ) ) {
	$this->css()->add( array(
		'selectors'    => array( '.site-footer a' ),
		'declarations' => array(
			'color' => $this->thememod()->get_value( 'footer-link-color' )
		)
	) );
}

// Footer link hover color
if ( $is_style_preview || ! $this->thememod()->is_default( 'footer-link-hover-color' ) || ! $this->thememod()->is_default( 'footer-link-color' ) ) {
	$this->css()->add( array(
		'selectors'    => array(
			'.site-footer a:hover',
			'.site-footer a:focus',
		),
		'declarations' => array(
			'color' => $this->thememod()->get_value( 'footer-link-hover-color' )
		)
	) );
}

// Footer border color
if ( $is_style_preview || ! $this->thememod()->is_default( 'footer-border-color' ) ) {
	$this->css()->add( array(
		'selectors'    => array( '.site-footer *:not(select)' ),
		'declarations' => array(
			'border-color' => $this->thememod()->get_value( 'footer-border-color' ) . ' !important'
		)
	) );
}

// Footer background color
if ( $is_style_preview || ! $this->thememod()->is_default( 'footer-background-color' ) || ! $this->thememod()->is_default( 'footer-background-color-opacity' ) ) {
	// Convert to RGBa
	$color = $this->helper()->hex_to_rgb( $this->thememod()->get_value( 'footer-background-color' ) ) . ', ' . $this->thememod()->get_value( 'footer-background-color-opacity' );

	$this->css()->add( array(
		'selectors'    => array( '.site-footer' ),
		'declarations' => array(
			'background-color' => 'rgba(' . $color . ')'
		)
	) );
}