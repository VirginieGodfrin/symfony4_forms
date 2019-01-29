$(document).ready(function() {
	var $locationSelect = $('.js-article-form-location');
	// $specificLocationTarget represents the div that's around articleForm.specificLocationName
	var $specificLocationTarget = $('.js-specific-location-target');

	// event : change
	$locationSelect.on('change', function(e) { 
		// we make the AJAX call by reading the data-specific-location-url attribute. 
		// The location key in the data option will cause that to be set as a query parameter.
		$.ajax({
			url: $locationSelect.data('specific-location-url'), 
			data: {
				location: $locationSelect.val() 
			},
			success: function (html) { 
				if (!html) {
					$specificLocationTarget.find('select').remove(); 
					$specificLocationTarget.addClass('d-none');
					return; 
				}
				// Replace the current field and show
				$specificLocationTarget .html(html) .removeClass('d-none')
			} 
		});
	}); 
});