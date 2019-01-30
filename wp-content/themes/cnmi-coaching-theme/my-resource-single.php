<?php
/*
 * Template Name: My Resource
 */
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

function show_my_training() {
	$progressID = $_GET['resource'];
	if($progressID) {
		$certification = CNMI_Progress::get_progress_by_id($progressID, false);
		$eventID = $certification->event_id;
		$eventType = CNMI_Events::get_event_type($eventID);
		$eventResourcesArray = CNMI_Certifications::get_certification_by_event($eventID);
		$breadcrumbs = [
			"Resources" => "/my-resources",
			$eventType => "#",
		];
		include(locate_template('partials/elements/breadcrumbs.php'));
		include(locate_template('partials/elements/top-matter.php'));
		$eventStartDate = CNMI_Events::get_event_start_date($eventID);
		if($eventStartDate) {
			$eventStartDate = $eventStartDate->format('m/d/Y');
		}
		$eventTrainer = CNMI_Events::get_event_trainer($eventID);

		?>
			<div class="item">
				<h3 class="title"><?php echo $eventType; ?></h3>
				<p class="students">Instructor: <?php echo $eventTrainer; ?></p>
				<p class="date">Date: <?php echo $eventStartDate; ?></p>
			</div>
		<?php
		foreach ($eventResourcesArray as $event) {
			var_dump($event);


		}

	} else {
		?>
			<p>Sorry, page not found.</p>
		<?php
	}
}
add_action('genesis_entry_content', 'show_my_training');

genesis();
