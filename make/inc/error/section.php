<?php
/**
 * @package Make
 */

/**
 * Class MAKE_Error_Section
 *
 * Customizer section for surfacing Make errors within the Customizer interface.
 *
 * @since 1.7.0.
 */
class MAKE_Error_Section extends WP_Customize_Section {
	/**
	 * The section type.
	 *
	 * @since 1.7.0.
	 *
	 * @var string
	 */
	public $type = 'make_error';

	/**
	 * Output a JS template for the Make Errors section.
	 *
	 * @since 1.7.0.
	 *
	 * @return void
	 */
	protected function render_template() {
		?>
		<li id="accordion-section-{{ data.id }}" class="accordion-section control-section control-section-{{ data.type }}">
			<h3 class="accordion-section-title">
				<?php esc_html_e( 'No Make Notices', 'make' ); ?>
			</h3>
		</li>
	<?php
	}
}