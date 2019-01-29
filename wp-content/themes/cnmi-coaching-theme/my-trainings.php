<?php
/*
 * Template Name: My Trainings
 */
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

function show_my_trainings() {
	get_template_part('partials/elements/top-matter');	
	$linkAddress = '/my-training?training=';
	include(locate_template('partials/elements/certification-list.php'));	
}
add_action('genesis_entry_content', 'show_my_trainings');

genesis();