<?php

class Tribe__Events__Community__Tickets__Route__Payment_Options extends Tribe__Events__Community__Tickets__Route__Abstract_Route {
	/**
	 * Route slug
	 * @var string
	 */
	public $route_slug = 'edit-payment-options-route';

	/**
	 * Route suffix
	 * @var string
	 */
	public $route_suffix = '(/page/(\d+))?/?$';

	/**
	 * constructor
	 */
	public function __construct( $router ) {
		$community_events = Tribe__Events__Community__Main::instance();
		$community_events->rewriteSlugs['payment-options'] = sanitize_title( __( 'payment-options', 'tribe-events-community-tickets' ) );

		$this->title = apply_filters( $this->hook_prefix . 'payment-options-title', __( 'Payment Options', 'tribe-events-community-tickets' ) );

		add_action( 'tribe_ce_after_event_list_top_buttons', array( $this, 'link' ) );
		add_action( 'tribe_ct_payment_options_nav', array( $this, 'link' ) );
		add_action( 'tribe_ct_attendees_report_nav', array( $this, 'link' ) );
		add_action( 'tribe_ct_sales_report_nav', array( $this, 'link' ) );

		parent::__construct( $router );
	}

	/**
	 * Handles the rendering of the route
	 */
	public function callback( $arg = null ) {
		$community_events = Tribe__Events__Community__Main::instance();
		$community_tickets = Tribe__Events__Community__Tickets__Main::instance();
		$community_tickets->require_login();

		add_filter( 'tribe_events_current_view_template', array( $community_events, 'default_template_placeholder' ) );
		tribe_asset_enqueue_group( 'events-styles' );

		$community_events->removeFilters();

		if (
			isset( $_POST['payment_options_nonce'] )
			&& wp_verify_nonce( $_POST['payment_options_nonce'], 'tribe_ct_save_payment_options' )
		) {
			$community_tickets->payment_options_form()->save( get_current_user_id(), $_POST );
		}

		ob_start();

		$community_tickets->payment_options_form()->render();

		$output = ob_get_clean();

		return $output;
	}

	/**
	 * Adds the route to the router
	 */
	public function add() {
		$community_tickets = Tribe__Events__Community__Tickets__Main::instance();

		if ( ! current_user_can( 'edit_event_tickets' ) && ! $community_tickets->is_split_payments_enabled() ) {
			return;
		}

		parent::add();
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

		$path = $community_events->getCommunityRewriteSlug() . '/' . $community_events->rewriteSlugs['payment-options'];
		$path .= $suffix;

		return $path;
	}//end path

	/**
	 * Hooked to the tribe_ce_after_event_list_top_buttons action to add navigation
	 */
	public function link() {
		$community_tickets = Tribe__Events__Community__Tickets__Main::instance();

		if ( ! $community_tickets->is_enabled() ) {
			return;
		}//end if

		if ( ! $community_tickets->is_split_payments_enabled() && ! current_user_can( 'edit_event_tickets' ) ) {
			return;
		}//end if

		?>
		<a href="<?php echo esc_url( $this->url() ); ?>" class="tribe-community-tickets-payment-options-link button">
			<?php echo esc_html( apply_filters( $this->hook_prefix . 'event_list_payment_options_button_text', __( 'Payment options', 'tribe-events-community-tickets' ) ) ); ?>
		</a>
		<?php
	}//end link

	/**
	 * return the payment options link
	 *
	 * @return string
	 */
	public function url() {
		$url = Tribe__Events__Community__Main::instance()->getUrl( 'payment-options' );
		return apply_filters( $this->hook_prefix . 'payment_options_url', $url );
	}//end url
}
