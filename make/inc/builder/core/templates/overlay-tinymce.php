<?php
get_template_part( '/inc/builder/core/templates/overlay', 'header' );

wp_editor( '', 'make_content_editor', array(
	'tinymce' => array(
		'wp_autoresize_on' => false,
		'resize'           => false,
	),
	'editor_height' => 270
) );

get_template_part( '/inc/builder/core/templates/overlay', 'footer' );
