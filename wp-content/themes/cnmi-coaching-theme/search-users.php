<?php
/*
 * Template Name: Search Users
 */

function search_users() {
	if( current_user_can('administrator')) {
		if ( 
			isset( $_POST['search_users'] ) 
			&& ! wp_verify_nonce( $_POST['search_users'], 'search_users' ) 
		) {
				print 'Sorry, your nonce did not verify.';
				exit;
		} else {
			$users = [];
			$search_term = "";
			if(isset($_POST['usersearch']) && $_POST['usersearch'] != '') {
				$search_term = sanitize_text_field( $_POST['usersearch'] );
				$user_query = new WP_User_Query( 
					array( 
						'role' => 'Subscriber',
						'search' => '*' . $search_term . '*',
						'meta_query' => array(
							'relation' => 'OR',
							array(
								'key' => 'first_name',
								'value' => $search_term,
								'compare' => 'LIKE'
							),
							array(
								'key' => 'last_name',
								'value' => $search_term,
								'compare' => 'LIKE'
							)
						)
					) );
				$users = $user_query->get_results();
			}
			?>

			<form method="post">
				<label for="usersearch">First Name, Last Name or Email</label>
				<input label="usersearch" name="usersearch" value="<?php echo $search_term; ?>" placeholder="First Name, Last Name or Email">
				<br/>
				<br/>
				<input type="submit" value="Search">
				<?php wp_nonce_field( 'search_users', 'search_users' ); ?>
			</form>
			<br/>

			<?php
			if(count($users) > 0) {
				?>
					<table>
						<tr>
							<th>Name</th>
							<th>Email</th>
							<th>View</th>
						</tr>
					<?php
					foreach ($users as $user) {
						?>
							<tr>
								<td><?php echo $user->first_name . ' ' . $user->last_name; ?></td>
								<td><?php echo $user->user_email; ?></td>
								<td><a href="/view-coach-certifications?user=<?php echo $user->ID; ?>" class="button">View</a></td>
							</tr>
						<?php
					}
					?>
					</table>
				<?php
			} else if ($search_term) {
				?>
					<p>Sorry, no results.</p>
				<?php
			}

		}
	} else {
		?>
			<p>Sorry, you don't have access to this page.</p>
		<?php
	}
}
add_action('genesis_entry_content', 'search_users');

genesis();
