<?php
/**
 * @package Make
 */

/**
 * Class MAKE_Builder_Setup
 *
 * @since 1.7.0.
 */
class MAKE_Builder_Setup extends MAKE_Util_Modules implements MAKE_Builder_SetupInterface, MAKE_Util_HookInterface {
	/**
	 * An associative array of required modules.
	 *
	 * @since 1.7.0.
	 *
	 * @var array
	 */
	protected $dependencies = array(
		'scripts' => 'MAKE_Setup_ScriptsInterface',
		'plus'    => 'MAKE_Plus_MethodsInterface',
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
	 * MAKE_Builder_Setup constructor.
	 *
	 * @since 1.7.0.
	 *
	 * @param MAKE_APIInterface|null $api
	 * @param array                  $modules
	 */
	public function __construct( MAKE_APIInterface $api, array $modules = array() ) {
		parent::__construct( $api, $modules );

		// Include the API
		require_once get_template_directory() . '/inc/builder/core/api.php';

		// Load backend files
		if ( is_admin() ) {
			require_once get_template_directory() . '/inc/builder/core/base.php';
		}
	}

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

		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_builder_scripts' ) );
		add_action( 'make_style_loaded', array( $this, 'builder_styles' ) );
		add_filter( 'make_get_section_data', array( $this, 'read_layout' ), 10, 2 );

		if ( is_admin() && ! $this->plus()->is_plus() ) {
			add_action( 'add_meta_boxes', array( $this, 'add_upsell_meta_box' ), 1 );
		}

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

	public function read_layout( $sections, $post_id ) {
		$layout = get_post_meta( $post_id, '_ttfmake_layout', true );

		if ( ! empty( $layout ) ) {
			$layout = maybe_unserialize( $layout );
			$sections = array();

			foreach ( $layout as $section_id ) {
				$section = get_post_meta( $post_id, "_ttfmake_section_{$section_id}", true );
				$section = maybe_unserialize( $section );
				$sections[] = $section;
			}
		}

		return $sections;
	}

	/**
	 * Register an upsell metabox for post builder support.
	 *
	 * @since 1.9.7.
	 *
	 * @hooked action add_meta_boxes
	 *
	 * @return void
	 */
	public function add_upsell_meta_box() {
		// Only show the builder upsell on posts
		if ( 'post' !== get_post_type() ) {
			return;
		}

		add_meta_box(
			'ttfmake-builder-toggle-upsell',
			esc_html__( 'Post Builder', 'make' ),
			array( $this, 'display_builder_toggle_upsell' ),
			'post',
			'side',
			'high'
		);
	}

	/**
	 * Render the upsell metabox for post builder support.
	 *
	 * @since 1.9.7.
	 *
	 * @return void
	 */
	public function display_builder_toggle_upsell() {
		?>
		<p>
			<input type="checkbox" disabled />
			&nbsp;<label for="use-builder"><?php esc_html_e( 'Enable post builder', 'make' ); ?></label>
		</p>
		<p style="color: #666;">
			<em><?php _e( 'Did you know: with Make Plus you can use the Make builder on posts, too.', 'make'  ) ?></em>
		<p>
		<p>
			<a href="<?php echo esc_url( 'https://thethemefoundry.com/wordpress-themes/make/?utm_source=app&utm_campaign=post-builder#get-started' ); ?>" target="_blank" class="button button-primary button-large"><?php _e( 'Upgrade to Make Plus Now', 'make' ); ?></a>
		</p>
		<?php
	}

	/**
	 * Handle frontend scripts for use with the existing sections on the current Builder page.
	 *
	 * @since 1.6.1.
	 *
	 * @hooked action wp_enqueue_scripts
	 *
	 * @return void
	 */
	public function frontend_builder_scripts() {
		if ( ttfmake_is_builder_page() ) {
			$sections = ttfmake_get_post_section_data( get_the_ID() );
			// Bail if there are no sections
			if ( empty( $sections ) ) {
				return;
			}
			// Parse the sections included on the page.
			$section_types = wp_list_pluck( $sections, 'section-type' );
			foreach ( $section_types as $section_id => $section_type ) {
				switch ( $section_type ) {
					default :
						break;
					case 'banner' :
					case 'postlist' :
					case 'productgrid' :
						// Add Cycle2 as a dependency for the Frontend script
						$this->scripts()->add_dependency( 'make-frontend', 'cycle2', 'script' );
						if ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) {
							$this->scripts()->add_dependency( 'make-frontend', 'cycle2-center', 'script' );
							$this->scripts()->add_dependency( 'make-frontend', 'cycle2-swipe', 'script' );
						}
						break;
				}
			}
		}
	}

	/**
	 * Trigger an action hook for each section on a Builder page for the purpose
	 * of adding section-specific CSS rules to the document head.
	 *
	 * @since 1.4.5
	 *
	 * @hooked action make_style_loaded
	 *
	 * @param MAKE_Style_ManagerInterface $style    The style manager instance.
	 *
	 * @return void
	 */
	public function builder_styles( MAKE_Style_ManagerInterface $style ) {
		if ( ttfmake_is_builder_page() ) {
			$sections = ttfmake_get_post_section_data( get_the_ID() );
			if ( ! empty( $sections ) ) {
				foreach ( $sections as $id => $data ) {
					if ( isset( $data['section-type'] ) ) {
						/**
						 * Allow section-specific CSS rules to be added to the document head of a Builder page.
						 *
						 * @since 1.4.5
						 * @since 1.7.0. Added the $style parameter.
						 *
						 * @param array                       $data     The Builder section's data.
						 * @param int                         $id       The ID of the Builder section.
						 * @param MAKE_Style_ManagerInterface $style    The style manager instance.
						 */
						do_action( 'make_builder_' . $data['section-type'] . '_css', $data, $id, $style );
					}
				}
			}
		}
	}
}

