<?php
$user_id = get_current_user_id();
$memberships = wc_memberships_get_user_active_memberships( $user_id );	
if($memberships){
	$plan_id = $memberships[0]->{"plan_id"};
	if ($plan_id == 406 || $plan_id == 407) {
		// certified coach in training and certified coach
		include(locate_template('partials/elements/certification-lists/certified-coach-in-training.php'));
	} elseif ($plan_id == 411) {
		// certified coach trainer
		// we want trainings listed or unique certifications based on the page
		$template = get_page_template_slug();
		if($template === 'my-trainings.php') {
			include(locate_template('partials/elements/certification-lists/certified-coach-trainer.php'));
		} else if ($template === 'my-certifications.php') {
			include(locate_template('partials/elements/certification-lists/unique-certifications-coach-trainer.php'));
		} else if ($template === 'my-resources.php') {
			include(locate_template('partials/elements/certification-lists/unique-certifications-with-resources-coach-trainer.php'));
		}
	} elseif ($plan_id == 408) {
		// contracting org
		$template = get_page_template_slug();
		if($template === 'my-resources.php') {
			include(locate_template('partials/elements/certification-lists/unique-certifications-with-resources-contracting-org.php'));
		} else {
			include(locate_template('partials/elements/certification-lists/contracting-org.php'));
		}
	} elseif ($plan_id == 410) {
		// licensing org
		$template = get_page_template_slug();
		if($template === 'my-resources.php') {
			include(locate_template('partials/elements/certification-lists/unique-certifications-with-resources-licensing-org.php'));
		} else {
			include(locate_template('partials/elements/certification-lists/licensing-org.php'));
		}
	}
}
