<?php
/**
 * @package Make
 */

/**
 * The current version of the theme.
 */
define( 'TTFMAKE_VERSION', '1.7.11' );

/**
 * The minimum version of WordPress required for Make.
 */
define( 'TTFMAKE_MIN_WP_VERSION', '4.4' );

// Activation
require_once get_template_directory() . '/inc/activation.php';

// Autoloader
require_once get_template_directory() . '/inc/autoload.php';

// Globals
global $content_width, $Make;

// Initial content width.
if ( ! isset( $content_width ) ) {
	$content_width = 960;
}

// Load API
$Make = new MAKE_API;

/**
 * Action: Fire when the Make API has finished loading.
 *
 * @since 1.7.0.
 *
 * @param MAKE_API $Make
 */
do_action( 'make_api_loaded', $Make );

// Template tags
require_once get_template_directory() . '/inc/template-tags.php';

/**
 * Get the global Make API object.
 *
 * @since 1.7.0.
 *
 * @return MAKE_API|null
 */
function Make() {
	global $Make;

	if ( ! did_action( 'make_api_loaded' ) || ! $Make instanceof MAKE_APIInterface ) {
		trigger_error(
			__( 'The Make() function should not be called before the make_api_loaded action has fired.', 'make' ),
			E_USER_WARNING
		);

		return null;
	}

	return $Make;
}

/**
 * Add or update a choice set.
 *
 * Some settings in Make need to be limited to a value that comes from a specific set of values, rather than having an
 * arbitrary value. These possible values are called "choices" in Make.
 *
 * A choice set consists of a set ID that corresponds to an array of value/label pairs.
 *
 *     $set_id = 'french-fries';
 *     $choices = array(
 *         'regular'     => __( 'Regular french fries', 'make-child' ),
 *         'cajun'       => __( 'Cajun spiced fries', 'make-child' ),
 *         'sweetpotato' => __( 'Sweet potato fries', 'make-child' ),
 *     );
 *
 * In order to ensure that all choice sets have been added and updated before they are used by the theme, this function
 * should only be called during the `make_choices_loaded` action.
 *
 * @link https://thethemefoundry.com/docs/make-docs/code/apis/choices-api/
 *
 * @since 1.7.0.
 *
 * @param string $set_id     A unique string to identify the choice set.
 * @param array  $choices    An array of value/label pairs.
 *
 * @return bool    True if the choice set was successfully added/updated.
 */
function make_update_choice_set( $set_id, $choices ) {
	// Make sure we're not doing it wrong.
	if ( 'make_choices_loaded' !== current_action() ) {
		Make()->compatibility()->doing_it_wrong(
			__FUNCTION__,
			sprintf(
				esc_html__( 'This function should only be called during the %s action.', 'make' ),
				'<code>make_choices_loaded</code>'
			),
			'1.7.0'
		);
	}

	// Construct the set
	$choice_set = array(
		$set_id => (array) $choices
	);

	return Make()->choices()->add_choice_sets( $choice_set, true );
}

/**
 * Add one or more fonts, under their own heading, to the list of available fonts in the
 * Customizer's Font Family dropdowns.
 *
 * A font source consists of a unique ID, a public label, an optional priority, and an associative array of data for the
 * actual fonts that the source contains. Each font in the data array is represented by an array item whose key is the
 * font's setting value and whose value is an array of properties. The required properties are a label and a font stack.
 *
 *     $id = 'epic';
 *     $label = __( 'Literally Epic Fonts', 'make-child' );
 *     $priority = 1;
 *     $data = array(
 *         'Comic Papyrus' => array(
 *             'label' => __( 'Comic Papyrus', 'make-child' ),
 *             'stack' => '"Comic Papyrus", "Comic Sans", "Papyrus", sans-serif',
 *         ),
 *         'Wing Dings 98' => array(
 *             'label' => __( 'Wing Dings 98', 'make-child' ),
 *             'stack' => '"Wing Dings 98", sans-serif',
 *         ),
 *     );
 *
 * @link https://thethemefoundry.com/docs/make-docs/code/apis/font-api/
 *
 * @since 1.7.0.
 *
 * @param string $id          A unique string to identify the source.
 * @param string $label       The public name of the font source.
 * @param array  $data        The array of fonts to add and their properties.
 * @param int    $priority    Optional. The order this source should appear in the list of all available fonts.
 *                            Higher number = further down the list.
 *
 * @return bool    True if the font source was successfully added.
 */
