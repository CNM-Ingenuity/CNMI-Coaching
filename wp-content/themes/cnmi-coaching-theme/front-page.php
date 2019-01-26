<?php
/**
 * This file adds the Home Page to the CNMI Theme.
 *
 */
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

// add_action('genesis_after_header', 'test_stuff');
function test_stuff() {
	get_template_part( 'partials/attendance-form' );
}

genesis();