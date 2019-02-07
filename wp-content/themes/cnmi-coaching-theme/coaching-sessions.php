<?php
/*
 * Template Name: Coaching Session Feedback
 */
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

function show_my_sessions() {
	$progressID = $_GET['certification'];
	if($progressID) {
		$certification = CNMI_Progress::get_progress_by_id($progressID, false);
		$eventID = $certification->event_id;
		$eventTypeForBreadCrumbs = CNMI_Events::get_event_type($eventID);
		$breadcrumbs = [
			"My Certifications" => "/my-certifications",
			$eventTypeForBreadCrumbs => "/my-certification?certification=" . $progressID,
			get_the_title() => "#"
		];
		include(locate_template('partials/elements/breadcrumbs.php'));	
		include(locate_template('partials/elements/top-matter.php'));
		$sessions = CNMI_Coaching_Session::get_coaching_sessions_by_progress_id($certification->id);

		?>
			<table>
				<tr>
					<th></th>
					<th>Status</th>
					<th>Actions</th>
				</tr>
		<?php
		$count = 0;
		foreach ($sessions as $coaching_session) {
			$count++;
				?>
					<tr>
						<td>Session <?php echo $count; ?></td>
						<td><?php echo $coaching_session->reviewer_id ? "Reviewed" : "Needs Review"; ?></td>
						<td>
							<?php 
								if($coaching_session->reviewer_id) {
									?>
										<a class="button" href="coaching-session-details?session_id=<?php echo $coaching_session->id; ?>">View Feedback</a></td>
									<?php
								}
							?>
					</tr>
				<?php
		}
		?>
			</table>

		<?php
	}
}
add_action('genesis_entry_content', 'show_my_sessions');

genesis();