<?php
$certification = CNMI_Progress::get_progress_by_id($progressID, false);
$hours = CNMI_Coaching_Hours::get_coaching_hours_by_progress_id($certification->id);
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
	<?php
		$sessions_attended = 0;
		for ($i = 1; $i < 11; $i++) {
			if($certification->{'attendance_' . $i} === '1') {
				$sessions_attended++;
			}
		}
		$total_training_time = 0;
		foreach ($hours as $coaching_hour) {
				$total_training_time += $coaching_hour->minutes;
		}
	?>
	<table class='requirements-table'>
		<tr>
			<th>Requirement</th>
			<th>Status</th>
		</tr>
		<tr>
			<td>Sessions Attended</td>
			<td><?php echo $sessions_attended; ?></td>
		</tr>
		<tr>
			<td>Training</td>
			<td><?php echo $certification->training_complete === "1" ? "Complete" : "Incomplete"; ?></td>
		</tr>
		<tr>
			<td>Fieldwork</td>
			<td><?php echo $certification->fieldwork === "1" ? "Complete" : "Incomplete"; ?></td>
		</tr>
		<tr>
			<td>Coaching Hours</td>
			<td><?php echo floor($total_training_time/60); ?> hours and <?php echo $total_training_time % 60; ?> minutes</td>
		</tr>
		<tr>
			<td>Coaching Hours Status</td>
			<td><?php echo $certification->coaching_hours_complete === "1" ? "Complete" : "Incomplete"; ?></td>
		</tr>
		<tr>
			<td>Coaching Sessions</td>
			<td><?php echo $certification->coaching_sessions_complete === "1" ? "Complete" : "Incomplete"; ?></td>
		</tr>
		<tr>
			<td>Assessment</td>
			<td><?php echo $certification->assessment_complete === "1" ? "Complete" : "Incomplete"; ?></td>
		</tr>
		<tr>
			<td>Certification</td>
			<td><?php echo $certification->certification_complete === "1" ? "Complete" : "Incomplete"; ?></td>
		</tr>
	</table>
	<div class="certification-buttons">
		<div class="one-half first">
			<a class="button item-button" href="/transcript">
				<p>View Transcript</p>
				<img src="/wp-content/uploads/2019/01/download-arrow.png">
			</a>
			<a class="button item-button" href="<?php echo $content['assessment'];?>">
				<p>Take Assessment</p>
				<span class="dashicons dashicons-media-document"></span>
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