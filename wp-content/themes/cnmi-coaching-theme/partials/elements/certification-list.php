<?php
$user_id = get_current_user_id();
$memberships = wc_memberships_get_user_active_memberships( $user_id );
if($memberships){
	$plan_id = $memberships[0]->{"plan_id"};
	if ($plan_id == 407) {
		// certified coach in training
		$certifications = CNMI_Progress::get_current_student_progress();
		foreach ($certifications as $certification) {
			$eventID = $certification->event_id;
			$eventType = CNMI_Events::get_event_type($eventID);
			$eventStartDate = CNMI_Events::get_event_start_date($eventID);
			if($eventStartDate) {
				$eventStartDate = $eventStartDate->format('m/d/Y');
			}
			$eventTrainer = CNMI_Events::get_event_trainer($eventID);
			?>

				<a href='<?php echo $linkAddress . $certification->id; ?>'>
					<div class="item">
						<h3 class="title"><?php echo $eventType; ?></h3>
						<p class="students">Instructor: <?php echo $eventTrainer; ?></p>
						<p class="date"><p>Date: <?php echo $eventStartDate; ?></p>
					</div>
				</a>
			<?php
		}
	} elseif ($plan_id == 411) {		
		// certified coach trainer	
		$events = CNMI_Events::get_events_by_coach_id($user_id);
		foreach ($events as $event) {
			$eventID = $event->ID;
			$eventType = CNMI_Events::get_event_type($eventID);
			$eventStartDate = CNMI_Events::get_event_start_date($eventID);
			if($eventStartDate) {
				$eventStartDate = $eventStartDate->format('m/d/Y');
			}
			?>
				<a href='<?php echo $linkAddress . $eventID; ?>'>
					<div class="item">
						<h3 class="title"><?php echo $eventType; ?></h3>
						<p class="date"><p>Date: <?php echo $eventStartDate; ?></p>
					</div>
				</a>
			<?php
		}
	}
}



