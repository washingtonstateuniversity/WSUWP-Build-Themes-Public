<?php
/**
 * @package Make
 */
function make_overlay_happyforms_ad( $overlay_id ) {
	if ( 'ttfmake-tinymce-overlay' === $overlay_id
		&& ! is_plugin_active( 'happyforms/happyforms.php' ) 
		&& ( ! Make()->plus()->is_plus() || ! intval( get_option( 'make_happyforms_ad_dismissed', 0 ) ) ) 
		) {
		get_template_part( '/inc/builder/core/templates/happyforms-ad' );
	}
}

function make_overlay_happyforms_dequeue_scripts() {
	if ( ! isset( $_GET['happyforms'] ) ) {
		return;
	}

	wp_dequeue_script( 'updates' );
}

function make_before_editor_happyforms_ad() {
	$current_screen = get_current_screen();
	$ad_post_types = array( 'post', 'page' );

	if ( in_array( $current_screen->post_type, $ad_post_types ) && 'edit' === $current_screen->parent_base ) {
		if ( ! is_plugin_active( 'happyforms/happyforms.php' ) 
			&& ( ! Make()->plus()->is_plus() || ! intval( get_option( 'make_happyforms_ad_dismissed', 0 ) ) ) 
		) {
		?>
			<div class="ttfmake-happyforms-ad--header">
				<?php get_template_part( '/inc/builder/core/templates/happyforms-ad' ); ?>
			</div>
		<?php
		}
	}
}

function make_ajax_dismiss_happyforms_ad() {
	update_option( 'make_happyforms_ad_dismissed', 1 );

	wp_die();
}

add_action( 'make_overlay_body_before', 'make_overlay_happyforms_ad' );
add_action( 'install_plugins_pre_plugin-information', 'make_overlay_happyforms_dequeue_scripts' );
add_action( 'edit_form_after_title', 'make_before_editor_happyforms_ad' );
add_action( 'wp_ajax_dismiss_happyforms_ad', 'make_ajax_dismiss_happyforms_ad' );