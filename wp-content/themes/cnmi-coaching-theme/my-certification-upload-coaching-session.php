<?php
/*
 * Template Name: Upload Coaching Session
 */
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

//* Add custom body class to the head
add_filter( 'body_class', 'form_body_class' );
function form_body_class( $classes ) {

	$classes[] = 'progress-form-page';
	return $classes;

}

function show_my_certification() {
	$progressID = $_GET['certification'];
	if($progressID) {
		$certification = CNMI_Progress::get_progress_by_id($progressID, false);

		$eventID = $certification->event_id;
		$eventTypeForBreadcrumbs = CNMI_Events::get_event_type($eventID);
		$breadcrumbs = [
			"My Certifications" => "/my-certifications",
			$eventTypeForBreadcrumbs => "/my-certification?certification=" . $progressID,
			get_the_title() => '#'
		];
		include(locate_template('partials/elements/breadcrumbs.php'));
		include(locate_template('partials/elements/top-matter.php'));
		include(locate_template('partials/forms/coaching-session-upload.php'));
	} else {
		?>
			<p>Sorry, page not found.</p>
		<?php
	}
}
add_action('genesis_entry_content', 'show_my_certification');

genesis();
