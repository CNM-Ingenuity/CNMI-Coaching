<?php

abstract class Tribe__Events__Community__Tickets__Gateway__Abstract {
	public $id;
	protected $fee_percentage;
	protected $fee_flat;
	protected $site_fee_type;
	protected $site_fee_percentage;
	protected $site_fee_flat;

	/**
	 * Returns whether or not all of the required fields have been entered
	 */
	abstract public function is_available();

	/**
	 * singleton to instantiate the Tickets clas
	 */
	public static function instance() {
		static $instance;

		if ( ! $instance ) {
			$instance = new self;
		}//end if

		return $instance;
	}//end instance

	/**
	 * constructor!
	 */
	public function __construct() {
		$community_tickets = Tribe__Events__Community__Tickets__Main::instance();
		$settings = get_option( Tribe__Events__Community__Tickets__Main::OPTIONNAME, $community_tickets->option_defaults );

		foreach ( $settings as $key => $value ) {
			if ( false === strpos( $key, 'site_fee' ) ) {
				continue;
			}

			$this->$key = $value;
		}//end foreach
	}//end __construct

	/**
	 * computes the price of a ticket based on the gateway's percentage, site percentage, etc
	 *
	 * @param $price int Price of the actual ticket
	 *
	 * @return float
	 */
	public function ticket_price( $price, $calculate_percentage_fee = false ) {
		$percentage = 0;

		if ( $calculate_percentage_fee ) {
			$percentage = $this->fee_percentage();
		}

		$total = round( $price / 100 * ( 100 - $percentage ), 2 );

		return $total;
	}//end ticket_price

	/**
	 * returns whether or not the site has a flat fee enabled
	 *
	 * @return boolean
	 */
	public function has_site_fee_flat() {
		return ( 'flat' === $this->site_fee_type || 'flat-and-percentage' === $this->site_fee_type );
	}//end has_site_fee_flat

	/**
	 * returns whether or not the site has a percentage fee enabled
	 *
	 * @return boolean
	 */
	public function has_site_fee_percentage() {
		return ( false !== strpos( $this->site_fee_type, 'percentage' ) );
	}//end has_site_fee_percentage

	/**
	 * returns the flat fee for tickets. Combines the gateway flat fee with the site flat fee if
	 * configured with one.
	 *
	 * @return float
	 */
	public function fee_flat() {
		$flat_fee = $this->fee_flat;

		if ( $this->has_site_fee_flat() ) {
			$flat_fee += $this->site_fee_flat;
		}

		return $flat_fee;
	}//end fee_percentage

	/**
	 * returns the fee percentage for tickets. Combines the gateway percentage with the site
	 * percentage if configured with one.
	 *
	 * @return float
	 */
	public function fee_percentage() {
		$percentage = $this->fee_percentage;


		if ( $this->has_site_fee_percentage() ) {
			$percentage += (float) $this->site_fee_percentage;
		}//end if

		return $percentage;
	}//end fee_percentage
}//end class
