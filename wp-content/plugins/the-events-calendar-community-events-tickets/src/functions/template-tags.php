<?php
if ( ! function_exists( 'tribe_community_tickets_is_frontend_attendees_report' ) ) {
	/**
	 * A handy function for knowing if we're on a front-end Attendees Report.
	 *
	 * @since 4.6.2
	 *
	 * @return boolean
	 */
	function tribe_community_tickets_is_frontend_attendees_report() {
		$wp_route = get_query_var( 'WP_Route' );

		return ! empty( $wp_route ) && 'view-attendees-report-route' === $wp_route;
	}
}

if ( ! function_exists( 'tribe_community_tickets_is_frontend_sales_report' ) ) {
	/**
	 * A handy function for knowing if we're on a front-end Sales Report.
	 *
	 * @since 4.6.2
	 *
	 * @return boolean
	 */
	function tribe_community_tickets_is_frontend_sales_report() {
		$wp_route = get_query_var( 'WP_Route' );

		return ! empty( $wp_route ) && 'view-sales-report-route' === $wp_route;
	}
}