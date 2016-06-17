<?php
/**
 * @package Make
 */
global $make_current_location;

$current_view = make_get_current_view();

$author_layout_key = 'layout-' . $current_view . '-post-author-location';
$author_option     = make_get_thememod_value( $author_layout_key );

$date_layout_key = 'layout-' . $current_view . '-post-date-location';
$date_option     = make_get_thememod_value( $date_layout_key );

$comment_count_layout_key = 'layout-' . $current_view . '-comment-count-location';
$comment_count_option     = make_get_thememod_value( $comment_count_layout_key );
?>
<div class="entry-meta">
<?php
if ( $make_current_location === $author_option ) :
	get_template_part( 'partials/entry', 'author' );
endif;

if ( $make_current_location === $comment_count_option ) :
	get_template_part( 'partials/entry', 'comment-count' );
endif;

if ( $make_current_location === $date_option ) :
	get_template_part( 'partials/entry', 'date' );
endif;
?>
</div>