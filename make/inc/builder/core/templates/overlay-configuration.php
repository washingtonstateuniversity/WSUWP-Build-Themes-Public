<script type="text/html" id="tmpl-ttfmake-overlay-configuration">
<?php get_template_part( '/inc/builder/core/templates/overlay', 'header' ); ?>
<?php get_template_part( '/inc/builder/core/templates/overlay', 'footer' ); ?>
</div>
</script>

<script type="text/html" id="tmpl-ttfmake-settings-divider">
<span data-name="{{ data.name }}" class="{{ data.class }}">{{ data.label }}</span>
</script>

<script type="text/html" id="tmpl-ttfmake-settings-section_title">
<input placeholder="{{ data.label }}" type="text" value="" class="{{ data.class }}" autocomplete="off"">
</script>

<script type="text/html" id="tmpl-ttfmake-settings-select">
<label>{{ data.label }}</label>
<select class="{{ data.class }}" {{ data.disabled ? 'disabled' : '' }}>
	<# for( var o in data.options ) { #>
	<option value="{{ o }}">{{ data.options[o] }}</option>
	<# } #>
</select>
<# if ( data.description ) { #>
<div class="ttfmake-configuration-description">{{ data.description }}</div>
<# } #>
</script>

<script type="text/html" id="tmpl-ttfmake-settings-checkbox">
<label>{{ data.label }}</label>
<input type="checkbox" value="1" class="{{ data.class }}"<# if( data.disabled ) { #>{{ disabled="disabled" }}<# } #>>
<# if ( data.description ) { #>
<div class="ttfmake-configuration-description">{{{ data.description }}}</div>
<# } #>
</script>

<script type="text/html" id="tmpl-ttfmake-settings-text">
<label>{{ data.label }}</label>
<input type="text" value="" class="{{ data.class }}">
<# if ( data.description ) { #>
<div class="ttfmake-configuration-description">{{ data.description }}</div>
<# } #>
</script>

<script type="text/html" id="tmpl-ttfmake-settings-image">
<label>{{ data.label }}</label>
<div class="ttfmake-uploader">
	<div data-title="Set image" class="ttfmake-media-uploader-placeholder ttfmake-media-uploader-add {{ data.class }}"></div>
</div>
<# if ( data.description ) { #>
<div class="ttfmake-configuration-description">{{ data.description }}</div>
<# } #>
</script>

<script type="text/html" id="tmpl-ttfmake-settings-color">
<label>{{ data.label }}</label>
<input type="text" class="ttfmake-text-background-color ttfmake-configuration-color-picker {{ data.class }}" value="">
<# if ( data.description ) { #>
<div class="ttfmake-configuration-description">{{ data.description }}</div>
<# } #>
</script>

<script type="text/html" id="tmpl-ttfmake-settings-description">
<# if ( data.description ) { #>
<div class="ttfmake-configuration-description" style="margin-top: 0;">{{{ data.description }}}</div>
<# } #>
</script>

<script type="text/html" id="tmpl-ttfmake-media-frame-remove-image">
<div class="ttfmake-remove-current-image">
	<h3><?php esc_html_e( 'Current image', 'make' ); ?></h3>
	<a href="#" class="ttfmake-media-frame-remove-image">
		<?php esc_html_e( 'Remove Current Image', 'make' ); ?>
	</a>
</div>
</script>