<?php
/**
 * @package Make
 */

$comment_count_key    = 'layout-' . make_get_current_view() . '-comment-count';
$comment_count_option = make_get_thememod_value( $comment_count_key );

// Comments number
if ( 'icon' === $comment_count_option ) :
	$comments_number = get_comments_number_text( '<span class="comment-count-icon zero">0</span>', '<span class="comment-count-icon one">1</span>', '<span class="comment-count-icon many">%</span>' );
else :
	$comments_number = get_comments_number_text();
endif;

// Output
if ( 'none' !== $comment_count_option ) : ?>
	<div class="entry-comment-count">
		<a href="<?php comments_link(); ?>"><?php echo $comments_number; ?></a>
	</div>
<?php endif;