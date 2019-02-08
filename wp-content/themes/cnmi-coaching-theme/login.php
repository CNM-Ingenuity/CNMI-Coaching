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
	?>
		<form name="loginform" id="loginform" action="<?php echo site_url( '/wp-login.php' ) . '?wpe-login=true'; ?>" method="post">
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