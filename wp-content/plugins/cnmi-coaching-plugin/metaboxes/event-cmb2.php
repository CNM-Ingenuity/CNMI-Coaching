<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if(!function_exists('cnmi_register_event_metabox')) {
  function cnmi_register_event_metabox() {
    $prefix = '_cnmi_event_metabox_';
    $cmb = new_cmb2_box(array(
      'id' => $prefix . 'metabox',
			'title' => 'Trainer and Organizations',
			'object_types' => array('tribe_events'), //Post type
			'show_names' => true, //show field names on the left
			'context' => 'normal',
			'priority' => 'high',
    ));

    $cmb->add_field(array(
        'name'    => __( 'Select Coach Trainer', 'cmb2' ),
        'desc'    => __( 'Select coach trainer for this event', 'cmb2' ),
        'id'      => $prefix . 'user_multicheckbox',
        'type'    => 'multicheck',
        'options' => cmb2_get_user_options( array( 'fields' => array( 'user_login' ) ) ),
    ));

    $cmb->add_field(array(
        'name'    => __( 'Select Licensing Organization', 'cmb2' ),
        'desc'    => __( '(optional)', 'cmb2' ),
        'id'      => $prefix . 'licensing_org_multicheckbox',
        'type'    => 'select',
        'options' => cmb2_get_licensing_orgs_options( array( 'fields' => array( 'user_login' ) ) ),
    ));

    $cmb->add_field(array(
        'name'    => __( 'Select Contracting Organization', 'cmb2' ),
        'desc'    => __( '(optional)', 'cmb2' ),
        'id'      => $prefix . 'contracting_org_multicheckbox',
        'type'    => 'select',
        'options' => cmb2_get_contracting_orgs_options( array( 'fields' => array( 'user_login' ) ) ),
    ));

    $cmb->add_field(array(
        'name'    => __( 'Evaluation', 'cmb2' ),
        'desc'    => __( 'Link for evaluation', 'cmb2' ),
        'id'      => $prefix . 'evaluation_url',
        'type'    => 'text_url'
    ));
  }
}
add_action('cmb2_admin_init', 'cnmi_register_event_metabox');



function cmb2_get_user_options( $query_args ) {

    $args = wp_parse_args( $query_args, array(

        'fields' => array( 'user_login' ),

    ) );

    $users = get_users(  );

    $user_options = array();
    if ( $users ) {
        foreach ( $users as $user ) {
          $user_id = $user->ID;
          if(wc_memberships_is_user_active_member( $user_id, 'certified-coach-trainer' )){
            $user_options[ $user->ID ] = $user->user_login;
          }
        }
    }

    return $user_options;
}

function cmb2_get_licensing_orgs_options( $query_args ) {

    $args = wp_parse_args( $query_args, array(

        'fields' => array( 'user_login' ),

    ) );

    $users = get_users(  );

    $user_options = array(null => 'Select One');
    if ( $users ) {
        foreach ( $users as $user ) {
          $user_id = $user->ID;
          if(wc_memberships_is_user_active_member( $user_id, 'licensed-org' )){
            $user_options[ $user->ID ] = $user->user_login;
          }
        }
    }

    return $user_options;
}

function cmb2_get_contracting_orgs_options( $query_args ) {

    $args = wp_parse_args( $query_args, array(

        'fields' => array( 'user_login' ),

    ) );

    $users = get_users(  );

    $user_options = array(null => 'Select One');
    if ( $users ) {
        foreach ( $users as $user ) {
          $user_id = $user->ID;
          if(wc_memberships_is_user_active_member( $user_id, 'contracting-organization' )){
            $user_options[ $user->ID ] = $user->user_login;
          }
        }
    }

    return $user_options;
}
