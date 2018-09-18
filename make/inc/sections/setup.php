<?php
/**
 * @package Make
 */

/**
 * Class MAKE_Sections_Setup
 *
 * @since 1.7.0.
 */
class MAKE_Sections_Setup extends MAKE_Util_Modules implements MAKE_Sections_SetupInterface, MAKE_Util_HookInterface {
	/**
	 * An associative array of required modules.
	 *
	 * @since 1.7.0.
	 *
	 * @var array
	 */
	protected $dependencies = array();

	/**
	 * Indicator of whether the hook routine has been run.
	 *
	 * @since 1.7.0.
	 *
	 * @var bool
	 */
	private static $hooked = false;

	/**
	 * TODO
	 */
	protected $makeplus_link = 'https://thethemefoundry.com/wordpress-themes/make/#get-started';

	/**
	 * MAKE_Sections_Setup constructor.
	 *
	 * @since 1.7.0.
	 *
	 * @param MAKE_APIInterface|null $api
	 * @param array                  $modules
	 */
	public function __construct( MAKE_APIInterface $api, array $modules = array() ) {
		parent::__construct( $api, $modules );
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

		// Register base sections
		add_action( 'after_setup_theme', array( $this, 'register_sections'), 11 );

		// Add base sections styles
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

		// Hooking has occurred.
		self::$hooked = true;
	}

	public function register_sections() {
		MAKE_Sections_Columns_Definition::register();
		MAKE_Sections_Banner_Definition::register();
		MAKE_Sections_Gallery_Definition::register();

		if ( is_admin() ) {
			add_filter( 'make_section_settings', array( $this, 'full_width_settings' ), 30, 2 );
			add_filter( 'make_sections_defaults', array( $this, 'full_width_defaults' ) );
			add_filter( 'make_prepare_data_section', array( $this, 'full_width_save_data' ), 20, 2 );

			// Upsells
			if ( ! Make()->plus()->is_plus() ) {
				add_filter( 'make_section_settings', array( $this, 'master_demo_setting' ), 40, 2 );
				add_filter( 'make_section_settings', array( $this, 'section_draft_demo_setting' ), 50, 2 );
				add_filter( 'make_section_settings', array( $this, 'section_code_demo_setting' ), 60, 2 );
			}
		}
	}

	/**
	 * Add a Full Width setting checkbox
	 *
	 * @since 1.6.0.
	 *
	 * @hooked filter make_section_settings
	 *
	 * @param array $args    The section args.
	 *
	 * @return array         The modified section args.
	 */
	public function full_width_settings( $settings, $section_type ) {
		if ( ! in_array( $section_type, array(
			'text', 'banner', 'gallery', 'panels', 'postlist', 'productgrid', 'downloads'
			) ) ) {
			return $settings;
		}
		$index = array_search( 'divider-background', wp_list_pluck( $settings, 'name' ) );
		$settings[$index - 25] = array(
			'type'    => 'checkbox',
			'label'   => __( 'Full width', 'make' ),
			'name'    => 'full-width',
			'default' => ttfmake_get_section_default( 'full-width', $section_type ),
		);
		return $settings;
	}

	/**
	 * TODO
	 */
	public function full_width_defaults( $defaults ) {
		foreach ( $defaults as $section_id => $section_defaults ) {
			if ( ! in_array( $section_defaults['section-type'], array(
				'text', 'banner', 'gallery', 'panels', 'postlist', 'productgrid', 'downloads'
				) ) ) {
				continue;
			}
			$defaults[$section_id]['full-width'] = 0;
		}
		return $defaults;
	}
	/**
	 * TODO
	 */
	public function full_width_save_data( $clean_data, $original_data ) {
		if ( isset( $original_data['full-width'] ) && $original_data['full-width'] == 1 ) {
			$clean_data['full-width'] = 1;
		} else {
			$clean_data['full-width'] = 0;
		}
		return $clean_data;
	}

