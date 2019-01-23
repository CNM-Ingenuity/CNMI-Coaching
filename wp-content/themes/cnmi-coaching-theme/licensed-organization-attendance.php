<?php

/*
 * This is a template for Organizations Training view
 * Template Name: Attendance

 */

//* Remove the entry meta in the entry header (requires HTML5 theme support)
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );
remove_action('genesis_after_header', 'eleven_online_add_hero_area');
remove_action( 'genesis_entry_content', 'genesis_do_post_content' );
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
add_filter( 'genesis_markup_site-inner', '__return_null' );
add_filter( 'genesis_markup_content-sidebar-wrap_output', '__return_null' );
add_filter( 'genesis_markup_content', '__return_null' );


add_action('genesis_entry_content', 'create_organizations_attendance');
//Create layout for the page
function create_organizations_attendance(){
  get_template_part('partials/top-matter');
  echo '<div class="attendance">';
  // $organizers = tribe_get_organizers();
  // $events = tribe_get_events();
  // var_dump($events);
  echo '<div class="training">';
  echo '<div class="training-title"><p>Training Title Goes Here</p></div>';
  echo '<div class="training-students"><p>Students: 7</p></div>';
  echo '<div class="training-date"><p>12/24/2019</p></div></div>';
  echo '<div class="training">';
  echo '<div class="training-title"><p>Training Title Goes Here</p></div>';
  echo '<div class="training-students"><p>Students: 7</p></div>';
  echo '<div class="training-date"><p>12/24/2019</p></div>';
  echo '</div>';
}



genesis();
