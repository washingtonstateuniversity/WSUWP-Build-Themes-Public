<?php
/**
 * @package Make
 */

// Bail if this isn't being included inside of a MAKE_Settings_ThemeModInterface.
if ( ! isset( $this ) || ! $this instanceof MAKE_Settings_ThemeModInterface ) {
	return;
}

// Boolean settings
$this->add_settings(
	array(
		'hide-site-title'                 => array( 'default' => false ),
		'hide-tagline'                    => array( 'default' => false ),
		'font-subnav-mobile'              => array( 'default' => true ),
		'header-hide-padding-bottom'      => array( 'default' => false, 'is_style' => true ),
		'header-show-social'              => array( 'default' => false ),
		'header-show-search'              => array( 'default' => true ),
		'footer-hide-padding-top'         => array( 'default' => false, 'is_style' => true ),
		'footer-show-social'              => array( 'default' => true ),
		'layout-blog-hide-header'         => array( 'default' => false ),
		'layout-blog-hide-footer'         => array( 'default' => false ),
		'layout-blog-sidebar-left'        => array( 'default' => false ),
		'layout-blog-sidebar-right'       => array( 'default' => true ),
		'layout-blog-auto-excerpt'        => array( 'default' => false ),
		'layout-blog-show-categories'     => array( 'default' => true ),
		'layout-blog-show-tags'           => array( 'default' => true ),
		'layout-blog-breadcrumb'          => array( 'default' => false ),
		'layout-archive-hide-header'      => array( 'default' => false ),
		'layout-archive-hide-footer'      => array( 'default' => false ),
		'layout-archive-sidebar-left'     => array( 'default' => false ),
		'layout-archive-sidebar-right'    => array( 'default' => true ),
		'layout-archive-auto-excerpt'     => array( 'default' => false ),
		'layout-archive-show-categories'  => array( 'default' => true ),
		'layout-archive-show-tags'        => array( 'default' => true ),
		'layout-archive-breadcrumb'       => array( 'default' => false ),
		'layout-search-hide-header'       => array( 'default' => false ),
		'layout-search-hide-footer'       => array( 'default' => false ),
		'layout-search-sidebar-left'      => array( 'default' => false ),
		'layout-search-sidebar-right'     => array( 'default' => true ),
		'layout-search-auto-excerpt'      => array( 'default' => true ),
		'layout-search-show-categories'   => array( 'default' => true ),
		'layout-search-show-tags'         => array( 'default' => true ),
		'layout-search-breadcrumb'        => array( 'default' => false ),
		'layout-post-hide-header'         => array( 'default' => false ),
		'layout-post-hide-footer'         => array( 'default' => false ),
		'layout-post-sidebar-left'        => array( 'default' => false ),
		'layout-post-sidebar-right'       => array( 'default' => false ),
		'layout-post-show-categories'     => array( 'default' => true ),
		'layout-post-show-tags'           => array( 'default' => true ),
		'layout-post-hide-title'          => array( 'default' => false ),
		'layout-post-hide-navigation'     => array( 'default' => false ),
		'layout-post-breadcrumb'          => array( 'default' => false ),
		'layout-page-hide-header'         => array( 'default' => false ),
		'layout-page-hide-footer'         => array( 'default' => false ),
		'layout-page-sidebar-left'        => array( 'default' => false ),
		'layout-page-sidebar-right'       => array( 'default' => false ),
		'layout-page-hide-title'          => array( 'default' => true ),
		'layout-page-breadcrumb'          => array( 'default' => false ),
	),
	array(
		'sanitize' => 'wp_validate_boolean',
	)
);

