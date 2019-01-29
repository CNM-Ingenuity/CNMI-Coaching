<?php

/*
 * This is a template for Organizations Training view
 * 
 */

$attendance_array = array(
  ['name' => 'Event 1',
    'students' => 4,
    'date' => '12/20/2019'
  ],
  ['name' => 'Event 2',
    'students' => 14,
    'date' => '3/15/2019'
  ],
  ['name' => 'Event 3',
    'students' => 17,
    'date' => '06/20/2019'
  ]
);
get_template_part('partials/elements/top-matter');
echo '<div class="attendance">';
// $organizers = tribe_get_organizers();
// $events = tribe_get_events();
// var_dump($events);
foreach ($attendance_array as $attendance)
{
    $event_name = $attendance['name'];
    $students = $attendance['students'];
    $date = $attendance ['date'];
    echo '<div class="training">';
    echo '<div class="training-title"><p>'. $event_name .'</p></div>';
    echo '<div class="training-students"><p>Students: '. $students .'</p></div>';
    echo '<div class="training-date"><p>Date: '. $date .'</p></div></div>';

}
