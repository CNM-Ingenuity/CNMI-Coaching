<?php
/*
 * Template Name: View User
 */

function elevenonline_view_user() {
	$linkAddress = '/student-progress?progress=';
	include(locate_template('partials/elements/certification-list.php'));	
}
add_action('genesis_entry_content', 'elevenonline_view_user');

genesis();