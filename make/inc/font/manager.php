<?php
/**
 * @package Make
 */

/**
 * Class MAKE_Font_Manager
 *
 * Manage font options from different sources.
 *
 * @since 1.7.0
 */
class MAKE_Font_Manager extends MAKE_Util_Modules implements MAKE_Font_ManagerInterface, MAKE_Util_LoadInterface {
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
	);

	/**
	 * Indicator of whether the load routine has been run.
	 *
	 * @since 1.7.0.
	 *
	 * @var bool
	 */
	private $loaded = false;

	/**
	 * MAKE_Font_Manager constructor.
	 *
	 * @since 1.7.0.
	 *
	 * @param MAKE_APIInterface $api
	 * @param array             $modules
	 */
	public function __construct( MAKE_APIInterface $api = null, array $modules = array() ) {
		// Load dependencies.
		parent::__construct( $api, $modules );

		// Generic font source
		$this->add_source( 'generic', new MAKE_Font_Source_Generic( $api ) );

		// Google font source
		$this->add_source( 'google', new MAKE_Font_Source_Google( $api ) );
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

		// Loading has occurred.
		$this->loaded = true;

		/**
		 * Action: Fires at the end of the font object's load method.
		 *
		 * This action gives a developer the opportunity to add font sources
		 * and run additional load routines.
		 *
		 * @since 1.7.0.
		 *
		 * @param MAKE_Font_Manager    $font    The font object that has just finished loading.
		 */
		do_action( 'make_font_loaded', $this );
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
	 * Add a font source as a special type of module.
	 *
	 * @since 1.7.0.
	 *
	 * @param string                         $source_id
	 * @param MAKE_Font_Source_BaseInterface $source
	 *
	 * @return bool
	 */
	public function add_source( $source_id, $source ) {
		if ( ! $source instanceof MAKE_Font_Source_BaseInterface ) {
			$this->error()->add_error( 'make_font_source_not_valid', sprintf( __( '"%s" can\'t be added because it isn\'t a valid font source.', 'make' ), $source_id ) );
			return false;
		}

		/**
		 * Filter: Prevent a font source from being added.
		 *
		 * @since 1.7.0.
		 *
		 * @param bool $add_source    True to allow the font source to be added.
		 */
		$add_source = apply_filters( 'make_add_font_source_' . $source_id, true );

		if ( true === $add_source ) {
			$module_name = 'source_' . $source_id;
			return parent::add_module( $module_name, $source );
		}

		return false;
	}

	/**
	 * Get a font source module.
	 *
	 * @since 1.7.0.
	 *
	 * @param string $source_id
	 *
	 * @return MAKE_Font_Source_BaseInterface|null
	 */
	public function get_source( $source_id ) {
		if ( $this->has_source( $source_id ) ) {
			$module_name = 'source_' . $source_id;
			return parent::get_module( $module_name );
		}

		return null;
	}

	/**
	 * Check if a particular font source exists, based on its ID.
	 *
	 * @since 1.7.0.
	 *
	 * @param string $source_id
	 *
	 * @return bool
	 */
	public function has_source( $source_id ) {
		if ( ! $this->is_loaded() ) {
			$this->load();
		}

		$module_name = 'source_' . $source_id;
		return parent::has_module( $module_name );
	}

	/**
	 * Return an array of font source objects, sorted by their priority property.
	 *
	 * @since 1.7.0.
	 *
	 * @return array
	 */
	private function get_sorted_font_sources() {
		if ( ! $this->is_loaded() ) {
			$this->load();
		}

		$prioritizer = array();

		foreach ( $this->modules as $source_id => $source ) {
			if ( ! $source instanceof MAKE_Font_Source_BaseInterface ) {
				continue;
			}

			$priority = $source->get_priority();

			if ( ! isset( $prioritizer[ $priority ] ) ) {
				$prioritizer[ $priority ] = array();
			}

			$prioritizer[ $priority ][ $source_id ] = $source;
		}

		ksort( $prioritizer );

		$sorted_sources = array();
		foreach ( $prioritizer as $source_group ) {
			$sorted_sources = array_merge( $sorted_sources, $source_group );
		}

		return $sorted_sources;
	}

	/**
	 * Get the source of a particular font, if it exists.
	 *
	 * Returns the source object, or just the source's ID.
	 *
	 * @since 1.7.0.
	 *
	 * @param string $font
	 * @param string $return
	 *
	 * @return MAKE_Font_Source_BaseInterface|string|null
	 */
	public function get_font_source( $font, $return = 'object' ) {
		foreach ( $this->get_sorted_font_sources() as $source ) {
			if ( $source->has_font( $font ) ) {
				return ( 'id' === $return ) ? $source->get_id() : $source;
			}
		}

		return null;
	}

	/**
	 * Get the data for a particular font, if it exists.
	 *
	 * Increase the efficiency of this method by specifying the font's source.
	 *
	 * @since 1.7.0.
	 *
	 * @param string $font
	 * @param string $source_id
	 *
	 * @return array
	 */
	public function get_font_data( $font, $source_id = null ) {
		$font_data = array();

		// Look for the font in a particular source.
		if ( ! is_null( $source_id ) && $this->has_source( $source_id ) ) {
			$font_data = $this->get_source( $source_id )->get_font_data( $font );
			if ( ! empty( $font_data ) ) {
				$font_data['source'] = $this->get_source( $source_id )->get_id();
			}
		}
		// Search all sources for the stack.
		else {
			$source = $this->get_font_source( $font );
			if ( ! is_null( $source ) ) {
				$font_data = $source->get_font_data( $font );
				if ( ! empty( $font_data ) ) {
					$font_data['source'] = $source->get_id();
				}
			}
		}

		return $font_data;
	}

	/**
	 * Get the CSS font stack for a particular font, if it exists. If not, fall back on a default stack.
	 *
	 * Increase the efficiency of this method by specifying the font's source.
	 *
	 * @since 1.7.0.
	 *
	 * @param string $font
	 * @param string $default
	 * @param string $source_id
	 *
	 * @return mixed|string|void
	 */
	public function get_font_stack( $font, $default = 'sans-serif', $source_id = null ) {
		$stack = '';

		// Look for the stack in a particular source.
		if ( ! is_null( $source_id ) && $this->has_source( $source_id ) ) {
			$stack = $this->get_source( $source_id )->get_font_stack( $font, $default );
		}
		// Search all sources for the stack.
		else {
			$source = $this->get_font_source( $font );
			if ( ! is_null( $source ) ) {
				$stack = $source->get_font_stack( $font, $default );
				$source_id = $source->get_id();
			}
		}

		/**
		 * Allow developers to filter the full font stack.
		 *
		 * @since 1.2.3.
		 *
		 * @param string    $stack    The font stack.
		 * @param string    $font     The font.
		 */
		return apply_filters( 'make_font_stack', $stack, $font, $source_id );
	}

	/**
	 * Get the array of all font choices, or for a particular source.
	 *
	 * If headings are set to true, extra array items will be added as separators between sources.
	 *
	 * @since 1.7.0.
	 *
	 * @param string $source_id
	 * @param bool   $headings
	 *
	 * @return array
	 */
	public function get_font_choices( $source_id = null, $headings = true ) {
		$heading_prefix = 'make-choice-heading-';
		$choices = array();

		// Get choices from a single source
		if ( ! is_null( $source_id ) && $this->has_source( $source_id ) ) {
			$choices = $this->get_source( $source_id )->get_font_choices();

			if ( true === $headings ) {
				$label = $this->get_source( $source_id )->get_label();
				$choices = array_merge( array( $heading_prefix . $source_id => $label ), $choices );
			}

			return $choices;
		}

		// Get all choices
		foreach ( $this->get_sorted_font_sources() as $source_id => $source ) {
			$source_choices = $source->get_font_choices();

			if ( ! empty( $source_choices ) && true === $headings ) {
				$label = $source->get_label();
				$source_choices = array_merge( array( $heading_prefix . $source_id => $label ), $source_choices );
			}

			$choices = array_merge( $choices, $source_choices );
		}

		// Check for deprecated filter
		if ( has_filter( 'make_all_font_choices' ) ) {
			$this->compatibility()->deprecated_hook(
				'make_all_font_choices',
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
			 * Filter the list of font choices.
			 *
			 * @since 1.0.0.
			 * @deprecated 1.7.0.
			 *
			 * @param array $choices
			 */
			$choices = apply_filters( 'make_all_font_choices', $choices );
		}

		return $choices;
	}

	/**
	 * Verify that a font choice is valid. Return a default value if not.
	 *
	 * Increase the efficiency of this method by specifying the font's source.
	 *
	 * @since 1.7.0.
	 *
	 * @param string $value
	 * @param string $source
	 * @param string $default
	 *
	 * @return string
	 */
	public function sanitize_font_choice( $value, $source = null, $default = '' ) {
		// Get fonts from one source, if specified. Otherwise, get all the fonts.
		if ( ! is_null( $source ) && $this->has_source( $source ) ) {
			$allowed_fonts = $this->get_font_choices( $source, false );
		} else {
			$allowed_fonts = $this->get_font_choices( null, false );
		}

		// Find the choice in the font list.
		if ( isset( $allowed_fonts[ $value ] ) ) {
			return $value;
		}

		// Not a valid choice, return the default.
		return $default;
	}
}