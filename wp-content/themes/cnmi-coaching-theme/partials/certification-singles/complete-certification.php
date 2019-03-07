<?php 

?>

<div class="description">
		<?php
			echo wpautop($content['content']);
	 	?>
	<div class="certification-buttons">
		<div class="one-half first">
			<a class="button item-button" href="/transcript">
				<p>View Transcript</p>
				<img src="/wp-content/uploads/2019/01/download-arrow.png">
			</a>
		</div>
		<div class="one-half">
			<a class="button item-button" href="/track-ceus/?certification=<?php echo $_GET['certification']; ?>">
				<p>Track CEUs</p>
				<img src="/wp-content/uploads/2019/01/download-arrow.png">
			</a>
		</div>
		<div class="one-half first">
			<a class="button item-button" target="_blank" href="<?php echo $content['certification_download']; ?>">
				<p>View Certificate</p>
				<img src="/wp-content/uploads/2019/01/download-arrow.png">
			</a>
		</div>
	</div>
</div>