<?php
/**
 * @package Make
 */

/**
 * Allow toggling of the footer credit.
 *
 * @since 1.2.3.
 *
 * @param bool    $show    Whether or not to show the footer credit.
 */
$footer_credit = apply_filters( 'make_show_footer_credit', true );
?>

<div class="site-info">
	<?php if ( make_get_thememod_value( 'footer-text' ) || is_customize_preview() ) : ?>
	<div class="footer-text">
		<?php echo make_get_thememod_value( 'footer-text' ); ?>
	</div>
	<?php endif; ?>

	<?php if ( true === $footer_credit ) : ?>
	<div class="footer-credit">
		<?php
		printf(
			wp_kses(
				__( 'Built with <a class="theme-name" href="%s" target="_blank">Make</a>. Your friendly WordPress page builder theme.', 'make' ),
				array( 'a' => array( 'class' => true, 'href' => true, 'target' => true ) )
			),
			'https://thethemefoundry.com/make/'
		);
		?>
	</div>
	<?php endif; ?>
</div>
