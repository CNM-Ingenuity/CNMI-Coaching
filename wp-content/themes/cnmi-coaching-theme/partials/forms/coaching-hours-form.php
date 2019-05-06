<?php
if (
	isset( $_POST['coaching_hours'] )
	&& ! wp_verify_nonce( $_POST['coaching_hours'], 'coaching_hours' )
) {
		print 'Sorry, your nonce did not verify.';
		exit;
} else {
	if(
		isset($_POST['id']) && $_POST['id'] !=''
		&&
		isset($_POST['comments']) && $_POST['comments'] !=''
		&&
		isset($_POST['client_name']) && $_POST['client_name'] !=''
		&&
		isset($_POST['date']) && $_POST['date'] !=''
		&&
		isset($_POST['minutes']) && $_POST['minutes'] !=''
	) {
			$result = CNMI_Coaching_Hours::save_new_coaching_hours($_POST['id'], $_POST['client_name'], $_POST['date'], $_POST['minutes'], $_POST['comments']);
			if($result) {
				?>
					<p class='success-message'>Your coaching hours have been saved.</p>
				<?php
			} else {
				?>
					<p class='error-message'>Something went wrong, please try again.</p>
				<?php
			}
	} else if (isset($_POST['id']) && $_POST['id'] !='') {
		?>
			<p class='error-message'>Some information is missing, please make sure your form is complete.</p>
		<?php
	}
}
$text = CNMI_Certifications::get_track_coaching_hours_text($eventID);
if ($text !== '') {
	echo '<div class="file-submission-description"><p>' . $text .'</p></div>';
}
$hours = CNMI_Coaching_Hours::get_coaching_hours_by_progress_id($certification->id);
$total_training_time = 0;
?>
	<h5>Previously Tracked Coaching Hours</h5>
	<table> <!--class="coaching-hours-table"-->
		<tr>
			<th>Client Name</th>
			<th>Date</th>
			<th>Minutes</th>
			<!--<th>Comments</th>-->
		</tr>
<?php
foreach ($hours as $coaching_hour) {
		$total_training_time += $coaching_hour->minutes;
		$date = new DateTime($coaching_hour->date);
		?>
			<tr>
				<td><?php echo $coaching_hour->client_name; ?></td>
				<td><?php echo $date->format('m/d/Y'); ?></td>
				<td><?php echo $coaching_hour->minutes; ?></td>
				<!--<td><?php echo $coaching_hour->comments; ?></td>-->
			</tr>
		<?php
}
?>
</table>
<h3>Coaching Hours Already Tracked: <?php echo floor($total_training_time/60); ?> hours and <?php echo $total_training_time % 60; ?> minutes</h3>
<h5>Track New Hours</h5>
<form id="coaching-hours-form" action="" method="POST" enctype="multipart/form-data">

	<input name="id" type="hidden" required value="<?php echo $_GET['certification']; ?>">

	<label for="client_name">Client Name</label>
	<input label="client_name" name="client_name" required>

	<label for="date">Date</label>
	<input label="date" name="date" type="date" required>

	<label for="minutes">Minutes</label>
	<input label="minutes" name="minutes" type="number" required>

	<label for="comments">Comments</label>
	<textarea label="comments" name="comments" required></textarea>

	<input type="submit" value="Submit" name="submit">
	<?php wp_nonce_field( 'coaching_hours', 'coaching_hours' ); ?>
</form>
<script>
	jQuery("#coaching-hours-form").validate();
</script>
