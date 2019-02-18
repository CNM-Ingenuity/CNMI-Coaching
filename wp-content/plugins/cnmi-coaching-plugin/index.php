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
 * 3. Imports
 *
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
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

// access level methods
include_once dirname(__FILE__) . '/access-levels.php';

// classes
include_once dirname(__FILE__) . '/classes/CNMI_Progress.php';
include_once dirname(__FILE__) . '/classes/CNMI_Coaching_Session.php';
include_once dirname(__FILE__) . '/classes/CNMI_Letters.php';
include_once dirname(__FILE__) . '/classes/CNMI_Agreement.php';
include_once dirname(__FILE__) . '/classes/CNMI_Coaching_Hours.php';
include_once dirname(__FILE__) . '/classes/CNMI_Events.php';
include_once dirname(__FILE__) . '/classes/CNMI_Certifications.php';
include_once dirname(__FILE__) . '/classes/CNMI_CEU_Entry.php';
include_once dirname(__FILE__) . '/classes/CNMI_Licensing_Org.php';

/*
 * Custom Tables
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'PROGRESS_TABLE_NAME', 'progress' );
define( 'COACHING_SESSIONS_TABLE_NAME', 'coaching_sessions' );
define( 'COACHING_HOURS_TABLE_NAME', 'coaching_hours' );
define( 'COACHING_LETTERS_TABLE_NAME', 'coaching_letters' );
define( 'COACHING_AGREEMENT_TABLE_NAME', 'coaching_agreement' );
define( 'CEU_ENTRIES_TABLE_NAME', 'ceu_entries');

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
    establish_trust_vc varchar(255),
    establish_trust_text text,
    effective_assessments_vc varchar(10),
    effective_assessments_text text,
    respect_decisions_vc varchar(10),
    respect_decisions_text text,
    listen_focus_vc varchar(10),
    listen_focus_text text,
    asks_powerful_vc varchar(10),
    asks_powerful_text text,
    asks_motivate_vc varchar(10),
    asks_motivate_text text,
    helps_discover_vc varchar(10),
    helps_discover_text text,
    helps_focus_vc varchar(10),
    helps_focus_text text,
    co_creates_action_vc varchar(10),
    co_creates_action_text text,
    prepares_managing_progress_vc varchar(10),
    prepares_managing_progress_text text,
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

  $coaching_letters_table_name = $wpdb->prefix . COACHING_LETTERS_TABLE_NAME;
  $sql_coaching_letters = "CREATE TABLE {$coaching_letters_table_name} (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    progress_id mediumint(9) NOT NULL,
    url varchar(255) NOT NULL,
    PRIMARY KEY (id)
  ) COLLATE {$wpdb_collate};";

  $coaching_agreement_table_name = $wpdb->prefix . COACHING_AGREEMENT_TABLE_NAME;
  $sql_coaching_agreement = "CREATE TABLE {$coaching_agreement_table_name} (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    progress_id mediumint(9) NOT NULL,
    url varchar(255) NOT NULL,
    PRIMARY KEY (id)
  ) COLLATE {$wpdb_collate};";

  $ceu_entries_table_name = $wpdb->prefix . CEU_ENTRIES_TABLE_NAME;
  $sql_ceu_entries = "CREATE TABLE {$ceu_entries_table_name} (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    progress_id mediumint(9) NOT NULL,
    is_outside_cnm tinyint(1) NOT NULL,
    ceus_requested mediumint(9),
    certification varchar(255),
    program_training_title text,
    org_sponsor varchar(255),
    trainer_name varchar(255),
    start_date date,
    end_date date,
    program_description text,
    program_website varchar(255),
    learning_objectives text,
    agenda_url varchar(255),
    date_completed date,
    verification_code varchar(255),
    PRIMARY KEY (id)
  ) COLLATE {$wpdb_collate};";

  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
  dbDelta( $sql_progress );
  dbDelta( $sql_coaching_sessions );
  dbDelta( $sql_coaching_hours );
  dbDelta( $sql_coaching_letters );
  dbDelta( $sql_coaching_agreement );
  dbDelta( $sql_ceu_entries );
}

register_activation_hook(__FILE__, 'create_custom_tables');
