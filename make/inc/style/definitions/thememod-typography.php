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
 * Body
 */
$element = 'body';
$selectors = array( 'body', '.font-body' );
$body_font_customized = false;
$declarations = $this->helper()->parse_font_properties( $element, $is_style_preview );
if ( ! empty( $declarations ) ) {
	$this->css()->add( array( 'selectors' => $selectors, 'declarations' => $declarations, ) );
	$body_font_customized = true;
}
$link_rule = $this->helper()->parse_link_underline( $element, array( 'a' ), $is_style_preview );
if ( ! empty( $link_rule ) ) {
	$this->css()->add( $link_rule );
}
// Links
if ( $is_style_preview || ! $this->thememod()->is_default( 'font-weight-body-link' ) ) {
	$this->css()->add( array(
		'selectors'    => array( 'a' ),
		'declarations' => array(
			'font-weight' => $this->thememod()->get_value( 'font-weight-body-link', 'style' ),
		)
	) );
}
// Comments
if ( isset( $declarations['font-size-px'] ) ) {
	$this->css()->add( array(
		'selectors'    => array( '#comments' ),
		'declarations' => array(
			'font-size-px'  => $this->helper()->get_relative_font_size( $declarations['font-size-px'], $this->helper()->get_relative_size( 'comments' ) ) . 'px',
			'font-size-rem' => $this->helper()->convert_px_to_rem( $this->helper()->get_relative_font_size( $declarations['font-size-px'], $this->helper()->get_relative_size( 'comments' ) ) ) . 'rem'
		)
	) );
	// Comment date
	$this->css()->add( array(
		'selectors'    => array( '.comment-date' ),
		'declarations' => array(
			'font-size-px'  => $this->helper()->get_relative_font_size( $declarations['font-size-px'], $this->helper()->get_relative_size( 'comment-date' ) ) . 'px',
			'font-size-rem' => $this->helper()->convert_px_to_rem( $this->helper()->get_relative_font_size( $declarations['font-size-px'], $this->helper()->get_relative_size( 'comment-date' ) ) ) . 'rem'
		)
	) );
}
// Buttons
$element = 'button';
$selectors = array( 'button', '.ttfmake-button', 'input[type="button"]', 'input[type="reset"]', 'input[type="submit"]', '.site-main .gform_wrapper .gform_footer input.button' );
$declarations = $this->helper()->parse_font_properties( $element, $is_style_preview );
if ( ! empty( $declarations ) ) {
	$this->css()->add( array( 'selectors' => $selectors, 'declarations' => $declarations, ) );
}

/**
 * H1
 */
$element = 'h1';
$selectors = array( 'h1:not(.site-title)', '.font-header', '.entry-title' );
$declarations = $this->helper()->parse_font_properties( $element, $is_style_preview );
if ( ! empty( $declarations ) ) {
	$this->css()->add( array( 'selectors' => $selectors, 'declarations' => $declarations, ) );
}
$link_rule = $this->helper()->parse_link_underline( $element, array( 'h1:not(.site-title) a' ), $is_style_preview );
if ( ! empty( $link_rule ) ) {
	$this->css()->add( $link_rule );
}

/**
 * H2
 */
$element = 'h2';
$selectors = array( 'h2' );
$declarations = $this->helper()->parse_font_properties( $element, $is_style_preview );
if ( ! empty( $declarations ) ) {
	$this->css()->add( array( 'selectors' => $selectors, 'declarations' => $declarations, ) );
}
$link_rule = $this->helper()->parse_link_underline( $element, array( 'h2 a' ), $is_style_preview );
if ( ! empty( $link_rule ) ) {
	$this->css()->add( $link_rule );
}
// Post title with two sidebars
if ( isset( $declarations['font-size-px'] ) ) {
	$this->css()->add( array(
		'selectors'    => array( '.has-left-sidebar.has-right-sidebar .entry-title' ),
		'declarations' => array(
			'font-size-px'  => $this->helper()->get_relative_font_size( $declarations['font-size-px'], $this->helper()->get_relative_size( 'post-title' ) ) . 'px',
			'font-size-rem' => $this->helper()->convert_px_to_rem( $this->helper()->get_relative_font_size( $declarations['font-size-px'], $this->helper()->get_relative_size( 'post-title' ) ) ) . 'rem'
		),
		'media'        => 'screen and (min-width: 800px)'
	) );
}

/**
 * H3
 */
$element = 'h3';
$selectors = array( 'h3', '.builder-text-content .widget-title' );
$declarations = $this->helper()->parse_font_properties( $element, $is_style_preview );
if ( ! empty( $declarations ) ) {
	$this->css()->add( array( 'selectors' => $selectors, 'declarations' => $declarations, ) );
}
$link_rule = $this->helper()->parse_link_underline( $element, array( 'h3 a' ), $is_style_preview );
if ( ! empty( $link_rule ) ) {
	$this->css()->add( $link_rule );
}

