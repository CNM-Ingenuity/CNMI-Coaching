<?php
/*
 * Custom Class to deal with the CEU entry table
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CNMI_CEU_Entry {

    public function __construct(){

    }

    public static function get_ceu_entry_by_id($id) {
        global $wpdb;
        $table_name  = $wpdb->prefix.CEU_ENTRIES_TABLE_NAME;
        return $wpdb->get_row($wpdb->prepare(
            "SELECT *
            FROM $table_name
            WHERE id = %s", intval( $id )
        ));
    }

    public static function get_ceu_entry_by_progress_id($id) {
        global $wpdb;
        $table_name  = $wpdb->prefix.CEU_ENTRIES_TABLE_NAME;
        return $wpdb->get_results($wpdb->prepare(
            "SELECT *
            FROM $table_name
            WHERE progress_id = %s", intval( $id )
        ));
    }

    public static function save_new_ceu_entry_outside_cnm(
        $progress_id, 
        $ceus_requested, 
        $certification, 
        $program_training_title, 
        $org_sponsor,  
        $trainer_name, 
        $start_date, 
        $end_date, 
        $program_description, 
        $program_website, 
        $learning_objectives, 
        $agenda_url 
    ) {

        // need to change this for these forms
        $has_access = verify_student_access($progress_id);
        if($has_access) {
            global $wpdb;
            $table_name  = $wpdb->prefix.CEU_ENTRIES_TABLE_NAME;
            return $wpdb->insert($table_name, array(
                    'progress_id' => intval( $progress_id ),
                    'is_outside_cnm' => 1,
                    'ceus_requested' => intval( $ceus_requested ),
                    'certification' => sanitize_text_field( $certification ),
                    'program_training_title' => sanitize_text_field( $program_training_title ),
                    'org_sponsor' => sanitize_text_field( $org_sponsor ),
                    'trainer_name' => sanitize_text_field( $trainer_name ),
                    'start_date' => sanitize_text_field( $start_date ),
                    'end_date' => sanitize_text_field( $end_date ),
                    'program_description' => sanitize_text_field( $program_description ),
                    'program_website' => sanitize_text_field( $program_website ),
                    'learning_objectives' => sanitize_text_field( $learning_objectives ),
                    'agenda_url' => sanitize_text_field( $agenda_url ),
                ),
                array('%s','%s', '%s')
            );
        } else {
            print_no_access();
        }
    }

    public static function save_new_ceu_entry_in_cnm(
        $progress_id, 
        $program_training_title, 
        $trainer_name, 
        $date_completed, 
        $verification_code
    ) {

        // need to change this for these forms
        $has_access = verify_student_access($progress_id);
        if($has_access) {
            global $wpdb;
            $table_name  = $wpdb->prefix.CEU_ENTRIES_TABLE_NAME;
            return $wpdb->insert($table_name, array(
                    'progress_id' => intval( $progress_id ),
                    'program_training_title' => sanitize_text_field( $program_training_title ),
                    'trainer_name' => sanitize_text_field( $trainer_name ),
                    'date_completed' => sanitize_text_field( $date_completed ),
                    'verification_code' => sanitize_text_field( $verification_code ),
                ),
                array('%s','%s', '%s')
            );
        } else {
            print_no_access();
        }
    }

}