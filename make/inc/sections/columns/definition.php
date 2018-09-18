<?php
/**
 * @package Make
 */

if ( ! class_exists( 'MAKE_Sections_Columns_Definition' ) ) :
/**
 * Section definition for Columns
 *
 * Class MAKE_Sections_Columns_Definition
 */
class MAKE_Sections_Columns_Definition {
	/**
	 * The one instance of MAKE_Sections_Columns_Definition.
	 *
	 * @var   MAKE_Sections_Columns_Definition
	 */
	private static $instance;

	/**
	 * Register the text section.
	 *
	 * Note that in 1.4.0, the "text" section was renamed to "columns". In order to provide good back compatibility,
	 * only the section label is changed to "Columns". All other internal references for this section will remain as
	 * "text".
	 *
	 * @return void
	 */
	public static function register() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct() {
		if ( is_admin() ) {
			add_filter( 'make_section_choices', array( $this, 'section_choices' ), 10, 3 );
			add_filter( 'make_sections_settings', array( $this, 'section_settings' ) );
			add_filter( 'make_sections_defaults', array( $this, 'section_defaults' ) );
			add_filter( 'make_get_section_json', array( $this, 'get_section_json' ), 10, 1 );
			add_filter( 'make_get_section_json', array( $this, 'embed_column_images' ), 20, 1 );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 20 );
			add_action( 'admin_footer', array( $this, 'print_templates' ) );
		}

		add_filter( 'make_section_html_class', array( $this, 'html_class' ), 10, 3 );

