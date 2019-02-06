<?php
/*
 * Custom Class to deal with the progress table
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

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
            $result->coaching_hours = CNMI_Letters::get_coaching_letters_by_progress_id($result->id);
            $result->coaching_hours = CNMI_Agreement::get_coaching_agreement_by_progress_id($result->id);
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
            $result->user_nicename = $user->first_name . ' ' . $user->last_name;
            $result->user_email = $user->user_email;
        }
        return $results;
    }
}