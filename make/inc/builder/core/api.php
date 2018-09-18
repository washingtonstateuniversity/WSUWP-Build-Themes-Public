<?php
/**
 * @package Make
 */

if ( ! class_exists( 'TTFMAKE_Sections' ) ) :
/**
 * Collector for builder sections.
 *
 * @since 1.0.0.
 *
 * Class TTFMAKE_Sections
 */
class TTFMAKE_Sections {
	/**
	 * The sections for the builder.
	 *
	 * @since 1.0.0.
	 *
	 * @var   array    The sections for the builder.
	 */
	private $_sections = array();

	/**
	 * The one instance of TTFMAKE_Sections.
	 *
	 * @since 1.0.0.
	 *
	 * @var   TTFMAKE_Sections
	 */
	private static $instance;

	/**
	 * Instantiate or return the one TTFMAKE_Sections instance.
	 *
	 * @since  1.0.0.
	 *
	 * @return TTFMAKE_Sections
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Create a new section.
	 *
	 * @since  1.0.0.
	 *
	 * @return TTFMAKE_Sections
	 */
	public function __construct() {
		// Run wpautop when saving the data
		add_filter( 'make_the_builder_content', 'wpautop' );
	}

	/**
	 * Return the sections.
	 *
	 * @since  1.0.0.
	 *
	 * @return array    The array of sections.
	 */
	public function get_sections() {
		return $this->_sections;
	}

	/**
	 * Add a section.
	 *
	 * @since  1.0.0.
	 *
	 * @param  string    $id                  Unique ID for the section. Alphanumeric characters only.
	 * @param  string    $label               Name to display for the section.
	 * @param  string    $description         Section description.
	 * @param  string    $icon                URL to the icon for the display.
	 * @param  string    $save_callback       Function to save the content.
	 * @param  array     $builder_template    A path or array (section[, item]) of paths to the template(s) used in the builder.
	 * @param  string    $display_template    Path to the template used for the frontend.
	 * @param  int       $order               The order in which to display the item.
	 * @param  string    $path                The path to the template files.
	 * @param  array     $config              Array of configuration options for the section.
	 * @param  array     $custom              Array of additional custom data to be appended to the section.
	 * @return void
	 */
	public function add_section( $id, $label, $icon, $description, $save_callback, $builder_template, $display_template, $order, $path, $config = false, $custom = false ) {

		$section = array(
			'id'               => $id,
			'label'            => $label,
			'icon'             => $icon,
			'description'      => $description,
			'save_callback'    => $save_callback,
			'builder_template' => $builder_template,
			'display_template' => $display_template,
			'order'            => $order,
			'path'             => $path,
			'config'           => ttfmake_get_sections_settings( $id ),
		);

		/**
		 * Allow the added sections to be filtered.
		 *
		 * This filters allows for dynamically altering sections as they get added. This can help enforce policies for
		 * sections by sanitizing the registered values.
		 *
		 * @since 1.2.3.
		 *
		 * @param array    $section    The section being added.
		 */
		$this->_sections[ $id ] = apply_filters( 'make_add_section', $section );
	}

	/**
	 * Remove a section.
	 *
	 * @since  1.0.7.
	 *
	 * @param  string    $id    Unique ID for an existing section. Alphanumeric characters only.
	 * @return void
	 */
	public function remove_section( $id ) {
		if ( isset( $this->_sections[ $id ] ) ) {
			unset( $this->_sections[ $id ] );
		}
	}

	/**
	 * An array of defaults for all the Builder section settings
	 *
	 * @since  1.0.4.
	 *
	 * @return array    The section defaults.
	 */
	public function get_section_defaults() {
		$defaults = array();

		/**
		 * Filter the section defaults.
		 *
		 * @since 1.2.3.
		 *
		 * @param array    $defaults    The default section data
		 */
		return apply_filters( 'make_sections_defaults', $defaults );
	}

	/**
	 * Define the choices for section setting dropdowns.
	 *
	 * @since  1.0.4.
	 *
	 * @param  string    $key             The key for the section setting.
	 * @param  string    $section_type    The section type.
 	 * @return array                      The array of choices for the section setting.
	 */
	public function get_choices( $key, $section_type ) {
		$choices = array();

		/**
		 * Filter the section choices.
		 *
		 * @since 1.2.3.
		 *
		 * @param array    $choices         The default section choices.
		 * @param string   $key             The key for the data.
		 * @param string   $section_type    The type of section this relates to.
		 */
		return apply_filters( 'make_section_choices', $choices, $key, $section_type );
	}

