<?php
$eventID = $progressID;
$eventType = CNMI_Events::get_event_type($eventID);
$breadcrumbs = [
	"My Trainings" => "/my-trainings",
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
	<a class="button bottom-right-button" href="/attendance?eventID=<?php echo $eventID; ?>">Take Attendance<span class="dashicons dashicons-yes"></span></a>
</div>
<table>
	<tr>
		<th>Name</th>
		<th>Email</th>
		<th>Actions</th>
	</tr>
<?php
foreach ($students as $student) {
	?>
		<tr>
			<td><?php echo $student->user_nicename; ?></td>
			<td><?php echo $student->user_email; ?></td>
			<td><a class="button" href="/student-progress?progress=<?php echo $student->id; ?>">View</a></td>
		</tr>
	<?php
}
?>
</table>