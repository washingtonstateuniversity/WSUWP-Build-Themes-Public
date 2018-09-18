<?php
/**
 * @package Make
 */
ttfmake_load_section_header();

global $ttfmake_section_data;
?>

<div class="ttfmake-banner-slides">
	<div class="ttfmake-banner-slides-stage"></div>
	<div class="ttfmake-add-item-wrapper">
		<a href="#" class="ttfmake-add-slide ttfmake-banner-add-item-link" title="<?php esc_attr_e( 'Add new slide', 'make' ); ?>">
			<span>
				<?php esc_html_e( 'Add new slide', 'make' ); ?>
			</span>
		</a>
	</div>
</div>

<?php ttfmake_load_section_footer();