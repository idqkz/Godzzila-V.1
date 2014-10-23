var shop_functions = {
	init: function() {
		//	добавление в корзину
		$('.shop-menu .add-to-basket-button').click(function() {
			shop_functions.add_to_basket($(this));
			return false;
		});

		//	выбор количества роллов перед добавлением в корзину
		$('.shop-menu .qty-selector').click(function() {
			//	change active state
			$(this).parent().find('.qty-selector').removeClass('active');
			$(this).addClass('active');
			//	change quantity
			$(this).parents('.item').find('.add-to-basket-button').attr('data-quantity', $(this).attr('data-quantity'));
			console.log($(this).attr('data-quantity'));
			return false;
		});

		//	изменение количества суши в корзине
		$('.quantity-box .btn').click(function() {
			//	записываем объект для использования его после выполнения аякса
			clicked_obj = $(this);
			//	отправляем айди наименования и изменение в количестве
			item_data = {item_id : clicked_obj.attr('data-item-id'), quantity: clicked_obj.attr('data-quantity')};
			$.ajax({
				url: clicked_obj.attr('href'),
				data: item_data,
				complete: function(respnonse_data) {
					respnonse_data = JSON.parse(respnonse_data['responseText']);
					//	обновляем:
						//	итог заказа
						$('.basket-wrapper .order-total .value').html(respnonse_data.order_total);
						if (!(clicked_obj.attr('data-item-id') in respnonse_data.items)) {
							clicked_obj.parents('.row').animate({opacity: 0}, 250, function() {$(this).remove();});
						} else {
							// итог для наименования 
							clicked_obj.parents('.row').find('.sub-total .value').html(respnonse_data.items[clicked_obj.attr('data-item-id')]['sub_total']);
							//	количество наименования
							clicked_obj.parents('.row').find('.quantity .value').html(respnonse_data.items[clicked_obj.attr('data-item-id')]['quantity']);
						}
				}
			})
			return false;
		});

		//	удаление наименования из корзины
		$('.remove-item-link').click(function() {
			clicked_obj = $(this);
			$.ajax({
				url: clicked_obj.attr('href'),
				complete: function(respnonse_data) {
					respnonse_data = JSON.parse(respnonse_data['responseText']);
					$('.basket-wrapper .order-total .value').html(respnonse_data.order_total);
					//	обновить итоговую сумму заказа
					clicked_obj.parents('.row').animate({opacity: 0}, 250, function() { $(this).remove()});
				}
			})
			return false;
		})

		console.log('init');
	},
	add_to_basket: function(clicked_obj) {
		item_data = {item_id: clicked_obj.attr('data-item-id')};
		item_data.quantity = clicked_obj.attr('data-quantity');
		clicked_item_image = clicked_obj.parents('.item').find('.image img');
		//	ajax
		$.ajax({
			url: clicked_obj.attr('href'),
			data: item_data,
			complete: function(respnonse_data) {
				if (respnonse_data['responseText']) {
					//	find position of the image
					position = site_functions.find_position(clicked_item_image[0]);
					//	create a new object 
					clicked_item_image.clone().addClass('animation-img').css({top: position.top, left: position.left}).appendTo('body');
					//	destination position
					destination = site_functions.find_position($('.main-menu .basket')[0]);

					console.log(destination.left + '-' + ($('.animation-img').outerWidth())/2 + '+' + $('.main-menu .basket').outerWidth()/2);
					var add_to_basket_animation = new TimelineLite();
					add_to_basket_animation
					.pause()
					.to($('.animation-img'), 1, {left: destination.left + $('.main-menu .basket').outerWidth()/2})
					.to($('.animation-img'), .75, {top: window.scrollY + 50}, '-=.7')
					.to($('.animation-img'), .5, {width: 0, height: 0}, '-=0.7')
					.to($('.animation-img'), .25, {opacity: 0}, '-=0.2')
					.play()
					.eventCallback('onComplete', function() {
						$('.animation-img').remove();
					})
					;
				};
			}
		})
	}
}

$(document).ready(function() {
	shop_functions.init();
})