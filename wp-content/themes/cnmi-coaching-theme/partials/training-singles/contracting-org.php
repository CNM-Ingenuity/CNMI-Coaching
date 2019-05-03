<?php
$eventID = $progressID;
$eventType = CNMI_Events::get_event_type($eventID);
$breadcrumbs = [
	"Scheduled Trainings" => "/scheduled-trainings",
	$eventType => "#"
];
include(locate_template('partials/elements/breadcrumbs.php'));
include(locate_template('partials/elements/top-matter.php'));
$eventStartDate = CNMI_Events::get_event_start_date($eventID);
if($eventStartDate) {
	$eventStartDate = $eventStartDate->format('m/d/Y');
}
$students = CNMI_Progress::get_students_from_event_id($eventID);

?>
<div class="item">
	<h3 class="title"><?php echo $eventType; ?></h3>
	<p class="students">Date: <?php echo $eventStartDate; ?></p>
	<p class="date">Students: <?php echo count($students); ?></p>
	<div><a class="button" href="/event?p=<?php echo $eventID; ?>">Register Additional Students</a></div>
</div>
<table>
	<tr>
		<th>Name</th>
		<th>Email</th>
	</tr>
<?php
foreach ($students as $student) {
	?>
		<tr>
			<td><?php echo $student->user_nicename; ?></td>
			<td><?php echo $student->user_email; ?></td>
		</tr>
	<?php
}
?>
</table>