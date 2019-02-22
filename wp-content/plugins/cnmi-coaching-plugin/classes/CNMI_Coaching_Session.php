<?php
/*
 * Custom Class to deal with the coaching sessions table
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

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

    public static function get_coaching_session_by_id_student_access($id) {
        global $wpdb;
        $table_name  = $wpdb->prefix.COACHING_SESSIONS_TABLE_NAME;
        $result = $wpdb->get_row($wpdb->prepare(
            "SELECT *
            FROM $table_name
            WHERE id = %s", intval( $id )
        ));
        $has_access = verify_student_access($result->progress_id);
        if($has_access) {
            return $result;
        } else {
            print_no_access();
        }
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

    public static function save_new_media($progress_id, $file, $media_upload) {
        $has_access = verify_student_access($progress_id);
        if($has_access) {
            global $wpdb;
            $table_name  = $wpdb->prefix.COACHING_SESSIONS_TABLE_NAME;
            // see if the user has uploaded a file or provided a link
            if($media_upload) {
                $bits = file_get_contents($file["tmp_name"]);
                $filetype = wp_check_filetype($file["name"]);
                $filename = 'progress_' . $progress_id . '_type_coaching_session_' . time() . '.' . $filetype['ext'];
                $upload = wp_upload_bits($filename, null, $bits);
                return $wpdb->insert($table_name, array(
                        'progress_id' => intval( $progress_id ),
                        'url' => $upload['url']
                    ),
                    array('%d','%s')
                );
            } else {
                return $wpdb->insert($table_name, array(
                        'progress_id' => intval( $progress_id ),
                        'url' => sanitize_text_field( $file )
                    ),
                    array('%d','%s')
                );
            }
        } else {
            print_no_access();
        }
    }

    public static function review_session(
        $id, 
        $establish_trust_vc,
        $establish_trust_text,
        $effective_assessments_vc,
        $effective_assessments_text,
        $respect_decisions_vc,
        $respect_decisions_text,
        $listen_focus_vc,
        $listen_focus_text,
        $asks_powerful_vc,
        $asks_powerful_text,
        $asks_motivate_vc,
        $asks_motivate_text,
        $helps_discover_vc,
        $helps_discover_text,
        $helps_focus_vc,
        $helps_focus_text,
        $co_creates_action_vc,
        $co_creates_action_text,
        $prepares_managing_progress_vc,
        $prepares_managing_progress_text,
        $session_accepted
    ) {
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
                        'establish_trust_vc' => sanitize_text_field( $establish_trust_vc ),
                        'establish_trust_text' => sanitize_textarea_field( $establish_trust_text ),
                        'effective_assessments_vc' => sanitize_text_field( $effective_assessments_vc ),
                        'effective_assessments_text' => sanitize_textarea_field( $effective_assessments_text ),
                        'respect_decisions_vc' => sanitize_text_field( $respect_decisions_vc ),
                        'respect_decisions_text' => sanitize_textarea_field( $respect_decisions_text ),
                        'listen_focus_vc' => sanitize_text_field( $listen_focus_vc ),
                        'listen_focus_text' => sanitize_textarea_field( $listen_focus_text ),
                        'asks_powerful_vc' => sanitize_text_field( $asks_powerful_vc ),
                        'asks_powerful_text' => sanitize_textarea_field( $asks_powerful_text ),
                        'asks_motivate_vc' => sanitize_text_field( $asks_motivate_vc ),
                        'asks_motivate_text' => sanitize_textarea_field( $asks_motivate_text ),
                        'helps_discover_vc' => sanitize_text_field( $helps_discover_vc ),
                        'helps_discover_text' => sanitize_textarea_field( $helps_discover_text ),
                        'helps_focus_vc' => sanitize_text_field( $helps_focus_vc ),
                        'helps_focus_text' => sanitize_textarea_field( $helps_focus_text ),
                        'co_creates_action_vc' => sanitize_text_field( $co_creates_action_vc ),
                        'co_creates_action_text' => sanitize_textarea_field( $co_creates_action_text ),
                        'prepares_managing_progress_vc' => sanitize_text_field( $prepares_managing_progress_vc ),
                        'prepares_managing_progress_text' => sanitize_textarea_field( $prepares_managing_progress_text ),
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