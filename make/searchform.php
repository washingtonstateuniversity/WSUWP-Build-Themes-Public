<?php
/**
 * @package Make
 */
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<input type="search" class="search-field" id="search-field" placeholder="<?php echo make_get_thememod_value( 'label-search-field' ); ?>" title="<?php esc_attr_e( 'Press Enter to submit your search', 'make' ) ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" aria-label="<?php /* Translators: this string is a label for a search input that is only visible to screen readers. */ esc_html_e( 'Search for:', 'make' ); ?>">
	<input type="submit" class="search-submit" value="<?php /* Translators: this string is the label on the search submit button. */ esc_attr_e( 'Search', 'make' ); ?>" aria-label="<?php /* Translators: this string is the label on the search submit button for screen readers. */ esc_attr_e( 'Search', 'make' ); ?>" role="button">
</form>
