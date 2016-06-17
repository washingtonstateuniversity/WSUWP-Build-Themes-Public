<?php
/**
 * @package Make
 */

if ( is_attachment() ) :
	// Always show post-header style image on Attachment view
	$thumb_option   = 'post-header';
	$thumbnail_id   = get_post()->ID;
	$thumbnail_size = make_get_entry_thumbnail_size( $thumb_option );

	$thumbnail_html = '<a href="' . wp_get_attachment_url() . '">' . wp_get_attachment_image( $thumbnail_id, $thumbnail_size ) . '</a>';
else:
	$thumb_option = make_get_thememod_value( 'layout-' . make_get_current_view() . '-featured-images' );
	$thumbnail_id = get_post_thumbnail_id();
	$thumbnail_size = make_get_entry_thumbnail_size( $thumb_option );

	$thumbnail_html = get_the_post_thumbnail( get_the_ID(), $thumbnail_size );
endif;
?>

<?php if ( 'none' !== $thumb_option && ! empty( $thumbnail_html ) ) : ?>
<figure class="entry-thumbnail <?php if ( ! is_attachment() ) echo esc_attr( $thumb_option ); ?>">
	<?php if ( ! is_singular() ) : ?><a href="<?php the_permalink(); ?>" rel="bookmark"><?php endif; ?>
		<?php echo $thumbnail_html; ?>
	<?php if ( ! is_singular() ) : ?></a><?php endif; ?>
	<?php if ( is_singular() && has_excerpt( $thumbnail_id ) ) : ?>
	<figcaption class="entry-thumbnail-caption">
		<?php echo Make()->sanitize()->sanitize_text( get_post( $thumbnail_id )->post_excerpt ); ?>
	</figcaption>
	<?php endif; ?>
</figure>
<?php endif; ?>
