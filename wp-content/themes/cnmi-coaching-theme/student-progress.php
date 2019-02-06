<?php
/*
 * Template Name: Student Progress
 */
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

//* Add custom body class to the head
add_filter( 'body_class', 'form_body_class' );
function form_body_class( $classes ) {
	
	$classes[] = 'progress-form-page';
	return $classes;
	
}

function show_progress() {
	$progressID = $_GET['progress'];
	if($progressID) {
		$progress = CNMI_Progress::get_progress_by_id($progressID);
		$eventID = $progress->event_id;
		$eventTypeForBreadcrumbs = CNMI_Events::get_event_type($eventID);
		$student = get_user_by('id', $progress->user_id);
		// event type is expected by top-matter
		$eventType = $student->first_name . ' ' . $student->last_name;
		$breadcrumbs = [
			"My Trainings" => "/my-trainings",
			$eventTypeForBreadcrumbs => "/my-training?training=" . $eventID,
			$eventType => '#'
		];
		include(locate_template('partials/elements/breadcrumbs.php'));	
		include(locate_template('partials/elements/top-matter.php'));
		
		
	} else {
		?>
			<p>Sorry, page not found.</p>
		<?php
	}
}
add_action('genesis_entry_content', 'show_progress');

genesis();