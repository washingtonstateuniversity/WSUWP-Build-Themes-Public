<?php
/**
 * @package Make
 */

/**
 * Class MAKE_Customizer_Control_Html
 *
 * JS-based control for adding arbitrary HTML to Customizer sections. Is the successor to TTFMAKE_Customize_Misc_Control
 *
 * @since 1.7.0.
 */
class MAKE_Customizer_Control_Html extends WP_Customize_Control {
	/**
	 * The current setting name.
	 *
	 * Starting in WP 4.5, a control with no associated settings should pass an empty array for this property.
	 *
	 * @link https://core.trac.wordpress.org/ticket/35926
	 *
	 * @since 1.7.0.
	 *
	 * @var   string|array    The current setting name.
	 */
	public $settings = array();

	/**
	 * The control type.
	 *
	 * @since 1.7.0.
	 *
	 * @var string
	 */
	public $type = 'make_html';

	/**
	 * The HTML to display with the control.
	 *
	 * @since 1.7.0.
	 *
	 * @var string
	 */
	public $html = '';

	/**
	 * MAKE_Customizer_Control_Html constructor.
	 *
	 * @since 1.7.0.
	 *
	 * @param WP_Customize_Manager $manager
	 * @param string               $id
	 * @param array                $args
	 */
	public function __construct( WP_Customize_Manager $manager, $id, array $args ) {
		// Add a dummy setting for the control
		// This is no longer needed in WP 4.5, which is also when the get_previewable_devices() method was added.
		// TODO remove this when 4.4 support is dropped.
		if ( ! method_exists( $manager, 'get_previewable_devices' ) ) {
			$this->settings = 'make-customize-control-html';
		}
		
		parent::__construct( $manager, $id, $args );

		// Ensure this instance maintains the proper type value.
		$this->type = 'make_html';
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

		$json['html'] = $this->html;

		return $json;
	}

	/**
	 * Define the JS template for the control.
	 *
	 * @since 1.7.0.
	 *
	 * @return void
	 */
	protected function content_template() { ?>
		<# if (data.label) { #>
			<span class="customize-control-title">{{ data.label }}</span>
		<# } #>
		<# if (data.description) { #>
			<span class="description customize-control-description">{{{ data.description }}}</span>
		<# } #>
		<# if (data.html) { #>
			<div class="make-html-container">
				{{{ data.html }}}
			</div>
		<# } #>
	<?php }
}