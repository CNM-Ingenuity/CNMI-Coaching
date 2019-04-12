<?php
$events = CNMI_Events::get_events_by_licensing_org_id($user_id);
foreach ($events as $event) {
	$eventID = $event->ID;
	$eventType = CNMI_Events::get_event_type($eventID);
	$eventStartDate = CNMI_Events::get_event_start_date($eventID);
	if(count($events) === 1) {
			echo "<script> window.location.href='". $linkAddress . $eventID ."';</script> ";
	}
	if($eventStartDate) {
		$eventStartDate = $eventStartDate->format('m/d/Y');
	}
	?>
		<a href='<?php echo $linkAddress . $eventID; ?>'>
			<div class="item">
				<h3 class="title"><?php echo $eventType; ?></h3>
				<p class="date">Date: <?php echo $eventStartDate; ?></p>
			</div>
		</a>
	<?php
}
