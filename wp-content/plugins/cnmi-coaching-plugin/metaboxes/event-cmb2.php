<?php

if(!function_exists('cnmi_register_event_metabox')) {
  function cnmi_register_event_metabox() {
    $prefix = '_cnmi_event_metabox_';
    $cmb = new_cmb2_box(array(
      'id' => $prefix . 'metabox',
			'title' => 'Trainer',
			'object_types' => array('tribe_events'), //Post type
			'show_names' => true, //show field names on the left
			'context' => 'normal',
			'priority' => 'high',
    ));

    $cmb->add_field(array(
        'name'    => __( 'Select Users', 'cmb2' ),
        'desc'    => __( 'field description (optional)', 'cmb2' ),
        'id'      => $prefix . 'user_multicheckbox',
        'type'    => 'multicheck',
        'options' => cmb2_get_user_options( array( 'fields' => array( 'user_login' ) ) ),
    ));
    $cmb->add_field(array(
        'name'    => __( 'Requirements', 'cmb2' ),
        'desc'    => __( 'Certification Requirements', 'cmb2' ),
        'id'      => $prefix . 'requirements',
        'type'    => 'text',
        'repeatable' => 'true'
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
