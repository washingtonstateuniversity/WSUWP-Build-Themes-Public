<?php
/**
 * @package Make
 */

if ( ! function_exists( 'ttfmake_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since  1.0.0.
 *
 * @param  array    $comment    The current comment object.
 * @param  array    $args       The comment configuration arguments.
 * @param  mixed    $depth      Depth of the current comment.
 * @return void
 */
function ttfmake_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;

	if ( 'pingback' == $comment->comment_type || 'trackback' == $comment->comment_type ) : ?>

	<li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
		<div class="comment-body">
			<?php esc_html_e( 'Pingback:', 'make' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( esc_html__( 'Edit', 'make' ), '<span class="edit-link">', '</span>' ); ?>
		</div>

	<?php else : ?>

	<li id="comment-<?php comment_ID(); ?>" <?php comment_class( empty( $args['has_children'] ) ? '' : 'comment-parent' ); ?>>
		<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
			<header class="comment-header">
				<?php // Avatar
				if ( 0 != $args['avatar_size'] ) :
					echo get_avatar( $comment, $args['avatar_size'] );
				endif;
				?>
				<div class="comment-date">
					<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
						<time datetime="<?php comment_time( 'c' ); ?>">
							<?php
							printf(
								esc_html_x( '%1$s at %2$s', '1: date, 2: time', 'make' ),
								get_comment_date(),
								get_comment_time()
							);
							?>
						</time>
					</a>
				</div>
				<div class="comment-author vcard">
					<?php
					printf(
						'%1$s <span class="says">%2$s</span>',
						sprintf(
							'<cite class="fn">%s</cite>',
							get_comment_author_link()
						),
						// Translators: this string is a verb whose subject is a comment author. e.g. Bob says: Hello.
						esc_html__( 'says:', 'make' )
					);
					?>
				</div>

				<?php if ( '0' == $comment->comment_approved ) : ?>
				<p class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'make' ); ?></p>
				<?php endif; ?>
			</header>

			<div class="comment-content">
				<?php comment_text(); ?>
			</div>

			<?php
			comment_reply_link( array_merge( $args, array(
				'add_below' => 'div-comment',
				'depth'     => $depth,
				'max_depth' => $args['max_depth'],
				'before'    => '<footer class="comment-reply">',
				'after'     => '</footer>',
			) ) );
			?>
		</article>

	<?php endif;
}
endif;

if ( ! function_exists( 'ttfmake_categorized_blog' ) ) :
/**
 * Returns true if a blog has more than 1 category.
 *
 * @since  1.0.0.
 *
 * @return bool    Determine if the site has more than one active category.
 */
function ttfmake_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'all_the_cool_cats' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'hide_empty' => 1,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'all_the_cool_cats', $all_the_cool_cats, DAY_IN_SECONDS );
	}

	if ( '1' != $all_the_cool_cats ) {
		// This blog has more than 1 category so ttfmake_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so ttfmake_categorized_blog should return false.
		return false;
	}
}
endif;

if ( ! function_exists( 'ttfmake_category_transient_flusher' ) ) :
/**
 * Flush out the transients used in ttfmake_categorized_blog.
 *
 * @since  1.0.0.
 *
 * @return void
 */
function ttfmake_category_transient_flusher() {
	delete_transient( 'all_the_cool_cats' );
	ttfmake_categorized_blog();
}
endif;

add_action( 'edit_category', 'ttfmake_category_transient_flusher' );
add_action( 'save_post',     'ttfmake_category_transient_flusher' );

if ( ! function_exists( 'ttfmake_get_read_more' ) ) :
/**
 * Return a read more link
 *
 * Use '%s' as a placeholder for the post URL.
 *
 * @since  1.0.0.
 *
 * @param  string    $before    HTML before the text.
 * @param  string    $after     HTML after the text.
 * @return string               Full read more HTML.
 */
