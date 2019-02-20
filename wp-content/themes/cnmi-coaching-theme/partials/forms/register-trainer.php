<?php
if ( 
	isset( $_POST['register_trainer'] ) 
	&& ! wp_verify_nonce( $_POST['register_trainer'], 'register_trainer' ) 
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
				'plan_id' => 411,
				'user_id' => $user_id,
			);
			wc_memberships_create_user_membership( $membershipArgs );
			// attach the user to the licensing org
			update_user_meta( $user_id, 'licensing_org', get_current_user_id() );
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
<form id="register-trainer" action="" method="POST">
	<label for="first_name">First Name</label>
	<input label="first_name" name="first_name" required>

	<label for="last_name">Last Name</label>
	<input label="last_name" name="last_name" required>

	<label for="email">Email</label>
	<input label="email" name="email" required type="email">


	<input type="submit" value="Register" name="submit">
	<?php wp_nonce_field( 'register_trainer', 'register_trainer' ); ?>
</form>
<script>
	jQuery("#register-trainer").validate();
</script>

