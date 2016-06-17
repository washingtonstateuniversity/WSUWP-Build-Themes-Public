<?php
/**
 * @package Make
 */

// Bail if this isn't being included inside of a MAKE_Compatibility_MethodsInterface.
if ( ! isset( $this ) || ! $this instanceof MAKE_Compatibility_MethodsInterface ) {
	return;
}

/**
 * The suffix to use for scripts.
 *
 * @deprecated 1.7.0.
 */
define( 'TTFMAKE_SUFFIX', '' );

/**
 * Load files.
 *
 * @since 1.6.1.
 * @deprecated 1.7.0.
 *
 * @return void
 */
function ttfmake_require_files() {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0' );
}

if ( has_filter( 'make_required_files' ) ) {
	Make()->compatibility()->deprecated_hook(
		'make_required_files',
		'1.7.0'
	);
}

if ( ! function_exists( 'ttfmake_setup' ) ) :
/**
 * Sets up text domain, theme support, menus, and editor styles
 *
 * @since  1.0.0.
 * @deprecated 1.7.0.
 *
 * @return void
 */
function ttfmake_setup() {
	Make()->compatibility()->deprecated_function(
		__FUNCTION__,
		'1.7.0',
		null,
		sprintf(
			esc_html__( 'Add/remove actions from the %1$s hook instead. See %2$s.', 'make' ),
			'<code>after_setup_theme</code>',
			'<code>MAKE_Setup_Misc</code>'
		)
	);
}
else :
	Make()->compatibility()->deprecated_function(
		'ttfmake_setup',
		'1.7.0',
		null,
		sprintf(
			esc_html__( 'Add/remove actions from the %1$s hook instead. See %2$s.', 'make' ),
			'<code>after_setup_theme</code>',
			'<code>MAKE_Setup_Misc</code>'
		)
	);
endif;

if ( ! function_exists( 'ttfmake_content_width' ) ) :
/**
 * Set the content width based on current layout
 *
 * @since  1.0.0.
 * @deprecated 1.7.0.
 *
 * @return void
 */
function ttfmake_content_width() {
	Make()->compatibility()->deprecated_function(
		__FUNCTION__,
		'1.7.0',
		null,
		sprintf(
			esc_html__( 'Use the %s hook instead.', 'make' ),
			'<code>make_content_width</code>'
		)
	);
}
else :
	Make()->compatibility()->deprecated_function(
		'ttfmake_content_width',
		'1.7.0',
		null,
		sprintf(
			esc_html__( 'Use the %s hook instead.', 'make' ),
			'<code>make_content_width</code>'
		)
	);
endif;

if ( ! function_exists( 'ttfmake_widgets_init' ) ) :
/**
 * Register widget areas
 *
 * @since  1.0.0.
 * @deprecated 1.7.0.
 *
 * @return void
 */
function ttfmake_widgets_init() {
	Make()->compatibility()->deprecated_function(
		__FUNCTION__,
		'1.7.0',
		null,
		esc_html__( 'Use the core sidebar registration functions instead.', 'make' )
	);
}
else :
	Make()->compatibility()->deprecated_function(
		'ttfmake_widgets_init',
		'1.7.0',
		null,
		esc_html__( 'Use the core sidebar registration functions instead.', 'make' )
	);
endif;

if ( ! function_exists( 'ttfmake_head_early' ) ) :
/**
 * Add items to the top of the wp_head section of the document head.
 *
 * @since  1.0.0.
 * @deprecated 1.7.0.
 *
 * @return void
 */
function ttfmake_head_early() {
	Make()->compatibility()->deprecated_function(
		__FUNCTION__,
		'1.7.0',
		null,
		sprintf(
			esc_html__( 'Add/remove actions from the %1$s hook instead. See %2$s.', 'make' ),
			'<code>wp_head</code>',
			'<code>MAKE_Setup_Head</code>'
		)
	);
}
else :
	Make()->compatibility()->deprecated_function(
		'ttfmake_head_early',
		'1.7.0',
		null,
		sprintf(
			esc_html__( 'Add/remove actions from the %1$s hook instead. See %2$s.', 'make' ),
			'<code>wp_head</code>',
			'<code>MAKE_Setup_Head</code>'
		)
	);
endif;

if ( ! function_exists( 'ttfmake_scripts' ) ) :
/**
 * Enqueue styles and scripts.
 *
 * @since  1.0.0.
 * @deprecated 1.7.0.
 *
 * @return void
 */
function ttfmake_scripts() {
	Make()->compatibility()->deprecated_function(
		__FUNCTION__,
		'1.7.0',
		'<code>wp_enqueue_script</code> / <code>wp_dequeue_script</code>'
	);
}
else :
	Make()->compatibility()->deprecated_function(
		'ttfmake_scripts',
		'1.7.0',
		'<code>wp_enqueue_script</code> / <code>wp_dequeue_script</code>'
	);
endif;

if ( ! function_exists( 'ttfmake_cycle2_script_setup' ) ) :
/**
 * Enqueue Cycle2 scripts
 *
 * If the environment is set up for minified scripts, load one concatenated, minified
 * Cycle 2 script. Otherwise, load each module separately.
 *
 * @since  1.0.0.
 * @deprecated 1.7.0.
 *
 * @param  array    $script_dependencies    Scripts that Cycle2 depends on.
 *
 * @return void
 */
function ttfmake_cycle2_script_setup( $script_dependencies ) {
	Make()->compatibility()->deprecated_function(
		__FUNCTION__,
		'1.7.0',
		null,
		esc_html__( 'Use the core script registration functions instead.', 'make' )
	);
}
else :
	Make()->compatibility()->deprecated_function(
		'ttfmake_cycle2_script_setup',
		'1.7.0',
		null,
		esc_html__( 'Use the core script registration functions instead.', 'make' )
	);
endif;

if ( ! function_exists( 'ttfmake_head_late' ) ) :
/**
 * Add additional items to the end of the wp_head section of the document head.
 *
 * @since  1.0.0.
 * @deprecated 1.7.0.
 *
 * @return void
 */
function ttfmake_head_late() {
	Make()->compatibility()->deprecated_function(
		__FUNCTION__,
		'1.7.0',
		null,
		sprintf(
			__( 'Add/remove actions from the %1$s hook instead. See %2$s.', 'make' ),
			'<code>wp_head</code>',
			'<code>MAKE_Setup_Head</code>'
		)
	);
}
else :
	Make()->compatibility()->deprecated_function(
		'ttfmake_head_late',
		'1.7.0',
		null,
		sprintf(
			__( 'Add/remove actions from the %1$s hook instead. See %2$s.', 'make' ),
			'<code>wp_head</code>',
			'<code>MAKE_Setup_Head</code>'
		)
	);
endif;

if ( ! function_exists( 'ttfmake_is_preview' ) ) :
/**
 * Check if the current view is rendering in the Customizer preview pane.
 *
 * @since 1.2.0.
 * @deprecated 1.7.0.
 *
 * @return bool    True if in the preview pane.
 */