function ttfmake_get_read_more( $before = '<a class="read-more" href="%s">', $after = '</a>' ) {
	if ( strpos( $before, '%s' ) ) {
		$before = sprintf(
			$before,
			get_permalink()
		);
	}

	/**
	 * Deprecated: Filter the value of the read more text.
	 *
	 * This filter hook has been deprecated in favor of a theme option in the Customizer. The theme option
	 * will only be available if no filters have been added to the hook.
	 *
	 * @since 1.2.3.
	 * @deprecated 1.5.0.
	 *
	 * @param string $read_more_text The read more text value.
	 */
	$more = apply_filters( 'make_read_more_text', false );

	// No filters, get the theme option.
	if ( false === $more ) {
		$more = esc_html( get_theme_mod( 'label-read-more', ttfmake_get_default( 'label-read-more' ) ) );
	}

	return $before . $more . $after;
}
endif;

if ( ! function_exists( 'ttfmake_maybe_show_site_region' ) ) :
/**
 * Output the site region (header or footer) markup if the current view calls for it.
 *
 * @since  1.0.0.
 *
 * @param  string    $region    Region to maybe show.
 * @return void
 */
function ttfmake_maybe_show_site_region( $region ) {
	if ( ! in_array( $region, array( 'header', 'footer' ) ) ) {
		return;
	}

	// Get the view
	$view = ttfmake_get_view();

	// Get the relevant option
	$hide_region = (bool) get_theme_mod( 'layout-' . $view . '-hide-' . $region, ttfmake_get_default( 'layout-' . $view . '-hide-' . $region ) );

	if ( true !== $hide_region ) {
		get_template_part(
			'partials/' . $region . '-layout',
			get_theme_mod( $region . '-layout', ttfmake_get_default( $region . '-layout' ) )
		);
	}
}
endif;

if ( ! function_exists( 'ttfmake_get_site_header_class' ) ) :
/**
 * Compile the classes for the site header
 *
 * @since 1.0.0.
 *
 * @return string
 */
function ttfmake_get_site_header_class() {
	// Base
	$class = 'site-header';

	// Layout
	$class .= ' header-layout-' . get_theme_mod( 'header-layout', ttfmake_get_default( 'header-layout' ) );

	// Title
	$hide_site_title = (int) get_theme_mod( 'hide-site-title', ttfmake_get_default( 'hide-site-title' ) );
	if ( 1 === $hide_site_title || ! get_bloginfo( 'name' ) ) {
		$class .= ' no-site-title';
	}

	// Tagline
	$hide_tagline    = (int) get_theme_mod( 'hide-tagline', ttfmake_get_default( 'hide-tagline' ) );
	if ( 1 === $hide_tagline || ! get_bloginfo( 'description' ) ) {
		$class .= ' no-site-tagline';
	}

	return esc_attr( $class );
}
endif;

if ( ! function_exists( 'ttfmake_maybe_show_sidebar' ) ) :
/**
 * Output the sidebar markup if the current view calls for it.
 *
 * The function is a wrapper for the get_sidebar() function. In this theme, the sidebars can be turned on and off for
 * different page views. It is important the the sidebar is *only included* if the user has set the option for it to
 * be included. As such, the get_sidebar() function needs to additional logic to determine whether or not to even
 * include the template.
 *
 * @since  1.0.0.
 *
 * @param  string    $location    The sidebar location (e.g., left, right).
 * @return void
 */
function ttfmake_maybe_show_sidebar( $location ) {
	// Get sidebar status
	$show_sidebar = ttfmake_has_sidebar( $location );

	// Output the sidebar
	if ( true === $show_sidebar ) {
		get_sidebar( $location );
	}
}
endif;

if ( ! function_exists( 'ttfmake_maybe_show_social_links' ) ) :
/**
 * Show the social links markup if the theme options and/or menus are configured for it.
 *
 * @since  1.0.0.
 *
 * @param  string    $region    The site region (header or footer).
 * @return void
 */
