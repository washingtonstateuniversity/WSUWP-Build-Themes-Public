<?php
/**
 * @package Make
 */
?>

<nav id="site-navigation" class="site-navigation" role="navigation">
	<?php if ( 'primary' === $mobile_menu ): ?>
		<span class="menu-toggle"><?php echo make_get_thememod_value( 'navigation-mobile-label' ); ?></span>
	<?php endif;?>
	<?php
	$nav_menu_container_class = 'primary' === $mobile_menu ? ' mobile-menu': 'desktop-menu';

	wp_nav_menu( array(
		'theme_location' => 'primary',
		'container_class' => $nav_menu_container_class,
		'fallback_cb'    => false,
	) );
	?>
</nav>