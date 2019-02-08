<?php
/*
 * Template Name: My Trainings
 */
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

function show_my_trainings() {
	$breadcrumbs = [
		"My Trainings" => "/my-trainings"
	];
	include(locate_template('partials/elements/breadcrumbs.php'));
	get_template_part('partials/elements/top-matter');
	$linkAddress = '/my-training?training=';
	$user_id = get_current_user_id();
	$memberships = wc_memberships_get_user_active_memberships( $user_id );
	$plan_id = $memberships[0]->{"plan_id"};
	if ($plan_id == 408) {
		include(locate_template('partials/elements/contracting-org-trainings.php'));
	} else {
		include(locate_template('partials/elements/certification-list.php'));
	}

}
add_action('genesis_entry_content', 'show_my_trainings');

genesis();
