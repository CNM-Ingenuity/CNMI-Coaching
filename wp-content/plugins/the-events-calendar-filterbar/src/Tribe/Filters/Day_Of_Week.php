<?php

/**
 * Class Tribe__Events__Filterbar__Filters__Day_Of_Week
 */
class Tribe__Events__Filterbar__Filters__Day_Of_Week extends Tribe__Events__Filterbar__Filter {

	public $type = 'checkbox';

	protected function get_values() {
		$day_of_week_array = array(
			__( 'Sunday', 'tribe-events-filter-view' ),
			__( 'Monday', 'tribe-events-filter-view' ),
			__( 'Tuesday', 'tribe-events-filter-view' ),
			__( 'Wednesday', 'tribe-events-filter-view' ),
			__( 'Thursday', 'tribe-events-filter-view' ),
			__( 'Friday', 'tribe-events-filter-view' ),
			__( 'Saturday', 'tribe-events-filter-view' ),
		);

		// Get WordPress system value for the start of the week, keep in mind that on WordPress it
		// starts on 0 instead of 1 like we did above.
		$sys_week_start = absint( get_option( 'start_of_week', 0 ) );

		$sorted = range( 0, 6 );

		// Push the items of the array until the start_of_week to the end
		for ( $i = 0; $i < $sys_week_start; $i ++ ) {
			array_push( $sorted, array_shift( $sorted ) );
		}

		$day_of_week_values = array();
		foreach ( $sorted as $n ) {
			$day_of_week_values[] = array(
				'name'  => $day_of_week_array[ $n ],
				'value' => $n + 1,
			);
		}

		return $day_of_week_values;
	}

	/**
	 * Add modifications to the query
	 *
	 * @return void
	 */
	protected function setup_query_filters() {
		global $wp_query;
		// band-aid for month view
		if ( $wp_query->is_main_query() && $wp_query->get( 'eventDisplay' ) == 'month' ) {
			$wp_query->set(
				'meta_query', array(
					array(
						'key'  => '_EventStartDate',
						'type' => 'DATETIME',
					),
				)
			);
		}
		parent::setup_query_filters();
	}

	protected function setup_join_clause() {
		add_filter( 'posts_join', array( 'Tribe__Events__Query', 'posts_join' ), 10, 2 );

		// Default behavior is to *not* force local TZ; so let's reset to the default behavior
		// to make sure we don't interfere with queries other than the Day-filter one.
		add_filter( 'tribe_events_query_force_local_tz', '__return_false' );
	}

	protected function setup_where_clause() {

		/** @var wpdb $wpdb */
		global $wpdb;
		$clauses = array();
		$values = array_map( 'intval', $this->currentValue );
		$values = implode( ',', $values );

		$eod_cutoff = tribe_get_option( 'multiDayCutoff', '00:00' );
		if ( $eod_cutoff != '00:00' ) {
			$eod_time_difference = Tribe__Date_Utils::time_between( '1/1/2014 00:00:00', "1/1/2014 {$eod_cutoff}:00" );
			$start_date = "DATE_SUB({$wpdb->postmeta}.meta_value, INTERVAL {$eod_time_difference} SECOND)";
			$end_date = "DATE_SUB(tribe_event_end_date.meta_value, INTERVAL {$eod_time_difference} SECOND)";
		} else {
			$start_date = "{$wpdb->postmeta}.meta_value";
			$end_date = 'tribe_event_end_date.meta_value';
		}

		$clauses[] = "(DAYOFWEEK($start_date) IN ($values))";

		// is it on at least 7 days (first day is 0)
		$clauses[] = "(DATEDIFF($end_date, $start_date) >=6)";

		// determine if the start of the nearest matching day is between the start and end dates
		$distance_to_day = array();
		foreach ( $this->currentValue as $day_of_week_index ) {
			$day_of_week_index = (int) $day_of_week_index;
			$distance_to_day[] = "MOD( 7 + $day_of_week_index - DAYOFWEEK($start_date), 7 )";
		}
		if ( count( $distance_to_day ) > 1 ) {
			$distance_to_next_matching_day = 'LEAST(' . implode( ',', $distance_to_day ) . ')';
		} else {
			$distance_to_next_matching_day = reset( $distance_to_day );
		}
		$clauses[] = "(DATE(DATE_ADD($start_date, INTERVAL $distance_to_next_matching_day DAY)) < $end_date)";

		$this->whereClause = ' AND (' . implode( ' OR ', $clauses ) . ')';

		// Forces the query to use _EventStartDate and _EventEndDate as the times to base results
		// off of, instead of _EventStartDateUTC, _EventEventDateUTC which can produce weird results.
		add_filter( 'tribe_events_query_force_local_tz', '__return_true' );
	}
}
