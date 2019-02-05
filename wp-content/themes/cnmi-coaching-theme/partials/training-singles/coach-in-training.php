<?php
$certification = CNMI_Progress::get_progress_by_id($progressID, false);
$certificationID = $certification->id;
$eventID = $certification->event_id;
$eventType = CNMI_Events::get_event_type($eventID);
$breadcrumbs = [
	"My Trainings" => "/my-trainings",
	$eventType => "#"
];
include(locate_template('partials/elements/breadcrumbs.php'));
include(locate_template('partials/elements/top-matter.php'));
$eventStartDate = CNMI_Events::get_event_start_date($eventID);
if($eventStartDate) {
	$eventStartDate = $eventStartDate->format('m/d/Y');
}
$eventTrainer = CNMI_Events::get_event_trainer($eventID);
$requirementsArray =  CNMI_Certifications::get_event_requirements($eventID);
$evaluationLink = CNMI_Events::get_event_evaluation_link($eventID);
$postContent = CNMI_Events::get_event_content($eventID);

?>
	<div class="item">
		<h3 class="title"><?php echo $eventType; ?></h3>
		<p class="students">Instructor: <?php echo $eventTrainer; ?></p>
		<p class="date">Date: <?php echo $eventStartDate; ?></p>
	</div>
	<div class="description">
			<?php
				echo wpautop($postContent);
		 	?>
		<p>Requirements:</p>
		<ul>
			<?php
				foreach($requirementsArray as $requirements){
					echo '<li>' . $requirements . '</li>';
				}
			?>
		</ul>
		<a class="button item-button" href="<?php echo $evaluationLink;?>"><p>Take Training Evaluation</p><span class="dashicons dashicons-media-text"></span></a>
	</div>
<?php