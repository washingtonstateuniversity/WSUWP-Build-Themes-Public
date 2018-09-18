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

/**
 * Deactivate Make Plus if it doesn't meet
 * the minimum version requirements.
 *
 * @since 1.9.1.
 *
 * @return void
 */
if ( defined( 'MAKEPLUS_VERSION' ) && version_compare( MAKEPLUS_VERSION, '1.9.0' ) < 0 ) {
	$plugin_name = plugin_basename( makeplus_get_plugin_directory() );
	$redirect_url = add_query_arg( 'make-plus-unsupported', '' );

	// Deactivate and redirect back with error
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	deactivate_plugins( "{$plugin_name}/{$plugin_name}.php" );
	wp_safe_redirect( $redirect_url );
	exit();
}

/**
 * Show unsupported Make Plus version notice.
 *
 * @since 1.9.1.
 *
 * @return void
 */
function makeplus_unsupported_version() {
	$message = sprintf(
		esc_html__( 'Make %1$s isn\'t compatible with your version of Make Plus. To avoid issues, Make Plus has been deactivated. Please update Make Plus.', 'make' ),
		esc_html( TTFMAKE_VERSION )
	);

	printf(
		'<div class="notice notice-error error"><p>%s</p></div>',
		$message
	);
}

if( isset( $_GET['make-plus-unsupported'] ) ) {
	add_action( 'admin_notices', 'makeplus_unsupported_version' );
}
