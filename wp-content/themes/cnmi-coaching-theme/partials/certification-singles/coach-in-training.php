<?php
$certification = CNMI_Progress::get_progress_by_id($progressID, false);
$hours = CNMI_Coaching_Hours::get_coaching_hours_by_progress_id($certification->id);
$eventID = $certification->event_id;
$eventType = CNMI_Events::get_event_type($eventID);
$breadcrumbs = [
	"My Certifications" => "/my-certifications",
	$eventType => "#",
];
include(locate_template('partials/elements/breadcrumbs.php'));	
include(locate_template('partials/elements/top-matter.php'));
$content = CNMI_Certifications::get_certification_content_by_event_id($eventID);
if($certification->certification_complete === "1" && $plan_id == 406) {
	// if certified coach membership and the certification is complete, show a different page
	include(locate_template('partials/certification-singles/complete-certification.php'));
} else {
	include(locate_template('partials/certification-singles/incomplete-certification.php'));
} 