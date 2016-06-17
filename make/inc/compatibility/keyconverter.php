<?php
/**
 * @package Make
 */

/**
 * Class MAKE_Compatibility_KeyConverter
 *
 * @since 1.7.0.
 */
final class MAKE_Compatibility_KeyConverter extends MAKE_Util_Modules implements MAKE_Util_HookInterface {
	/**
	 * An associative array of required modules.
	 *
	 * @since 1.7.0.
	 *
	 * @var array
	 */
	protected $dependencies = array(
		'style' => 'MAKE_Style_ManagerInterface',
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

		add_action( 'after_setup_theme', array( $this, 'set_up_theme_mod_conversions' ), 11 );

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
	 * Return an array of theme_mod key migration sets.
	 *
	 * @since  1.3.0.
	 *
	 * @return array    The list of key migration sets.
	 */
	private function get_key_conversions() {
		// $new_key => $old_key
		$conversions = array(
			'font-family-site-title'      => 'font-site-title',
			'font-family-h1'              => 'font-header',
			'font-family-h2'              => 'font-header',
			'font-family-h3'              => 'font-header',
			'font-family-h4'              => 'font-header',
			'font-family-h5'              => 'font-header',
			'font-family-h6'              => 'font-header',
			'font-family-body'            => 'font-body',
			'font-size-site-title'        => 'font-site-title-size',
			'font-size-site-tagline'      => 'font-site-tagline-size',
			'font-size-nav'               => 'font-nav-size',
			'font-size-h1'                => 'font-header-size',
			'font-size-h2'                => 'font-header-size',
			'font-size-h3'                => 'font-header-size',
			'font-size-h4'                => 'font-header-size',
			'font-size-h5'                => 'font-header-size',
			'font-size-h6'                => 'font-header-size',
			'font-size-widget'            => 'font-widget-size',
			'font-size-body'              => 'font-body-size',
			'social-facebook-official'    => 'social-facebook', // 1.5.0
			'main-content-link-underline' => 'link-underline-body', // 1.5.0
		);

		/**
		 * Filter the array of Customizer option key conversions.
		 *
		 * The keys for some Customizer options have changed between versions. This array
		 * defines each change as $new_key => $old key.
		 *
		 * @since 1.3.0.
		 *
		 * @param array    $conversions    The array of key conversions.
		 */
		return apply_filters( 'make_customizer_key_conversions', $conversions );
	}

	/**
	 * Convert old theme mod values to their newer equivalents.
	 *
	 * @since  1.3.0.
	 *
	 * @hooked action after_setup_theme
	 *
	 * @return void
	 */
	public function set_up_theme_mod_conversions() {
		// Set up the necessary filters
		foreach ( $this->get_key_conversions() as $key => $value ) {
			add_filter( 'theme_mod_' . $key, array( $this, 'convert_theme_mods_filter' ), 11 );
		}
	}

	/**
	 * Convert a new theme mod value from an old one.
	 *
	 * @since  1.3.0.
	 *
	 * @hooked filter theme_mod_{$key}
	 *
	 * @param  mixed    $value    The current value.
	 * @return mixed              The modified value.
	 */
	public function convert_theme_mods_filter( $value ) {
		$new_mod_name = str_replace( 'theme_mod_', '', current_filter() );
		$conversions  = $this->get_key_conversions();
		$mods         = get_theme_mods();

		/**
		 * When previewing a page, the logic for this filter needs to change. Because the isset check in the conditional
		 * below will always fail if the new mod key is not set (i.e., the value isn't in the db yet), the default value,
		 * instead of the preview value will always show. Instead, when previewing, the value needs to be gotten from
		 * the `get_theme_mod()` call without this filter applied. This will give the new preview value. If it is not found,
		 * then the normal routine will be used.
		 */
		if ( is_customize_preview() ) {
			remove_filter( current_filter(), array( $this, 'convert_theme_mods_filter' ), 11 );
			$previewed_value = get_theme_mod( $new_mod_name, 'default-value' );
			add_filter( current_filter(), array( $this, 'convert_theme_mods_filter' ), 11 );

			if ( 'default-value' !== $previewed_value ) {
				return $previewed_value;
			}
		}

		/**
		 * We only want to convert the value if the new mod is not in the mods array. This means that the value is not set
		 * and an attempt to get the value from an old key is warranted.
		 */
		if ( ! isset( $mods[ $new_mod_name ] ) ) {
			// Verify that this key should be converted
			if ( isset( $conversions[ $new_mod_name ] ) ) {
				$old_mod_name  = $conversions[ $new_mod_name ];
				$old_mod_value = get_theme_mod( $old_mod_name, 'default-value' );

				// The old value is indeed set
				if ( 'default-value' !== $old_mod_value ) {
					$value = $old_mod_value;

					// Now that we have the right old value, convert it if needed
					$value = $this->convert_theme_mods_values( $old_mod_name, $new_mod_name, $value );
				}
			}
		}

		return $value;
	}

	/**
	 * This function converts values from old mods to values for new mods.
	 *
	 * @since  1.3.0.
	 *
	 * @param  string    $old_key    The old mod key.
	 * @param  string    $new_key    The new mod key.
	 * @param  mixed     $value      The value of the mod.
	 * @return mixed                 The convert mod value.
	 */
	private function convert_theme_mods_values( $old_key, $new_key, $value ) {
		if ( 'font-header-size' === $old_key ) {
			$h       = preg_replace( '/font-size-(h\d)/', '$1', $new_key );
			$percent = $this->style()->helper()->get_relative_size( $h );
			$value   = $this->style()->helper()->get_relative_font_size( $value, $percent );
		} else if ( 'main-content-link-underline' === $old_key ) {
			if ( 1 == $value ) {
				$value = 'always';
			}
		}

		return $value;
	}
}