// Number (integer) settings
$this->add_settings(
	array(
		'font-size-body'                => array( 'default' => 17 ),
		'font-size-button'              => array( 'default' => 13 ),
		'font-size-h1'                  => array( 'default' => 46 ),
		'font-size-h2'                  => array( 'default' => 34 ),
		'font-size-h3'                  => array( 'default' => 24 ),
		'font-size-h4'                  => array( 'default' => 24 ),
		'font-size-h5'                  => array( 'default' => 16 ),
		'font-size-h6'                  => array( 'default' => 14 ),
		'font-size-site-title'          => array( 'default' => 34 ),
		'font-size-site-tagline'        => array( 'default' => 12 ),
		'font-size-nav'                 => array( 'default' => 14 ),
		'font-size-subnav'              => array( 'default' => 13 ),
		'font-size-header-bar-text'     => array( 'default' => 13 ),
		'font-size-header-bar-icon'     => array( 'default' => 20 ),
		'font-size-widget-title'        => array( 'default' => 13 ),
		'font-size-widget'              => array( 'default' => 13 ),
		'font-size-footer-text'         => array( 'default' => 13 ),
		'font-size-footer-widget-title' => array( 'default' => 13 ),
		'font-size-footer-widget'       => array( 'default' => 13 ),
		'font-size-footer-icon'         => array( 'default' => 20 ),
		'custom_logo'                   => array( 'default' => 0, 'is_style' => false )
	),
	array(
		'sanitize' => 'absint',
		'is_style' => true,
	)
);

// Number (float) settings
$this->add_settings(
	array(
		'line-height-body'                          => array( 'default' => (float) 1.6 ),
		'letter-spacing-body'                       => array( 'default' => (float) 0 ),
		'word-spacing-body'                         => array( 'default' => (float) 0 ),
		'line-height-button'                        => array( 'default' => (float) 1.6 ),
		'letter-spacing-button'                     => array( 'default' => (float) 0 ),
		'word-spacing-button'                       => array( 'default' => (float) 0 ),
		'line-height-h1'                            => array( 'default' => (float) 1.2 ),
		'letter-spacing-h1'                         => array( 'default' => (float) 0 ),
		'word-spacing-h1'                           => array( 'default' => (float) 0 ),
		'line-height-h2'                            => array( 'default' => (float) 1.6 ),
		'letter-spacing-h2'                         => array( 'default' => (float) 0 ),
		'word-spacing-h2'                           => array( 'default' => (float) 0 ),
		'line-height-h3'                            => array( 'default' => (float) 1.6 ),
		'letter-spacing-h3'                         => array( 'default' => (float) 0 ),
		'word-spacing-h3'                           => array( 'default' => (float) 0 ),
		'line-height-h4'                            => array( 'default' => (float) 1.6 ),
		'letter-spacing-h4'                         => array( 'default' => (float) 0 ),
		'word-spacing-h4'                           => array( 'default' => (float) 0 ),
		'line-height-h5'                            => array( 'default' => (float) 1.6 ),
		'letter-spacing-h5'                         => array( 'default' => (float) 1 ),
		'word-spacing-h5'                           => array( 'default' => (float) 0 ),
		'line-height-h6'                            => array( 'default' => (float) 1.6 ),
		'letter-spacing-h6'                         => array( 'default' => (float) 2 ),
		'word-spacing-h6'                           => array( 'default' => (float) 0 ),
		'line-height-site-title'                    => array( 'default' => (float) 1.2 ),
		'letter-spacing-site-title'                 => array( 'default' => (float) 0 ),
		'word-spacing-site-title'                   => array( 'default' => (float) 0 ),
		'line-height-site-tagline'                  => array( 'default' => (float) 1.6 ),
		'letter-spacing-site-tagline'               => array( 'default' => (float) 1 ),
		'word-spacing-site-tagline'                 => array( 'default' => (float) 0 ),
		'line-height-nav'                           => array( 'default' => (float) 1.4 ),
		'letter-spacing-nav'                        => array( 'default' => (float) 0 ),
		'word-spacing-nav'                          => array( 'default' => (float) 0 ),
		'line-height-subnav'                        => array( 'default' => (float) 1.4 ),
		'letter-spacing-subnav'                     => array( 'default' => (float) 0 ),
		'word-spacing-subnav'                       => array( 'default' => (float) 0 ),
		'line-height-header-bar-text'               => array( 'default' => (float) 1.6 ),
		'letter-spacing-header-bar-text'            => array( 'default' => (float) 0 ),
		'word-spacing-header-bar-text'              => array( 'default' => (float) 0 ),
		'line-height-widget-title'                  => array( 'default' => (float) 1.6 ),
		'letter-spacing-widget-title'               => array( 'default' => (float) 0 ),
		'word-spacing-widget-title'                 => array( 'default' => (float) 0 ),
		'line-height-widget'                        => array( 'default' => (float) 1.6 ),
		'letter-spacing-widget'                     => array( 'default' => (float) 0 ),
		'word-spacing-widget'                       => array( 'default' => (float) 0 ),
		'line-height-footer-text'                   => array( 'default' => (float) 1.6 ),
		'letter-spacing-footer-text'                => array( 'default' => (float) 0 ),
		'word-spacing-footer-text'                  => array( 'default' => (float) 0 ),
		'line-height-footer-widget-title'           => array( 'default' => (float) 1.6 ),
		'letter-spacing-footer-widget-title'        => array( 'default' => (float) 0 ),
		'word-spacing-footer-widget-title'          => array( 'default' => (float) 0 ),
		'line-height-footer-widget'                 => array( 'default' => (float) 1.6 ),
		'letter-spacing-footer-widget'              => array( 'default' => (float) 0 ),
		'word-spacing-footer-widget'                => array( 'default' => (float) 0 ),
		'main-background-color-opacity'             => array( 'default' => (float) 1 ),
		'header-background-color-opacity'           => array( 'default' => (float) 1 ),
		'color-subnav-background-opacity'           => array( 'default' => (float) 1 ),
		'color-subnav-background-hover-opacity'     => array( 'default' => (float) 1 ),
		'color-nav-current-item-background-opacity' => array( 'default' => (float) 1 ),
		'header-bar-background-color-opacity'       => array( 'default' => (float) 1 ),
		'footer-background-color-opacity'           => array( 'default' => (float) 1 ),
	),
	array(
		'sanitize' => array( Make()->sanitize(), 'sanitize_float' ),
		'is_style' => true,
	)
);

