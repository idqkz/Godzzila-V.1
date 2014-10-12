<div class="shop-menu">
	<div class="container">
		<div class='items-selector'>
			<?php 
				echo $this->element('items-selector-out'); 
			?>
		</div>

		<div class="items">
		<?php
			$items_html = null;
			foreach ($items as $item) {	
					$add_link = null;
					$add_link_2 = null;

					$image_html = $this->Html->div('image', 
						$this->Html->image(unserialize($item['Image']['medium']), array('class' => 'Image', 'alt' => $item['Item']['name']))
					);

					$description_html = $this->Html->div('name', $item['Item']['name']);
					$description_html .= $this->Html->div('description', $item['Item']['description']);

					//	проверка какие кнопки ставить
					if ($item['Item']['price_2'] != null) {

						$price = $this->Html->div('pcs', '4 штуки');
						$price .= $this->Html->div('pcs-price', $item['Item']['price'] . ' тг');
						$description_html .= $this->Html->div('qty-selector btn btn-primary', $price, array('data-quantity' => 4));

						$price = $this->Html->div('pcs', '8 штук');
						$price .= $this->Html->div('pcs-price', $item['Item']['price_2'] . ' тг');
						$description_html .= $this->Html->div('qty-selector btn btn-primary active', $price, array('data-quantity' => 8));
						$quantity = 8;

					} else {
						$quantity = 1;
					}

					$add_link = $this->Html->link('Добавить к заказу', 
						array('controller' => 'pages', 'action' => 'change_basket'),
						array('class' => 'btn btn-success add-to-basket-button', 'data-item-id' => $item['Item']['id'], 'data-quantity' => $quantity)
					);

					// Форма для добавления в корзину
					$description_html .= $this->Html->div('add-to-basket-button-wrapper', $add_link);
					$description_html = $this->Html->div('description-wrapper', $description_html);


				$items_html .= $this->Html->div('item', $image_html . $description_html);
			}

			echo $items_html;
		?>
		</div>

		<div class='items-selector down'>
			<?php 
				echo $this->element('items-selector-out'); 
			?>
		</div>
	</div>
</div>
<?php

	echo $this->Html->script('order_script');

?>