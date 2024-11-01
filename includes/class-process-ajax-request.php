<?php

/**
 *	Process the AJAX request and update the order number
 *	@package WooCommerce Edit Order Numbers
 *	@author UltimateWoo
 */

if ( ! class_exists( 'WooCommerce_Edit_Order_Numbers_Process_Ajax_Request' ) ):

class WooCommerce_Edit_Order_Numbers_Process_Ajax_Request {

	public function __construct() {
		$this->hooks();
	}

	/**
	 *	Start
	 */
	public function hooks() {

		add_action( 'wp_ajax_change_order_numbers', array( $this, 'process_ajax' ) );
	}

	/**
	 *	Process the AJAX request
	 */
	public function process_ajax() {

		// Check permission
		if ( ! current_user_can( apply_filters( 'edit_order_numbers_permissions', 'manage_options' ) ) ) {
			die();
		}

		// Validate nonce authentication
		if ( ! isset( $_POST['change_order_numbers_nonce'] ) || ! wp_verify_nonce( $_POST['change_order_numbers_nonce'], 'change_order_numbers_nonce' ) ) {
			wp_send_json( array( 
				'status' => 'failed',
				'message' => __( 'Permission denied.', WOOCOMMERCE_EDIT_ORDER_NUMBERS_TEXT_DOMAIN )
			) );
		}

		// Current order number, requested order number and original order's post ID
		if ( isset( $_POST['current_order_number'] ) && isset( $_POST['new_order_number'] ) && isset( $_POST['order_post_id'] ) ) {

			$current_order_number = sanitize_text_field( $_POST['current_order_number'] );
			$new_order_number = sanitize_text_field( $_POST['new_order_number'] );
			$order_post_id = sanitize_text_field( $_POST['order_post_id'] );

		} else {
			wp_send_json( array(
				'status' => 'failed',
				'message' => __( 'Request failed.', WOOCOMMERCE_EDIT_ORDER_NUMBERS_TEXT_DOMAIN )
			) );
		}

		// Get the original order
		$order = get_post( $order_post_id );

		// Determines if there are conflicts with another post ID
		$conflicts = get_post_status( $new_order_number );

		// Determines if there are conflicts with another order number
		$conflicting_order_ids = new WP_Query(
			array(
				'meta_query' => array(
					'relation' => 'OR',
					array(
						'key' => '_order_number',
						'value' => $new_order_number,
						'compare' => '=',
					),
					array(
						'key' => '_order_number_formatted',
						'value' => $new_order_number,
						'compare' => '=',
					),
				),
			)
		);

		// This acts as a second check for conflicts
		if ( $conflicting_order_ids->have_posts() ) {
			$conflicts = true;
		}

		/**
		 *	1. Check to make sure the original order exists
		 *	2. Make sure there are no conflicts (other post IDs and order numbers)
		 *	3. The exception to #2 is if the requested order # is the same as the order post ID
		 */
		if ( $order && ( ! $conflicts || $new_order_number === $order_post_id ) ) {

			update_post_meta( $order_post_id, '_order_number', $new_order_number );

			update_post_meta( $order_post_id, '_order_number_formatted', $new_order_number );

			$response = array(
				'status' => 'success',
				'message' => __( 'Order Number Updated!', WOOCOMMERCE_EDIT_ORDER_NUMBERS_TEXT_DOMAIN ),
				'order_id' => $new_order_number ? intval( $new_order_number ) : $order_post_id
			);

			wp_send_json( $response );
		
		} elseif ( $conflicts ) {

			$response = array(
				'status' => 'failed',
				'message' => __( 'Request failed. That input conflicts with a post or another order.', WOOCOMMERCE_EDIT_ORDER_NUMBERS_TEXT_DOMAIN )
			);

			wp_send_json( $response );
		}
	}
}

endif;

new WooCommerce_Edit_Order_Numbers_Process_Ajax_Request;