function make_add_font_source( $id, $label, $data = array(), $priority = 10 ) {
	// Make sure we're not doing it wrong.
	if ( 'make_font_loaded' !== current_action() ) {
		Make()->compatibility()->doing_it_wrong(
			__FUNCTION__,
			sprintf(
				esc_html__( 'This function should only be called during the %s action.', 'make' ),
				'<code>make_font_loaded</code>'
			),
			'1.7.0'
		);
	}

	$source = new MAKE_Font_Source_Base( $id, $label, $data, $priority );

	return Make()->font()->add_source( $id, $source );
}

/**
 * Add or update a view definition.
 *
 * Make uses "views" to determine which layout settings to apply to a given page load.
 *
 * A view definition consists of a view ID that corresponds to an array of properties. The required properties for a
 * view are a label and a callback function that determines whether it qualifies to be the current view. A third,
 * optional property is priority, which determines which view will take precedent when the current view qualifies for
 * multiple views. A higher priority will take precedent over a lower.
 *
 *     $view_id = 'page';
 *     $args = array(
 *         'label'    => __( 'Page', 'make-child' ),
 *         'callback' => 'is_page',
 *         'priority' => 10,
 *     );
 *
 * When updating an existing definition, only the properties that are changing need to be included in the $args array.
 *
 * In order to ensure that all view definitions have been added and updated before they are used by the theme, this
 * function should only be called during the `make_view_loaded` action.
 *
 * @since 1.7.0.
 *
 * @param string $view_id    A unique string to identify the view.
 * @param array  $args       {
 *     An array of properties for the view.
 *
 *     @type string       $label       The public name of the view. May appear in the UI.
 *     @type string|array $callback    A callable that returns a boolean value that answers "Is this the current view?"
 *     @type int          $priority    A number that indicates when the view's callback will be evaluated.
 *                                     Higher = later = higher precedence.
 *     @type mixed        $various     Any arbitrary key/value pair can be added to a view definition.
 * }
 *
 * @return bool    True if the view definition was successfully added/updated.
 */
function make_update_view_definition( $view_id, $args ) {
	// Make sure we're not doing it wrong.
	if ( 'make_view_loaded' !== current_action() ) {
		Make()->compatibility()->doing_it_wrong(
			__FUNCTION__,
			sprintf(
				esc_html__( 'This function should only be called during the %s action.', 'make' ),
				'<code>make_view_loaded</code>'
			),
			'1.7.0'
		);
	}

	// Cast the args
	$args = (array) $args;

	return Make()->view()->add_view( $view_id, $args, true );
}

/**
 * Add or update a setting definition.
 *
 * Make provides a lot of customization options through the Customizer, and each of these is represented by a "setting".
 * A setting consists of an ID that is tied to a particular value.
 *
 * A setting definition consists of an ID that corresponds to an array of properties. The required properties for a
 * setting are a default value and a callback that validates and sanitizes a setting's value before it is used by the
 * theme.
 *
 *     $setting_id = 'my-site-rocks';
 *     $args = array(
 *         'default'  => true,
 *         'sanitize' => 'wp_validate_boolean',
 *     );
 *
 * When updating an existing definition, only the properties that are changing need to be included in the $args array.
 *
 * In order to ensure that all setting definitions have been added and updated before they are used by the theme, this
 * function should only be called during the `make_settings_thememod_loaded` action.
 *
 * @since 1.7.0.
 *
 * @param string $setting_id    A unique string to identify the setting.
 * @param array  $args          {
 *     An array of properties for the setting.
 *
 *     @type mixed        $default     The default value for the setting.
 *     @type string|array $sanitize    A callable that validates and sanitizes a setting's value before it is used by
 *                                     the theme.
 *     @type mixed        $various     Any arbitrary key/value pair can be added to a setting definition.
 * }
 *
 * @return bool    True if the setting definition was successfully added/updated.
 */