/**
 * Global Builder functions
 */

if ( ! function_exists( 'ttfmake_get_post_section_data' ) ) :
/**
 * Retrieve all of the data for the sections.
 *
 * @since  1.8.12.
 *
 * @param  string    $post_id        The post to retrieve the data from.
 * @param  string    $section_id     The optional section_id to retrieve data for.
 * @return array                     The combined data.
 */
function ttfmake_get_post_section_data( $post_id, $section_id = false ) {
	$ordered_data = array();
	$ids          = get_post_meta( $post_id, '_ttfmake-section-ids', true );
	$ids          = ( ! empty( $ids ) && is_array( $ids ) ) ? array_map( 'strval', $ids ) : $ids;
	$post_meta    = get_post_meta( $post_id );

	// Temp array of hashed keys
	$temp_data = array();

	// Any meta containing the old keys should be deleted
	if ( is_array( $post_meta ) ) {
		foreach ( $post_meta as $key => $value ) {
			// Only consider builder values
			if ( 0 === strpos( $key, '_ttfmake:' ) ) {
				// Get the individual pieces
				$temp_data[ str_replace( '_ttfmake:', '', $key ) ] = $value[0];
			}
		}
	}

	// Create multidimensional array from postmeta
	$data = ttfmake_create_array_from_meta_keys( $temp_data );

	// Reorder the data in the order specified by the section IDs
	if ( is_array( $ids ) ) {
		foreach ( $ids as $id ) {
			if ( isset( $data[ $id ] ) ) {
				$ordered_data[ $id ] = $data[ $id ];
			}
		}
	}


	if ( ! isset( $GLOBALS['ttfmake_sections'] ) ) {
		/**
		 * Filter the section data for a post.
		 *
		 * @since 1.2.3.
		 *
		 * @param array    $ordered_data    The array of section data.
		 * @param int      $post_id         The post ID for the retrieved data.
		 */
		$ttfmake_sections = apply_filters( 'make_get_section_data', $ordered_data, $post_id );
	} else {
		global $ttfmake_sections;
	}

	if ( false !== $section_id ) {
		foreach( $ttfmake_sections as $section_data ) {
			if ( strval( $section_id ) === $section_data['id'] ) {
				return $section_data;
			}
		}

		return false;
	}

	return $ttfmake_sections;
}
endif;

if ( ! function_exists( 'ttfmake_create_array_from_meta_keys' ) ) :
/**
 * Convert an array with array keys that map to a multidimensional array to the array.
 *
 * @since  1.2.0.
 *
 * @param  array    $arr    The array to convert.
 * @return array            The converted array.
 */
function ttfmake_create_array_from_meta_keys( $arr ) {
	// The new multidimensional array we will return
	$result = array();

	// Process each item of the input array
	foreach ( $arr as $key => $value ) {
		// Store a reference to the root of the array
		$current = & $result;

		// Split up the current item's key into its pieces
		$pieces = explode( ':', $key );

		/**
		 * For all but the last piece of the key, create a new sub-array (if necessary), and update the $current
		 * variable to a reference of that sub-array.
		 */
		for ( $i = 0; $i < count( $pieces ) - 1; $i++ ) {
			$step = $pieces[ $i ];
			if ( ! isset( $current[ $step ] ) ) {
				$current[ $step ] = array();
			}
			$current = & $current[ $step ];
		}

		// Add the current value into the final nested sub-array
		$current[ $pieces[ $i ] ] = $value;
	}

	// Return the result array
	return $result;
}
endif;

if ( ! function_exists( 'ttfmake_post_type_supports_builder' ) ) :
/**
 * Check if a post type supports the Make builder.
 *
 * @since  1.2.0.
 *
 * @param  string    $post_type    The post type to test.
 * @return bool                    True if the post type supports the builder; false if it does not.
 */
function ttfmake_post_type_supports_builder( $post_type ) {
	return post_type_supports( $post_type, 'make-builder' );
}
endif;

if ( ! function_exists( 'ttfmake_is_builder_page' ) ) :
/**
 * Determine if the post uses the builder or not.
 *
 * @since  1.2.0.
 *
 * @param  int     $post_id    The post to inspect.
 * @return bool                True if builder is used for post; false if it is not.
 */
function ttfmake_is_builder_page( $post_id = 0 ) {
	if ( empty( $post_id ) ) {
		$post_id = get_the_ID();
	}

	// Pages will use the template-builder.php template to denote that it is a builder page
	$has_builder_template = ( 'template-builder.php' === get_page_template_slug( $post_id ) );

	// Other post types will use meta data to support builder pages
	$has_builder_meta = ( 1 === (int) get_post_meta( $post_id, '_ttfmake-use-builder', true ) );

	$is_builder_page = $has_builder_template || $has_builder_meta;

	/**
	 * Allow a developer to dynamically change whether the post uses the builder or not.
	 *
	 * @since 1.2.3
	 *
	 * @param bool    $is_builder_page    Whether or not the post uses the builder.
	 * @param int     $post_id            The ID of post being evaluated.
	 */
	return apply_filters( 'make_is_builder_page', $is_builder_page, $post_id );
}
endif;
