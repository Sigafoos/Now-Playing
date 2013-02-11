$(document).ready(function(){
		$('#nowplaying a').click(function(){
			if (!$('#video').is(':visible')) {
			$('#video').slideDown('fast');
			$('#arrow').html('hide <i class="icon-angle-up icon-large"></i>');
			} else {
			$('#video').slideUp('fast');
			$('#arrow').html('listen <i class="icon-angle-down icon-large"></i>');
			}
			});
		});
