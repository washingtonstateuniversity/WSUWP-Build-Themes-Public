<?php
/**
 * @package Make
 */

/**
 * Class MAKE_Customizer_Control_SocialIcons
 *
 * Special control for managing social profile icons.
 *
 * @since 1.7.0.
 */
class MAKE_Customizer_Control_SocialIcons extends WP_Customize_Control {
	/**
	 * The control type.
	 *
	 * @since 1.7.0.
	 *
	 * @var string
	 */
	public $type = 'make_socialicons';

	/**
	 * Enqueue necessary scripts for this control.
	 *
	 * @since 1.7.0.
	 *
	 * @return void
	 */
	public function enqueue() {
		wp_enqueue_style( 'font-awesome' );
		wp_enqueue_script( 'jquery-ui-sortable' );
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
		$json['link'] = $this->get_link();
		$json['value'] = wp_json_encode( $this->value() );
		foreach ( $this->value() as $key => $value ) {
			$json['socialicons'][ $key ] = $value;
		}

		return $json;
	}

	/**
	 * Override the parent class's method.
	 *
	 * Without this, the parent class method will be called when the Customizer loads and generate a standard input
     * element for this control which it then tries to populate the value attribute with the setting's value, which
     * is an array. This throws a PHP notice, which breaks the Customizer if xdebug is turned on.
	 *
	 * Since this control is rendered entirely with JavaScript, this method is not technically necessary anyway.
     *
     * @since 1.7.0.
	 *
	 * @return void
	 */
	protected function render_content() {}

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

		<div class="make-socialicons-container">
			<div class="make-socialicons-stage">
				<# _.each(data.socialicons.items, function(item) { #>
					{{{ data.itemTemplate(item) }}}
				<# }) #>
			</div>
			<div class="make-socialicons-buttons">
				<a id="list-icons_{{ data.id }}" href="#"><?php esc_html_e( 'See Available Icons', 'make' ); ?></a>
				<button id="add-icon_{{ data.id }}" class="button-secondary"><?php esc_html_e( 'Add Icon', 'make' ); ?></button>
			</div>
			<div class="make-socialicons-options">
				<label for="email-toggle_{{ data.id }}">
					<input id="email-toggle_{{ data.id }}" data-name="email-toggle" type="checkbox"<# if (true == data.socialicons['email-toggle']) { #> checked="checked" <# } #> />
					<?php esc_html_e( 'Include email icon', 'make' ); ?>
				</label>
				<label for="rss-toggle_{{ data.id }}">
					<input id="rss-toggle_{{ data.id }}" data-name="rss-toggle" type="checkbox"<# if (true == data.socialicons['rss-toggle']) { #> checked="checked" <# } #> />
					<?php esc_html_e( 'Include RSS feed icon', 'make' ); ?>
				</label>
				<span id="rss-help_{{ data.id }}" class="description customize-control-description"><?php esc_html_e( 'Leave the &ldquo;Custom RSS feed URL&rdquo; field blank to use the default RSS feed URL.', 'make' ); ?></span>
				<label for="new-window_{{ data.id }}">
					<input id="new-window_{{ data.id }}" data-name="new-window" type="checkbox"<# if (true == data.socialicons['new-window']) { #> checked="checked" <# } #> />
					<?php esc_html_e( 'Open icon links in a new tab', 'make' ); ?>
				</label>
			</div>
			<input id="input_{{ data.id }}" class="make-socialicons-value" type="hidden" value="{{ data.value }}" {{{ data.link }}} />
		</div>
	<?php
	}

	/**
     * Define the item sub-template for the control.
     *
     * @since 1.7.0.
	 *
	 * @return void
     */
	protected function item_template() {
		?>
		<#
			var type    = ('undefined' === typeof data.type)    ? 'link' : data.type,
			    content = ('undefined' === typeof data.content) ? ''     : data.content;
		#>
		<div class="make-socialicons-item make-socialicons-item-{{ data.type }}" data-type="{{ data.type }}">
			<div class="make-socialicons-item-handle">
				<i></i>
			</div>
			<# if ('email' === type) { #>
				<label class="screen-reader-text"><?php esc_html_e( 'Email address', 'make' ); ?></label>
				<input class="make-socialicons-item-content" type="email" placeholder="<?php esc_html_e( 'Email address', 'make' ) ?>" value="{{ data.content }}" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" />
			<# } else if ('rss' === type) { #>
				<label class="screen-reader-text"><?php esc_html_e( 'Custom RSS feed URL', 'make' ); ?></label>
				<input class="make-socialicons-item-content" type="url" placeholder="<?php esc_html_e( 'Custom RSS feed URL', 'make' ) ?>" value="{{ data.content }}" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" />
			<# } else { #>
				<label class="screen-reader-text"><?php esc_html_e( 'Social profile URL', 'make' ); ?></label>
				<input class="make-socialicons-item-content" type="url" placeholder="http://" value="{{ data.content }}" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" />
				<button class="make-socialicons-item-remove">
					<span class="screen-reader-text"><?php esc_html_e( 'Remove', 'make' ); ?></span>
					<span class="make-socialicons-item-remove-icon"></span>
				</button>
			<# } #>
		</div>
	<?php
	}

	/**
	 * Define the list sub-template for the control.
	 *
	 * @since 1.7.0.
	 *
	 * @return void
	 */
	protected function list_template() {
		?>
		<div id="make-socialicons-list-wrapper">
			<div id="make-socialicons-list">
				<div class="make-socialicons-overlay-window-head-wrapper">
					<div class="make-socialicons-overlay-window-head">
						<?php esc_html_e( 'Available Social Icons', 'make' ); ?>
						<button id="make-socialicons-list-close">
							<span class="screen-reader-text"><?php esc_html_e( 'Close', 'make' ); ?></span>
							<span class="make-socialicons-list-close-icon"></span>
						</button>
					</div>
				</div>
				<ul>
				<# for (pattern in data) { #>
					<# var classes = data[pattern].class.join(' '); #>
					<li>
						<i class="{{ classes }}"></i>
						<span class="make-socialicons-list-item-title">{{ data[pattern].title }}</span>
					</li>
				<# } #>
				</ul>
			</div>
		</div>
	<?php
	}

	/**
	 * Print additional JS templates.
	 *
	 * @since 1.7.0.
	 *
	 * @return void
	 */
	public function print_sub_templates() {
		?>
		<script type="text/html" id="tmpl-customize-control-<?php echo $this->type; ?>-item">
			<?php $this->item_template(); ?>
		</script>
		<script type="text/html" id="tmpl-customize-control-<?php echo $this->type; ?>-list">
			<?php $this->list_template(); ?>
		</script>
		<?php
	}
}
