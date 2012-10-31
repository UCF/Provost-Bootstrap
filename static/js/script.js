$().ready(function() {
	
	$('.select-links')
		.change(function() {
			var selected = $(this).children('option:selected');
			if(typeof selected != 'undefined') {
				var url = selected.val();
				if(url != '') {
					window.location = url;
				}
			}
		});
});