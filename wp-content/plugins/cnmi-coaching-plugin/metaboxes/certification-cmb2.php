<?php
/**
 * Hook in and add a metabox that only appears on 'Certifications'
 */

if(!function_exists('cnmi_register_certification_metabox')) {
  function cnmi_register_certification_metabox() {
    $prefix = '_cnmi_certification_metabox_';
    $cmb = new_cmb2_box(array(
      'id' => $prefix . 'metabox',
			'title' => 'Certification',
			'object_types' => array('certifications'), //Post type
			'show_names' => true, //show field names on the left
			'context' => 'normal',
			'priority' => 'high',
    ));
    $cmb->add_field( array(
      'name' => __('Hours', 'cmb2'),
      'desc' => __('Number of hours', 'cmb2'),
      'id' => $prefix . 'hours',
      'type' => 'text',
    ));
    $cmb->add_field( array(
      'name' => __('Training Type', 'cmb2'),
      'desc' => __('Number of hours', 'cmb2'),
      'id' => $prefix . 'training_type',
      'type' => 'text',
    ));
    $cmb->add_field( array(
      'name' => __('Transcripts', 'cmb2'),
      'desc' => __('Transcript of instruction', 'cmb2'),
      'id' => $prefix . 'transcript',
      'type' => 'text_url',
    ));
    //Not sure if this should be a file or a URL
    $cmb->add_field( array(
      'name' => __('Assessment', 'cmb2'),
      'desc' => __('Assessment', 'cmb2'),
      'id' => $prefix . 'assessment',
      'type' => 'text_url',
    ));
    $cmb->add_field( array(
      'name' => __('Submit Coaching Session', 'cmb2'),
      'desc' => __('Link to view to submit coaching session', 'cmb2'),
      'id' => $prefix . 'submit_coaching_session',
      'type' => 'text_url',
    ));
    $cmb->add_field( array(
      'name' => __('Submit Letters of Reference', 'cmb2'),
      'desc' => __('Link to view to submit letters of reference', 'cmb2'),
      'id' => $prefix . 'letters_of_reference',
      'type' => 'text_url',
    ));
    $cmb->add_field( array(
      'name' => __('Coach End User Agreement', 'cmb2'),
      'desc' => __('Link to Coach End User Agreement', 'cmb2'),
      'id' => $prefix . 'coach_end_user_agreement',
      'type' => 'text_url',
    ));
    $cmb->add_field( array(
      'name' => __('Training', 'cmb2'),
      'desc' => __('Link to Training', 'cmb2'),
      'id' => $prefix . 'training',
      'type' => 'text_url',
      'repeatable' => true,
    	'text' => array(
        'add_row_text' => 'Add Training',
      ),
    ));
  }
}

add_action('cmb2_admin_init', 'cnmi_register_certification_metabox');