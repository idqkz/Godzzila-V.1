	<?php

		echo $this->Html->div('order_list');
		echo $this->Html->div('col-10');
		echo $this->Html->div('id col-1', '№');
		echo $this->Html->div('status col-1', 'Статус');
		echo $this->Html->div('data col-3', 'Дата заказа');
		echo $this->Html->div('fio col-2', 'Фамилия Имя Отчество');
		echo $this->Html->div('phone col-2', 'Телефон');
		echo $this->Html->div('pay_price col-1', 'К оплате');
		echo $this->Html->div('submit col-1', ' ');
		echo '</div>';

		foreach ($orders as $order) {
			$pay_price = null;
			foreach ($order['Item'] as $i => $value) {
				$pay_price += $value['price'] * $value['ItemsOrder']['kol'];
			}
			
			echo $this->Html->div('col-10');
			echo $this->Html->div('id col-1',$order['Order']['id']);
			echo $this->Html->div('status col-1',$order['Order']['status']);
			echo $this->Html->div('data col-3',$order['Order']['created']);
			echo $this->Html->div('fio col-2',$order['Order']['name']);
			echo $this->Html->div('phone col-2',$order['Order']['phone']);
			echo $this->Html->div('pay_price col-1', $pay_price);
			echo $this->Html->div('submit col-1' , $this->Html->link('Изменить', 
						array('controller' => 'admin', 'action' => 'orders_all', $order['Order']['id'])//,
						// array('class' => 'btn btn-success')
						));
			echo '</div>';
		}
		
		echo '</div>';
	?>