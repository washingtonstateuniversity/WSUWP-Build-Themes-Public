<?php
/**
 * @package Make
 */

/**
 * Class MAKE_Customizer_Control_Select
 *
 * @since 1.8.4.
 */
class MAKE_Customizer_Control_Select extends WP_Customize_Control {
	/**
	 * The control type.
	 *
	 * @since 1.8.4.
	 *
	 * @var string
	 */
	public $type = 'make_select';

	/**
	 * MAKE_Customizer_Control_Select constructor.
	 *
	 * @since 1.8.4.
	 *
	 * @param WP_Customize_Manager $manager
	 * @param string               $id
	 * @param array                $args
	 */
	public function __construct( WP_Customize_Manager $manager, $id, array $args ) {
		parent::__construct( $manager, $id, $args );

		// Ensure this instance maintains the proper type value.
		$this->type = 'make_select';
	}

	/**
	 * Add extra properties to JSON array.
	 *
	 * @since 1.8.4.
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
		<div id="input_{{ data.id }}" class="make-select-container">
			<select id="{{ data.id }}" name="_customize-select-{{ data.id }}" {{{ data.link }}}>
			<# for (key in data.choices) { #>
				<option value="{{ key }}"<# if (key === data.value) { #> selected="selected" <# } #>>{{ data.choices[key] }}</option>
			<# } #>
			</select>
		</div>
	<?php
	}
}