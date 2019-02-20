<?php 

?>

<div class="description">
		<?php
			echo wpautop($content['content']);
	 	?>
	<p>Requirements:</p>
	<ul>
		<?php
			$count = 0;
			foreach($content['requirements'] as $requirements){
				if($count === 0) {
					echo '<li>' . $requirements . ' Hours</li>';
				} else {
					echo '<li>' . $requirements . '</li>';
				}
				$count++;
			}
		?>
	</ul>
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