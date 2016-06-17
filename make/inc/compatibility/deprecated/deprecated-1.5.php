<?php
/**
 * @package Make
 */

// Bail if this isn't being included inside of a MAKE_Compatibility_MethodsInterface.
if ( ! isset( $this ) || ! $this instanceof MAKE_Compatibility_MethodsInterface ) {
	return;
}

if ( ! function_exists( 'ttfmake_display_favicons' ) ) :
/**
 * Write the favicons to the head to implement the options.
 *
 * This function is deprecated. The functionality was moved to ttfmake_head_late().
 *
 * @since  1.0.0.
 * @deprecated 1.5.0.
 *
 * @return void
 */
function ttfmake_display_favicons() {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.5.0' );
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_display_favicons', '1.5.0' );
endif;

if ( ! function_exists( 'ttfmake_body_layout_classes' ) ) :
/**
 * Add theme option body classes.
 *
 * This function is deprecated. The functionality was moved to ttfmake_body_classes().
 *
 * @since  1.0.0.
 * @deprecated 1.5.0.
 *
 * @param  array    $classes    Existing classes.
 * @return array                Modified classes.
 */
function ttfmake_body_layout_classes( $classes ) {
	Make()->compatibility()->deprecated_function(
		__FUNCTION__,
		'1.5.0',
		null,
		sprintf(
			__( 'Add/remove filters from the %1$s hook instead. See %2$s.', 'make' ),
			'<code>body_class</code>',
			'<code>MAKE_Setup_Misc</code>'
		)
	);

	return $classes;
}
else :
	Make()->compatibility()->deprecated_function(
		'ttfmake_body_layout_classes',
		'1.5.0',
		null,
		sprintf(
			__( 'Add/remove filters from the %1$s hook instead. See %2$s.', 'make' ),
			'<code>body_class</code>',
			'<code>MAKE_Setup_Misc</code>'
		)
	);
endif;

if ( ! function_exists( 'ttfmake_customizer_define_header_sections' ) ) :
/**
 * Define the sections and settings for the Header panel.
 *
 * @since  1.3.0.
 * @deprecated 1.5.0.
 *
 * @param  array    $sections    The master array of Customizer sections
 * @return array                 The augmented master array
 */
function ttfmake_customizer_define_header_sections( $sections ) {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.5.0' );
	return $sections;
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_customizer_define_header_sections', '1.5.0' );
endif;

if ( ! function_exists( 'ttfmake_customizer_define_footer_sections' ) ) :
/**
 * Define the sections and settings for the Footer panel
 *
 * @since  1.3.0.
 * @deprecated 1.5.0.
 *
 * @param  array    $sections    The master array of Customizer sections
 * @return array                 The augmented master array
 */
function ttfmake_customizer_define_footer_sections( $sections ) {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.5.0' );
	return $sections;
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_customizer_define_footer_sections', '1.5.0' );
endif;

if ( ! function_exists( 'ttfmake_css_add_rules' ) ) :
/**
 * Process user options to generate CSS needed to implement the choices.
 *
 * This function has been broken up into several files/functions in the inc/customizer/style directory.
 *
 * @since  1.0.0.
 * @deprecated 1.5.0.
 *
 * @return void
 */
function ttfmake_css_add_rules() {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.5.0' );
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_css_add_rules', '1.5.0' );
endif;