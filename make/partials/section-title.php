<?php
/**
 * @package Make
 */
?>

<h1 class="section-title">
	<?php
	if ( is_archive() ) :
		the_archive_title();

	elseif ( is_search() ) :
		printf(
			esc_html__( 'Search for %s', 'make' ),
			'<strong class="search-keyword">' . get_search_query() . '</strong>'
		);
		printf(
			' &#45; <span class="search-result">%s</span>',
			sprintf(
				esc_html( _n( '%s result found', '%s results found', absint( $wp_query->found_posts ), 'make' ) ),
				number_format_i18n( absint( $wp_query->found_posts ) )
			)
		);

	endif;
	?>
</h1>