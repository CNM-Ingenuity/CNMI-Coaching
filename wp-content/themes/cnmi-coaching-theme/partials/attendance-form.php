<?php
if ( 
	isset( $_POST['take_attendance'] ) 
	&& ! wp_verify_nonce( $_POST['take_attendance'], 'take_attendance' ) 
) {
		print 'Sorry, your nonce did not verify.';
		exit;
} else {
	if(
		isset($_POST['event_id']) && $_POST['event_id'] !=''
		&&
		isset($_POST['session_number']) && $_POST['session_number'] !=''
		&&
		isset($_POST['student_ids'])
	) {
		CNMI_Progress::take_attendance($_POST['event_id'], $_POST['session_number'], $_POST['student_ids']);
	}
}

// get students
$students = CNMI_Progress::get_students_from_event_id(383);
?>
<h1>Take Attendance Form</h1>
<form action="/" method="POST">
	<label for="event_id">Event ID</label>
	<input label="event_id" name="event_id" type="number">

	<label for="session_number">Session Number</label>
	<input label="session_number" name="session_number" type="number">

	<?php foreach($students as $student) {
		?>

		<input type="checkbox" name="student_ids[]" value="<?php echo $student->user_id; ?>"> 
		<?php echo $student->user_nicename; ?><br/>
		
		<?php
	} ?>

	
	<input type="submit" value="Take Attendance">
	<?php wp_nonce_field( 'take_attendance', 'take_attendance' ); ?>
</form>