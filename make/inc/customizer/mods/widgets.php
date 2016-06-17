<?php
/**
 * @package Make
 */

// Bail if this isn't being included inside of a MAKE_Customizer_ControlsInterface.
if ( ! isset( $this ) || ! $this instanceof MAKE_Customizer_ControlsInterface ) {
	return;
}

// Panel ID
$panel_id = 'widgets';

// Panel object
$panel = $wp_customize->get_panel( $panel_id );

// Bail if the panel isn't registered
if ( ! $panel instanceof WP_Customize_Panel ) {
	return;
}

// Get the last priority
$last_priority = $this->get_last_priority( $wp_customize->panels() );
$priority->set( $last_priority + 100 );
$panel->priority = $priority->add();

// Rename the panel
$panel->title = __( 'Sidebars & Widgets', 'make' );