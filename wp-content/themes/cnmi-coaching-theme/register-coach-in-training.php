<?php
/*
 * Template Name: Register Coach in Training
 */


//* Add custom body class to the head
add_filter( 'body_class', 'form_body_class' );
function form_body_class( $classes ) {
	
	$classes[] = 'progress-form-page';
	return $classes;
	
}

function register_coach_in_training() {
	get_template_part('partials/forms/register-coach-in-training');
}
add_action('genesis_entry_content', 'register_coach_in_training');

genesis();
