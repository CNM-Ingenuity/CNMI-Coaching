<?php
function verify_student_access($progress_id) {
    $current_user = wp_get_current_user();
    $user_id = $current_user->ID;
    return CNMI_Progress::get_progress_by_id_and_user_id($progress_id, $user_id);
}

function print_no_access() {
    print "Sorry, you don't have access to update this information.";
    exit;
}

function verify_coach_access($progress_id) {
    $current_user = wp_get_current_user();
    $user_id = $current_user->ID;
    $progress = CNMI_Progress::get_progress_by_id($progress_id, false);
    if($progress) {
        return CNMI_Events::get_events_by_id_and_coach_id($progress->event_id, $user_id);
    } else {
        return false;
    }
}