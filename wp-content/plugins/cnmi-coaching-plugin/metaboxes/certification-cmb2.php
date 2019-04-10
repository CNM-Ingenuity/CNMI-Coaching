<?php
/**
 * Hook in and add a metabox that only appears on 'Certifications'
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

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
      'desc' => __('Online, in person, something else.', 'cmb2'),
      'id' => $prefix . 'training_type',
      'type' => 'text',
    ));
    //Not sure if this should be a file or a URL
    $cmb->add_field( array(
      'name' => __('Assessment', 'cmb2'),
      'desc' => __('Assessment', 'cmb2'),
      'id' => $prefix . 'assessment',
      'type' => 'text_url',
    ));
    $cmb->add_field( array(
      'name' => __('Certification Download', 'cmb2'),
      'desc' => __('File that Certified Coaches can download', 'cmb2'),
      'id' => $prefix . 'certification_download',
      'type' => 'file',
    ));
    $cmb->add_field( array(
      'id'          => $prefix . 'training_resource_group',
      'type'        => 'group',
      'repeatable'  => true,
      'options'     => array(
          'group_title'   => 'Training Resource #{#}',
          'add_button'    => 'Add Another Resource',
          'remove_button' => 'Remove Resource',
          'sortable' => true,
      ),
    ));
    $cmb->add_group_field( '_cnmi_certification_metabox_training_resource_group', array(
        'name'             => 'Resource Name',
        'id'               => 'name',
        'type'             => 'text_medium',
    ) );
    // Field: Character Type
    $cmb->add_group_field( '_cnmi_certification_metabox_training_resource_group', array(
        'name'             => 'Resource Link',
        'id'               => 'file',
        'type'             => 'file'
    ) );
    $cmb->add_field( array(
      'name' => __('Text above Coaching Session Upload', 'cmb2'),
      'desc' => __('This is where you write the text that goes above the coaching session upload.', 'cmb2'),
      'id' => $prefix . 'coaching_session_upload_text',
      'type' => 'textarea',
    ));
    $cmb->add_field( array(
      'name' => __('Text above Letter Upload', 'cmb2'),
      'desc' => __('This is where you write the text that goes above the letter upload.', 'cmb2'),
      'id' => $prefix . 'letter_upload_text',
      'type' => 'textarea',
    ));
    $cmb->add_field( array(
      'name' => __('Text above Track Coaching Hours', 'cmb2'),
      'desc' => __('This is where you write the text that goes above the coaching upload section.', 'cmb2'),
      'id' => $prefix . 'track_coaching_hours_text',
      'type' => 'textarea',
    ));
    $cmb->add_field( array(
      'name' => __('Text above Coaching End User Agreement ', 'cmb2'),
      'desc' => __('This is where you write the text that goes above the letter upload.', 'cmb2'),
      'id' => $prefix . 'coaching_end_user_agreement_text',
      'type' => 'textarea',
    ));
  }
}

add_action('cmb2_admin_init', 'cnmi_register_certification_metabox');
