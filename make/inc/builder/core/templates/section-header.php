<?php
/**
 * @package Make
 */

global $ttfmake_section_data;

$ttfmake_section_data['config'] = isset( $ttfmake_section_data['config'] ) ? $ttfmake_section_data['config']: array();
$ttfmake_section_data['label'] = isset( $ttfmake_section_data['label'] ) ? $ttfmake_section_data['label']: '';

$links = array(
	100 => array(
		'href'  => '#',
		'class' => 'ttfmake-section-remove',
		'label' => __( 'Trash section', 'make' ),
		'title' => __( 'Trash section', 'make' ),
	)
);

if ( ! empty( $ttfmake_section_data['config'] ) ) {
	$links[25] = array(
		'href'  => '#',
		'class' => 'ttfmake-section-configure ttfmake-overlay-open',
		'label' => __( 'Configure section', 'make' ),
		'title' => __( 'Configure section', 'make' ),
	);
}

/**
 * Deprecated: Filter the definitions for the links that appear in each Builder section's footer.
 *
 * This filter is deprecated. Use make_builder_section_links instead.
 *
 * @since 1.0.7.
 * @deprecated 1.4.0.
 *
 * @param array    $links    The link definition array.
 */
$links = apply_filters( 'ttfmake_builder_section_footer_links', $links );

/**
 * Filter the definitions for the buttons that appear in each Builder section's header.
 *
 * @since 1.4.0.
 *
 * @param array    $links    The button definition array.
 */
$links = apply_filters( 'make_builder_section_links', $links );
ksort( $links );

/**
 * Filters the rendered HTML class of the section in the Builder.
 *
 * @since 1.9.0.
 *
 * @param string   $class   The current HTML class.
 *
* @return string            The filtered HTML class.
 */
$section_classes = apply_filters( 'make_builder_section_class', '' );
?>

<div class="ttfmake-section {{ 'closed' === data.get('state') ? '' : 'ttfmake-section-open' }} ttfmake-section-{{ data.get('section-type') }} <?php echo $section_classes; ?>" data-id="{{ data.get('id') }}" data-section-type="{{ data.get('section-type') }}">
	<?php
	/**
	 * Execute code before the section header is displayed.
	 *
	 * @since 1.2.3.
	 */
	do_action( 'make_before_section_header' );
	?>
	<div class="ttfmake-section-header">
		<h3{{ (data.get('title')) ? ' class=has-title' : '' }}>
			<span class="ttfmake-section-header-title">{{ data.get('title') }}</span><em><?php echo ( esc_html( $ttfmake_section_data['label'] ) ); ?></em>
			<?php
			/**
			 * Display custom badges.
			 *
			 * @since 1.8.11.
			 */
			do_action( 'make_section_header_badges' );
			?>
		</h3>
		<div class="ttf-make-section-header-button-wrapper">
			<?php foreach ( $links as $link ) : ?>
				<?php
				$href  = ( isset( $link['href'] ) ) ? ' href="' . esc_url( $link['href'] ) . '"' : '';
				$id    = ( isset( $link['id'] ) ) ? ' id="' . esc_attr( $link['id'] ) . '"' : '';
				$label = ( isset( $link['label'] ) ) ? esc_html( $link['label'] ) : '';
				$title = ( isset( $link['title'] ) ) ? ' title="' . esc_html( $link['title'] ) . '"' : '';

				// Set up the class value with a base class
				$class_base = ' class="ttfmake-builder-section-link';
				$class      = ( isset( $link['class'] ) ) ? $class_base . ' ' . esc_attr( $link['class'] ) . '"' : '"';
				?>
				<a<?php echo $href . $id . $class . $title; ?>>
					<span>
						<?php echo $label; ?>
					</span>
				</a>
			<?php endforeach; ?>
		</div>
		<a href="#" class="ttfmake-section-toggle" title="<?php esc_attr_e( 'Click to toggle', 'make' ); ?>">
			<div class="ttfmake-section-toggle__wrapper">
				<span class="ttfmake-section-toggle__indicator"></span>
			</div>
		</a>
	</div>
	<div class="clear"></div>
	<div class="ttfmake-section-body">
