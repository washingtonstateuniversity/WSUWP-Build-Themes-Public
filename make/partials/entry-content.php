<?php
/**
 * @package Make
 */

// Posts and Pages
if ( is_singular() ) :
	the_content();

// Blog, Archives, Search Results
else :
	$content_key    = 'layout-' . make_get_current_view() . '-auto-excerpt';
	$content_option = make_get_thememod_value( $content_key );

	if ( $content_option || has_excerpt() ) :
		echo wpautop( get_the_excerpt() . "\n\n" . ttfmake_get_read_more() );
	else :
		the_content( ttfmake_get_read_more( '', '' ) );
	endif;
endif;