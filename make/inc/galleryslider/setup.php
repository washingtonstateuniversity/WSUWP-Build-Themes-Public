<?php
/**
 * @package Make
 */

/**
 * Class MAKE_GallerySlider_Setup
 *
 * Enhances the core [gallery] shortcode with a slider option.
 *
 * @since 1.0.0.
 * @since 1.7.0. Renamed from TTFMAKE_Gallery_Slider
 */
class MAKE_GallerySlider_Setup extends MAKE_Util_Modules implements MAKE_GallerySlider_SetupInterface, MAKE_Util_HookInterface {
	/**
	 * An associative array of required modules.
	 *
	 * @since 1.7.0.
	 *
	 * @var array
	 */
	protected $dependencies = array(
		'scripts' => 'MAKE_Setup_ScriptsInterface',
	);

	/**
	 * Indicator of whether the hook routine has been run.
	 *
	 * @since 1.7.0.
	 *
	 * @var bool
	 */
	private static $hooked = false;

	/**
	 * Hook into WordPress.
	 *
	 * @since 1.7.0.
	 *
	 * @return void
	 */
	public function hook() {
		if ( $this->is_hooked() ) {
			return;
		}

		// Filter the gallery shortcode output
		add_filter( 'post_gallery', array( $this, 'render_gallery' ), 1001, 2 );

		// Admin scripts
		if ( is_admin() ) {
			add_action( 'wp_enqueue_media', array( $this, 'enqueue_media' ), 99 );
			add_action( 'print_media_templates', array( $this, 'print_media_templates' ) );
		}

		// Hooking has occurred.
		self::$hooked = true;
	}

	/**
	 * Check if the hook routine has been run.
	 *
	 * @since 1.7.0.
	 *
	 * @return bool
	 */
	public function is_hooked() {
		return self::$hooked;
	}

	/**
	 * Enqueue the admin script that handles the slider settings in the Media Manager
	 *
	 * @since 1.0.0.
	 *
	 * @hooked action wp_enqueue_media
	 *
	 * @return void
	 */
	function enqueue_media() {
		wp_enqueue_script(
			'make-admin-gallery-settings',
			$this->scripts()->get_js_directory_uri() . '/galleryslider/galleryslider.js',
			array( 'media-views' ),
			TTFMAKE_VERSION,
			true
		);
	}

	/**
	 * Markup for the slider settings in the Media Manager
	 *
	 * @since 1.0.0.
	 *
	 * @hooked action print_media_templates
	 *
	 * @return void
	 */
	function print_media_templates() {
		?>
		<script type="text/html" id="tmpl-ttfmake-gallery-settings">
			<h3 style="float:left;margin-top:10px;"><?php esc_html_e( 'Slider Settings', 'make' ); ?></h3>
			<label class="setting">
				<span><?php esc_html_e( 'Show gallery as slider', 'make' ); ?></span>
				<input id="ttfmake-slider" type="checkbox" data-setting="ttfmake_slider" />
			</label>
			<div id="ttfmake-slider-settings">
				<label class="setting">
					<span><?php esc_html_e( 'Hide navigation arrows', 'make' ); ?></span>
					<input type="checkbox" data-setting="ttfmake_prevnext" />
				</label>
				<label class="setting">
					<span><?php esc_html_e( 'Hide navigation dots', 'make' ); ?></span>
					<input type="checkbox" data-setting="ttfmake_pager" />
				</label>
				<label class="setting">
					<span><?php esc_html_e( 'Autoplay', 'make' ); ?></span>
					<input type="checkbox" data-setting="ttfmake_autoplay" />
				</label>
				<label class="setting">
					<span><?php esc_html_e( 'Time between slides (ms)', 'make' ); ?></span>
					<input type="text" data-setting="ttfmake_delay" style="float:left;width:25%;" />
				</label>
				<label class="setting">
					<span><?php esc_html_e( 'Effect', 'make' ); ?></span>
					<select data-setting="ttfmake_effect">
						<option value="scrollHorz" selected="selected"><?php esc_html_e( 'Slide horizontal', 'make' ); ?></option>
						<option value="fade"><?php esc_html_e( 'Fade', 'make' ); ?></option>
						<option value="none"><?php esc_html_e( 'None', 'make' ); ?></option>
					</select>
				</label>
			</div>
		</script>
	<?php
	}