/**
 * H4
 */
$element = 'h4';
$selectors = array( 'h4' );
$declarations = $this->helper()->parse_font_properties( $element, $is_style_preview );
if ( ! empty( $declarations ) ) {
	$this->css()->add( array( 'selectors' => $selectors, 'declarations' => $declarations, ) );
}
$link_rule = $this->helper()->parse_link_underline( $element, array( 'h4 a' ), $is_style_preview );
if ( ! empty( $link_rule ) ) {
	$this->css()->add( $link_rule );
}

/**
 * H5
 */
$element = 'h5';
$selectors = array( 'h5' );
$declarations = $this->helper()->parse_font_properties( $element, $is_style_preview );
if ( ! empty( $declarations ) ) {
	$this->css()->add( array( 'selectors' => $selectors, 'declarations' => $declarations, ) );
}
$link_rule = $this->helper()->parse_link_underline( $element, array( 'h5 a' ), $is_style_preview );
if ( ! empty( $link_rule ) ) {
	$this->css()->add( $link_rule );
}

/**
 * H6
 */
$element = 'h6';
$selectors = array( 'h6' );
$declarations = $this->helper()->parse_font_properties( $element, $is_style_preview );
if ( ! empty( $declarations ) ) {
	$this->css()->add( array( 'selectors' => $selectors, 'declarations' => $declarations, ) );
}
$link_rule = $this->helper()->parse_link_underline( $element, array( 'h6 a' ), $is_style_preview );
if ( ! empty( $link_rule ) ) {
	$this->css()->add( $link_rule );
}

/**
 * Site Title
 */
$element = 'site-title';
$selectors = array( '.site-title', '.site-title a', '.font-site-title' );
$declarations = $this->helper()->parse_font_properties( $element, $is_style_preview );
if ( ! empty( $declarations ) ) {
	$this->css()->add( array( 'selectors' => $selectors, 'declarations' => $declarations, ) );
}
$link_rule = $this->helper()->parse_link_underline( $element, array( '.site-title a' ), $is_style_preview );
if ( ! empty( $link_rule ) ) {
	$this->css()->add( $link_rule );
}

/**
 * Site Tagline
 */
$element = 'site-tagline';
$selectors = array( '.site-description', '.site-description a', '.font-site-tagline' );
$declarations = $this->helper()->parse_font_properties( $element, $is_style_preview );
if ( ! empty( $declarations ) ) {
	$this->css()->add( array( 'selectors' => $selectors, 'declarations' => $declarations, ) );
}
$link_rule = $this->helper()->parse_link_underline( $element, array( '.site-description a' ), $is_style_preview );
if ( ! empty( $link_rule ) ) {
	$this->css()->add( $link_rule );
}

/**
 * Menu Item
 */
$menu_items_customized = false;
$element = 'nav';
$selectors = array( '.site-navigation .menu li a', '.font-nav', '.site-navigation .menu-toggle' );
$force = $is_style_preview || $body_font_customized;
$declarations = $this->helper()->parse_font_properties( $element, $force );
if ( ! empty( $declarations ) ) {
	$this->css()->add( array( 'selectors' => $selectors, 'declarations' => $declarations, ) );
	$menu_items_customized = true;
}
$link_rule = $this->helper()->parse_link_underline( $element, array( '.site-navigation .menu li a' ), $is_style_preview );
if ( ! empty( $link_rule ) ) {
	$this->css()->add( $link_rule );
}
// Arrow size and grandchild arrow size and position
if ( isset( $declarations['font-size-px'] ) ) {
	$this->css()->add( array(
		'selectors'    => array(
			'.site-navigation .menu .page_item_has_children a:after',
			'.site-navigation .menu .menu-item-has-children a:after'
		),
		'declarations' => array(
			'top' => '-' . $this->helper()->get_relative_font_size( $declarations['font-size-px'], 10 ) . 'px',
			'font-size-px' => $this->helper()->get_relative_font_size( $declarations['font-size-px'], 72 ) . 'px',
			'font-size-rem' => $this->helper()->convert_px_to_rem( $this->helper()->get_relative_font_size( $declarations['font-size-px'], 72 ) ) . 'rem'
		),
		'media'        => 'screen and (min-width: 800px)'
	) );
}

/**
 * Sub-Menu Item
 */
