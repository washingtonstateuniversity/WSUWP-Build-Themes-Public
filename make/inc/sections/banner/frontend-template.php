<?php
/**
 * @package Make
 */
$banner_slides = ttfmake_get_section_field( 'banner-slides' );
$is_slider = ( count( $banner_slides ) > 1 ) ? true : false;
?>

<section id="<?php echo ttfmake_get_section_html_id(); ?>" class="<?php echo esc_attr( ttfmake_get_section_html_class() ); ?>" style="<?php echo esc_attr( ttfmake_get_section_html_style() ); ?>">

	<?php
	$title = ttfmake_get_section_field( 'title' );
	if ( '' !== $title ) : ?>
	<h3 class="builder-banner-section-title">
		<?php echo apply_filters( 'the_title', $title ); ?>
	</h3>
	<?php endif; ?>

	<div class="builder-section-content<?php echo ( $is_slider ) ? ' cycle-slideshow' : ''; ?>"<?php echo ( $is_slider ) ? ttfmake_get_section_html_attrs() : ''; ?>>
		<?php if ( ! empty( $banner_slides ) ) : foreach ( $banner_slides as $slide ) : ?>
		<div class="builder-banner-slide<?php echo ttfmake_get_section_item_html_class( $slide ); ?>" style="<?php echo ttfmake_get_section_item_html_style( $slide ); ?>">
			<div class="builder-banner-content">
				<div class="builder-banner-inner-content">
					<?php echo ttfmake_get_content( $slide['content'] ); ?>
				</div>
			</div>
			<?php if ( 0 !== absint( $slide['darken'] ) ) : ?>
			<div class="builder-banner-overlay"></div>
			<?php endif; ?>
		</div>
		<?php endforeach; endif; ?>
		<?php if ( $is_slider && true === (bool) ttfmake_get_section_field( 'arrows' ) ) : ?>
		<div class="cycle-prev"></div>
		<div class="cycle-next"></div>
		<?php endif; ?>
		<?php if ( $is_slider && true === (bool) ttfmake_get_section_field( 'dots' ) ) : ?>
		<div class="cycle-pager"></div>
		<?php endif; ?>
	</div>

	<?php if ( absint( ttfmake_get_section_field( 'darken' ) ) ) : ?>
	<div class="builder-section-overlay"></div>
	<?php endif; ?>

</section>
