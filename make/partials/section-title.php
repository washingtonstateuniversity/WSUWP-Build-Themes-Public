<?php
/**
 * @package Make
 */
?>

<h1 class="section-title">
	<?php
	if ( is_archive() ) :
		if ( is_category() ) :
			printf(
				esc_html__( 'From %s', 'make' ),
				'<strong>' . single_cat_title( '', false ) . '</strong>'
			);

		elseif ( is_tag() ) :
			printf(
				esc_html__( 'Tagged %s', 'make' ),
				'<strong>' . single_tag_title( '', false ) . '</strong>'
			);

		elseif ( is_day() ) :
			printf(
				esc_html__( 'From %s', 'make' ),
				'<strong>' . get_the_date() . '</strong>'
			);

		elseif ( is_month() ) :
			printf(
				esc_html__( 'From %s', 'make' ),
				'<strong>' . get_the_date( _x( 'F Y', 'date format code for month and year', 'make' ) ) . '</strong>'
			);

		elseif ( is_year() ) :
			printf(
				esc_html__( 'From %s', 'make' ),
				'<strong>' . get_the_date( _x( 'Y', 'date format code for year', 'make' ) ) . '</strong>'
			);

		elseif ( is_author() ) :
			printf(
				esc_html__( 'By %s', 'make' ),
				'<strong class="vcard">' . get_the_author() . '</strong>'
			);

		else :
			esc_html_e( 'Archive', 'make' );

		endif;

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