<?php
/**
 * @package Make
 */
?>

<?php if ( get_next_posts_link() || get_previous_posts_link() ) : ?>
<nav class="navigation paging-navigation" role="navigation">
	<span class="screen-reader-text"><?php esc_html_e( 'Posts navigation', 'make' ); ?></span>
	<?php if ( function_exists( 'wp_pagenavi' ) ) : ?>
		<?php wp_pagenavi(); ?>
	<?php else : ?>
	<div class="nav-links">
		<?php
		// Left arrow
		if ( get_previous_posts_link() ) : ?>
		<div class="nav-previous">
			<?php previous_posts_link( esc_html__( 'Newer posts', 'make' ) ); ?>
		</div>
		<?php endif; ?>

		<?php
		// Right arrow
		if ( get_next_posts_link() ) : ?>
		<div class="nav-next">
			<?php next_posts_link( esc_html__( 'Older posts', 'make' ) ); ?>
		</div>
		<?php endif; ?>
	</div>
	<?php endif; ?>
</nav>
<?php endif;