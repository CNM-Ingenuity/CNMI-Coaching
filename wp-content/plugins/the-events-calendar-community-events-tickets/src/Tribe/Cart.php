<?php

class Tribe__Events__Community__Tickets__Cart {
	/**
	 * get the gateway used by this ecommerce adapter
	 */
	public function gateway() {
		return Tribe__Events__Community__Tickets__Main::instance()->gateway( 'PayPal' );
	}//end gateway

	/**
	 * loops over items in an order and breaks them down into receivers, amounts, and opportunities for fees
	 *
	 * @param array $items Items to loop over (cart items, order items, etc)
	 *
	 * @return array Array of receivers and fees
	 */
	public function parse_order( $items ) {
		$receivers = array();
		$fees = array();

		$main = Tribe__Events__Community__Tickets__Main::instance();
		$options = get_option( Tribe__Events__Community__Tickets__Main::OPTIONNAME );

		if ( $main->is_split_payments_enabled() ) {
			$site_receiver_email = $options['paypal_receiver_email'];
		} else {
			$woocommerce_options = get_option( 'woocommerce_paypal_settings' );
			$site_receiver_email = isset( $woocommerce_options['receiver_email'] ) ? $woocommerce_options['receiver_email'] : '';
		}

		if ( count( $items ) > 0 ) {
			foreach ( $items as $item ) {
				if ( empty( $item['quantity'] ) && empty( $item['qty'] ) ) {
					continue;
				}

				$event_id = get_post_meta( $item['product_id'], '_tribe_wooticket_for_event', true );

				// if the event doesn't exist, skip
				if ( ! $event_id || ! ( $event = get_post( $event_id ) ) ) {
					continue;
				}

				$event_creator = get_user_by( 'id', $event->post_author );
				$receiver_email = $site_receiver_email;

				if ( $main->is_split_payments_enabled() ) {
					$creator_options = $main->payment_options_form()->get_meta( $event_creator->ID );
					$receiver_email = $creator_options['paypal_account_email'];
				}

				$payment_fee_setting = $main->get_payment_fee_setting( $event );

				$product_id = $item['product_id'];
				$line_item = $item['line_total'];
				$receiver_total = $this->gateway()->ticket_price( $line_item, 'pass' !== $payment_fee_setting );

				// set up the receiver
				if ( isset( $receivers[ $receiver_email ] ) ) {
					$receivers[ $receiver_email ]['amount'] = number_format( $receivers[ $receiver_email ]['amount'] + $receiver_total, 2, '.', '' );
				} else {
					$receiver = array(
						'user_id' => $event_creator->ID,
						'payment_fee_setting' => $payment_fee_setting,
						'email' => $receiver_email,
						'amount' => 0,
						'primary' => 'false',
					);

					$receiver['amount'] = number_format( $receiver['amount'] + $receiver_total, 2, '.', '' );
					$receivers[ $receiver_email ] = $receiver;
				}//end else

				// track flat fee deduction requirements
				if ( ! isset( $fees[ $receiver_email ] ) ) {
					$fees[ $receiver_email ] = array();
				}

				// only charge a single flat fee per event
				if ( ! isset( $fees[ $receiver_email ][ $event_id ] ) ) {
					$fees[ $receiver_email ][ $event_id ] = array(
						'event_id' => $event_id,
						'price' => $receiver_total,
					);
				} else {
					$fees[ $receiver_email ][ $event_id ]['price'] += $receiver_total;
				}
			}
		}

		return array(
			'receivers' => $receivers,
			'fees' => $fees,
		);
	}

	/**
	 * Calculate cart site fees. Site fees will only exist if the payment settings for at least one
	 * line item in an order is set to pass fees on to purchasers.
	 *
	 * @param WC_Cart $cart WooCommerce cart object
	 */
	public function calculate_cart_fees( $cart ) {
		if ( ! $cart instanceof WC_Cart ) {
			return;
		}

		$order_breakdown = $this->parse_order( $cart->get_cart() );

		$fees = 0;
		foreach ( $order_breakdown['receivers'] as $receiver_email => $receiver ) {
			if ( 'pass' !== $receiver['payment_fee_setting'] ) {
				continue;
			}

			if ( ! isset( $order_breakdown['fees'][ $receiver_email ] ) ) {
				continue;
			}

			foreach ( $order_breakdown['fees'][ $receiver_email ] as $fee ) {
				$fees += $this->calculate_ticket_fee_for_pass_setting( $fee['price'] );
			}
		}//end foreach

		if ( $fees ) {
			$cart->add_fee( __( 'Site Fees', 'tribe-events-community-tickets' ), $fees );
		}
	}

	/**
	 * When passing fees on to a user, we need to calculate the appropriate fees for a given amount
	 * such that the fees do not impact the amount that will be delivered to a community organizer. This
	 * method handles that!
	 *
	 * @param float $price Price of ticket
	 *
	 * @return float Fee that must be added to ticket
	 */
	public function calculate_ticket_fee_for_pass_setting( $price ) {
		// if the fees are passed on to the end user, the calculations for the actual total works like this:

		$flat_fee = $this->gateway()->fee_flat();
		$percentage = $this->gateway()->fee_percentage();

		$fee = round( $price * ( $percentage / 100 ), 2 ) + $flat_fee;

		return $fee;
	}
}
