<div class="uw-edit-order-number no-link">

	<div class="uw-edit-order-number-inputs">
		
		<span class="uw-edit-order-number-input" style="display: inline-block; width: 90%;">
			<input id="uw_change_order_number_input_<?php echo $post->ID; ?>" type="text" value="<?php echo esc_attr( $order_id ); ?>" style="width: 100%;">
		</span>

		<span class="uw-edit-order-number-submit" style="display: inline-block; width: 5%;">
			<span class="uw_change_order_number_submit dashicons dashicons-image-rotate" data-current-order-number="<?php echo esc_attr( $order_id ); ?>" data-order-post-id="<?php echo $post->ID; ?>" style="cursor: pointer; width: 100%;"></span>
		</span>

	</div>

	<div class="uw-edit-order-number-result">

		<span class="uw_change_order_number_submit_processing_<?php echo $post->ID; ?>" style="display: none;"><?php _e( 'Processing...', WOOCOMMERCE_EDIT_ORDER_NUMBERS_TEXT_DOMAIN ); ?></span>

		<span id="uw_change_order_number_result_<?php echo $post->ID; ?>" style="display: none;"></span>

	</div>

</div>