function make_update_thememod_setting_definition( $setting_id, $args ) {
	// Make sure we're not doing it wrong.
	if ( 'make_settings_thememod_loaded' !== current_action() ) {
		Make()->compatibility()->doing_it_wrong(
			__FUNCTION__,
			sprintf(
				esc_html__( 'This function should only be called during the %s action.', 'make' ),
				'<code>make_settings_thememod_loaded</code>'
			),
			'1.7.0'
		);
	}

	// Construct the setting definition
	$setting_definition = array(
		$setting_id => (array) $args
	);

	return Make()->thememod()->add_settings( $setting_definition, array(), true );
}

/**
 * Add or update a social icon definition.
 *
 * Make allows users to add links to their online profiles to the header and/or footer of their site. These links are
 * represented by icons that correspond to the services providing the online profiles.
 *
 * An icon definition consists of a URL pattern that corresponds to an array of icon properties. The required properties
 * for an icon are a title and an array of CSS classes that will be applied to the icon's HTML element.
 *
 *     $pattern = 'myspace.com';
 *     $args = array(
 *         'title' => __( 'MySpace', 'make-child' ),
 *         'class' => array( 'my-cool-icon-font', 'mcif-myspace' ),
 *     );
 *
 * When updating an existing definition, only the properties that are changing need to be included in the $args array.
 *
 * In order to ensure that all icon definitions have been added and updated before they are used by the theme, this
 * function should only be called during the `make_socialicons_loaded` action.
 *
 * @since 1.7.0.
 *
 * @param string $pattern    A unique pattern string that will be used to match a URL to a particular icon.
 * @param array  $args       {
 *     An array of properties for the icon.
 *
 *     @type string $title    The public name of the social profile service that corresponds to the icon.
 *     @type array  $class    An array of class name strings.
 * }
 *
 * @return bool    True if the icon definition was successfully added/updated.
 */
function make_update_socialicon_definition( $pattern, $args ) {
	// Make sure we're not doing it wrong.
	if ( 'make_socialicons_loaded' !== current_action() ) {
		Make()->compatibility()->doing_it_wrong(
			__FUNCTION__,
			sprintf(
				esc_html__( 'This function should only be called during the %s action.', 'make' ),
				'<code>make_socialicons_loaded</code>'
			),
			'1.7.0'
		);
	}

	// Construct the icon definition
	$icon_definition = array(
		$pattern => (array) $args
	);

	return Make()->socialicons()->add_icons( $icon_definition, true );
}

/**
 * Add a style rule.
 *
 * Many of Make's settings modify the site's appearance and style. Because the style rules for these settings have
 * dynamic values and may be different for different views, they cannot be added to a normal stylesheet, but must
 * instead be generated programmatically.
 *
 * A style rule consists of an array containing a selectors item, a declarations item, and optionally a media query
 * item.
 *
 *     $args = array(
 *         'selectors'    => array( '.site-header', '.site-footer' ),
 *         'declarations' => array(
 *             'background-color' => '#00ff00',
 *             'font-size'        => '27px'
 *         ),
 *         'media'        => 'screen and (min-width: 800px)',
 *     );
 *
 * Note that styles that do not have dynamic values should be added to a stylesheet file instead of programmatically.
 *
 * @since 1.7.0.
 *
 * @param array $args    {
 *     An array containing the selectors, declarations, and optional media query for the style rule.
 *
 *     @type array  $selectors       A numeric array of CSS selectors.
 *     @type array  $declarations    An associative array of CSS property/value pairs.
 *     @type string $media           Optional. A media query string.
 * }
 *
 * @return void
 */
function make_add_style_rule( $args ) {
	// Make sure we're not doing it wrong.
	if ( 'make_style_loaded' !== current_action() ) {
		Make()->compatibility()->doing_it_wrong(
			__FUNCTION__,
			sprintf(
				esc_html__( 'This function should only be called during the %s action.', 'make' ),
				'<code>make_style_loaded</code>'
			),
			'1.7.0'
		);
	}

	// Cast args
	$args = (array) $args;

	// Validate the rule args
	if ( ! isset( $args['selectors'] ) || ! isset( $args['declarations'] ) ) {
		$error_message = __( 'The style rule does not have the required properties.', 'make' ) . $this->error()->generate_backtrace();

		Make()->error()->add_error(
			'make_style_invalid_rule',
			$error_message
		);

		return;
	}

	Make()->style()->css()->add( $args );
}
