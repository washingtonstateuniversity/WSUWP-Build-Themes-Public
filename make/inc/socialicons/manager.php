<?php
/**
 * @package Make
 */

/**
 * Class MAKE_SocialIcons_Manager
 *
 * Manage and display icons for various social profile services.
 *
 * @since 1.7.0.
 */
class MAKE_SocialIcons_Manager extends MAKE_Util_Modules implements MAKE_SocialIcons_ManagerInterface, MAKE_Util_HookInterface, MAKE_Util_LoadInterface {
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
		'thememod'      => 'MAKE_Settings_ThemeModInterface',
	);

	/**
	 * The collection of URL patterns and their icon classes.
	 *
	 * @since 1.7.0.
	 *
	 * @var array
	 */
	private $icons = array();

	/**
	 * Required properties for icon definitions.
	 *
	 * @since 1.7.0.
	 *
	 * @var array
	 */
	private $required_properties = array(
		'title',
		'class',
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

		// Convert deprecated social profile settings if necessary
		add_filter( 'theme_mod_social-icons', array( $this, 'filter_theme_mod' ), 1 );

		// Fix sanitization of values coming from the Customizer
		add_filter( 'make_settings_thememod_sanitize_callback_parameters', array( $this, 'not_always_an_array' ), 20, 3 );

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
	 * Load data files.
	 *
	 * @since 1.7.0.
	 *
	 * @return void
	 */
	public function load() {
		if ( $this->is_loaded() ) {
			return;
		}

		// Load the default icon patterns
		$file = dirname( __FILE__ ) . '/definitions/socialicons.php';
		if ( is_readable( $file ) ) {
			include $file;
		}

		// Loading has occurred.
		$this->loaded = true;

		/**
		 * Action: Fires at the end of the Social Icons object's load method.
		 *
		 * This action gives a developer the opportunity to add or modify icon definitions
		 * and run additional load routines.
		 *
		 * @since 1.7.0.
		 *
		 * @param MAKE_SocialIcons_Manager $socialicons     The settings object that has just finished loading.
		 */
		do_action( "make_socialicons_loaded", $this );
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
	 * Add or update icon definitions.
	 *
	 * @since 1.7.0.
	 *
	 * @param array $icons
	 * @param bool  $overwrite
	 *
	 * @return bool
	 */
	public function add_icons( array $icons, $overwrite = false ) {
		$existing_icons = $this->icons;
		$new_icons = array();
		$return = true;

		// Check each setting definition for required properties before adding it.
		foreach ( $icons as $pattern => $icon_props ) {
			// Overwrite an existing icon.
			if ( isset( $existing_icons[ $pattern ] ) && true === $overwrite ) {
				$new_icons[ $pattern ] = wp_parse_args( $icon_props, $existing_icons[ $pattern ] );
			}
			// Icon already exists, overwriting disabled.
			else if ( isset( $existing_icons[ $pattern ] ) && true !== $overwrite ) {
				$this->error()->add_error( 'make_socialicons_already_exists', sprintf( __( 'The social icon URL pattern "%s" can\'t be added because it already exists.', 'make' ), esc_html( $pattern ) ) );
				$return = false;
			}
			// Icon does not have required properties.
			else if ( ! $this->has_required_properties( $icon_props ) ) {
				$this->error()->add_error( 'make_socialicons_missing_required_properties', sprintf( __( 'The social icon URL pattern "%s" setting can\'t be added because it is missing required properties.', 'make' ), esc_html( $pattern ) ) );
				$return = false;
			}
			// Add a new icon.
			else {
				$new_icons[ $pattern ] = $icon_props;
			}
		}

		// Add the valid new settings to the existing settings array.
		if ( ! empty( $new_icons ) ) {
			$this->icons = array_merge( $existing_icons, $new_icons );
		}

		return $return;
	}

	/**
	 * Check an array of icon definition properties against another array of required ones.
	 *
	 * @since 1.7.0.
	 *
	 * @param array $properties    The array of properties to check.
	 *
	 * @return bool                True if all required properties are present.
	 */
	private function has_required_properties( $properties ) {
		$properties = (array) $properties;
		$required_properties = $this->required_properties;
		$existing_properties = array_keys( $properties );

		// If there aren't any required properties, return true.
		if ( empty( $required_properties ) ) {
			return true;
		}

		// This variable will contain any array keys that aren't found in $existing_properties.
		$diff = array_diff_key( $required_properties, $existing_properties );

		return empty( $diff );
	}

	/**
	 * Remove one or more icon definitions from the collection.
	 *
	 * @since 1.7.0.
	 *
	 * @param array|string $icons    The array of icons to remove, or 'all'.
	 *
	 * @return bool                  True if all icon definitions were successfully removed, false if there was an error.
	 */
	public function remove_icons( $icons ) {
		if ( 'all' === $icons ) {
			// Clear the entire settings array.
			$this->icons = array();
			return true;
		}

		$return = true;

		foreach ( (array) $icons as $pattern ) {
			if ( isset( $this->icons[ $pattern ] ) ) {
				unset( $this->icons[ $pattern ] );
			} else {
				$this->error()->add_error( 'make_socialicons_cannot_remove', sprintf( __( 'The social icon URL pattern "%s" can\'t be removed because it doesn\'t exist.', 'make' ), esc_html( $pattern ) ) );
				$return = false;
			}
		}

		return $return;
	}

	/**
	 * Return the property containing the array of icon definitions.
	 *
	 * @since 1.7.0.
	 *
	 * @return array
	 */
	public function get_icons() {
		if ( false === $this->is_loaded() ) {
			$this->load();
		}

		$icons = $this->icons;

		// Check for deprecated filter.
		if ( has_filter( 'make_supported_social_icons' ) ) {
			$this->compatibility()->deprecated_hook(
				'make_supported_social_icons',
				'1.7.0',
				sprintf(
					wp_kses(
						__( 'To add or modify social icons, use the %1$s function instead. See the <a href="%2$s" target="_blank">Social Icons API documentation</a>.', 'make' ),
						array( 'a' => array( 'href' => true, 'target' => true ) )
					),
					'<code>make_update_socialicon_definition()</code>',
					'https://thethemefoundry.com/docs/make-docs/code/apis/social-icons-api/'
				)
			);

			/**
			 * Filter the supported social icons.
			 *
			 * This array uses the url pattern for the key and the CSS class (as dictated by Font Awesome) as the array value.
			 * The URL pattern is used to match the URL used by a menu item.
			 *
			 * @since 1.2.3.
			 * @deprecated 1.7.0.
			 *
			 * @param array    $icons    The array of supported social icons.
			 */
			$icons = apply_filters( 'make_supported_social_icons', $icons );

			// Convert any additional icons added to the new format
			foreach ( $icons as $pattern => $data ) {
				if ( ! is_array( $data ) ) {
					$icons[ $pattern ] = array(
						'title' => __( 'Link', 'make' ),
						'class' => ( 0 === strpos( $data, 'fa-' ) ) ? array( 'fa', 'fa-fw', $data ) : array( $data ),
					);
				}
			}
		}

		return $icons;
	}

	/**
	 * Get the icon definition for an email address.
	 *
	 * @since 1.7.0.
	 *
	 * @return array
	 */
	private function get_email_props() {
		/**
		 * Filter: Modify the icon definition for an email address.
		 *
		 * @since 1.7.0.
		 *
		 * @param array $icon    The icon definition.
		 */
		return apply_filters( 'make_socialicons_email', array(
			'title' => esc_html__( 'Email', 'make' ),
			'class' => array( 'fa', 'fa-fw', 'fa-envelope' ),
		) );
	}

	/**
	 * Get the icon definition for an RSS feed.
	 *
	 * @since 1.7.0.
	 *
	 * @return array
	 */
	private function get_rss_props() {
		/**
		 * Filter: Modify the icon definition for an RSS feed.
		 *
		 * @since 1.7.0.
		 *
		 * @param array $icon    The icon definition.
		 */
		return apply_filters( 'make_socialicons_rss', array(
			'title' => esc_html__( 'RSS', 'make' ),
			'class' => array( 'fa', 'fa-fw', 'fa-rss' ),
		) );
	}

	/**
	 * Get the icon definition for a URL that doesn't match any icon URL pattern.
	 *
	 * @since 1.7.0.
	 *
	 * @return array
	 */
	private function get_default_props() {
		/**
		 * Filter: Modify the icon definition for a URL that doesn't match any icon URL pattern.
		 *
		 * @since 1.7.0.
		 *
		 * @param array $icon    The icon definition.
		 */
		return apply_filters( 'make_socialicons_default', array(
			'title' => esc_html__( 'Link', 'make' ),
			'class' => array( 'fa', 'fa-fw', 'fa-external-link-square' ),
		) );
	}

	/**
	 * Make sure an item is an array with specific keys.
	 *
	 * @since 1.7.0.
	 *
	 * @param mixed $item
	 *
	 * @return array
	 */
	private function sanitize_item( $item ) {
		return wp_parse_args( (array) $item, array( 'type' => '', 'content' => '' ) );
	}

	/**
	 * Compare a string to the icon URL patterns to find a match.
	 *
	 * @since 1.7.0.
	 *
	 * @param array $item
	 *
	 * @return array
	 */
	public function find_match( $item ) {
		$item = $this->sanitize_item( $item );

		// Special cases for email and rss
		if ( 'email' === $item['type'] ) {
			return $this->get_email_props();
		} else if ( 'rss' === $item['type'] ) {
			return $this->get_rss_props();
		}

		// If it's not a valid URL, return empty
		$string = esc_url( $item['content'] );
		if ( function_exists( 'filter_var' ) ) { // Some hosts don't enable this function
			if ( false === filter_var( $string, FILTER_VALIDATE_URL ) ) {
				return array();
			}
		}

		// Search for a pattern match
		$icons = $this->get_icons();
		foreach ( $icons as $pattern => $props ) {
			if ( false !== stripos( $string, $pattern ) ) {
				return $props;
			}
		}

		// If we've made it this far, return the default
		return $this->get_default_props();
	}

	/**
	 * Gather the data from deprecated social profile settings and convert it into the current icon data array.
	 *
	 * @since 1.7.0.
	 *
	 * @return array
	 */
	private function get_icon_data_from_old_settings() {
		$old_settings = array(
			'social-facebook-official',
			'social-twitter',
			'social-google-plus-square',
			'social-linkedin',
			'social-instagram',
			'social-flickr',
			'social-youtube',
			'social-vimeo-square',
			'social-pinterest',
			'social-email',
			'social-hide-rss',
			'social-custom-rss',
		);

		// The RSS icon is visible by default
		$icon_data = array(
			'rss-toggle' => true,
		);

		// Populate from Customizer settings first
		foreach ( $old_settings as $setting_id ) {
			$value = get_theme_mod( $setting_id, null );

			if ( ! is_null( $value ) ) {
				switch ( $setting_id ) {
					default :
					case 'social-facebook-official' :
					case 'social-twitter' :
					case 'social-google-plus-square' :
					case 'social-linkedin' :
					case 'social-instagram' :
					case 'social-flickr' :
					case 'social-youtube' :
					case 'social-vimeo-square' :
					case 'social-pinterest' :
						if ( ! isset( $icon_data['items'] ) ) {
							$icon_data['items'] = array();
						}
						$icon_data['items'][] = array(
							'type'    => 'link',
							'content' => $value,
						);
						break;

					case 'social-email' :
						$icon_data['email-toggle'] = true;
						if ( ! isset( $icon_data['items'] ) ) {
							$icon_data['items'] = array();
						}
						$icon_data['items'][] = array(
							'type'    => 'email',
							'content' => $value,
						);
						break;

					case 'social-hide-rss' :
						$icon_data['rss-toggle'] = ! wp_validate_boolean( $value );
						break;

					case 'social-custom-rss' :
						if ( ! isset( $icon_data['items'] ) ) {
							$icon_data['items'] = array();
						}
						$icon_data['items'][] = array(
							'type'    => 'rss',
							'content' => $value,
						);
						break;
				}
			}
		}

		// Look for an overriding custom menu
		if ( ( $locations = get_nav_menu_locations() ) && isset( $locations[ 'social' ] ) ) {
			$menu = wp_get_nav_menu_object( $locations[ 'social' ] );

			if ( $menu && ! is_wp_error( $menu ) ) {
				// Add an error message
				if ( ! $this->error()->has_code( 'make_deprecated_social_menu' ) ) {
					$this->error()->add_error(
						'make_deprecated_social_menu',
						wp_kses( __( 'Make no longer uses a custom menu to output social icons. Instead, use the interface in the Customizer under <em>General &rarr; Social Icons</em>.', 'make' ), array( 'em' => true ) )
					);
				}

				$menu_items = wp_get_nav_menu_items( $menu->term_id, array( 'update_post_term_cache' => false ) );

				// Set up the $menu_item variables
				_wp_menu_item_classes_by_context( $menu_items );

				// Sort the menu items
				$sorted_menu_items = array();
				foreach ( (array) $menu_items as $menu_item ) {
					$sorted_menu_items[ $menu_item->menu_order ] = $menu_item;
				}

				unset( $menu_items, $menu_item );

				// Reset the items array, since the menu overrides the Customizer fields.
				$icon_data['items'] = array();

				foreach ( $sorted_menu_items as $item ) {
					if ( 0 === strpos( $item->url, 'mailto:' ) ) {
						$icon_data['email-toggle'] = true;
						$icon_data['items'][] = array(
							'type'    => 'email',
							'content' => str_replace( 'mailto:', '', $item->url ),
						);
					} else {
						$icon_data['items'][] = array(
							'type'    => 'link',
							'content' => $item->url,
						);
					}

					if ( isset( $item->target ) && $item->target ) {
						$icon_data['new-window'] = true;
					}
				}
			}
		}

		// Make sure the Email and RSS items are placed correctly.
		if ( isset( $icon_data['email-toggle'] ) && true === $icon_data['email-toggle'] ) {
			if ( ! isset( $icon_data['items'] ) ) {
				$icon_data['items'] = array();
			}

			if ( false === array_search( 'email', wp_list_pluck( $icon_data['items'], 'type' ) ) ) {
				$icon_data['items'][] = array(
					'type'    => 'email',
					'content' => $this->thememod()->get_default( 'social-icons-item-content-email' ),
				);
			}
		}
		if ( isset( $icon_data['rss-toggle'] ) && true === $icon_data['rss-toggle'] ) {
			if ( ! isset( $icon_data['items'] ) ) {
				$icon_data['items'] = array();
			}

			if ( false === array_search( 'rss', wp_list_pluck( $icon_data['items'], 'type' ) ) ) {
				$icon_data['items'][] = array(
					'type'    => 'rss',
					'content' => $this->thememod()->get_default( 'social-icons-item-content-rss' ),
				);
			}
		}

		return $icon_data;
	}

	/**
	 * If the 'social-icons' setting doesn't exist yet, try to fall back on deprecated social profile settings.
	 *
	 * @since 1.7.0.
	 *
	 * @hooked filter theme_mod_social-icons
	 *
	 * @param array $value
	 *
	 * @return array
	 */
	public function filter_theme_mod( $value ) {
		$all_mods = get_theme_mods();

		if ( ! isset( $all_mods['social-icons'] ) ) {
			$icon_data = $this->get_icon_data_from_old_settings();
			if ( ! empty( $icon_data ) ) {
				return $icon_data;
			}
		}

		return $value;
	}

	/**
	 * Wrapper function for retrieving social icon data.
	 *
	 * @since 1.7.0.
	 *
	 * @param bool $raw
	 *
	 * @return array
	 */
	private function get_icon_data( $raw = false ) {
		if ( $raw ) {
			return $this->thememod()->get_raw_value( 'social-icons' );
		} else {
			return $this->thememod()->get_value( 'social-icons' );
		}
	}

	/**
	 * Check to see if social icons have been configured for display.
	 *
	 * @since 1.7.0.
	 *
	 * @return bool
	 */
	public function has_icon_data() {
		$icon_data = $this->get_icon_data( true );
		return ( isset( $icon_data['items'] ) && ! empty( $icon_data['items'] ) );
	}

	/**
	 * Render the social icons as an HTML unordered list.
	 *
	 * @since 1.7.0.
	 *
	 * @return string
	 */
	public function render_icons() {
		$icon_data = $this->get_icon_data();
		$items = ( isset( $icon_data['items'] ) ) ? $icon_data['items'] : array();

		/**
		 * Filter: Override the default social icons rendered output.
		 *
		 * @since 1.7.0.
		 *
		 * @param string|null $override     This value will be returned if it is not null.
		 * @param array       $icon_data    The array of icon data to use for rendering.
		 */
		$override = apply_filters( 'make_socialicons_render_override', null, $icon_data );
		if ( is_string( $override ) ) {
			return $override;
		}

		// Check for deprecated filter.
		if ( has_filter( 'make_social_links' ) ) {
			$this->compatibility()->deprecated_hook(
				'make_social_links',
				'1.7.0',
				sprintf(
					wp_kses(
						__( 'To add or modify social icons, use the %1$s function instead. See the <a href="%2$s" target="_blank">Social Icons API documentation</a>.', 'make' ),
						array( 'a' => array( 'href' => true, 'target' => true ) )
					),
					'<code>make_update_socialicon_definition()</code>',
					'https://thethemefoundry.com/docs/make-docs/code/apis/social-icons-api/'
				)
			);
		}

		ob_start();

		// Render list items
		foreach( $items as $item ) {
			$item = $this->sanitize_item( $item );
			$icon = $this->find_match( $item );
			if ( ! empty( $icon ) ) {
				$title = $icon['title'];
				$class = implode( ' ', $icon['class'] );

				// Prep the content
				if ( 'email' === $item['type'] && $item['content'] ) {
					$content = 'mailto:' . $item['content'];
				} else if ( 'rss' === $item['type'] ) {
					if ( $item['content'] ) {
						$content = $item['content'];
					} else {
						$content = get_feed_link();
					}
				} else {
					$content = $item['content'];
				}

				// Don't render items with no content
				if ( empty( $content ) ) {
					continue;
				}
				?>
				<li class="make-social-icon">
					<a href="<?php echo esc_attr( $content ); ?>"<?php if ( true === $icon_data['new-window'] && false === strpos( $content, 'mailto:' ) ) : ?> target="_blank"<?php endif; ?>>
						<i class="<?php echo esc_attr( $class ); ?>" aria-hidden="true"></i>
						<span class="screen-reader-text"><?php echo esc_attr( $title ); ?></span>
					</a>
				</li>
			<?php
			}
		}

		$output = ob_get_clean();

		// Add the list wrapper
		if ( $output ) {
			$output = "<ul class=\"social-customizer social-links\">\n" . $output . "</ul>\n";
		}

		return $output;
	}

	/**
	 * When the social icons data is coming from the Customizer, it's not an array, in which
	 * case the wrap_array_values filter needs to be undone.
	 *
	 * @since 1.7.0.
	 *
	 * @hooked filter make_settings_thememod_sanitize_callback_parameters
	 *
	 * @param mixed        $value
	 * @param string       $setting_id
	 * @param array|string $callback
	 *
	 * @return array
	 */
	public function not_always_an_array( $value, $setting_id, $callback ) {
		if (
			'social-icons' === $setting_id
			&&
			is_array( $value )
			&&
			is_array( $callback )
			&&
		    $callback[0] instanceof MAKE_Settings_SanitizeInterface
			&&
		    $callback[1] === 'sanitize_socialicons_from_customizer'
		) {
			$value = $value[0];
		}

		return $value;
	}
}