$submenu_items_customized = false;
$element = 'subnav';
$selectors = array( '.site-navigation .menu .sub-menu li a', '.site-navigation .menu .children li a' );
$force = $is_style_preview || $menu_items_customized;
$declarations = $this->helper()->parse_font_properties( $element, $force );
$simplify_mobile = $this->thememod()->get_value( 'font-' . $element . '-mobile', 'style' );
$media = 'all';
if ( true === $simplify_mobile ) {
	$media = 'screen and (min-width: 800px)';
}
if ( ! empty( $declarations ) ) {
	$this->css()->add( array( 'selectors' => $selectors, 'declarations' => $declarations, 'media' => $media ) );
	$submenu_items_customized = true;
}
$link_rule = $this->helper()->parse_link_underline( $element, array( '.site-navigation .menu .sub-menu li a', '.site-navigation .menu .children li a' ), $is_style_preview );
if ( ! empty( $link_rule ) ) {
	$link_rule['media'] = $media;
	$this->css()->add( $link_rule );
}
// Grandchild arrow size
if ( isset( $declarations['font-size-px'] ) ) {
	$this->css()->add( array(
		'selectors'    => array(
			'.site-navigation .menu .children .page_item_has_children a:after',
			'.site-navigation .menu .sub-menu .menu-item-has-children a:after'
		),
		'declarations' => array(
			'font-size-px' => $this->helper()->get_relative_font_size( $declarations['font-size-px'], 72 ) . 'px',
			'font-size-rem' => $this->helper()->convert_px_to_rem( $this->helper()->get_relative_font_size( $declarations['font-size-px'], 72 ) ) . 'rem'
		),
		'media'        => 'screen and (min-width: 800px)'
	) );
}

/**
 * Current Item
 */
if ( $is_style_preview || ! $this->thememod()->is_default( 'font-weight-nav-current-item' ) || true === $menu_items_customized || true === $submenu_items_customized ) {
	$this->css()->add( array(
		'selectors' => array(
			'.site-navigation .menu li.current_page_item > a',
			'.site-navigation .menu .children li.current_page_item > a',
			'.site-navigation .menu li.current-menu-item > a',
			'.site-navigation .menu .sub-menu li.current-menu-item > a',
		),
		'declarations' => array(
			'font-weight' => $this->thememod()->get_value( 'font-weight-nav-current-item', 'style' )
		),
	) );
	$this->css()->add( array(
		'selectors' => array(
			'.site-navigation .menu li.current_page_item > a',
			'.site-navigation .menu .children li.current_page_item > a',
			'.site-navigation .menu li.current_page_ancestor > a',
			'.site-navigation .menu li.current-menu-item > a',
			'.site-navigation .menu .sub-menu li.current-menu-item > a',
			'.site-navigation .menu li.current-menu-ancestor > a',
		),
		'declarations' => array(
			'font-weight' => $this->thememod()->get_value( 'font-weight-nav-current-item', 'style' )
		),
		'media' => 'screen and (min-width: 800px)',
	) );
}

/**
 * Header Bar Text
 */
$element = 'header-bar-text';
$selectors = array( '.header-bar', '.header-text', '.header-bar .search-form input', '.header-bar .menu a', '.header-navigation .mobile-menu ul.menu li a' );
$force = $is_style_preview || $body_font_customized;
$declarations = $this->helper()->parse_font_properties( $element, $force );
if ( ! empty( $declarations ) ) {
	$this->css()->add( array( 'selectors' => $selectors, 'declarations' => $declarations, ) );
}
$link_rule = $this->helper()->parse_link_underline( $element, array( '.header-bar a', '.header-text a', '.header-bar .menu a' ), $is_style_preview );
if ( ! empty( $link_rule ) ) {
	$this->css()->add( $link_rule );
}
// Header Bar Icons
$header_icon_size = $this->thememod()->get_value( 'font-size-header-bar-icon', 'style' );
if ( $is_style_preview || ! $this->thememod()->is_default( 'font-size-header-bar-icon' ) ) {
	$this->css()->add( array(
		'selectors' => array( '.header-social-links li a' ),
		'declarations' => array(
			'font-size-px' => $header_icon_size . 'px',
			'font-size-rem' => $this->helper()->convert_px_to_rem( $header_icon_size ) . 'rem'
		),
	) );
	$this->css()->add( array(
		'selectors' => array( '.header-social-links li a' ),
		'declarations' => array(
			'font-size-px' => $this->helper()->get_relative_font_size( $header_icon_size, $this->helper()->get_relative_size( 'header-bar-icon' ) ) . 'px',
			'font-size-rem' => $this->helper()->convert_px_to_rem( $this->helper()->get_relative_font_size( $header_icon_size, $this->helper()->get_relative_size( 'header-bar-icon' ) ) ) . 'rem'
		),
		'media' => 'screen and (min-width: 1100px)'
	) );
}

