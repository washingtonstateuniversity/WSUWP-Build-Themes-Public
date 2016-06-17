<?php
/**
 * @package Make
 */

// Prevent Make from activating if WordPress doesn't meet the minimum version requirement.
if ( version_compare( $GLOBALS['wp_version'], TTFMAKE_MIN_WP_VERSION, '<' ) ) {
	add_action( 'after_switch_theme', 'make_prevent_activation' );
}

/**
 * Switch to the default theme and display a notice.
 *
 * @since 1.6.1.
 *
 * @link https://github.com/WordPress/WordPress/blob/4.1.1/wp-content/themes/twentyfifteen/inc/back-compat.php#L14-L39
 *
 * @return void
 */
function make_prevent_activation() {
	switch_theme( WP_DEFAULT_THEME, WP_DEFAULT_THEME );
	unset( $_GET['activated'] );
	add_action( 'admin_notices', 'make_deactivation_notice' );
}

/**
 * Show deactivation notice.
 *
 * @since 1.6.1.
 *
 * @return void
 */
function make_deactivation_notice() {
	$message = sprintf(
		esc_html__( 'Make requires at least WordPress version %1$s. You are running version %2$s. Please upgrade and try again.', 'make' ),
		esc_html( TTFMAKE_MIN_WP_VERSION ),
		esc_html( $GLOBALS['wp_version'] )
	);

	printf(
		'<div class="notice notice-error error"><p>%s</p></div>',
		$message
	);
}