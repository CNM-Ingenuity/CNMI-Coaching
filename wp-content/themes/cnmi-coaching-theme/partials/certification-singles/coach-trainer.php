<?php
$post = get_post($progressID);
if($post) {
	// event type is the variable top matter expects
	$eventType = $post->post_title;
}
$breadcrumbs = [
	"My Certifications" => "/my-certifications",
	$eventType => "#",
];
include(locate_template('partials/elements/breadcrumbs.php'));
include(locate_template('partials/elements/top-matter.php'));
if($post) {
	?>
	<div class="description">
		<?php
    	echo wpautop($post->post_content);
}
?>
	  <!-- The buttons below might not be necessary  -->
		<!-- <div class="certification-buttons">
			<div class="one-half first">
				<a class="button item-button" href="/transcript">
					<p>View Transcript</p>
					<img src="/wp-content/uploads/2019/01/download-arrow.png">
				</a>
			</div>
			<div class="one-half">
				<a class="button item-button" href="/training/record">
					<p>Take Assessment</p>
					<span class="dashicons dashicons-media-document"></span>
				</a>
			</div>
		</div> -->

	</div>
