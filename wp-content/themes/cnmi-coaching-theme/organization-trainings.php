<?php

/*
 * This is a template for Organizations Training view
 * Template Name: Organizations Training

 */

//* Remove the entry meta in the entry header (requires HTML5 theme support)
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );
remove_action('genesis_after_header', 'eleven_online_add_hero_area');
remove_action( 'genesis_entry_content', 'genesis_do_post_content' );
add_filter( 'genesis_markup_site-inner', '__return_null' );
add_filter( 'genesis_markup_content-sidebar-wrap_output', '__return_null' );
add_filter( 'genesis_markup_content', '__return_null' );


add_action('genesis_entry_content', 'create_organizations_trainings');
//Create layout for the page
function create_organizations_trainings(){
  echo '<div class="organizations-trainings">';
  echo '<div class="training one-half first">';
  echo '<div class="training-text"><h3>Schedule a Training</h3></div>';
  echo '</div>';
  echo '<div class="training one-half">';
  echo '<div class="training-text"><h3>View Scheduled Trainings</h3></div></div>';
  echo '<div class="training one-half first">';
  echo '<div class="training-text first"><h3>Schedule a Training</h3></div></div>';
  echo '<div class="training one-half">';
  echo '<div class="training-text"><h3>View Scheduled Trainings </h3></div></div>';
}



genesis();
