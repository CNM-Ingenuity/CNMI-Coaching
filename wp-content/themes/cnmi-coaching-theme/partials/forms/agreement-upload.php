<?php
if (
	isset( $_POST['upload_file'] )
	&& ! wp_verify_nonce( $_POST['upload_file'], 'upload_file' )
) {
		print 'Sorry, your nonce did not verify.';
		exit;
} else {
	if(isset($_POST['id']) && $_POST['id'] !='' && isset($_FILES['file']['name']) && $_FILES['file']['name'] != '') {
		$result = CNMI_Agreement::save_new_media($_POST['id'], $_FILES['file']);
		if($result) {
			?>
				<p class='success-message'>Your end user agreement has been saved.</p>
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
//Get the text and file link for the end user agreement
$content = CNMI_Certifications::get_coaching_end_user_agreement_content($eventID);
if ($content['text'] !== '') {
	echo '<div class="file-submission-description"><p>' . $content['text'] .'</p></div>';
}
if ($content['file'] !== '') {
	echo '<div class="agreement-download"><a class="button" href="' . $content['file'] .'" download>Download Agreement</a></div>';
}
?>
<form id="agreement-upload-form" action="" method="POST" enctype="multipart/form-data">
	<label>Please upload a .pdf document</label>

	<input name="id" type="hidden" required value="<?php echo $_GET['certification']; ?>">

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
					foreach($progress->coaching_agreement as $coaching_agreement) {
						$reviewed = '<p>This submission has not beeen reviewed</p>' ;
						if($coaching_agreement->reviewer_id !== null) {
							$reviewed = '<p>This submission has been reviewed on: ' . $coaching_agreement->date .'</p>';
						}
						echo '<li><a class="button" href="'. $coaching_agreement->url .'" target="_blank" download>View Submission</a>';
						echo $reviewed .'</li>';
					}
				?>
			</ul>
		<?php
	}
?>
<script>
	jQuery("#agreement-upload-form").validate();
</script>
