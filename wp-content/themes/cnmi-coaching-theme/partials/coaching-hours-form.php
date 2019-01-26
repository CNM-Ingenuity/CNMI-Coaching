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
			CNMI_Coaching_Hours::save_new_coaching_hours($_POST['id'], $_POST['client_name'], $_POST['date'], $_POST['minutes'], $_POST['comments']);
	}
}
?>
<h1>Submit Coaching Hours</h1>
<form action="" method="POST" enctype="multipart/form-data">
	<label for="id">Progress ID</label>
	<input label="ID" name="id" type="number">

	<label for="client_name">Client Name</label>
	<input label="client_name" name="client_name">

	<label for="date">Date</label>
	<input label="date" name="date" type="date">

	<label for="minutes">Minutes</label>
	<input label="minutes" name="minutes" type="number">

	<label for="comments">Comments</label>
	<textarea label="comments" name="comments"></textarea>
	
	<input type="submit" value="Submit" name="submit">
	<?php wp_nonce_field( 'coaching_hours', 'coaching_hours' ); ?>
</form>