function ttfmake_maybe_show_social_links( $region ) {
	if ( ! in_array( $region, array( 'header', 'footer' ) ) ) {
		return;
	}

	$show_social = (bool) get_theme_mod( $region . '-show-social', ttfmake_get_default( $region . '-show-social' ) );

	if ( true === $show_social ) {
		// First look for the alternate custom menu method
		if ( has_nav_menu( 'social' ) ) {
			wp_nav_menu(
				array(
					'theme_location' => 'social',
					'container'      => false,
					'menu_id'        => '',
					'menu_class'     => 'social-menu social-links ' . $region . '-social-links',
					'depth'          => 1,
					'fallback_cb'    => '',
				)
			);
		}
		// Then look for the Customizer theme option method
		else {
			$social_links = ttfmake_get_social_links();
			if ( ! empty( $social_links ) ) { ?>
				<ul class="social-customizer social-links <?php echo $region; ?>-social-links">
				<?php foreach ( $social_links as $key => $link ) : ?>
					<li class="<?php echo esc_attr( $key ); ?>">
						<a href="<?php echo esc_url( $link['url'] ); ?>">
							<i class="fa fa-fw <?php echo esc_attr( $link['class'] ); ?>">
								<span><?php echo esc_html( $link['title'] ); ?></span>
							</i>
						</a>
					</li>
				<?php endforeach; ?>
				</ul>
			<?php }
		}
	}
}
endif;

if ( ! function_exists( 'ttfmake_get_exif_data' ) ) :
/**
 * Get EXIF data from an attachment.
 *
 * @since  1.0.0.
 *
 * @param  int       $attachment_id    The attachment ID to get data from.
 * @return string                      The EXIF data.
 */
