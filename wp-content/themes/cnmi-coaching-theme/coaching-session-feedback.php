<?php
/*
 * Template Name: Coaching Sessions
 */
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

function show_my_sessions() {
	$sessionID = $_GET['session_id'];
	if($sessionID) {
		$session = CNMI_Coaching_Session::get_coaching_session_by_id_student_access($sessionID);
		$progressID = $session->progress_id;
		$certification = CNMI_Progress::get_progress_by_id($progressID, false);
		$eventID = $certification->event_id;
		$eventTypeForBreadCrumbs = CNMI_Events::get_event_type($eventID);
		$breadcrumbs = [
			"My Certifications" => "/my-certifications",
			$eventTypeForBreadCrumbs => "/my-certification?certification=" . $progressID,
			"Coaching Sessions" => "/coaching-sessions?certification=" . $progressID,
			get_the_title() => "#"
		];
		include(locate_template('partials/elements/breadcrumbs.php'));	
		include(locate_template('partials/elements/top-matter.php'));
		$date = new DateTime($session->date);
		$user = get_user_by('id', $session->reviewer_id);
		?>
			<h6>Reviewer: <?php echo $user->first_name . ' ' . $user->last_name; ?></h6>
			<h6>Date Reviewed: <?php echo $date->format('m/d/Y'); ?></h6>
			<h6>Open <a target="_blank" href="<?php echo $session->url; ?>">File</a></h6>
			<br/>
		<?php
		$fieldsGroups = [
			"Setting the Foundation and Co-Creating the Relationship" => [
				"establish_trust" => "Establishes trust with the person being coached by creating a safe, supportive coaching partnership.",
				"effective_assessments" => "Uses effective assessments & tools to promote self- discovery and clarity, and to provoke new ideas in alignment with the person’s desired outcomes.",
				"respect_decisions" => "Respects person's decisions & goals without judgmentby accepting the person's expressions of goals, values, feelings & beliefs about what is important & notimportant."
			],
			"Communicating Effectively" => [
				"listen_focus" => "Listening: Focuses completely on the person being coached using clear & direct communication in language that is appropriate for  providing perspective& feedback.",
				"asks_powerful" => "Asks powerful open-ended questions that inspire insight, clarity  & challenge assumptions.",
				"asks_motivate" => "Asks questions that motivate commitment & action toward what the person wants to accomplish."
			],
			"Facilitating Learning" => [
				"helps_discover" => "Helps the person to discover for themselves the new thoughts, beliefs, strengths, perceptions, emotions, etc.",
				"helps_focus" => "Helps the person to focus on desired outcomes &create awareness about significant and trivial situations or behaviors."
			],
			"Designing Actions and Managing Progress" => [
				"co_creates_action" => "Co-creates an action plan with goals that are specific,attainable,  relevant  and  measurable  and  aligned  with dates.",
				"prepares_managing_progress" => "Prepares for managing progress & accountability. Co- Defines methods of follow- up & communication."
			]
		];
		foreach($fieldsGroups as $title => $fields) {
			?>
				<h3><?php echo $title; ?></h3>
			<?php
			foreach($fields as $field => $label) {
				?>
					<h5><?php echo $label; ?></h5>
					<h6>Rating:</h6>
					<p><?php echo ucfirst($session->{$field . "_vc"}); ?></p>
					<h6>Comments:</h6>
					<p><?php echo $session->{$field . "_text"}; ?></p>
					<hr/>

				<?php
			} 	
			?>
				<br/>
				<br/>
				<br/>
			<?php
		}
	}
}
add_action('genesis_entry_content', 'show_my_sessions');

genesis();