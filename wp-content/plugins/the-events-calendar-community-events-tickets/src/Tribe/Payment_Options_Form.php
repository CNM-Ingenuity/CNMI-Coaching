<?php

class Tribe__Events__Community__Tickets__Payment_Options_Form {
	public static $meta_key = 'tribe_events_community_tickets';
	public static $defaults = array(
		'paypal_account_email' => null,
		'payment_fee_setting' => null,
	);

	/**
	 * Sets a default settings value
	 *
	 * @param string $setting Payment Options form setting
	 * @param mixed $value Value for the Payment Options form setting
	 */
	public function set_default( $setting, $value ) {
		self::$defaults[ $setting ] = $value;
	}

	/**
	 * fetches payment options meta
	 */
	public static function get_meta( $user_id ) {
		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}

		$data = get_user_meta( $user_id, self::$meta_key, true );
		$data = wp_parse_args( $data, self::$defaults );

		return $data;
	}//end get_meta

	/**
	 * Handles the submission of the payment options form
	 *
	 * @param $user_id int User ID of the user to save data to
	 * @param $data array Array of posted data
	 */
	public function save( $user_id, $data ) {
		// make sure we only have keys that we care about
		$data = array_intersect_key( $data, self::$defaults );

		// make sure we have ALL the keys we want
		$data = wp_parse_args( $data, self::$defaults );

		if ( $data['paypal_account_email'] ) {
			$data['paypal_account_email'] = sanitize_email( $data['paypal_account_email'] );
		}//end if

		if ( 'pass' !== $data['payment_fee_setting'] ) {
			$data['payment_fee_setting'] = 'absorb';
		}

		return update_user_meta( $user_id, self::$meta_key, $data );
	}//end save

	/**
	 * renders the payment options UI
	 */
	public function render() {
		wp_enqueue_style( Tribe__Events__Main::POSTTYPE . '-community' );
		wp_enqueue_style( 'events-community-tickets' );

		tribe_get_template_part( 'community-tickets/modules/payment-options', null, array(
			'data' => self::get_meta( get_current_user_id() ),
		) );
	}//end render
}//end class
