<div class="wrapper">
	<div class="conteiner">
		<?php 
			if($id != null){
				echo $this->Form->create('All_form', array('url' => array('controller' => 'admin', 'action' => 'order_change/'.$id)));
				echo $this->Form->input('Order.status', array('label' => 'Статус',
					'options' => $status_orders));
				echo $this->Html->div('submit', $this->Form->submit('OK',array('class' => 'btn btn-success')));

				echo '<br><hr>';

								echo $this->Html->div('table');
				echo $this->Html->div('col-10');
				echo $this->Html->div('name col-2', 'Название');
				echo $this->Html->div('kol col-2', 'Количество');
				echo $this->Html->div('price col-1', 'Цена');
				echo $this->Html->div('kol_price col-1', 'Итого');
				echo '</div>';
				$pay_all = null;
				foreach ($orders as $order) {
					if ($order['Order']['id'] == $id){
						foreach ($order['Item'] as $i => $item) {
							$kol_plus = $this->Html->link('+', 
								array('controller' => 'admin', 
									'action' => $this->action, $item['ItemsOrder']['id'], 'plus'),
								array('class' => 'btn btn-success'));
							$kol_minus = $this->Html->link('-', 
								array('controller' => 'admin', 
									'action' => $this->action, $item['ItemsOrder']['id'], 'minus'),
								array('class' => 'btn btn-success'));
							echo $this->Html->div('col-10');
							echo $this->Html->div('name col-2', $item['name']);
							echo $this->Html->div('kol col-2', $kol_plus.$item['ItemsOrder']['kol'].$kol_minus);
							echo $this->Html->div('price col-1', $item['price']);
							$price_all = $item['price']*$item['ItemsOrder']['kol'];
							$pay_all += $price_all;
							echo $this->Html->div('kol_price col-1', $price_all);
							echo $this->Html->div('submit col-1' , $this->Html->link('Х', 
								array('controller' => 'admin', 'action' => 'delete_item_from_order', $item['ItemsOrder']['id']),
								array('class' => 'btn btn-danger')));
							echo '</div>';
						}
						echo $this->Html->div('pay_all col-10','Итого: '.$pay_all);
					}
				}
				echo '</div><hr>';

				// echo $this->Form->input('ItemsOrder.yes', array('label' => 'Добавить?', 'type' => 'checkbox'));
				echo $this->Form->input('ItemsOrder.item_id', array('value' => $items_list, 'options' => $items_list, 
					'label' => 'Добавить наименование',	'default' => 0));
				echo $this->Form->input('ItemsOrder.kol', array('label' => 'Количество'));
				echo $this->Html->div('submit', $this->Form->submit('OK',array('class' => 'btn btn-success')));
				
				echo '<hr>';

				echo $this->Form->input('Order.name', array('label' => 'ФИО*', 'required'=> true));
				echo $this->Form->input('Order.email', array('label' => 'Е-mail'));
				echo $this->Form->input('Order.adress', array('label' => 'Адресс*', 'required'=> true));
				echo $this->Form->input('Order.phone', array('label' => 'Телефон*', 'required'=> true));
				echo $this->Form->input('Order.message', array('label' => 'Коментарии', 'type' => 'textarea'));
				echo $this->Form->hidden('Order.stiker');
				echo $this->Form->hidden('Order.id');
				echo $this->Html->div('submit col-1', $this->Form->submit('Изменить',array('class' => 'btn btn-success')));
				echo $this->Form->end();
			}
			echo $this->element('order-all-out');
		?>
	</div>
</div>