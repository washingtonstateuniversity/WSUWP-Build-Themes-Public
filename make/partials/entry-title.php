<?php
/**
 * @package Make
 */

$title_key    = 'layout-' . make_get_current_view() . '-hide-title';
$title_option = make_get_thememod_value( $title_key );
?>

<?php if ( get_the_title() && ( ! is_singular( array( 'post', 'page' ) ) || ! $title_option ) ) : ?>
<h1 class="entry-title">
	<?php if ( ! is_singular() ) : ?><a href="<?php the_permalink(); ?>" rel="bookmark"><?php endif; ?>
		<?php the_title(); ?>
	<?php if ( ! is_singular() ) : ?></a><?php endif; ?>
</h1>
<?php endif; ?>