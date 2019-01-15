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
	global $wpdb;
	$current_user = wp_get_current_user();
  $table_name  = $wpdb->prefix."progress";
	$original_status = 'active';
	$new_status = '';
	$user_id = '';
	$progresses = $wpdb->get_results("SELECT * FROM wp_progress;");
	// $progress_2 = $wpdb->get_results("SELECT * FROM ". $table_name ." WHERE status = 'suspended';");

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
		if(isset($_GET['user_id']) && $_GET['user_id'] !='') {
			$user_id = $_GET['user_id'];
		}
		if(isset($_GET['status']) && $_GET['status'] !='') {
			$new_status = $_GET['status'];
		}
		if(isset($_GET['user_id']) && $_GET['user_id'] !='' && isset($_GET['status']) && $_GET['status'] !='') {
		$wpdb->query( $wpdb->prepare("UPDATE $table_name
		  SET status = %s
		 WHERE user_id = %s", $new_status, $user_id)
		);
		}

}

//* Force full width content layout
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

add_action('genesis_after_header', 'add_home_page_widgets');
function add_home_page_widgets() {
  genesis_widget_area( 'home-widget-1', array(
		'before' => '<div id="home-widget-1" class="home-widget-1 widget-area"><div class="wrap">',
		'after'  => '</div></div>',
  ) );
  genesis_widget_area( 'home-widget-2', array(
		'before' => '<div id="home-widget-2" class="home-widget-2 widget-area"><div class="wrap">',
		'after'  => '</div></div>',
  ) );
  genesis_widget_area( 'home-widget-3', array(
		'before' => '<div id="home-widget-3" class="home-widget-3 widget-area"><div class="wrap">',
		'after'  => '</div></div>',
  ) );
  genesis_widget_area( 'home-widget-4', array(
		'before' => '<div id="home-widget-4" class="home-widget-4 widget-area"><div class="wrap">',
		'after'  => '</div></div>',
  ) );
  genesis_widget_area( 'home-widget-5', array(
		'before' => '<div id="home-widget-5" class="home-widget-5 widget-area"><div class="wrap">',
		'after'  => '</div></div>',
  ) );
}

genesis();
?>
