<?php
/**
 * @package Make
 */

if ( ! function_exists( 'TTFMAKE_Builder_Save' ) ) :
/**
 * Defines the functionality for the HTML Builder.
 *
 * @since 1.0.0.
 */
class TTFMAKE_Builder_Save {
	/**
	 * The one instance of TTFMAKE_Builder_Save.
	 *
	 * @since 1.0.0.
	 *
	 * @var   TTFMAKE_Builder_Save
	 */
	private static $instance;

	/**
	 * Holds the clean section data.
	 *
	 * @since 1.0.0.
	 *
	 * @var   array
	 */
	private $_sanitized_sections = array();

	/**
	 * Instantiate or return the one TTFMAKE_Builder_Save instance.
	 *
	 * @since  1.0.0.
	 *
	 * @return TTFMAKE_Builder_Save
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Initiate actions.
	 *
	 * @since  1.0.0.
	 *
	 * @return TTFMAKE_Builder_Save
	 */
	public function __construct() {
		// Only add filters when the builder is being saved
		if ( isset( $_POST[ 'ttfmake-builder-nonce' ] ) && wp_verify_nonce( $_POST[ 'ttfmake-builder-nonce' ], 'save' ) ) {
			// Save the post's meta data
			add_action( 'save_post', array( $this, 'save_post' ), 10, 2 );

			// Combine the input into the post's content
			add_filter( 'wp_insert_post_data', array( $this, 'wp_insert_post_data' ), 30, 2 );

			// Write sections to new layout format
			add_action( 'make_builder_data_saved', array( $this, 'save_layout' ), 10, 2 );
		}
	}

