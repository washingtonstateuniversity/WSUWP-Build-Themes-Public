<div class="<?php echo $ttfmake_overlay_class; ?>" id="<?php echo $ttfmake_overlay_id; ?>">
	<div class="ttfmake-overlay-wrapper">
		<div class="ttfmake-overlay-dialog">
			<div class="ttfmake-overlay-header">
				<div class="ttfmake-overlay-window-head">
					<div class="ttfmake-overlay-title"><?php echo $ttfmake_overlay_title; ?></div>
					<button type="button" class="media-modal-close ttfmake-overlay-close-discard">
						<span class="media-modal-icon">
					</button>
				</div>
			</div>
			<div class="ttfmake-overlay-body">
				<?php
				/**
				 * Action: Fires before the overlay body gets rendered.
				 *
				 * This action gives a developer the opportunity to output additional
				 * content before an overlay body.
				 *
				 * @since 1.9.9.
				 *
				 * @param string $ttfmake_overlay_id The html id of the overlay.
				 */
				do_action( 'make_overlay_body_before', $ttfmake_overlay_id ); ?>