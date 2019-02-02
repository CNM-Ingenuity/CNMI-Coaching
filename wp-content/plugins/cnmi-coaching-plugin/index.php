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

// custom tables
include_once dirname(__FILE__) . '/custom-tables.php';

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
