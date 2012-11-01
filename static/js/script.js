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
		
	$('#home-images-carousel').carousel('cycle');
	
	// menu nav separators
	$('.menu li:last-child').addClass('last');
	if ($.browser.msie && $.browser.version < 9) {
		$('.menu li').append('<span class="ieseparator">•</span>');
		$('.menu li.last .ieseparator').remove();
	}
	
	// tons of tables? why not javascript? (\/) (°,,,°) (\/)
	$('.page-content table.blue').addClass('table table-striped');
});