	/**
	 * Define the sections settings.
	 *
	 * @since  1.8.10.
	 *
	 * @return array        The array of choices for the section setting.
	 */
	public function get_settings( $section_type = false ) {
		/**
		 * Filter the sections settings.
		 *
		 * @since 1.8.10.
		 *
		 * @param array    $settings        The section settings.
		 */
		$settings = apply_filters( 'make_sections_settings', array() );

		foreach ( $settings as $_section_type => $section_settings ) {
			/**
			 * Filter the default section data that is received.
			 *
			 * @since 1.8.10.
			 *
			 * @param string    $section_settings        Array of current section settings.
			 * @param string    $section_type            The type of section the data is for.
			 * @return mixed                             Array of settings if found; false if not found.
			 */
			$section_settings = apply_filters( 'make_section_settings', $section_settings, $_section_type );
			ksort( $section_settings, SORT_NUMERIC );
			$settings[$_section_type] = array_values( $section_settings );
		}

		if ( $section_type && isset( $settings[$section_type] ) ) {
			return $settings[$section_type];
		}

		return $settings;
	}

	/**
	 * Duplicate of "the_content" with custom filter name for generating content in builder templates.
	 *
	 * @since  1.0.0.
	 *
	 * @param  string    $content    The original content.
	 * @return void
	 */
	public function the_builder_content( $content ) {
		/**
		 * Filter the content used for "post_content" when the builder is used to generate content.
		 *
		 * @since 1.2.3.
		 * @deprecated 1.7.0.
		 *
		 * @param string    $content    The post content.
		 */
		$content = apply_filters( 'ttfmake_the_builder_content', $content );

		/**
		 * Filter the content used for "post_content" when the builder is used to generate content.
		 *
		 * @since 1.2.3.
		 *
		 * @param string    $content    The post content.
		 */
		$content = apply_filters( 'make_the_builder_content', $content );

		$content = str_replace( ']]>', ']]&gt;', $content );

		return $content;
	}

	/**
	 * Get the next section's data.
	 *
	 * @since  1.0.0.
	 *
	 * @param  array    $current_section    The current section's data.
	 * @param  array    $sections           The list of sections.
	 * @return array                        The next section's data.
	 */
	public function get_next_section_data( $current_section, $sections ) {
		$next_is_the_one = false;
		$next_data       = array();
		foreach ( $sections as $id => $data ) {
			if ( true === $next_is_the_one ) {
				$next_data = $data;
				break;
			}
			if ( $current_section['id'] == $id ) {
				$next_is_the_one = true;
			}
		}
		/**
		 * Allow developers to alter the "next" section data.
		 *
		 * @since 1.2.3.
		 *
		 * @param array    $next_data          The data for the next section.
		 * @param array    $current_section    The data for the current section.
		 * @param array    $sections           The list of all sections.
		 */
		return apply_filters( 'make_get_next_section_data', $next_data, $current_section, $sections );
	}

	/**
	 * Get the previous section's data.
	 *
	 * @since  1.0.0.
	 *
	 * @param  array    $current_section    The current section's data.
	 * @param  array    $sections           The list of sections.
	 * @return array                        The previous section's data.
	 */
	public function get_prev_section_data( $current_section, $sections ) {
		foreach ( $sections as $id => $data ) {
			if ( $current_section['id'] == $id ) {
				break;
			} else {
				$prev_key = $id;
			}
		}
		$prev_section = ( isset( $prev_key ) && isset( $sections[ $prev_key ] ) ) ? $sections[ $prev_key ] : array();
		/**
		 * Allow developers to alter the "next" section data.
		 *
		 * @since 1.2.3.
		 *
		 * @param array    $prev_section       The data for the next section.
		 * @param array    $current_section    The data for the current section.
		 * @param array    $sections           The list of all sections.
		 */
		return apply_filters( 'make_get_prev_section_data', $prev_section, $current_section, $sections );
	}

