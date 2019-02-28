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
		$result = CNMI_Progress::take_attendance($_POST['event_id'], $_POST['session_number'], $_POST['student_ids']);
		// this result returns either an int of affected rows or false on failure
		if(is_int($result)) {
				?>
					<p class='success-message'>Attendance has been saved.</p>
				<?php
			} else {
				?>
					<p class='error-message'>Something went wrong, please try again.</p>
				<?php
			}
	} else if (isset($_POST['event_id']) && $_POST['event_id'] !='') {
		?>
			<p class='error-message'>Some information is missing, please make sure your form is complete.</p>
		<?php
	}
}

// get students
$students = CNMI_Progress::get_students_from_event_id($_GET['eventID']);
?>
<form id="attendance-form" method="POST">
	<input name="event_id" type="hidden" value="<?php echo $_GET['eventID']; ?>">

	<label for="session_number">Session</label>
	<select label="session_number" name="session_number" required>
		<?php for($i = 1; $i < 11; $i++) {
			?>
				<option value="<?php echo $i; ?>">Session <?php echo $i; ?></option>
			<?php
		} ?>
	</select>
	<fieldset>
		<legend>Students In Attendance</legend>
		<?php foreach($students as $student) {
			?>

			<input type="checkbox" name="student_ids[]" value="<?php echo $student->user_id; ?>"> 
			<?php echo $student->user_nicename; ?><br/>
			
			<?php
		} ?>
	</fieldset>

	
	<input type="submit" value="Take Attendance">
	<?php wp_nonce_field( 'take_attendance', 'take_attendance' ); ?>
</form>
<script>
	jQuery("#attendance-form").validate();
</script>