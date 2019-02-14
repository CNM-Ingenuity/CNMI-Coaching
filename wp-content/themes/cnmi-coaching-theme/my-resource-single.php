<?php
/*
 * Template Name: My Resource
 */
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

function show_my_resource() {
	$progressID = $_GET['resource'];
	if($progressID) {
		$user_id = get_current_user_id();
		$memberships = wc_memberships_get_user_active_memberships( $user_id );		
		if($memberships){
			$plan_id = $memberships[0]->{"plan_id"};
			if ($plan_id == 407) {
				// certified coach in training
				include(locate_template('partials/resource-single/coach-in-training.php'));
			} elseif ($plan_id == 411 || $plan_id == 408) {
				// certified coach trainer
				include(locate_template('partials/resource-single/coach-trainer.php'));
			}
		}
} else {
?>
	<p>Sorry, page not found.</p>
<?php
}
}
add_action('genesis_entry_content', 'show_my_resource');

genesis();
