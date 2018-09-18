<?php
/**
 * @package Make
 */
?>

<?php if ( ! empty( $header_bar_menu ) ): ?>
    <nav class="header-navigation" role="navigation">
        <?php if ( 'header-bar' === $mobile_menu ): ?>
            <span class="menu-toggle"><?php echo make_get_thememod_value( 'navigation-mobile-label' ); ?></span>
        <?php endif;?>
        <?php echo $header_bar_menu; ?>
    </nav>
<?php endif; ?>