/**
 * Sidebar Widget Title
 */
$element = 'widget-title';
$selectors = array( '.sidebar .widget-title', '.sidebar .widgettitle', '.sidebar .widget-title a', '.sidebar .widgettitle a', '.font-widget-title' );
$force = $is_style_preview || $body_font_customized;
$declarations = $this->helper()->parse_font_properties( $element, $force );
if ( ! empty( $declarations ) ) {
	$this->css()->add( array( 'selectors' => $selectors, 'declarations' => $declarations, ) );
}
$link_rule = $this->helper()->parse_link_underline( $element, array( '.sidebar .widget-title a', '.sidebar .widgettitle a' ), $is_style_preview );
if ( ! empty( $link_rule ) ) {
	$this->css()->add( $link_rule );
}

/**
 * Sidebar Widget Body
 */
$element = 'widget';
$selectors = array( '.sidebar .widget', '.font-widget' );
$force = ( $is_style_preview || ! $this->thememod()->is_default( 'font-family-body' ) );
$declarations = $this->helper()->parse_font_properties( $element, $force );
if ( ! empty( $declarations ) ) {
	$this->css()->add( array( 'selectors' => $selectors, 'declarations' => $declarations, ) );
}
$link_rule = $this->helper()->parse_link_underline( $element, array( '.sidebar .widget a' ), $is_style_preview );
if ( ! empty( $link_rule ) ) {
	$this->css()->add( $link_rule );
}

/**
 * Footer Widget Title
 */
$element = 'footer-widget-title';
$selectors = array( '.footer-widget-container .widget-title', '.footer-widget-container .widgettitle', '.footer-widget-container .widget-title a', '.footer-widget-container .widgettitle a' );
$force = $is_style_preview || $body_font_customized;
$declarations = $this->helper()->parse_font_properties( $element, $force );
if ( ! empty( $declarations ) ) {
	$this->css()->add( array( 'selectors' => $selectors, 'declarations' => $declarations, ) );
}
$link_rule = $this->helper()->parse_link_underline( $element, array( '.footer-widget-container .widget-title a', '.footer-widget-container .widgettitle a' ), $is_style_preview );
if ( ! empty( $link_rule ) ) {
	$this->css()->add( $link_rule );
}

/**
 * Footer Widget Body
 */
$element = 'footer-widget';
$selectors = array( '.footer-widget-container .widget' );
$force = ( $is_style_preview || ! $this->thememod()->is_default( 'font-family-body' ) );
$declarations = $this->helper()->parse_font_properties( $element, $force );
if ( ! empty( $declarations ) ) {
	$this->css()->add( array( 'selectors' => $selectors, 'declarations' => $declarations, ) );
}
$link_rule = $this->helper()->parse_link_underline( $element, array( '.footer-widget-container .widget a' ), $is_style_preview );
if ( ! empty( $link_rule ) ) {
	$this->css()->add( $link_rule );
}

/**
 * Footer Text
 */
$element = 'footer-text';
$selectors = array( '.footer-text' );
$force = ( $is_style_preview || ! $this->thememod()->is_default( 'font-family-body' ) );
$declarations = $this->helper()->parse_font_properties( $element, $force );
if ( ! empty( $declarations ) ) {
	$this->css()->add( array( 'selectors' => $selectors, 'declarations' => $declarations, ) );
}
$link_rule = $this->helper()->parse_link_underline( $element, array( '.footer-text a' ), $is_style_preview );
if ( ! empty( $link_rule ) ) {
	$this->css()->add( $link_rule );
}
// Footer Icons
$footer_icon_size = $this->thememod()->get_value( 'font-size-footer-icon', 'style' );
if ( $is_style_preview || ! $this->thememod()->is_default( 'font-size-footer-icon' ) ) {
	$this->css()->add( array(
		'selectors' => array( '.footer-social-links' ),
		'declarations' => array(
			'font-size-px' => $footer_icon_size . 'px',
			'font-size-rem' => $this->helper()->convert_px_to_rem( $footer_icon_size ) . 'rem'
		),
	) );
	$this->css()->add( array(
		'selectors' => array( '.footer-social-links' ),
		'declarations' => array(
			'font-size-px' => $this->helper()->get_relative_font_size( $footer_icon_size, $this->helper()->get_relative_size( 'footer-icon' ) ) . 'px',
			'font-size-rem' => $this->helper()->convert_px_to_rem( $this->helper()->get_relative_font_size( $footer_icon_size, $this->helper()->get_relative_size( 'footer-icon' ) ) ) . 'rem'
		),
		'media' => 'screen and (min-width: 1100px)'
	) );
}
