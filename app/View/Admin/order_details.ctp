<div class="wrapper">
	<div class="conteiner">
		<div class='h1'>
			<p>Информация о заказе</p>
		</div>
		<?php

			echo $this->Html->div('order-info col-10', 
				$this->Html->div('row', 
					$this->Form->create('Order')
				.	$this->Form->hidden('id')
				.	$this->Form->input('Order.status', array('label' => 'Статус', 'options' => $options_status))
				.	$this->Form->input('info.time', array('label' => 'Время доставки', 'options' => $options_time))
				.	$this->Form->input('Order.name', array('label' => 'Имя'))
				.	$this->Form->input('Order.phone', array('label' => 'Телефон', 'disabled' => 'disabled'))
				.	$this->Form->input('Order.address', array('label' => 'Адресс'))
				// .	$this->Html->link('показать на карте', 
						// array('controller' => 'admin', 'action' => 'show_order_on_map', $this->data['Order']['id']), 
						// array('class' => 'show-order-on-map'))
				.	$this->Html->div('time', $this->data['Order']['created'])
				.	$this->Html->div('total', $this->data['Order']['total'])
				.	$this->Form->submit('Сохранить', array('class' => 'btn btn-success'))
				.	$this->Form->end()
			));

			/*echo $this->Html->div('order-info col-10', 
				$this->Html->div('row', 
					$this->Html->div('name', $this->data['Order']['name'])
				.	$this->Html->div('address', $this->data['Order']['address'])
				.	$this->Html->div('time', $this->data['Order']['created'])
				.	$this->Html->div('total', $this->data['Order']['total'])
			));*/
			$edit_link = $this->Html->link('Ред. заказ',
				array('controller' => 'admin', 'action' => 'order_items_edit', $this->data['Order']['id']),
				array('class' => 'btn btn-primary'));

			$items_html = $this->Html->div('row heading', 
				$this->Html->div('name', 'наименование')
			.	$this->Html->div('quantity', 'количество')
			.	$this->Html->div('total', 'под итог')
			.	$this->Html->div('link', $edit_link)
			);

			foreach ($this->data['Item'] as $item_data) {
				($item_data['price'] != null ? $change_count = '4' : $change_count = '1');
				$quantity_plus = $this->Html->link('+', 
					array('controller' => 'admin', 'action' => 'order_items_change_quantity', $id, $item_data['ItemsOrder']['id'], $change_count),
					array('class' => 'btn btn-success'));
				$quantity_minus = $this->Html->link('-', 
					array('controller' => 'admin', 'action' => 'order_items_change_quantity', $id, $item_data['ItemsOrder']['id'], $change_count*-1),
					array('class' => 'btn btn-success'));

				$name = $this->Html->div('name', $item_data['name']);
				$quantity = $this->Html->div('quantity', $quantity_minus . $item_data['ItemsOrder']['quantity'] . $quantity_plus);
				$sub_total = $this->Html->div('sub_total', $item_data['ItemsOrder']['total']);
				$remove = $this->Html->div('remove', 
					$this->Html->link('x', 
						array('controller' => 'admin', 'action' => 'remove_item_from_order', $item_data['ItemsOrder']['id']),
						array('class' => 'btn btn-danger')
					)
				);
				$items_html .= $this->Html->div('row', $name . $quantity . $sub_total . $remove);

			}

			$items_html .= $this->Html->div('row total', 
				$this->Html->div('name', '')
			.	$this->Html->div('quantity', 'итого')
			.	$this->Html->div('total', $this->data['Order']['total'])
			.	$this->Html->div('link', '')
			);

			echo $this->Html->div('order-items-wrapper', $this->Html->div('order-items', $items_html));

		?>
	</div>
	<script type="text/javascript">
		$('.show-order-on-map').click(function() {
			$.ajax({
				url: $(this).attr('href'),
				complete: function(response_data) {
					$('body').append(response_data['responseText']);
				}
			})
			return false;
		})
	</script>
</div>