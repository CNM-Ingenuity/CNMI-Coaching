<?php
/*
 * Template Name: My Certifications
 */
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

function show_my_certifications() {
	get_template_part('partials/elements/top-matter');	
	$certifications = CNMI_Progress::get_current_student_progress();
	foreach ($certifications as $certification) {
		$eventID = $certification->event_id;
		$eventType = CNMI_Events::get_event_type($eventID);
		$eventStartDate = CNMI_Events::get_event_start_date($eventID);
		if($eventStartDate) {
			$eventStartDate = $eventStartDate->format('m/d/Y');
		}
		$eventTrainer = CNMI_Events::get_event_trainer($eventID);
		?>

			<a href='/my-certifiction?certification=<?php echo $eventID; ?>'>
				<div class="training">
					<h3 class="training-title"><?php echo $eventType; ?></h3>
					<p class="training-students">Instructor: <?php echo $eventTrainer; ?></p>
					<p class="training-date"><p>Date: <?php echo $eventStartDate; ?></p>
				</div>
			</a>
      	<?php
	}
}
add_action('genesis_entry_content', 'show_my_certifications');

genesis();