<?php
/**
 * @package Make
 */

/**
 * Class MAKE_Layout_View
 *
 * Define and manage distinct views for the theme. Used to determine which set of layout settings to apply.
 *
 * @since 1.7.0.
 */
final class MAKE_Layout_View extends MAKE_Util_Modules implements MAKE_Layout_ViewInterface, MAKE_Util_LoadInterface {
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
	);

	/**
	 * View bucket.
	 *
	 * @since 1.7.0.
	 *
	 * @var array
	 */
	private $views = array();

	/**
	 * The default view.
	 *
	 * @since 1.7.0.
	 *
	 * @var string|null
	 */
	private $default_view = null;

	/**
	 * Indicator of whether the load routine has been run.
	 *
	 * @since 1.7.0.
	 *
	 * @var bool
	 */
	private $loaded = false;

	/**
	 * Load data files.
	 *
	 * @since 1.7.0.
	 *
	 * @return void
	 */
	public function load() {
		if ( true === $this->is_loaded() ) {
			return;
		}

		$views = array(
			'blog'    => array(
				'label'    => __( 'Blog (Post Page)', 'make' ),
				'callback' => 'is_home',
			),
			'archive' => array(
				'label'    => __( 'Archive', 'make' ),
				'callback' => 'is_archive',
			),
			'search'  => array(
				'label'    => __( 'Search Results', 'make' ),
				'callback' => 'is_search',
			),
			'page'    => array(
				'label'    => __( 'Page', 'make' ),
				'callback' => array( $this, 'callback_page' ),
			),
			'post'    => array(
				'label'    => __( 'Post', 'make' ),
				'callback' => array( $this, 'callback_post' ),
			),
		);

		foreach ( $views as $view_id => $view_args ) {
			$this->add_view( $view_id, $view_args );
		}

		// Loading has occurred.
		$this->loaded = true;

		/**
		 * Action: Fires at the end of the view object's load method.
		 *
		 * This action gives a developer the opportunity to add or modify views
		 * and run additional load routines.
		 *
		 * @since 1.7.0.
		 *
		 * @param MAKE_Layout_View $view    The view object that has just finished loading.
		 */
		do_action( 'make_view_loaded', $this );
	}

	/**
	 * Check if the load routine has been run.
	 *
	 * @since 1.7.0.
	 *
	 * @return bool
	 */
	public function is_loaded() {
		return $this->loaded;
	}

	/**
	 * Add or update a view definition.
	 *
	 * Example:
	 * add_view(
	 *     'page',
	 *     array(
	 *         'label'    => __( 'Page', 'make' ),
	 *         'callback' => 'is_page',
	 *         'priority' => 10
	 *     ),
	 *     true
	 * );
	 *
	 * @since 1.7.0.
	 *
	 * @param string $view_id
	 * @param array  $args
	 * @param bool   $overwrite
	 *
	 * @return bool
	 */
	public function add_view( $view_id, array $args = array(), $overwrite = false ) {
		$view_id = sanitize_key( $view_id );
		$new_view_args = array();
		$return = false;

		// Overwrite an existing view.
		if ( isset( $this->views[ $view_id ] ) && true === $overwrite ) {
			$new_view_args = wp_parse_args( $args, $this->views[ $view_id ] );
		}
		// View already exists, overwriting disabled.
		else if ( isset( $this->views[ $view_id ] ) && true !== $overwrite ) {
			$this->error()->add_error(
				'make_view_already_exists',
				sprintf(
					__( 'The "%s" view can\'t be added because it already exists.', 'make' ),
					esc_html( $view_id )
				)
			);
		}
		// Add a new view.
		else {
			// Merge defaults
			$defaults = array(
				'label'    => ucwords( preg_replace( '/[\-_]*/', ' ', $view_id ) ),
				'callback' => '',
				'priority' => 10,
			);
			$new_view_args = wp_parse_args( $args, $defaults );
		}

		if ( ! empty( $new_view_args ) ) {
			// Validate the callback.
			if ( is_callable( $new_view_args['callback'] ) ) {
				$this->views[ $view_id ] = $new_view_args;
				$return                  = true;
			} else {
				$this->error()->add_error(
					'make_view_callback_not_valid',
					sprintf(
						__( 'The view callback (%1$s) for "%2$s" is not valid.', 'make' ),
						esc_html( print_r( $args['callback'], true ) ),
						esc_html( $view_id )
					)
				);
			}
		}

		return $return;
	}

	/**
	 * Remove a view definition, if it exists.
	 *
	 * @since 1.7.0.
	 *
	 * @param string $view_id
	 *
	 * @return bool
	 */
	public function remove_view( $view_id ) {
		if ( ! isset( $this->views[ $view_id ] ) ) {
			$this->error()->add_error( 'make_view_cannot_remove', sprintf( __( 'The "%s" view can\'t be removed because it doesn\'t exist.', 'make' ), esc_html( $view_id ) ) );
			return false;
		} else {
			unset( $this->views[ $view_id ] );
		}

		return true;
	}

	/**
	 * Get an array of complete view definitions, or a specific property of each one.
	 *
	 * If the view definition doesn't have the specified property, it will be omitted.
	 *
	 * @param string $property
	 *
	 * @return array
	 */
	public function get_views( $property = 'all' ) {
		if ( ! $this->is_loaded() ) {
			$this->load();
		}

		if ( 'all' === $property ) {
			return $this->views;
		}

		$views = array();

		foreach ( $this->views as $view_id => $properties ) {
			if ( isset( $properties[ $property ] ) ) {
				$views[ $view_id ] = $properties[ $property ];
			}
		}

		return $views;
	}

	/**
	 * Get a view definition array for a particular view.
	 *
	 * @since 1.7.0.
	 *
	 * @param string $view_id
	 *
	 * @return array
	 */
	public function get_view( $view_id ) {
		$view = array();

		if ( $this->view_exists( $view_id ) ) {
			$views = $this->get_views();
			$view = $views[ $view_id ];
		}

		return $view;
	}

	/**
	 * Get a sorted array of view definitions, based on the priority property.
	 *
	 * @since 1.7.0.
	 *
	 * @return array
	 */
	public function get_sorted_views() {
		$views = $this->get_views();
		$prioritizer = array();

		foreach ( $views as $view_id => $view_args ) {
			$priority = absint( $view_args['priority'] );

			if ( ! isset( $prioritizer[ $priority ] ) ) {
				$prioritizer[ $priority ] = array();
			}

			$prioritizer[ $priority ][ $view_id ] = $view_args;
		}

		ksort( $prioritizer );

		$sorted_views = array();

		foreach ( $prioritizer as $view_group ) {
			$sorted_views = array_merge( $sorted_views, $view_group );
		}

		return $sorted_views;
	}

	/**
	 * Check if a particular view exists.
	 *
	 * @since 1.7.0.
	 *
	 * @param string $view_id
	 *
	 * @return bool
	 */
	public function view_exists( $view_id ) {
		$views = $this->get_views();
		return isset( $views[ $view_id ] );
	}

	/**
	 * Get the label for a particular view, if it exists.
	 *
	 * @since 1.7.0.
	 *
	 * @param string $view_id
	 *
	 * @return string
	 */
	public function get_view_label( $view_id ) {
		$label = '';

		if ( $this->view_exists( $view_id ) ) {
			$view = $this->get_view( $view_id );
			$label = ( isset( $view['label'] ) ) ? $view['label'] : '';
		}

		return $label;
	}

	/**
	 * Get the name of the callback function used to test for a particular view.
	 *
	 * @since 1.7.0.
	 *
	 * @param string $view_id
	 * @param string $context
	 *
	 * @return null
	 */
	public function get_view_callback( $view_id, $context = '' ) {
		$callback = null;

		if ( $this->view_exists( $view_id ) ) {
			$view = $this->get_view( $view_id );

			if ( $context && isset( $view[ 'callback_' . $context ] ) ) {
				$callback = $view[ 'callback_' . $context ];
			} else if ( isset( $view['callback'] ) ) {
				$callback = $view['callback'];
			}
		}

		return $callback;
	}

	/**
	 * Determine the current view from the callbacks of each view definition.
	 *
	 * @since 1.7.0.
	 *
	 * @param string $context
	 *
	 * @return string|null
	 */
	public function get_current_view( $context = '' ) {
		// Make sure we're not doing it wrong.
		$action = ( is_admin() ) ? 'current_screen' : 'parse_query';
		$too_early = $action !== current_action() && ! did_action( $action );

		if ( $too_early ) {
			$this->compatibility()->doing_it_wrong(
				__FUNCTION__,
				sprintf(
					__( 'View cannot be accurately determined until during or after the %s action.', 'make' ),
					"<code>$action</code>"
				),
				'1.7.0'
			);

			return null;
		}

		$views = $this->get_sorted_views();
		$view = $this->default_view;

		foreach ( $views as $view_id => $view_args ) {
			$callback = $this->get_view_callback( $view_id, $context );

			if ( is_callable( $callback ) && true === call_user_func( $callback ) ) {
				$view = $view_id;
			}
		}

		// Check for deprecated filter.
		if ( has_filter( 'make_get_view' ) ) {
			$this->compatibility()->deprecated_hook(
				'make_get_view',
				'1.7.0',
				sprintf(
					wp_kses(
						__( 'To add or modify theme views, use the %1$s function instead. See the <a href="%2$s" target="_blank">View API documentation</a>.', 'make' ),
						array( 'a' => array( 'href' => true, 'target' => true ) )
					),
					'<code>make_update_view_definition()</code>',
					'https://thethemefoundry.com/docs/make-docs/code/apis/view-api/'
				)
			);

			/**
			 * Allow developers to dynamically change the view.
			 *
			 * @since 1.2.3.
			 * @deprecated 1.7.0.
			 *
			 * @param string    $view                The view name.
			 * @param string    $parent_post_type    The post type for the parent post of the current post.
			 */
			$view = apply_filters( 'make_get_view', $view, $this->get_parent_post_type( get_post() ) );
		}

		return $view;
	}

	/**
	 * Determine if the current view is "post".
	 *
	 * The "post" view includes the standard post along with all public custom post types and attachments that are
	 * children of these post types.
	 *
	 * @since 1.7.0.
	 *
	 * @return bool
	 */
	public function callback_post() {
		// Post types
		$post_types = get_post_types(
			array(
				'public' => true,
				'_builtin' => false
			)
		);
		$post_types[] = 'post';

		return is_singular( $post_types ) || ( is_attachment() && in_array( $this->get_parent_post_type( get_post() ), $post_types ) );
	}

	/**
	 * Determine if the current view is "page".
	 *
	 * The "page" view includes the page post type and attachments that are children of that post type.
	 *
	 * @since 1.7.0.
	 *
	 * @return bool
	 */
	public function callback_page() {
		return is_page() || ( is_attachment() && 'page' === $this->get_parent_post_type( get_post() ) );
	}

	/**
	 * Get the post type of a post's parent.
	 *
	 * @since 1.7.0.
	 *
	 * @param WP_Post|null $post
	 *
	 * @return false|string
	 */
	private function get_parent_post_type( WP_Post $post = null ) {
		if ( is_null( $post ) ) {
			return false;
		} else {
			return get_post_type( $post->post_parent );
		}
	}
}