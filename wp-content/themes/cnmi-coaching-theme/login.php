<?php
/*
 * Template Name: Login
 */
//* Add custom body class to the head
add_filter( 'body_class', 'login_body_class' );
function login_body_class( $classes ) {
	
	$classes[] = 'form-page';
	return $classes;
	
}

add_action('genesis_entry_content', 'add_login_form_to_page');
function add_login_form_to_page() {
	if($_GET['login'] === 'failed') {
		?>
			<p style='text-align: center; color: white;'>We couldn't log you in with those credentials. Please try again.</p>
		<?php
	}
	?>
		<form name="loginform" id="loginform" action="<?php echo wp_login_url() . "?wpe-login=true"; ?>" method="post">
			<p>
				<input id="user_login" type="text" size="20" value="" name="log" placeholder="Email">
			</p>
			<p>
				<input id="user_pass" type="password" size="20" value="" name="pwd" placeholder="Password">
			</p>

			<p>
				<input id="wp-submit" type="submit" value="Login" name="wp-submit">
			</p>
			<input type="hidden" value="1" name="testcookie">
			<a class='recover-password' href='/my-account/lost-password/'>Recover Password</a>
		</form>
	<?php
}

genesis();