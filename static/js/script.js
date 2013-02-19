if (typeof jQuery != 'undefined'){
	jQuery(document).ready(function($) {
		Webcom.slideshow($);
		Webcom.analytics($);
		Webcom.handleExternalLinks($);
		Webcom.loadMoreSearchResults($);
		
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
		
		// ie fix for stripey tables
		if ($.browser.msie && $.browser.version < 9) {
			$('.table-striped tbody tr:nth-child(even) td, .table-striped tbody tr:nth-child(even) th').css('background-color','#E5ECF9');
			$('#faculty-excellence-awards .table-striped tbody tr:nth-child(even) td, #faculty-excellence-awards.table-striped tbody tr:nth-child(even) th').css('background-color','#fff');
			$('#faculty-excellence-awards .table-striped tbody tr:nth-child(odd) td, #faculty-excellence-awards.table-striped tbody tr:nth-child(odd) th').css('background-color','#E5ECF9');
		}
	});
}else{console.log('jQuery dependancy failed to load');}