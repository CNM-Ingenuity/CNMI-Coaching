<?php
$student_id = $_GET['user'];
$certifications = CNMI_Progress::get_current_student_progress_as_admin($student_id);
$user = get_user_by('id', $student_id);
?>
	<h3>Coach: <?php echo $user->first_name . ' ' . $user->last_name; ?></h3>
<?php
foreach ($certifications as $certification) {
	$eventID = $certification->event_id;
	$eventType = CNMI_Events::get_event_type($eventID);
	$eventStartDate = CNMI_Events::get_event_start_date($eventID);
	if($eventStartDate) {
		$eventStartDate = $eventStartDate->format('m/d/Y');
	}
	$eventTrainer = CNMI_Events::get_event_trainer($eventID);
	if(count($certifications) === 1) {
		echo "<script> window.location.href='". $linkAddress . $certification->id ."';</script> ";
	}
	?>

		<a href='<?php echo $linkAddress . $certification->id; ?>'>
			<div class="item">
				<h3 class="title"><?php echo $eventType; ?></h3>
				<p class="students">Instructor: <?php echo $eventTrainer; ?></p>
				<p class="date">Date: <?php echo $eventStartDate; ?></p>
			</div>
		</a>
	<?php
}