	/**
	 * Prepare the HTML id for a section.
	 *
	 * @since 1.6.0.
	 *
	 * @param $current_section
	 *
	 * @return mixed|void
	 */
	public function section_html_id( $current_section ) {
		$prefix = 'builder-section-';
		$id = sanitize_title_with_dashes( $current_section['id'] );

		/**
		 * Filter the section wrapper's HTML id attribute.
		 *
		 * @since 1.6.0.
		 *
		 * @param string    $section_id         The string used in the section's HTML id attribute.
		 * @param array     $current_section    The data for the section.
		 */
		return apply_filters( 'make_section_html_id', $prefix . $id, $current_section );
	}

	/**
	 * Prepare the HTML classes for a section.
	 *
	 * Includes the current section data and an array of the sections in the layout.
	 *
	 * @since  1.0.0.
	 *
	 * @param  array     $current_section    The current section's data.
	 * @param  array     $sections           The list of sections.
	 * @return string                        The class string.
	 */
	public function section_html_classes( $current_section, $sections ) {
		$prefix = 'builder-section-';

		// Get the current section type
		$current = ( isset( $current_section['section-type'] ) ) ? $prefix . $current_section['section-type'] : '';

		// Prepend default classes
		$current = 'builder-section ' . $current;

		// Get the next section's type
		$next_data = $this->get_next_section_data( $current_section, $sections );
		$next      = ( ! empty( $next_data ) && isset( $next_data['section-type'] ) ) ? $prefix . 'next-' . $next_data['section-type'] : $prefix . 'last';

		// Get the previous section's type
		$prev_data = $this->get_prev_section_data( $current_section, $sections );
		$prev      = ( ! empty( $prev_data ) && isset( $prev_data['section-type'] ) ) ? $prefix . 'prev-' . $prev_data['section-type'] : $prefix . 'first';

		$html_classes = $prev . ' ' . $current . ' ' . $next;

		// Background
		$bg_color = ( isset( $current_section['background-color'] ) && ! empty( $current_section['background-color'] ) );
		$bg_image = ( isset( $current_section['background-image'] ) && 0 !== absint( $current_section['background-image'] ) );

		if ( true === $bg_color || true === $bg_image ) {
			$html_classes .= ' has-background';
		}

		// Full width
		$full_width = isset( $current_section['full-width'] ) && 0 !== absint( $current_section['full-width'] );

		if ( true === $full_width ) {
			$html_classes .= ' builder-section-full-width';
		}

		/**
		 * Filter the section classes.
		 *
		 * @since 1.2.3.
		 *
		 * @param string    $classes            The sting of classes.
		 * @param array     $current_section    The array of data for the current section.
		 */
		$html_classes = apply_filters( 'make_section_classes', $html_classes, $current_section );

		return $html_classes;
	}

	/**
	 * Prepare the HTML style for a section.
	 *
	 * Includes the current section data.
	 *
	 * @since  1.9.0.
	 *
	 * @param  array     $current_section    The current section's data.
	 * @return string                        The class styles.
	 */
	public function section_html_style( $current_section ) {
		$style = '';

		// Background color
		if ( isset( $current_section['background-color'] ) && ! empty( $current_section['background-color'] ) ) {
			$style .= 'background-color:' . maybe_hash_hex_color( $current_section['background-color'] ) . ';';
		}

		// Background image
		if ( isset( $current_section['background-image'] ) && 0 !== absint( $current_section['background-image'] ) ) {
			$image_src = ttfmake_get_image_src( $current_section['background-image'], 'full' );
			if ( isset( $image_src[0] ) ) {
				$style .= 'background-image: url(\'' . addcslashes( esc_url_raw( $image_src[0] ), '"' ) . '\');';
			}
		}

		// Background style
		if ( isset( $current_section['background-style'] ) && ! empty( $current_section['background-style'] ) ) {
			if ( in_array( $current_section['background-style'], array( 'cover', 'contain' ) ) ) {
				$style .= 'background-size: ' . $current_section['background-style'] . '; background-repeat: no-repeat;';
			} else if ( 'tile' === $current_section['background-style'] ) {
				$style .= 'background-repeat: repeat;';
			}
		}

		// Background position
		if ( isset( $current_section['background-position'] ) && ! empty( $current_section['background-position'] ) ) {
			$rule = explode( '-', $current_section['background-position'] );
			$style .= 'background-position: ' . implode( ' ', $rule ) . ';';
		}

		return $style;
	}
}
endif;

