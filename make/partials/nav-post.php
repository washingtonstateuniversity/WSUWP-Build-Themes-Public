<?php
/**
 * @package Make
 */

$navigation_key    = 'layout-' . make_get_current_view() . '-hide-navigation';
$navigation_option = make_get_thememod_value( $navigation_key );

// Left arrow
$previous_link = get_next_post_link(
	'<div class="nav-previous">%link</div>',
	'%title'
);

// Right arrow
$next_link = get_previous_post_link(
	'<div class="nav-next">%link</div>',
	'%title'
);

if ( ! $navigation_option && ( '' !== $next_link || '' !== $previous_link ) ) : ?>
<nav class="navigation post-navigation" role="navigation">
	<span class="screen-reader-text"><?php esc_html_e( 'Post navigation', 'make' ); ?></span>
	<div class="nav-links">
		<?php
		echo $previous_link;
		echo $next_link;
		?>
	</div>
</nav>
<?php endif;