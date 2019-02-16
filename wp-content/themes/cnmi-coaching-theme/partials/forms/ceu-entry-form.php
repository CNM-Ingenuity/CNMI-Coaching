<?php
if ( 
	isset( $_POST['ceu_entry'] ) 
	&& ! wp_verify_nonce( $_POST['ceu_entry'], 'ceu_entry' ) 
) {
		print 'Sorry, your nonce did not verify.';
		exit;
} else {
	if ($_POST['is_outside_cnm'] == '1') {
		if(
			isset($_POST['id']) && $_POST['id'] !=''
			&&
			isset($_POST['ceus_requested']) && $_POST['ceus_requested'] !=''
			&&
			isset($_POST['certification']) && $_POST['certification'] !=''
			&&
			isset($_POST['program_training_title']) && $_POST['program_training_title'] !=''
			&&
			isset($_POST['org_sponsor']) && $_POST['org_sponsor'] !=''
			&&
			isset($_POST['trainer_name']) && $_POST['trainer_name'] !=''
			&&
			isset($_POST['start_date']) && $_POST['start_date'] !=''
			&&
			isset($_POST['end_date']) && $_POST['end_date'] !=''
			&&
			isset($_POST['program_description']) && $_POST['program_description'] !=''
			&&
			isset($_POST['program_website']) && $_POST['program_website'] !=''
			&&
			isset($_POST['learning_objectives']) && $_POST['learning_objectives'] !=''
			&&
			isset($_POST['agenda_url']) && $_POST['agenda_url'] !=''
			&&
			isset($_FILES['file']['name']) && $_FILES['file']['name'] != ''
		) {

			// insert here
				
		} else if (isset($_POST['id']) && $_POST['id'] !='') {
			?>
				<p class='error-message'>Some information is missing, please make sure your form is complete.</p>
			<?php
		}

	} else if ($_POST['is_outside_cnm'] == '0') {
		if(
			isset($_POST['id']) && $_POST['id'] !=''
			&&
			isset($_POST['program_training_title']) && $_POST['program_training_title'] !=''
			&&
			isset($_POST['trainer_name']) && $_POST['trainer_name'] !=''
			&&
			isset($_POST['date_completed']) && $_POST['date_completed'] !=''
			&&
			isset($_POST['verification_code']) && $_POST['verification_code'] !=''
		) {
			// insert here
		}
	}
	
}
?>
<form id="ceu-entry-form" action="" method="POST" enctype="multipart/form-data">

	<input name="id" type="hidden" required value="<?php echo $_GET['ceu-entry']; ?>">

	<label for="is_outside_cnm">CUEs Outside of CNM?</label>
	<select name="is_outside_cnm" id="is_outside_cnm" required>
		<option value="1">Yes</option>
		<option value="0">No</option>
	</select>

	<div id="outside-cnm">

		<label for="ceus_requested">CEUs Requested</label>
		<input label="ceus_requested" name="ceus_requested" type="number">

		<label for="certification">Certification</label>
		<select name="certification">
			<option value="financial_coach">Financial Coach</option>
			<option value="academic_coach">Academic Coach</option>
			<option value="career_coach">Career Coach</option>
			<option value="coach_trainer">Coach Trainer</option>
		</select>

		<label for="program_training_title">Program/Training Title</label>
		<input label="program_training_title" name="program_training_title">

		<label for="org_sponsor">Organization or Sponsor of Training</label>
		<input label="org_sponsor" name="org_sponsor">

		<label for="trainer_name">Trainer Name</label>
		<input label="trainer_name" name="trainer_name">

		<label for="start_date">Start Date</label>
		<input label="start_date" name="start_date" type="date">

		<label for="end_date">End Date</label>
		<input label="end_date" name="end_date" type="date">

		<label for="program_description">Program Description</label>
		<textarea label="program_description" name="program_description"></textarea>

		<label for="program_website">Program Website</label>
		<input label="program_website" name="program_website">

		<label for="learning_objectives">Learning Objectives</label>
		<textarea label="learning_objectives" name="learning_objectives"></textarea>

		<div id="agenda_url">
			Select agenda file to upload:
			<input type="file" name="agenda_url">
		</div>

	</div>

	<div id="use-cnm">

		<label for="program_training_title">Program/Training Title</label>
		<input label="program_training_title" name="program_training_title">
		
		<label for="trainer_name">Trainer Name</label>
		<input label="trainer_name" name="trainer_name">

		<label for="date_completed">Date Completed</label>
		<input label="date_completed" name="date_completed" type="date">

		<label for="verification_code">Verification Code</label>
		<input label="verification_code" name="verification_code">

	</div>
	
	<input type="submit" value="Submit" name="submit">
	<?php wp_nonce_field( 'ceu_entry', 'ceu_entry' ); ?>
</form>
<script>
	jQuery("#ceu-entry-form").validate();
	jQuery("#is_outside_cnm").change(function() {
		if(jQuery(this).val() == 1) {
			jQuery('#use-cnm').hide();
			jQuery('#outside-cnm').show();
		} else {
			jQuery('#use-cnm').show();
			jQuery('#outside-cnm').hide();
		}
	});
</script>