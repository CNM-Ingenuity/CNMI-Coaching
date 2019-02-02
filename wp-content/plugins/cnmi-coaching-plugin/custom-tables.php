<?php
/*
 * Custom Tables
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'PROGRESS_TABLE_NAME', 'progress' );
define( 'COACHING_SESSIONS_TABLE_NAME', 'coaching_sessions' );
define( 'COACHING_HOURS_TABLE_NAME', 'coaching_hours' );
define( 'COACHING_LETTERS_TABLE_NAME', 'coaching_letters' );
define( 'COACHING_AGREEMENT_TABLE_NAME', 'coaching_agreement' );

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

  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
  dbDelta( $sql_progress );
  dbDelta( $sql_coaching_sessions );
  dbDelta( $sql_coaching_hours );
  dbDelta( $sql_coaching_letters );
  dbDelta( $sql_coaching_agreement );
}

register_activation_hook(__FILE__, 'create_custom_tables');