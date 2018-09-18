<?php
/**
 * @package Make
 */

/**
 * Class MAKE_Setup_Widgets
 *
 * Methods for setting up sidebars and widgets.
 *
 * @since 1.7.0.
 */
final class MAKE_Setup_Widgets extends MAKE_Util_Modules implements MAKE_Setup_WidgetsInterface, MAKE_Util_HookInterface {
	/**
	 * An associative array of required modules.
	 *
	 * @since 1.7.0.
	 *
	 * @var array
	 */
	protected $dependencies = array(
		'error'         => 'MAKE_Error_CollectorInterface',
		'compatibility' => 'MAKE_Compatibility_MethodsInterface',
		'view'          => 'MAKE_Layout_ViewInterface',
		'thememod'      => 'MAKE_Settings_ThemeModInterface',
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
	 *
	 * @return void
	 */
	public function hook() {
		if ( $this->is_hooked() ) {
			return;
		}

		// Register sidebars
		add_action( 'widgets_init', array( $this, 'register_sidebars' ) );

		// Backcompat with old function
		add_action( 'make_deprecated_function_run', array( $this, 'backcompat_widgets_init' ) );

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
	 * Get the widget HTML markup parameters for a particular sidebar.
	 *
	 * @since 1.7.0.
	 *
	 * @param string $sidebar_id
	 *
	 * @return array
	 */
	public function get_widget_display_args( $sidebar_id ) {
		$widget_args = array(
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		);

		/**
		 * Filter: Modify the wrapper markup parameters for the widgets in a particular sidebar.
		 *
		 * @since 1.7.0.
		 *
		 * @param array  $widget_args    The default widget markup for sidebars.
		 * @param string $sidebar_id     The ID of the sidebar that the widget markup will apply to.
		 */
		return apply_filters( 'make_widget_display_args', $widget_args, $sidebar_id );
	}

	/**
	 * Register the theme's sidebars.
	 *
	 * @since 1.0.0.
	 *
	 * @hooked action widgets_init
	 *
	 * @return void
	 */
	public function register_sidebars() {
		// Sidebar IDs and labels
		$sidebars = array(
			'sidebar-left'  => __( 'Left Sidebar', 'make' ),
			'sidebar-right' => __( 'Right Sidebar', 'make' ),
			'footer-1'      => __( 'Footer 1', 'make' ),
			'footer-2'      => __( 'Footer 2', 'make' ),
			'footer-3'      => __( 'Footer 3', 'make' ),
			'footer-4'      => __( 'Footer 4', 'make' ),
		);

		// Register each sidebar
		foreach ( $sidebars as $sidebar_id => $sidebar_name ) {
			$sidebar_args = array(
				'id'          => $sidebar_id,
				'name'        => $sidebar_name,
				'description' => ( is_admin() ) ? $this->get_sidebar_description( $sidebar_id ) : '', // The sidebar description isn't needed for the front end.
			);

			register_sidebar( $sidebar_args + $this->get_widget_display_args( $sidebar_id ) );
		}
	}

	/**
	 * Generate a description for a sidebar based on where it is set to be displayed.
	 *
	 * @since 1.7.0.
	 *
	 * @param string $sidebar_id
	 *
	 * @return string
	 */
	private function get_sidebar_description( $sidebar_id ) {
		$description = '';

		// Footer sidebars
		if ( false !== strpos( $sidebar_id, 'footer-' ) ) {
			$column = (int) str_replace( 'footer-', '', $sidebar_id );
			$column_count = $this->thememod()->get_value( 'footer-widget-areas' );

			if ( $column > $column_count ) {
				$description = __( 'This widget area is currently disabled. Enable it in the "Footer" section of the "Layout" panel in the Customizer.', 'make' );
			}
		}
		// Other sidebars
		else if ( false !== strpos( $sidebar_id, 'sidebar-' ) ) {
			$location = str_replace( 'sidebar-', '', $sidebar_id );

			$enabled_views = $this->get_enabled_view_labels( $location );

			// Not enabled anywhere
			if ( empty( $enabled_views ) ) {
				$description = __( 'This widget area is currently disabled. Enable it in the "Layout" panel of the Customizer.', 'make' );
			}
			// List enabled views
			else {
				$description = sprintf(
					__( 'This widget area is currently enabled for the following views: %s. Change this in the "Layout" panel of the Customizer.', 'make' ),
					implode( _x( ', ', 'list item separator', 'make' ), $enabled_views )
				);
			}
		}

		return $description;
	}

	/**
	 * Get an array of view names where a particular sidebar is enabled.
	 *
	 * @since 1.7.0.
	 *
	 * @param string $location    'right' or 'left'
	 *
	 * @return array
	 */
	private function get_enabled_view_labels( $location ) {
		$enabled_views = array();

		$views = array_keys( $this->view()->get_sorted_views() );

		foreach ( $views as $view_id ) {
			$setting_id = 'layout-' . $view_id . '-sidebar-' . $location;
			if ( true === $this->thememod()->get_value( $setting_id ) ) {
				$enabled_views[] = $this->view()->get_view_label( $view_id );
			}
		}

		// Check for deprecated filter.
		if ( has_filter( 'make_sidebar_list_enabled' ) ) {
			$this->compatibility()->deprecated_hook(
				'make_sidebar_list_enabled',
				'1.7.0'
			);
		}

		return $enabled_views;
	}

	/**
	 * Determine if a particular sidebar is enabled in the current view.
	 *
	 * @since 1.7.0.
	 *
	 * @param string $location
	 *
	 * @return bool
	 */
	public function has_sidebar( $location ) {
		// Get the view
		$view = $this->view()->get_current_view();

		// Get the relevant theme mod
		$setting_id = 'layout-' . $view . '-sidebar-' . $location;
		$has_sidebar = (bool) $this->thememod()->get_value( $setting_id );

		// Builder template doesn't support sidebars
		if ( is_page() && 'template-builder.php' === get_page_template_slug() ) {
			$has_sidebar = false;
		}

		/**
		 * Filter: Dynamically change the result of the "has sidebar" check.
		 *
		 * @since 1.2.3.
		 *
		 * @param bool      $has_sidebar    Whether or not to show the sidebar.
		 * @param string    $location       The location of the sidebar being evaluated.
		 * @param string    $view           The view name.
		 */
		return apply_filters( 'make_has_sidebar', $has_sidebar, $location, $view );
	}

	/**
	 * Backcompat for the deprecated pluggable ttfmake_widgets_init() function.
	 *
	 * This will fire if the Compatibility module's deprecated_function method is run, which will happen
	 * if ttfmake_widgets_init() has been plugged.
	 *
	 * @since 1.7.0.
	 *
	 * @hooked action make_deprecated_function_run
	 *
	 * @param string $function
	 *
	 * @return void
	 */
	public function backcompat_widgets_init( $function ) {
		// Don't bother if this is happening during widgets_init already.
		if ( doing_action( 'widgets_init' ) || did_action( 'widgets_init' ) ) {
			return;
		}

		if ( 'ttfmake_widgets_init' === $function && false === has_action( 'widgets_init', 'ttfmake_widgets_init' ) ) {
			remove_action( 'widgets_init', array( $this, 'register_sidebars' ) );
			add_action( 'widgets_init', 'ttfmake_widgets_init' );
		}
	}
}
