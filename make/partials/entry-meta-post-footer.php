<?php
/**
 * @package Make
 */
global $make_current_location;
$make_current_location = 'post-footer';
get_template_part( 'partials/entry', 'meta' );
unset( $make_current_location );