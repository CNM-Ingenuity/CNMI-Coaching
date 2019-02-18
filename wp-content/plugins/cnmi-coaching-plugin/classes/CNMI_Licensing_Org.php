<?php
/*
 * Custom Class to deal with the coaching letters table
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CNMI_Licensing_Org {

    public function __construct(){

    }

    public static function get_coaches() {
        return get_users( array('meta_key' => 'licensing_org', 'meta_value' => get_current_user_id()) );
    }
}