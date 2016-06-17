<?php
/**
 * @package Make
 */

/**
 * Class MAKE_Compatibility_HookPrefixer
 *
 * @since 1.7.0.
 */
final class MAKE_Compatibility_HookPrefixer extends MAKE_Util_Modules implements MAKE_Util_HookInterface {
	/**
	 * An associative array of required modules.
	 *
	 * @since 1.7.0.
	 *
	 * @var array
	 */
	protected $dependencies = array(
		'compatibility' => 'MAKE_Compatibility_MethodsInterface',
	);

	/**
	 * Indicator of whether the hook routine has been run.
	 *
	 * @since 1.7.0.
	 *
	 * @var bool
	 */
	private static $hooked = false;

	/**
	 * Hook into WordPress.
	 *
	 * @since 1.7.0.
	 */
	public function hook() {
		if ( $this->is_hooked() ) {
			return;
		}

		// Filters
		add_action( 'after_setup_theme', array( $this, 'add_filters' ), 99 );

		// Actions
		add_action( 'after_setup_theme', array( $this, 'add_actions' ), 99 );

		// Hooking has occurred.
		self::$hooked = true;
	}

	/**
	 * Check if the hook routine has been run.
	 *
	 * @since 1.7.0.
	 *
	 * @return bool
	 */
	public function is_hooked() {
		return self::$hooked;
	}

	/**
	 * Adds back compat for filters with changed names.
	 *
	 * In Make 1.2.3, filters were all changed from "ttfmake_" to "make_". In order to maintain back compatibility, the old
	 * version of the filter needs to still be called. This function collects all of those changed filters and mirrors the
	 * new filter so that the old filter name will still work.
	 *
	 * @since  1.2.3.
	 *
	 * @hooked action after_setup_theme
	 *
	 * @return void
	 */
	public function add_filters() {
		// All filters that need a name change
		$filter_slugs = array(
			'template_content_archive'     => 2,
			'fitvids_custom_selectors'     => 1,
			'template_content_page'        => 2,
			'template_content_search'      => 2,
			'footer_1'                     => 1,
			'footer_2'                     => 1,
			'footer_3'                     => 1,
			'footer_4'                     => 1,
			'sidebar_left'                 => 1,
			'sidebar_right'                => 1,
			'template_content_single'      => 2,
			'get_view'                     => 2,
			'has_sidebar'                  => 3,
			'read_more_text'               => 1,
			'supported_social_icons'       => 1,
			'exif_shutter_speed'           => 2,
			'exif_aperture'                => 2,
			'style_formats'                => 1,
			'prepare_data_section'         => 3,
			'insert_post_data_sections'    => 1,
			'section_classes'              => 2,
			'the_builder_content'          => 1,
			'builder_section_footer_links' => 1,
			'section_defaults'             => 1,
			'section_choices'              => 3,
			'gallery_class'                => 2,
			'builder_banner_class'         => 2,
			'customizer_sections'          => 1,
			'setting_defaults'             => 1,
			'font_relative_size'           => 1,
			'font_stack'                   => 2,
			'font_variants'                => 3,
			'all_fonts'                    => 1,
			'get_google_fonts'             => 1,
			'custom_logo_information'      => 1,
			'custom_logo_max_width'        => 1,
			'setting_choices'              => 2,
			'social_links'                 => 1,
			'show_footer_credit'           => 1,
			'is_plus'                      => 1,
			'builder_js_dependencies'      => 1,
		);

		foreach ( $filter_slugs as $filter_slug => $args ) {
			$old_filter = 'ttfmake_' . $filter_slug;
			$new_filter = 'make_' . $filter_slug;

			if ( has_filter( $old_filter ) ) {
				$this->compatibility()->deprecated_hook(
					$old_filter,
					'1.2.3',
					sprintf(
						esc_html__( 'Use the %s hook instead.', 'make' ),
						"<code>$new_filter</code>"
					)
				);

				add_filter( $new_filter, array( $this, 'mirror_filter' ), 1, $args );
			}
		}
	}

	/**
	 * Prepends "ttf" to a filter name and calls that new filter variant.
	 *
	 * @since  1.2.3.
	 *
	 * @hooked filter (various)
	 *
	 * @return mixed    The result of the filter.
	 */
	public function mirror_filter() {
		$filter = 'ttf' . current_filter();
		$args   = func_get_args();
		return apply_filters_ref_array( $filter, $args );
	}

	/**
	 * Adds back compat for actions with changed names.
	 *
	 * In Make 1.2.3, actions were all changed from "ttfmake_" to "make_". In order to maintain back compatibility, the old
	 * version of the action needs to still be called. This function collects all of those changed actions and mirrors the
	 * new filter so that the old filter name will still work.
	 *
	 * @since  1.2.3.
	 *
	 * @hooked action after_setup_theme
	 *
	 * @return void
	 */
	public function add_actions() {
		// All actions that need a name change
		$action_slugs = array(
			'section_text_before_columns_select' => 1,
			'section_text_after_columns_select'  => 1,
			'section_text_after_title'           => 1,
			'section_text_before_column'         => 2,
			'section_text_after_column'          => 2,
			'section_text_after_columns'         => 1,
			'css'                                => 1,
		);

		foreach ( $action_slugs as $action_slug => $args ) {
			$old_action = 'ttfmake_' . $action_slug;
			$new_action = 'make_' . $action_slug;

			if ( has_action( $old_action ) ) {
				$this->compatibility()->deprecated_hook(
					$old_action,
					'1.2.3',
					sprintf(
						esc_html__( 'Use the %s hook instead.', 'make' ),
						"<code>$new_action</code>"
					)
				);

				add_action( $new_action, array( $this, 'mirror_action' ), 1, $args );
			}
		}
	}

	/**
	 * Prepends "ttf" to an action name and calls that new action variant.
	 *
	 * @since  1.2.3.
	 *
	 * @hooked filter (various)
	 *
	 * @return mixed    The result of the action.
	 */
	public function mirror_action() {
		$action = 'ttf' . current_action();
		$args   = func_get_args();
		do_action_ref_array( $action, $args );
	}
}