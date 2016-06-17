<?php
/**
 * @package Make
 */

// Bail if this isn't being included inside of a MAKE_Compatibility_MethodsInterface.
if ( ! isset( $this ) || ! $this instanceof MAKE_Compatibility_MethodsInterface ) {
	return;
}

if ( ! function_exists( 'ttfmake_customizer_supports_panels' ) ) :
/**
 * Detect support for Customizer panels.
 *
 * This feature was introduced in WP 4.0. The WP_Customize_Manager class is not loaded
 * outside of the Customizer, so this also looks for wp_validate_boolean(), another
 * function added in WP 4.0.
 *
 * This function has been deprecated, as Make no longer supports WordPress versions that don't support panels.
 *
 * @since  1.3.0.
 * @deprecated 1.6.0.
 *
 * @return bool    Whether or not panels are supported.
 */
function ttfmake_customizer_supports_panels() {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.6.0' );
	return ( class_exists( 'WP_Customize_Manager' ) && method_exists( 'WP_Customize_Manager', 'add_panel' ) ) || function_exists( 'wp_validate_boolean' );
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_customizer_supports_panels', '1.6.0' );
endif;

if ( ! function_exists( 'ttfmake_customizer_add_legacy_sections' ) ) :
/**
 * Add the old sections and controls to the customizer for WP installations with no panel support.
 *
 * This function has been deprecated, as Make no longer supports WordPress versions that don't support panels.
 *
 * @since  1.3.0.
 * @deprecated 1.6.0.
 *
 * @param  WP_Customize_Manager    $wp_customize    Theme Customizer object.
 * @return void
 */
function ttfmake_customizer_add_legacy_sections( $wp_customize ) {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.6.0' );
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_customizer_add_legacy_sections', '1.6.0' );
endif;

if ( ! function_exists( 'ttfmake_css_legacy_fonts' ) ) :
/**
 * Build the CSS rules for the custom fonts.
 *
 * This function has been deprecated, as Make no longer supports WordPress versions that don't support panels.
 *
 * @since  1.0.0.
 * @deprecated 1.6.0.
 *
 * @return void
 */
function ttfmake_css_legacy_fonts() {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.6.0' );
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_css_legacy_fonts', '1.6.0' );
endif;