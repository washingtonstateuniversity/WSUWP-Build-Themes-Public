<?php
/**
 * @package Make
 */

/**
 * Class MAKE_Customizer_Control_BackgroundPosition
 *
 * Specialized radio control for choosing background image positioning.
 *
 * @since 1.5.0.
 * @since 1.7.0. Converted to content_template().
 */
class MAKE_Customizer_Control_BackgroundPosition extends WP_Customize_Control {
	/**
	 * The control type.
	 *
	 * @since 1.5.0.
	 * @since 1.7.0. Changed to 'make_backgroundposition'
	 *
	 * @var string
	 */
	public $type = 'make_backgroundposition';

	/**
	 * MAKE_Customizer_Control_BackgroundPosition constructor.
	 *
	 * @since 1.7.0.
	 *
	 * @param WP_Customize_Manager $manager
	 * @param string               $id
	 * @param array                $args
	 */
	public function __construct( WP_Customize_Manager $manager, $id, array $args ) {
		parent::__construct( $manager, $id, $args );

		// Ensure this instance maintains the proper type value.
		$this->type = 'make_backgroundposition';
	}

	/**
	 * Enqueue necessary scripts for this control.
	 *
	 * @since 1.5.0.
	 *
	 * @return void
	 */
	public function enqueue() {
		wp_enqueue_script( 'jquery-ui-button' );
	}

	/**
	 * Add extra properties to JSON array.
	 *
	 * @since 1.7.0.
	 *
	 * @return array
	 */
	public function json() {
		$json = parent::json();

		$json['id'] = $this->id;
		$json['choices'] = $this->choices;
		$json['value'] = $this->value();
		$json['link'] = $this->get_link();

		return $json;
	}

	/**
	 * Define the JS template for the control.
	 *
	 * @since 1.7.0.
	 *
	 * @return void
	 */
	protected function content_template() {
		?>
		<# if (data.label) { #>
			<span class="customize-control-title">{{ data.label }}</span>
		<# } #>
		<# if (data.description) { #>
			<span class="description customize-control-description">{{{ data.description }}}</span>
		<# } #>

		<div class="customize-control-content">
			<fieldset>
				<div id="input_{{ data.id }}" class="make-backgroundposition-container background-position-control">
					<div class="button-group">
						<#
						var i = 1;
						var icons = [
							'dashicons dashicons-arrow-left-alt',
							'dashicons dashicons-arrow-up-alt',
							'dashicons dashicons-arrow-right-alt',
							'dashicons dashicons-arrow-left-alt',
							'background-position-center-icon',
							'dashicons dashicons-arrow-right-alt',
							'dashicons dashicons-arrow-left-alt',
							'dashicons dashicons-arrow-down-alt',
							'dashicons dashicons-arrow-right-alt'
						];

						for ( key in data.choices ) { #>
							<label for="{{ data.id }}{{ key }}" class="choice-{{ i }}" data-label="{{ data.choices[ key ] }}">
								<input id="{{ data.id }}{{ key }}" class="screen-reader-text" name="_customize-radio-{{ data.id }}" type="radio" value="{{ key }}" {{{ data.link }}}<# if (key === data.value) { #> checked="checked" <# } #> />
								<span class="button display-options position">
									<span class="{{icons[i - 1]}}" aria-hidden="true"></span>
								</span>
							</label>
						<# i++; #>
					<# if (i < 9 && (i - 1) % 3 == 0) { #>
					</div>
					<div class="button-group">
					<# } #>
						<# } #>
					</div>
				</div>
			</fieldset>
		</div>
	<?php
	}
}