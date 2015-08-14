<?php
/**
 * @package Make
 */

if ( post_password_required() ) :
	return;
endif;
?>

<div id="comments" class="comments-area">
	<?php if ( have_comments() ) : ?>
	<h3 class="comments-title">
		<?php
		printf(
			// Translators: this string appears as the title of the Comments section of a post.
			esc_html( _n( 'One comment', '%1$s comments', get_comments_number(), 'make' ) ),
			number_format_i18n( get_comments_number() )
		);
		?>
	</h3>

		<?php if ( get_comment_pages_count() > 1 ) : ?>
		<nav id="comment-nav-above" class="comment-navigation" role="navigation">
			<span class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'make' ); ?></span>
			<?php paginate_comments_links(); ?>
		</nav>
		<?php endif; ?>

		<ol class="comment-list">
			<?php
			wp_list_comments( array(
				'avatar_size' => 38,
				'callback'    => 'ttfmake_comment'
			) );
			?>
		</ol>

		<?php if ( get_comment_pages_count() > 1 ) : ?>
		<nav id="comment-nav-below" class="comment-navigation" role="navigation">
			<span class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'make' ); ?></span>
			<?php paginate_comments_links(); ?>
		</nav>
		<?php endif; ?>

	<?php endif; ?>

	<?php if ( ! comments_open() && '0' != get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
	<p class="no-comments">
		<?php esc_html_e( 'Comments are closed.', 'make' ); ?>
	</p>
	<?php endif; ?>

	<?php comment_form(); ?>
</div>
