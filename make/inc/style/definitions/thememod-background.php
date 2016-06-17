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
 * Background images
 */
$regions = array(
	'header' => array( '.site-header-main' ),
	'main'   => array( '.site-content' ),
	'footer' => array( '.site-footer' ),
);

foreach ( $regions as $region => $selectors ) {
	$background_image = $this->thememod()->get_value( $region . '-background-image', 'style' );
	if ( ! empty( $background_image ) ) {
		$this->css()->add( array(
			'selectors'    => $selectors,
			'declarations' => array(
				'background-image'      => 'url(' . addcslashes( $background_image, '"' ) . ')',
				'background-repeat'     => $this->thememod()->get_value( $region . '-background-repeat', 'style' ),
				'background-position'   => str_replace( '-', ' ', $this->thememod()->get_value( $region . '-background-position', 'style' ) ),
				'background-attachment' => $this->thememod()->get_value( $region . '-background-attachment', 'style' ),
				'background-size'       => $this->thememod()->get_value( $region . '-background-size', 'style' ),
			)
		) );
	}
	// Explicitly set empty background images if this is a style preview.
	else if ( $is_style_preview ) {
		$this->css()->add( array(
			'selectors'    => $selectors,
			'declarations' => array(
				'background-image' => 'none',
			)
		) );
	}
}

/**
 * Site background image position and size
 *
 * Position: WordPress prevents vertical positioning options from working by adding "top" to the rule in the header.
 * This will override that rule.
 *
 * Size: WordPress does not offer this rule be default.
 */
$site_background_image = $this->thememod()->get_value( 'background_image', 'style' );
if ( ! empty( $site_background_image ) ) {
	$this->css()->add( array(
		'selectors'    => array( 'body.custom-background' ),
		'declarations' => array(
			'background-position' => str_replace( '-', ' ', $this->thememod()->get_value( 'background_position_x', 'style' ) ),
			'background-size'     => $this->thememod()->get_value( 'background_size', 'style' ),
		),
	) );
}