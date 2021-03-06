<?php
/*
 * Custom Class to deal with getting events
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CNMI_Events {

    public function __construct() {

    }

    public static function get_event_type($id) {
        $categories = wp_get_object_terms($id, 'tribe_events_cat');
        if(isset($categories[0])) {
            return $categories[0]->name;
        } else {
            return "Unknown Certification";
        }
    }

    public static function get_event_trainer($id) {
        $users = get_post_meta(
            $id,
            '_cnmi_event_metabox_user_multicheckbox',
            true
        );
        if($users) {
            $user = get_user_by('id', $users[0]);
            return $user->first_name . ' ' . $user->last_name;
        } else {
            return false;
        }
    }

    public static function get_event_start_date($id) {
        $date = get_post_meta(
            $id,
            '_EventStartDate',
            true
        );
        if($date) {
            $tz = new DateTimeZone('America/Denver');
            return new DateTime($date, $tz);
        } else {
            return false;
        }
    }

    public static function get_event_post_name($id) {
      $post = get_post($id);
      if($post)
      {
        $slug = $post->post_name;
        return $slug;
      } else {
        return false;
      }
    }

    public static function get_event_post_title($id) {
      $post = get_post($id);
      if($post)
      {
        $title = $post->post_title;
        return $title;
      } else {
        return false;
      }
    }

    public static function get_events_by_coach_id($coach_id) {
        $args = array(
            'post_type' => 'tribe_events',
            'meta_query' => array(
                array(
                    'key' => '_cnmi_event_metabox_user_multicheckbox',
                    'value' => sprintf(':"%s";', intval( $coach_id )),
                    'compare' => 'LIKE'
                )
            )
        );
        return get_posts($args);
    }

    public static function get_events_by_licensing_org_id($licensing_org_id) {
        $args = array(
            'post_type' => 'tribe_events',
            'meta_query' => array(
                array(
                    'key' => '_cnmi_event_metabox_licensing_org_multicheckbox',
                    'value' => intval( $licensing_org_id )
                )
            )
        );
        return get_posts($args);
    }

    public static function get_events_by_contracting_org_id($contracting_org_id) {
        $args = array(
            'post_type' => 'tribe_events',
            'meta_query' => array(
                array(
                    'key' => '_cnmi_event_metabox_contracting_org_multicheckbox',
                    'value' => intval( $contracting_org_id )
                )
            )
        );
        return get_posts($args);
    }

    // used to see if a coach has access to progress
    public static function get_events_by_id_and_coach_id($id, $coach_id) {
        $args = array(
            'post_type' => 'tribe_events',
            'include' => [intval( $id )],
            'meta_query' => array(
                array(
                    'key' => '_cnmi_event_metabox_user_multicheckbox',
                    'value' => sprintf(':"%s";', intval( $coach_id )),
                    'compare' => 'LIKE'
                )
            )
        );
        $events = get_posts($args);
        if(count($events) > 0) {
            return true;
        } else {
            return false;
        }
    }



    public static function get_event_evaluation_link($id) {
      $evaluationLink = get_post_meta(
          $id,
          '_cnmi_event_metabox_evaluation_url',
          true
      );
      if($evaluationLink) {
          return $evaluationLink;
      } else {
          return false;
      }
    }

    public static function get_event_content($id) {
      $eventContent = get_post($id);
      if($eventContent) {
        return $eventContent->post_content;
      } else {
        return false;
      }
    }
}
