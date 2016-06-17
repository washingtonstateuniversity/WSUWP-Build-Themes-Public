<?php
/**
 * @package Make
 */

/**
 * Class MAKE_Setup_Head
 *
 * Set up the contents of the document head.
 * 
 * @since 1.7.0.
 */
final class MAKE_Setup_Head extends MAKE_Util_Modules implements MAKE_Setup_HeadInterface, MAKE_Util_HookInterface {
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
		'scripts'       => 'MAKE_Setup_ScriptsInterface',
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

		// Head actions
		add_action( 'wp_head', array( $this, 'meta_charset' ), 0 );
		add_action( 'wp_head', array( $this, 'dns_prefetch' ), 1 );
		add_action( 'wp_head', array( $this, 'js_detection' ), 1 );
		add_action( 'wp_head', array( $this, 'meta_viewport' ) );
		add_action( 'wp_head', array( $this, 'pingback' ) );
		add_action( 'wp_head', array( $this, 'backcompat_icons' ) );

		// Backcompat with old head actions
		add_action( 'make_deprecated_function_run', array( $this, 'backcompat_head_actions' ) );

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
	 * Simple script taken from Modernizr to indicate via HTML class whether the browser has JavaScript enabled.
	 *
	 * @since 1.0.0.
	 *
	 * @hooked action wp_head
	 *
	 * @return void
	 */
	public function js_detection() {
		?>
		<script type="text/javascript">
			/* <![CDATA[ */
			document.documentElement.className = document.documentElement.className.replace(new RegExp('(^|\\s)no-js(\\s|$)'), '$1js$2');
			/* ]]> */
		</script>
	<?php
	}

	/**
	 * Pre-fetch DNS for third-party assets.
	 *
	 * @since 1.7.0.
	 *
	 * @hooked action wp_head
	 *
	 * @return void
	 */
	public function dns_prefetch() {
		// Google fonts
		if ( ! is_customize_preview() && $this->scripts()->get_google_url() ) : ?>
			<link rel="dns-prefetch" href="//fonts.googleapis.com" />
	<?php endif;
	}

	/**
	 * Meta tag indicating the site's character set.
	 *
	 * @since 1.0.0.
	 *
	 * @hooked action wp_head
	 *
	 * @return void
	 */
	public function meta_charset() {
		?>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<?php
	}

	/**
	 * Meta tag setting viewport parameters.
	 *
	 * @since 1.0.0.
	 *
	 * @hooked action wp_head
	 *
	 * @return void
	 */
	public function meta_viewport() {
		?>
		<meta name="viewport" content="width=device-width, initial-scale=1" />
	<?php
	}

	/**
	 * Add the pingback link to relevant views, if pingbacks are enabled.
	 *
	 * @since 1.0.0.
	 * @since 1.7.0. Added conditional wrapper.
	 *
	 * @hooked action wp_head
	 *
	 * @return void
	 */
	public function pingback() {
		if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
			<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php endif;
	}

	/**
	 * Backcompat for Make's old Favicon and Apple Touch Icon settings.
	 *
	 * WordPress introduced its own Site Icon setting in version 4.3. This only checks the old theme icon
	 * settings if no value is set for the Core icon option.
	 *
	 * @since 1.6.2.
	 *
	 * @hooked action wp_head
	 *
	 * @return void
	 */
	public function backcompat_icons() {
		// Core Site Icon option overrides Make's deprecated Favicon and Apple Touch Icon settings
		if ( ! get_option( 'site_icon' ) ) :
			// Favicon
			$logo_favicon = make_get_thememod_value( 'logo-favicon' );
			if ( ! empty( $logo_favicon ) ) :
				if ( is_int( $logo_favicon ) ) :
					$logo_favicon_src = wp_get_attachment_image_src( $logo_favicon, 'full' );
					$logo_favicon = isset( $logo_favicon_src[0] ) ? $logo_favicon_src[0] : '';
				endif;
				?>
				<link rel="icon" href="<?php echo esc_url( $logo_favicon ); ?>" />
			<?php endif;

			// Apple Touch icon
			$logo_apple_touch = make_get_thememod_value( 'logo-apple-touch' );
			if ( ! empty( $logo_apple_touch ) ) :
				if ( is_int( $logo_apple_touch ) ) :
					$logo_apple_touch_src = wp_get_attachment_image_src( $logo_apple_touch, 'full' );
					$logo_apple_touch = isset( $logo_apple_touch_src[0] ) ? $logo_apple_touch_src[0] : '';
				endif;
				?>
				<link rel="apple-touch-icon" href="<?php echo esc_url( $logo_apple_touch ); ?>" />
			<?php endif;

			// Add a Make Notice if old settings are still in use
			if ( ! empty( $logo_favicon ) || ! empty( $logo_apple_touch ) ) :
				$this->error()->add_error(
					'make_deprecated_site_icon',
					wp_kses(
						__( 'This site is using the old Favicon and Apple Touch Icon settings, which were deprecated in version 1.6.2. Go to <em>General &rarr; Site Identity</em> in the Customizer to switch to the new, improved Site Icon setting.', 'make' ),
						array( 'em' => true )
					)
				);
			endif;
		endif;
	}

	/**
	 * Backcompat for deprecated pluggable functions hooked to wp_head.
	 *
	 * This will fire if the Compatibility module's deprecated_function method is run, which will happen
	 * if either of the deprecated head functions have been plugged.
	 *
	 * @since 1.7.0.
	 *
	 * @hooked action make_deprecated_function_run
	 *
	 * @param string $function
	 *
	 * @return void
	 */
	public function backcompat_head_actions( $function ) {
		// Don't bother if this is happening during wp_head already.
		if ( doing_action( 'wp_head' ) || did_action( 'wp_head' ) ) {
			return;
		}

		// Early
		if ( 'ttfmake_head_early' === $function && false === has_action( 'wp_head', 'ttfmake_head_early' ) ) {
			remove_action( 'wp_head', array( $this, 'js_detection' ), 0 );
			remove_action( 'wp_head', array( $this, 'meta_charset' ) );
			remove_action( 'wp_head', array( $this, 'meta_viewport' ) );
			add_action( 'wp_head', 'ttfmake_head_early', 1 );
		}
		// Late
		else if ( 'ttfmake_head_late' === $function && false === has_action( 'wp_head', 'ttfmake_head_late' ) ) {
			remove_action( 'wp_head', array( $this, 'pingback' ) );
			remove_action( 'wp_head', array( $this, 'backcompat_icons' ) );
			add_action( 'wp_head', 'ttfmake_head_late', 99 );
		}
	}
}