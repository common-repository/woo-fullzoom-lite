jQuery(document).ready(function($) {
	var i = $('#woofz-product-image');
	
	function a($element) {
		if($element) {
			$thumbnails = $element.find('img');
			
			$thumbnails.show();
		}
	}

	function c() {
		if($('#woofz-thumbnails').length) {
			$el = $('#woofz-thumbnails').find('img');
		}
	}

	if($('#woofz-wrap').length) {
		a($('#woofz-thumbnails'));

		$('#woofz-thumbnails img').each( function(index) {
			$(this).click( function() {
				var $img = $(this).data('img');
				var $src = $(this).data('src');

				$('#woofz-product-image').attr('src', $img);

				$('#woofz-product-image').attr('data-img', $src);

				$('#woofz-thumbnails').find('img').removeClass('active');
				$(this).addClass('active');
			});
		});

		$(document).on('click', '#woofz-product-image', function(event) {
	            var $img = $(this).attr('data-img');
	            var $clone = $('#woofz-thumbnails').html();
	            var preloader_html = [
	            	'',
	            	'<div class="woofzpl-rotating-plane"><\/div>',
	            ];
	            var $preloader = (woofz_script_vars.preloader > 0 ? '<div class="woofz-preloader"><ul class="woofz-preloader-flex-container"><li>' + preloader_html[woofz_script_vars.preloader] + '<\/li><\/ul><\/div>' : '' );
	            var $content = $('<div id="woofz-fullscreen"><img src="' + $img + '">' + $preloader + '<figcaption id="woofz-fullscreen-thumbnails">' + $clone + '</figcaption></div>');
	            
			$('body').prepend($content);
			$('body').css('overflow', 'hidden');

			a($('#woofz-fullscreen-thumbnails'));
			
			u = event.clientY;
			$('#woofz-fullscreen > img').on('load', function() {
				var i = $('#woofz-fullscreen > img').height(),
				t = $(window).innerHeight(),
				r = t / 2 - i / 2,
				f = u * r / (t / 2);
				
				if( woofz_script_vars.preloader > 0 ) {
					$(".woofz-preloader").hide();
				}
				
				$("#woofz-fullscreen > img").show();
				$('#woofz-fullscreen > img').css({'top': f + 'px'});
			});
	        });

	        $(document).on("mousemove", "#woofz-fullscreen", function(event) {
			var i = this.querySelector("img").clientHeight,
			t = window.innerHeight,
			r = t / 2 - i / 2,
			u = event.clientY,
			f = u * r / (t / 2);
			
			if( i != 0 ) {
				this.querySelector("img").style.top = f + "px";
			}
	        });

	        $(document).on('keydown', function(e) {
	            if (e.keyCode == 27) {
				$('#woofz-fullscreen').remove();
				$('body').css('overflow', 'auto');
	            }
	        });

	        $(document).on("click", "#woofz-fullscreen", function(event) {
	            if(!$(event.target).closest('#woofz-fullscreen-thumbnails').length) {
	                $(this).remove();
	                $('body').css('overflow', 'auto');
			}
		});

		$(document).on("click", "#woofz-fullscreen-thumbnails img", function(event) {
			var $src = $(this).data('src');

			var i = $('#woofz-fullscreen > img').height(),
			t = $(window).innerHeight(),
			r = t / 2 - i / 2,
			u = event.clientY,
			f = u * r / (t / 2);
			
			$('#woofz-fullscreen > img').hide();
			
			if( woofz_script_vars.preloader > 0 ) {
				$(".woofz-preloader").show();
			}

			$("#woofz-fullscreen > img").one("load", function() {
				if( woofz_script_vars.preloader > 0 ) {
					$(".woofz-preloader").hide();
				}
				
				$("#woofz-fullscreen > img").show();
				$('#woofz-fullscreen > img').css({'top': f + 'px'});
			}).attr("src", $src);

			$('#woofz-fullscreen-thumbnails').find('img').removeClass('active');
			$(this).addClass('active');
		});
	}
});
