<?php
/**
 * @package Make
 */

/**
 * Class MAKE_Font_Source_Base
 *
 * An object for managing fonts from a particular source.
 *
 * An extending class should define the following properties:
 * - $id          A string used to identify the source
 * - $label       A string for the source's name, which will appear in font selection dropdowns.
 * - $priority    An integer to indicate the source's order in a list. Higher = lower on the list.
 *
 * An extending class must also define the data for each font. Font data should include at least a label
 * and a stack. Example:
 *
 * $this->data = array(
 *     'serif' => array(
 *         'label' => __( 'Serif', 'make' ),
 *         'stack' => 'Georgia,Times,"Times New Roman",serif'
 *     ),
 * )
 *
 * @since 1.7.0.
 */
class MAKE_Font_Source_Base extends MAKE_Util_Modules implements MAKE_Font_Source_BaseInterface {
	/**
	 * The source ID.
	 *
	 * @since 1.7.0.
	 *
	 * @var string
	 */
	protected $id = '';

	/**
	 * The source's name in the UI.
	 *
	 * @since 1.7.0.
	 *
	 * @var string
	 */
	protected $label = '';

	/**
	 * The source's order priority. E.g. where its fonts will appear in a list of all fonts.
	 *
	 * @since 1.7.0.
	 *
	 * @var int
	 */
	protected $priority = 10;

	/**
	 * The source's font data.
	 *
	 * @since 1.7.0.
	 *
	 * @var array
	 */
	protected $data = array();

	/**
	 * MAKE_Font_Source_Base constructor.
	 *
	 * @since 1.7.0.
	 *
	 * @param string                 $id
	 * @param string                 $label
	 * @param array                  $data
	 * @param int                    $priority
	 * @param MAKE_APIInterface|null $api
	 * @param array                  $modules
	 */
	public function __construct( $id, $label, $data = array(), $priority = 10, MAKE_APIInterface $api = null, array $modules = array() ) {
		// Set properties
		$this->id = $id;
		$this->label = $label;
		$this->data = (array) $data;
		$this->priority = absint( $priority );

		// Load dependencies
		parent::__construct( $api, $modules );
	}

	/**
	 * Getter for the $id property.
	 *
	 * @since 1.7.0.
	 *
	 * @return string
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * Getter for the $label property.
	 *
	 * @since 1.7.0.
	 *
	 * @return string
	 */
	public function get_label() {
		return $this->label;
	}

	/**
	 * Getter for the $priority property.
	 *
	 * @since 1.7.0.
	 *
	 * @return int
	 */
	public function get_priority() {
		return $this->priority;
	}

	/**
	 * Get the data for a particular font, or all of the source's font data.
	 *
	 * @since 1.7.0.
	 *
	 * @param string|null $font
	 *
	 * @return array
	 */
	public function get_font_data( $font = null ) {
		// Return data for a specific font.
		if ( ! is_null( $font ) ) {
			$data = array();

			if ( isset( $this->data[ $font ] ) ) {
				$data = $this->data[ $font ];
			}

			return $data;
		}

		/**
		 * Filter: Modify the font data from a particular source.
		 *
		 * @since 1.7.0.
		 *
		 * @param array $font_data
		 */
		return apply_filters( "make_font_data_{$this->id}", $this->data );
	}

	/**
	 * Check if this source has a particular font.
	 *
	 * @since 1.7.0.
	 *
	 * @param string $font
	 *
	 * @return bool
	 */
	public function has_font( $font ) {
		$data = $this->get_font_data( $font );
		return ! empty( $data );
	}

	/**
	 * Return a list of this source's fonts in an array format, as used for choice arrays.
	 *
	 * 'font value' => 'font label'
	 *
	 * @since 1.7.0.
	 *
	 * @return array
	 */
	public function get_font_choices() {
		$choices = array();

		foreach ( $this->get_font_data() as $key => $data ) {
			if ( isset( $data['label'] ) ) {
				$choices[ $key ] = $data['label'];
			}
		}

		return $choices;
	}

	/**
	 * Get the font stack for a particular font. If no stack is available, use a default stack instead.
	 *
	 * @since 1.7.0.
	 *
	 * @param string $font
	 * @param string $default_stack
	 *
	 * @return string
	 */
	public function get_font_stack( $font, $default_stack = 'sans-serif' ) {
		$data = $this->get_font_data( $font );
		$stack = '';

		if ( isset( $data['stack'] ) ) {
			$stack = $data['stack'];
		} else if ( is_string( $default_stack ) ) {
			$stack = $default_stack;
		}

		return $stack;
	}
}