$(document).ready(function() {

	if( $('.ajax-filter-form').length > 0 ) {

		var form = $('.ajax-filter-form');
		var url = form.data('url');
	
		form.find('input').change(function (e) {
			e.preventDefault();

			console.log(form.serialize());
			
			$.ajax({
				type: "GET",
				url: url,
				data: form.serialize(),
				success: function(response) {
					$('.page__body .page__content').html(response);
					var newUrl = $('.resources__all').data('url');
					history.pushState(null, null, newUrl);
				}
			});
		});
	}

});