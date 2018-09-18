<?php
/**
 * @package Make
 */

/**
 * Class MAKE_Plus_Methods
 *
 * @since 1.7.0.
 */
final class MAKE_Plus_Methods implements MAKE_Plus_MethodsInterface, MAKE_Util_HookInterface {
	/**
	 * Whether Make Plus is installed and active.
	 *
	 * @since 1.7.0.
	 *
	 * @var bool
	 */
	private $plus = null;

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

		// Admin notices
		add_action( 'make_notice_loaded', array( $this, 'admin_notices' ) );

		// Admin body classes
		add_filter( 'admin_body_class', array( $this, 'admin_body_classes' ) );

		// Add info
		if ( ! $this->is_plus() && $this->can_add_plus() ) {
			// Customizer info
			add_action( 'customize_controls_print_footer_scripts', array( $this, 'customizer_add_header_info' ) );
			add_action( 'customize_register', array( $this, 'customizer_add_section_info' ), 99 );

			// Duplicate info
			add_action( 'post_submitbox_misc_actions', array( $this, 'duplicate_add_info' ) );

			// Per Page info
			add_action( 'add_meta_boxes', array( $this, 'perpage_add_info' ) );

			// Sections info
			add_action( 'make_after_builder_menu', array( $this, 'sections_add_info' ) );

			// Widget area info
			add_action( 'make_section_text_before_columns_select', array( $this, 'widgetarea_add_info' ) );
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

	/**
	 * Check to see if Make Plus is active.
	 *
	 * @since 1.7.0.
	 *
	 * @return bool
	 */
	public function is_plus() {
		if ( ! is_null( $this->plus ) ) {
			return $this->plus;
		}

		$is_plus = false;

		// Look for active plugin
		$relative_path = 'make-plus/make-plus.php';
		if ( is_multisite() ) {
			$active_network_plugins = (array) get_site_option( 'active_sitewide_plugins' );
			if ( isset( $active_network_plugins[ $relative_path ] ) ) {
				$is_plus = true;
			}
		} else {
			$active_plugins = (array) get_option( 'active_plugins' );
			if ( in_array( $relative_path, $active_plugins ) ) {
				$is_plus = true;
			}
		}

		// Look for API
		if ( false === $is_plus && function_exists( 'MakePlus' ) ) {
			$is_plus = true;
		}

		// Look for deprecated class
		if ( false === $is_plus && class_exists( 'TTFMP_App' ) ) {
			$is_plus = true;
		}

		/**
		 * Filter: Modify the status of Make Plus.
		 *
		 * @since 1.2.3.
		 *
		 * @param bool $is_plus    True if Make Plus is active.
		 */
		$this->plus = apply_filters( 'make_is_plus', $is_plus );

		return $this->plus;
	}

	/**
	 * Shortcut to determine if the current user is capable of adding Make Plus to the site.
	 *
	 * @since 1.7.0.
	 *
	 * @return bool
	 */
	private function can_add_plus() {
		return current_user_can( 'install_plugins' );
	}

	/**
	 * Generate a link to the Make Plus info page.
	 *
	 * @since 1.0.6.
	 *
	 * @return string
	 */
	public function get_plus_link() {
		return 'https://thethemefoundry.com/wordpress-themes/make/#get-started';
	}

	/**
	 * Get the version of Make Plus currently running.
	 *
	 * @since 1.7.0.
	 *
	 * @return string|null
	 */
	public function get_plus_version() {
		$version = null;

		if ( true === $this->is_plus() ) {
			if ( defined( 'MAKEPLUS_VERSION' ) && MAKEPLUS_VERSION ) {
				$version = MAKEPLUS_VERSION;
			} else if ( function_exists( 'ttfmp_get_app' ) ) {
				$version = ttfmp_get_app()->version;
			}
		}

		return $version;
	}

	/**
	 * Add admin notices related to Make Plus.
	 *
	 * @since 1.7.0.
	 *
	 * @hooked action make_notice_loaded
	 *
	 * @param MAKE_Admin_NoticeInterface $notice
	 *
	 * @return void
	 */
	public function admin_notices( MAKE_Admin_NoticeInterface $notice ) {
		// Notice to help with potential update issues with Make Plus
		if ( true === $this->is_plus() && version_compare( $this->get_plus_version(), '1.4.7', '<=' ) ) {
			$notice->register_admin_notice(
				'make-plus-lte-147',
				sprintf(
					__( 'A new version of Make Plus is available. If you encounter problems updating through <a href="%1$s">the WordPress interface</a>, please <a href="%2$s" target="_blank">follow these steps</a> to update manually.', 'make' ),
					admin_url( 'update-core.php' ),
					'https://thethemefoundry.com/tutorials/updating-your-existing-theme/'
				),
				array(
					'cap'     => 'update_plugins',
					'dismiss' => true,
					'screen'  => array( 'dashboard', 'update-core.php', 'plugins.php' ),
					'type'    => 'warning',
				)
			);
		} else if ( true === $this->is_plus() && version_compare( $this->get_plus_version(), '1.7.0', '<' ) ) {
			$notice->register_admin_notice(
				'make-plus-lt-170',
				sprintf(
					__( 'The current version of the Make theme is only fully compatible with version 1.7.0 or higher of the Make Plus plugin. Please update Make Plus to the latest version.', 'make' )
				),
				array(
					'cap'     => 'update_plugins',
					'dismiss' => true,
					'screen'  => array( 'dashboard', 'update-core.php', 'plugins.php' ),
					'type'    => 'warning',
				)
			);
		}
	}

	/**
	 * Add a class to the <body> tag on Admin screens indicating whether Make Plus is active.
	 *
	 * Unlike the `body_class` filter, `admin_body_class` is a space-separated string rather than an array.
	 *
	 * @since 1.7.0.
	 *
	 * @hooked filter admin_body_class
	 *
	 * @param string $classes
	 *
	 * @return string
	 */
	public function admin_body_classes( $classes ) {
		if ( $this->is_plus() ) {
			$classes .= ' make-plus-enabled';
		} else {
			$classes .= ' make-plus-disabled';
		}

		return $classes;
	}

	/**
	 * Display Make Plus info in the Customizer controls header.
	 *
	 * @since 1.7.0.
	 *
	 * @hooked action customize_controls_print_footer_scripts
	 *
	 * @return void
	 */
	public function customizer_add_header_info() {
		?>
		<script type="application/javascript">
			(function($) {
				$(document).ready(function() {
					var plus = '<div class="ttfmake-customize-plus"><p><?php esc_html_e( 'Looking for more style controls?', 'make' ); ?></p><a href="<?php echo esc_js( $this->get_plus_link() ); ?>" target="_blank"><?php esc_html_e( 'Upgrade to Make Plus', 'make' ); ?></a></div>';
					$('#accordion-section-themes .accordion-section-title .change-theme').before(plus);
					// Remove accordion click event
					$('.ttfmake-customize-plus').on('click', function(e) {
						e.stopPropagation();
					});
				});
			})(jQuery);
		</script>
	<?php
	}

	/**
	 * Display information about Typekit and White Label in the Customizer.
	 *
	 * @since 1.7.0.
	 *
	 * @hooked action customize_register
	 *
	 * @param WP_Customize_Manager $wp_customize
	 *
	 * @return void
	 */
	public function customizer_add_section_info( WP_Customize_Manager $wp_customize ) {
		// Add section for Typekit
		$wp_customize->add_section( 'ttfmake_font-typekit', array(
			'panel'       => 'ttfmake_typography',
			'title'       => __( 'Typekit', 'make' ),
			'description' => __( 'Looking to add premium fonts from Typekit to your website?', 'make' ),
			'priority'    => $wp_customize->get_section( 'ttfmake_font-google' )->priority + 2
		) );

		// Add control for Typekit
		$wp_customize->add_control( new MAKE_Customizer_Control_Html( $wp_customize, 'ttfmake_font-typekit-update-text', array(
			'section'     => 'ttfmake_font-typekit',
			'description'  => sprintf(
				'<a href="%1$s" target="_blank">%2$s</a>',
				esc_url( $this->get_plus_link() ),
				esc_html__( 'Upgrade to Make Plus.', 'make' )
			),
		) ) );

		// Add section for White Label
		$wp_customize->add_section( 'ttfmake_white-label', array(
			'panel'       => 'ttfmake_general',
			'title'       => __( 'White Label', 'make' ),
			'description' => __( 'Want to remove the theme byline from your website&#8217;s footer?', 'make' ),
			'priority'    => $wp_customize->get_section( 'ttfmake_social' )->priority + 2
		) );

		// Add control for White Label
		$wp_customize->add_control( new MAKE_Customizer_Control_Html( $wp_customize, 'ttfmake_footer-white-label-text', array(
			'section'     => 'ttfmake_white-label',
			'description'  => sprintf(
				'<a href="%1$s" target="_blank">%2$s</a>',
				esc_url( $this->get_plus_link() ),
				esc_html__( 'Upgrade to Make Plus.', 'make' )
			),
		) ) );
	}

	/**
	 * Display information about duplicating posts.
	 *
	 * @since  1.1.0.
	 *
	 * @hooked action post_submitbox_misc_actions
	 *
	 * @return void
	 */
	public function duplicate_add_info() {
		global $typenow;

		if ( 'page' === $typenow ) : ?>
			<div class="misc-pub-section ttfmake-duplicator">
				<p style="font-style:italic;margin:0 0 7px 3px;">
					<?php
					printf(
						esc_html__( 'Duplicate this page with %s.', 'make' ),
						sprintf(
							'<a href="%1$s" target="_blank">%2$s</a>',
							esc_url( $this->get_plus_link() ),
							'Make Plus'
						)
					);
					?>
				</p>
				<div class="clear"></div>
			</div>
		<?php endif;
	}

	/**
	 * Add a metabox to each qualified post type edit screen.
	 *
	 * @since 1.0.6.
	 *
	 * @hooked action add_meta_boxes
	 *
	 * @return void
	 */
	public function perpage_add_info() {
		// Post types
		$post_types = get_post_types(
			array(
				'public' => true,
				'_builtin' => false
			)
		);
		$post_types[] = 'post';
		$post_types[] = 'page';

		// Add the metabox if compatible with the current post type
		if ( in_array( get_post_type(), $post_types ) ) {
			add_meta_box(
				'ttfmake-plus-metabox',
				esc_html__( 'Layout Settings', 'make' ),
				array( $this, 'perpage_render_metabox' ),
				get_post_type(),
				'side',
				'default'
			);
		}
	}

	/**
	 * Render the metabox with information about per-page layout options.
	 *
	 * @since 1.0.6.
	 *
	 * @param WP_Post $post    The current post object.
	 *
	 * @return void
	 */
	public function perpage_render_metabox( WP_Post $post ) {
		// Get the post type label
		$post_type = get_post_type_object( $post->post_type );
		$label = ( isset( $post_type->labels->singular_name ) ) ? $post_type->labels->singular_name : __( 'Post', 'make' );

		echo '<p class="howto">';
		printf(
			esc_html__( 'Looking to configure a unique layout for this %1$s? %2$s', 'make' ),
			esc_html( strtolower( $label ) ),
			sprintf(
				'<a href="%1$s" target="_blank">%2$s</a>',
				esc_url( $this->get_plus_link() ),
				esc_html__( 'Upgrade to Make Plus.', 'make' )
			)
		);
		echo '</p>';
	}

	/**
	 * Display info about additional Builder sections.
	 *
	 * @since 1.7.0.
	 *
	 * @hooked action make_after_builder_menu
	 *
	 * @return void
	 */
	public function sections_add_info() {
		?>
		<li id="ttfmake-menu-list-item-link-plus" class="ttfmake-menu-list-item">
			<div>
				<h4><?php esc_html_e( 'Get more.', 'make' ); ?></h4>
				<p class="howto">
					<?php
					printf(
						esc_html__( 'Looking for more sections and options? %s', 'make' ),
						sprintf(
							'<a href="%1$s" target="_blank">%2$s</a>',
							$this->get_plus_link(),
							esc_html__( 'Upgrade to Make Plus.', 'make' )
						)
					);
					?>
				</p>
			</div>
		</li>
	<?php
	}

	/**
	 * Display info about Columns section widget areas.
	 *
	 * @since 1.7.0.
	 *
	 * @hooked action make_section_text_before_columns_select
	 *
	 * @return void
	 */
	public function widgetarea_add_info() {
		?>
		<div class="ttfmake-plus-info">
			<p>
				<em>
					<?php
					printf(
						esc_html__( 'Convert any column into an area for widgets. %s', 'make' ),
						sprintf(
							'<a href="%1$s" target="_blank">%2$s</a>',
							esc_url( $this->get_plus_link() ),
							esc_html__( 'Upgrade to Make Plus.', 'make' )
						)
					);
					?>
				</em>
			</p>
		</div>
	<?php
	}
}