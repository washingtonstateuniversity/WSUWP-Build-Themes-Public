<?php
/**
 * @package Make
 */

// Bail if this isn't being included inside of a MAKE_Choices_ManagerInterface.
if ( ! isset( $this ) || ! $this instanceof MAKE_Choices_ManagerInterface ) {
	return;
}

// Add the default choice sets.
$this->add_choice_sets( array(
	'0-4' => array(
		0 => __( '0', 'make' ),
		1 => __( '1', 'make' ),
		2 => __( '2', 'make' ),
		3 => __( '3', 'make' ),
		4 => __( '4', 'make' )
	),
	'alignment-horizontal-2' => array(
		'left'  => __( 'Left', 'make' ),
		'right' => __( 'Right', 'make' )
	),
	'alignment-horizontal-3' => array(
		'left'   => __( 'Left', 'make' ),
		'center' => __( 'Center', 'make' ),
		'right'  => __( 'Right', 'make' )
	),
	'alignment-full-9' => array(
		'top-left'      => __( 'Top Left', 'make' ),
		'top'           => __( 'Top', 'make' ),
		'top-right'     => __( 'Top Right', 'make' ),
		'left'          => __( 'Left', 'make' ),
		'center'        => __( 'Center', 'make' ),
		'right'         => __( 'Right', 'make' ),
		'bottom-left'   => __( 'Bottom Left', 'make' ),
		'bottom'        => __( 'Bottom', 'make' ),
		'bottom-right'  => __( 'Bottom Right', 'make' ),
	),
	'background-attachment' => array(
		'scroll' => __( 'Scroll', 'make' ),
		'fixed'  => __( 'Fixed', 'make' ),
	),
	'background-repeat' => array(
		'no-repeat' => __( 'No Repeat', 'make' ),
		'repeat'    => __( 'Tile', 'make' ),
		'repeat-x'  => __( 'Tile Horizontally', 'make' ),
		'repeat-y'  => __( 'Tile Vertically', 'make' )
	),
	'background-size' => array(
		'auto'    => __( 'Auto', 'make' ),
		'cover'   => __( 'Cover', 'make' ),
		'contain' => __( 'Contain', 'make' )
	),
	'comment-count' => array(
		'icon' => __( 'With icon', 'make' ),
		'text' => __( 'With text', 'make' ),
		'none' => __( 'None', 'make' ),
	),
	'featured-images' => array(
		'post-header' => __( 'Post header', 'make' ),
		'thumbnail'   => __( 'Thumbnail', 'make' ),
		'none'        => __( 'None', 'make' ),
	),
	'font-style' => array(
		'normal' => __( 'Normal', 'make' ),
		'italic' => __( 'Italic', 'make' ),
	),
	'font-weight' => array(
		'normal' => __( 'Normal', 'make' ),
		'bold'   => __( 'Bold', 'make' ),
	),
	'footer-layout' => array(
		1  => __( 'Traditional', 'make' ),
		2  => __( 'Centered', 'make' ),
	),
	'general-layout' => array(
		'full-width' => __( 'Full-width', 'make' ),
		'boxed'      => __( 'Boxed', 'make' )
	),
	'header-bar-content-layout' => array(
		'default' => __( 'Default', 'make' ),
		'flipped' => __( 'Flipped', 'make' )
	),
	'header-layout' => array(
		1  => __( 'Traditional', 'make' ),
		2  => __( 'Centered', 'make' ),
		3  => __( 'Navigation Below (Primary)', 'make' ),
	),
	'link-underline' => array(
		'always' => __( 'Always', 'make' ),
		'hover'  => __( 'On hover/focus', 'make' ),
		'never'  => __( 'Never', 'make' ),
	),
	'post-author' => array(
		'avatar' => __( 'With avatar', 'make' ),
		'name'   => __( 'Without avatar', 'make' ),
		'none'   => __( 'None', 'make' ),
	),
	'post-date' => array(
		// Translators: %s is a placeholder for a date.
		'absolute' => sprintf( __( 'Absolute (%s)', 'make' ), date( get_option( 'date_format' ), time() - WEEK_IN_SECONDS ) ),
		'relative' => __( 'Relative (1 week ago)', 'make' ),
		'none'     => __( 'None', 'make' ),
	),
	'post-item-location' => array(
		'top'            => __( 'Top', 'make' ),
		'before-content' => __( 'Before content', 'make' ),
		'post-footer'    => __( 'Post footer', 'make' ),
	),
	'social-icon-type' => array(
		'email' => __( 'Email', 'make' ),
		'rss'   => __( 'RSS', 'make' ),
		'link'  => __( 'Link', 'make' ),
	),
	'text-transform' => array(
		'none'      => __( 'None', 'make' ),
		'uppercase' => __( 'Uppercase', 'make' ),
		'lowercase' => __( 'Lowercase', 'make' ),
	),

	'mobile-menu' => get_registered_nav_menus()
) );