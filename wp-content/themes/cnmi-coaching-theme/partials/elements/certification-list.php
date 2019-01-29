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
			<div class="training">
				<h3 class="training-title"><?php echo $eventType; ?></h3>
				<p class="training-students">Instructor: <?php echo $eventTrainer; ?></p>
				<p class="training-date"><p>Date: <?php echo $eventStartDate; ?></p>
			</div>
		</a>
	<?php
}