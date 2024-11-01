jQuery(document).ready(function($) {

	$('.uw_change_order_number_submit').click(function(e) {

		e.preventDefault;

		var $this = $(this),
			post_id = $this.data('order-post-id'),
			processing = $('.uw_change_order_number_submit_processing_' + post_id),
			result = $('#uw_change_order_number_result_' + post_id),
			current_order_number = $this.data('current-order-number'),
			input = $('#uw_change_order_number_input_' + post_id),
			new_order_number = input.val().replace('<script>', '').replace('</script>', '').replace('<?', '').replace('?>', '');			

		result.html(''); // Remove any result still showing
		processing.show(); // Show processing message

		data = {
			action: 'change_order_numbers',
			change_order_numbers_nonce: change_order_numbers.change_order_numbers_nonce,
			current_order_number: current_order_number,
			new_order_number: new_order_number,
			order_post_id: post_id
		};


		$.post(ajaxurl, data, function(response) {
			
			if ( response.status == 'success' ) {

				var row_title = $this.closest('td').siblings('td.order_title').find('.row-title');
				row_title.text( '#' + response.order_id );
			}

			input.val(new_order_number); // Sanitized input
			processing.hide(); // Hide processing message
			result.html(response.message).show().delay(5000).fadeOut(); // Show then hide result
		});

		return false;
	});
});