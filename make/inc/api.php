<?php
/**
 * @package Make
 */

/**
 * Class MAKE_API
 *
 * Class to manage and provide access to all of the modules that make up the Make API.
 *
 * Access this class via the global Make() function.
 *
 * @since 1.7.0.
 */
final class MAKE_API extends MAKE_Util_Modules implements MAKE_APIInterface {
	/**
	 * An associative array of required modules.
	 *
	 * @since 1.7.0.
	 *
	 * @var array
	 */
	protected $dependencies = array(
		'l10n'                => 'MAKE_Setup_L10nInterface',
		'notice'              => 'MAKE_Admin_NoticeInterface',
		'error'               => 'MAKE_Error_CollectorInterface',
		'compatibility'       => 'MAKE_Compatibility_MethodsInterface',
		'plus'                => 'MAKE_Plus_MethodsInterface',
		'choices'             => 'MAKE_Choices_ManagerInterface',
		'font'                => 'MAKE_Font_ManagerInterface',
		'view'                => 'MAKE_Layout_ViewInterface',
		'thememod'            => 'MAKE_Settings_ThemeModInterface',
		'sanitize'            => 'MAKE_Settings_SanitizeInterface',
		'widgets'             => 'MAKE_Setup_WidgetsInterface',
		'scripts'             => 'MAKE_Setup_ScriptsInterface',
		'style'               => 'MAKE_Style_ManagerInterface',
		'builder'             => 'MAKE_Builder_SetupInterface',
		'formatting'          => 'MAKE_Formatting_ManagerInterface',
		'galleryslider'       => 'MAKE_GallerySlider_SetupInterface',
		'logo'                => 'MAKE_Logo_MethodsInterface',
		'socialicons'         => 'MAKE_SocialIcons_ManagerInterface',
		'customizer_controls' => 'MAKE_Customizer_ControlsInterface',
		'customizer_preview'  => 'MAKE_Customizer_PreviewInterface',
		'integration'         => 'MAKE_Integration_ManagerInterface',
		'setup'               => 'MAKE_Setup_MiscInterface',
		'head'                => 'MAKE_Setup_HeadInterface',
		'sections'			  => 'MAKE_Sections_SetupInterface'
	);

	/**
	 * An associative array of the default classes to use for each dependency.
	 *
	 * @since 1.7.0.
	 *
	 * @var array
	 */
	private $defaults = array(
		'l10n'                => 'MAKE_Setup_L10n',
		'notice'              => 'MAKE_Admin_Notice',
		'error'               => 'MAKE_Error_Collector',
		'compatibility'       => 'MAKE_Compatibility_Methods',
		'plus'                => 'MAKE_Plus_Methods',
		'choices'             => 'MAKE_Choices_Manager',
		'font'                => 'MAKE_Font_Manager',
		'view'                => 'MAKE_Layout_View',
		'thememod'            => 'MAKE_Settings_ThemeMod',
		'sanitize'            => 'MAKE_Settings_Sanitize',
		'widgets'             => 'MAKE_Setup_Widgets',
		'scripts'             => 'MAKE_Setup_Scripts',
		'style'               => 'MAKE_Style_Manager',
		'builder'             => 'MAKE_Builder_Setup',
		'formatting'          => 'MAKE_Formatting_Manager',
		'galleryslider'       => 'MAKE_GallerySlider_Setup',
		'logo'                => 'MAKE_Logo_Methods',
		'socialicons'         => 'MAKE_SocialIcons_Manager',
		'customizer_controls' => 'MAKE_Customizer_Controls',
		'customizer_preview'  => 'MAKE_Customizer_Preview',
		'integration'         => 'MAKE_Integration_Manager',
		'setup'               => 'MAKE_Setup_Misc',
		'head'                => 'MAKE_Setup_Head',
		'sections'			  => 'MAKE_Sections_Setup'
	);

	/**
	 * MAKE_API constructor.
	 *
	 * @since 1.7.0.
	 *
	 * @param array $modules
	 */
	public function __construct( array $modules = array() ) {
		$modules = wp_parse_args( $modules, $this->get_default_modules() );

		// Remove conditional dependencies
		if ( ! is_admin() ) {
			unset( $this->dependencies['notice'] );

			if ( ! is_customize_preview() ) {
				unset( $this->dependencies['customizer_controls'] );
				unset( $this->dependencies['customizer_preview'] );
			}
		}

		parent::__construct( $this, $modules );
	}

	/**
	 * Getter for the private defaults array.
	 *
	 * @since 1.7.0.
	 *
	 * @return array
	 */
	private function get_default_modules() {
		return $this->defaults;
	}

	/**
	 * Return the specified module without running its load routine.
	 *
	 * @since 1.7.0.
	 *
	 * @param string $module_name
	 *
	 * @return null
	 */
	public function inject_module( $module_name ) {
		// Module exists.
		if ( $this->has_module( $module_name ) ) {
			return $this->modules[ $module_name ];
		}

		// Module doesn't exist. Use the get_module method to generate an error.
		else {
			return $this->get_module( $module_name );
		}
	}
}
