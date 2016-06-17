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

		<div id="input_{{ data.id }}" class="make-backgroundposition-container">
		<# var i = 1;
		for ( key in data.choices ) { #>
			<input id="{{ data.id }}{{ key }}" name="_customize-radio-{{ data.id }}" type="radio" value="{{ key }}" {{{ data.link }}}<# if (key === data.value) { #> checked="checked" <# } #> />
			<label for="{{ data.id }}{{ key }}" class="choice-{{ i }}" data-label="{{ data.choices[ key ] }}"></label>
		<# i++;
		} #>
		</div>
		<div class="background-position-caption">
			<# if (data.value) { #>
				{{ data.choices[ data.value ] }}
			<# } #>
		</div>
	<?php
	}
}