<?php
/**
 * @package Make
 */

/**
 * Class MAKE_Logo_Methods
 *
 * Acts as a compatibility switchboard between Make's legacy custom logo settings and the Core custom logo
 * introduced in WordPress 4.5.
 *
 * @since 1.7.0.
 */
final class MAKE_Logo_Methods extends MAKE_Util_Modules implements MAKE_Logo_MethodsInterface, MAKE_Util_HookInterface {
	/**
	 * An associative array of required modules.
	 *
	 * @since 1.7.0.
	 *
	 * @var array
	 */
	protected $dependencies = array(
		'thememod' => 'MAKE_Settings_ThemeModInterface',
		'sanitize' => 'MAKE_Settings_SanitizeInterface',
		'legacy'   => 'MAKE_Logo_LegacyInterface',
	);

	/**
	 * The default max width of the logo, in pixels
	 *
	 * @since 1.7.0.
	 *
	 * @var int
	 */
	private $default_max_width = 960;

	/**
	 * Indicator of whether the hook routine has been run.
	 *
	 * @since 1.7.0.
	 *
	 * @var bool
	 */
	private static $hooked = false;

	/**
	 * MAKE_Logo_Methods constructor.
	 *
	 * @since 1.7.0.
	 *
	 * @param MAKE_APIInterface|null $api
	 * @param array                  $modules
	 */
	public function __construct( MAKE_APIInterface $api = null, array $modules = array() ) {
		// Module defaults.
		$modules = wp_parse_args( $modules, array(
			'legacy' => 'MAKE_Logo_Legacy',
		) );

		/**
		 * Filter: Switch to prevent legacy logo functionality from loading.
		 *
		 * @since 1.7.0.
		 *
		 * @param bool $load_legacy
		 */
		$load_legacy = apply_filters( 'make_logo_load_legacy', true );

		if ( false === $load_legacy ) {
			unset( $this->dependencies['legacy'] );
		}

		// Load dependencies
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

		// Logo max width
		add_action( 'make_style_loaded', array( $this, 'set_logo_max_width' ) );

		// Logo conversion
		add_action( 'after_setup_theme', array( $this, 'convert_old_logo_settings' ), 30 );

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
	 * Test for support of the custom logo functionality introduced into Core in 4.5.
	 *
	 * @since 1.7.0.
	 *
	 * @return bool
	 */
	public function custom_logo_is_supported() {
		return function_exists( 'has_custom_logo' ) && current_theme_supports( 'custom-logo' );
	}

	/**
	 * Wrapper function to support custom logo detection either via Core or the legacy theme functionality.
	 *
	 * @since 1.7.0.
	 *
	 * @return bool
	 */
	public function has_logo() {
		if ( $this->custom_logo_is_supported() ) {
			return has_custom_logo();
		} else if ( $this->has_module( 'legacy' ) ) {
			return $this->legacy()->has_logo();
		} else {
			return false;
		}
	}

	/**
	 * Wrapper function to support getting the custom logo markup either via Core or the legacy theme functionality.
	 *
	 * @since 1.7.0.
	 *
	 * @return string
	 */
	public function get_logo() {
		if ( $this->custom_logo_is_supported() && function_exists( 'get_custom_logo' ) ) {
			return get_custom_logo();
		} else {
			ob_start();
			?>
			<div class="custom-logo">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"></a>
			</div>
		<?php
			return ob_get_clean();
		}
	}

	/**
	 * Getter that allows the default max width value to be filtered.
	 *
	 * @since 1.7.0.
	 *
	 * @return int
	 */
	private function get_logo_max_width() {
		// Max width
		$width = $this->default_max_width;

		// Check for deprecated filter.
		if ( has_filter( 'ttfmake_custom_logo_max_width' ) ) {
			$this->compatibility()->deprecated_hook(
				'ttfmake_custom_logo_max_width',
				'1.7.0',
				sprintf(
					esc_html__( 'Use the %s hook instead.', 'make' ),
					'<code>make_logo_max_width</code>'
				)
			);

			/**
			 * Filter the maximum allowable width for a custom logo.
			 *
			 * @since 1.0.0.
			 * @deprecated 1.7.0.
			 *
			 * @param int $width    The maximum width, in pixels.
			 */
			$width = apply_filters( 'ttfmake_custom_logo_max_width', $width );
		}

		/**
		 * Filter the maximum allowable width for a custom logo.
		 *
		 * @since 1.7.0.
		 *
		 * @param int $width    The maximum width, in pixels.
		 */
		return apply_filters( 'make_logo_max_width', $width );
	}

	/**
	 * Add a style rule for the max width of the custom logo, if necessary.
	 *
	 * @since 1.7.0.
	 *
	 * @hooked action make_style_loaded
	 *
	 * @param MAKE_Style_ManagerInterface $style
	 *
	 * @return void
	 */
	public function set_logo_max_width( MAKE_Style_ManagerInterface $style ) {
		$max_width = absint( $this->get_logo_max_width() );

		if ( $this->custom_logo_is_supported() && $this->has_logo() && $this->default_max_width !== $max_width ) {
			$style->css()->add( array(
				'selectors' => 'img.custom-logo',
				'declarations' => array(
					'max-width' => "{$max_width}px",
					'width'     => '100%',
				),
			) );
		}
	}

	/**
	 * Convert the theme's legacy custom logo mods to the Core version.
	 *
	 * Since Core relies on one attachment for all sizes of the logo, this converter looks for a retina-sized
	 * logo first, and falls back on the regular. This helps to ensure that the largest image size becomes
	 * the custom logo attachment.
	 *
	 * @since 1.7.0.
	 *
	 * @hooked action after_setup_theme
	 *
	 * @return void
	 */
	public function convert_old_logo_settings() {
		// Don't bother if the Core custom logo isn't supported
		if ( ! $this->custom_logo_is_supported() ) {
			return;
		}

		// Bail if this conversion process has already been run or a Core custom logo has already been set.
		if ( true === get_theme_mod( 'make-logo-converted' ) || false !== get_theme_mod( 'custom_logo', false ) ) {
			return;
		}

		$logo_value = '';

		if ( false !== $retina = get_theme_mod( 'logo-retina', false ) ) {
			$logo_value = $this->sanitize()->sanitize_image( $retina, true );
		} else if ( false !== $regular = get_theme_mod( 'logo-regular', false ) ) {
			$logo_value = $this->sanitize()->sanitize_image( $regular, true );
		}

		if ( is_int( $logo_value ) && $logo_value > 0 ) {
			set_theme_mod( 'custom_logo', $logo_value );
		}

		// Add a flag that the conversion has been run.
		set_theme_mod( 'make-logo-converted', true );
	}
}