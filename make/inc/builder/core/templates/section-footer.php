<?php
/**
 * @package Make
 */
?>

	<textarea name="ttfmake-section-json-{{ data.get('id') }}" class="ttfmake-section-json" style="display: none;">{{ JSON.stringify( data.toJSON() ) }}</textarea>
</div>