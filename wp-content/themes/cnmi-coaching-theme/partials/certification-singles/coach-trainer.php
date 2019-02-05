<?php
$post = get_post($progressID);
if($post) {
	// event type is the variable top matter expects
	$eventType = $post->post_title;
}
$breadcrumbs = [
	"Resources" => "/my-certifications",
	$eventType => "#",
];
include(locate_template('partials/elements/breadcrumbs.php'));
include(locate_template('partials/elements/top-matter.php'));
if($post) {
    echo wpautop($post->post_content);
}