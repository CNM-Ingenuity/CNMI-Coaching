<?php ?>

<div class="description">
		<?php
			echo wpautop($content['content']);
	 	?>
	<p>Requirements:</p>
	<ul>
		<?php
			foreach($content['requirements'] as $requirements){
				echo '<li>' . $requirements . '</li>';
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
			
		</div>
	</div>
</div>