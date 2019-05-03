<?php
/*
 * Template Name: Transcript
 */
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

function show_transcript() {
	$current_user = wp_get_current_user();
	$breadcrumbs = [
		"My Certifications" => "/my-certifications",
		get_the_title() => "#"
	];
	include(locate_template('partials/elements/breadcrumbs.php'));
	include(locate_template('partials/elements/top-matter.php'));

	// add a print button
	?>
		<p class="button" onclick="window.print();">
			Print
			<span class="dashicons dashicons-download" ></span>
		</p>
		<h3>Coach In Training: <?php echo $current_user->first_name . ' ' . $current_user->last_name ?></h3>
		<table>
			<tbody>
	<?php

	$certifications = CNMI_Progress::get_current_student_progress();
	foreach ($certifications as $certification) {
		$eventID = $certification->event_id;
		$eventType = CNMI_Events::get_event_type($eventID);
		$eventStartDate = CNMI_Events::get_event_start_date($eventID);
		if($eventStartDate) {
			$eventStartDate = $eventStartDate->format('m/d/Y');
		}
		?>

				<tr>
					<td><strong><?php echo $eventType; ?></strong></td>
					<td><strong><?php echo $eventStartDate; ?></strong></td>
					<td><strong>Attended</strong></td>
				</tr>
				<tr>
					<td></td>
					<td>Session 1, Day 1, Morning</td>
					<td><?php echo $certification->attendance_1 ? "Yes" : "No"; ?></td>
				</tr>
				<tr>
					<td></td>
					<td>Session 2, Day 1, Afternoon</td>
					<td><?php echo $certification->attendance_2 ? "Yes" : "No"; ?></td>
				</tr>
				<tr>
					<td></td>
					<td>Session 3, Day 2, Morning</td>
					<td><?php echo $certification->attendance_3 ? "Yes" : "No"; ?></td>
				</tr>
				<tr>
					<td></td>
					<td>Session 4, Day 2, Afternoon</td>
					<td><?php echo $certification->attendance_4 ? "Yes" : "No"; ?></td>
				</tr>
				<tr>
					<td></td>
					<td>Session 5, Day 3, Morning</td>
					<td><?php echo $certification->attendance_5 ? "Yes" : "No"; ?></td>
				</tr>
				<tr>
					<td></td>
					<td>Session 6, Day 4, Morning</td>
					<td><?php echo $certification->attendance_6 ? "Yes" : "No"; ?></td>
				</tr>
				<tr>
					<td></td>
					<td>Session 7, Day 4, Afternoon</td>
					<td><?php echo $certification->attendance_7 ? "Yes" : "No"; ?></td>
				</tr>
				<tr>
					<td></td>
					<td>Session 8, Day 5, Morning</td>
					<td><?php echo $certification->attendance_8 ? "Yes" : "No"; ?></td>
				</tr>
				<tr>
					<td></td>
					<td>Session 9, Day 5, Afternoon</td>
					<td><?php echo $certification->attendance_9 ? "Yes" : "No"; ?></td>
				</tr>
				<tr>
					<td></td>
					<td>Session 10, Day 6, Morning</td>
					<td><?php echo $certification->attendance_10 ? "Yes" : "No"; ?></td>
				</tr>

		<?php
	}
	?>
			</tbody>
		</table>
	<?php
}
add_action('genesis_entry_content', 'show_transcript');

genesis();
