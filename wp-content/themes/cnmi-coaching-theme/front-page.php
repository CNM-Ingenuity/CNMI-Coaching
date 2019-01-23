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
	?>
		<div class="wrap">
			<div class="one-half first">
				<?php get_template_part( 'partials/progress-form' ); ?>
				<?php get_template_part( 'partials/file-form' ); ?>
			</div>
	<?php
	$progresses = CNMI_Progress::get_progress_by_id(1);
	//output progress
	echo '<div class="one-half"><h2> Progress</h2>';
	// print_r($progresses);
	echo '<a class="button">Button Test</a><ul>';
	$count = 0;
	foreach ($progresses as $progress) {
			$count++;
			$progress_user_id = $progress->user_id;
			$progress_coach_id = $progress->coach_id;
			$progress_status = $progress->status;
			echo '<li> <p>  User ID: '. $progress_user_id . '  Coach ID: ' . $progress_coach_id . '  Status: ' . $progress_status .'</p></li>';
			if(isset($progress->files)) {
				foreach ($progress->files as $file) {
					?>
					<li><img src="<?php echo $file->url; ?>"/></li>
					<?php
				}
			}
	}
	echo '</ul></div></div>';
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
			echo "<div class='one-half first event-block'>";
		} else {
			echo "<div class='one-half event-block'>";
		}
		echo "<div class='event-date'>";
		echo $eventStartTime->format('M d');
		echo "</div><div class='event-details'><h5>";
		echo $event->post_title;
		echo "</h5><p>";
		echo $eventStartTime->format('g:i a');
		echo " - ";
		echo $eventEndTime->format('g:i a');
		echo "</p><a class='button secondary' href='" . get_permalink($event->ID) . "'>Sign Up</a></div></div>";
		$count++;
	}
	echo "<p class='view-more-events-container'><a class='view-more-events' href='/events'>VIEW MORE</a></p>";
	echo "</div></div>";
}

genesis();
?>