		ttfmake_add_section(
			'text',
			__( 'Content', 'make' ),
			Make()->scripts()->get_css_directory_uri() . '/builder/sections/images/text.png',
			__( 'Create rearrangeable content of text and images.', 'make' ),
			array( $this, 'save' ),
			array(
				'text' => 'sections/columns/builder-template',
				'text-item' => 'sections/columns/builder-template-column'
			),
			'sections/columns/frontend-template',
			100,
			get_template_directory() . '/inc/builder/'
		);
	}

	public function get_settings() {
		return array(
			100 => array(
				'type'  => 'divider',
				'label' => __( 'General', 'make' ),
				'name'  => 'divider-general',
				'class' => 'ttfmake-configuration-divider open',
			),
			200 => array(
				'type'  => 'section_title',
				'name'  => 'title',
				'label' => __( 'Enter section title', 'make' ),
				'class' => 'ttfmake-configuration-title ttfmake-section-header-title-input',
				'default' => ttfmake_get_section_default( 'title', 'text' ),
			),
			300 => array(
				'type'    => 'select',
				'name'    => 'columns-number',
				'class'   => 'ttfmake-text-columns',
				'label'   => __( 'Columns', 'make' ),
				'default' => ttfmake_get_section_default( 'columns-number', 'text' ),
				'options' => ttfmake_get_section_choices( 'columns-number', 'text' ),
			),
			500 => array(
				'type'    => 'divider',
				'label'   => __( 'Background', 'make' ),
				'name'    => 'divider-background',
				'class'   => 'ttfmake-configuration-divider',
			),
			600 => array(
				'type'  => 'image',
				'name'  => 'background-image',
				'label' => __( 'Background image', 'make' ),
				'class' => 'ttfmake-configuration-media',
				'default' => ttfmake_get_section_default( 'background-image', 'text' ),
			),
			700 => array(
				'type'  => 'select',
				'name'  => 'background-position',
				'label' => __( 'Position', 'make' ),
				'class' => 'ttfmake-configuration-media-related',
				'default' => ttfmake_get_section_default( 'background-position', 'text' ),
				'options' => ttfmake_get_section_choices( 'background-position', 'text' ),
			),
			800 => array(
				'type'    => 'select',
				'name'    => 'background-style',
				'label'   => __( 'Display', 'make' ),
				'class'   => 'ttfmake-configuration-media-related',
				'default' => ttfmake_get_section_default( 'background-style', 'text' ),
				'options' => ttfmake_get_section_choices( 'background-style', 'text' ),
			),
			900 => array(
				'type'    => 'checkbox',
				'label'   => __( 'Darken', 'make' ),
				'name'    => 'darken',
				'default' => ttfmake_get_section_default( 'darken', 'text' ),
			),
			1000 => array(
				'type'    => 'color',
				'label'   => __( 'Background color', 'make' ),
				'name'    => 'background-color',
				'class'   => 'ttfmake-text-background-color ttfmake-configuration-color-picker',
				'default' => ttfmake_get_section_default( 'background-color', 'text' ),
			),
		);
	}

	/**
	 * Define settings for this section
	 *
	 * @since 1.8.11.
	 *
	 * @hooked filter make_sections_settings
	 *
	 * @param array $settings   The existing array of section settings.
	 *
	 * @return array             The modified array of section settings.
	 */
	public function section_settings( $settings ) {
		$settings['text'] = $this->get_settings();

		return $settings;
	}

	/**
	 * Add new section choices.
	 *
	 * @since 1.8.8.
	 *
	 * @hooked filter make_section_choices
	 *
	 * @param array  $choices         The existing choices.
	 * @param string $key             The key for the section setting.
	 * @param string $section_type    The section type.
	 *
	 * @return array                  The choices for the particular section_type / key combo.
	 */
	public function section_choices( $choices, $key, $section_type ) {
		if ( count( $choices ) > 1 || ! in_array( $section_type, array( 'text' ) ) ) {
			return $choices;
		}

		$choice_id = "$section_type-$key";

		switch ( $choice_id ) {
			case 'text-columns-number':
				$choices = array(
					1 => 1,
					2 => 2,
					3 => 3,
					4 => 4,
					5 => 5,
					6 => 6
				);
				break;

			case 'text-background-style' :
				$choices = array(
					'tile'  => __( 'Tile', 'make' ),
					'cover' => __( 'Cover', 'make' ),
					'contain' => __( 'Contain', 'make' ),
				);
				break;

			case 'text-background-position' :
				$choices = array(
					'center-top'  => __( 'Top', 'make' ),
					'center-center' => __( 'Center', 'make' ),
					'center-bottom' => __( 'Bottom', 'make' ),
					'left-center'  => __( 'Left', 'make' ),
					'right-center' => __( 'Right', 'make' )
				);
				break;
		}

		return $choices;
	}

	/**
	 * Get default values for columns section
	 *
	 * @since 1.8
	 *
	 * @return array
	 */
	public function get_defaults() {
		return array(
			'section-type' => 'text',
			'state' => 'open',
			'title' => '',
			'image-link' => '',
			'columns-number' => 3,
			'background-image' => '',
			'background-position' => 'center-center',
			'darken' => 0,
			'background-style' => 'cover',
			'background-color' => '',
		);
	}

	/**
	 * Get default values for column
	 *
	 * @since 1.8
	 *
	 * @return array
	 */
	public function get_item_defaults() {
		return array(
			'section-type' => 'text-item',
			'content' => '',
			'sidebar-label' => '',
			'widget-area-id' => '',
			'widget-area' => '',
			'widgets' => ''
		);
	}

	/**
	 * Extract the setting defaults and add them to Make's section defaults system.
	 *
	 * @since 1.6.0.
	 *
	 * @hooked filter make_sections_defaults
	 *
	 * @param array $defaults    The existing array of section defaults.
	 *
	 * @return array             The modified array of section defaults.
	 */
	public function section_defaults( $defaults ) {
		$defaults['text'] = $this->get_defaults();
		$defaults['text-item'] = $this->get_item_defaults();

		return $defaults;
	}

	/**
	 * Filter the json representation of this section.
	 *
	 * @since 1.8.0.
	 *
	 * @hooked filter make_get_section_json
	 *
	 * @param array $defaults    The array of data for this section.
	 *
	 * @return array             The modified array to be jsonified.
	 */
	public function get_section_json( $data ) {
		if ( $data['section-type'] == 'text' ) {
			$data = wp_parse_args( $data, $this->get_defaults() );
			$image = ttfmake_get_image_src( $data['background-image'], 'large' );

			if ( isset( $image[0] ) ) {
				$data['background-image-url'] = $image[0];
			} else {
				$data['background-image'] = '';
			}

			if ( isset( $data['columns'] ) && is_array( $data['columns'] ) ) {
				// back compatibility
				if ( isset( $data['columns-order'] ) ) {
					$ordered_items = array();

					foreach ( $data['columns-order'] as $index => $item_id ) {
						array_push( $ordered_items, $data['columns'][$index + 1] );

						if ( array_key_exists( 'sidebar-label', $ordered_items[$index] )
							&& ( $ordered_items[$index]['sidebar-label'] != '' )
							&& empty( $ordered_items[$index]['widget-area-id'] ) ) {

							// index started at 1 before
							$old_index = $index + 1;
							$page_id = get_the_ID();
							$ordered_items[$index]['widget-area-id'] = 'ttfmp-' . $page_id . '-' . $data['id'] . '-' . $old_index;
						}
					}

					$data['columns'] = $ordered_items;
					unset( $data['columns-order'] );
				}

				foreach ( $data['columns'] as $s => $column ) {
					/*
					 * Back compat stuff for versions older than 1.8.6.
					 * Checks if image-id is present.
					 *
					 * This is used in a condition at the end of this foreach
					 * to remove empty columns created when coming from older versions.
					 */
					$column_image_id_set = false;

					if ( isset( $column['image-id'] ) ) {
						$column_image_id_set = true;
					}

					$column = wp_parse_args( $column, $this->get_item_defaults() );

					// Handle legacy data layout
					$id = isset( $column['id'] ) ? $column['id']: $s;
					$column['id'] = $id;

					$column_image = '';

					if ( isset( $column['image-id'] ) ) {
						$column_image = ttfmake_get_image_src( $column['image-id'], 'large' );

						if ( isset( $column_image[0] ) ) {
							$column['image-url'] = $column_image[0];
						}
					}

					if ( isset( $column['sidebar-label'] ) && !empty( $column['sidebar-label'] ) && empty( $column['widget-area-id'] ) ) {
						$column['widget-area-id'] = 'ttfmp-' . get_the_ID() . '-' . $data['id'] . '-' . $column['id'];
					}

					$data['columns'][$s] = $column;

					/*
					 * Checks for an empty columns accidentally created when coming from
					 * versions older than 1.8.6. Then removes those.
					 */
					if ( empty( $column['content'] ) && $column_image_id_set && empty( $column_image ) && empty( $column['sidebar-label'] ) ) {
						unset( $data['columns'][$s] );
					}
				}
			}
		}

		return $data;
	}

	/**
	 * Embeds columns featured images in
	 * columns content.
	 *
	 * @since 1.8.6.
	 *
	 * @hooked filter make_get_section_json
	 *
	 * @param array $defaults    The array of data for this section.
	 *
	 * @return array             The modified array to be jsonified.
	 */
	public function embed_column_images( $data ) {
		if ( $data['section-type'] == 'text' ) {
			foreach ( $data['columns'] as $s => $column ) {
				$image_tag = '';
				$column_title = '';

				if ( isset( $column['title'] ) && '' !== $column['title'] ) {
					$column_title = apply_filters( 'the_title', $column['title'] );
					$column_title = sprintf( '<h3>%s</h3>', $column_title );
				}

				if ( isset( $column['image-id'] ) ) {
					$attachment_id = intval( $column['image-id'] );

					if ( $attachment_id > 0 ) {
						$image_attrs = wp_get_attachment_image_src( $attachment_id, 'full' );
						$image_template = '<img src="%s" width="%s" height="%s" class="alignnone size-full wp-image-%s" />';
						$image_tag = sprintf( $image_template, $image_attrs[0], $image_attrs[1], $image_attrs[2], $attachment_id, $image_tag );

						if ( isset( $column['image-link'] ) && '' !== $column['image-link'] ) {
							$image_link = esc_url_raw( $column['image-link'] );
							$image_tag = sprintf( '<a href="%s">%s</a>', $image_link, $image_tag );
						}

						$image_tag = sprintf( '<p>%s</p>', $image_tag, $image_tag );

						if ( '' == $column_title && '</p>' == substr( $image_tag, -4 ) && '<p>' == substr( $column['content'], 0, 3 ) ) {
							$image_tag = substr( $image_tag, 0, -4 );
							$column['content'] = substr( $column['content'], 3 );
						}
					}
				}

				unset( $column['image-id'] );
				unset( $column['image-url'] );
				unset( $column['image-link'] );
				unset( $column['title'] );

				$column['content'] = $image_tag . $column_title . $column['content'];
				$data['columns'][$s] = $column;
			}
		}

		return $data;
	}

	/**
	 * Save the data for the section.
	 *
	 * @param  array    $data    The data from the $_POST array for the section.
	 * @return array             The cleaned data.
	 */
	public function save( $data ) {
		$clean_data = array(
			'id' => $data['id'],
			'section-type' => $data['section-type'],
			'state' => $data['state'],
		);

		$clean_data['title'] = $clean_data['label'] = ( isset( $data['title'] ) ) ? apply_filters( 'title_save_pre', $data['title'] ) : '';

		if ( isset( $data['columns-number'] ) ) {
			$clean_data['columns-number'] = ttfmake_sanitize_section_choice( $data['columns-number'], 'columns-number', $data['section-type'] );
		}

		if ( isset( $data['background-image'] ) && '' !== $data['background-image'] ) {
			$clean_data['background-image'] = ttfmake_sanitize_image_id( $data['background-image'] );
		} else {
			$clean_data['background-image'] = '';
		}

		if ( isset( $data['background-image-url'] ) && '' !== $data['background-image-url'] ) {
			$clean_data['background-image-url'] = $data['background-image-url'];
		}

		if ( isset( $data['background-position'] ) ) {
			$clean_data['background-position'] = ttfmake_sanitize_section_choice( $data['background-position'], 'background-position', $data['section-type'] );
		}

		if ( isset( $data['darken'] ) && $data['darken'] == 1 ) {
			$clean_data['darken'] = 1;
		} else {
			$clean_data['darken'] = 0;
		}

		if ( isset( $data['background-color'] ) ) {
			$clean_data['background-color'] = maybe_hash_hex_color( $data['background-color'] );
		}

		if ( isset( $data['background-style'] ) ) {
			$clean_data['background-style'] = ttfmake_sanitize_section_choice( $data['background-style'], 'background-style', $data['section-type'] );
		}

		if ( isset( $data['columns'] ) && is_array( $data['columns'] ) ) {
			$clean_data['columns'] = array();

			foreach ( $data['columns'] as $i => $item ) {
				$item = wp_parse_args( $item, $this->get_item_defaults() );

				$id = isset( $item['id'] ) ? $item['id'] : $i;

				$clean_item_data = array(
					'id' => $id,
					'section-type' => $item['section-type']
				);

				if ( isset( $item['content'] ) ) {
					$clean_item_data['content'] = sanitize_post_field( 'post_content', $item['content'], ( get_post() ) ? get_the_ID() : 0, 'db' );
				}

				if ( isset( $item['size'] ) ) {
					$clean_item_data['size'] = esc_attr( $item['size'] );
				}

				if ( isset( $item['sidebar-label'] ) ) {
					$clean_item_data['sidebar-label'] = $item['sidebar-label'];
				}

				array_push( $clean_data['columns'], $clean_item_data );
			}
		}

		return $clean_data;
	}

	public function admin_enqueue_scripts( $hook_suffix ) {
		// Only load resources if they are needed on the current page
		if ( ! in_array( $hook_suffix, array( 'post.php', 'post-new.php' ) ) || ! ttfmake_post_type_supports_builder( get_post_type() ) ) {
			return;
		}

		/**
		 * Filter any available extensions for the Make builder JS.
		 *
		 * @since 1.8.11.
		 *
		 * @param array    $dependencies    The list of dependencies.
		 */
		$dependencies = apply_filters( 'make_builder_js_extensions', array(
			'ttfmake-builder', 'ttfmake-builder-overlay'
		) );

		wp_enqueue_script(
			'builder-section-columns',
			Make()->scripts()->get_js_directory_uri() . '/builder/sections/columns.js',
			$dependencies,
			TTFMAKE_VERSION,
			true
		);
	}

	public function print_templates() {
		global $hook_suffix, $typenow, $ttfmake_section_data;

		// Only show when adding/editing pages
		if ( ! ttfmake_post_type_supports_builder( $typenow ) || ! in_array( $hook_suffix, array( 'post.php', 'post-new.php' ) )) {
			return;
		}

		$section_definitions = ttfmake_get_sections();
		$ttfmake_section_data = $section_definitions[ 'text' ];
		?>
		<script type="text/template" id="tmpl-ttfmake-section-text">
		<?php get_template_part( 'inc/sections/columns/builder-template' ); ?>
		</script>
		<?php $ttfmake_section_data = array(); ?>
		<script type="text/template" id="tmpl-ttfmake-section-text-item">
		<?php get_template_part( 'inc/sections/columns/builder-template', 'column' ); ?>
		</script>
		<?php
	}

	public function html_class( $classes, $section_data, $sections ) {
		if ( 'text' === $section_data['section-type'] ) {
			// Columns
			$columns_number = ( isset( $section_data['columns-number'] ) ) ? absint( $section_data['columns-number'] ) : 1;
			$classes .= ' builder-text-columns-' . $columns_number;

			/**
			 * Filter the text section class.
			 *
			 * @since 1.2.3.
			 *
			 * @param string    $text_class              The computed class string.
			 * @param array     $ttfmake_section_data    The section data.
			 * @param array     $sections                The list of sections.
			 */
			$classes = apply_filters( 'make_builder_get_text_class', $classes, $section_data, $sections );
		}

		return $classes;
	}
}
endif;
