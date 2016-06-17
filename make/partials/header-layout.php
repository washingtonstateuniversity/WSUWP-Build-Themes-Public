<?php
/**
 * @package Make
 */

// Header Options
$subheader_class = ( make_get_thememod_value( 'header-show-social' ) || make_get_thememod_value( 'header-show-search' ) ) ? ' right-content' : '';
$header_bar_menu = wp_nav_menu( array(
	'theme_location'  => 'header-bar',
	'container_class' => 'header-bar-menu',
	'depth'           => 1,
	'fallback_cb'     => false,
	'echo'            => false,
) );
?>

<header id="site-header" class="<?php echo esc_attr( ttfmake_get_site_header_class() ); ?>" role="banner">
	<?php // Only show Header Bar if it has content
	if (
		make_get_thememod_value( 'header-text' )
		||
		make_get_thememod_value( 'header-show-search' )
		||
		( make_has_socialicons() && make_get_thememod_value( 'header-show-social' ) )
		||
		! empty( $header_bar_menu )
	) : ?>
	<div class="header-bar<?php echo esc_attr( $subheader_class ); ?>">
		<div class="container">
			<?php // Search form
			if ( make_get_thememod_value( 'header-show-search' ) ) :
				get_search_form();
			endif; ?>
			<?php // Social links
			make_socialicons( 'header' ); ?>
			<?php // Header text; shown only if there is no header menu
			if ( ( make_get_thememod_value( 'header-text' ) || is_customize_preview() ) && empty( $header_bar_menu ) ) : ?>
				<span class="header-text">
				<?php echo make_get_thememod_value( 'header-text' ); ?>
				</span>
			<?php endif; ?>
			<?php echo $header_bar_menu; ?>
		</div>
	</div>
	<?php endif; ?>
	<div class="site-header-main">
		<div class="container">
			<div class="site-branding">
				<?php // Logo
				if ( make_has_logo() ) : ?>
					<?php make_logo(); ?>
				<?php endif; ?>
				<?php // Site title
				if ( get_bloginfo( 'name' ) ) : ?>
				<h1 class="site-title<?php if ( make_get_thememod_value( 'hide-site-title' ) ) echo ' screen-reader-text'; ?>">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
				</h1>
				<?php endif; ?>
				<?php // Tagline
				if ( get_bloginfo( 'description' ) ) : ?>
				<span class="site-description<?php if ( make_get_thememod_value( 'hide-tagline' ) ) echo ' screen-reader-text'; ?>">
					<?php bloginfo( 'description' ); ?>
				</span>
				<?php endif; ?>
			</div>

			<nav id="site-navigation" class="site-navigation" role="navigation">
				<span class="menu-toggle"><?php echo make_get_thememod_value( 'navigation-mobile-label' ); ?></span>
				<?php
				wp_nav_menu( array(
					'theme_location' => 'primary'
				) );
				?>
			</nav>
		</div>
	</div>
</header>