	/**
	 * Save section data.
	 *
	 * @since  1.0.0.
	 *
	 * @param  int        $post_id    The ID of the current post.
	 * @param  WP_Post    $post       The post object for the current post.
	 * @return void
	 */
	public function save_post( $post_id, $post ) {
		// Don't do anything during autosave
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Only check permissions for pages since it can only run on pages
		if ( ! current_user_can( 'edit_page', $post_id ) || ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Indicate if the post is a builder post; handled earlier because if won't pass future tests
		if ( isset( $_POST['use-builder'] ) && 1 === (int) $_POST['use-builder'] ) {
			update_post_meta( $post_id, '_ttfmake-use-builder', 1 );
		} else {
			update_post_meta( $post_id, '_ttfmake-use-builder', '' );
		}

		// Don't save data if we're not using the Builder template
		if ( ! ttfmake_will_be_builder_page() ) {
			return;
		}

		// Process and save data
		if ( isset( $_POST[ 'ttfmake-builder-nonce' ] ) && wp_verify_nonce( $_POST[ 'ttfmake-builder-nonce' ], 'save' ) ) {
			$this->save_data( $this->get_sanitized_sections(), $post_id );
		}
	}

	/**
	 * Validate and sanitize the builder section data.
	 *
	 * @since  1.0.0.
	 *
	 * @param  array     $sections     The section data submitted to the server.
	 * @return array                   Array of cleaned section data.
	 */
	public function prepare_data( $sections ) {
		$clean_sections      = array();
		$registered_sections = ttfmake_get_sections();

		// Call the save callback for each section
		foreach ( $sections as $section ) {
			if ( isset( $registered_sections[ $section['section-type'] ]['save_callback'] ) && true === $this->is_save_callback_callable( $registered_sections[ $section['section-type'] ] ) ) {
				/**
				 * Filter the prepared data for an individual section.
				 *
				 * The result of the `call_user_func_array()` call is an array of data representing the data for the
				 * section. This filter allows a developer to alter that data after it is handled.
				 *
				 * @since 1.2.3.
				 *
				 * @param array  $data            The section data.
				 * @param array  $data            The raw section data.
				 * @param string $section_type    The type of section being handled.
				 */
				$id = $section['id'];
				$clean_sections[ $id ]['id'] = strval( $id );
				$clean_sections[ $id ]['state'] = ( isset( $section['state'] ) ) ? sanitize_key( $section['state'] ) : 'open';
				$clean_sections[ $id ]['section-type'] = $section['section-type'];
				$clean_sections[ $id ] = apply_filters( 'make_prepare_data_section', call_user_func_array( $registered_sections[ $section['section-type'] ]['save_callback'], array( $section ) ), $section, $section['section-type'] );
			}
		}

		/**
		 * Filter the full set of data for a post.
		 *
		 * @since 1.2.3.
		 *
		 * @param array    $clean_sections    The clean sections.
		 * @param array    $sections          The raw sections.
		 */
		return apply_filters( 'make_prepare_data', $clean_sections, $sections );
	}

	/**
	 * Save an array of data as individual rows in postmeta.
	 *
	 * @since  1.0.0.
	 *
	 * @param  array     $sections    Array of section data.
	 * @param  string    $post_id     The post ID.
	 * @return void
	 */
	public function save_data( $sections, $post_id ) {
		/**
		 * Save each value in the array as a separate row in the `postmeta` table. This avoids the nasty issue with
		 * array serialization, whereby changing the site domain can lead to the value being unreadable. Instead, each
		 * value is independent.
		 */
		$values_to_save = $this->flatten_array( $sections, '_ttfmake:', ':' );

		foreach ( $values_to_save as $key => $value ) {
			update_post_meta( $post_id, $key, $value );
		}

		// Save the ids for the sections. This will be used to lookup all of the separate values.
		$section_ids = array_keys( $sections );
		update_post_meta( $post_id, '_ttfmake-section-ids', $section_ids );

		/**
		 * Execute code after the section data is saved.
		 *
		 * While it is possible to use a "save_post" to hook into the save routine, this action is preferred as it is
		 * only called after all validation and sanitization is completed.
		 *
		 * @since 1.2.3.
		 *
		 * @param array    $sections    The clean section data.
		 * @param int      $post_id     The post ID for the saved data.
		 */
		do_action( 'make_builder_data_saved', $sections, $post_id );

		// Remove the old section values if necessary
		$this->prune_abandoned_rows( $post_id, $values_to_save );
	}

	public function save_layout( $sections, $post_id ) {
		// Remove legacy layout field
		delete_post_meta( $post_id, '_ttfmake-section-ids' );

		// Remove legacy section metas
		$post_meta = get_post_meta( $post_id );
		foreach ( $post_meta as $key => $value ) {
			if ( 0 === strpos( $key, '_ttfmake:' ) ) {
				delete_post_meta( $post_id, $key );
			}
		}

		$layout = array();

		foreach( $sections as $id => $section ) {
			// Save new section metas
			update_post_meta( $post_id, '_ttfmake_section_' . $section[ 'id' ], $section );

			$layout[] = $section[ 'id' ];
		}

		// Purge removed sections
		$current_layout = get_post_meta( $post_id, '_ttfmake_layout', true );

		if ( ! empty( $current_layout ) ) {
			$current_layout = maybe_unserialize( $current_layout );
			$removed_section_ids = array_diff( $current_layout, $layout );

			foreach ( $removed_section_ids as $section_id ) {
				delete_post_meta( $post_id, "_ttfmake_section_{$section_id}" );
			}
		}

		// Update layout
		update_post_meta( $post_id, '_ttfmake_layout', $layout );
	}

	/**
	 * Remove deprecated section values.
	 *
	 * @since  1.0.0.
	 *
	 * @param  string    $post_id           The post to prune the values.
	 * @param  array     $current_values    The current values that *should* be in the post's postmeta.
	 * @return void
	 */
	public function prune_abandoned_rows( $post_id, $current_values ) {
		// Get all of the metadata associated with the post
		$post_meta = get_post_meta( $post_id );

		// Any meta containing the old keys should be deleted
		if ( is_array( $post_meta ) && ! empty( $post_meta ) ) {
			foreach ( $post_meta as $key => $value ) {
				// Only consider builder values
				if ( 0 === strpos( $key, '_ttfmake:' ) ) {
					if ( ! isset( $current_values[ $key ] ) ) {
						delete_post_meta( $post_id, $key );
					}
				}
			}
		}
	}

	/**
	 * Flatten a multidimensional array.
	 *
	 * @since  1.0.0.
	 *
	 * @param  array     $array        Array to transform.
	 * @param  string    $prefix       The beginning key value.
	 * @param  string    $separator    The value to place between key values.
	 * @return array                   Flattened array.
	 */
	public function flatten_array( $array, $prefix = '', $separator = ':' ) {
		$result = array();

		foreach ( $array as $key => $value ) {
			if ( is_array( $value ) ) {
				$result = $result + $this->flatten_array( $value, $prefix . $key . $separator, $separator );
			}
			else {
				$result[ $prefix . $key ] = $value;
			}
		}

		return $result;
	}

	/**
	 * Determine if the specified save_callback is callable.
	 *
	 * @since  1.0.0.
	 *
	 * @param  array    $section    The registered section data.
	 * @return bool                 True if callback; false if not callable.
	 */
	public function is_save_callback_callable( $section ) {
		$result = false;

		if ( ! empty( $section['save_callback'] ) ) {
			$callback = $section['save_callback'];

			if ( is_array( $callback ) && isset( $callback[0] ) && isset( $callback[1] ) ) {
				$result = method_exists( $callback[0], $callback[1] );
			} elseif ( is_string( $callback ) ) {
				$result = function_exists( $callback );
			}
		}

		return $result;
	}

	/**
	 * On post save, use a theme template to generate content from metadata.
	 *
	 * @since  1.0.0.
	 *
	 * @param  array    $data       The processed post data.
	 * @param  array    $postarr    The raw post data.
	 * @return array                Modified post data.
	 */
	public function wp_insert_post_data( $data, $postarr ) {
		if ( ! ttfmake_will_be_builder_page() || ! isset( $_POST[ 'ttfmake-builder-nonce' ] ) || ! wp_verify_nonce( $_POST[ 'ttfmake-builder-nonce' ], 'save' ) ) {
			return $data;
		}

		// Don't do anything during autosave
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $data;
		}

		// Only check permissions for pages since it can only run on pages
		if ( ! current_user_can( 'edit_page', get_the_ID() ) ) {
			return $data;
		}

		/**
		 * Filter the section data.
		 *
		 * @since 1.2.3.
		 *
		 * @param array    $data   The sanitized data.
		 */
		$sanitized_sections = apply_filters( 'make_insert_post_data_sections', $this->get_sanitized_sections() );

		// The data has been deleted and can be removed
		if ( empty( $sanitized_sections ) ) {
			$data['post_content'] = '';
			return $data;
		}

		// Generate the post content
		$post_content = $this->generate_post_content( $sanitized_sections );

		// Sanitize and set the content
		kses_remove_filters();
		$data['post_content'] = sanitize_post_field( 'post_content', $post_content, get_the_ID(), 'db' );
		kses_init_filters();

		return $data;
	}