if ( ! function_exists( 'ttfmake_get_sections_class' ) ) :
/**
 * Instantiate or return the one TTFMAKE_Sections instance.
 *
 * @since  1.0.0.
 *
 * @return TTFMAKE_Sections
 */
function ttfmake_get_sections_class() {
	return TTFMAKE_Sections::instance();
}
endif;

if ( ! function_exists( 'ttfmake_get_sections' ) ) :
/**
 * Get the registered sections.
 *
 * @since  1.0.0.
 *
 * @return array    The list of registered sections.
 */
function ttfmake_get_sections() {
	return ttfmake_get_sections_class()->get_sections();
}
endif;

if ( ! function_exists( 'ttfmake_get_section_definition' ) ) :
/**
 * Get a registered section definition.
 *
 * @since  1.8.12.
 *
 * @return array    The defined properties for the section.
 */
function ttfmake_get_section_definition( $section_type ) {
	$section_definitions = ttfmake_get_sections();

	if ( isset( $section_definitions[$section_type] ) ) {
		return $section_definitions[$section_type];
	}

	return false;
}
endif;

if ( ! function_exists( 'ttfmake_get_sections_by_order' ) ) :
/**
 * Get the registered sections by the order parameter.
 *
 * @since  1.0.0.
 *
 * @return array    The list of registered sections in the parameter order.
 */
function ttfmake_get_sections_by_order() {
	$sections = ttfmake_get_sections_class()->get_sections();
	usort( $sections, 'ttfmake_sorter' );
	return $sections;
}
endif;

if ( ! function_exists( 'ttfmake_sorter' ) ) :
/**
 * Callback for `usort()` that sorts sections by order.
 *
 * @since  1.0.0.
 *
 * @param  mixed    $a    The first element.
 * @param  mixed    $b    The second element.
 * @return mixed          The result.
 */
function ttfmake_sorter( $a, $b ) {
	return $a['order'] - $b['order'];
}
endif;

if ( ! function_exists( 'ttfmake_add_section' ) ) :
/**
 * Add a section.
 *
 * @since  1.0.0.
 *
 * @param  string    $id                  Unique ID for the section. Alphanumeric characters only.
 * @param  string    $label               Name to display for the section.
 * @param  string    $description         Section description.
 * @param  string    $icon                URL to the icon for the display.
 * @param  string    $save_callback       Function to save the content.
 * @param  string    $builder_template    Path to the template used in the builder.
 * @param  string    $display_template    Path to the template used for the frontend.
 * @param  int       $order               The order in which to display the item.
 * @param  string    $path                The path to the template files.
 * @param  array     $config              Array of configuration options for the section.
 * @param  array     $custom              Array of additional custom data to be appended to the section.
 * @return void
 */
function ttfmake_add_section( $id, $label, $icon, $description, $save_callback, $builder_template, $display_template, $order, $path, $config = false, $custom = false ) {
	ttfmake_get_sections_class()->add_section( $id, $label, $icon, $description, $save_callback, $builder_template, $display_template, $order, $path, $config, $custom );
}
endif;

if ( ! function_exists( 'ttfmake_remove_section' ) ) :
/**
 * Remove a defined section.
 *
 * @since  1.0.7.
 *
 * @param  string    $id    Unique ID for an existing section. Alphanumeric characters only.
 * @return void
 */
function ttfmake_remove_section( $id ) {
	ttfmake_get_sections_class()->remove_section( $id );
}
endif;

if ( ! function_exists( 'ttfmake_get_sections_defaults' ) ) :
/**
 * Return the default value for a particular section setting.
 *
 * @since 1.8.12.
 *
 * @return mixed 	Default sections values.
 */
function ttfmake_get_sections_defaults() {
	return ttfmake_get_sections_class()->get_section_defaults();
}
endif;

if ( ! function_exists( 'ttfmake_get_section_defaults' ) ) :
/**
 * Return the default value for a particular section setting.
 *
 * @since 1.8.12.
 *
 * @return mixed 	Default sections values.
 */
function ttfmake_get_section_defaults( $section_type ) {
	$defaults = ttfmake_get_sections_defaults();

	if ( isset( $defaults[$section_type] ) ) {
		return $defaults[$section_type];
	}

	return false;
}
endif;

