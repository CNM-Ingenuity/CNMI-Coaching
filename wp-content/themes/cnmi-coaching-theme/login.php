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

add_action('genesis_after_entry', 'add_login_form_to_page');
function add_login_form_to_page() {
	$redirect_to = '/';
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

			<input type="hidden" value="<?php echo esc_attr( $redirect_to ); ?>" name="redirect_to">
			<input type="hidden" value="1" name="testcookie">
		</form>
	<?php
}

genesis();