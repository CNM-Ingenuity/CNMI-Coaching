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

/*
 * Custom Tables
 */

function create_custom_tables()
{
  global $wpdb;
  $wpdb_collate = $wpdb->collate;
  
  $table_name = $wpdb->prefix . "progress";
  $sql_progress = "CREATE TABLE {$table_name} (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    user_id mediumint(9) NOT NULL,
    coach_id mediumint(9) NOT NULL,
    PRIMARY KEY (id)
  ) COLLATE {$wpdb_collate};";

  $media_table_name = $wpdb->prefix . "progress_media";
  $sql_media_progress = "CREATE TABLE {$media_table_name} (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    progress_id mediumint(9) NOT NULL,
    type varchar(255) NOT NULL,
    url varchar(255) NOT NULL,
    PRIMARY KEY (id)
  ) COLLATE {$wpdb_collate};";
  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
  dbDelta( $sql_progress );
  dbDelta( $sql_media_progress );
}

register_activation_hook(__FILE__, 'create_custom_tables');

/* 
 * Custom Class to deal with the progress table
 */
class CNMI_Progress {
    public $id;
    public $user_id;
    public $coach_id;

    public function __construct($id, $user_id, $coach_id) {
       
    }

    public static function get_progress_by_id($id) {
        global $wpdb;
        $table_name  = $wpdb->prefix."progress";
        return $wpdb->get_results($wpdb->prepare( 
            "SELECT * 
            FROM $table_name 
            WHERE id = %s", $id
        ));
    }

    public static function update_progress_by_id_for_coach($id, $status) {
        // get the current user to prevent people from updating someone else's students
        $current_user = wp_get_current_user();
        $user_id = $current_user->ID;
        global $wpdb;
        $table_name  = $wpdb->prefix."progress";
        return $wpdb->query( $wpdb->prepare(
            "UPDATE $table_name
            SET status = %s
            WHERE id = %s AND coach_id = %s", $status, $id, $user_id
        ));
    }

    public static function update_progress_by_id_for_student($id, $status){
        // get the current user to prevent people from updating someone else's account
        $current_user = wp_get_current_user();
        $user_id = $current_user->ID;
        global $wpdb;
        $table_name  = $wpdb->prefix."progress";
        return $wpdb->query( $wpdb->prepare(
            "UPDATE $table_name
            SET status = %s
            WHERE id = %s AND user_id = %s", $status, $id, $user_id
        ));
    }

    public static function get_current_student_progress() {
        $current_user = wp_get_current_user();
        $user_id = $current_user->ID;
        global $wpdb;
        $table_name  = $wpdb->prefix."progress";
        return $wpdb->get_results($wpdb->prepare( 
            "SELECT * 
            FROM $table_name 
            WHERE user_id = %s", $user_id
        ));
    }

    public static function get_current_coach_progress() {
        $current_user = wp_get_current_user();
        $user_id = $current_user->ID;
        global $wpdb;
        $table_name  = $wpdb->prefix."progress";
        return $wpdb->get_results($wpdb->prepare( 
            "SELECT * 
            FROM $table_name 
            WHERE coach_id = %s", $user_id
        ));
    }
}

/* 
 * Custom Class to deal with the progress media table
 */
class CNMI_Progress_Media {
    public $id;
    public $progress_id;
    public $type;
    public $url;

    public function __construct($id, $progress_id, $type, $url){

    }

    public static function get_progress_media_by_id($id) {
        global $wpdb;
        $table_name  = $wpdb->prefix."progress_media";
        return $wpdb->get_results($wpdb->prepare( 
            "SELECT * 
            FROM $table_name 
            WHERE id = %s", $id
        ));
    }

    public static function get_progress_media_by_progress_id($progress_id) {
        global $wpdb;
        $table_name  = $wpdb->prefix."progress_media";
        return $wpdb->get_results($wpdb->prepare( 
            "SELECT * 
            FROM $table_name 
            WHERE progress_id = %s", $progress_id
        ));
    }

    public static function save_new_media($progress_id, $type, $file) {
        $bits = file_get_contents($file["tmp_name"]);
        $filetype = wp_check_filetype($file["name"]);
        $filename = 'progress_' . $progress_id . '_type_' . $type . '_' . time() . '.' . $filetype['ext'];
        var_dump($filename);
        $upload = wp_upload_bits($filename, null, $bits);
        global $wpdb;
        $table_name  = $wpdb->prefix."progress_media";
        return $wpdb->insert($table_name, array(
                'progress_id' => $progress_id, 
                'type' => $type,
                'url' => $upload['url']
            ),
            array('%s','%s', '%s') 
        );
    }
}