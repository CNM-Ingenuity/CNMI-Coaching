<?php
/*
 * Custom Class to deal with getting certifications
 */
class CNMI_Certifications {
  public static function get_certification_id_by_event_id($id) {
    $categories = wp_get_object_terms($id, 'tribe_events_cat');
      if(isset($categories[0])) {
        return $categories[0]->term_id;
      } else {
        return false;
      }
  }

  public static function get_certification_id_by_category_id($id) {
    switch($id){
      case 42:
        return 441;
      default:
        return false;
    }
  }

  public static function get_certification_by_event($id){
    $categoryID = self::get_certification_id_by_event_id($id);
    $certificationID = self::get_certification_id_by_category_id($categoryID);
    return get_post_meta($certificationID, '_cnmi_certification_metabox_training_resource_group', true);
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

  public static function get_certification_content_by_event_id($id) {
    $categoryID = self::get_certification_id_by_event_id($id);
    $certificationID = self::get_certification_id_by_category_id($categoryID);

    // get our post
    $content = "";
    $post = get_post($certificationID);
    if($post) {
        $content = $post->post_content;
    }

    // get our requirements
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
    $requirements = [$hours, $trainingType];

    // get our assessment link
    $assessment = get_post_meta(
        $certificationID,
        '_cnmi_certification_metabox_assessment',
        true
    );

    return [
        "content" => $content,
        "requirements" => $requirements,
        "assessment" => $assessment
    ];
  }

}