	/**
	 * Based on section data, generate a post's post_content.
	 *
	 * @since  1.0.4.
	 *
	 * @param  array     $data    Data for sections used to comprise a page's post_content.
	 * @return string             The post content.
	 */
	public function generate_post_content( $data ) {
		// Handle oEmbeds correctly
		add_filter( 'make_the_builder_content', array( $this, 'embed_handling' ), 8 );
		add_filter( 'embed_handler_html', array( $this, 'embed_handler_html' ) , 10, 3 );
		add_filter( 'embed_oembed_html', array( $this, 'embed_oembed_html' ) , 10, 4 );

		// Remove editor image constraints while rendering section data.
		add_filter( 'editor_max_image_size', array( &$this, 'remove_image_constraints' ) );

		// Set section data as a global to avoid re-querying when
		// templates render through ttfmake_get_section_data
		global $ttfmake_sections, $ttfmake_section_data;
		$ttfmake_sections = $data;
		$post_id = get_the_ID();

		// Start the output buffer to collect the contents of the templates
		ob_start();

		// For each sections, render it using the template
		foreach ( $ttfmake_sections as $section_data ) {
			$ttfmake_section_data = $section_data;
			$section_definition = ttfmake_get_section_definition( $section_data['section-type'] );
			$section_template = $section_definition['display_template'];

			if ( ttfmake_should_render_section( $ttfmake_section_data ) ) {
				ttfmake_get_template( $section_template );
			}
		}

		unset( $GLOBALS['ttfmake_sections'] );
		unset( $GLOBALS['ttfmake_section_data'] );

		// Get the rendered templates from the output buffer
		$post_content = ob_get_clean();

		// Allow constraints again after builder data processing is complete.
		remove_filter( 'editor_max_image_size', array( &$this, 'remove_image_constraints' ) );

		/**
		 * Filter the generated post content.
		 *
		 * This content is the full HTML version of the content that will be saved as "post_content".
		 *
		 * @since 1.2.3.
		 *
		 * @param string    $post_content    The fully generated post content.
		 * @param array     $data            The data used to generate the content.
		 */
		return apply_filters( 'make_generate_post_content', $post_content, $data );
	}