// Text settings
$this->add_settings(
	array(
		'navigation-mobile-label' => array( 'default' => __( 'Menu', 'make' ) ),
		'general-sticky-label'    => array( 'default' => __( 'Featured', 'make' ) ),
		'label-read-more'         => array( 'default' => __( 'Read more', 'make' ) ),
		'label-search-field'      => array( 'default' => __( 'Search&hellip;', 'make' ) ),
	),
	array(
		'sanitize' => 'sanitize_text_field',
	)
);

// HTML settings
$this->add_settings(
	array_fill_keys( array(
		'header-text',
		'footer-text',
	), array() ),
	array(
		'default'  => '',
		'sanitize' => array( Make()->sanitize(), 'sanitize_text' ),
	)
);

// Color settings
$this->add_settings(
	array(
		'color-primary'                     		=> array( 'default' => '#3070d1' ),
		'color-secondary'                   		=> array( 'default' => '#eaecee' ),
		'color-text'                        		=> array( 'default' => '#171717' ),
		'color-detail'                      		=> array( 'default' => '#b9bcbf' ),
		'color-primary-link'                		=> array( 'default' => '' ),
		'color-button-text'                 		=> array( 'default' => '#ffffff' ),
		'color-button-text-hover'           		=> array( 'default' => '' ),
		'color-button-background'           		=> array( 'default' => '#171717' ),
		'color-button-background-hover'     		=> array( 'default' => '' ),
		'background_color'                  		=> array( 'default' => 'b9bcbf' ), // '#' intentionally left off here
		'main-background-color'             		=> array( 'default' => '#ffffff' ),
		'header-text-color'                 		=> array( 'default' => '#171717' ),
		'header-background-color'           		=> array( 'default' => '#ffffff' ),
		'color-site-title'                  		=> array( 'default' => '' ),
		'color-site-tagline'                		=> array( 'default' => '' ),
		'color-nav-text'                    		=> array( 'default' => '' ),
		'color-nav-text-hover'              		=> array( 'default' => '' ),
		'color-subnav-text'                 		=> array( 'default' => '' ),
		'color-subnav-text-hover'           		=> array( 'default' => '' ),
		'color-subnav-detail'               		=> array( 'default' => '' ),
		'color-subnav-background'           		=> array( 'default' => '' ),
		'color-subnav-background-hover'     		=> array( 'default' => '' ),
		'color-nav-mobile-menu-trigger-background' 	=> array( 'default' => '#000000' ),
		'color-nav-current-item-background' 		=> array( 'default' => '' ),
		'header-bar-text-color'             		=> array( 'default' => '#ffffff' ),
		'header-bar-link-color'             		=> array( 'default' => '' ),
		'header-bar-link-hover-color'       		=> array( 'default' => '' ),
		'header-bar-border-color'           		=> array( 'default' => '#171717' ),
		'header-bar-background-color'       		=> array( 'default' => '#171717' ),
		'color-widget-title-text'           		=> array( 'default' => '' ),
		'color-widget-text'                 		=> array( 'default' => '' ),
		'color-widget-border'               		=> array( 'default' => '' ),
		'color-widget-link'                 		=> array( 'default' => '' ),
		'color-widget-link-hover'           		=> array( 'default' => '' ),
		'footer-text-color'                 		=> array( 'default' => '#464849' ),
		'footer-link-color'                			=> array( 'default' => '' ),
		'footer-link-hover-color'           		=> array( 'default' => '' ),
		'footer-border-color'               		=> array( 'default' => '#b9bcbf' ),
		'footer-background-color'           		=> array( 'default' => '#eaecee' ),
		'color-panel'								=> array( 'default' => '#eaecee' ),
		'color-panel-title'							=> array( 'default' => '#b9bcbf' ),
		'color-panel-active'						=> array( 'default' => '#3070d1' ),
		'color-panel-active-title'					=> array( 'default' => '#eaecee' ),
	),
	array(
		'sanitize' => 'maybe_hash_hex_color',
		'is_style' => true,
	)
);

