<?php
/*
 * Template Name: My Certifications
 */
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

function show_my_certifications() {
	$breadcrumbs = [
		"My Certifications" => "/my-certifications"
	];
	include(locate_template('partials/elements/breadcrumbs.php'));	
	get_template_part('partials/elements/top-matter');	
	$linkAddress = '/my-certification?certification=';
	include(locate_template('partials/elements/certification-list.php'));	
}
add_action('genesis_entry_content', 'show_my_certifications');

genesis();