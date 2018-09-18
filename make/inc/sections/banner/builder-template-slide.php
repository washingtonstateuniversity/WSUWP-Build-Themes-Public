<?php
/**
 * @package Make
 */

$section_name = "ttfmake-section[{{ data.get('parentID') }}][banner-slides][{{ data.get('id') }}]";
$combined_id = "{{ data.get('parentID') }}-{{ id }}";
$overlay_id  = "ttfmake-overlay-" . $combined_id;
?>

<div class="ttfmake-banner-slide" id="ttfmake-banner-slide-{{ data.get('parentID') }}" data-id="{{ data.get('id') }}" data-section-type="banner-slide">

	<div title="<?php esc_attr_e( 'Drag-and-drop this slide into place', 'make' ); ?>" class="ttfmake-sortable-handle">
		<div class="sortable-background"></div>

		<a href="#" class="ttfmake-configure-item-button" title="Configure banner">
			<span>Configure options</span>
		</a>
	</div>

	<?php
	$configuration_buttons = array(
		100 => array(
			'label'              => __( 'Edit content', 'make' ),
			'href'               => '#',
			'class'              => 'edit-content-link ttfmake-icon-pencil {{ (data.get("content") && data.get("content").length) ? "item-has-content" : "" }}',
			'title'              => __( 'Edit content', 'make' ),
		),
		200 => array(
			'label'				 => __( 'Configure slide', 'make' ),
			'href'				 => '#',
			'class'				 => 'ttfmake-icon-cog ttfmake-banner-slide-configure ttfmake-overlay-open',
			'title'				 => __( 'Configure slide', 'make' ),
		),
		1000 => array(
			'label'              => __( 'Trash slide', 'make' ),
			'href'               => '#',
			'class'              => 'ttfmake-icon-trash ttfmake-banner-slide-remove',
			'title'              => __( 'Trash slide', 'make' )
		)
	);

	$configuration_buttons = apply_filters( 'make_banner_slide_buttons', $configuration_buttons, 'slide' );
	ksort( $configuration_buttons );
	?>

	<ul class="configure-item-dropdown">
		<?php foreach( $configuration_buttons as $button ) : ?>
			<li>
				<a href="<?php echo esc_url( $button['href'] ); ?>" class="<?php echo $button['class']; ?>" title="<?php echo $button['title']; ?>">
					<?php echo esc_html( $button['label'] ); ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>

	<?php echo ttfmake_get_builder_base()->add_uploader( $section_name, 0, __( 'Set banner image', 'make' ), 'background-image-url' ); ?>
	<?php ttfmake_get_builder_base()->add_frame( '', 'content', '', '', false ); ?>
</div>
