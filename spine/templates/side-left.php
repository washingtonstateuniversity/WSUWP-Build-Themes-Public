<?php /* Template Name: Side - Left */ ?>

<?php get_header(); ?>

<main class="spine-sideleft-template">

<?php if ( have_posts() ) : while( have_posts() ) : the_post(); ?>

<?php get_template_part('parts/headers'); ?> 

<section class="row side-left">

	<div class="column one">
	
		<?php 
		$column = get_post_meta( get_the_ID(), 'column-one', true );
		if( ! empty( $column ) ) { echo $column; }
		?>
		
	</div><!--/column-->
	
	<div class="column two">
		
		<?php get_template_part('articles/article'); ?>
		
	</div>

</section>
<?php endwhile; endif; ?>
</main>

<?php get_footer(); ?>