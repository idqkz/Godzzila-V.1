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
						$kol_plus = $this->Html->link('+', 
							array('controller' => 'pages', 'action' => 'change_basket'),
							array('class' => 'btn btn-success', 'data-quantity' => $change_count, 'data-item-id' => $item_id));
						$kol_minus = $this->Html->link('-', 
							array('controller' => 'pages', 'action' => 'change_basket'),
							array('class' => 'btn btn-success', 'data-quantity' => $change_count * -1, 'data-item-id' => $item_id));
						$del_link = $this->Html->link('X', 
							array('controller' => 'pages', 'action' => 'remove_basket', $item_id),
							array('class' => 'btn btn-danger remove-item-link'));

						$name = 		$this->Html->div('item-info', $item_image . $item_name);
						$kol = 			$this->Html->div('quantity-box', $kol_minus . $count . $kol_plus);
						$sub_total = 	$this->Html->div('sub-total', $this->Html->para('value', $basket_item['sub_total']) . ' тг');
						$del_link = 	$this->Html->div('remove-link-wrapper', $del_link);

						$table_cols .= $this->Html->div('row', $name . $kol . $sub_total . $del_link);
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
				echo $this->Form->hidden('status', array('value' => '1'));
				echo $this->Form->input('name', array('placeholder' => 'Как вас зовут', 'required' => true));
				echo $this->Form->input('phone', array(
					'type' => 'tel', 
					'pattern' => '8[0-9]{10}', 
					'required' => true, 
					'placeholder' => '8 707 456 789'));
				echo $this->Form->input('email', array('placeholder' => 'Е-mail (не обезательно)'));
				echo $this->Form->input('adress', array('placeholder' => 'Адресс доставки', 'required' => true));
				
				echo $this->Form->input('message', array('placeholder' => 'Коментарии', 'type' => 'textarea'));
				echo $this->Form->hidden('stiker');
				echo $this->Html->div('submit', $this->Form->submit('Сделать заказ', array('class' => 'btn btn-success', 'div' => false)));

				echo $this->Form->end();

				endif;
			?>
		</div>

		
	</div>
</div>

<?php

	echo $this->Html->script('order_script');

?>