// Background image settings
$this->add_settings(
	array_fill_keys( array(
		'background_image',
		'header-background-image',
		'main-background-image',
		'footer-background-image',
	), array() ),
	array(
		'default'                  => '',
		'sanitize'                 => 'esc_url',
		'sanitize_from_customizer' => 'esc_url_raw',
		'sanitize_database'        => 'esc_url_raw',
		'sanitize_style'           => 'esc_url_raw',
		'is_style'                 => true,
	)
);

// Logo image settings
$this->add_settings(
	array_fill_keys( array(
		'logo-regular',
		'logo-retina',
		'logo-favicon',
		'logo-apple-touch',
	), array() ),
	array(
		'default'                  => '',
		'sanitize'                 => 'esc_url',
		'sanitize_from_customizer' => 'esc_url_raw',
		'sanitize_database'        => 'esc_url_raw',
		'is_style'                 => true,
	)
);

// Choice settings (alignment-horizontal-3)
$this->add_settings(
	array_fill_keys( array(
		'layout-blog-featured-images-alignment',
		'layout-archive-featured-images-alignment',
		'layout-search-featured-images-alignment',
		'layout-post-featured-images-alignment',
		'layout-page-featured-images-alignment',
	), array() ),
	array(
		'default'       => 'center',
		'sanitize'      => array( Make()->sanitize(), 'sanitize_choice' ),
		'choice_set_id' => 'alignment-horizontal-3',
		'is_style'      => true,
	)
);

// Choice settings (alignment-full-9)
$this->add_settings(
	array(
		'background_position_x'      => array( 'default' => 'left' ),
		'header-background-position' => array( 'default' => 'center' ),
		'main-background-position'   => array( 'default' => 'left' ),
		'footer-background-position' => array( 'default' => 'center' ),
	),
	array(
		'sanitize'      => array( Make()->sanitize(), 'sanitize_choice' ),
		'choice_set_id' => 'alignment-full-9',
		'is_style'      => true,
	)
);

