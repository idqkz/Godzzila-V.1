<div class="wrapper">
	<div class="conteiner">
		<div class='h1'>
			<p>Информация о заказе</p>
		</div>
		<?php

			echo $this->Html->div('order-info', 
				$this->Html->div('row', 
					$this->Html->div('name', $this->data['Order']['name'])
				.	$this->Html->div('address', $this->data['Order']['address'])
				.	$this->Html->div('time', $this->data['Order']['created'])
				.	$this->Html->div('total', $this->data['Order']['total'])
			));

			$items_html = $this->Html->div('row heading', 
				$this->Html->div('name', 'наименование')
			.	$this->Html->div('quantity', 'количество')
			.	$this->Html->div('total', 'под итог')
			.	$this->Html->div('link', '')
			);

			foreach ($this->data['Item'] as $item_data) {

				$name = $this->Html->div('name', $item_data['name']);
				$quantity = $this->Html->div('quantity', $item_data['ItemsOrder']['quantity']);
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
</div>