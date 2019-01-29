<?php
/*
 * Template Name: My Resource
 */
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

function show_my_training() {
	get_template_part('partials/elements/top-matter');	
	$progressID = $_GET['resource'];
	if($progressID) {
		$certification = CNMI_Progress::get_progress_by_id($progressID, false);
		$eventID = $certification->event_id;
		$eventType = CNMI_Events::get_event_type($eventID);
		$eventStartDate = CNMI_Events::get_event_start_date($eventID);
		if($eventStartDate) {
			$eventStartDate = $eventStartDate->format('m/d/Y');
		}
		$eventTrainer = CNMI_Events::get_event_trainer($eventID);
		var_dump($certification);
	} else {
		?>
			<p>Sorry, page not found.</p>
		<?php
	}
}
add_action('genesis_entry_content', 'show_my_training');

genesis();