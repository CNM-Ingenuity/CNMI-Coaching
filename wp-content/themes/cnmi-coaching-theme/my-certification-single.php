<?php
/*
 * Template Name: My Certification
 */
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

function show_my_certification() {
	$progressID = $_GET['certification'];
	if($progressID) {
		$certification = CNMI_Progress::get_progress_by_id($progressID, false);
		$eventID = $certification->event_id;
		$eventType = CNMI_Events::get_event_type($eventID);
		$breadcrumbs = [
			"My Certifications" => "/my-certifications",
			$eventType => "#",
		];
		include(locate_template('partials/elements/breadcrumbs.php'));	
		include(locate_template('partials/elements/top-matter.php'));
		$content = CNMI_Certifications::get_certification_content_by_event_id($eventID);
		?>
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
					<a class="button item-button" href="<?php echo '';?>">
						<p>View Transcript</p>
						<img src="/wp-content/uploads/2019/01/download-arrow.png">
					</a>
					<a class="button item-button" href="/upload-coaching-session/?certification=<?php echo $progressID;?>">
						<p>Submit Coaching Session</p>
						<img src="/wp-content/uploads/2019/01/download-arrow.png">
					</a>
				</div>
				<div class="one-half">
					<a class="button item-button" href="/track-coaching-hours/?certification=<?php echo $progressID;?>">
						<p>Track Coaching Hours</p>
						<span class="dashicons dashicons-clock"></span>
					</a>
					<a class="button item-button" href="/submit-letters-of-reference/?certification=<?php echo $progressID;?>">
						<p>Submit Letters of Reference</p>
						<img src="/wp-content/uploads/2019/01/download-arrow.png">
					</a>
					<a class="button item-button" href="/coach-end-user-agreement/?certification=<?php echo $progressID;?>">
						<p>Coach End User Agreement</p>
						<img src="/wp-content/uploads/2019/01/download-arrow.png">
					</a>
				</div>
			</div>
		</div>
		<?php
	} else {
		?>
			<p>Sorry, page not found.</p>
		<?php
	}
}
add_action('genesis_entry_content', 'show_my_certification');

genesis();