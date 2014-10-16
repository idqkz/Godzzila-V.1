<div class="wrapper">
	<div class="conteiner">
		<?php

			$orders_html = null;
			foreach ($orders as $order_data) {
				$date = $this->Html->div('date', $order_data['Order']['created']);
				$address = $this->Html->div('address', $order_data['Order']['address']);
				$order_total = $this->Html->div('total', $order_data['Order']['total'] . ' тг');
				$button = $this->Html->div('btn-wrapper', 
					$this->Html->link('редактировать', 
						array('controller' => 'admin', 'action' => 'order_details', $order_data['Order']['id']),
						array('class' => 'btn btn-primary')
					));
				$orders_html .= $this->Html->div('row', $date . $address . $order_total . $button);
			}
			echo $this->Html->div('orders', $orders_html);
		?>
	</div>
</div>