if ( ! function_exists( 'ttfmake_get_section_default' ) ) :
/**
 * Return the default value for a particular section setting.
 *
 * @since 1.0.4.
 *
 * @param  string    $key             The key for the section setting.
 * @param  string    $section_type    The section type.
 * @return mixed                      Default value if found; false if not found.
 */
function ttfmake_get_section_default( $key, $section_type ) {
	$defaults = ttfmake_get_sections_defaults();
	$value = false;

	if ( isset( $defaults[$section_type] ) && isset( $defaults[$section_type][$key] ) ) {
		$value = $defaults[$section_type][$key];
	}

	/**
	 * Filter the default section data that is received.
	 *
	 * @since 1.2.3.
	 *
	 * @param mixed     $value           The section value.
	 * @param string    $key             The key to get data for.
	 * @param string    $section_type    The type of section the data is for.
	 */
	return apply_filters( 'make_get_section_default', $value, $key, $section_type );
}
endif;

if ( ! function_exists( 'ttfmake_get_section_choices' ) ) :
/**
 * Wrapper function for TTFMAKE_Section_Definitions->get_choices
 *
 * @since 1.0.4.
 *
 * @param  string    $key             The key for the section setting.
 * @param  string    $section_type    The section type.
 * @return array                      The array of choices for the section setting.
 */
function ttfmake_get_section_choices( $key, $section_type ) {
	return ttfmake_get_sections_class()->get_choices( $key, $section_type );
}
endif;

if ( ! function_exists( 'ttfmake_sanitize_section_choice' ) ) :
/**
 * Sanitize a value from a list of allowed values.
 *
 * @since 1.0.4.
 *
 * @param  string|int $value The current value of the section setting.
 * @param  string        $key             The key for the section setting.
 * @param  string        $section_type    The section type.
 * @return mixed                          The sanitized value.
 */
function ttfmake_sanitize_section_choice( $value, $key, $section_type ) {
	$choices         = ttfmake_get_section_choices( $key, $section_type );
	$allowed_choices = array_keys( $choices );

	if ( ! in_array( $value, $allowed_choices ) ) {
		$value = ttfmake_get_section_default( $key, $section_type );
	}

	/**
	 * Allow developers to alter a section choice during the sanitization process.
	 *
	 * @since 1.2.3.
	 *
	 * @param mixed     $value           The value for the section choice.
	 * @param string    $key             The key for the section choice.
	 * @param string    $section_type    The section type.
	 */
	return apply_filters( 'make_sanitize_section_choice', $value, $key, $section_type );
}
endif;

if ( ! function_exists( 'ttfmake_get_section_settings' ) ) :
/**
 * Return the default value for a particular section setting.
 *
 * @since 1.8.10.
 *
 * @param  string    $key             The key for the section setting.
 * @param  string    $section_type    The section type.
 * @return mixed                      Default value if found; false if not found.
 */
function ttfmake_get_sections_settings( $section_type = false ) {
	$settings = ttfmake_get_sections_class()->get_settings( $section_type );
	return $settings;
}
endif;

if ( ! function_exists( 'ttfmake_get_template' ) ) :
/**
 * Load a section front- or back-end section template. Searches for child theme versions
 * first, then parent themes, then plugins.
 *
 * @since  1.9.0.
 *
 * @param  string    $slug    The slug name for the generic template.
 * @param  string    $name    The name of the specialised template.
 * @return void
 */
function ttfmake_get_template( $slug, $name = '' ) {
	$templates = array();
	$paths = array(
		get_stylesheet_directory() . '/',
		get_template_directory() . '/inc/'
	);
	$slug = ltrim( $slug, '/' );

	if ( '' !== $name ) {
		$templates[] = "{$slug}-{$name}.php";
	}

	$templates[] = "{$slug}.php";

	if ( Make()->plus()->is_plus() ) {
		$paths[] = makeplus_get_plugin_directory() . '/inc/';
	}

	foreach ( $templates as $template ) {
		foreach( $paths as $path ) {
			$template_file = $path . $template;

			/**
			 * Filter the template to try and load.
			 *
			 * @since 1.9.0.
			 *
			 * @param array    $templates    The template file to load.
			 * @param string   $slug         The template slug.
			 * @param string   $name         The optional template name.
			 */
			$template_file = apply_filters( 'make_load_section_template', $template_file, $slug, $name );

			if ( file_exists( $template_file ) ) {
				return require( $template_file );
			}
		}
	}

	get_template_part( $slug, $name );
}
endif;