	/**
	 * TODO
	 */
	public function master_demo_setting( $settings, $section_type ) {
		if ( ! in_array( $section_type, array(
			'text', 'banner', 'gallery', 'panels', 'postlist', 'productgrid', 'downloads'
			) ) ) {
			return $settings;
		}
		$index = max( array_keys( $settings ) );
		$settings[$index + 100] = array(
			'type' => 'divider',
			'label' => __( 'Master', 'make' ),
			'name' => 'divider-master',
			'class' => 'ttfmake-configuration-divider',
		);
		$settings[$index + 125] = array(
			'type' => 'description',
			'label' => __( 'Master', 'make' ),
			'name' => 'master',
			'description' => '<p>' . __( 'Did you know: Master mode lets you add this section to other pages, or parts of pages, and changes you make will apply everywhere this section is used.', 'make'  ) . '</p><p><a href="' . esc_js( $this->makeplus_link ) . '" target="_blank">' . __( 'Upgrade to Make Plus to get Master mode.', 'make' ) . '</a></p>',
		);
		return $settings;
	}
	/**
	 * TODO
	 */
	public function section_draft_demo_setting( $settings, $section_type ) {
		if ( ! in_array( $section_type, array(
			'text', 'banner', 'gallery', 'panels', 'postlist', 'productgrid', 'downloads'
			) ) ) {
			return $settings;
		}
		$index = array_search( 'divider-background', wp_list_pluck( $settings, 'name' ) );
		$settings[$index - 50] = array(
			'type'        => 'checkbox',
			'label'       => __( 'Draft mode', 'make' ),
			'name'        => 'draft',
			'default'     => '',
			'disabled'    => true,
			'description' => '<p>' . __( 'Did you know: When Draft mode is enabled, the section is hidden from the front-end, so you can continue to edit it without your readers seeing it.', 'make' ) . '</p><p><a href="' . esc_js( $this->makeplus_link ) . '" target="_blank">' . __( 'Upgrade to Make Plus to get Draft mode.', 'make' ) . '</a></p>'
		);
		return $settings;
	}
	/**
	 * TODO
	 */
	public function section_code_demo_setting( $settings, $section_type ) {
		if ( ! in_array( $section_type, array(
			'text', 'banner', 'gallery', 'panels', 'postlist', 'productgrid', 'downloads'
			) ) ) {
			return $settings;
		}
		$index = max( array_keys( $settings ) );
		$settings[ $index + 50 ] = array(
			'type'	  => 'divider',
			'name'	  => 'divider-code',
			'label'	  => __( 'Code', 'make-plus' ),
			'class'	  => 'ttfmake-configuration-divider'
		);
		$settings[ $index + 100 ] = array(
			'type' => 'description',
			'label' => __( 'Master', 'make' ),
			'name' => 'master',
			'description' => '<p>' . __( 'Did you know: With Make Plus you can add custom classes and IDs to this section so applying custom CSS styles is easy.', 'make'  ) . '</p><p><a href="' . esc_js( $this->makeplus_link ) . '" target="_blank">' . __( 'Upgrade to Make Plus to get custom classes and IDs.', 'make' ) . '</a></p>',
		);
		return $settings;
	}

	/**
	 * Enqueue base section styles.
	 *
	 * @since  1.8.12.
	 *
	 * @param  string    $hook_suffix    The suffix for the screen.
	 * @return void
	 */
	public function admin_enqueue_scripts( $hook_suffix ) {
		// Only load resources if they are needed on the current page
		if ( ! in_array( $hook_suffix, array( 'post.php', 'post-new.php' ) ) || ! ttfmake_post_type_supports_builder( get_post_type() ) ) {
			return;
		}

		// Add the section CSS
		wp_enqueue_style(
			'ttfmake-sections/css/sections.css',
			Make()->scripts()->get_css_directory_uri() . '/builder/sections/sections.css',
			array(),
			TTFMAKE_VERSION,
			'all'
		);
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
}
