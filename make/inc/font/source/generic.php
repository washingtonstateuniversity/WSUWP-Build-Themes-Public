<?php
/**
 * @package Make
 */

/**
 * Class MAKE_Font_Source_Generic
 *
 * A font source for generic, websafe fonts.
 *
 * @since 1.7.0.
 */
final class MAKE_Font_Source_Generic extends MAKE_Font_Source_Base {
	/**
	 * An associative array of required modules.
	 *
	 * @since 1.7.0.
	 *
	 * @var array
	 */
	protected $dependencies = array(
		'compatibility' => 'MAKE_Compatibility_MethodsInterface',
	);

	/**
	 * MAKE_Font_Source_Generic constructor.
	 *
	 * @since 1.7.0.
	 *
	 * @param MAKE_APIInterface $api
	 * @param array             $modules
	 */
	public function __construct( MAKE_APIInterface $api = null, array $modules = array() ) {
		// Parent constructor.
		parent::__construct(
			'generic',
			__( 'Generic Fonts', 'make' ),
			array(
				'serif' => array(
					'label' => __( 'Serif', 'make' ),
					'stack' => 'Georgia,Times,"Times New Roman",serif'
				),
				'sans-serif' => array(
					'label' => __( 'Sans Serif', 'make' ),
					'stack' => '"Helvetica Neue",Helvetica,Arial,sans-serif'
				),
				'monospace' => array(
					'label' => __( 'Monospaced', 'make' ),
					'stack' => 'Monaco,"Lucida Sans Typewriter","Lucida Typewriter","Courier New",Courier,monospace'
				),
			),
			10,
			$api,
			$modules
		);
	}

	/**
	 * Extension of the parent class's get_font_data method to account for deprecated filters.
	 *
	 * @since x.x.x.
	 *
	 * @param string|null $font
	 *
	 * @return array
	 */
	public function get_font_data( $font = null ) {
		$data = parent::get_font_data();

		// Check for deprecated filters
		if ( has_filter( 'make_get_standard_fonts' ) ) {
			$this->compatibility()->deprecated_hook(
				'make_get_standard_fonts',
				'1.7.0',
				sprintf(
					wp_kses(
						__( 'To modify Standard fonts, use the %1$s filter hook instead. See the <a href="%2$s" target="_blank">Font API documentation</a>.', 'make' ),
						array( 'a' => array( 'href' => true, 'target' => true ) )
					),
					'<code>make_font_data_generic</code>',
					'https://thethemefoundry.com/docs/make-docs/code/apis/font-api/'
				)
			);

			/**
			 * Allow for developers to modify the standard fonts.
			 *
			 * @since 1.2.3.
			 * @deprecated 1.7.0.
			 *
			 * @param array    $fonts    The list of standard fonts.
			 */
			$data = apply_filters( 'make_get_standard_fonts', $data );
		}

		if ( has_filter( 'make_all_fonts' ) ) {
			$this->compatibility()->deprecated_hook(
				'make_all_fonts',
				'1.7.0',
				sprintf(
					wp_kses(
						__( 'To add fonts, use the %1$s function. To modify existing fonts, use a filter hook for a specific font source, such as %2$s. See the <a href="%3$s" target="_blank">Font API documentation</a>.', 'make' ),
						array( 'a' => array( 'href' => true, 'target' => true ) )
					),
					'<code>make_add_font_source()</code>',
					'<code>make_font_data_google</code>',
					'https://thethemefoundry.com/docs/make-docs/code/apis/font-api/'
				)
			);

			/**
			 * Allow for developers to modify the full list of fonts.
			 *
			 * @since 1.2.3.
			 * @deprecated 1.7.0.
			 *
			 * @param array    $fonts    The list of all fonts.
			 */
			$data = apply_filters( 'make_all_fonts', $data );
		}

		// Return data for a specific font.
		if ( ! is_null( $font ) ) {
			if ( isset( $data[ $font ] ) ) {
				return $data[ $font ];
			} else {
				return array();
			}
		}

		return $data;
	}
}