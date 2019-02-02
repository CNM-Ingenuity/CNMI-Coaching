<?php
/*
 * Template Name: Review Coaching Session
 */
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

//* Add custom body class to the head
add_filter( 'body_class', 'form_body_class' );
function form_body_class( $classes ) {
	
	$classes[] = 'progress-form-page';
	return $classes;
	
}

function show_session_review_form() {
	$sessionID = $_GET['session'];
	if($sessionID) {
		$breadcrumbs = [
			get_the_title() => '#'
		];
		include(locate_template('partials/elements/breadcrumbs.php'));	
		include(locate_template('partials/elements/top-matter.php'));
		
		get_template_part('partials/forms/coaching-session-review');
	} else {
		?>
			<p>Sorry, page not found.</p>
		<?php
	}
}
add_action('genesis_entry_content', 'show_session_review_form');

genesis();