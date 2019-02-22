<?php
/*
 * Custom Class to deal with the coaching letters table
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CNMI_Agreement {

    public function __construct(){

    }

    public static function get_coaching_agreement_by_id($id) {
        global $wpdb;
        $table_name  = $wpdb->prefix.COACHING_AGREEMENT_TABLE_NAME;
        return $wpdb->get_row($wpdb->prepare(
            "SELECT *
            FROM $table_name
            WHERE id = %s", intval( $id )
        ));
    }

    public static function get_coaching_agreement_by_progress_id($progress_id) {
        global $wpdb;
        $table_name  = $wpdb->prefix.COACHING_AGREEMENT_TABLE_NAME;
        return $wpdb->get_results($wpdb->prepare(
            "SELECT *
            FROM $table_name
            WHERE progress_id = %s", intval( $progress_id )
        ));
    }

    public static function save_new_media($progress_id, $file) {
        $has_access = verify_student_access($progress_id);
        if($has_access) {
            global $wpdb;
            $table_name  = $wpdb->prefix.COACHING_AGREEMENT_TABLE_NAME;
            $bits = file_get_contents($file["tmp_name"]);
            $filetype = wp_check_filetype($file["name"]);
            $filename = 'progress_' . $progress_id . '_type_agreement_' . time() . '.' . $filetype['ext'];
            $upload = wp_upload_bits($filename, null, $bits);
            return $wpdb->insert($table_name, array(
                    'progress_id' => intval( $progress_id ),
                    'url' => $upload['url']
                ),
                array('%d','%s')
            );
        } else {
            print_no_access();
        }
    }
}