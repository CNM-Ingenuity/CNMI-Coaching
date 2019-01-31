<?php
/*
 * Template Name: Transcript
 */


function show_transcript() {

	// add a print button
	?>
		<p class="button" onclick="window.print();">
			Print
			<span class="dashicons dashicons-download" ></span>
		</p>
	<?php

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

			<a href='<?php echo $linkAddress . $certification->id; ?>'>
				<div class="item">
					<h3 class="title"><?php echo $eventType; ?></h3>
					<p class="students">Instructor: <?php echo $eventTrainer; ?></p>
					<p class="date"><p>Date: <?php echo $eventStartDate; ?></p>
				</div>
			</a>

		<?php
	}
}
add_action('genesis_entry_content', 'show_transcript');

genesis();