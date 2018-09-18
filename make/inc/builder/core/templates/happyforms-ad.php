<div class="ttfmake-happyforms-ad">
	<?php $url = add_query_arg( array(
		'tab' => 'plugin-information',
		'plugin' => 'happyforms&happyforms=1',
		'TB_iframe' => true,
	), network_admin_url( 'plugin-install.php' ) );

	_e( sprintf( '<p>Adding a contact form? <a href="%s" target="_blank">Check out HappyForms</a>. It’s free and easy to use. <a href="%s" class="%s">Install Now</a></p>', 'https://wordpress.org/plugins/happyforms/', $url, 'button thickbox open-plugin-details-modal' ), 'make' ); ?>

	<?php if ( Make()->plus()->is_plus() ) { ?>
		<button class="notice-dismiss"><span class="screen-reader-text"><?php _e( 'Dismiss', 'make' ); ?></span></button>
	<?php } ?>
</div>