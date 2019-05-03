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
			if($_POST['media_upload'] && isset($_FILES['file']['name']) && $_FILES['file']['name'] != '') {
				$result = CNMI_Coaching_Session::save_new_media($_POST['id'], $_FILES['file'], true);
				if($result) {
					?>
						<p class='success-message'>Your coaching session has been saved.</p>
					<?php
				} else {
					?>
						<p class='error-message'>Something went wrong, please try again.</p>
					<?php
				}
			} else if(isset($_POST['link']) && $_POST['link'] !='') {
				$result = CNMI_Coaching_Session::save_new_media($_POST['id'], $_POST['link'], false);
				if($result) {
					?>
						<p class='success-message'>Your coaching session has been saved.</p>
					<?php
				} else {
					?>
						<p class='error-message'>Something went wrong, please try again.</p>
					<?php
				}
			} else {
				?>
					<p class='error-message'>Some information is missing, please make sure your form is complete.</p>
				<?php
			}
		}
	} else if (isset($_POST['id']) && $_POST['id'] !='') {
		?>
			<p class='error-message'>Some information is missing, please make sure your form is complete.</p>
		<?php
	}
}
$text = CNMI_Certifications::get_coaching_session_upload_text($eventID);
if ($text !== '') {
	echo '<div class="file-submission-description"><p>' . $text .'</p></div>';
}
?>
<form id="coaching-session-upload-form" action="" method="POST" enctype="multipart/form-data">
	<input name="id" type="hidden" required value="<?php echo $_GET['certification']; ?>">
	<label for="media_upload">Upload File or Use a Link to Existing File</label>
	<select name="media_upload" id="media_upload" required>
		<option value="0">Use a Link</option>
		<option value="1">Upload File</option>
	</select>
	<label>Most common video and audio file formats are accepted( .mov, .mp4,.avi,.wmv, .mp3, etc)</label>
	</br>


	<div id="upload-file" style="display: none">
		Select file to upload:
		<input type="file" name="file">
	</div>

	<div id="use-link">
		<label for="link">Link to Existing File</label>
		<input label="link" name="link" type="url" required url placeholder="https://example.com/file.mp3">
	</div>

	<input type="submit" value="Submit" name="submit" required>
	<?php wp_nonce_field( 'upload_file', 'upload_file' ); ?>
</form>
<?php
	$progressID = $_GET['certification'];
	if($progressID) {
		$progress = CNMI_Progress::get_progress_by_id($progressID);
		?>
			<label>File Uploads</label>
			<ul class="file-review">
				<?php
					foreach($progress->coaching_sessions as $coaching_session) {
						echo '<li><a class="button" href="'. $coaching_session->url . '" target="_blank" download>View Submission</a>';
						echo $coaching_session->description .'</li>';
					}
				?>
			</ul>
		<?php
	}
?>

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
