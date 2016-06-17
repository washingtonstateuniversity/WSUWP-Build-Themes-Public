<?php
/**
 * @package Make
 */

/**
 * Class MAKE_Admin_Notice
 *
 * Register and display notices in the Admin interface.
 *
 * @since 1.4.9.
 * @since 1.7.0. Changed class name from TTFMAKE_Admin_Notice
 */
final class MAKE_Admin_Notice implements MAKE_Admin_NoticeInterface, MAKE_Util_HookInterface, MAKE_Util_LoadInterface {
	/**
	 * The array of registered notices.
	 *
	 * @since 1.4.9.
	 *
	 * @var array    The array of registered notices.
	 */
	private $notices = array();

	/**
	 * Indicator of whether the hook routine has been run.
	 *
	 * @since 1.7.0.
	 *
	 * @var bool
	 */
	private static $hooked = false;

	/**
	 * Indicator of whether the load routine has been run.
	 *
	 * @since 1.7.0.
	 *
	 * @var bool
	 */
	private $loaded = false;

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

		// Hook up notices
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );

		if ( is_admin() ) {
			// Register Ajax action
			add_action( 'wp_ajax_make_hide_notice', array( $this, 'handle_ajax' ) );
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
	 * Load files.
	 *
	 * @since 1.4.9.
	 *
	 * @return void
	 */
	public function load() {
		if ( $this->is_loaded() ) {
			return;
		}

		// Load the current notices
		$file = dirname( __FILE__ ) . '/current-notices.php';
		if ( is_readable( $file ) ) {
			include_once $file;
		}

		// Loading has occurred.
		$this->loaded = true;

		/**
		 * Action: Fires at the end of the Admin Notice object's load method.
		 *
		 * This action gives a developer the opportunity to add additional admin notices
		 * and run additional load routines.
		 *
		 * @since 1.7.0.
		 *
		 * @param MAKE_Admin_Notice    $notice     The notice object that has just finished loading.
		 */
		do_action( 'make_notice_loaded', $this );
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
	 * Register an admin notice.
	 *
	 * A registered notice will be displayed in the notice section of the UI, beneath the page title,
	 * if the conditions specified in the notice arguments are met.
	 *
	 * @since 1.4.9.
	 *
	 * @param string    $id         A unique ID string for the admin notice.
	 * @param string    $message    The content of the admin notice.
	 * @param array     $args       Array of configuration parameters for the admin notice.
	 *
	 * @return bool                 True if the admin notice was successfully registered.
	 */
	public function register_admin_notice( $id, $message, array $args = array() ) {
		// Sanitize ID
		$id = sanitize_key( $id );

		// Prep args
		$defaults = array(
			'cap'     => 'switch_themes',      // User capability to see the notice
			'dismiss' => true,                 // Whether notice is dismissible
			'screen'  => array( 'dashboard' ), // IDs/filenames of screens to show the notice on
			'type'    => 'info',               // success, warning, error, info
		);
		$args = wp_parse_args( $args, $defaults );

		// Not a one time notice
		$args['one-time'] = false;

		// Register the notice
		if ( $id && $message ) {
			$this->notices[ $id ] = array_merge( array( 'message' => $message ), $args );
			return true;
		}

		// If you get to here, the registration failed.
		return false;
	}

	/**
	 * Register a one time admin notice.
	 *
	 * One time notices are specific to a user and are stored in the database as a transient for display
	 * during a later page load. Once one has been displayed, it is removed from the database.
	 *
	 * @since 1.7.0.
	 *
	 * @param string       $message    The content of the admin notice.
	 * @param WP_User|null $user       The object for user who will receive the notice.
	 * @param array        $args       Array of configuration parameters for the admin notice.
	 *
	 * @return bool                    True if the admin notice was successfully registered.
	 */
	public function register_one_time_admin_notice( $message, WP_User $user = null, array $args = array() ) {
		// Prep args
		$defaults = array(
			'dismiss' => true,   // Whether notice is dismissible
			'type'    => 'info', // success, warning, error, info
		);
		$args = wp_parse_args( $args, $defaults );

		// One time notice
		$args['one-time'] = true;

		// Sanitize message
		$message = $this->sanitize_message( $message );

		// Register the notice
		if ( $message ) {
			$notice = array_merge( array( 'message' => $message ), $args );
			$id = $this->generate_one_time_admin_notice_id( $user );

			$notices = get_transient( $id );
			if ( ! is_array( $notices ) ) {
				$notices = array();
			}
			$notices[] = $notice;

			return set_transient( $id, $notices, MONTH_IN_SECONDS );
		}

		// If you get to here, the registration failed.
		return false;
	}

	/**
	 * Retrieve one time notices and delete them from the database.
	 *
	 * @since 1.7.0.
	 *
	 * @param WP_User|null $user    The object for user who will receive the notices.
	 *
	 * @return array                An array of notices.
	 */
	private function get_one_time_admin_notices( WP_User $user = null ) {
		$id = $this->generate_one_time_admin_notice_id( $user );

		$notices = get_transient( $id );

		if ( is_array( $notices ) ) {
			delete_transient( $id );
			return $notices;
		}

		return array();
	}

	/**
	 * Generate a transient ID based on the specified user or current user.
	 *
	 * @since 1.7.0.
	 *
	 * @param WP_User|null $user    The object for the user for whom to generate an ID.
	 *
	 * @return string               The generated ID.
	 */
	private function generate_one_time_admin_notice_id( WP_User $user = null ) {
		if ( is_null( $user ) ) {
			$user = wp_get_current_user();
		}

		return 'make-notice-' . md5( $user->user_login );
	}

	/**
	 * Get the visible notices for a specified screen.
	 *
	 * @since 1.4.9.
	 *
	 * @param  WP_Screen|null $screen    The screen to display the notices on.
	 *
	 * @return array                     Array of notices to display on the specified screen.
	 */
	private function get_notices( WP_Screen $screen = null ) {
		if ( is_null( $screen ) ) {
			$screen = get_current_screen();
		}
		
		// Get the array of notices that the current user has already dismissed
		$dismissed = $this->get_dismissed_notices( get_current_user_id() );

		// Remove notices that don't meet requirements
		$notices = $this->notices;
		foreach( $notices as $id => $args ) {
			if (
				! $this->screen_is_enabled( $screen, $args['screen'] )
				||
				! current_user_can( $args['cap'] )
				||
				in_array( $id, $dismissed )
			) {
				unset( $notices[ $id ] );
			}
		}

		// Look for one time notices
		$one_time_notices = $this->get_one_time_admin_notices();
		$notices = array_merge( $one_time_notices, $notices );

		return $notices;
	}

	/**
	 * Retrieve the stored array of dismissed notices for a user.
	 *
	 * @since 1.7.0.
	 *
	 * @param int $user_id
	 *
	 * @return array
	 */
	private function get_dismissed_notices( $user_id ) {
		$dismissed = get_user_meta( $user_id, 'make-dismissed-notices', true );

		if ( ! $dismissed ) {
			// Look for deprecated meta key
			$dismissed = get_user_meta( $user_id, 'ttfmake-dismissed-notices', true );

			if ( $dismissed ) {
				// Add new meta entry and delete the deprecated one
				$this->update_dismissed_notices( $user_id, $dismissed );
				delete_user_meta( $user_id, 'ttfmake-dismissed-notices' );
			}
		}

		if ( ! is_array( $dismissed ) ) {
			$dismissed = array();
		}

		return $dismissed;
	}

	/**
	 * Update the stored array of dismissed notices for a user.
	 *
	 * @since 1.7.0.
	 *
	 * @param int   $user_id
	 * @param array $notices
	 *
	 * @return bool
	 */
	private function update_dismissed_notices( $user_id, array $notices ) {
		return update_user_meta( $user_id, 'make-dismissed-notices', $notices );
	}

	/**
	 * Check if the given screen is in the array of allowed screens.
	 *
	 * @since 1.6.0.
	 *
	 * @param  WP_Screen    $current_screen     The WP_Screen object for the given screen.
	 * @param  array        $enabled_screens    Array of allowed screen IDs.
	 *
	 * @return bool                             True if the given screen is enabled for displaying the notice.
	 */
	private function screen_is_enabled( $current_screen, $enabled_screens ) {
		// Validate current screen variable
		if ( ! $current_screen instanceof WP_Screen ) {
			return false;
		}

		// Ensure correct casting
		$enabled_screens = (array) $enabled_screens;

		// Check screen ID first
		if ( in_array( $current_screen->id, $enabled_screens ) ) {
			return true;
		}

		// Check screen's parent file next
		return in_array( $current_screen->parent_file, $enabled_screens );
	}

	/**
	 * Wrapper function for admin_notices hook that sets everything up.
	 *
	 * @since 1.4.9.
	 *
	 * @hooked action admin_notices
	 *
	 * @return void
	 */
	public function admin_notices() {
		// Make sure files are loaded first.
		if ( ! $this->is_loaded() ) {
			$this->load();
		}

		$current_notices = $this->get_notices();

		if ( ! empty( $current_notices ) ) {
			$this->render_notices( $current_notices );
		}
	}

	/**
	 * Output the markup and styles for admin notices.
	 *
	 * @since 1.4.9.
	 *
	 * @param  array    $notices    The array of notices to render.
	 *
	 * @return void
	 */
	private function render_notices( $notices ) {
		// Add styles and script to page if necessary
		if ( in_array( true, wp_list_pluck( $notices, 'dismiss' ) ) ) {
			add_action( 'admin_print_footer_scripts', array( $this, 'print_admin_notices_js' ) );
		}

		// Prep and render each notice
		foreach ( $notices as $id => $args ) {
			// Notice config
			$id      = sanitize_key( $id );
			$message = $this->sanitize_message( $args['message'] );
			$dismiss = wp_validate_boolean( $args['dismiss'] );
			$type    = sanitize_key( $args['type'] );
			$onetime = wp_validate_boolean( $args['one-time'] );
			$classes = array( 'notice', 'notice-' . $type );
			$nonce   = ( $dismiss && ! $onetime ) ? ' data-nonce="' . esc_attr( wp_create_nonce( 'make_dismiss_' . $id ) ) . '"' : '';

			// Add dismissible class
			if ( true === $dismiss ) {
				$classes[] = 'is-dismissible';
			}

			// Add one time class
			if ( true === $onetime ) {
				$classes[] = 'one-time';
			}

			// Convert classes to string
			$classes = implode( ' ', $classes );

			// Render
			?>
			<div id="make-notice-<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $classes ); ?>"<?php echo $nonce; ?>>
				<?php echo wpautop( $message ); ?>
			</div>
			<?php
		}
	}

	/**
	 * Output the JS to hide admin notices.
	 *
	 * @since 1.4.9.
	 *
	 * @hooked action admin_print_footer_scripts
	 *
	 * @return void
	 */
	public function print_admin_notices_js() {
		?>
		<script type="application/javascript">
			/* Make admin notices */
			/* <![CDATA[ */
			(function($) {
				$('.notice:not(.one-time)').on('click', '.notice-dismiss', function(evt) {
					evt.preventDefault();

					var $target = $(evt.target),
						$parent = $target.parents('.notice').first(),
						id      = $parent.attr('id').replace('make-notice-', ''),
						nonce   = $parent.data('nonce');

					$.post(
						ajaxurl,
						{
							action : 'make_hide_notice',
							nid    : id,
							nonce  : nonce
						}
					);
				});
			})(jQuery);
			/* ]]> */
		</script>
		<?php
	}

	/**
	 * Process the Ajax request to hide an admin notice.
	 *
	 * @since 1.4.9.
	 *
	 * @hooked action wp_ajax_make_hide_notice
	 *
	 * @return void
	 */
	public function handle_ajax() {
		// Only run this during an Ajax request.
		if ( 'wp_ajax_make_hide_notice' !== current_action() ) {
			return;
		}

		// Get POST parameters
		$nid   = isset( $_POST['nid'] )   ? sanitize_key( $_POST['nid'] ) : false;
		$nonce = isset( $_POST['nonce'] ) ? $_POST['nonce']               : false;

		// Check requirements
		if (
			! defined( 'DOING_AJAX' ) ||
			true !== DOING_AJAX ||
			false === $nid ||
			false === $nonce ||
			! wp_verify_nonce( $nonce, 'make_dismiss_' . $nid )
		) {
			// Requirement check failed. Bail.
			wp_die();
		}

		// Get the array of notices that the current user has already dismissed
		$user_id = get_current_user_id();
		$dismissed = $this->get_dismissed_notices( $user_id );

		// Add a new notice to the array
		$dismissed[] = $nid;
		$success = $this->update_dismissed_notices( $user_id, $dismissed );

		// Return a success response.
		if ( $success ) {
			echo 1;
		}
		wp_die();
	}

	/**
	 * Sanitize an admin notice message.
	 *
	 * @since 1.6.5.
	 *
	 * @param  string    $message    The message string to sanitize.
	 * @return string                The sanitized message string.
	 */
	private function sanitize_message( $message ) {
		$allowedtags = wp_kses_allowed_html();
		$allowedtags['a']['target'] = true;
		return wp_kses( $message, $allowedtags );
	}
}