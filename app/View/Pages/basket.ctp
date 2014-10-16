<div class="basket-wrapper">
	<div class="container">
		<div class='h1'><p>Ваш заказ</p></div>
		<div class="order">
			<?php
				
				if ($basket['order_total'] == 0) :
					echo $this->Html->div('empty','Ваша корзина пуста');

				else:

					$table_cols = null;
					$pay_price = null;

					foreach ($basket['items'] as $item_id => $basket_item) {
						$item_data = $items_data[$item_id];

						$item_name =	$this->Html->div('name', $item_data['Item']['name']);
						$count =		$this->Html->div('quantity', $this->Html->para('value', $basket_item['quantity']) . ' шт');
						$item_image = 	$this->Html->image(unserialize($item_data['Image']['medium']));
						$item_image = 	$this->Html->div('image', $item_image);

						// Вывод цены, и кол-во шт для изменения корзины
						if ($item_data['Item']['price'] != null) {
							$item_price = 'Цена: 8шт-'.$item_data['Item']['price_2'].'тг, 4шт-'.$item_data['Item']['price'].'тг';
							$change_count = '4';
						} else {
							$item_price = 'Цена: ' . $item_data['Item']['price'].'тг';
							$change_count = '1';
						}

						$item_price = $this->Html->div('price', $item_price);

						// Кнопки + - Х
						$quantity_plus = $this->Html->link('+', 
							array('controller' => 'pages', 'action' => 'change_basket'),
							array('class' => 'btn btn-success', 'data-quantity' => $change_count, 'data-item-id' => $item_id));
						$quantity_minus = $this->Html->link('-', 
							array('controller' => 'pages', 'action' => 'change_basket'),
							array('class' => 'btn btn-success', 'data-quantity' => $change_count * -1, 'data-item-id' => $item_id));
						$del_link = $this->Html->link('X', 
							array('controller' => 'pages', 'action' => 'remove_basket', $item_id),
							array('class' => 'btn btn-danger remove-item-link'));

						$name = 		$this->Html->div('item-info', $item_image . $item_name);
						$quantity = 			$this->Html->div('quantity-box', $quantity_minus . $count . $quantity_plus);
						$sub_total = 	$this->Html->div('sub-total', $this->Html->para('value', $basket_item['sub_total']) . ' тг');
						$del_link = 	$this->Html->div('remove-link-wrapper', $del_link);

						$table_cols .= $this->Html->div('row', $name . $quantity . $sub_total . $del_link);
					}

					$table_cols .= $this->Html->div('row order-total', 'Итого: ' . $this->Html->para('value', $basket['order_total']) . ' тг');

					echo $this->Html->div('table', $table_cols);
				
			?>
		</div>
		<div class='h2'>
			<p>Заполните форму, и мы вам перезвоним для подтверждения заказа.</p>
		</div>
		<div class="form-to-send-order">
			<?php
				echo $this->Form->create('Order', array(
					'url' => array('controller' => 'pages', 'action' => 'add_order'),
					'inputDefaults' => array('label' => false)
				));
				//	TODO: нужно перенести установку статуса в модель, в beforeSave что бы при сохранении заказа без ID статус устанавливался
				//	а еще лучше, в базе данных прописать значение по умолчанию :) тогда код вообще никакой не надо писать
				echo $this->Form->hidden('status', array('value' => '1'));
				echo $this->Form->hidden('location');
				echo $this->Form->input('name', array('placeholder' => 'Как вас зовут', 'required' => true));
				echo $this->Form->input('phone', array(
					'type' => 'tel', 
					'pattern' => '8[0-9]{10}', 
					'required' => true, 
					'placeholder' => '8 707 456 789'));
				echo $this->Form->input('email', array('placeholder' => 'Е-mail (не обезательно)'));
				echo $this->Form->input('address', array('placeholder' => 'Адресс доставки', 'required' => true));
				
				echo $this->Form->input('message', array('placeholder' => 'Коментарий, опишите как быстрее всего вас найти', 'type' => 'textarea'));
				echo $this->Html->div('submit', $this->Form->submit('Сделать заказ', array('class' => 'btn btn-success', 'div' => false)));

				echo $this->Form->end();

			?>
		</div>

		<div id='map'></div>

		<script src="http://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
		<script type="text/javascript">
		ymaps.ready(init);

		function init() {
			var coords = [51.1278, 71.4307];
		    var myPlacemark,
		        myMap = new ymaps.Map('map', {
		            center: coords,
		            zoom: 12
		        });

		    // Слушаем клик на карте
		    myMap.events.add('click', function (e) {
		        var coords = e.get('coords');
		        console.log(coords);

		        // Если метка уже создана – просто передвигаем ее
		        if (myPlacemark) {
		            myPlacemark.geometry.setCoordinates(coords);
		        }
		        // Если нет – создаем.
		        else {
		            myPlacemark = createPlacemark(coords);
		            myMap.geoObjects.add(myPlacemark);
		            // Слушаем событие окончания перетаскивания на метке.
		            myPlacemark.events.add('dragend', function () {
		                getAddress(myPlacemark.geometry.getCoordinates());
		            });
		        }
		        getAddress(coords);
		        form = document.getElementById('OrderLocation');
		        form.value = ([coords[0].toPrecision(6), coords[1].toPrecision(6)].join(', '));
		    });

		    // Создание метки
		    function createPlacemark(coords) {
		        return new ymaps.Placemark(coords, {

		        }, {
		            preset: 'islands#violetStretchyIcon',
		            draggable: true
		        });
		    }

		    // Определяем адрес по координатам (обратное геокодирование)
		    function getAddress(coords) {
		        myPlacemark.properties.set('iconContent', 'поиск...');
		        ymaps.geocode(coords).then(function (res) {
		            var firstGeoObject = res.geoObjects.get(0);

		            myPlacemark.properties
		                .set({
		                    iconContent: firstGeoObject.properties.get('name'),
		                    balloonContent: firstGeoObject.properties.get('text')
		                });
		        });
		    }

		}
		</script>
		<?php
			endif;
		?>
	</div>
</div>

<?php

	echo $this->Html->script('order_script');

?>