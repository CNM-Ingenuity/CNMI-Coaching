<?php
/*
 * Template Name: Attendance
 */
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

//* Add custom body class to the head
add_filter( 'body_class', 'form_body_class' );
function form_body_class( $classes ) {
	
	$classes[] = 'progress-form-page';
	return $classes;
	
}

function show_attendance() {
	$eventID = $_GET['eventID'];
	if($eventID) {
		$eventTypeForBreadcrumbs = CNMI_Events::get_event_type($eventID);
		$breadcrumbs = [
			"My Trainings" => "/my-trainings",
			$eventTypeForBreadcrumbs => "/my-training?training=" . $eventID,
			get_the_title() => '#'
		];
		include(locate_template('partials/elements/breadcrumbs.php'));	
		include(locate_template('partials/elements/top-matter.php'));
		
		get_template_part('partials/forms/attendance-form');
	} else {
		?>
			<p>Sorry, page not found.</p>
		<?php
	}
}
add_action('genesis_entry_content', 'show_attendance');

genesis();