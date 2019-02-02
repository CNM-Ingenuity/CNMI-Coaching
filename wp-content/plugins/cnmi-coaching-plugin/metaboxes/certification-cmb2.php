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
      'desc' => __('Number of hours', 'cmb2'),
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
  }
}

add_action('cmb2_admin_init', 'cnmi_register_certification_metabox');
