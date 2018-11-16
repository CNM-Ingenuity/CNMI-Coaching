<?php

class Tribe__Events__Community__Tickets__Route__Attendees_Report extends Tribe__Events__Community__Tickets__Route__Abstract_Route {
	/**
	 * Route slug
	 * @var string
	 */
	public $route_slug = 'view-attendees-report-route';

	/**
	 * Route suffix
	 * @var string
	 */
	public $route_suffix = '/(\d+/?)$';

	/**
	 * Route query vars
	 * @var array
	 */
	public $route_query_vars = array(
		'tribe_community_event_id' => 1,
	);

	/**
	 * Page arguments
	 * @var array
	 */
	public $page_args = array(
		'tribe_community_event_id',
	);

	/**
	 * constructor
	 */
	public function __construct( $router ) {
		$community_events = Tribe__Events__Community__Main::instance();
		$community_events->rewriteSlugs['attendees'] = sanitize_title( __( 'attendees', 'tribe-events-community-tickets' ) );

		$this->title = apply_filters( $this->hook_prefix . 'attendees_report_title', __( 'Attendees Report', 'tribe-events-community-tickets' ) );

		parent::__construct( $router );
	}

	/**
	 * Handles the rendering of the route
	 */
	public function callback( $event_id = null ) {
		$community_events = Tribe__Events__Community__Main::instance();
		$community_tickets = Tribe__Events__Community__Tickets__Main::instance();
		$community_tickets->require_login( $event_id );
		$community_tickets->register_resources();

		add_thickbox();

		add_filter( 'tribe_events_current_view_template', array( $community_events, 'default_template_placeholder' ) );
		tribe_asset_enqueue_group( 'events-styles' );

		// the attendees report requires that the event ID be placed in $_GET['event_id']
		$_GET['event_id'] = $event_id;

		$GLOBALS['hook_suffix'] = null;

		include_once ABSPATH . '/wp-admin/includes/screen.php';
		include_once ABSPATH . '/wp-admin/includes/template.php';

		// starting with WordPress 4.4, these two classes were split out to their own files
		if ( ! class_exists( 'WP_Screen' ) ) {
			include_once ABSPATH . '/wp-admin/includes/class-wp-screen.php';
		}

		if ( ! class_exists( 'WP_List_Table' ) ) {
			include_once ABSPATH . '/wp-admin/includes/class-wp-list-table.php';
		}

		$community_events->removeFilters();

		ob_start();

		if ( empty( $_GET['attendees_csv'] ) ) {
			?>
			<div class="table-menu-wrapper">
				<a href="<?php echo esc_url( tribe_community_events_list_events_link() ); ?>" class="button">
					<?php echo sprintf( __( 'My %s', 'tribe-events-community' ), tribe_get_event_label_plural() ); ?>
				</a>
				<?php do_action( 'tribe_ct_attendees_nav' ); ?>
			</div>
			<?php
		}

		$attendees = tribe( 'tickets.attendees' );
		$attendees->enqueue_assets( '' );
		$attendees->load_pointers( '' );
		$attendees->screen_setup();
		$attendees->render();

		wp_enqueue_style( 'events-community-tickets' );
		wp_enqueue_script( 'events-community-tickets' );
		wp_enqueue_script( 'list-table' );
		wp_enqueue_script( 'common' );
		$output = ob_get_clean();

		$output = '<div id="tribe-events-report">' . $output . '</div>';

		return $output;
	}

	/**
	 * Returns paths for routes
	 *
	 * @param $suffix string Value gets appended to the end of the path upon return
	 *
	 * @return string
	 */
	public function path( $suffix = null ) {
		$community_events = Tribe__Events__Community__Main::instance();

		$path = $community_events->getCommunityRewriteSlug() . '/' . $community_events->rewriteSlugs['attendees'] . '/' . $community_events->rewriteSlugs['event'];
		$path .= $suffix;

		return $path;
	}//end path

	/**
	 * return the attendees report link
	 */
	public function url( $post_id ) {
		$url = Tribe__Events__Community__Main::instance()->getUrl( 'attendees', $post_id, null, Tribe__Events__Main::POSTTYPE );
		return apply_filters( $this->hook_prefix . 'attendees_report_url', $url );
	}//end url
}
