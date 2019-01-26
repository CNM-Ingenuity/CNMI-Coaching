<?php
/*
Plugin Name: CNMI Coaching Site Plugin
Description: Plugin for CNMI Coaching Site
Version:     1.0
Author:      11online
Author URI:  http://11online.us
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/
/*
 * TABLE OF CONTENTS
 * 1. Custom Post Types
 * 2. CMB2
 * 3. Custom Tables
 * 4. Progress Class for easy interaction
 *
 */
 /*
 * CUSTOM POST TYPES
 */
// Add "Certifications" custom post type
function create_certifications_cpt()
{
    $labels = array(
        'name' => __('Certifications'),
        'singular_name' => __('Certification')
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'rewrite' => array('slug' => 'certifications'),
        'label' => __('Certifications', 'text_domain'),
        'description' => __('Certifications', 'text_domain'),
        'supports' => array('title', 'editor', 'excerpt', 'publicize', 'thumbnail', 'trackbacks', 'revisions', 'custom-fields', 'page-attributes',),
        'taxonomies' => array('file-under'),
        'hierarchical' => false,
        'menu_position' => 7,
        'menu_icon' => 'dashicons-awards',
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => true,
        'can_export' => true,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'capability_type' => 'page',
    );
    register_post_type('certifications', $args);
}
add_action('init', 'create_certifications_cpt');

/*
 * CMB2
 */
include_once dirname(__FILE__) . '/metaboxes/certification-cmb2.php';
include_once dirname(__FILE__) . '/metaboxes/event-cmb2.php';

/*
 * Custom Tables
 */
define( 'PROGRESS_TABLE_NAME', 'progress' );
define( 'COACHING_SESSIONS_TABLE_NAME', 'coaching_sessions' );
define( 'COACHING_HOURS_TABLE_NAME', 'coaching_hours' );

function create_custom_tables()
{
  global $wpdb;
  $wpdb_collate = $wpdb->collate;

  $table_name = $wpdb->prefix . PROGRESS_TABLE_NAME;
  $sql_progress = "CREATE TABLE {$table_name} (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    user_id mediumint(9) NOT NULL,
    event_id mediumint(9) NOT NULL,
    attendance_1 tinyint(1) DEFAULT 0 NOT NULL,
    attendance_2 tinyint(1) DEFAULT 0 NOT NULL,
    attendance_3 tinyint(1) DEFAULT 0 NOT NULL,
    attendance_4 tinyint(1) DEFAULT 0 NOT NULL,
    attendance_5 tinyint(1) DEFAULT 0 NOT NULL,
    attendance_6 tinyint(1) DEFAULT 0 NOT NULL,
    attendance_7 tinyint(1) DEFAULT 0 NOT NULL,
    attendance_8 tinyint(1) DEFAULT 0 NOT NULL,
    attendance_9 tinyint(1) DEFAULT 0 NOT NULL,
    attendance_10 tinyint(1) DEFAULT 0 NOT NULL,
    evaluation tinyint(1) DEFAULT 0 NOT NULL,
    fieldwork tinyint(1) DEFAULT 0 NOT NULL,
    training_complete tinyint(1) DEFAULT 0 NOT NULL,
    coaching_hours_complete tinyint(1) DEFAULT 0 NOT NULL,
    coaching_sessions_complete tinyint(1) DEFAULT 0 NOT NULL,
    assessment_complete tinyint(1) DEFAULT 0 NOT NULL,
    certification_complete tinyint(1) DEFAULT 0 NOT NULL,
    PRIMARY KEY (id)
  ) COLLATE {$wpdb_collate};";

  $coaching_sessions_table_name = $wpdb->prefix . COACHING_SESSIONS_TABLE_NAME;
  $sql_coaching_sessions = "CREATE TABLE {$coaching_sessions_table_name} (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    progress_id mediumint(9) NOT NULL,
    url varchar(255) NOT NULL,
    reviewer_id mediumint(9),
    date date,
    comments text,
    session_accepted tinyint(1) DEFAULT 0 NOT NULL,
    PRIMARY KEY (id)
  ) COLLATE {$wpdb_collate};";

  $coaching_hours_table_name = $wpdb->prefix . COACHING_HOURS_TABLE_NAME;
  $sql_coaching_hours = "CREATE TABLE {$coaching_hours_table_name} (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    progress_id mediumint(9) NOT NULL,
    client_name varchar(255) NOT NULL,
    date date NOT NULL,
    minutes mediumint(9) NOT NULL,
    comments text NOT NULL,
    PRIMARY KEY (id)
  ) COLLATE {$wpdb_collate};";
  
  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
  dbDelta( $sql_progress );
  dbDelta( $sql_coaching_sessions );
  dbDelta( $sql_coaching_hours );
}