	/**
	 * Alternate gallery shortcode handler for the slider
	 *
	 * @since  1.0.0.
	 *
	 * @hooked filter post_gallery
	 *
	 * @param string $output    The original shortcode output.
	 * @param array  $attr      The shortcode attrs.
	 *
	 * @return string           The modified gallery code.
	 */
	function render_gallery( $output, $attr ) {
		// Only use this alternative output if the slider is set to true
		if ( isset( $attr['ttfmake_slider'] ) && true == $attr['ttfmake_slider'] ) {
			// Add Cycle2 as a dependency for the Frontend script
			$this->scripts()->add_dependency( 'make-frontend', 'cycle2', 'script' );
			if ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) {
				$this->scripts()->add_dependency( 'make-frontend', 'cycle2-center', 'script' );
				$this->scripts()->add_dependency( 'make-frontend', 'cycle2-swipe', 'script' );
			}

			$post = get_post();

			if ( ! empty( $attr['ids'] ) ) {
				// 'ids' is explicitly ordered, unless you specify otherwise.
				if ( empty( $attr['orderby'] ) ) {
					$attr['orderby'] = 'post__in';
				}

				$attr['include'] = $attr['ids'];
			}

			// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
			if ( isset( $attr['orderby'] ) ) {
				$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
				if ( !$attr['orderby'] ) {
					unset( $attr['orderby'] );
				}
			}

			$attr = shortcode_atts( array(
				// Built-in
				'order'            => 'ASC',
				'orderby'          => 'menu_order ID',
				'id'               => $post ? $post->ID : 0,
				'size'             => 'large',
				'include'          => '',
				'exclude'          => '',

				// Make slider
				'ttfmake_slider'   => true,
				'ttfmake_autoplay' => false,
				'ttfmake_prevnext' => false,
				'ttfmake_pager'    => false,
				'ttfmake_delay'    => 6000,
				'ttfmake_effect'   => 'scrollHorz'
			), $attr, 'gallery');

			$attr['id'] = intval( $attr['id'] );
			if ( 'RAND' == $attr['order'] ) {
				$attr['orderby'] = 'none';
			}

			if ( !empty( $attr['include'] ) ) {
				$_attachments = get_posts( array(
					'include'        => $attr['include'],
					'post_status'    => 'inherit',
					'post_type'      => 'attachment',
					'post_mime_type' => 'image',
					'order'          => $attr['order'],
					'orderby'        => $attr['orderby']
				) );

				$attachments = array();
				foreach ( $_attachments as $key => $val ) {
					$attachments[ $val->ID ] = $_attachments[ $key ];
				}
			}
			elseif ( !empty( $attr['exclude'] ) ) {
				$attachments = get_children( array(
					'post_parent'    => $attr['id'],
					'exclude'        => $attr['exclude'],
					'post_status'    => 'inherit',
					'post_type'      => 'attachment',
					'post_mime_type' => 'image',
					'order'          => $attr['order'],
					'orderby'        => $attr['orderby']
				) );
			}
			else {
				$attachments = get_children( array(
					'post_parent'    => $attr['id'],
					'post_status'    => 'inherit',
					'post_type'      => 'attachment',
					'post_mime_type' => 'image',
					'order'          => $attr['order'],
					'orderby'        => $attr['orderby']
				) );
			}

			if ( empty( $attachments ) ) {
				return '';
			}

			if ( is_feed() ) {
				$output = "\n";
				foreach ( $attachments as $att_id => $attachment ) {
					$output .= wp_get_attachment_link( $att_id, $attr['size'], true ) . "\n";
				}
				return $output;
			}
			// End core code

			// Classes
			$classes = 'cycle-slideshow';

			// Data attributes
			$data_attributes  = ' data-cycle-slides=".cycle-slide"';
			$data_attributes .= ' data-cycle-loader="wait"';
			$data_attributes .= ' data-cycle-auto-height="calc"';
			$data_attributes .= ' data-cycle-center-horz="true"';
    		$data_attributes .= ' data-cycle-center-vert="true"';
			$data_attributes .= ' data-cycle-swipe="true"';
			if ( true != $attr[ 'ttfmake_prevnext' ] ) {
				$data_attributes .= ' data-cycle-prev="~ .cycle-prev"';
				$data_attributes .= ' data-cycle-next="~ .cycle-next"';
			}
			if ( true != $attr[ 'ttfmake_pager' ] ) {
				$data_attributes .= ' data-cycle-pager="~ .cycle-pager"';
			}
			if ( ! defined( 'SCRIPT_DEBUG' ) || false === SCRIPT_DEBUG ) {
				$data_attributes .= ' data-cycle-log="false"';
			}

			// No autoplay
			$autoplay = (bool) $attr['ttfmake_autoplay'];
			if ( false === $autoplay ) {
				$data_attributes .= ' data-cycle-paused="true"';
			}

			// Delay
			$delay = absint( $attr['ttfmake_delay'] );
			if ( 0 === $delay ) {
				$delay = 6000;
			}

			if ( 4000 !== $delay ) {
				$data_attributes .= ' data-cycle-timeout="' . esc_attr( $delay ) . '"';
			}

			// Effect
			$effect = trim( $attr['ttfmake_effect'] );
			if ( ! in_array( $effect, array( 'fade', 'scrollHorz', 'none' ) ) ) {
				$effect = 'scrollHorz';
			}

			if ( 'fade' !== $effect ) {
				$data_attributes .= ' data-cycle-fx="' . esc_attr( $effect ) . '"';
			}

			// Markup
			ob_start(); ?>
			<div class="ttfmake-shortcode-slider">
				<div class="<?php echo esc_attr( $classes ); ?>"<?php echo $data_attributes; ?>>
					<?php foreach ( $attachments as $id => $attachment ) : ?>
					<figure class="cycle-slide">
						<?php echo wp_get_attachment_image( $id, $attr[ 'size' ], false ); ?>
						<?php if ( trim( $attachment->post_excerpt ) ) : ?>
						<figcaption class="cycle-caption">
							<?php echo wptexturize( $attachment->post_excerpt ); ?>
						</figcaption>
						<?php endif; ?>
					</figure>
					<?php endforeach; ?>
				</div>
				<?php if ( true != $attr[ 'ttfmake_prevnext' ] ) : ?>
				<div class="cycle-prev"></div>
				<div class="cycle-next"></div>
				<?php endif; ?>
				<?php if ( true != $attr[ 'ttfmake_pager' ] ) : ?>
				<div class="cycle-pager"></div>
				<?php endif; ?>
			</div>
			<?php
			$output = ob_get_clean();
		}

		return $output;
	}
}