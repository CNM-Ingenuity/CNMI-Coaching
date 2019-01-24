<?php

/*
 * This is a template for Organizations Training view
 * Template Name: My Scheduled Trainings

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


add_action('genesis_entry_content', 'create_organizations_scheduled_trainings');
//Create layout for the page
function create_organizations_scheduled_trainings(){
  get_template_part('partials/top-matter');
  echo '<div class="organizations-scheduled-trainings">';
  // $organizers = tribe_get_organizers();
  // $events = tribe_get_events();
  // var_dump($events);
  $trainings_array = array(
    ['name' => 'Event 1',
      'students' => 4,
      'date' => '12/20/2019'
    ],
    ['name' => 'Event 2',
      'students' => 14,
      'date' => '3/15/2019'
    ],
    ['name' => 'Event 3',
      'students' => 17,
      'date' => '06/20/2019'
    ]
  );
  foreach ($trainings_array as $training)
  {
      $event_name = $training['name'];
      $students = $training['students'];
      $date = $training ['date'];
      echo '<div class="training">';
      echo '<div class="training-title"><p>'. $event_name .'</p></div>';
      echo '<div class="training-students"><p>Students:'. $students .'</p></div>';
      echo '<div class="training-date"><p>Date:'. $date .'</p></div></div>';

  }

}



genesis();
