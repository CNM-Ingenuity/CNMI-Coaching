<?php
/*
 * Custom Class to deal with getting certifications
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CNMI_Certifications {
  public static function get_certification_id_by_event_id($id) {
    $categories = wp_get_object_terms($id, 'tribe_events_cat');
      if(isset($categories[0])) {
        return $categories[0]->term_id;
      } else {
        return false;
      }
  }

  public static function get_category_by_event_id($id) {
    $categories = wp_get_object_terms($id, 'tribe_events_cat');
      if(isset($categories[0])) {
        return $categories[0];
      } else {
        return false;
      }
  }

  public static function get_certification_id_by_category_id($id) {
    switch($id){
      case 44:
        // financial coach training
        return 581;
      case 43:
        // academic coach training
        return 583;
      default:
        return false;
    }
  }

  public static function get_certification_by_event($id){
    $categoryID = self::get_certification_id_by_event_id($id);
    $certificationID = self::get_certification_id_by_category_id($categoryID);
    $result = get_post_meta($certificationID, '_cnmi_certification_metabox_training_resource_group', true);
    if($result) {
      return $result;
    } else {
      return array();
    }
  }

  public static function get_unique_certifications_by_coach_id($coach_id) {
    $events = CNMI_Events::get_events_by_coach_id($coach_id);
    $uniqueCertifications = [];
    foreach ($events as $event) {
      $category = self::get_category_by_event_id($event->ID);
      $certificationId = self::get_certification_id_by_category_id($category->term_id);
      $uniqueCertifications[$certificationId] = $category->name;
    }
    return $uniqueCertifications;
  }

  public static function get_unique_certifications_by_contracting_org_id($contracting_org_id) {
    $events = CNMI_Events::get_events_by_contracting_org_id($contracting_org_id);
    $uniqueCertifications = [];
    foreach ($events as $event) {
      $category = self::get_category_by_event_id($event->ID);
      $certificationId = self::get_certification_id_by_category_id($category->term_id);
      $uniqueCertifications[$certificationId] = $category->name;
    }
    return $uniqueCertifications;
  }

  public static function get_unique_certifications_by_licensing_org_id($licensing_org_id) {
    $events = CNMI_Events::get_events_by_licensing_org_id($licensing_org_id);
    $uniqueCertifications = [];
    foreach ($events as $event) {
      $category = self::get_category_by_event_id($event->ID);
      $certificationId = self::get_certification_id_by_category_id($category->term_id);
      $uniqueCertifications[$certificationId] = $category->name;
    }
    return $uniqueCertifications;
  }

  public static function get_event_requirements($id) {
    $categoryID = self::get_certification_id_by_event_id($id);
    $certificationID = self::get_certification_id_by_category_id($categoryID);
    $hours = get_post_meta(
        $certificationID,
        '_cnmi_certification_metabox_hours',
        true
    );
    $trainingType = get_post_meta(
        $certificationID,
        '_cnmi_certification_metabox_training_type',
        true
    );
    return [$hours, $trainingType];
  }

  public static function get_certification_resources($id) {
    $certificationResourcesArray = get_post_meta(
      $id,
      '_cnmi_certification_metabox_training_resource_group',
      true
    );

    return $certificationResourcesArray;

  }

  public static function get_certification_content_by_event_id($id) {
    $categoryID = self::get_certification_id_by_event_id($id);
    $certificationID = self::get_certification_id_by_category_id($categoryID);

    // get our post
    $content = "";
    $post = get_post($certificationID);
    if($post) {
        $content = $post->post_content;
    }

    // get our assessment link
    $assessment = get_post_meta(
        $certificationID,
        '_cnmi_certification_metabox_assessment',
        true
    );

    $certification_download = get_post_meta(
        $certificationID,
        '_cnmi_certification_metabox_certification_download',
        true
    );

    return [
        "content" => $content,
        "assessment" => $assessment,
        "certification_download" => $certification_download
    ];
  }

  public static function get_coaching_session_upload_text($id) {
    $categoryID = self::get_certification_id_by_event_id($id);
    $certificationID = self::get_certification_id_by_category_id($categoryID);

    // get our post

    $post = get_post($certificationID);

    // get the metabox content for the upload coaching session page
    return get_post_meta(
        $certificationID,
        '_cnmi_certification_metabox_coaching_session_upload_text',
        true
    );
  }

  public static function get_letter_upload_text($id) {
    $categoryID = self::get_certification_id_by_event_id($id);
    $certificationID = self::get_certification_id_by_category_id($categoryID);

    // get our post

    $post = get_post($certificationID);

    // get the metabox content for the upload coaching session page
    return get_post_meta(
        $certificationID,
        '_cnmi_certification_metabox_letter_upload_text',
        true
    );
  }

  public static function get_track_coaching_hours_text($id) {
    $categoryID = self::get_certification_id_by_event_id($id);
    $certificationID = self::get_certification_id_by_category_id($categoryID);

    // get our post

    $post = get_post($certificationID);

    // get the metabox content for the upload coaching session page
    return get_post_meta(
        $certificationID,
        '_cnmi_certification_metabox_track_coaching_hours_text',
        true
    );
  }

  public static function get_coaching_end_user_agreement_content($id) {
    $categoryID = self::get_certification_id_by_event_id($id);
    $certificationID = self::get_certification_id_by_category_id($categoryID);

    // get our assessment link
    $text = get_post_meta(
        $certificationID,
        '_cnmi_certification_metabox_coaching_end_user_agreement_text',
        true
    );

    $file = get_post_meta(
        $certificationID,
        '_cnmi_certification_metabox_coaching_end_user_agreement_file',
        true
    );

    return [
      "text" => $text,
      "file" => $file
    ];
  }



}
