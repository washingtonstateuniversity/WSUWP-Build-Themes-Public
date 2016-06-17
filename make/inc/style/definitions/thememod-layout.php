<?php
/**
 * @package Make
 */

// Bail if this isn't being included inside of a MAKE_Style_ManagerInterface.
if ( ! isset( $this ) || ! $this instanceof MAKE_Style_ManagerInterface ) {
	return;
}

$is_style_preview = isset( $_POST['make-preview'] );

// Header padding
if ( $is_style_preview || ( ! $this->thememod()->is_default( 'header-hide-padding-bottom' ) && true === $this->thememod()->get_value( 'header-hide-padding-bottom' ) ) ) {
	$this->css()->add( array(
		'selectors'    => array( '.site-content' ),
		'declarations' => array(
			'padding-top' => 0
		),
	) );
}

// Footer padding
if ( $is_style_preview || ( ! $this->thememod()->is_default( 'footer-hide-padding-top' ) && true === $this->thememod()->get_value( 'footer-hide-padding-top' ) ) ) {
	$this->css()->add( array(
		'selectors'    => array( '.site-content' ),
		'declarations' => array(
			'padding-bottom' => 0
		),
	) );
}

// Featured image alignment
foreach ( array( 'blog', 'archive', 'search', 'post', 'page' ) as $view ) {
	$key = "layout-$view-featured-images-alignment";
	if ( $is_style_preview || ! $this->thememod()->is_default( $key ) ) {
		$this->css()->add( array(
			'selectors'    => array( ".view-$view .entry-header .entry-thumbnail" ),
			'declarations' => array(
				'text-align' => $this->thememod()->get_value( $key ),
			),
		) );
	}
}