<?php
/*
 * Template Name: Dashboard
 */

remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

function show_appropriate_dashboard_to_user() {
	$user_id = get_current_user_id();
	$memberships = wc_memberships_get_user_active_memberships( $user_id );
	if($memberships){

		$plan_id = $memberships[0]->{"plan_id"};
		if ($plan_id == 406) {
			get_template_part('partials/dashboard/certified-coach-dashboard');
		} elseif ($plan_id == 407) {
			get_template_part('partials/dashboard/coach-in-training-dashboard');
		} elseif ($plan_id == 408) {
			get_template_part('partials/dashboard/contracting-organization-dashboard');
		} elseif ($plan_id == 410) {
			get_template_part('partials/dashboard/licensed-organization-dashboard');
		} elseif ($plan_id == 411) {			
			get_template_part('partials/dashboard/certified-coach-trainer-dashboard');

		}
	}
}
add_action('genesis_entry_content', 'show_appropriate_dashboard_to_user');

genesis();