register_activation_hook(__FILE__, 'create_custom_tables');

/*
 * Custom Class to deal with the progress table
 */
class CNMI_Progress {

    public function __construct() {

    }

    public static function get_progress_by_id($id, $include_relational_data = true) {
        global $wpdb;
        $table_name  = $wpdb->prefix.PROGRESS_TABLE_NAME;
        $result = $wpdb->get_row($wpdb->prepare(
            "SELECT *
            FROM $table_name
            WHERE id = %s", intval( $id )
        ));
        if($result && $include_relational_data) {
            // attach coaching sessions and hours
            $result->coaching_sessions = CNMI_Coaching_Session::get_coaching_sessions_by_progress_id($result->id);
            $result->coaching_hours = CNMI_Coaching_Hours::get_coaching_hours_by_progress_id($result->id);
        }
        return $result;
    }

    // this is a method used to make sure people are only updating their own progress
    public static function get_progress_by_id_and_user_id($id, $user_id) {
        global $wpdb;
        $table_name  = $wpdb->prefix.PROGRESS_TABLE_NAME;
        $results = $wpdb->get_row($wpdb->prepare(
            "SELECT *
            FROM $table_name
            WHERE id = %s AND user_id = %s", intval( $id ), intval( $user_id )
        ));
        return $results;
    }

    public static function take_attendance($event_id, $session_number, $student_ids) {
        $current_user = wp_get_current_user();
        $user_id = $current_user->ID;
        $has_access = CNMI_Events::get_events_by_id_and_coach_id($event_id, $user_id);
        if($has_access) {
            global $wpdb;
            $table_name  = $wpdb->prefix.PROGRESS_TABLE_NAME;
            // prepare our student ids for the where in clause
            $student_ids = array_map(function($id) {
                return "'" . esc_sql($id) . "'";
            }, $student_ids);
            $student_ids = implode(',', $student_ids);
            return $wpdb->query( $wpdb->prepare(
                "UPDATE $table_name
                SET attendance_" . intval( $session_number ) . " = 1
                WHERE user_id IN (" . $student_ids . ") AND event_id = %s", intval( $event_id )
            ));
        } else {
            print_no_access();
        }
    }

    public static function get_current_student_progress() {
        $current_user = wp_get_current_user();
        $user_id = $current_user->ID;
        global $wpdb;
        $table_name  = $wpdb->prefix.PROGRESS_TABLE_NAME;
        return $wpdb->get_results($wpdb->prepare(
            "SELECT *
            FROM $table_name
            WHERE user_id = %s", intval( $user_id )
        ));
    }

    public static function get_students_from_event_id($event_id) {
        global $wpdb;
        $table_name  = $wpdb->prefix.PROGRESS_TABLE_NAME;
        $results = $wpdb->get_results($wpdb->prepare(
            "SELECT *
            FROM $table_name
            WHERE event_id = %s", intval( $event_id )
        ));
        foreach($results as $result) {
            $user = get_user_by('id', $result->user_id);
            $result->user_nicename = $user->user_nicename;
        }
        return $results;
    }
}

/*
 * Custom Class to deal with the coaching sessions table
 */
class CNMI_Coaching_Session {

    public function __construct(){

    }

    public static function get_coaching_session_by_id($id) {
        global $wpdb;
        $table_name  = $wpdb->prefix.COACHING_SESSIONS_TABLE_NAME;
        return $wpdb->get_row($wpdb->prepare(
            "SELECT *
            FROM $table_name
            WHERE id = %s", intval( $id )
        ));
    }