// Choice settings (background-attachment)
$this->add_settings(
	array_fill_keys( array(
		'background_attachment',
		'header-background-attachment',
		'main-background-attachment',
		'footer-background-attachment',
	), array() ),
	array(
		'default'       => 'scroll',
		'sanitize'      => array( Make()->sanitize(), 'sanitize_choice' ),
		'choice_set_id' => 'background-attachment',
		'is_style'      => true,
	)
);

// Choice settings (background-repeat)
$this->add_settings(
	array(
		'background_repeat'        => array( 'default' => 'repeat' ),
		'header-background-repeat' => array( 'default' => 'no-repeat' ),
		'main-background-repeat'   => array( 'default' => 'repeat' ),
		'footer-background-repeat' => array( 'default' => 'no-repeat' ),
	),
	array(
		'sanitize'      => array( Make()->sanitize(), 'sanitize_choice' ),
		'choice_set_id' => 'background-repeat',
		'is_style'      => true,
	)
);

// Choice settings (background-size)
$this->add_settings(
	array(
		'background_size'        => array( 'default' => 'auto' ),
		'header-background-size' => array( 'default' => 'cover' ),
		'main-background-size'   => array( 'default' => 'auto' ),
		'footer-background-size' => array( 'default' => 'cover' ),
	),
	array(
		'sanitize'      => array( Make()->sanitize(), 'sanitize_choice' ),
		'choice_set_id' => 'background-size',
		'is_style'      => true,
	)
);

// Choice settings (comment-count)
$this->add_settings(
	array_fill_keys( array(
		'layout-blog-comment-count',
		'layout-archive-comment-count',
		'layout-search-comment-count',
		'layout-post-comment-count',
		'layout-page-comment-count',
	), array() ),
	array(
		'default'       => 'none',
		'sanitize'      => array( Make()->sanitize(), 'sanitize_choice' ),
		'choice_set_id' => 'comment-count',
	)
);

// Choice settings (featured-images)
$this->add_settings(
	array(
		'layout-blog-featured-images'    => array( 'default' => 'post-header' ),
		'layout-archive-featured-images' => array( 'default' => 'post-header' ),
		'layout-search-featured-images'  => array( 'default' => 'thumbnail' ),
		'layout-post-featured-images'    => array( 'default' => 'post-header' ),
		'layout-page-featured-images'    => array( 'default' => 'none' ),
	),
	array(
		'sanitize'      => array( Make()->sanitize(), 'sanitize_choice' ),
		'choice_set_id' => 'featured-images',
	)
);

// Choice settings (font-family)
$this->add_settings(
	array(
		'font-family-body'                => array( 'default' => 'Open Sans' ),
		'font-family-button'              => array( 'default' => 'Open Sans' ),
		'font-family-h1'                  => array( 'default' => 'sans-serif' ),
		'font-family-h2'                  => array( 'default' => 'sans-serif' ),
		'font-family-h3'                  => array( 'default' => 'sans-serif' ),
		'font-family-h4'                  => array( 'default' => 'sans-serif' ),
		'font-family-h5'                  => array( 'default' => 'sans-serif' ),
		'font-family-h6'                  => array( 'default' => 'sans-serif' ),
		'font-family-site-title'          => array( 'default' => 'sans-serif' ),
		'font-family-site-tagline'        => array( 'default' => 'Open Sans' ),
		'font-family-nav'                 => array( 'default' => 'Open Sans' ),
		'font-family-subnav'              => array( 'default' => 'Open Sans' ),
		'font-family-header-bar-text'     => array( 'default' => 'Open Sans' ),
		'font-family-widget-title'        => array( 'default' => 'Open Sans' ),
		'font-family-widget'              => array( 'default' => 'Open Sans' ),
		'font-family-footer-text'         => array( 'default' => 'Open Sans' ),
		'font-family-footer-widget-title' => array( 'default' => 'Open Sans' ),
		'font-family-footer-widget'       => array( 'default' => 'Open Sans' ),
	),
	array(
		'sanitize'                 => 'sanitize_text_field',
		'sanitize_from_customizer' => array( Make()->sanitize(), 'sanitize_font_choice' ),
		'sanitize_to_customizer'   => array( Make()->sanitize(), 'sanitize_font_choice' ),
		'is_style'                 => true,
		'is_font'                  => true,
		// The choice set for font family is too big, so is handled separately.
	)
);

