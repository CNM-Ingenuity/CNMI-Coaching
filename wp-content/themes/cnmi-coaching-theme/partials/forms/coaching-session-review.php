<?php
if ( 
	isset( $_POST['id'] ) 
	&& ! wp_verify_nonce( $_POST['review_session'], 'review_session' ) 
) {
		print 'Sorry, your nonce did not verify.';
		exit;
} else {
	if(
		isset($_POST['id']) && $_POST['id'] !=''
		&&
		isset($_POST['session_accepted']) && $_POST['session_accepted'] !=''
	) {
			CNMI_Coaching_Session::review_session(
				$_POST['id'], 
				$_POST['establish_trust_vc'],
				$_POST['establish_trust_text'],
				$_POST['effective_assessments_vc'],
				$_POST['effective_assessments_text'],
				$_POST['respect_decisions_vc'],
				$_POST['respect_decisions_text'],
				$_POST['listen_focus_vc'],
				$_POST['listen_focus_text'],
				$_POST['asks_powerful_vc'],
				$_POST['asks_powerful_text'],
				$_POST['asks_motivate_vc'],
				$_POST['asks_motivate_text'],
				$_POST['helps_discover_vc'],
				$_POST['helps_discover_text'],
				$_POST['helps_focus_vc'],
				$_POST['helps_focus_text'],
				$_POST['co_creates_action_vc'],
				$_POST['co_creates_action_text'],
				$_POST['prepares_managing_progress_vc'],
				$_POST['prepares_managing_progress_text'], 
				$_POST['session_accepted']
			);
	}
}
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

?>
<form id="coaching-session-review-form" action="" method="POST" enctype="multipart/form-data">
	<input name="id" type="hidden" required value="<?php echo $_GET['session']; ?>">

	<?php 
		foreach($fieldsGroups as $title => $fields) {
			?>
				<h3><?php echo $title; ?></h3>
			<?php
			foreach($fields as $field => $label) {
				?>
					<label for="<?php echo $field; ?>_vc"><?php echo $label; ?></label>

					<select name="<?php echo $field; ?>_vc" required>
						<option>Select One</option>
						<option value="strong">Strong</option>
						<option value="good">Good</option>
						<option value="improve">Improve</option>
					</select>
	
					<label for="<?php echo $field; ?>_text">Comments</label>
					<textarea name="<?php echo $field; ?>_text" required></textarea>
				<?php
			} 	
		}
	?>

	<label for="session_accepted">Session Accepted</label>
	<select name="session_accepted" required>
		<option value="1">Yes</option>
		<option value="0">No</option>
	</select>
	
	<input type="submit" value="Review Session" name="submit">
	<?php wp_nonce_field( 'review_session', 'review_session' ); ?>
</form>
<script>
	jQuery("#coaching-session-review-form").validate();
</script>