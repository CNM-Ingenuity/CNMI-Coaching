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
// $organizers = tribe_get_organizers();
// $events = tribe_get_events();
// var_dump($events);
foreach ($attendance_array as $attendance)
{
    $event_name = $attendance['name'];
    $students = $attendance['students'];
    $date = $attendance ['date'];
    echo '<div class="item">';
    echo '<h3 class="title">'. $event_name .'</div>';
    echo '<p class="students">Students: '. $students .'</div>';
    echo '<p class="date"><p>Date: '. $date .'</p></div></div>';

}
