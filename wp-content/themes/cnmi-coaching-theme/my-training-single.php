<?php
/*
 * Template Name: My Training
 */
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

function show_my_training() {
	$progressID = $_GET['training'];
	if($progressID) {
		$user_id = get_current_user_id();
		$memberships = wc_memberships_get_user_active_memberships( $user_id );
		if($memberships){
			$plan_id = $memberships[0]->{"plan_id"};
			if ($plan_id == 406 || $plan_id == 407) {
				// certified coach in training and certified coach
				include(locate_template('partials/training-singles/coach-in-training.php'));
			} elseif ($plan_id == 411) {
				// certified coach trainer
				include(locate_template('partials/training-singles/coach-trainer.php'));
			} elseif ($plan_id == 408 || $plan_id == 410) {
				// contracing org or licensing org
				include(locate_template('partials/training-singles/contracting-org.php'));
			}
		}
	} else {
		?>
			<p>Sorry, page not found.</p>
		<?php
	}
}
add_action('genesis_entry_content', 'show_my_training');

genesis();