function ttfmake_is_preview() {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0', 'is_customize_preview' );
	return is_customize_preview();
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_is_preview', '1.7.0', 'is_customize_preview' );
endif;

/**
 * Determine if the companion plugin is installed.
 *
 * @since  1.0.4.
 * @deprecated 1.7.0.
 *
 * @return bool    Whether or not the companion plugin is installed.
 */
function ttfmake_is_plus() {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0', 'Make()->plus()->is_plus()' );
	return Make()->plus()->is_plus();
}

/**
 * Generate a link to the Make info page.
 *
 * @since  1.0.6.
 * @deprecated 1.7.0.
 *
 * @param  string    $deprecated    This parameter is no longer used.
 * @return string                   The link.
 */
function ttfmake_get_plus_link( $deprecated = '' ) {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0', 'Make()->plus()->get_plus_link()' );
	return Make()->plus()->get_plus_link();
}

/**
 * Add styles to admin head for Make Plus
 *
 * @since 1.0.6.
 * @deprecated 1.7.0.
 *
 * @return void
 */
function ttfmake_plus_styles() {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0' );
}

if ( ! function_exists( 'ttfmake_body_classes' ) ) :
/**
 * Adds custom classes to the array of body classes.
 *
 * @since  1.0.0.
 * @deprecated 1.7.0.
 *
 * @param  array    $classes    Classes for the body element.
 * @return array                Modified class list.
 */
function ttfmake_body_classes( $classes ) {
	Make()->compatibility()->deprecated_function(
		__FUNCTION__,
		'1.7.0',
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
		'ttfmake_body_classes',
		'1.7.0',
		null,
		sprintf(
			__( 'Add/remove filters from the %1$s hook instead. See %2$s.', 'make' ),
			'<code>body_class</code>',
			'<code>MAKE_Setup_Misc</code>'
		)
	);
endif;

if ( ! function_exists( 'ttfmake_maybe_add_with_avatar_class' ) ) :
/**
 * Add a class to the bounding div if a post uses an avatar with the author byline.
 *
 * @since  1.0.11.
 * @deprecated 1.7.0.
 *
 * @param  array     $classes    An array of post classes.
 * @param  string    $class      A comma-separated list of additional classes added to the post.
 * @param  int       $post_ID    The post ID.
 * @return array                 The modified post class array.
 */
function ttfmake_maybe_add_with_avatar_class( $classes, $class, $post_ID ) {
	Make()->compatibility()->deprecated_function(
		__FUNCTION__,
		'1.7.0',
		null,
		sprintf(
			__( 'Add/remove filters from the %1$s hook instead. See %2$s.', 'make' ),
			'<code>post_class</code>',
			'<code>MAKE_Setup_Misc</code>'
		)
	);

	return $classes;
}
else :
	Make()->compatibility()->deprecated_function(
		'ttfmake_maybe_add_with_avatar_class',
		'1.7.0',
		null,
		sprintf(
			__( 'Add/remove filters from the %1$s hook instead. See %2$s.', 'make' ),
			'<code>post_class</code>',
			'<code>MAKE_Setup_Misc</code>'
		)
	);
endif;

if ( ! function_exists( 'ttfmake_excerpt_more' ) ) :
/**
 * Modify the excerpt suffix
 *
 * @since 1.0.0.
 * @deprecated 1.7.0.
 *
 * @param string $more
 *
 * @return string
 */
function ttfmake_excerpt_more( $more ) {
	Make()->compatibility()->deprecated_function(
		__FUNCTION__,
		'1.7.0',
		null,
		sprintf(
			__( 'Add/remove filters from the %1$s hook instead. See %2$s.', 'make' ),
			'<code>excerpt_more</code>',
			'<code>MAKE_Setup_Misc</code>'
		)
	);

	return $more;
}
else :
	Make()->compatibility()->deprecated_function(
		'ttfmake_excerpt_more',
		'1.7.0',
		null,
		sprintf(
			__( 'Add/remove filters from the %1$s hook instead. See %2$s.', 'make' ),
			'<code>excerpt_more</code>',
			'<code>MAKE_Setup_Misc</code>'
		)
	);
endif;

if ( ! function_exists( 'ttfmake_page_menu_args' ) ) :
/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @since  1.0.0.
 * @deprecated 1.7.0
 *
 * @param  array    $args    Configuration arguments.
 * @return array             Modified page menu args.
 */
function ttfmake_page_menu_args( $args ) {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0' );
	return $args;
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_page_menu_args', '1.7.0' );
endif;

if ( ! function_exists( 'ttfmake_wp_title' ) ) :
/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 *
 * @since  1.0.0.
 * @deprecated 1.7.0.
 *
 * @param  string    $title    Default title text for current view.
 * @param  string    $sep      Optional separator.
 *
 * @return string              The filtered title.
 */
function ttfmake_wp_title( $title, $sep ) {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0' );
	return $title;
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_wp_title', '1.7.0' );
endif;

/**
 * Add a wrapper div to the output of oembeds and the [embed] shortcode.
 *
 * Also enqueues FitVids, since the embed might be a video.
 *
 * @since 1.6.0.
 * @deprecated 1.7.0.
 *
 * @param  string    $html    The generated HTML of the embed handler.
 * @param  string    $url     The embed URL.
 * @param  array     $attr    The attributes of the embed shortcode.
 *
 * @return string             The wrapped HTML.
 */
function ttfmake_embed_container( $html, $url, $attr ) {
	Make()->compatibility()->deprecated_function(
		__FUNCTION__,
		'1.7.0',
		null,
		sprintf(
			__( 'This function has been moved to %s.', 'make' ),
			'<code>MAKE_Setup_Misc</code>'
		)
	);

	return $html;
}

/**
 * Sanitize a string to ensure that it is a float number.
 *
 * @since 1.5.0.
 * @deprecated 1.7.0.
 *
 * @param  string|float    $value    The value to sanitize.
 * @return float                     The sanitized value.
 */
function ttfmake_sanitize_float( $value ) {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0', 'Make()->sanitize()->sanitize_float()' );
	return Make()->sanitize()->sanitize_float( $value );
}

if ( ! function_exists( 'ttfmake_sanitize_text' ) ) :
/**
 * Allow only certain tags and attributes in a string.
 *
 * @since  1.0.0.
 * @deprecated 1.7.0.
 *
 * @param  string    $string    The unsanitized string.
 * @return string               The sanitized string.
 */
function ttfmake_sanitize_text( $string ) {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0', 'Make()->sanitize()->sanitize_text()' );
	return Make()->sanitize()->sanitize_text( $string );
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_sanitize_text', '1.7.0', 'Make()->sanitize()->sanitize_text()' );
endif;

if ( ! function_exists( 'ttfmake_get_view' ) ) :
/**
 * Determine the current view.
 *
 * For use with view-related theme options.
 *
 * @since  1.0.0.
 * @deprecated 1.7.0.
 *
 * @return string    The string representing the current view.
 */
function ttfmake_get_view() {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0', 'make_get_current_view' );
	return make_get_current_view();
}
else :
	Make()->compatibility()->deprecated_function(
		'ttfmake_get_view',
		'1.7.0',
		null,
		sprintf(
			wp_kses(
				__( 'To add or modify theme views, use the %1$s function instead. See the <a href="%2$s" target="_blank">View API documentation</a>.', 'make' ),
				array( 'a' => array( 'href' => true, 'target' => true ) )
			),
			'<code>make_update_view_definition()</code>',
			'https://thethemefoundry.com/docs/make-docs/code/apis/view-api/'
		)
	);
endif;

if ( ! function_exists( 'ttfmake_has_sidebar' ) ) :
/**
 * Determine if the current view should show a sidebar in the given location.
 *
 * @since  1.0.0.
 * @deprecated 1.7.0.
 *
 * @param  string    $location    The location to test for.
 * @return bool                   Whether or not the location has a sidebar.
 */
function ttfmake_has_sidebar( $location ) {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0', 'make_has_sidebar' );
	return make_has_sidebar( $location );
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_has_sidebar', '1.7.0', 'make_has_sidebar' );
endif;

if ( ! function_exists( 'ttfmake_sidebar_description' ) ) :
/**
 * Output a sidebar description that reflects its current status.
 *
 * @since  1.0.0.
 * @deprecated 1.7.0.
 *
 * @param  string    $sidebar_id    The sidebar to look up the description for.
 * @return string                   The description.
 */
function ttfmake_sidebar_description( $sidebar_id ) {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0' );
	return '';
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_sidebar_description', '1.7.0' );
endif;

if ( ! function_exists( 'ttfmake_sidebar_list_enabled' ) ) :
/**
 * Compile a list of views where a particular sidebar is enabled.
 *
 * @since  1.0.0.
 * @deprecated 1.7.0.
 *
 * @param  string    $location    The sidebar to look up.
 * @return array                  The sidebar's current locations.
 */
function ttfmake_sidebar_list_enabled( $location ) {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0' );
	return array();
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_sidebar_list_enabled', '1.7.0' );
endif;

if ( ! function_exists( 'ttfmake_get_social_links' ) ) :
/**
 * Get the social links from options.
 *
 * @since  1.0.0.
 * @deprecated 1.7.0.
 *
 * @return array    Keys are service names and the values are links.
 */
function ttfmake_get_social_links() {
	Make()->compatibility()->deprecated_function(
		__FUNCTION__,
		'1.7.0',
		null,
		sprintf(
			wp_kses(
				__( 'See the <a href="%s" target="_blank">Social Icons API documentation</a>.', 'make' ),
				array( 'a' => array( 'href' => true, 'target' => true ) )
			),
			'https://thethemefoundry.com/docs/make-docs/code/apis/social-icons-api/'
		)
	);
}
else :
	Make()->compatibility()->deprecated_function(
		'ttfmake_get_social_links',
		'1.7.0',
		null,
		sprintf(
			wp_kses(
				__( 'To add or modify social icons, use the %1$s function instead. See the <a href="%2$s" target="_blank">Social Icons API documentation</a>.', 'make' ),
				array( 'a' => array( 'href' => true, 'target' => true ) )
			),
			'<code>make_update_socialicon_definition()</code>',
			'https://thethemefoundry.com/docs/make-docs/code/apis/social-icons-api/'
		)
	);
endif;

if ( ! function_exists( 'ttfmake_pre_wp_nav_menu_social' ) ) :
/**
 * Alternative output for wp_nav_menu for the 'social' menu location.
 *
 * @since  1.0.0.
 * @deprecated 1.7.0.
 *
 * @param  string    $output    Output for the menu.
 * @param  object    $args      wp_nav_menu arguments.
 * @return string               Modified menu.
 */
function ttfmake_pre_wp_nav_menu_social( $output, $args ) {
	Make()->compatibility()->deprecated_function(
		__FUNCTION__,
		'1.7.0',
		null,
		sprintf(
			wp_kses(
				__( 'See the <a href="%s" target="_blank">Social Icons API documentation</a>.', 'make' ),
				array( 'a' => array( 'href' => true, 'target' => true ) )
			),
			'https://thethemefoundry.com/docs/make-docs/code/apis/social-icons-api/'
		)
	);

	return $output;
}
else :
	Make()->compatibility()->deprecated_function(
		'ttfmake_pre_wp_nav_menu_social',
		'1.7.0',
		null,
		sprintf(
			wp_kses(
				__( 'To add or modify social icons, use the %1$s function instead. See the <a href="%2$s" target="_blank">Social Icons API documentation</a>.', 'make' ),
				array( 'a' => array( 'href' => true, 'target' => true ) )
			),
			'<code>make_update_socialicon_definition()</code>',
			'https://thethemefoundry.com/docs/make-docs/code/apis/social-icons-api/'
		)
	);
endif;

/**
 * Handle frontend scripts for use with the existing sections on the current Builder page.
 *
 * @since 1.6.1.
 * @deprecated 1.7.0.
 *
 * @return void
 */
function ttfmake_frontend_builder_scripts() {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0' );
}

if ( ! function_exists( 'ttfmake_builder_css' ) ) :
/**
 * Trigger an action hook for each section on a Builder page for the purpose
 * of adding section-specific CSS rules to the document head.
 *
 * @since 1.4.5.
 * @deprecated 1.7.0.
 *
 * @return void
 */
function ttfmake_builder_css() {
	Make()->compatibility()->deprecated_function(
		__FUNCTION__,
		'1.7.0',
		null,
		sprintf(
			__( 'This function has been moved to %s.', 'make' ),
			'<code>MAKE_Builder_Setup</code>'
		)
	);
}
else :
	Make()->compatibility()->deprecated_function(
		'ttfmake_builder_css',
		'1.7.0',
		null,
		sprintf(
			__( 'This function has been moved to %s.', 'make' ),
			'<code>MAKE_Builder_Setup</code>'
		)
	);
endif;

if ( ! function_exists( 'ttfmake_builder_banner_css' ) ) :
/**
 * Add frontend CSS rules for Banner sections based on certain section options.
 *
 * @since 1.4.5.
 * @deprecated 1.7.0.
 *
 * @param array    $data    The banner's section data.
 * @param int      $id      The banner's section ID.
 *
 * @return void
 */
function ttfmake_builder_banner_css( $data, $id ) {
	Make()->compatibility()->deprecated_function(
		__FUNCTION__,
		'1.7.0',
		null,
		sprintf(
			__( 'This function has been moved to %s.', 'make' ),
			'<code>MAKE_Builder_Setup</code>'
		)
	);
}
else :
	Make()->compatibility()->deprecated_function(
		'ttfmake_builder_banner_css',
		'1.7.0',
		null,
		sprintf(
			__( 'This function has been moved to %s.', 'make' ),
			'<code>MAKE_Builder_Setup</code>'
		)
	);
endif;

if ( ! function_exists( 'ttfmake_category_transient_flusher' ) ) :
/**
 * Flush out the transients used in ttfmake_categorized_blog.
 *
 * @since  1.0.0.
 * @deprecated 1.7.0.
 *
 * @return void
 */
function ttfmake_category_transient_flusher() {
	Make()->compatibility()->deprecated_function(
		__FUNCTION__,
		'1.7.0',
		null,
		sprintf(
			__( 'This function has been moved to %s.', 'make' ),
			'<code>MAKE_Setup_Misc</code>'
		)
	);
}
else :
	Make()->compatibility()->deprecated_function(
		'ttfmake_category_transient_flusher',
		'1.7.0',
		null,
		sprintf(
			__( 'This function has been moved to %s.', 'make' ),
			'<code>MAKE_Setup_Misc</code>'
		)
	);
endif;

if ( ! function_exists( 'ttfmake_maybe_show_social_links' ) ) :
/**
 * Show the social links markup if the theme options and/or menus are configured for it.
 *
 * @since 1.0.0.
 * @deprecated 1.7.0.
 *
 * @param  string    $region    The site region (header or footer).
 * @return void
 */
function ttfmake_maybe_show_social_links( $region ) {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0', 'make_socialicons' );
	make_socialicons( $region );
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_maybe_show_social_links', '1.7.0', 'make_socialicons' );
endif;

/**
 * Add the Yoast SEO breadcrumb, if the plugin is activated.
 *
 * @since 1.6.4.
 * @deprecated 1.7.0.
 *
 * @return void
 */
function ttfmake_yoast_seo_breadcrumb() {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0', 'make_breadcrumb' );
	make_breadcrumb();
}

/**
 * Add notice if Make Plus is installed as a theme.
 *
 * @since  1.1.2.
 * @deprecated 1.7.0.
 *
 * @param  string         $source           File source location.
 * @param  string         $remote_source    Remove file source location.
 * @param  WP_Upgrader    $upgrader         WP_Upgrader instance.
 * @return WP_Error                         Error or source on success.
 */
function ttfmake_check_package( $source, $remote_source, $upgrader ) {
	Make()->compatibility()->deprecated_function(
		__FUNCTION__,
		'1.7.0',
		null,
		sprintf(
			__( 'This function has been moved to %s.', 'make' ),
			'<code>MAKE_Compatibility_Methods</code>'
		)
	);

	return $source;
}

if ( ! function_exists( 'ttfmake_filter_backcompat' ) ) :
/**
 * Adds back compat for filters with changed names.
 *
 * In Make 1.2.3, filters were all changed from "ttfmake_" to "make_". In order to maintain back compatibility, the old
 * version of the filter needs to still be called. This function collects all of those changed filters and mirrors the
 * new filter so that the old filter name will still work.
 *
 * @since  1.2.3.
 * @deprecated 1.7.0.
 *
 * @return void
 */
function ttfmake_filter_backcompat() {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0' );
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_filter_backcompat', '1.7.0' );
endif;

if ( ! function_exists( 'ttfmake_backcompat_filter' ) ) :
/**
 * Prepends "ttf" to a filter name and calls that new filter variant.
 *
 * @since  1.2.3.
 * @deprecated 1.7.0.
 *
 * @return mixed    The result of the filter.
 */
function ttfmake_backcompat_filter() {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0' );
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_backcompat_filter', '1.7.0' );
endif;

if ( ! function_exists( 'ttfmake_action_backcompat' ) ) :
/**
 * Adds back compat for actions with changed names.
 *
 * In Make 1.2.3, actions were all changed from "ttfmake_" to "make_". In order to maintain back compatibility, the old
 * version of the action needs to still be called. This function collects all of those changed actions and mirrors the
 * new filter so that the old filter name will still work.
 *
 * @since  1.2.3.
 * @deprecated 1.7.0.
 *
 * @return void
 */
function ttfmake_action_backcompat() {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0' );
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_action_backcompat', '1.7.0' );
endif;

if ( ! function_exists( 'ttfmake_backcompat_action' ) ) :
/**
 * Prepends "ttf" to a filter name and calls that new filter variant.
 *
 * @since  1.2.3.
 * @deprecated 1.7.0.
 *
 * @return mixed    The result of the filter.
 */
function ttfmake_backcompat_action() {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0' );
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_backcompat_action', '1.7.0' );
endif;

if ( ! function_exists( 'ttfmake_customizer_get_key_conversions' ) ) :
/**
 * Return an array of option key migration sets.
 *
 * @since  1.3.0.
 * @deprecated 1.7.0.
 *
 * @return array    The list of key migration sets.
 */
function ttfmake_customizer_get_key_conversions() {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0' );
	return array();
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_customizer_get_key_conversions', '1.7.0' );
endif;

if ( ! function_exists( 'ttfmake_customizer_set_up_theme_mod_conversions' ) ) :
/**
 * Convert old theme mod values to their newer equivalents.
 *
 * @since  1.3.0.
 * @deprecated 1.7.0.
 *
 * @return void
 */
function ttfmake_customizer_set_up_theme_mod_conversions() {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0' );
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_customizer_set_up_theme_mod_conversions', '1.7.0' );
endif;

if ( ! function_exists( 'ttfmake_customizer_convert_theme_mods_filter' ) ) :
/**
 * Convert a new theme mod value from an old one.
 *
 * @since  1.3.0.
 * @deprecated 1.7.0.
 *
 * @param  mixed    $value    The current value.
 * @return mixed              The modified value.
 */
function ttfmake_customizer_convert_theme_mods_filter( $value ) {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0' );
	return $value;
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_customizer_convert_theme_mods_filter', '1.7.0' );
endif;

if ( ! function_exists( 'ttfmake_customizer_convert_theme_mods_values' ) ) :
/**
 * This function converts values from old mods to values for new mods.
 *
 * @since  1.3.0.
 * @deprecated 1.7.0.
 *
 * @param  string    $old_key    The old mod key.
 * @param  string    $new_key    The new mod key.
 * @param  mixed     $value      The value of the mod.
 * @return mixed                 The convert mod value.
 */
function ttfmake_customizer_convert_theme_mods_values( $old_key, $new_key, $value ) {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0' );
	return $value;
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_customizer_convert_theme_mods_values', '1.7.0' );
endif;

/**
 * Instantiate or return the one TTFMAKE_Admin_Notice instance.
 *
 * @since  1.4.9.
 * @deprecated 1.7.0.
 *
 * @return TTFMAKE_Admin_Notice
 */
function ttfmake_admin_notice() {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0', 'Make()->notice()' );
	return Make()->notice();
}

/**
 * Wrapper function to register an admin notice.
 *
 * @since 1.4.9.
 * @deprecated 1.7.0.
 *
 * @param string    $id         A unique ID string for the admin notice.
 * @param string    $message    The content of the admin notice.
 * @param array     $args       Array of configuration parameters for the admin notice.
 * @return void
 */
function ttfmake_register_admin_notice( $id, $message, $args ) {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0', 'Make()->notice()->register_admin_notice' );
	Make()->notice()->register_admin_notice( $id, $message, $args );
}

/**
 * Upgrade notices related to Make.
 *
 * @since 1.4.9.
 * @deprecated 1.7.0.
 *
 * @return void
 */
function ttfmake_upgrade_notices() {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0' );
}

/**
 * Upgrade notices related to Make Plus.
 *
 * @since 1.4.9.
 * @deprecated 1.7.0.
 *
 * @return void
 */
function ttfmake_plus_upgrade_notices() {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0' );
}

/**
 * Wrapper function to instantiate the L10n class and call the method to load text domains.
 *
 * @since 1.6.2.
 * @deprecated 1.7.0.
 *
 * @return bool
 */
function ttfmake_load_textdomains() {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0', 'Make()->l10n()->load_textdomains' );
	return Make()->l10n()->load_textdomains();
}

if ( ! function_exists( 'ttfmake_option_defaults' ) ) :
/**
 * The big array of global option defaults.
 *
 * @since  1.0.0
 * @deprecated 1.7.0.
 *
 * @return array    The default values for all theme options.
 */
function ttfmake_option_defaults() {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0', 'Make()->thememod()->get_settings( \'default\' )' );
	return Make()->thememod()->get_settings( 'default' );
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_option_defaults', '1.7.0', 'Make()->thememod()->get_settings( \'default\' )' );
endif;

if ( ! function_exists( 'ttfmake_get_default' ) ) :
/**
 * Return a particular global option default.
 *
 * @since  1.0.0.
 * @deprecated 1.7.0.
 *
 * @param  string    $option    The key of the option to return.
 * @return mixed                Default value if found; false if not found.
 */
function ttfmake_get_default( $option ) {
	Make()->compatibility()->deprecated_function(
		__FUNCTION__,
		'1.7.0',
		'make_get_thememod_default'
	);

	return make_get_thememod_default( $option );
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_get_default', '1.7.0', 'make_get_thememod_default' );
endif;

if ( ! function_exists( 'ttfmake_get_choices' ) ) :
/**
 * Return the available choices for a given setting
 *
 * @since  1.0.0.
 * @deprecated 1.7.0.
 *
 * @param  string|object    $setting    The setting to get options for.
 * @return array                        The options for the setting.
 */
function ttfmake_get_choices( $setting ) {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0', 'Make()->thememod()->get_choice_set()' );
	return Make()->thememod()->get_choice_set( $setting );
}
else :
	Make()->compatibility()->deprecated_function(
		'ttfmake_get_choices',
		'1.7.0',
		null,
		sprintf(
			wp_kses(
				__( 'To add or modify setting choices, use the %1$s function instead. See the <a href="%2$s" target="_blank">Choices API documentation</a>.', 'make' ),
				array( 'a' => array( 'href' => true, 'target' => true ) )
			),
			'<code>make_update_choice_set()</code>',
			'https://thethemefoundry.com/docs/make-docs/code/apis/choices-api/'
		)
	);
endif;

if ( ! function_exists( 'ttfmake_sanitize_choice' ) ) :
/**
 * Sanitize a value from a list of allowed values.
 *
 * @since 1.0.0.
 * @deprecated 1.7.0.
 *
 * @param  mixed    $value      The value to sanitize.
 * @param  mixed    $setting    The setting for which the sanitizing is occurring.
 * @return mixed                The sanitized value.
 */
function ttfmake_sanitize_choice( $value, $setting ) {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0', 'Make()->sanitize()->sanitize_choice' );
	return Make()->sanitize()->sanitize_choice( $value, $setting );
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_sanitize_choice', '1.7.0', 'Make()->sanitize()->sanitize_choice' );
endif;

if ( ! function_exists( 'ttfmake_edit_page_script' ) ) :
/**
 * Enqueue scripts that run on the Edit Page screen
 *
 * @since  1.0.0.
 * @deprecated 1.7.0.
 *
 * @return void
 */
function ttfmake_edit_page_script() {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0' );
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_edit_page_script', '1.7.0' );
endif;

if ( ! function_exists( 'ttfmake_jetpack_setup' ) ) :
/**
 * Jetpack compatibility.
 *
 * @since  1.0.0.
 * @deprecated 1.7.0.
 *
 * @return void
 */
function ttfmake_jetpack_setup() {
	Make()->compatibility()->deprecated_function(
		__FUNCTION__,
		'1.7.0',
		null,
		sprintf(
			__( 'This function has been moved to %s.', 'make' ),
			'<code>MAKE_Integration_Jetpack</code>'
		)
	);
}
else :
	Make()->compatibility()->deprecated_function(
		'ttfmake_jetpack_setup',
		'1.7.0',
		null,
		sprintf(
			__( 'This function has been moved to %s.', 'make' ),
			'<code>MAKE_Integration_Jetpack</code>'
		)
	);
endif;

if ( ! function_exists( 'ttfmake_jetpack_infinite_scroll_footer_callback' ) ) :
/**
 * Callback to render the special footer added by Infinite Scroll.
 *
 * @since  1.0.0.
 * @deprecated 1.7.0.
 *
 * @return void
 */
function ttfmake_jetpack_infinite_scroll_footer_callback() {
	Make()->compatibility()->deprecated_function(
		__FUNCTION__,
		'1.7.0',
		null,
		sprintf(
			__( 'This function has been moved to %s.', 'make' ),
			'<code>MAKE_Integration_Jetpack</code>'
		)
	);

	Make()->integration()->get_integration( 'jetpack' )->infinite_scroll_footer_callback();
}
else :
	Make()->compatibility()->deprecated_function(
		'ttfmake_jetpack_infinite_scroll_footer_callback',
		'1.7.0',
		null,
		sprintf(
			__( 'This function has been moved to %s.', 'make' ),
			'<code>MAKE_Integration_Jetpack</code>'
		)
	);
endif;

if ( ! function_exists( 'ttfmake_jetpack_infinite_scroll_has_footer_widgets' ) ) :
/**
 * Determine whether any footer widgets are actually showing.
 *
 * @since  1.0.0.
 * @deprecated 1.7.0.
 *
 * @return bool    Whether or not infinite scroll has footer widgets.
 */
function ttfmake_jetpack_infinite_scroll_has_footer_widgets() {
	Make()->compatibility()->deprecated_function(
		__FUNCTION__,
		'1.7.0',
		null,
		sprintf(
			__( 'This function has been moved to %s.', 'make' ),
			'<code>MAKE_Integration_Jetpack</code>'
		)
	);

	return false;
}
else :
	Make()->compatibility()->deprecated_function(
		'ttfmake_jetpack_infinite_scroll_has_footer_widgets',
		'1.7.0',
		null,
		sprintf(
			__( 'This function has been moved to %s.', 'make' ),
			'<code>MAKE_Integration_Jetpack</code>'
		)
	);
endif;

if ( ! function_exists( 'ttfmake_jetpack_infinite_scroll_render' ) ) :
/**
 * Render the additional posts added by Infinite Scroll
 *
 * @since  1.0.0.
 * @deprecated 1.7.0.
 *
 * @return void
 */
function ttfmake_jetpack_infinite_scroll_render() {
	Make()->compatibility()->deprecated_function(
		__FUNCTION__,
		'1.7.0',
		null,
		sprintf(
			__( 'This function has been moved to %s.', 'make' ),
			'<code>MAKE_Integration_Jetpack</code>'
		)
	);

	Make()->integration()->get_integration( 'jetpack' )->infinite_scroll_render();
}
else :
	Make()->compatibility()->deprecated_function(
		'ttfmake_jetpack_infinite_scroll_render',
		'1.7.0',
		null,
		sprintf(
			__( 'This function has been moved to %s.', 'make' ),
			'<code>MAKE_Integration_Jetpack</code>'
		)
	);
endif;

if ( ! function_exists( 'ttfmake_jetpack_remove_sharing' ) ) :
/**
 * Remove the Jetpack Sharing output from the end of the post content so it can be output elsewhere.
 *
 * @since  1.0.0.
 * @deprecated 1.7.0.
 *
 * @return void
 */
function ttfmake_jetpack_remove_sharing() {
	Make()->compatibility()->deprecated_function(
		__FUNCTION__,
		'1.7.0',
		null,
		sprintf(
			__( 'This function has been moved to %s.', 'make' ),
			'<code>MAKE_Integration_Jetpack</code>'
		)
	);
}
else :
	Make()->compatibility()->deprecated_function(
		'ttfmake_jetpack_remove_sharing',
		'1.7.0',
		null,
		sprintf(
			__( 'This function has been moved to %s.', 'make' ),
			'<code>MAKE_Integration_Jetpack</code>'
		)
	);
endif;

if ( ! function_exists( 'ttfmake_woocommerce_init' ) ) :
/**
 * Add theme support and remove default action hooks so we can replace them with our own.
 *
 * @since  1.0.0.
 * @deprecated 1.7.0.
 *
 * @return void
 */
function ttfmake_woocommerce_init() {
	Make()->compatibility()->deprecated_function(
		__FUNCTION__,
		'1.7.0',
		null,
		sprintf(
			__( 'This function has been moved to %s.', 'make' ),
			'<code>MAKE_Integration_WooCommerce</code>'
		)
	);
}
else :
	Make()->compatibility()->deprecated_function(
		'ttfmake_woocommerce_init',
		'1.7.0',
		null,
		sprintf(
			__( 'This function has been moved to %s.', 'make' ),
			'<code>MAKE_Integration_WooCommerce</code>'
		)
	);
endif;

if ( ! function_exists( 'ttfmake_woocommerce_before_main_content' ) ) :
/**
 * Markup to show before the main WooCommerce content.
 *
 * @since  1.0.0.
 * @deprecated 1.7.0.
 *
 * @return void
 */
function ttfmake_woocommerce_before_main_content() {
	Make()->compatibility()->deprecated_function(
		__FUNCTION__,
		'1.7.0',
		null,
		sprintf(
			__( 'This function has been moved to %s.', 'make' ),
			'<code>MAKE_Integration_WooCommerce</code>'
		)
	);
}
else :
	Make()->compatibility()->deprecated_function(
		'ttfmake_woocommerce_before_main_content',
		'1.7.0',
		null,
		sprintf(
			__( 'This function has been moved to %s.', 'make' ),
			'<code>MAKE_Integration_WooCommerce</code>'
		)
	);
endif;

if ( ! function_exists( 'ttfmake_woocommerce_after_main_content' ) ) :
/**
 * Markup to show after the main WooCommerce content
 *
 * @since  1.0.0.
 * @deprecated 1.7.0.
 *
 * @return void
 */
function ttfmake_woocommerce_after_main_content() {
	Make()->compatibility()->deprecated_function(
		__FUNCTION__,
		'1.7.0',
		null,
		sprintf(
			__( 'This function has been moved to %s.', 'make' ),
			'<code>MAKE_Integration_WooCommerce</code>'
		)
	);
}
else :
	Make()->compatibility()->deprecated_function(
		'ttfmake_woocommerce_after_main_content',
		'1.7.0',
		null,
		sprintf(
			__( 'This function has been moved to %s.', 'make' ),
			'<code>MAKE_Integration_WooCommerce</code>'
		)
	);
endif;

if ( ! function_exists( 'ttfmake_get_css' ) ) :
/**
 * Return the one TTFMAKE_CSS object.
 *
 * @since  1.0.0.
 * @deprecated 1.7.0.
 *
 * @return TTFMAKE_CSS    The one TTFMAKE_CSS object.
 */
function ttfmake_get_css() {
	Make()->compatibility()->deprecated_function(
		__FUNCTION__,
		'1.7.0',
		null,
		sprintf(
			wp_kses(
				__( 'To add a style rule, use the %1$s function. See the <a href="%2$s" target="_blank">Style API documentation</a>.', 'make' ),
				array( 'a' => array( 'href' => true, 'target' => true ) )
			),
			'<code>make_add_style_rule()</code>',
			'https://thethemefoundry.com/docs/make-docs/code/apis/style-api/'
		)
	);

	return Make()->style()->css();
}
else :
	Make()->compatibility()->deprecated_function(
		'ttfmake_get_css',
		'1.7.0',
		null,
		sprintf(
			wp_kses(
				__( 'To add a style rule, use the %1$s function. See the <a href="%2$s" target="_blank">Style API documentation</a>.', 'make' ),
				array( 'a' => array( 'href' => true, 'target' => true ) )
			),
			'<code>make_add_style_rule()</code>',
			'https://thethemefoundry.com/docs/make-docs/code/apis/style-api/'
		)
	);
endif;

/**
 * Build the CSS rules for the color scheme.
 *
 * @since 1.5.0.
 * @deprecated 1.7.0.
 *
 * @return void
 */
function ttfmake_css_background() {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0' );
}

/**
 * Build the CSS rules for the color scheme.
 *
 * @since 1.5.0.
 * @deprecated 1.7.0.
 *
 * @return void
 */
function ttfmake_css_color() {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0' );
}

if ( ! function_exists( 'ttfmake_css_layout' ) ) :
/**
 * Build the CSS rules for the custom layout options.
 *
 * @since  1.5.0.
 * @deprecated 1.7.0.
 *
 * @return void
 */
function ttfmake_css_layout() {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0' );
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_css_layout', '1.7.0' );
endif;

if ( ! function_exists( 'ttfmake_css_fonts' ) ) :
/**
 * Build the CSS rules for the custom fonts
 *
 * @since  1.0.0
 * @deprecated 1.7.0.
 *
 * @return void
 */
function ttfmake_css_fonts() {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0' );
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_css_fonts', '1.7.0' );
endif;

if ( ! function_exists( 'ttfmake_get_font_stack' ) ) :
/**
 * Validate the font choice and get a font stack for it.
 *
 * @since  1.0.0.
 * @deprecated 1.7.0.
 *
 * @param  string    $font    The 1st font in the stack.
 * @return string             The full font stack.
 */
function ttfmake_get_font_stack( $font ) {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0', 'Make()->font()->get_font_stack' );
	return Make()->font()->get_font_stack( $font );
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_get_font_stack', '1.7.0', 'Make()->font()->get_font_stack' );
endif;

if ( ! function_exists( 'ttfmake_font_get_relative_sizes' ) ) :
/**
 * Return an array of percentages to use when calculating certain font sizes.
 *
 * @since  1.3.0.
 * @deprecated 1.7.0.
 *
 * @return array    The percentage value relative to another specific size
 */
function ttfmake_font_get_relative_sizes() {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0', 'Make()->style()->helper()->get_relative_size()' );
	return Make()->style()->helper()->get_relative_size();
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_font_get_relative_sizes', '1.7.0', 'Make()->style()->helper()->get_relative_size()' );
endif;

if ( ! function_exists( 'ttfmake_parse_font_properties' ) ) :
/**
 * Cycle through the font options for the given element and collect an array
 * of option values that are non-default.
 *
 * @since  1.3.0.
 * @deprecated 1.7.0.
 *
 * @param  string    $element    The element to parse the options for.
 * @param  bool      $force      True to include properties that have default values.
 * @return array                 An array of non-default CSS declarations.
 */
function ttfmake_parse_font_properties( $element, $force = false ) {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0', 'Make()->style()->helper()->parse_font_properties()' );
	return Make()->style()->helper()->parse_font_properties( $element, $force );
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_parse_font_properties', '1.7.0', 'Make()->style()->helper()->parse_font_properties()' );
endif;

/**
 * Generate a CSS rule definition array for an element's link underline property.
 *
 * @since 1.5.0.
 * @deprecated 1.7.0.
 *
 * @param  string    $element      The element to look up in the theme options.
 * @param  array     $selectors    The base selectors to use for the rule.
 * @return array                   A CSS rule definition array.
 */
function ttfmake_parse_link_underline( $element, $selectors ) {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0', 'Make()->style()->helper()->parse_link_underline()' );
	return Make()->style()->helper()->parse_link_underline( $element, $selectors );
}

if ( ! function_exists( 'ttfmake_get_relative_font_size' ) ) :
/**
 * Convert a font size to a relative size based on a starting value and percentage.
 *
 * @since  1.0.0.
 * @deprecated 1.7.0.
 *
 * @param  mixed    $value         The value to base the final value on.
 * @param  mixed    $percentage    The percentage of change.
 * @return float                   The converted value.
 */
function ttfmake_get_relative_font_size( $value, $percentage ) {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0', 'Make()->style()->helper()->get_relative_font_size()' );
	return Make()->style()->helper()->get_relative_font_size( $value, $percentage );
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_get_relative_font_size', '1.7.0', 'Make()->style()->helper()->get_relative_font_size()' );
endif;

if ( ! function_exists( 'ttfmake_convert_px_to_rem' ) ) :
/**
 * Given a px value, return a rem value.
 *
 * @since  1.0.0.
 * @deprecated 1.7.0.
 *
 * @param  mixed    $px      The value to convert.
 * @param  mixed    $base    The font-size base for the rem conversion (deprecated).
 * @return float             The converted value.
 */
function ttfmake_convert_px_to_rem( $px, $base = 0 ) {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0', 'Make()->style()->helper()->convert_px_to_rem()' );
	return Make()->style()->helper()->convert_px_to_rem( $px );
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_convert_px_to_rem', '1.7.0', 'Make()->style()->helper()->convert_px_to_rem()' );
endif;

/**
 * Convert a hex string into a comma separated RGB string.
 *
 * @link http://bavotasan.com/2011/convert-hex-color-to-rgb-using-php/
 *
 * @since 1.5.0.
 * @deprecated 1.7.0.
 *
 * @param  $value
 * @return bool|string
 */
function ttfmake_hex_to_rgb( $value ) {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0', 'Make()->style()->helper()->hex_to_rgb()' );
	return Make()->style()->helper()->hex_to_rgb( $value );
}

if ( ! function_exists( 'ttfmake_customizer_init' ) ) :
/**
 * Load the customizer files and enqueue scripts
 *
 * @since  1.0.0.
 * @deprecated 1.7.0.
 *
 * @return void
 */
function ttfmake_customizer_init() {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0' );
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_customizer_init', '1.7.0' );
endif;

/**
 * Register autoloaders for Customizer-related classes.
 *
 * This function is hooked to customize_register so that it is only registered within the Customizer.
 *
 * @since 1.6.3.
 * @deprecated 1.7.0.
 *
 * @return void
 */
function ttfmake_customizer_register_autoload() {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0' );
}

/**
 * Autoloader callback for loading Make's custom Customizer control classes.
 *
 * @since 1.6.3.
 * @deprecated 1.7.0.
 *
 * @param string    $class    The name of the class that is attempting to load.
 *
 * @return void
 */
function ttfmake_customizer_control_autoload( $class ) {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0' );
}

if ( ! function_exists( 'ttfmake_customizer_get_panels' ) ) :
/**
 * Return an array of panel definitions.
 *
 * @since  1.3.0.
 * @deprecated 1.7.0.
 *
 * @return array    The array of panel definitions.
 */
function ttfmake_customizer_get_panels() {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0', 'Make()->customizer_controls()->get_panel_definitions()' );

	if ( Make()->has_module( 'customizer_controls' ) ) {
		return Make()->customizer_controls()->get_panel_definitions();
	}

	return array();
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_customizer_get_panels', '1.7.0', 'Make()->customizer_controls()->get_panel_definitions()' );
endif;

if ( ! function_exists( 'ttfmake_customizer_add_panels' ) ) :
/**
 * Register Customizer panels
 *
 * @since  1.3.0.
 * @deprecated 1.7.0.
 *
 * @param  WP_Customize_Manager    $wp_customize    Customizer object.
 * @return void
 */
function ttfmake_customizer_add_panels( $wp_customize ) {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0', '$wp_customize->add_panel()' );
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_customizer_add_panels', '1.7.0', '$wp_customize->add_panel()' );
endif;

if ( ! function_exists( 'ttfmake_customizer_get_sections' ) ) :
/**
 * Return the master array of Customizer sections
 *
 * @since  1.3.0.
 * @deprecated 1.7.0.
 *
 * @return array    The master array of Customizer sections
 */
function ttfmake_customizer_get_sections() {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0', 'Make()->customizer_controls()->get_section_definitions()' );

	if ( Make()->has_module( 'customizer_controls' ) ) {
		return Make()->customizer_controls()->get_section_definitions();
	}

	return array();
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_customizer_get_sections', '1.7.0', 'Make()->customizer_controls()->get_section_definitions()' );
endif;

if ( ! function_exists( 'ttfmake_customizer_add_sections' ) ) :
/**
 * Add sections and controls to the customizer.
 *
 * Hooked to 'customize_register' via ttfmake_customizer_init().
 *
 * @since  1.0.0.
 * @deprecated 1.7.0.
 *
 * @param  WP_Customize_Manager    $wp_customize    Theme Customizer object.
 * @return void
 */
function ttfmake_customizer_add_sections( $wp_customize ) {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0', '$wp_customize->add_section()' );
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_customizer_add_sections', '1.7.0', '$wp_customize->add_section()' );
endif;

if ( ! function_exists( 'ttfmake_customizer_add_section_options' ) ) :
/**
 * Register settings and controls for a section.
 *
 * @since  1.3.0.
 * @deprecated 1.7.0.
 *
 * @param  string    $section             Section ID
 * @param  array     $args                Array of setting and control definitions
 * @param  int       $initial_priority    The initial priority to use for controls
 * @return int                            The last priority value assigned
 */
function ttfmake_customizer_add_section_options( $section, $args, $initial_priority = 100 ) {
	Make()->compatibility()->deprecated_function(
		__FUNCTION__,
		'1.7.0',
		null,
		sprintf(
			esc_html__( 'Use methods from %s instead.', 'make' ),
			'<code>$wp_customize</code>'
		)
	);

	return $initial_priority;
}
else :
	Make()->compatibility()->deprecated_function(
		'ttfmake_customizer_add_section_options',
		'1.7.0',
		null,
		sprintf(
			esc_html__( 'Use methods from %s instead.', 'make' ),
			'<code>$wp_customize</code>'
		)
	);
endif;

if ( ! function_exists( 'ttfmake_customizer_set_transport' ) ) :
/**
 * Add postMessage support for certain built-in settings in the Theme Customizer.
 *
 * Allows these settings to update asynchronously in the Preview pane.
 *
 * @since  1.0.0.
 * @deprecated 1.7.0.
 *
 * @param  WP_Customize_Manager    $wp_customize    Theme Customizer object.
 * @return void
 */
function ttfmake_customizer_set_transport( $wp_customize ) {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0' );
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_customizer_set_transport', '1.7.0' );
endif;

if ( ! function_exists( 'ttfmake_customizer_preview_script' ) ) :
/**
 * Enqueue customizer preview script
 *
 * Hooked to 'customize_preview_init' via ttfmake_customizer_init()
 *
 * @since  1.0.0.
 * @deprecated 1.7.0.
 *
 * @return void
 */
function ttfmake_customizer_preview_script() {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0', 'wp_enqueue_script' );
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_customizer_preview_script', '1.7.0', 'wp_enqueue_script' );
endif;

if ( ! function_exists( 'ttfmake_customizer_scripts' ) ) :
/**
 * Enqueue customizer sections script
 *
 * Hooked to 'customize_controls_enqueue_scripts' via ttfmake_customizer_init()
 *
 * @since  1.5.0.
 * @deprecated 1.7.0.
 *
 * @return void
 */
function ttfmake_customizer_scripts() {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0', 'wp_enqueue_script' );
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_customizer_scripts', '1.7.0', 'wp_enqueue_script' );
endif;

if ( ! function_exists( 'ttfmake_add_customizations' ) ) :
/**
 * Make sure the 'make_css' action only runs once.
 *
 * @since  1.0.0.
 * @deprecated 1.7.0.
 *
 * @return void
 */
function ttfmake_add_customizations() {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0' );
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_add_customizations', '1.7.0' );
endif;

if ( ! function_exists( 'ttfmake_customizer_background' ) ) :
/**
 * Configure settings and controls for the Background section.
 *
 * @since  1.0.0.
 * @deprecated 1.7.0.
 *
 * @return void
 */
function ttfmake_customizer_background() {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0' );
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_customizer_background', '1.7.0' );
endif;

if ( ! function_exists( 'ttfmake_customizer_navigation' ) ) :
/**
 * Configure settings and controls for the Navigation section.
 *
 * @since  1.0.0.
 * @deprecated 1.7.0.
 *
 * @return void
 */
function ttfmake_customizer_navigation() {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0' );
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_customizer_navigation', '1.7.0' );
endif;

if ( ! function_exists( 'ttfmake_customizer_sitetitletagline' ) ) :
/**
 * Configure settings and controls for the Site Title & Tagline section.
 *
 * @since  1.0.0.
 * @deprecated 1.7.0.
 *
 * @return void
 */
function ttfmake_customizer_sitetitletagline() {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0' );
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_customizer_sitetitletagline', '1.7.0' );
endif;

if ( ! function_exists( 'ttfmake_customizer_staticfrontpage' ) ) :
/**
 * Configure settings and controls for the Static Front Page section.
 *
 * @since  1.3.0.
 * @deprecated 1.7.0.
 *
 * @return void
 */
function ttfmake_customizer_staticfrontpage() {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0' );
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_customizer_staticfrontpage', '1.7.0' );
endif;

/**
 * Define the sections and settings for the Background Images panel
 *
 * @since 1.5.0.
 * @deprecated 1.7.0.
 *
 * @param  array    $sections    The master array of Customizer sections
 * @return array                 The augmented master array
 */
function ttfmake_customizer_define_background_images_sections( $sections ) {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0' );
	return $sections;
}

/**
 * Generate an array of Customizer option definitions for a particular HTML element.
 *
 * @since 1.5.0.
 * @deprecated 1.7.0.
 *
 * @param  $region
 * @return array
 */
function ttfmake_customizer_background_image_group_definitions( $region ) {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0' );
	return array();
}

if ( ! function_exists( 'ttfmake_customizer_define_colorscheme_sections' ) ) :
/**
 * Define the sections and settings for the Color panel
 *
 * @since  1.3.0.
 * @deprecated 1.7.0.
 *
 * @param  array    $sections    The master array of Customizer sections
 * @return array                 The augmented master array
 */
function ttfmake_customizer_define_colorscheme_sections( $sections ) {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0' );
	return $sections;
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_customizer_define_colorscheme_sections', '1.7.0' );
endif;

if ( ! function_exists( 'ttfmake_customizer_define_general_sections' ) ) :
/**
 * Define the sections and settings for the General panel
 *
 * @since  1.3.0.
 * @deprecated 1.7.0.
 *
 * @param  array    $sections    The master array of Customizer sections
 * @return array                 The augmented master array
 */
function ttfmake_customizer_define_general_sections( $sections ) {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0' );
	return $sections;
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_customizer_define_general_sections', '1.7.0' );
endif;

if ( ! function_exists( 'ttfmake_customizer_define_contentlayout_sections' ) ) :
/**
 * Define the sections and settings for the Content & Layout panel
 *
 * @since  1.3.0.
 * @deprecated 1.7.0.
 *
 * @param  array    $sections    The master array of Customizer sections
 * @return array                 The augmented master array
 */
function ttfmake_customizer_define_contentlayout_sections( $sections ) {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0' );
	return $sections;
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_customizer_define_contentlayout_sections', '1.7.0' );
endif;

/**
 * Generate an array of Customizer option definitions for a particular view.
 *
 * @since 1.5.0.
 * @deprecated 1.7.0.
 *
 * @param  string    $view
 * @return array
 */
function ttfmake_customizer_layout_region_group_definitions( $view ) {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0' );
	return array();
}

/**
 * Generate an array of Customizer option definitions for a particular view.
 *
 * @since 1.5.0.
 * @deprecated 1.7.0.
 *
 * @param  string    $view
 * @return array
 */
function ttfmake_customizer_layout_featured_image_group_definitions( $view ) {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0' );
	return array();
}

/**
 * Generate an array of Customizer option definitions for a particular view.
 *
 * @since 1.5.0.
 * @deprecated 1.7.0.
 *
 * @param  string    $view
 * @return array
 */
function ttfmake_customizer_layout_post_date_group_definitions( $view ) {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0' );
	return array();
}

/**
 * Generate an array of Customizer option definitions for a particular view.
 *
 * @since 1.5.0.
 * @deprecated 1.7.0.
 *
 * @param  string    $view
 * @return array
 */
function ttfmake_customizer_layout_post_author_group_definitions( $view ) {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0' );
	return array();
}

/**
 * Generate an array of Customizer option definitions for a particular view.
 *
 * @since 1.5.0.
 * @deprecated 1.7.0.
 *
 * @param  string    $view
 * @return array
 */
function ttfmake_customizer_layout_comment_count_group_definitions( $view ) {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0' );
	return array();
}

/**
 * Generate an array of Customizer option definitions for a particular view.
 *
 * @since 1.5.0.
 * @deprecated 1.7.0.
 *
 * @param  string    $view
 * @return array
 */
function ttfmake_customizer_layout_post_meta_group_definitions( $view ) {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0' );
	return array();
}

/**
 * Generate an array of Customizer option definitions for a particular view.
 *
 * @since 1.5.0.
 * @deprecated 1.7.0.
 *
 * @param  string    $view
 * @return array
 */
function ttfmake_customizer_layout_content_group_definitions( $view ) {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0' );
	return array();
}

/**
 * Generate an array of Customizer option definitions for a particular view.
 *
 * @since 1.6.4.
 * @deprecated 1.7.0.
 *
 * @param  string    $view
 *
 * @return array
 */
function ttfmake_customizer_layout_breadcrumb_group_definitions( $view ) {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0' );
	return array();
}

if ( ! function_exists( 'ttfmake_customizer_stylekit' ) ) :
/**
 * Filter to add a new Customizer section
 *
 * This function takes the main array of Customizer sections and adds a new one
 * right before the first panel.
 *
 * @since  1.3.0.
 * @deprecated 1.7.0.
 *
 * @param  array    $sections    The array of sections to add to the Customizer.
 * @return array                 The modified array of sections.
 */
function ttfmake_customizer_stylekit( $sections ) {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0' );
	return $sections;
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_customizer_stylekit', '1.7.0' );
endif;

if ( ! function_exists( 'ttfmake_customizer_define_typography_sections' ) ) :
/**
 * Define the sections and settings for the General panel
 *
 * @since  1.3.0.
 * @deprecated 1.7.0.
 *
 * @param  array    $sections    The master array of Customizer sections
 * @return array                 The augmented master array
 */
function ttfmake_customizer_define_typography_sections( $sections ) {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0' );
	return $sections;
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_customizer_define_typography_sections', '1.7.0' );
endif;

/**
 * Generate an array of Customizer option definitions for a particular HTML element.
 *
 * @since 1.5.0.
 * @deprecated 1.7.0.
 *
 * @param  string    $element
 * @param  string    $label
 * @param  string    $description
 * @return array
 */
function ttfmake_customizer_typography_group_definitions( $element, $label, $description = '' ) {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0' );
	return array();
}

if ( ! function_exists( 'ttfmake_font_choices_placeholder' ) ) :
/**
 * Add a placeholder for the large font choices array, which will be loaded
 * in via JavaScript.
 *
 * @since 1.3.0.
 * @deprecated 1.7.0.
 *
 * @return array
 */
function ttfmake_font_choices_placeholder() {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0' );
	return array( 'placeholder' => __( 'Loading&hellip;', 'make' ) );
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_font_choices_placeholder', '1.7.0' );
endif;

if ( ! function_exists( 'ttfmake_sanitize_font_choice' ) ) :
/**
 * Sanitize a font choice.
 *
 * @since  1.0.0.
 * @deprecated 1.7.0.
 *
 * @param  string    $value    The font choice.
 * @return string              The sanitized font choice.
 */
function ttfmake_sanitize_font_choice( $value ) {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0', 'Make()->sanitize()->sanitize_font_choice()' );
	return $value;
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_sanitize_font_choice', '1.7.0', 'Make()->sanitize()->sanitize_font_choice()' );
endif;

if ( ! function_exists( 'ttfmake_sanitize_font_subset' ) ) :
/**
 * Sanitize the Character Subset choice.
 *
 * @since  1.0.0
 * @deprecated 1.7.0.
 *
 * @param  string    $value    The value to sanitize.
 * @return array               The sanitized value.
 */
function ttfmake_sanitize_font_subset( $value ) {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0', 'Make()->sanitize()->sanitize_google_font_subset()' );
	return Make()->sanitize()->sanitize_google_font_subset( $value );
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_sanitize_font_subset', '1.7.0', 'Make()->sanitize()->sanitize_google_font_subset()' );
endif;

if ( ! function_exists( 'ttfmake_get_all_fonts' ) ) :
/**
 * Compile font options from different sources.
 *
 * @since  1.0.0.
 * @deprecated 1.7.0.
 *
 * @return array    All available fonts.
 */
function ttfmake_get_all_fonts() {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0', 'Make()->font()->get_font_choices()' );
	return Make()->font()->get_font_choices();
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_get_all_fonts', '1.7.0', 'Make()->font()->get_font_choices()' );
endif;

if ( ! function_exists( 'ttfmake_all_font_choices' ) ) :
/**
 * Packages the font choices into value/label pairs for use with the customizer.
 *
 * @since  1.0.0.
 * @deprecated 1.7.0.
 *
 * @return array    The fonts in value/label pairs.
 */
function ttfmake_all_font_choices() {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0', 'Make()->font()->get_font_choices()' );
	return Make()->font()->get_font_choices();
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_all_font_choices', '1.7.0', 'Make()->font()->get_font_choices()' );
endif;

if ( ! function_exists( 'ttfmake_all_font_choices_js' ) ) :
/**
 * Compile the font choices for better handling as a JSON object
 *
 * @since 1.3.0.
 * @deprecated 1.7.0.
 *
 * @return array
 */
function ttfmake_all_font_choices_js() {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0' );
	return array();
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_all_font_choices_js', '1.7.0' );
endif;

if ( ! function_exists( 'ttfmake_get_font_property_option_keys' ) ) :
/**
 * Return all the option keys for the specified font property.
 *
 * @since  1.3.0.
 * @deprecated 1.7.0.
 *
 * @param  string    $property    The font property to search for.
 * @return array                  Array of matching font option keys.
 */
function ttfmake_get_font_property_option_keys( $property ) {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0' );

	$all_keys = array_keys( Make()->thememod()->get_settings( 'default' ) );

	$font_keys = array();
	foreach ( $all_keys as $key ) {
		if ( preg_match( '/^' . $property . '-/', $key ) || preg_match( '/^font-' . $property . '-/', $key ) ) {
			$font_keys[] = $key;
		}
	}

	return $font_keys;
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_get_font_property_option_keys', '1.7.0' );
endif;

if ( ! function_exists( 'ttfmake_get_standard_fonts' ) ) :
/**
 * Return an array of standard websafe fonts.
 *
 * @since  1.0.0.
 * @deprecated 1.7.0.
 *
 * @return array    Standard websafe fonts.
 */
function ttfmake_get_standard_fonts() {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0', 'Make()->font()->get_source( \'generic\' )->get_font_data()' );
	return Make()->font()->get_source( 'generic' )->get_font_data();
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_get_standard_fonts', '1.7.0', 'Make()->font()->get_source( \'generic\' )->get_font_data()' );
endif;

if ( ! function_exists( 'ttfmake_get_google_fonts' ) ) :
/**
 * Return an array of all available Google Fonts.
 *
 * @since  1.0.0.
 * @deprecated 1.7.0.
 *
 * @return array    All Google Fonts.
 */
function ttfmake_get_google_fonts() {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0', 'Make()->font()->get_source( \'google\' )->get_font_data()' );

	if ( Make()->font()->has_source( 'google' ) ) {
		return Make()->font()->get_source( 'google' )->get_font_data();
	}

	return array();
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_get_google_fonts', '1.7.0', 'Make()->font()->get_source( \'google\' )->get_font_data()' );
endif;

if ( ! function_exists( 'ttfmake_choose_google_font_variants' ) ) :
/**
 * Given a font, chose the variants to load for the theme.
 *
 * Attempts to load regular, italic, and 700. If regular is not found, the first variant in the family is chosen. italic
 * and 700 are only loaded if found. No fallbacks are loaded for those fonts.
 *
 * @since  1.0.0.
 * @deprecated 1.7.0.
 *
 * @param  string    $font        The font to load variants for.
 * @param  array     $variants    The variants for the font.
 * @return array                  The chosen variants.
 */
function ttfmake_choose_google_font_variants( $font, $variants = array() ) {
	Make()->compatibility()->deprecated_function(
		__FUNCTION__,
		'1.7.0',
		null,
		sprintf(
			__( 'This function has been moved to %s.', 'make' ),
			'<code>MAKE_Font_Source_Google</code>'
		)
	);

	return array();
}
else :
	Make()->compatibility()->deprecated_function(
		'ttfmake_choose_google_font_variants',
		'1.7.0',
		null,
		sprintf(
			__( 'This function has been moved to %s.', 'make' ),
			'<code>MAKE_Font_Source_Google</code>'
		)
	);
endif;

if ( ! function_exists( 'ttfmake_get_google_font_subsets' ) ) :
/**
 * Retrieve the list of available Google font subsets.
 *
 * @since  1.0.0.
 * @deprecated 1.7.0.
 *
 * @return array    The available subsets.
 */
function ttfmake_get_google_font_subsets() {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0', 'Make()->font()->get_source( \'google\' )->get_subsets()' );

	if ( Make()->font()->has_source( 'google' ) ) {
		return Make()->font()->get_source( 'google' )->get_subsets();
	}

	return array();
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_get_google_font_subsets', '1.7.0', 'Make()->font()->get_source( \'google\' )->get_subsets()' );
endif;

if ( ! function_exists( 'ttfmake_get_google_font_uri' ) ) :
/**
 * Build the HTTP request URL for Google Fonts.
 *
 * The wp_enqueue_style function escapes the stylesheet URL, so no escaping is done here. If
 * this function is used in a different context, make sure the output is escaped!
 *
 * @since  1.0.0.
 * @deprecate 1.7.0.
 *
 * @return string    The URL for including Google Fonts.
 */
function ttfmake_get_google_font_uri() {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0', 'Make()->scripts()->get_google_url()' );
	return Make()->scripts()->get_google_url();
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_get_google_font_uri', '1.7.0', 'Make()->scripts()->get_google_url()' );
endif;

if ( ! function_exists( 'ttfmake_get_logo' ) ) :
/**
 * Return the one TTFMAKE_Logo object.
 *
 * @since  1.0.0.
 * @deprecated 1.7.0.
 *
 * @return TTFMAKE_Logo
 */
function ttfmake_get_logo() {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0', 'Make()->logo()->legacy()' );
	return Make()->logo()->legacy();
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_get_logo', '1.7.0', 'Make()->logo()->legacy()' );
endif;

if ( ! function_exists( 'ttfmake_refresh_logo_cache' ) ) :
/**
 * Refresh the logo cache after the customizer is saved.
 *
 * @since  1.0.0.
 * @deprecated 1.7.0.
 *
 * @param  object    $wp_customize    The customizer object.
 * @return void
 */
function ttfmake_refresh_logo_cache( $wp_customize ) {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0', 'Make()->logo()->legacy()->refresh_logo_cache()' );
	Make()->logo()->legacy()->refresh_logo_cache();
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_refresh_logo_cache', '1.7.0', 'Make()->logo()->legacy()->refresh_logo_cache()' );
endif;

/**
 * Instantiate or return the one TTFMAKE_Formatting instance.
 *
 * @since  1.4.1.
 * @deprecated 1.7.0.
 *
 * @return TTFMAKE_Formatting
 */
function ttfmake_formatting() {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0', 'Make()->formatting()' );
	return Make()->formatting();
}

/**
 * Run the init function for the Format Builder
 *
 * @since 1.4.1.
 * @deprecated 1.7.0.
 *
 * @return void
 */
function ttfmake_formatting_init() {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0' );
}

if ( ! function_exists( 'ttfmake_get_gallery_slider' ) ) :
/**
 * Return the one TTFMAKE_Gallery_Slider object.
 *
 * @since  1.0.0.
 * @deprecated 1.7.0.
 *
 * @return TTFMAKE_Gallery_Slider
 */
function ttfmake_get_gallery_slider() {
	Make()->compatibility()->deprecated_function( __FUNCTION__, '1.7.0', 'Make()->galleryslider()' );
	return Make()->galleryslider();
}
else :
	Make()->compatibility()->deprecated_function( 'ttfmake_get_gallery_slider', '1.7.0', 'Make()->galleryslider()' );
endif;

if ( class_exists( 'WP_Customize_Control' ) ) :
/**
 * Class TTFMAKE_Customize_Background_Position_Control
 *
 * Specialized radio control for choosing background image positioning.
 *
 * This control has been deprecated in favor of MAKE_Customizer_Control_BackgroundPosition.
 *
 * @since 1.5.0.
 * @deprecated 1.7.0.
 */
class TTFMAKE_Customize_Background_Position_Control extends MAKE_Customizer_Control_BackgroundPosition {
	public function __construct( WP_Customize_Manager $manager, $id, array $args ) {
		parent::__construct( $manager, $id, $args );
		$this->type = 'make_backgroundposition';

		Make()->error()->add_error(
			'make_customizer_control_deprecated',
			sprintf(
				esc_html__( 'The %1$s control is deprecated. Use %2$s instead.', 'make' ),
				'<code>TTFMAKE_Customize_Background_Position_Control</code>',
				'<code>MAKE_Customizer_Control_BackgroundPosition</code>'
			)
		);
	}
}

/**
 * Class TTFMAKE_Customize_Image_Control
 *
 * Extend WP_Customize_Image_Control allowing access to uploads made within the same context.
 *
 * @since 1.0.0.
 * @deprecated 1.7.0.
 */
class TTFMAKE_Customize_Image_Control extends WP_Customize_Image_Control {
	public function __construct( WP_Customize_Manager $manager, $id, $args = array() ) {
		parent::__construct( $manager, $id, $args );

		Make()->error()->add_error(
			'make_customizer_control_deprecated',
			sprintf(
				esc_html__( 'The %1$s control is deprecated. Use %2$s instead.', 'make' ),
				'<code>TTFMAKE_Customize_Image_Control</code>',
				'<code>WP_Customize_Image_Control</code>'
			)
		);
	}
}

/**
 * Class TTFMAKE_Customize_Misc_Control
 *
 * Control for adding arbitrary HTML to a Customizer section.
 *
 * This control has been deprecated in favor of MAKE_Customizer_Control_Html.
 *
 * @since 1.0.0.
 * @deprecated 1.7.0.
 */
class TTFMAKE_Customize_Misc_Control extends MAKE_Customizer_Control_Html {
	/**
	 * Convert the ID and args for use with MAKE_Customizer_Control_Html.
	 *
	 * @since 1.7.0.
	 *
	 * @param WP_Customize_Manager $manager
	 * @param string               $id
	 * @param array                $args
	 */
	public function __construct( WP_Customize_Manager $manager, $id, array $args = array() ) {
		parent::__construct( $manager, $id, $args );

		$type = $this->type;
		$this->type = 'make_html';

		switch ( $type ) {
			case 'group-title' :
				$this->html = '<h4 class="make-group-title">' . esc_html( $this->label ) . '</h4>';
				if ( '' !== $this->description ) {
					$this->html .= '<span class="description customize-control-description">' . $this->description . '</span>';
				}
				$this->label = '';
				$this->description = '';
				break;
			case 'line' :
				$this->html = '<hr class="make-ruled-line" />';
				break;
		}

		Make()->error()->add_error(
			'make_customizer_control_deprecated',
			sprintf(
				esc_html__( 'The %1$s control is deprecated. Use %2$s instead.', 'make' ),
				'<code>TTFMAKE_Customize_Misc_Control</code>',
				'<code>MAKE_Customizer_Control_Html</code>'
			)
		);
	}
}

/**
 * Class TTFMAKE_Customize_Radio_Control
 *
 * Specialized radio control to enable buttonset-style choices.
 *
 * Inspired by Kirki.
 * @link https://github.com/aristath/kirki/blob/0.5/includes/controls/class-Kirki_Customize_Radio_Control.php
 *
 * This control has been deprecated in favor of MAKE_Customizer_Control_Radio.
 *
 * @since 1.5.0.
 * @deprecated 1.7.0.
 */
class TTFMAKE_Customize_Radio_Control extends MAKE_Customizer_Control_Radio {
	public function __construct( WP_Customize_Manager $manager, $id, array $args ) {
		parent::__construct( $manager, $id, $args );
		$this->type = 'make_radio';

		Make()->error()->add_error(
			'make_customizer_control_deprecated',
			sprintf(
				esc_html__( 'The %1$s control is deprecated. Use %2$s instead.', 'make' ),
				'<code>TTFMAKE_Customize_Radio_Control</code>',
				'<code>MAKE_Customizer_Control_Radio</code>'
			)
		);
	}
}

/**
 * Class TTFMAKE_Customize_Range_Control
 *
 * Specialized range control to enable a slider with an accompanying number field.
 *
 * Inspired by Kirki.
 * @link https://github.com/aristath/kirki/blob/0.5/includes/controls/class-Kirki_Customize_Sliderui_Control.php
 *
 * This control has been deprecated in favor of MAKE_Customizer_Control_Range.
 *
 * @since 1.5.0.
 * @deprecated 1.7.0.
 */
class TTFMAKE_Customize_Range_Control extends MAKE_Customizer_Control_Range {
	public function __construct( WP_Customize_Manager $manager, $id, array $args ) {
		parent::__construct( $manager, $id, $args );
		$this->type = 'make_range';

		Make()->error()->add_error(
			'make_customizer_control_deprecated',
			sprintf(
				esc_html__( 'The %1$s control is deprecated. Use %2$s instead.', 'make' ),
				'<code>TTFMAKE_Customize_Range_Control</code>',
				'<code>MAKE_Customizer_Control_Range</code>'
			)
		);
	}
}
endif;