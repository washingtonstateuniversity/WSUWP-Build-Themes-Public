<?php
/**
 * @package Make
 */
get_header();
?>

<main id="site-main" class="site-main" role="main">
	<article class="error-404 not-found">
		<header class="entry-header">
			<?php make_breadcrumb(); ?>
			<h1 class="entry-title">
				<?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'make' ); ?>
			</h1>
		</header>

		<div class="entry-content">
			<p>
				<?php esc_html_e( 'Maybe try searching this website:', 'make' ); ?>
			</p>
			<?php get_search_form(); ?>
		</div>
	</article>
</main>

<?php get_footer(); ?>