	/**
	 * Run content through the $wp_embed->autoembed method to identify and process oEmbeds.
	 *
	 * This function causes oEmbeds to be identified and HTML to created for those oEmbeds. Additional functions in this
	 * file will not allow the embed code to be saved, but rather wrap the oEmbed url in embed shortcode tags (i.e.,
	 * [embed]url[/embed]).
	 *
	 * In other words, if the following content is passed to this function:
	 *
	 *     https://www.youtube.com/watch?v=jScLjUlLTLI
	 *
	 *     <p>Here is some more content</p>
	 *
	 * it is transformed into:
	 *
	 *     [embed]https://www.youtube.com/watch?v=jScLjUlLTLI[/embed]
	 *
	 *     <p>Here is some more content</p>
	 *
	 * @since  1.0.0.
	 *
	 * @param  string    $content    The content to inspect.
	 * @return string                The modified content.
	 */
	function embed_handling( $content ) {
		global $wp_embed;
		$content = $wp_embed->autoembed( $content );
		return $content;
	}

	/**
	 * Modify the embed HTML to be just the URL wrapped in embed tags.
	 *
	 * @since  1.0.0.
	 *
	 * @param  string    $cache      The previously cached embed value.
	 * @param  string    $url        The embed URL.
	 * @param  array     $attr       The shortcode attrs.
	 * @param  int       $post_ID    The current Post ID.
	 * @return string                The modified embed code.
	 */
	function embed_oembed_html( $cache, $url, $attr, $post_ID ) {
		return $this->generate_embed_shortcode( $url, $attr );
	}

	/**
	 * Modify the embed HTML to be just the URL wrapped in embed tags.
	 *
	 * @since  1.0.0.
	 *
	 * @param  string    $return     The embed code.
	 * @param  string    $url        The embed URL.
	 * @param  array     $attr       The shortcode attrs.
	 * @return string                The modified embed code.
	 */
	function embed_handler_html( $return, $url, $attr ) {
		return $this->generate_embed_shortcode( $url, $attr );
	}

	/**
	 * Wrap a URL in embed shortcode tags.
	 *
	 * This function also will apply shortcode attrs if they are available. It only supports the "height" and "width"
	 * attributes that core supports.
	 *
	 * @since  1.0.0.
	 *
	 * @param  string    $url        The embed URL.
	 * @param  array     $attr       The shortcode attrs.
	 * @return string                The modified embed code.
	 */
	function generate_embed_shortcode( $url, $attr ) {
		$attr_string = '';

		if ( isset( $attr['height'] ) ) {
			$attr_string = ' height="' . absint( $attr['height'] ) . '"';
		}

		if ( isset( $attr['width'] ) ) {
			$attr_string = ' width="' . absint( $attr['width'] ) . '"';
		}

		return '[embed' . $attr_string . ']' . $url . '[/embed]';
	}

	/**
	 * Allows image size to be saved regardless of the content width variable.
	 *
	 * @since  1.0.0.
	 *
	 * @param  array    $dimensions    The default dimensions.
	 * @return array                   The modified dimensions.
	 */
	public function remove_image_constraints( $dimensions ) {
		return array( 9999, 9999 );
	}

	/**
	 * Get the sanitized section data.
	 *
	 * @since  1.0.0.
	 *
	 * @return array    The sanitized section data.
	 */
	public function get_sanitized_sections() {
		if ( empty( $this->_sanitized_sections ) ) {
			$data = array();

			if ( isset( $_POST['ttfmake-section-layout'] ) && ! empty( $_POST['ttfmake-section-layout'] ) ) {
				$section_ids = json_decode( wp_unslash( $_POST['ttfmake-section-layout'] ), true );

				foreach( $section_ids as $section_id ) {
					$section_name = 'ttfmake-section-json-' . $section_id;

					if ( isset( $_POST[ $section_name ] ) ) {
						$section_data = json_decode( wp_unslash( $_POST[ $section_name ] ), true );
						$data[ $section_id ] = $section_data;
					}
				}
			}

			$this->_sanitized_sections = $this->prepare_data( $data );
		}

		return $this->_sanitized_sections;
	}

	/**
	 * Sanitizes a string to only return numbers.
	 *
	 * @since  1.0.0.
	 *
	 * @param  string    $id    The section ID.
	 * @return string           The sanitized ID.
	 */
	public static function clean_section_id( $id ) {
		return preg_replace( '/[^0-9]/', '', $id );
	}
}
endif;

if ( ! function_exists( 'ttfmake_get_builder_save' ) ) :
/**
 * Instantiate or return the one TTFMAKE_Builder_Save instance.
 *
 * @since  1.0.0.
 *
 * @return TTFMAKE_Builder_Save
 */
function ttfmake_get_builder_save() {
	return TTFMAKE_Builder_Save::instance();
}
endif;

add_action( 'admin_init', 'ttfmake_get_builder_save' );
