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
 *
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
 * @since 1.0.0.
 * @since 1.7.0. Updated to match Twenty Sixteen's functionality.
 *
 * @return bool
 */
function ttfmake_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'make_category_count' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'make_category_count', $all_the_cool_cats, WEEK_IN_SECONDS );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so return true.
		return true;
	} else {
		// This blog does not have more than 1 category so return false.
		return false;
	}
}
endif;

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
 *
 * @return string               Full read more HTML.
 */
function ttfmake_get_read_more( $before = ' <a class="more-link" href="%s">', $after = '</a>' ) {
	// Add the permalink
	if ( false !== strpos( $before, '%s' ) ) {
		$before = sprintf(
			$before,
			get_permalink()
		);
	}

	$more = false;

	// Check for deprecated filter
	if ( has_filter( 'make_read_more_text' ) ) {
		Make()->compatibility()->deprecated_hook(
			'make_read_more_text',
			'1.5.0',
			esc_html__( '
				The hook has been replaced with a theme option in the Customizer.
				The theme option will only be available if no filters have been added to the hook.
			', 'make' )
		);

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
		$more = apply_filters( 'make_read_more_text', $more );
	}

	// No filters, get the theme option.
	if ( false === $more ) {
		$more = make_get_thememod_value( 'label-read-more' );
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
	$view = make_get_current_view();

	// Get the relevant option
	$hide_region = make_get_thememod_value( 'layout-' . $view . '-hide-' . $region );

	if ( true !== $hide_region ) {
		get_template_part(
			'partials/' . $region . '-layout',
			make_get_thememod_value( $region . '-layout' )
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
	// Collector
	$classes = array();

	// Base
	$classes[] = 'site-header';

	// Layout
	$classes[] = 'header-layout-' . make_get_thememod_value( 'header-layout' );

	// Title
	$hide_site_title = make_get_thememod_value( 'hide-site-title' );
	if ( $hide_site_title || ! get_bloginfo( 'name' ) ) {
		$classes[] = 'no-site-title';
	}

	// Tagline
	$hide_tagline = make_get_thememod_value( 'hide-tagline' );
	if ( $hide_tagline || ! get_bloginfo( 'description' ) ) {
		$classes[] = 'no-site-tagline';
	}

	/**
	 * Filter: Modify the classes applied to the site header element.
	 *
	 * @since 1.7.0.
	 *
	 * @param array $classes
	 */
	$classes = apply_filters( 'make_site_header_class', $classes );

	// Convert array to string and return
	return implode( ' ', $classes );
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
 *
 * @return void
 */
function ttfmake_maybe_show_sidebar( $location ) {
	// Get sidebar status
	$show_sidebar = make_has_sidebar( $location );

	// Output the sidebar
	if ( true === $show_sidebar ) {
		get_sidebar( $location );
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
 *
 * @return string                      The EXIF data with HTML markup.
 */
function ttfmake_get_exif_data( $attachment_id = 0 ) {
	// Validate attachment id
	if ( 0 === absint( $attachment_id ) ) {
		$attachment_id = get_post()->ID;
	}

	$output = '';

	$attachment_meta = wp_get_attachment_metadata( $attachment_id );
	$image_meta      = ( isset( $attachment_meta['image_meta'] ) ) ? $attachment_meta['image_meta'] : array();
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
		if ( 0 != $image_meta[ 'shutter_speed' ] ) {
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
			 * @since 1.7.0. Added $attachment_id parameter.
			 *
			 * @param string    $converted_as         The shutter speed value.
			 * @param float     $raw_shutter_speed    The raw shutter speed value.
			 * @param int       $attachment_id        The ID of the attachment.
			 */
			$image_meta['shutter_speed'] = apply_filters( 'make_exif_shutter_speed', $converted_ss, $image_meta['shutter_speed'], $attachment_id );
		}

		// Convert the aperture to an F-stop
		if ( 0 != $image_meta[ 'aperture' ] ) {
			// Translators: this string denotes a camera f-stop. %s is a placeholder for the f-stop value. E.g. f/3.5
			$f_stop = sprintf(
				__( 'f/%s', 'make' ),
				number_format_i18n( pow( sqrt( 2 ), absint( $image_meta['aperture'] ) ) )
			);

			/**
			 * Filter the aperture value.
			 *
			 * @since 1.2.3.
			 * @since 1.7.0. Added $attachment_id parameter.
			 *
			 * @param string    $f_stop          The aperture value.
			 * @param int       $raw_aperture    The raw aperture value.
			 * @param int       $attachment_id   The ID of the attachment.
			 */
			$image_meta['aperture'] = apply_filters( 'make_exif_aperture', $f_stop, $image_meta['aperture'], $attachment_id );
		}

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
	}

	// Wrap list items
	if ( '' !== $output ) {
		$output = "<ul class=\"entry-exif-list\">\n" . $output . "</ul>\n";
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
 * Get a sanitized value for a Theme Mod setting.
 *
 * @since 1.7.0.
 *
 * @param        $setting_id
 * @param string $context
 *
 * @return mixed
 */
function make_get_thememod_value( $setting_id, $context = 'template' ) {
	return Make()->thememod()->get_value( $setting_id, $context );
}

/**
 * Get the default value for a Theme Mod setting.
 *
 * @since 1.7.0.
 *
 * @param $setting_id
 *
 * @return mixed
 */
function make_get_thememod_default( $setting_id ) {
	return Make()->thememod()->get_default( $setting_id );
}

/**
 * Get the current view.
 *
 * @since 1.7.0.
 *
 * @return mixed
 */
function make_get_current_view() {
	return Make()->view()->get_current_view();
}

/**
 * Check if the current view has a sidebar in the specified location (left or right).
 *
 * @since 1.7.0.
 *
 * @param $location
 *
 * @return mixed
 */
function make_has_sidebar( $location ) {
	return Make()->widgets()->has_sidebar( $location );
}

/**
 * Check if a custom logo has been set.
 *
 * @since 1.7.0.
 *
 * @return bool
 */
function make_has_logo() {
	return Make()->logo()->has_logo();
}

/**
 * Output the markup for a custom logo.
 *
 * @since 1.7.0.
 *
 * return void
 */
function make_logo() {
	echo Make()->logo()->get_logo();
}

/**
 * Check to see if social icons have been configured for display.
 *
 * @since 1.7.0.
 *
 * @return bool
 */
function make_has_socialicons() {
	return Make()->socialicons()->has_icon_data();
}

/**
 * Display social icons for the site header or footer.
 *
 * @since 1.7.0.
 *
 * @param $region
 *
 * @return void
 */
function make_socialicons( $region ) {
	if ( ! in_array( $region, array( 'header', 'footer' ) ) ) {
		return;
	}

	$show_icons = make_has_socialicons() && make_get_thememod_value( $region . '-show-social' );

	if ( $show_icons || is_customize_preview() ) : ?>
		<div class="<?php echo $region; ?>-social-links">
	<?php endif;

	if ( $show_icons ) : ?>
		<?php echo Make()->socialicons()->render_icons(); ?>
	<?php endif;

	if ( $show_icons || is_customize_preview() ) : ?>
		</div>
	<?php endif;
}

/**
 * Display a breadcrumb.
 *
 * @since 1.7.0.
 *
 * @param string $before
 * @param string $after
 *
 * @return void
 */
function make_breadcrumb( $before = '<p class="yoast-seo-breadcrumb">', $after = '</p>' ) {
	$breadcrumb = '';

	$breadcrumb_override = apply_filters( 'make_breadcrumb_override', false );

	if ( false === $breadcrumb_override ) {
		if ( Make()->integration()->has_integration( 'yoastseo' ) ) {
			$breadcrumb = Make()->integration()->get_integration( 'yoastseo' )->maybe_render_breadcrumb( $before, $after );
		}
	} else {
		$show_breadcrumbs = Make()->thememod()->get_value( 'layout-' . make_get_current_view() . '-breadcrumb' );

		if ( ( $show_breadcrumbs && ! is_front_page() ) || is_404() ) {
			/**
			 * Filter: Modify the output of breadcrumb
			 *
			 * @since 1.8.9.
			 *
			 * @param string $breadcrumb        The breadcrumb markup.
			 * @param string $before            The wrapper opening markup.
			 * @param string $after             The wrapper closing markup.
			 */
			$breadcrumb = apply_filters( 'make_breadcrumb_output', $breadcrumb, $before, $after );
		}
	}

	echo $breadcrumb;
}

/**
 * Determine which image size to use to display a post's featured image.
 *
 * @since 1.7.4.
 *
 * @param string $layout_setting
 *
 * @return string
 */
function make_get_entry_thumbnail_size( $layout_setting = 'none' ) {
	// Currently viewing an attachment
	if ( is_attachment() ) {
		$size = 'full';
	}
	// Currently viewing some other post type
	else {
		if ( 'post-header' === $layout_setting ) {
			$size = 'large';
		} else {
			$size = ( is_singular() ) ? 'medium' : 'thumbnail';
		}
	}

	/**
	 * Filter: Modify the image size used to display a post's featured image (post thumbnail)
	 *
	 * @since 1.7.4.
	 *
	 * @param string $size              The ID of the image size to use.
	 * @param string $layout_setting    The value of the featured image layout setting for the current view.
	 */
	return apply_filters( 'make_entry_thumbnail_size', $size, $layout_setting );
}

if ( ! function_exists( 'sanitize_hex_color' ) ) :
/**
 * Sanitizes a hex color.
 *
 * This replicates the core function that is unfortunately only available in the Customizer.
 *
 * @since  1.0.0.
 *
 * @param string $color    The proposed color.
 *
 * @return string    The sanitized color.
 */
function sanitize_hex_color( $color ) {
	if ( '' === $color ) {
		return '';
	}

	// 3 or 6 hex digits, or the empty string.
	if ( preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) ) {
		return $color;
	}

	return '';
}
endif;

if ( ! function_exists( 'sanitize_hex_color_no_hash' ) ) :
/**
 * Sanitizes a hex color without a hash. Use sanitize_hex_color() when possible.
 *
 * This replicates the core function that is unfortunately only available in the Customizer.
 *
 * @since  1.0.0.
 *
 * @param  string         $color    The proposed color.
 * @return string|null              The sanitized color.
 */
function sanitize_hex_color_no_hash( $color ) {
	$color = ltrim( $color, '#' );
	if ( '' === $color ) {
		return '';
	}

	return sanitize_hex_color( '#' . $color ) ? $color : null;
}
endif;

if ( ! function_exists( 'maybe_hash_hex_color' ) ) :
/**
 * Ensures that any hex color is properly hashed.
 *
 * This replicates the core function that is unfortunately only available in the Customizer.
 *
 * @since  1.0.0.
 *
 * @param  string         $color    The proposed color.
 * @return string|null              The sanitized color.
 */
function maybe_hash_hex_color( $color ) {
	if ( $unhashed = sanitize_hex_color_no_hash( $color ) ) {
		return '#' . $unhashed;
	}

	return $color;
}
endif;