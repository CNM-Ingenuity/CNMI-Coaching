<?php

/*
 * This is a custom post type (certifications) single
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



add_action('genesis_entry_content', 'create_certification_single');
function create_certification_single(){
  global $current_user;
  $current_user = wp_get_current_user();
  $display_name = $current_user->display_name;
  $id = get_the_ID();
  $title = get_the_title();
  $meta = get_post_meta(get_the_ID(), '_cnmi_certification_metabox_', true );
  $content = get_the_content();
  $transcript = get_post_meta(get_the_ID(), '_cnmi_certification_metabox_transcript', true );
  $assessment = get_post_meta(get_the_ID(), '_cnmi_certification_metabox_assessment', true );
  $submit_coaching_session = get_post_meta(get_the_ID(), '_cnmi_certification_metabox_submit_coaching_session', true );
  $letters_of_reference = get_post_meta(get_the_ID(), '_cnmi_certification_metabox_leters_of_reference', true );
  $coach_end_user_agreement = get_post_meta(get_the_ID(), '_cnmi_certification_metabox_coach_end_user_agreement', true );
  $hours = get_post_meta(get_the_ID(), '_cnmi_certification_metabox_hours', true );
  $training_type = get_post_meta(get_the_ID(), '_cnmi_certification_metabox_training_type', true );
  //certified coach trainer layout
  echo '<div class="certification-single">';
  echo '<div class="certification-description">';
  echo $content;
  echo '</div>';
  echo '<div class="certification-links"><a class="button transcript" href="'.$transcript.'">View Transcript<img src="/wp-content/uploads/2019/01/download-arrow.png"></a><a class="button training" href="'.$transcript.'">View Training Record<img src="/wp-content/uploads/2019/01/eye.png"></div>';

}


genesis();
