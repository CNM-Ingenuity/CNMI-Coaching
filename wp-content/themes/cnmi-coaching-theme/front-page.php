<?php
/**
 * This file adds the Home Page to the Owner Direct Theme.
 *
 */

add_action( 'genesis_meta', 'starter_theme_home_genesis_meta' );
function starter_theme_home_genesis_meta(){

}

//* Remove the default Genesis loop (don't do the posts)
remove_action( 'genesis_loop', 'genesis_do_loop' );


add_action('genesis_after_header', 'get_progress');
function get_progress(){
	// need to update db before getting if there is a change
	if(isset($_GET['user_id']) && $_GET['user_id'] !='') {
			$user_id = $_GET['user_id'];
		}
		if(isset($_GET['status']) && $_GET['status'] !='') {
			$new_status = $_GET['status'];
		}
		if(isset($_GET['user_id']) && $_GET['user_id'] !='' && isset($_GET['status']) && $_GET['status'] !='') {
		CNMI_Progress::update_status_by_user_id($user_id, $new_status);
	}
	$progresses = CNMI_Progress::get_all_progress();

	echo '<div class="one-half first"><form id="test-form" action="/">';
	echo '<div class="one-half first"><label for="user_id">user ID</label>';
	echo '<input id="test-form-user-id" label="User ID" name="user_id" type="number" class="user_id"></div>';
	echo '<div class="one-half"><label for="status">Select Status</label>';
	echo '<select name="status" id="test-form-status" class="one-half"><option value="active">Active</option>';
	echo '<option value="suspended">Suspended</option></select></div><input type="submit" value="Change"></form></div>';

	//output progress
	echo '<div class="one-half"><h2> Progress</h2>';
	// print_r($progresses);
	echo '<ul>';
	$count = 0;
	foreach ($progresses as $progress) {
			$count++;
			$progress_user_id = $progress->user_id;
			$progress_coach_id = $progress->coach_id;
			$progress_status = $progress->status;
			echo '<li> <p>  User ID: '. $progress_user_id . '  Coach ID: ' . $progress_coach_id . '  Status: ' . $progress_status .'</p></li>';
	}
	echo '</ul></div>';
}

//* Force full width content layout
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

add_action('genesis_before_footer', 'add_event_section', 8);
function add_event_section() {
 	// upcoming events section
	$tz = new DateTimeZone('America/Denver');
	$start_date = new DateTime();
	
	// this is the latest events section
	$events = tribe_get_events( array(
		'start_date'     => $start_date->format('Y-m-d 00:00:00'),
		'eventDisplay'   => 'custom',
		'posts_per_page' => 4
	));
	
	echo "<div class='upcoming-events-section content'><div class='wrap'>";
	echo "<h1>Training Calendar</h1>";
	$count = 0;
	foreach($events as $event) {
		$eventStartTime = new DateTime($event->EventStartDate, $tz);
		$eventEndTime = new DateTime($event->EventEndDate, $tz);
		if($count % 2 === 0) {
			echo "<div class='one-half first'>";
		} else {
			echo "<div class='one-half'>";
		}
		echo "<div>";
		echo $eventStartTime->format('M d');
		echo "</div><div><a href='" . get_permalink($event->ID) . "'>";
		echo $event->post_title;
		echo "</a>";
		echo "<p>";
		echo $eventStartTime->format('g:i a');
		echo " - ";
		echo $eventEndTime->format('g:i a');
		echo "</p></div></div>";
		$count++;
	}
	echo "<p><a href='/events'>VIEW MORE</a></p>";
	echo "</div></div>";
}

genesis();
?>
