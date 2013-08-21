$(document).ready(function() {
	// Sort by name
	$('#header .name').click(function() {
		
		$('#header .size, #header .modtime').removeClass('asc desc');

		if ($(this).hasClass('desc')) {
			$($('#file_list ul li').toArray().sort(function(a, b) {					
		    	return $(a).attr('data-name').localeCompare($(b).attr('data-name'));
			})).appendTo('#file_list ul');

			$(this).removeClass('desc');
			$(this).addClass('asc');
		} else {
			$($('#file_list ul li').toArray().sort(function(a, b) {					
		    	return $(b).attr('data-name').localeCompare($(a).attr('data-name'));
			})).appendTo('#file_list ul');

			$(this).removeClass('asc');
			$(this).addClass('desc');
		}
	});

	// Sort by size
	$('#header .size').click(function() {
		
		$('#header .name, #header .modtime').removeClass('asc desc');

		if ($(this).hasClass('desc')) {
			$($('#file_list ul li').toArray().sort(function(a, b) {					
		    	return $(a).attr('data-size') - $(b).attr('data-size');
			})).appendTo('#file_list ul');

			$(this).removeClass('desc');
			$(this).addClass('asc');
		} else {
			$($('#file_list ul li').toArray().sort(function(a, b) {					
		    	return $(b).attr('data-size') - $(a).attr('data-size');
			})).appendTo('#file_list ul');

			$(this).removeClass('asc');
			$(this).addClass('desc');
		}
	});

	// Sort by last write time
	$('#header .modtime').click(function() {
		
		$('#header .name, #header .size').removeClass('asc desc');

		if ($(this).hasClass('desc')) {
			$($('#file_list ul li').toArray().sort(function(a, b) {					
		    	return $(a).attr('data-time') - $(b).attr('data-time');
			})).appendTo('#file_list ul');

			$(this).removeClass('desc');
			$(this).addClass('asc');
		} else {
			$($('#file_list ul li').toArray().sort(function(a, b) {					
		    	return $(b).attr('data-time') - $(a).attr('data-time');
			})).appendTo('#file_list ul');

			$(this).removeClass('asc');
			$(this).addClass('desc');
		}
	});
});