// Choice settings (font-style)
$this->add_settings(
	array_fill_keys( array(
		'font-style-body',
		'font-style-button',
		'font-style-h1',
		'font-style-h2',
		'font-style-h3',
		'font-style-h4',
		'font-style-h5',
		'font-style-h6',
		'font-style-site-title',
		'font-style-site-tagline',
		'font-style-nav',
		'font-style-subnav',
		'font-style-header-bar-text',
		'font-style-widget-title',
		'font-style-widget',
		'font-style-footer-text',
		'font-style-footer-widget-title',
		'font-style-footer-widget',
	), array() ),
	array(
		'default'       => 'normal',
		'sanitize'      => array( Make()->sanitize(), 'sanitize_choice' ),
		'choice_set_id' => 'font-style',
		'is_style'      => true,
	)
);

// Choice settings (font-weight)
$this->add_settings(
	array(
		'font-weight-body'                => array( 'default' => 'normal' ),
		'font-weight-body-link'           => array( 'default' => 'bold' ),
		'font-weight-button'              => array( 'default' => 'normal' ),
		'font-weight-h1'                  => array( 'default' => 'normal' ),
		'font-weight-h2'                  => array( 'default' => 'bold' ),
		'font-weight-h3'                  => array( 'default' => 'bold' ),
		'font-weight-h4'                  => array( 'default' => 'normal' ),
		'font-weight-h5'                  => array( 'default' => 'bold' ),
		'font-weight-h6'                  => array( 'default' => 'normal' ),
		'font-weight-site-title'          => array( 'default' => 'bold' ),
		'font-weight-site-tagline'        => array( 'default' => 'normal' ),
		'font-weight-nav'                 => array( 'default' => 'normal' ),
		'font-weight-subnav'              => array( 'default' => 'normal' ),
		'font-weight-nav-current-item'    => array( 'default' => 'bold' ),
		'font-weight-header-bar-text'     => array( 'default' => 'normal' ),
		'font-weight-widget-title'        => array( 'default' => 'bold' ),
		'font-weight-widget'              => array( 'default' => 'normal' ),
		'font-weight-footer-text'         => array( 'default' => 'normal' ),
		'font-weight-footer-widget-title' => array( 'default' => 'bold' ),
		'font-weight-footer-widget'       => array( 'default' => 'normal' ),
	),
	array(
		'sanitize'      => array( Make()->sanitize(), 'sanitize_choice' ),
		'choice_set_id' => 'font-weight',
		'is_style'      => true,
	)
);

// Choice settings (link-underline)
$this->add_settings(
	array_fill_keys( array(
		'link-underline-body',
		'link-underline-h1',
		'link-underline-h2',
		'link-underline-h3',
		'link-underline-h4',
		'link-underline-h5',
		'link-underline-h6',
		'link-underline-site-title',
		'link-underline-site-tagline',
		'link-underline-nav',
		'link-underline-subnav',
		'link-underline-header-bar-text',
		'link-underline-widget-title',
		'link-underline-widget',
		'link-underline-footer-text',
		'link-underline-footer-widget-title',
		'link-underline-footer-widget',
	), array() ),
	array(
		'default'       => 'never',
		'sanitize'      => array( Make()->sanitize(), 'sanitize_choice' ),
		'choice_set_id' => 'link-underline',
		'is_style'      => true,
	)
);

