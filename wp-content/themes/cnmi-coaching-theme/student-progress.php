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
		$hasAccess = false;
		if( current_user_can('administrator')) {
			$hasAccess = true;
		} else {
			$hasAccess = verify_coach_access($progressID);
			// event type is expected by top-matter
			$eventType = $student->first_name . ' ' . $student->last_name;
			$breadcrumbs = [
				"My Trainings" => "/my-trainings",
				$eventTypeForBreadcrumbs => "/my-training?training=" . $eventID,
				$eventType => '#'
			];
			include(locate_template('partials/elements/breadcrumbs.php'));	
			include(locate_template('partials/elements/top-matter.php'));
		}
		if(!$hasAccess) {
			?>
				<p>Sorry, you don't have access to this page.</p>
			<?php
			
		} else {
			$progress = CNMI_Progress::get_progress_by_id($progressID);
			
			$eventID = $progress->event_id;
			$eventTypeForBreadcrumbs = CNMI_Events::get_event_type($eventID);
			$student = get_user_by('id', $progress->user_id);
			

			$sessions_attended = 0;
			for ($i = 1; $i < 11; $i++) {
				if($progress->{'attendance_' . $i} === '1') {
					$sessions_attended++;
				}
			}

			?>
				<table>
					<tr>
						<td>Sessions Attended</td>
						<td><?php echo $sessions_attended; ?></td>
					</tr>
						<?php
						build_complete_section("Training Complete", "training_complete", $progress, $eventID);
						build_complete_section("Fieldwork", "fieldwork", $progress, $eventID);
						if( current_user_can('administrator')) {
							build_complete_section("Assessment Complete", "assessment_complete", $progress, $eventID);
						} else {
							build_complete_section("Assessment Complete", "assessment_complete", $progress, $eventID, true);
						}
						?>
				</table>
			<?php
			

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

			<table>
			<?php
			build_complete_section('Coaching Hours', "coaching_hours_complete", $progress, $eventID);
			?>
			</table>
			<h5>Coaching Sessions</h5>
			<table>
				<tr>
					<th></th>
					<th>Status</th>
					<th>Actions</th>
				</tr>
			<?php
			$count = 0;
			foreach ($progress->coaching_sessions as $coaching_session) {
				$count++;
					?>
						<tr>
							<td>Session <?php echo $count; ?></td>
							<td><?php echo $coaching_session->reviewer_id ? "Reviewed" : "Needs Review"; ?></td>
							<td><a class="button" href="review-coaching-session?session=<?php echo $coaching_session->id; ?>">Review</a></td>
						</tr>
					<?php
			}
			?>
			</table>
			<table>
			<?php
			build_complete_section('Coaching Sessions', "coaching_sessions_complete", $progress, $eventID);
			?>
			</table>
			<h5>Coaching Letters</h5>
			<table>
				<tr>
					<th></th>
					<th>Actions</th>
				</tr>
			<?php 
			$count = 0;
			foreach ($progress->coaching_letters as $coaching_letter) {
				$count++;
					?>
						<tr>
							<td>Letter <?php echo $count; ?></td>
							<td><a class="button" target="_blank" href="<?php echo $coaching_letter->url; ?>">Review</a></td>
						</tr>
					<?php
			}
			?>
			</table>
			<h5>Coaching Agreements</h5>
			<table>
				<tr>
					<th></th>
					<th>Actions</th>
				</tr>
			<?php 
			$count = 0;
			foreach ($progress->coaching_agreement as $coaching_agreement) {
				$count++;
					?>
						<tr>
							<td>Agreement <?php echo $count; ?></td>
							<td><a class="button" target="_blank" href="<?php echo $coaching_agreement->url; ?>">Review</a></td>
						</tr>
					<?php
			}
			?>
			</table>
			<table>
			<?php
			build_complete_section("Certification Complete", "certification_complete", $progress, $eventID);
			?>
			</table>
			<?php

			if($progress->certification_complete === '1') {
				$ceus = CNMI_CEU_Entry::get_ceu_entry_by_progress_id($progressID);

				$inCNM = [];
				$outsideCNM = [];

				foreach ($ceus as $ceu) {
					if($ceu->is_outside_cnm === "0") {
						$inCNM[] = $ceu;
					} else {
						$outsideCNM[] = $ceu;
					}
				}

				if(count($inCNM) > 0) {
					?>
						<h4>CNM CEUs</h4>
						<table>
							<tr>
								<th>Title</th>
								<th>Trainer</th>
								<th>Date Completed</th>
								<th>Verification Code</th>
							</tr>
							<?php
								foreach($inCNM as $ceu) {
									?>
										<tr>
											<td><?php echo $ceu->program_training_title; ?></td>
											<td><?php echo $ceu->trainer_name; ?></td>
											<td><?php echo $ceu->date_completed; ?></td>
											<td><?php echo $ceu->verification_code; ?></td>
										</tr>	
									<?php
								}
							?>
						</table>
					<?php
				}

				if(count($outsideCNM) > 0) {
					?>
						<h4>Non-CNM CEUs</h4>
							
						<?php
							foreach($outsideCNM as $ceu) {
								?>
									<h5><?php echo $ceu->program_training_title; ?></h5>
									<table>
										<tr>
											<td>CEUs Requested</td>
											<td><?php echo $ceu->ceus_requested; ?></td>
										</tr>
										<tr>
											<td>Certification</td>
											<td><?php echo $ceu->certification; ?></td>
										</tr>
										<tr>
											<td>Trainer</td>
											<td><?php echo $ceu->trainer_name; ?></td>
										</tr>
										<tr>
											<td>Organization Sponsor</td>
											<td><?php echo $ceu->org_sponsor; ?></td>
										</tr>
										<tr>
											<td>Start Date</td>
											<td><?php echo $ceu->start_date; ?></td>
										</tr>
										<tr>
											<td>End Date</td>
											<td><?php echo $ceu->end_date; ?></td>
										</tr>
										<tr>
											<td>Program Description</td>
											<td><?php echo $ceu->program_description; ?></td>
										</tr>
										<tr>
											<td>Program Website</td>
											<td><a href="<?php echo $ceu->program_website; ?>" class="button">View</a></td>
										</tr>
										<tr>
											<td>Learning Objectives</td>
											<td><?php echo $ceu->learning_objectives; ?></td>
										</tr>
										<tr>
											<td>Agenda</td>
											<td><a href="<?php echo $ceu->agenda_url; ?>" class="button">View</a></td>
										</tr>
									</table>
							<?php
						}
				}

			}
		}
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

function build_complete_section($description, $value, $progress, $eventID, $hideForm = false) {
	$complete = $progress->{$value} === '1';
	if($hideForm) {
		$completeText = $complete ? "Complete" : "Incomplete";
	} else {
		$completeText = $complete ? "Complete" : build_complete_form($value, $eventID);
	}
	echo "<tr><td>" . $description . "</td><td>" . $completeText . "</td></tr>";
}

genesis();