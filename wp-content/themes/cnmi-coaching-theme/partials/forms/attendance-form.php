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

$user_id = get_current_user_id();
$memberships = wc_memberships_get_user_active_memberships( $user_id );
if($memberships){
	$plan_id = $memberships[0]->{"plan_id"};
	if($plan_id === 411) {
		// get students
		$students = CNMI_Progress::get_students_from_event_id($_GET['eventID']);
		?>
		<form id="attendance-form" method="POST">
			<input name="event_id" type="hidden" value="<?php echo $_GET['eventID']; ?>">

			<label for="session_number">Session</label>
			<select label="session_number" name="session_number" required>
				<option value="1">Session 1, Day 1, Morning</option>
				<option value="2">Session 2, Day 1, Afternoon</option>
				<option value="3">Session 3, Day 2, Morning</option>
				<option value="4">Session 4, Day 2, Afternoon</option>
				<option value="5">Session 5, Day 3, Morning</option>
				<option value="6">Session 6, Day 4, Morning</option>
				<option value="7">Session 7, Day 4, Afternoon</option>
				<option value="8">Session 8, Day 5, Morning</option>
				<option value="9">Session 9, Day 5, Afternoon</option>
				<option value="10">Session 10, Day 6, Morning</option>
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
		<?php
	} else {
		?>
			<p class='error-message'>Sorry, you don't have access to this page.</p>
		<?php
	}

}