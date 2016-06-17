<?php
/**
 * @package Make
 */

/**
 * Class MAKE_Customizer_Control_Radio
 *
 * Specialized radio control to enable buttonset-style choices.
 *
 * Inspired by Kirki.
 * @link https://github.com/aristath/kirki/blob/0.5/includes/controls/class-Kirki_Customize_Radio_Control.php
 *
 * @since 1.5.0.
 * @since 1.7.0. Converted to content_template().
 */
class MAKE_Customizer_Control_Radio extends WP_Customize_Control {
	/**
	 * The control type.
	 *
	 * @since 1.5.0.
	 * @since 1.7.0. Changed to 'make_radio'
	 *
	 * @var string
	 */
	public $type = 'make_radio';

	/**
	 * The control mode.
	 *
	 * Possible values are 'buttonset', 'image', and 'radio'.
	 *
	 * @since 1.5.0.
	 *
	 * @var string
	 */
	public $mode = 'radio';

	/**
	 * MAKE_Customizer_Control_Radio constructor.
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
		$this->type = 'make_radio';
	}

	/**
	 * Enqueue necessary scripts for this control.
	 *
	 * @since 1.5.0.
	 *
	 * @return void
	 */
	public function enqueue() {
		if ( 'buttonset' === $this->mode || 'image' === $this->mode ) {
			wp_enqueue_script( 'jquery-ui-button' );
		}
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
		$json['mode'] = $this->mode;
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

		<div id="input_{{ data.id }}" class="make-radio-container<# if (0 <= ['buttonset', 'image'].indexOf( data.mode )) { #> make-radio-{{ data.mode }}-container<# } #>">
			<# if ('buttonset' === data.mode) { #>
				<# for (key in data.choices) { #>
					<input id="{{ data.id }}{{ key }}" name="_customize-radio-{{ data.id }}" type="radio" value="{{ key }}" {{{ data.link }}}<# if (key === data.value) { #> checked="checked" <# } #> />
					<label for="{{ data.id }}{{ key }}">{{ data.choices[ key ] }}</label>
				<# } #>
			<# } else if ('image' === data.mode) { #>
				<# for (key in data.choices) { #>
					<input id="{{ data.id }}{{ key }}" name="_customize-radio-{{ data.id }}" class="image-select" type="radio" value="{{ key }}" {{{ data.link }}}<# if (key === data.value) { #> checked="checked" <# } #> />
					<label for="{{ data.id }}{{ key }}"><img src="{{ data.choices[ key ] }}" alt="{{ key }}" /></label>
				<# } #>
			<# } else { #>
				<# for (key in data.choices) { #>
					<label for="{{ data.id }}{{ key }}" class="customizer-radio">
						<input id="{{ data.id }}{{ key }}" name="_customize-radio-{{ data.id }}" type="radio" value="{{ key }}" {{{ data.link }}}<# if (key === data.value) { #> checked="checked" <# } #> />
						{{ data.choices[ key ] }}<br />
					</label>
				<# } #>
			<# } #>
		</div>
	<?php
	}
}