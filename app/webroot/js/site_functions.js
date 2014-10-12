var site_functions = {
	class_to_add: 'animated',
	init: function() {

		fade_in = document.getElementsByClassName('fade-in');

		for (var i = fade_in.length - 1; i >= 0; i--) {
			site_functions.fade_in(fade_in[i], site_functions.find_position(fade_in[i]).top);
		};

		site_functions.sticky('.main-menu', 'fixed', '.main-menu');

	},
	sticky: function(item_to_watch, class_to_add, sticky_top_class) {
		var sticky = document.querySelector(item_to_watch);

		if (sticky.style.position !== 'sticky') {
		  var stickyTop = $(sticky_top_class).outerHeight();
		  return document.addEventListener('scroll', function () {
		  	if (window.scrollY >= stickyTop) {
		  		sticky.classList.add(class_to_add);
		  	} else {
		  		sticky.classList.remove(class_to_add);
		  	}
		  });
		}
	},
	find_position: function(obj) {
		var curleft = curtop = 0;
		if (obj.offsetParent) { do { curleft += obj.offsetLeft; curtop += obj.offsetTop; } while (obj = obj.offsetParent); }
		return {left: curleft, top: curtop};
	},
	fade_in: function(item_to_watch, threshold) {
		
		init_top = item_to_watch.style.top;
		init_left = item_to_watch.style.left;
		init_width = item_to_watch.style.width;

		return document.addEventListener('scroll', function () {
			setTimeout(function() {
				if ((window.scrollY +  window.innerHeight) >= (threshold + 100)) {
					if (item_to_watch.classList.contains(site_functions.class_to_add) == false) {
						item_to_watch.classList.add(site_functions.class_to_add);
						TweenLite.to(item_to_watch, 1, {opacity: 1});
					}
				} else {
					item_to_watch.classList.remove(site_functions.class_to_add);
					item_to_watch.style.opacity = 0;
					item_to_watch.style.top = init_top;
					item_to_watch.style.left = init_left;
					item_to_watch.style.width = init_width;
				}
			}, 100)
			
		});
	},
	push_history: function(href) {
		state = $('html').html();
		window.history.pushState(state, $('html title').html(), window.location.href.substring(0, window.location.href.lastIndexOf('#') + 0) + href);
	},
	
	carousel_next: function(clicked) {
		mar_l = ($('.interior .carousel .item:first()').outerWidth(true)) * -1;
		if (clicked.hasClass('left')) {
			//	animate left
			$('.interior .carousel .item:first()').animate({marginLeft: mar_l}, 250, function() {
				$('.interior .carousel .item:first()').detach().css({marginLeft: 0}).appendTo('.interior .carousel .inner');
			})
		} else {
			//	animate right
			$('.interior .carousel .item:last()').detach().css({marginLeft: mar_l}).prependTo('.interior .carousel .inner');
			$('.interior .carousel .item:first()').animate({marginLeft: 0}, 250);
		}
	},
	carousel_zoom: function(clicked) {
		image = $('<img src="' + clicked.attr('data-large') + '"/>');
		$('.popup-bg').append(image).fadeIn(100);
		$('.popup-bg').click(function() {
			$(this).html('').fadeOut(200);
		})

	}
}
$(document).ready(function() {
	site_functions.init();
})