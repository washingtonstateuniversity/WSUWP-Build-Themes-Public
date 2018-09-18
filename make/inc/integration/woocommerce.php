<?php
/**
 * @package Make
 */

/**
 * Class MAKE_Integration_WooCommerce
 *
 * Modifications to better integrate Make and WooCommerce.
 *
 * @since 1.7.0.
 */
final class MAKE_Integration_WooCommerce extends MAKE_Util_Modules implements MAKE_Util_HookInterface {
	/**
	 * An associative array of required modules.
	 *
	 * @since 1.7.0.
	 *
	 * @var array
	 */
	protected $dependencies = array(
		'widgets' => 'MAKE_Setup_WidgetsInterface',
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

		// Set up integration
		add_action( 'after_setup_theme', array( $this, 'setup' ) );

		// Before main content
		add_action( 'woocommerce_before_main_content', array( $this, 'before_main_content' ) );

		// After main content
		add_action( 'woocommerce_after_main_content', array( $this, 'after_main_content' ) );

		// Backcompat with old head actions
		add_action( 'make_deprecated_function_run', array( $this, 'backcompat_main_content_actions' ) );

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
	 * Add theme support and remove default action hooks so we can replace them with our own.
	 *
	 * @since 1.0.0.
	 *
	 * @hooked action after_setup_theme
	 *
	 * @return void
	 */
	function setup() {
		// Theme support
		add_theme_support( 'woocommerce' );
		// Adding support for WooCommerce 3.0 product galleries
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );

		// Content wrapper
		remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper' );
		remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end' );

		// Sidebar
		remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar' );
	}

	/**
	 * Markup to show before the main WooCommerce content.
	 *
	 * @since 1.0.0.
	 *
	 * @hooked action woocommerce_before_main_content
	 *
	 * @return void
	 */
	function before_main_content() {
		// Left sidebar
		ttfmake_maybe_show_sidebar( 'left' );

		// Begin content wrapper
		?>
		<main id="site-main" class="site-main" role="main">
		<?php
	}

	/**
	 * Markup to show after the main WooCommerce content
	 *
	 * @since 1.0.0.
	 *
	 * @hooked action woocommerce_after_main_content
	 *
	 * @return void
	 */
	function after_main_content() {
		// End content wrapper
		?>
		</main>
		<?php
		// Right sidebar
		ttfmake_maybe_show_sidebar( 'right' );
	}

	/**
	 * Backcompat for deprecated pluggable functions hooked to woocommerce_{}_main_content actions.
	 *
	 * This will fire if the Compatibility module's deprecated_function method is run, which will happen
	 * if either of the deprecated functions have been plugged.
	 *
	 * @since 1.7.0.
	 *
	 * @hooked action make_deprecated_function_run
	 *
	 * @param string $function
	 *
	 * @return void
	 */
	public function backcompat_main_content_actions( $function ) {
		// Don't bother if this is happening during woocommerce_before_main_content already.
		if ( doing_action( 'woocommerce_before_main_content' ) || did_action( 'woocommerce_before_main_content' ) ) {
			return;
		}

		// Before
		if ( 'ttfmake_woocommerce_before_main_content' === $function && false === has_action( 'woocommerce_before_main_content', 'ttfmake_woocommerce_before_main_content' ) ) {
			remove_action( 'woocommerce_before_main_content', array( $this, 'before_main_content' ) );
			add_action( 'woocommerce_before_main_content', 'ttfmake_woocommerce_before_main_content' );
		}
		// After
		else if ( 'ttfmake_woocommerce_after_main_content' === $function && false === has_action( 'woocommerce_after_main_content', 'ttfmake_woocommerce_after_main_content' ) ) {
			remove_action( 'woocommerce_after_main_content', array( $this, 'after_main_content' ) );
			add_action( 'woocommerce_after_main_content', 'ttfmake_woocommerce_after_main_content' );
		}
	}
}