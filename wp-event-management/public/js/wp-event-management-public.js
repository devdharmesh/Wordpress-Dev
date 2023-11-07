jQuery(document).ready(function ($) {
	$('#event-date-time').datetimepicker({
		minDate: new Date(),
	});

	$('input[name="event-type"]').on('change', function () {
		$type = $(this).val();
		$(".event-form .event-type-relation").parent().hide();
		$(`.event-form .event-type-${$type}.event-type-relation`).parent().show();
	});

	$(".event-form .event-form-submit").on('click', function (e) {
		e.preventDefault(), e.stopPropagation();
		$button = $(this);
		// $button.prop('disabled', true);

		$messgeEle = $(".event-form .event-form-message");
		$messgeEle.html('').removeClass('alert-danger').removeClass('alert-success');

		let name = $('#event-name').val(),
			description = $('#event-description').val(),
			dateTime = validator.toDate($('#event-date-time').val()),
			organizer = $('#event-organizer-name').val(),
			type = $('input[name="event-type"]:checked').val(),
			address = $('#event-address').val(),
			link = $('#event-link').val(),
			city = $('#event-city').val();

		let fileInput = $('#event-image');

		let error = [];
		if (!validator.contains(name)) {
			error.push('Event Name is required');
		}

		if (!validator.contains(description)) {
			error.push('Event Description is required');
		}

		if (!validator.isDate(dateTime)) {
			error.push('Event Date and Time is required');
		}

		if (!validator.contains(organizer)) {
			error.push('Event Organizer Name is required');
		}

		if( type == 'virtual' ) {
			if (!validator.isURL(link, {require_host: true, require_protocol: true})) {
				error.push('Event Link is required');
			}
		} else {
			if (!validator.contains(address)) {
				error.push('Event Address is required');
			}
		}

		if (!validator.contains(city)) {
			error.push('Event City is required');
		}

		const file = fileInput[0].files[0];
		if (!file) {
			error.push('Event Image is required');
		} else {
			const fileExtension = file.name.split('.').pop().toLowerCase();
			if( file.size > 2 * 1024 * 1024 ) {
				error.push('Event Image should be less than 2 MB');
			} else if (fileExtension != 'jpg' && fileExtension != 'jpeg' && fileExtension != 'png') {
				error.push('Event Image should be jpg, jpeg or png');
			}
		}

		if (error.length > 0) {
			$button.prop('disabled', false);
			$messgeEle.html(error.join('<br>')).addClass('alert-danger');
		} else {
			$button.prop('disabled', true);
			$messgeEle.html('').removeClass('alert-danger alert-success');
			
			let formData = new FormData();
			formData.append('name', name);
			formData.append('description', description);
			formData.append('date_time', dateTime);
			formData.append('organizer', organizer);
			formData.append('type', type);
			formData.append('address', address);
			formData.append('link', link);
			formData.append('city', city);
			formData.append('file', file);
			formData.append('action', 'wp_event_management_save_event');
			formData.append('nonce', wp_event_management.nonce);
			// send ajax
			$.ajax({
				url: wp_event_management.ajax_url,
				type: 'post',
				data: formData,
				contentType: false,
				processData: false,
				success: function (response) {
					$button.prop('disabled', false);
					if( response.success ) {
						$messgeEle.html(response.data).addClass('alert-success');
						$(".event-form .event-form-wrapper").trigger('reset');
						setTimeout(function () {
							$messgeEle.html('').removeClass('alert-danger alert-success');
						}, 5000);
					} else {
						$messgeEle.html(response.data).addClass('alert-danger');
					}
				},
				error: function () {
					$button.prop('disabled', false);
					$messgeEle.html('Something went wrong. Please try again.').addClass('alert-danger');
				}
			})
		}
	});
});