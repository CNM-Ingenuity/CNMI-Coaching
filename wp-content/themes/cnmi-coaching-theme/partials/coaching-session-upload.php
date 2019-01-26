<?php
if ( 
	isset( $_POST['upload_file'] ) 
	&& ! wp_verify_nonce( $_POST['upload_file'], 'upload_file' ) 
) {
		print 'Sorry, your nonce did not verify.';
		exit;
} else {
	if(isset($_POST['id']) && $_POST['id'] !='') {
		$id = $_POST['id'];
	}
	if(isset($_POST['id']) && $_POST['id'] !='') {
			CNMI_Coaching_Session::save_new_media($id, $_FILES['file']);
	}
}
?>
<h1>Upload Coaching Session</h1>
<form action="" method="POST" enctype="multipart/form-data">
	<label for="id">Progress ID</label>
	<input label="ID" name="id" type="number">

	Select file to upload:
	<input type="file" name="file">
	<input type="submit" value="Upload File" name="submit">
	<?php wp_nonce_field( 'upload_file', 'upload_file' ); ?>
</form>