if ( ! function_exists( 'ttfmake_get_section_data' ) ) :
/**
 * MISSING DOCS
 *
 */
function ttfmake_get_section_data() {
	global $ttfmake_section_data;

	return $ttfmake_section_data;
}
endif;

if ( ! function_exists( 'ttfmake_get_section_field' ) ) :
/**
 * Returns the value of the specified section setting.
 *
 * @since 1.9.0.
 *
 * @param  string    $field           The key for the section setting.
 *
 * @return string                     The saved value as string,
 *                                    or default value if not set,
 *                                    or false if the field doesn't exist.
 */
function ttfmake_get_section_field( $field ) {
	global $ttfmake_section_data;

	$value = ttfmake_get_section_default( $field, $ttfmake_section_data['section-type'] );
	$value = false !== $value && $value || '';

	if ( isset( $ttfmake_section_data[$field] ) ) {
		$value = $ttfmake_section_data[$field];
	}

	return $value;
}
endif;

if ( ! function_exists( 'ttfmake_get_content' ) ) :
/**
 * Returns the input string as filtered content.
 *
 * @since 1.9.0.
 *
 * @param  string    $content         The content string.
 *
 * @return string                     The filtered content string.
 */
function ttfmake_get_content( $content ) {
	return ttfmake_get_sections_class()->the_builder_content( $content );
}
endif;

if ( ! function_exists( 'ttfmake_get_image' ) ) :
/**
 * Get an image to display in page builder backend or front end template.
 *
 * This function allows image IDs defined with a negative number to surface placeholder images. This allows templates to
 * approximate real content without needing to add images to the user's media library.
 *
 * @since  1.0.4.
 *
 * @param  int       $image_id    The attachment ID. Dimension value IDs represent placeholders (100x150).
 * @param  string    $size        The image size.
 * @return string                 HTML for the image. Empty string if image cannot be produced.
 */
function ttfmake_get_image( $image_id, $size ) {
	$return = '';

	if ( false === strpos( $image_id, 'x' ) ) {
		$return = wp_get_attachment_image( $image_id, $size );
	} else {
		$image = ttfmake_get_placeholder_image( $image_id );

		if ( ! empty( $image ) && isset( $image['src'] ) && isset( $image['alt'] ) && isset( $image['class'] ) && isset( $image['height'] ) && isset( $image['width'] ) ) {
			$return = '<img src="' . $image['src'] . '" alt="' . $image['alt'] . '" class="' . $image['class'] . '" height="' . $image['height'] . '" width="' . $image['width'] . '" />';
		}
	}

	/**
	 * Filter the image HTML.
	 *
	 * @since 1.2.3.
	 *
	 * @param string    $return      The image HTML.
	 * @param int       $image_id    The ID for the image.
	 * @param bool      $size        The requested image size.
	 */
	return apply_filters( 'make_get_image', $return, $image_id, $size );
}
endif;

global $ttfmake_placeholder_images;

if ( ! function_exists( 'ttfmake_get_placeholder_image' ) ) :
/**
 * Gets the specified placeholder image.
 *
 * @since  1.0.4.
 *
 * @param  int      $image_id    Image ID. Should be a dimension value (100x150).
 * @return array                 The image data, including 'src', 'alt', 'class', 'height', and 'width'.
 */
function ttfmake_get_placeholder_image( $image_id ) {
	global $ttfmake_placeholder_images;
	$return = array();

	if ( isset( $ttfmake_placeholder_images[ $image_id ] ) ) {
		$return = $ttfmake_placeholder_images[ $image_id ];
	}

	/**
	 * Filter the image source attributes.
	 *
	 * @since 1.2.3.
	 *
	 * @param string    $return                        The image source attributes.
	 * @param int       $image_id                      The ID for the image.
	 * @param bool      $ttfmake_placeholder_images    The list of placeholder images.
	 */
	return apply_filters( 'make_get_placeholder_image', $return, $image_id, $ttfmake_placeholder_images );
}
endif;

if ( ! function_exists( 'ttfmake_register_placeholder_image' ) ) :
/**
 * Add a new placeholder image.
 *
 * @since  1.0.4.
 *
 * @param  int      $id      The ID for the image. Should be a dimension value (100x150).
 * @param  array    $data    The image data, including 'src', 'alt', 'class', 'height', and 'width'.
 * @return void
 */
