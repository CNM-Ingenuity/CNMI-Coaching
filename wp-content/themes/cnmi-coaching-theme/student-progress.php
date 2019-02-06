<?php
/*
 * Template Name: Student Progress
 */
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

//* Add custom body class to the head
add_filter( 'body_class', 'form_body_class' );
function form_body_class( $classes ) {
	
	$classes[] = 'progress-form-page';
	return $classes;
	
}

function show_progress() {
	if ( 
		isset( $_POST['mark_complete'] ) 
		&& ! wp_verify_nonce( $_POST['mark_complete'], 'mark_complete' ) 
	) {
			print 'Sorry, your nonce did not verify.';
			exit;
	} else {
		if(
			isset($_POST['id']) && $_POST['id'] !=''
			&&
			isset($_POST['event_id']) && $_POST['event_id'] !=''
			&&
			isset($_POST['key']) && $_POST['key'] !=''
		) {
			$result = CNMI_Progress::mark_complete($_POST['id'], $_POST['event_id'], $_POST['key']);
			if($result) {
					?>
						<p class='success-message'>Progress has been saved.</p>
					<?php
				} else {
					?>
						<p class='error-message'>Something went wrong, please try again.</p>
					<?php
				}
		} else if (isset($_POST['id']) && $_POST['id'] !='') {
			var_dump($_POST);
			?>
				<p class='error-message'>Some information is missing, please make sure your form is complete.</p>
			<?php
		}
	}
	$progressID = $_GET['progress'];
	if($progressID) {
		$progress = CNMI_Progress::get_progress_by_id($progressID);
		// var_dump($progress);
		$eventID = $progress->event_id;
		$eventTypeForBreadcrumbs = CNMI_Events::get_event_type($eventID);
		$student = get_user_by('id', $progress->user_id);
		// event type is expected by top-matter
		$eventType = $student->first_name . ' ' . $student->last_name;
		$breadcrumbs = [
			"My Trainings" => "/my-trainings",
			$eventTypeForBreadcrumbs => "/my-training?training=" . $eventID,
			$eventType => '#'
		];
		include(locate_template('partials/elements/breadcrumbs.php'));	
		include(locate_template('partials/elements/top-matter.php'));

		$sessions_attended = 0;
		for ($i = 1; $i < 11; $i++) {
			if($progress->{'attendance_' . $i} === '1') {
				$sessions_attended++;
			}
		}

		?>
			<h5>Sessions Attended: <?php echo $sessions_attended; ?></h5>
		<?php

		$completeness_fields = [
			"Fieldwork" => "fieldwork",
			"Training Complete" => "training_complete",
			"Assessment Complete" => "assessment_complete",
			"Certification Complete" => "certification_complete"
		];

		foreach ($completeness_fields as $description => $value) {
			$complete = $progress->{$value} === '1';
			$completeText = $complete ? "Complete" : "Incomplete " . build_complete_form($value, $eventID);
			echo "<p>" . $description . ": " . $completeText . "</p>";		

		}

		$total_training_time = 0;
		?>
			<h5>Coaching Hours</h5>
			<table>
				<tr>
					<th>Client Name</th>
					<th>Date</th>
					<th>Minutes</th>
					<th>Comments</th>
				</tr>
		<?php
		foreach ($progress->coaching_hours as $coaching_hour) {
				$total_training_time += $coaching_hour->minutes;
				$date = new DateTime($coaching_hour->date);
				?>
					<tr>
						<td><?php echo $coaching_hour->client_name; ?></td>
						<td><?php echo $date->format('m/d/Y'); ?></td>
						<td><?php echo $coaching_hour->minutes; ?></td>
						<td><?php echo $coaching_hour->comments; ?></td>
					</tr>
				<?php
		}
		?>
			</table>
			<h5>Total Training Time: <?php echo floor($total_training_time/60); ?> hours and <?php echo $total_training_time % 60; ?> minutes</h5>

		<?php

		 
		// "coaching_hours_complete",
		// "coaching_sessions_complete",
		// "coaching_agreement".
		// "coaching_letters"
	} else {
		?>
			<p>Sorry, page not found.</p>
		<?php
	}
}
add_action('genesis_entry_content', 'show_progress');

function build_complete_form($key, $eventID) {
	$progressID = $_GET['progress'];
	ob_start();
	?>
		<form method="POST">
			<input type="hidden" name="id" value="<?php echo $progressID; ?>">
			<input type="hidden" name="event_id" value="<?php echo $eventID; ?>">
			<input type="hidden" name="key" value="<?php echo $key; ?>">
		
			<input type="submit" value="Mark Complete">
			<?php wp_nonce_field( 'mark_complete', 'mark_complete' ); ?>
		</form>
	<?php
	$result = ob_get_clean();
	return $result;
}

genesis();