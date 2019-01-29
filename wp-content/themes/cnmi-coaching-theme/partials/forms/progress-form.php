<?php
if ( 
	isset( $_POST['change_progress'] ) 
	&& ! wp_verify_nonce( $_POST['change_progress'], 'change_progress' ) 
) {
		print 'Sorry, your nonce did not verify.';
		exit;
} else {
	if(isset($_POST['id']) && $_POST['id'] !='') {
		$id = $_POST['id'];
	}
	if(isset($_POST['id']) && $_POST['id'] !='') {
		// CNMI_Progress::update_progress_by_id_for_coach($id);
	}
}
?>
<h1>Change Status Form</h1>
<form action="/" method="POST">
	<label for="id">ID</label>
	<input label="ID" name="id" type="number">

	<label for="status">Select Status</label>
	<select name="status">
		<option value="active">Active</option>
		<option value="suspended">Suspended</option>
	</select>
	<input type="submit" value="Change">
	<?php wp_nonce_field( 'change_progress', 'change_progress' ); ?>
</form>