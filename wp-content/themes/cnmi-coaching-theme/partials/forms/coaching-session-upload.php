<?php
if ( 
	isset( $_POST['upload_file'] ) 
	&& ! wp_verify_nonce( $_POST['upload_file'], 'upload_file' ) 
) {
		print 'Sorry, your nonce did not verify.';
		exit;
} else {
	if(isset($_POST['id']) && $_POST['id'] !='') {
		if(isset($_POST['media_upload']) && $_POST['media_upload'] !='') {
			$result = false;
			if($_POST['media_upload']) {
				$result = CNMI_Coaching_Session::save_new_media($_POST['id'], $_FILES['file'], true);
			} else if(isset($_POST['link']) && $_POST['link'] !='') {
				$result = CNMI_Coaching_Session::save_new_media($_POST['id'], $_POST['link'], false);
			}
			if($result) {
				?>
					<p class='success'>Your coaching session has been saved.</p>
				<?php
			} else {
				?>
					<p class='error'>Something went wrong, please try again.</p>
				<?php
			}
		}
	} else if (isset($_POST['id']) && $_POST['id'] !='') {
		?>
			<p class='error'>Some information is missing, please make sure your form is complete.</p>
		<?php
	}
}
?>
<h1>Upload Coaching Session</h1>
<form id="coaching-session-upload-form" action="" method="POST" enctype="multipart/form-data">
	
	<input name="id" type="hidden" required value="<?php echo $_GET['certification']; ?>">

	<label for="media_upload">Upload File or Use a Link to Existing File</label>
	<select name="media_upload" id="media_upload" required>
		<option value="0">Use a Link</option>
		<option value="1">Upload File</option>
	</select>

	<div id="upload-file" style="display: none">
		Select file to upload:
		<input type="file" name="file">
	</div>

	<div id="use-link">
		<label for="link">Link to Existing File</label>
		<input label="link" name="link" type="url" required url placeholder="https://example.com/file.mp3">
	</div>

	<input type="submit" value="Upload File" name="submit" required>
	<?php wp_nonce_field( 'upload_file', 'upload_file' ); ?>
</form>
<script>
	jQuery("#coaching-session-upload-form").validate();
	jQuery("#media_upload").change(function() {
		if(jQuery(this).val() == 1) {
			jQuery('#use-link').hide();
			jQuery('#upload-file').show();
		} else {
			jQuery('#use-link').show();
			jQuery('#upload-file').hide();
		}
	});
</script>