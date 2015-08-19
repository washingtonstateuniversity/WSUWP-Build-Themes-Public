<?php global $ttfmake_overlay_id, $ttfmake_overlay_class, $ttfmake_overlay_title; ?>
<div class="ttfmake-overlay <?php if ( ! empty( $ttfmake_overlay_class ) ) echo esc_attr( $ttfmake_overlay_class ); ?>"<?php if ( ! empty( $ttfmake_overlay_id ) ) echo ' id="' . esc_attr( $ttfmake_overlay_id ) . '"'; ?>>
	<div class="ttfmake-overlay-wrapper">
		<div class="ttfmake-overlay-header">
			<div class="ttfmake-overlay-window-head">
				<div class="ttfmake-overlay-title"><?php if ( ! empty( $ttfmake_overlay_title ) ) : echo esc_html( $ttfmake_overlay_title ); else : esc_html_e( 'Configuration', 'make' ); endif; ?></div>
				<span class="ttfmake-overlay-close ttfmake-overlay-close-action" aria-hidden="true"><?php esc_html_e( 'Done', 'make' ); ?></span>
			</div>
		</div>
		<div class="ttfmake-overlay-body">