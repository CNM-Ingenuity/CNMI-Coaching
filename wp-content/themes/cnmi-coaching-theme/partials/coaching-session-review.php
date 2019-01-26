<?php
if ( 
	isset( $_POST['upload_file'] ) 
	&& ! wp_verify_nonce( $_POST['review_session'], 'review_session' ) 
) {
		print 'Sorry, your nonce did not verify.';
		exit;
} else {
	if(
		isset($_POST['id']) && $_POST['id'] !=''
		&&
		isset($_POST['comments']) && $_POST['comments'] !=''
		&&
		isset($_POST['session_accepted']) && $_POST['session_accepted'] !=''
	) {
			CNMI_Coaching_Session::review_session($_POST['id'], $_POST['comments'], $_POST['session_accepted']);
	}
}
?>
<h1>Coaching Session Review</h1>
<form action="" method="POST" enctype="multipart/form-data">
	<label for="id">Session ID</label>
	<input label="ID" name="id" type="number">

	<label for="comments">Comments</label>
	<textarea label="comments" name="comments"></textarea>

	<label for="session_accepted">Session Accepted</label>
	<select name="session_accepted">
		<option value="1">Yes</option>
		<option value="0">No</option>
	</select>
	
	<input type="submit" value="Review Session" name="submit">
	<?php wp_nonce_field( 'review_session', 'review_session' ); ?>
</form>