function ttfmake_register_placeholder_image( $id, $data ) {
	global $ttfmake_placeholder_images;
	$ttfmake_placeholder_images[ $id ] = $data;
}
endif;

if ( ! function_exists( 'ttfmake_get_image_src' ) ) :
/**
 * Get an image's src.
 *
 * @since  1.0.4.
 *
 * @param  int       $image_id    The attachment ID. Dimension value IDs represent placeholders (100x150).
 * @param  string    $size        The image size.
 * @return string                 URL for the image.
 */
function ttfmake_get_image_src( $image_id, $size ) {
	$src = '';

	if ( false === strpos( $image_id, 'x' ) ) {
		$image = wp_get_attachment_image_src( $image_id, $size );

		if ( false !== $image && isset( $image[0] ) ) {
			$src = $image;
		}
	} else {
		$image = ttfmake_get_placeholder_image( $image_id );

		if ( isset( $image['src'] ) ) {
			$wp_src = array(
				0 => $image['src'],
				1 => $image['width'],
				2 => $image['height'],
			);
			$src = array_merge( $image, $wp_src );
		}
	}

	/**
	 * Filter the image source attributes.
	 *
	 * @since 1.2.3.
	 *
	 * @param string    $src         The image source attributes.
	 * @param int       $image_id    The ID for the image.
	 * @param bool      $size        The requested image size.
	 */
	return apply_filters( 'make_get_image_src', $src, $image_id, $size );
}
endif;

if ( ! function_exists( 'ttfmake_get_section_html_id' ) ) :
/**
 * Returns the current section HTML id.
 *
 * @since 1.9.0.
 *
 * @return string       The section id.
 */
function ttfmake_get_section_html_id() {
	global $ttfmake_section_data;

	return ttfmake_get_sections_class()->section_html_id( $ttfmake_section_data );
}
endif;

if ( ! function_exists( 'ttfmake_get_section_html_class' ) ) :
/**
 * Returns the current section HTML class.
 *
 * @since 1.9.0.
 *
 * @return string   The section HTML class.
 */
function ttfmake_get_section_html_class() {
	global $ttfmake_section_data, $ttfmake_sections;
	$classes = ttfmake_get_sections_class()->section_html_classes( $ttfmake_section_data, $ttfmake_sections );

	/**
	 * Filters the rendered HTML class of the section.
	 *
	 * @since 1.9.0.
	 *
	 * @param string   $classes                 The current HTML class.
	 * @param array    $ttfmake_section_data    The data of the current section.
	 * @param array    $ttfmake_sections        A list of sections in the current layout.
	 *
 	 * @return string                           The filtered HTML class.
	 */
	return apply_filters( 'make_section_html_class', $classes, $ttfmake_section_data, $ttfmake_sections );
}
endif;

if ( ! function_exists( 'ttfmake_get_section_html_style' ) ) :
/**
 * Returns the current section HTML style.
 *
 * @since 1.9.0.
 *
 * @return string       The section HTML style.
 */
function ttfmake_get_section_html_style() {
	global $ttfmake_section_data, $ttfmake_sections;
	$style = ttfmake_get_sections_class()->section_html_style( $ttfmake_section_data );

	/**
	 * Filters the rendered HTML style of the section.
	 *
	 * @since 1.9.0.
	 *
	 * @param string   $style                   The current HTML style.
	 * @param array    $ttfmake_section_data    The data of the current section.
	 * @param array    $ttfmake_sections        A list of sections in the current layout.
	 *
 	 * @return string                           The filtered HTML style.
	 */
	return apply_filters( 'make_section_html_style', $style, $ttfmake_section_data, $ttfmake_sections );
}
endif;

if ( ! function_exists( 'ttfmake_get_section_item_html_class' ) ) :
/**
 * Returns the current section item HTML class.
 *
 * @since 1.9.0.
 *
 * @param array   $item_data   The current section item data.
 *
 * @return string              The item HTML class.
 */
