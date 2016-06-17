<?php
/**
 * @package Make
 */

$date_key    = 'layout-' . make_get_current_view() . '-post-date';
$date_option = make_get_thememod_value( $date_key );

// Get date string
$date_string = get_the_date();
if ( 'relative' === $date_option ) :
	$date_string = sprintf(
		// Translators: this string indicates an amount of passed time. e.g. 5 minutes ago
		__( '%s ago', 'make' ),
		human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) )
	);
endif;

// Add permalink if not single view
if ( ! is_singular() ) :
	$date_string = '<a href="' . get_permalink() . '" rel="bookmark">' . $date_string . '</a>';
endif;
?>

<?php if ( 'none' !== $date_option ) : ?>
<time class="entry-date published" datetime="<?php the_time( 'c' ); ?>"><?php echo $date_string; ?></time>
<?php endif; ?>