<?php
$progressID = $_GET['resource'];
if($progressID) {
	$certification = CNMI_Progress::get_progress_by_id($progressID, false);
	$eventID = $certification->event_id;
	$eventType = CNMI_Events::get_event_type($eventID);
	$eventResourcesArray = CNMI_Certifications::get_certification_by_event($eventID);
	$breadcrumbs = [
		"Resources" => "/my-resources",
		$eventType => "#",
	];
	include(locate_template('partials/elements/breadcrumbs.php'));
	include(locate_template('partials/elements/top-matter.php'));
	$eventStartDate = CNMI_Events::get_event_start_date($eventID);
	if($eventStartDate) {
		$eventStartDate = $eventStartDate->format('m/d/Y');
	}
	$eventTrainer = CNMI_Events::get_event_trainer($eventID);

	?>
	<div class="item">
		<h3 class="title"><?php echo $eventType; ?></h3>
		<p class="students">Instructor: <?php echo $eventTrainer; ?></p>
		<p class="date">Date: <?php echo $eventStartDate; ?></p>
	</div>
	<div class='resource-buttons'>
		<?php
		foreach ($eventResourcesArray as $event) {
			$resourceName= $event['name'];
			$resourceFile = $event['file'];
			?>
				<a class="button item-button" href="<?php echo $resourceFile; ?>" target="_blank">
					<p><?php echo $resourceName;?></p>
					<img src="/wp-content/uploads/2019/01/download-arrow.png">
				</a>
			<?php
		}
	?>
	</div>
	<?php
	include(locate_template('partials/elements/view-shop-button.php'));
} else {
	?>
		<p>Sorry, page not found.</p>
	<?php
}