// Choice settings (post-author)
$this->add_settings(
	array(
		'layout-blog-post-author'    => array( 'default' => 'avatar' ),
		'layout-archive-post-author' => array( 'default' => 'avatar' ),
		'layout-search-post-author'  => array( 'default' => 'name' ),
		'layout-post-post-author'    => array( 'default' => 'avatar' ),
		'layout-page-post-author'    => array( 'default' => 'none' ),
	),
	array(
		'sanitize'      => array( Make()->sanitize(), 'sanitize_choice' ),
		'choice_set_id' => 'post-author',
	)
);

// Choice settings (post-date)
$this->add_settings(
	array(
		'layout-blog-post-date'    => array( 'default' => 'absolute' ),
		'layout-archive-post-date' => array( 'default' => 'absolute' ),
		'layout-search-post-date'  => array( 'default' => 'absolute' ),
		'layout-post-post-date'    => array( 'default' => 'absolute' ),
		'layout-page-post-date'    => array( 'default' => 'none' ),
	),
	array(
		'sanitize'      => array( Make()->sanitize(), 'sanitize_choice' ),
		'choice_set_id' => 'post-date',
	)
);

// Choice settings (post-item-location)
$this->add_settings(
	array(
		'layout-blog-post-date-location'        => array( 'default' => 'top' ),
		'layout-archive-post-date-location'     => array( 'default' => 'top' ),
		'layout-search-post-date-location'      => array( 'default' => 'top' ),
		'layout-post-post-date-location'        => array( 'default' => 'top' ),
		'layout-page-post-date-location'        => array( 'default' => 'top' ),
		'layout-blog-post-author-location'      => array( 'default' => 'post-footer' ),
		'layout-archive-post-author-location'   => array( 'default' => 'post-footer' ),
		'layout-search-post-author-location'    => array( 'default' => 'post-footer' ),
		'layout-post-post-author-location'      => array( 'default' => 'post-footer' ),
		'layout-page-post-author-location'      => array( 'default' => 'post-footer' ),
		'layout-blog-comment-count-location'    => array( 'default' => 'before-content' ),
		'layout-archive-comment-count-location' => array( 'default' => 'before-content' ),
		'layout-search-comment-count-location'  => array( 'default' => 'before-content' ),
		'layout-post-comment-count-location'    => array( 'default' => 'before-content' ),
		'layout-page-comment-count-location'    => array( 'default' => 'before-content' ),
	),
	array(
		'sanitize'      => array( Make()->sanitize(), 'sanitize_choice' ),
		'choice_set_id' => 'post-item-location',
	)
);

// Choice settings (text-transform)
$this->add_settings(
	array(
		'text-transform-body'                => array( 'default' => 'none' ),
		'text-transform-button'              => array( 'default' => 'none' ),
		'text-transform-h1'                  => array( 'default' => 'none' ),
		'text-transform-h2'                  => array( 'default' => 'none' ),
		'text-transform-h3'                  => array( 'default' => 'none' ),
		'text-transform-h4'                  => array( 'default' => 'none' ),
		'text-transform-h5'                  => array( 'default' => 'uppercase' ),
		'text-transform-h6'                  => array( 'default' => 'uppercase' ),
		'text-transform-site-title'          => array( 'default' => 'none' ),
		'text-transform-site-tagline'        => array( 'default' => 'uppercase' ),
		'text-transform-nav'                 => array( 'default' => 'none' ),
		'text-transform-subnav'              => array( 'default' => 'none' ),
		'text-transform-header-bar-text'     => array( 'default' => 'none' ),
		'text-transform-widget-title'        => array( 'default' => 'none' ),
		'text-transform-widget'              => array( 'default' => 'none' ),
		'text-transform-footer-text'         => array( 'default' => 'none' ),
		'text-transform-footer-widget-title' => array( 'default' => 'none' ),
		'text-transform-footer-widget'       => array( 'default' => 'none' ),
	),
	array(
		'sanitize'      => array( Make()->sanitize(), 'sanitize_choice' ),
		'choice_set_id' => 'text-transform',
		'is_style'      => true,
	)
);