function ttfmake_get_exif_data( $attachment_id = 0 ) {
	// Validate attachment id
	if ( 0 === absint( $attachment_id ) ) {
		$attachment_id = get_post()->ID;
	}

	$output = '';

	$attachment_meta = wp_get_attachment_metadata( $attachment_id );
	$image_meta      = ( isset( $attachment_meta['image_meta'] ) ) ? array_filter( $attachment_meta['image_meta'], 'trim' ) : array();
	if ( ! empty( $image_meta ) ) {
		// Defaults
		$defaults = array(
			'aperture' => 0,
  			'camera' => '',
  			'created_timestamp' => 0,
  			'focal_length' => 0,
  			'iso' => 0,
  			'shutter_speed' => 0,
		);
		$image_meta = wp_parse_args( $image_meta, $defaults );

		// Convert the shutter speed to a fraction and add units
		if ( 0 !== $image_meta[ 'shutter_speed' ] ) {
			$raw_ss = floatval( $image_meta['shutter_speed'] );
			$denominator = 1 / $raw_ss;
			if ( $denominator > 1 ) {
				$decimal_places = 0;
				if ( in_array( number_format( $denominator, 1 ), array( 1.3, 1.5, 1.6, 2.5 ) ) ) {
					$decimal_places = 1;
				}
				// Translators: this string denotes a camera shutter speed as a fraction of a second. %s is a placeholder for the denominator of the fraction.
				$converted_ss = sprintf(
					esc_html__( '1/%s second', 'make' ),
					number_format_i18n( $denominator, $decimal_places )
				);
			} else {
				// Translators: this string denotes a camera shutter speed as a number of seconds. %s is a placeholder for the number.
				$converted_ss = sprintf(
					esc_html__( '%s seconds', 'make' ),
					number_format_i18n( $raw_ss, 1 )
				);
			}

			/**
			 * Filter the shutter speed value.
			 *
			 * @since 1.2.3.
			 *
			 * @param string    $converted_as         The shutter speed value.
			 * @param float     $raw_shutter_speed    The raw shutter speed value.
			 */
			$image_meta['shutter_speed'] = apply_filters( 'make_exif_shutter_speed', $converted_ss, $image_meta['shutter_speed'] );
		}

		// Convert the aperture to an F-stop
		if ( 0 !== $image_meta[ 'aperture' ] ) {
			// Translators: this string denotes a camera f-stop. %s is a placeholder for the f-stop value. E.g. f/3.5
			$f_stop = sprintf(
				__( 'f/%s', 'make' ),
				number_format_i18n( pow( sqrt( 2 ), absint( $image_meta['aperture'] ) ) )
			);

			/**
			 * Filter the aperture value.
			 *
			 * @since 1.2.3.
			 *
			 * @param string    $f_stop          The aperture value.
			 * @param int       $raw_aperture    The raw aperture value.
			 */
			$image_meta['aperture'] = apply_filters( 'make_exif_aperture', $f_stop, $image_meta['aperture'] );
		}

		$output .= "<ul class=\"entry-exif-list\">\n";

		// Camera
		if ( ! empty( $image_meta['camera'] ) ) {
			// Translators: "Camera" refers to the model name of a camera. %s is a placeholder for the model name.
			$output .= sprintf(
				'<li>' . esc_html__( 'Camera: %s', 'make' ) . "</li>\n",
				esc_html( $image_meta['camera'] )
			);
		}

		// Creation Date
		if ( ! empty( $image_meta['created_timestamp'] ) ) {
			$date = new DateTime( gmdate( "Y-m-d\TH:i:s\Z", $image_meta['created_timestamp'] ) );
			// Translators: "Taken" refers to the date that a photograph was taken. %s is a placeholder for that date.
			$output .= sprintf(
				'<li>' . esc_html__( 'Taken: %s', 'make' ) . "</li>\n",
				esc_html( $date->format( get_option( 'date_format' ) ) )
			);
		}

		// Focal length
		if ( ! empty( $image_meta['focal_length'] ) ) {
			// Translators: "Focal length" refers to the length of a camera's lens. %s is a placeholder for the focal length value, and "mm" is the units in millimeters.
			$output .= sprintf(
				'<li>' . esc_html__( 'Focal length: %smm', 'make' ) . "</li>\n",
				number_format_i18n( absint( $image_meta['focal_length'] ), 0 )
			);
		}

		// Aperture
		if ( ! empty( $image_meta['aperture'] ) ) {
			// Translators: "Aperture" refers to the amount of light passing through a camera lens. %s is a placeholder for the aperture value, represented as an f-stop.
			$output .= sprintf(
				'<li>' . esc_html__( 'Aperture: %s', 'make' ) . "</li>\n",
				esc_html( $image_meta['aperture'] )
			);
		}

		// Exposure
		if ( ! empty( $image_meta['shutter_speed'] ) ) {
			// Translators: "Exposure" refers to a camera's shutter speed. %s is a placeholder for the shutter speed value.
			$output .= sprintf(
				'<li>' . esc_html__( 'Exposure: %s', 'make' ) . "</li>\n",
				esc_html( $image_meta['shutter_speed'] )
			);
		}

		// ISO
		if ( ! empty( $image_meta['iso'] ) ) {
			// Translators: "ISO" is an acronym that refers to a camera's sensitivity to light. %s is a placeholder for the ISO value.
			$output .= sprintf(
				'<li>' . esc_html__( 'ISO: %s', 'make' ) . "</li>\n",
				absint( $image_meta['iso'] )
			);
		}

		$output .= "</ul>\n";
	}

	/**
	 * Alter the exif data output.
	 *
	 * @since 1.2.3.
	 *
	 * @param string    $output           The EXIF data prepared as HTML.
	 * @param int       $attachment_id    The image being generated.
	 */
	return apply_filters( 'make_get_exif_data', $output, $attachment_id );
}
endif;

/**
 * Add the Yoast SEO breadcrumb, if the plugin is activated.
 *
 * @since 1.6.4.
 *
 * @return void
 */
function ttfmake_yoast_seo_breadcrumb() {
	if ( function_exists( 'yoast_breadcrumb' ) ) {
		$key    = 'layout-' . ttfmake_get_view() . '-yoast-breadcrumb';
		$option = absint( get_theme_mod( $key, ttfmake_get_default( $key ) ) );

		if ( ( 1 === $option && ! is_front_page() ) || is_404() ) {
			yoast_breadcrumb( '<p class="yoast-seo-breadcrumb">', '</p>' );
		}
	}
}