<?php
/*
 * Template Name: Register Trainer
 */
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

//* Add custom body class to the head
add_filter( 'body_class', 'form_body_class' );
function form_body_class( $classes ) {
	
	$classes[] = 'progress-form-page';
	return $classes;
	
}

function register_trainer() {
	$breadcrumbs = [
		"My Organization's Trainings" => "/my-trainings",
		"Register Trainer" => "#"
	];
	include(locate_template('partials/elements/breadcrumbs.php'));
	get_template_part('partials/elements/top-matter');
	$user_id = get_current_user_id();
	$memberships = wc_memberships_get_user_active_memberships( $user_id );
	$plan_id = $memberships[0]->{"plan_id"};
	if ($plan_id == 410) {
		get_template_part('partials/forms/register-trainer');
	}

}
add_action('genesis_entry_content', 'register_trainer');

genesis();
