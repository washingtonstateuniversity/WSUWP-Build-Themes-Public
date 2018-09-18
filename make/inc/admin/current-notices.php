<?php
/**
 * @package Make
 */

// Bail if this isn't being included inside of a MAKE_Admin_NoticeInterface.
if ( ! isset( $this ) || ! $this instanceof MAKE_Admin_NoticeInterface ) {
	return;
}

global $wp_version;

// Notice of unsupported WordPress version
if ( version_compare( $wp_version, TTFMAKE_MIN_WP_VERSION, '<' ) ) {
	$this->register_admin_notice(
		'make-wp-lt-min-version-' . TTFMAKE_MIN_WP_VERSION,
		sprintf(
			__( 'Make requires version %1$s of WordPress or higher. Your current version is %2$s. Please <a href="%3$s">update WordPress</a> to ensure full compatibility.', 'make' ),
			TTFMAKE_MIN_WP_VERSION,
			esc_html( $wp_version ),
			admin_url( 'update-core.php' )
		),
		array(
			'cap'     => 'update_core',
			'dismiss' => false,
			'screen'  => array( 'dashboard', 'themes', 'update-core.php' ),
			'type'    => 'error',
		)
	);
}

// Help notices
$this->register_admin_notice(
	'make-page-builder-welcome',
	sprintf(
		__( 'This is the page builder. Learn to <a href="%s" target="_blank">add, edit and arrange sections</a>.', 'make' ),
		'https://thethemefoundry.com/docs/make-docs/page-builder/managing-sections/'
	),
	array(
		'cap'     => 'edit_pages',
		'dismiss' => true,
		'screen'  => array( 'page' ),
		'type'    => 'info',
	)
);
if ( ! is_child_theme() ) {
	$this->register_admin_notice(
		'make-themes-child-theme-intro',
		sprintf(
			__( 'Looking to take Make even further? Learn to <a href="%1$s" target="_blank">install a child theme</a> and <a href="%2$s" target="_blank">apply custom code</a>.', 'make' ),
			'https://thethemefoundry.com/docs/make-docs/code/installing-child-theme/',
			'https://thethemefoundry.com/docs/make-docs/code/custom-css/'
		),
		array(
			'cap'     => 'switch_themes',
			'dismiss' => true,
			'screen'  => array( 'theme-editor' ),
			'type'    => 'info',
		)
	);
}
$this->register_admin_notice(
	'make-dashboard-simple-start',
	sprintf(
		__( 'Welcome to Make! Get up and running with our <a href="%s" target="_blank">Simple Start Handbook</a>.', 'make' ),
		'https://thethemefoundry.com/docs/make-docs/simple-start-handbook/'
	),
	array(
		'cap'     => 'edit_pages',
		'dismiss' => true,
		'screen'  => array( 'dashboard' ),
		'type'    => 'info',
	)
);

// Notice of Make 1.8 not being compatible with Make Plus older than 1.8.0
if ( Make()->plus()->is_plus() && strcmp( Make()->plus()->get_plus_version(), '1.8.0' ) < 0 ) {
	$this->register_admin_notice(
		'make-makeplus-18-compatibility',
		__( 'The latest version of Make isn’t compatible with the version of Make Plus you’re using. Please update Make Plus.', 'make' ),
		array(
			'cap'     => 'install_plugins',
			'dismiss' => false,
			'screen'  => array( 'dashboard', 'themes', 'plugins' ),
			'type'    => 'error',
		)
	);
}
