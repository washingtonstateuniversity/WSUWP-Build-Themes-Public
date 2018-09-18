<?php
/**
 * @package Make
 */

$gallery = ttfmake_get_section_field( 'gallery-items' );
$captions = ( '' !== ttfmake_get_section_field( 'captions' ) ) ? esc_attr( ttfmake_get_section_field( 'captions' ) ) : 'reveal';
$aspect = ( '' !== ttfmake_get_section_field( 'aspect' ) ) ? esc_attr( ttfmake_get_section_field( 'aspect' ) ) : 'square';
?>

<section id="<?php echo ttfmake_get_section_html_id(); ?>" class="<?php echo esc_attr( ttfmake_get_section_html_class() ); ?>" style="<?php echo esc_attr( ttfmake_get_section_html_style() ); ?>">

	<?php
	$title = ttfmake_get_section_field( 'title' );
	if ( '' !== $title ) : ?>
    <h3 class="builder-gallery-section-title">
        <?php echo apply_filters( 'the_title', $title ); ?>
    </h3>
    <?php endif; ?>

	<div class="builder-section-content">
		<?php if ( ! empty( $gallery ) ) : foreach ( $gallery as $item ) : ?>

		<div class="builder-gallery-item <?php echo esc_attr( ttfmake_get_section_item_html_class( $item ) ); ?>"<?php echo ttfmake_builder_get_gallery_item_onclick( $item ); ?>>

			<?php $image = ttfmake_builder_get_gallery_item_image( $item ); ?>
			<?php if ( '' !== $image ) : ?>
				<?php echo $image; ?>
			<?php endif; ?>

			<?php if ( 'none' !== $captions && ( '' !== $item['title'] || '' !== $item['description'] || has_excerpt( $item['background-image'] ) ) ) : ?>
			<div class="builder-gallery-content">
				<div class="builder-gallery-content-inner">
					<?php if ( '' !== $item['title'] ) : ?>
					<h4 class="builder-gallery-title">
						<?php echo apply_filters( 'the_title', $item['title'] ); ?>
					</h4>
					<?php endif; ?>
					<?php if ( '' !== $item['description'] ) : ?>
					<div class="builder-gallery-description">
						<?php echo ttfmake_get_content( $item['description'] ); ?>
					</div>
					<?php elseif ( has_excerpt( $item['background-image'] ) ) : ?>
					<div class="builder-gallery-description">
						<?php echo Make()->sanitize()->sanitize_text( get_post( $item['background-image'] )->post_excerpt ); ?>
					</div>
					<?php endif; ?>
				</div>
			</div>
			<?php endif; ?>
		</div>
		<?php endforeach; endif; ?>
	</div>

	<?php if ( absint( ttfmake_get_section_field( 'darken' ) ) ) : ?>
	<div class="builder-section-overlay"></div>
	<?php endif; ?>

</section>
