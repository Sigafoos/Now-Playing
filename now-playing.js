$(document).ready(function(){
		$('#nowplaying a').click(function(){
			if (!$('#video').is(':visible')) {
			$('#video').slideDown('fast');
			$('#arrow').html('<i class="icon-angle-up icon-large"></i>');
			} else {
			$('#video').slideUp('fast');
			$('#arrow').html('<i class="icon-angle-down icon-large"></i>');
			}
			});
		});
