<?php
if ( 
	isset( $_POST['register_coach_in_training'] ) 
	&& ! wp_verify_nonce( $_POST['register_coach_in_training'], 'register_coach_in_training' ) 
) {
		print 'Sorry, your nonce did not verify.';
		exit;
} else {
	if(
		isset($_POST['email']) && $_POST['email'] !='' 
		&& 
		isset($_POST['first_name']) && $_POST['first_name'] !='' 
		&&
		isset($_POST['last_name']) && $_POST['last_name'] !='' 
	) {
		$first_name = $_POST['first_name'];
		$last_name = $_POST['last_name'];
		$user_email = $_POST['email'];
		$user_data = array(
			'user_pass' => '',
			'user_login' => $user_email,
			'user_email' => $user_email,
			'first_name' => $first_name,
			'last_name' => $last_name,
			'role' => 'subscriber'
		);
		$event_id = $_POST['event_id'];
		if(! email_exists($user_email)){
			$user_id = wp_insert_user($user_data);
			if($user_id) {
				?>
					<p class='success-message'>Trainer successfully created.</p>
				<?php
			} else {
				?>
					<p class='error-message'>Something went wrong, please try again.</p>
				<?php
			}
			// Send notification email. We will probably want to customize the message
			$notification = wp_new_user_notification($user_id, null , 'user');
			// attach the coach in trainer membership
			$membershipArgs = array(
			// Enter the ID (post ID) of the plan to grant at registration
				'plan_id' => 407,
				'user_id' => $user_id,
			);
			wc_memberships_create_user_membership( $membershipArgs );
			// add progress with event id
			global $wpdb;
			$table_name  = $wpdb->prefix.'progress';
			$wpdb->insert(
				$table_name, 
				array(
					'user_id' => $user_id,
					'event_id' => $event_id
				),
				array('%d','%d')
			);
		} else {
			?>
				<p class='error-message'>This user already has an account.</p>
			<?php
		}
	} else if (isset($_POST['email']) && $_POST['email'] !='') {
		?>
			<p class='error-message'>Some information is missing, please make sure your form is complete.</p>
		<?php
	}
}
?>
<form id="register-coach-in-training" action="" method="POST">
	<label for="first_name">First Name</label>
	<input label="first_name" name="first_name" required>

	<label for="last_name">Last Name</label>
	<input label="last_name" name="last_name" required>

	<label for="email">Email</label>
	<input label="email" name="email" required type="email">

	<label for="event_id">Which Training</label>
	<select name="event_id">
		<option value="590">Academic Coach Training</option>
		<option value="573">Financial Coach Training	</option>
	</select>


	<input type="submit" value="Register" name="submit">
	<?php wp_nonce_field( 'register_coach_in_training', 'register_coach_in_training' ); ?>
</form>
<script>
	jQuery("#register-coach-in-training").validate();
</script>

