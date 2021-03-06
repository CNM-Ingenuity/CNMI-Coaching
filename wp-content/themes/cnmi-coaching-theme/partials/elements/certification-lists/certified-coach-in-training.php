<?php
$certifications = CNMI_Progress::get_current_student_progress();
foreach ($certifications as $certification) {
	$eventID = $certification->event_id;
	$eventType = CNMI_Events::get_event_type($eventID);
	$eventTitle = CNMI_Events::get_event_post_title($eventID);	
	$eventStartDate = CNMI_Events::get_event_start_date($eventID);
	if(count($certifications) === 1) {
			echo "<script> window.location.href='". $linkAddress . $certification->id ."';</script> ";
		}
	if($eventStartDate) {
		$eventStartDate = $eventStartDate->format('m/d/Y');
	}
	$eventTrainer = CNMI_Events::get_event_trainer($eventID);
	$appendText = strpos($linkAddress, 'training') !== false ? ' Training' : ' Certification';
	?>

		<a href='<?php echo $linkAddress . $certification->id; ?>'>
			<div class="item">
				<h3 class="title"><?php echo $eventTitle . $appendText; ?></h3>
				<p class="students">Instructor: <?php echo $eventTrainer; ?></p>
				<p class="date">Date: <?php echo $eventStartDate; ?></p>
			</div>
		</a>
	<?php
}
