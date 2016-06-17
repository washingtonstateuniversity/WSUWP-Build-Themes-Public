<?php
/**
 * @package Make
 */

/**
 * Class MAKE_Customizer_DataHelper
 *
 * Methods to help process Customizer definition data.
 *
 * @since 1.7.0.
 */
final class MAKE_Customizer_DataHelper extends MAKE_Util_Modules implements MAKE_Customizer_DataHelperInterface {
	/**
	 * An associative array of required modules.
	 *
	 * @since 1.7.0.
	 *
	 * @var array
	 */
	protected $dependencies = array(
		'compatibility' => 'MAKE_Compatibility_MethodsInterface',
		'font'          => 'MAKE_Font_ManagerInterface',
		'thememod'      => 'MAKE_Settings_ThemeModInterface',
	);

	/**
	 * Generate a set of typography control definitions for a given element.
	 *
	 * @since 1.7.0.
	 *
	 * @param string $element
	 * @param string $label
	 * @param string $description
	 *
	 * @return array
	 */
	public function get_typography_group_definitions( $element, $label, $description = '' ) {
		// Check for deprecated filter
		foreach ( array( 'make_customizer_typography_group_definitions' ) as $filter ) {
			if ( has_filter( $filter ) ) {
				$this->compatibility()->deprecated_hook(
					$filter,
					'1.7.0',
					sprintf(
						esc_html__( 'To add or modify Customizer sections and controls, use the %1$s hook instead, or the core %2$s methods.', 'make' ),
						'<code>make_customizer_sections</code>',
						'<code>$wp_customize</code>'
					)
				);
			}
		}

		$font_value = $this->thememod()->get_value( 'font-family-' . $element );
		$font_choices = $this->font()->get_font_choices( null, false );
		$font_label = isset( $font_choices[ $font_value ] ) ? $font_choices[ $font_value ] : '';

		// Definitions collector
		$definitions = array();

		// Font Family
		if ( $this->thememod()->setting_exists( 'font-family-' . $element ) ) {
			$definitions[ 'font-family-' . $element ] = array(
				'setting' => true,
				'control' => array(
					'label'   => __( 'Font Family', 'make' ),
					'type'    => 'select',
					'choices' => array( $font_value => $font_label ),
				),
			);
		}
		// Font Style
		if ( $this->thememod()->setting_exists( 'font-style-' . $element ) ) {
			$definitions[ 'font-style-' . $element ] = array(
				'setting' => true,
				'control' => array(
					'control_type' => 'MAKE_Customizer_Control_Radio',
					'label'   => __( 'Font Style', 'make' ),
					'mode'  => 'buttonset',
					'choices' => $this->thememod()->get_choice_set( 'font-style-' . $element ),
				),
			);
		}
		// Font Weight
		if ( $this->thememod()->setting_exists( 'font-weight-' . $element ) ) {
			$definitions[ 'font-weight-' . $element ] = array(
				'setting' => true,
				'control' => array(
					'control_type' => 'MAKE_Customizer_Control_Radio',
					'label'   => __( 'Font Weight', 'make' ),
					'mode'  => 'buttonset',
					'choices' => $this->thememod()->get_choice_set( 'font-weight-' . $element ),
				),
			);
		}
		// Font Size
		if ( $this->thememod()->setting_exists( 'font-size-' . $element ) ) {
			$definitions[ 'font-size-' . $element ] = array(
				'setting' => true,
				'control' => array(
					'control_type' => 'MAKE_Customizer_Control_Range',
					'label'   => __( 'Font Size (px)', 'make' ),
					'input_attrs' => array(
						'min'  => 6,
						'max'  => 100,
						'step' => 1,
					),
				),
			);
		}
		// Text Transform
		if ( $this->thememod()->setting_exists( 'text-transform-' . $element ) ) {
			$definitions[ 'text-transform-' . $element ] = array(
				'setting' => true,
				'control' => array(
					'control_type' => 'MAKE_Customizer_Control_Radio',
					'label'   => __( 'Text Transform', 'make' ),
					'mode'  => 'buttonset',
					'choices' => $this->thememod()->get_choice_set( 'text-transform-' . $element ),
				),
			);
		}
		// Line Height
		if ( $this->thememod()->setting_exists( 'line-height-' . $element ) ) {
			$definitions[ 'line-height-' . $element ] = array(
				'setting' => true,
				'control' => array(
					'control_type' => 'MAKE_Customizer_Control_Range',
					'label'   => __( 'Line Height (em)', 'make' ),
					'input_attrs' => array(
						'min'  => 0,
						'max'  => 5,
						'step' => 0.1,
					),
				),
			);
		}
		// Letter Spacing
		if ( $this->thememod()->setting_exists( 'letter-spacing-' . $element ) ) {
			$definitions[ 'letter-spacing-' . $element ] = array(
				'setting' => true,
				'control' => array(
					'control_type' => 'MAKE_Customizer_Control_Range',
					'label'   => __( 'Letter Spacing (px)', 'make' ),
					'input_attrs' => array(
						'min'  => 0,
						'max'  => 10,
						'step' => 0.5,
					),
				),
			);
		}
		// Word Spacing
		if ( $this->thememod()->setting_exists( 'word-spacing-' . $element ) ) {
			$definitions[ 'word-spacing-' . $element ] = array(
				'setting' => true,
				'control' => array(
					'control_type' => 'MAKE_Customizer_Control_Range',
					'label'   => __( 'Word Spacing (px)', 'make' ),
					'input_attrs' => array(
						'min'  => 0,
						'max'  => 20,
						'step' => 1,
					),
				),
			);
		}
		// Link Underline
		if ( $this->thememod()->setting_exists( 'link-underline-' . $element ) ) {
			$definitions[ 'link-underline-' . $element ] = array(
				'setting' => true,
				'control' => array(
					'control_type' => 'MAKE_Customizer_Control_Radio',
					'label'   => __( 'Link Underline', 'make' ),
					'mode'  => 'buttonset',
					'choices' => $this->thememod()->get_choice_set( 'link-underline-' . $element ),
				),
			);
		}
		// Group Title
		if ( ! empty( $definitions ) ) {
			$group_title = '<h4 class="make-group-title">' . esc_html( $label ) . '</h4>';
			if ( $description ) {
				$group_title .= '<span class="description customize-control-description">' . $description . '</span>';
			}

			$definitions = array_merge( array(
				'typography-group-' . $element => array(
					'control' => array(
						'control_type' => 'MAKE_Customizer_Control_Html',
						'html'  => $group_title,
					),
				),
			), $definitions );
		}

		return $definitions;
	}
}