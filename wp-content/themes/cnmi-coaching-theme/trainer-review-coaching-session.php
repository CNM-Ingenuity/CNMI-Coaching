<?php
/*
 * Template Name: Review Coaching Session
 */
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

//* Add custom body class to the head
add_filter( 'body_class', 'form_body_class' );
function form_body_class( $classes ) {
	
	$classes[] = 'progress-form-page';
	return $classes;
	
}

function show_session_review_form() {
	$sessionID = $_GET['session'];
	if($sessionID) {
		$session = CNMI_Coaching_Session::get_coaching_session_by_id($_GET['session']);
		$progress = CNMI_Progress::get_progress_by_id($session->progress_id);
		$eventTypeForBreadcrumbs = CNMI_Events::get_event_type($progress->event_id);
		$student = get_user_by('id', $progress->user_id);
		$studentName = $student->first_name . ' ' . $student->last_name;
		$breadcrumbs = [
			"My Trainings" => "/my-trainings",
			$eventTypeForBreadcrumbs => "/my-training?training=" . $progress->event_id,
			$studentName => '/student-progress/?progress=' . $progress->id,
			get_the_title() => '#'
		];
		include(locate_template('partials/elements/breadcrumbs.php'));	
		include(locate_template('partials/elements/top-matter.php'));
		include(locate_template('partials/forms/coaching-session-review.php'));
	} else {
		?>
			<p>Sorry, page not found.</p>
		<?php
	}
}
add_action('genesis_entry_content', 'show_session_review_form');

genesis();