// Choice settings (misc)
$this->add_settings(
	array(
		'footer-layout' => array(
			'default'       => 1,
			'choice_set_id' => 'footer-layout',
		),
		'footer-widget-areas' => array(
			'default'       => 3,
			'choice_set_id' => '0-4',
		),
		'general-layout' => array(
			'default'       => 'full-width',
			'choice_set_id' => 'general-layout',
		),
		'header-layout' => array(
			'default'       => 1,
			'choice_set_id' => 'header-layout',
		),
		'header-branding-position' => array(
			'default'       => 'left',
			'choice_set_id' => 'alignment-horizontal-2',
		),
		'header-bar-content-layout' => array(
			'default'       => 'default',
			'choice_set_id' => 'header-bar-content-layout',
		),
		'mobile-menu' => array(
			'default'       => 'primary',
			'choice_set_id' => 'mobile-menu',
		)
	),
	array(
		'sanitize' => array( Make()->sanitize(), 'sanitize_choice' ),
	)
);

// Misc
$this->add_settings(
	array(
		'font-stack-cache' => array(
			'default'  => array(),
			'sanitize' => array( Make()->sanitize(), 'sanitize_font_stack_cache' ),
			'is_cache' => true,
			'is_array' => true,
		),
	)
);

// Google fonts
if ( $this->font()->has_source( 'google' ) ) {
	$this->add_settings(
		array(
			'font-subset'     => array(
				'default'                  => 'latin',
				'sanitize'                 => 'sanitize_key',
				'sanitize_from_customizer' => array( Make()->sanitize(), 'sanitize_google_font_subset' ),
				'sanitize_to_customizer'   => array( Make()->sanitize(), 'sanitize_google_font_subset' ),
			),
			'google-font-url' => array(
				'default'           => '',
				'sanitize'          => 'esc_url',
				'sanitize_database' => 'esc_url_raw',
				'is_cache'          => true,
			),
		)
	);
}

// Social Icons
$this->add_settings(
	array(
		'social-icons-email-toggle'       => array(
			'default'            => false,
			'sanitize'           => 'wp_validate_boolean',
			'social_icon_option' => true,
		),
		'social-icons-rss-toggle'         => array(
			'default'            => true,
			'sanitize'           => 'wp_validate_boolean',
			'social_icon_option' => true,
		),
		'social-icons-new-window'         => array(
			'default'            => false,
			'sanitize'           => 'wp_validate_boolean',
			'social_icon_option' => true,
		),
		'social-icons-item-type'          => array(
			'default'       => 'link',
			'sanitize'      => array( Make()->sanitize(), 'sanitize_choice' ),
			'choice_set_id' => 'social-icon-type',
		),
		'social-icons-item-content-email' => array(
			'default'  => '',
			'sanitize' => 'sanitize_email',
		),
		'social-icons-item-content-rss'   => array(
			'default'                  => '',
			'sanitize'                 => 'esc_url',
			'sanitize_from_customizer' => 'esc_url_raw',
			'sanitize_database'        => 'esc_url_raw',
		),
		'social-icons-item-content-link'  => array(
			'default'                  => '',
			'sanitize'                 => 'esc_url',
			'sanitize_from_customizer' => 'esc_url_raw',
			'sanitize_database'        => 'esc_url_raw',
		),
		'social-icons'                    => array(
			'default'                  => array(
				'items'        => array(
					array(
						'type'    => 'rss',
						'content' => '',
					),
				),
				'email-toggle' => false,
				'rss-toggle'   => true,
				'new-window'   => false,
			),
			'sanitize'                 => array( Make()->sanitize(), 'sanitize_socialicons' ),
			'sanitize_from_customizer' => array( Make()->sanitize(), 'sanitize_socialicons_from_customizer' ),
			'sanitize_to_customizer'   => array( Make()->sanitize(), 'sanitize_socialicons_to_customizer' ),
			'is_array'                 => true,
		),
	)
);
