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

function create_progress_table()
{
  global $wpdb;
  $table_name = $wpdb->prefix . "progress";
  require_once(ABSPATH . 'wp-admin/includes/upgrade.php' );

  $sql = "CREATE TABLE $table_name (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    user_id mediumint(9) NOT NULL,
    coach_id mediumint(9) NOT NULL,
    PRIMARY KEY (id)
  ) $charset_collate;";

  dbDelta( $sql );
}

register_activation_hook(__FILE__, 'create_progress_table');

/* 
 * Custom Class to deal with the progress table
 */
class CNMI_Progress {
    public $id;
    public $user_id;
    public $coach_id;

    public function __construct($id, $user_id, $coach_id)
    {
        global $wpdb;
        $this->id = $id;
        $this->user_id = $user_id;
        $this->coach_id = $coach_id;
    }

    public static function get_all_progress()
    {
        global $wpdb;
        $table_name  = $wpdb->prefix."progress";
        return $wpdb->get_results("SELECT * FROM wp_progress;");
    }

    public static function update_status_by_user_id($user_id, $status)
    {
        global $wpdb;
        $table_name  = $wpdb->prefix."progress";
        return $wpdb->query( $wpdb->prepare(
            "UPDATE $table_name
            SET status = %s
            WHERE user_id = %s", $status, $user_id
        ));
    }
}
