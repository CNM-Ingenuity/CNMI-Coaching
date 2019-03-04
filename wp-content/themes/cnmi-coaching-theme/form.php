<?php
/*
 * Template Name: Form
 */
//* Add custom body class to the head
add_filter( 'body_class', 'login_body_class' );
function login_body_class( $classes ) {

	$classes[] = 'form-page';
	return $classes;

}

genesis();
