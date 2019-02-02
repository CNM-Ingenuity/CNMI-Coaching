<?php
/*
 * Custom Class to deal with the coaching sessions table
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

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