    public static function get_coaching_sessions_by_progress_id($progress_id) {
        global $wpdb;
        $table_name  = $wpdb->prefix.COACHING_SESSIONS_TABLE_NAME;
        return $wpdb->get_results($wpdb->prepare(
            "SELECT *
            FROM $table_name
            WHERE progress_id = %s", intval( $progress_id )
        ));
    }

    public static function save_new_media($progress_id, $file) {
        $has_access = verify_student_access($progress_id);
        if($has_access) {
            $bits = file_get_contents($file["tmp_name"]);
            $filetype = wp_check_filetype($file["name"]);
            $filename = 'progress_' . $progress_id . '_type_' . $type . '_' . time() . '.' . $filetype['ext'];
            $upload = wp_upload_bits($filename, null, $bits);
            global $wpdb;
            $table_name  = $wpdb->prefix.COACHING_SESSIONS_TABLE_NAME;
            return $wpdb->insert($table_name, array(
                    'progress_id' => intval( $progress_id ),
                    'url' => $upload['url']
                ),
                array('%s','%s', '%s')
            );
        } else {
            print_no_access();
        }
    }

    public static function review_session($id, $comments, $session_accepted) {
        $session = self::get_coaching_session_by_id($id);
        if($session) {
            $has_access = verify_coach_access($session->progress_id);
            if($has_access) {
                global $wpdb;
                $table_name  = $wpdb->prefix.COACHING_SESSIONS_TABLE_NAME;
                $current_user = wp_get_current_user();
                $user_id = $current_user->ID;
                $data = array(
                        'reviewer_id' => $user_id,
                        'date' => date('Y-m-d'),
                        'comments' => sanitize_textarea_field( $comments ),
                        'session_accepted' => boolval( $session_accepted )
                );
                $where = array('ID' => intval( $id ));
                return $wpdb->update(
                    $table_name, 
                    $data,
                    $where
                );
            } else {
                print_no_access();
            }
        } else {
            print_no_access();
        }
    }
}

/*
 * Custom Class to deal with the coaching sessions table
 */
class CNMI_Coaching_Hours {

    public function __construct(){

    }

    public static function get_coaching_hours_by_id($id) {
        global $wpdb;
        $table_name  = $wpdb->prefix.COACHING_HOURS_TABLE_NAME;
        return $wpdb->get_row($wpdb->prepare(
            "SELECT *
            FROM $table_name
            WHERE id = %s", intval( $id )
        ));
    }

    public static function get_coaching_hours_by_progress_id($progress_id) {
        global $wpdb;
        $table_name  = $wpdb->prefix.COACHING_HOURS_TABLE_NAME;
        return $wpdb->get_results($wpdb->prepare(
            "SELECT *
            FROM $table_name
            WHERE progress_id = %s", intval( $progress_id )
        ));
    }

    public static function save_new_coaching_hours($progress_id, $client_name, $date, $minutes, $comments) {
        $has_access = verify_student_access($progress_id);
        if($has_access) {
            global $wpdb;
            $table_name  = $wpdb->prefix.COACHING_HOURS_TABLE_NAME;
            return $wpdb->insert($table_name, array(
                    'progress_id' => intval( $progress_id ),
                    'client_name' => sanitize_text_field( $client_name ),
                    'date' => sanitize_text_field( $date ),
                    'minutes' => intval( $minutes ),
                    'comments' => sanitize_textarea_field( $comments ),
                ),
                array('%s','%s', '%s')
            );
        } else {
            print_no_access();
        }
    }
}

function verify_student_access($progress_id) {
    $current_user = wp_get_current_user();
    $user_id = $current_user->ID;
    return CNMI_Progress::get_progress_by_id_and_user_id($progress_id, $user_id);
}

function print_no_access() {
    print "Sorry, you don't have access to update this certification.";
    exit;
}

function verify_coach_access($progress_id) {
    $current_user = wp_get_current_user();
    $user_id = $current_user->ID;
    $progress = CNMI_Progress::get_progress_by_id($progress_id, false);
    if($progress) {
        return CNMI_Events::get_events_by_id_and_coach_id($progress->event_id, $user_id);
    } else {
        return false;
    }
}

/*
 * Custom Class to deal with getting events
 */
class CNMI_Events {

    public function __construct() {

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
}
