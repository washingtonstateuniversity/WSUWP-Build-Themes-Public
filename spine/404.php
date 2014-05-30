<?php get_header(); ?>

	<main class="spine-single-template">

		<?php get_template_part('parts/headers'); ?>

		<section class="row single">

			<div class="column one">

				<article id="post-0" class="post error404 no-results not-found">

					<header class="article-header">
						<h1 class="article-title">This is somewhat embarrassing, isn't it?</h1>
					</header>

					<div class="entry-content">
						<p>It seems we can't find what you're looking for. Perhaps searching can help.</p>
						<?php get_search_form(); ?>
					</div><!-- .entry-content -->

				</article>

			</div><!--/column-->

		</section>
	</main>

<?php get_footer(); ?>