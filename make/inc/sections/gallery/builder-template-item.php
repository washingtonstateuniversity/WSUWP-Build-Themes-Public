<?php
/**
 * @package Make
 */

global $ttfmake_section_data, $ttfmake_gallery_id;

$section_name = "ttfmake-section[{{ data.get('parentID') }}][gallery-items][{{ data.get('id') }}]";
$combined_id = "{{ data.get('parentID') }}-{{ id }}";
$overlay_id  = "ttfmake-overlay-" . $combined_id;
?>

<div class="ttfmake-gallery-item" data-id="{{ data.get('id') }}" data-section-type="gallery-item">
	<div title="<?php esc_attr_e( 'Drag-and-drop this item into place', 'make' ); ?>" class="ttfmake-sortable-handle">
		<div class="sortable-background"></div>

		<a href="#" class="ttfmake-configure-item-button" title="Configure item">
			<span>Configure options</span>
		</a>
	</div>

	<?php
	$configuration_buttons = array(
		100 => array(
			'label'              => __( 'Edit content', 'make' ),
			'href'               => '#',
			'class'              => 'edit-content-link ttfmake-icon-pencil {{ (data.get("content")) ? "item-has-content" : "" }}',
			'title'              => __( 'Edit content', 'make' ),
		),
		200 => array(
			'label'				 => __( 'Configure item', 'make' ),
			'href'				 => '#',
			'class'				 => 'ttfmake-icon-cog ttfmake-overlay-open ttfmake-gallery-item-configure',
			'title'				 => __( 'Configure item', 'make' ),
		),
		1000 => array(
			'label'              => __( 'Trash item', 'make' ),
			'href'               => '#',
			'class'              => 'ttfmake-icon-trash ttfmake-gallery-item-remove',
			'title'              => __( 'Trash item', 'make' )
		)
	);

	$configuration_buttons = apply_filters( 'make_gallery_item_buttons', $configuration_buttons, 'item' );
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

	<?php echo ttfmake_get_builder_base()->add_uploader( '', 0, __( 'Set gallery image', 'make' ), 'background-image-url' ); ?>
	<?php ttfmake_get_builder_base()->add_frame( '', 'description', '', '', false ); ?>
</div>
