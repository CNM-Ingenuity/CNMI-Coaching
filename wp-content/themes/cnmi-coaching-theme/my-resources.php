<?php
/*
 * Template Name: My Resources
 */
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

function show_my_resources() {
	$breadcrumbs = [
		"Resources" => "/my-resources"
	];
	include(locate_template('partials/elements/breadcrumbs.php'));	
	get_template_part('partials/elements/top-matter');	
	$linkAddress = '/my-resource?resource=';
	include(locate_template('partials/elements/certification-list.php'));	
}
add_action('genesis_entry_content', 'show_my_resources');

genesis();