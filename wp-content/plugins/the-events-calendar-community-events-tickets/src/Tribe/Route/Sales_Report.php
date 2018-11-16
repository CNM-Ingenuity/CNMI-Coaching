<?php

class Tribe__Events__Community__Tickets__Route__Sales_Report extends Tribe__Events__Community__Tickets__Route__Abstract_Route {
	/**
	 * Route slug
	 * @var string
	 */
	public $route_slug = 'view-sales-report-route';

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
		$community_events->rewriteSlugs['sales'] = sanitize_title( __( 'sales', 'tribe-events-community-tickets' ) );

		$this->title = apply_filters( $this->hook_prefix . 'sales_report_title', __( 'Sales Report', 'tribe-events-community-tickets' ) );

		parent::__construct( $router );
	}

	/**
	 * Handles the rendering of the route
	 */
	public function callback( $event_id = null ) {
		$community_events = Tribe__Events__Community__Main::instance();
		$community_tickets = Tribe__Events__Community__Tickets__Main::instance();
		$community_tickets->require_login();
		$community_tickets->register_resources();

		$community_events = Tribe__Events__Community__Main::instance();
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
		?>
		<div class="table-menu-wrapper">
			<a href="<?php echo esc_url( tribe_community_events_list_events_link() ); ?>" class="button">
				<?php echo sprintf( __( 'My %s', 'tribe-events-community' ), tribe_get_event_label_plural() ); ?>
			</a>
			<?php do_action( 'tribe_ct_sales_report_nav' ); ?>
		</div>
		<?php
		$orders_report = Tribe__Tickets_Plus__Commerce__WooCommerce__Main::get_instance()->orders_report();
		$orders_report->orders_page_screen_setup();
		$orders_report->orders_page_inside();
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

		$path = $community_events->getCommunityRewriteSlug() . '/' . $community_events->rewriteSlugs['sales'] . '/' . $community_events->rewriteSlugs['event'];
		$path .= $suffix;

		return $path;
	}//end path

	/**
	 * return the sales report link
	 */
	public function url( $post_id ) {
		$url = Tribe__Events__Community__Main::instance()->getUrl( 'sales', $post_id, null, Tribe__Events__Main::POSTTYPE );
		return apply_filters( $this->hook_prefix . 'sales_report_url', $url );
	}//end url
}
