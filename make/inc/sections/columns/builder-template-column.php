<?php

global $ttfmake_section_data;

$section_name   = 'ttfmake-section[{{ data.get("parentID") }}][columns][{{ data.get("id") }}]';
$combined_id = "{{ data.get('parentID') }}-{{ data.get('id') }}";
$overlay_id  = "ttfmake-overlay-" . $combined_id;

?>
<?php
	$column_name = $section_name . '[columns][{{ data.get("id") }}]';
	$iframe_id = 'ttfmake-iframe-'. $combined_id;
	$textarea_id = 'ttfmake-content-'. $combined_id;
	$content     = '{{ data.get("content") }}';

	$column_buttons = array(
		100 => array(
			'label'              => __( 'Edit content', 'make' ),
			'href'               => '#',
			'class'              => "edit-text-column-link edit-content-link ttfmake-icon-pencil {{ (data.get('content')) ? 'item-has-content' : '' }}",
			'title'              => __( 'Edit content', 'make' )
		),
		600 => array(
			'label'              => __( 'Trash column', 'make' ),
			'href'               => '#',
			'class'              => 'ttfmake-text-column-remove ttfmake-icon-trash',
			'title'              => __( 'Trash column', 'make' )
		)
	);

	/**
	 * Filter the buttons added to a text column.
	 *
	 * @since 1.4.0.
	 * @since 1.8.8. Changed second argument from $ttfmake_section_data to item type, i.e. 'column'.
	 *
	 * @param array    $column_buttons          The current list of buttons.
	 * @param string   $item_type 			    Item type, in this case 'column'.
	 */
	$column_buttons = apply_filters( 'make_column_buttons', $column_buttons, 'column' );
	ksort( $column_buttons );

	/**
	 * Filter the classes applied to each column in a Columns section.
	 *
	 * @since 1.2.0.
	 *
	 * @param string    $column_classes          The classes for the column.
	 * @param int       $i                       The column number.
	 * @param array     $ttfmake_section_data    The array of data for the section.
	 */
	$column_classes = apply_filters( 'ttfmake-text-column-classes', 'ttfmake-text-column', $ttfmake_section_data );
?>

<div class="ttfmake-text-column{{ (data.get('size')) ? ' ttfmake-column-width-'+data.get('size') : '' }}" data-id="{{ data.get('id') }}">
	<div title="<?php esc_attr_e( 'Drag-and-drop this column into place', 'make' ); ?>" class="ttfmake-sortable-handle">
		<div class="sortable-background column-sortable-background"></div>

		<a href="#" class="ttfmake-configure-item-button" title="Configure column">
			<span>Configure options</span>
		</a>
	</div>

	<?php
	/**
	 * Execute code before an individual text column is displayed.
	 *
	 * @since 1.2.3.
	 *
	 * @param array    $ttfmake_section_data    The data for the section.
	 */
	do_action( 'make_section_text_before_column', $ttfmake_section_data );
	?>

	<ul class="configure-item-dropdown">
		<?php foreach ( $column_buttons as $button ) : ?>
			<li>
				<a href="<?php echo esc_url( $button['href'] ); ?>" class="column-buttons <?php echo $button['class']; ?>" title="<?php echo $button['title']; ?>">
					<?php echo $button['label']; ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>

	<?php ttfmake_get_builder_base()->add_frame( '', 'content', '', $content ); ?>

	<?php
	/**
	 * Execute code after an individual text column is displayed.
	 *
	 * @since 1.2.3.
	 *
	 * @param array    $ttfmake_section_data    The data for the section.
	 */
	do_action( 'make_section_text_after_column', $ttfmake_section_data );
	?>
</div>