jQuery(document).ready(function ($) {
	let eventType = $('input[name="event-type"]');
	if( eventType.length > 0 ) {
		let selectedValue = $('input[name="event-type"]:checked').val();
		if (selectedValue == 'virtual') {
			$("#event-link").parents('.event-form-group').show();
			$("#event-address").parents('.event-form-group').hide();
		} else {
			$("#event-link").parents('.event-form-group').hide();
			$("#event-address").parents('.event-form-group').show();
		}
		eventType.on('change', function () {
			console.log($(this).val());
			if ($(this).val() == 'virtual') {
				$("#event-link").parents('.event-form-group').show();
				$("#event-address").parents('.event-form-group').hide();
			} else {
				$("#event-link").parents('.event-form-group').hide();
				$("#event-address").parents('.event-form-group').show();
			}
		});
	}
});