function ttfmake_get_section_item_html_class( $item_data ) {
	global $ttfmake_section_data;
	$classes = '';

	/**
	 * Filters the rendered HTML class of the section item.
	 *
	 * @since 1.9.0.
	 *
	 * @param string   $classes                 The current HTML class.
	 * @param array    $item_data               The data of the current section item.
	 * @param array    $ttfmake_section_data    The data of the current section.
	 *
 	 * @return string                           The filtered HTML class.
	 */
	return apply_filters( 'make_section_item_html_class', $classes, $item_data, $ttfmake_section_data );
}
endif;

if ( ! function_exists( 'ttfmake_get_section_item_html_style' ) ) :
/**
 * Returns the current section item HTML style.
 *
 * @since 1.9.0.
 *
 * @param array   $item_data   The current section item data.
 *
 * @return string              The current section item HTML style.
 */
function ttfmake_get_section_item_html_style( $item_data ) {
	global $ttfmake_section_data;
	$style = '';

	/**
	 * Filters the rendered HTML style of the section item.
	 *
	 * @since 1.9.0.
	 *
	 * @param string   $style                   The current HTML style.
	 * @param array    $item_data               The data of the current section item.
	 * @param array    $ttfmake_section_data    The data of the current section.
	 *
 	 * @return string                           The filtered HTML style.
	 */
	return apply_filters( 'make_section_item_html_style', $style, $item_data, $ttfmake_section_data );
}
endif;

if ( ! function_exists( 'ttfmake_get_section_item_html_attrs' ) ) :
/**
 * Returns the current section item HTML attributes.
 *
 * @since 1.9.0.
 *
 * @param array   $item_data   The current section item data.
 *
 * @return string              The current section item HTML attributes.
 */
function ttfmake_get_section_item_html_attrs( $item_data ) {
	global $ttfmake_section_data;
	$attrs = '';

	/**
	 * Filters the rendered HTML attributes of the section item.
	 *
	 * @since 1.9.0.
	 *
	 * @param string   $attrs                   The current HTML attrs.
	 * @param array    $item_data               The data of the current section item.
	 * @param array    $ttfmake_section_data    The data of the current section.
	 *
 	 * @return string                           The filtered HTML attrs.
	 */
	return apply_filters( 'make_section_item_html_attrs', $attrs, $item_data, $ttfmake_section_data );
}
endif;

if ( ! function_exists( 'ttfmake_should_render_section' ) ) :
/**
 * Wether or not the specified section should be rendered.
 *
 * @since 1.9.0.
 *
 * @param array   $section_data   The current section item data.
 *
 * @return boolean                Wether or not to render the section. True by default.
 */
function ttfmake_should_render_section( $section_data ) {
	/**
	 * Filters the condition for rendering a section.
	 *
	 * @since 1.9.0.
	 *
	 * @param string   $render                  Wether or not to render the section. True by default.
	 * @param array    $section_data            The data of the current section.
	 *
 	 * @return boolean                          Wether or not to render the section.
	 */
	return apply_filters( 'make_should_render_section', true, $section_data );
}
endif;

if ( ! function_exists( 'ttfmake_get_section_html_attrs' ) ) :
/**
 * Returns the current section HTML attributes.
 *
 * @since 1.9.0.
 *
 * @return string       The section HTML attributes.
 */
function ttfmake_get_section_html_attrs() {
	global $ttfmake_section_data, $ttfmake_sections;
	$attrs = '';

	/**
	 * Filters the rendered HTML attributes of the section.
	 *
	 * @since 1.9.0.
	 *
	 * @param string   $attrs                   The current HTML attributes.
	 * @param array    $ttfmake_section_data    The data of the current section.
	 *
 	 * @return string                           The filtered HTML attributes.
	 */
	return apply_filters( 'make_section_html_attrs', $attrs, $ttfmake_section_data );
}
endif;

if ( ! function_exists( 'ttfmake_sanitize_image_id' ) ) :
/**
 * Cleans an ID for an image.
 *
 * Handles integer or dimension IDs. This function is necessary for handling the cleaning of placeholder image IDs.
 *
 * @since  1.0.0.
 *
 * @param  int|string    $id    Image ID.
 * @return int|string           Cleaned image ID.
 */
function ttfmake_sanitize_image_id( $id ) {
	if ( false !== strpos( $id, 'x' ) ) {
		$pieces       = explode( 'x', $id );
		$clean_pieces = array_map( 'absint', $pieces );
		$id           = implode( 'x', $clean_pieces );
	} else {
		$id = absint( $id );
	}

	return $id;
}
endif;
