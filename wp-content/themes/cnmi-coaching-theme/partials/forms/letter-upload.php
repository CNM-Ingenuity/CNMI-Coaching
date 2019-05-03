<?php
if (
	isset( $_POST['upload_file'] )
	&& ! wp_verify_nonce( $_POST['upload_file'], 'upload_file' )
) {
		print 'Sorry, your nonce did not verify.';
		exit;
} else {
	if(isset($_POST['id']) && $_POST['id'] !='' && isset($_POST['description']) && $_POST['description'] !='' && isset($_FILES['file']['name']) && $_FILES['file']['name'] != '') {
		$result = CNMI_Letters::save_new_media($_POST['id'], $_FILES['file'], $_POST['description']);
		if($result) {
			?>
				<p class='success-message'>Your letter of reference has been saved.</p>
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
$text = CNMI_Certifications::get_letter_upload_text($eventID);
if ($text !== '') {
	echo '<div class="file-submission-description"><p>' . $text .'</p></div>';
}
?>
<form id="letter-upload-form" action="" method="POST" enctype="multipart/form-data">
	
	<input name="id" type="hidden" required value="<?php echo $_GET['certification']; ?>">

	<label for="description">Description</label>
	<input label="description" name="description" required>

	<label>Please upload a .pdf document or an audio or video file( .mov, .mp4,.avi,.wmv, .mp3, etc)</label>
	</br>
	<div>
		Select file to upload:
		<input type="file" name="file">
	</div>

	<input type="submit" value="Upload File" name="submit" required>
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
					foreach($progress->coaching_letters as $coaching_letters) {
						echo '<li><a class="button" href="'. $coaching_letters->url .' " target="_blank" download>Review Submission</a>';
						echo $coaching_letters->description .'</li>';
					}
				?>
			</ul>
		<?php
	}
?>

<script>
	jQuery("#letter-upload-form").validate();
</script>
