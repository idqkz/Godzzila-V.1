<div class="wrapper">
	<div class="conteiner">
		<?php 
			if($id != null){
				echo $this->Form->create('All_form', array('url' => array('controller' => 'admin', 'action' => 'order_change/'.$id)));
				echo $this->Form->input('Order.status', array('label' => 'Статус',
					'options' => $status_orders));
				echo $this->Html->div('submit', $this->Form->submit('OK',array('class' => 'btn btn-success')));

				echo '<br><hr>';

				$table_html = $this->Html->div('row heading', 
					$this->Html->div('name col-4', 'Название')
				.	$this->Html->div('quantity col-2', 'Количество')
				.	$this->Html->div('price col-2', 'Цена')
				.	$this->Html->div('quantity_price col-2', 'Итого')
				);

				$pay_all = null;
				$orders_html = null;
				foreach ($orders as $order) {

					if ($order['Order']['id'] == $id){
						foreach ($order['Item'] as $i => $item) {
							$quantity_plus = $this->Html->link('+', 
								array('controller' => 'admin', 
									'action' => $this->action, $item['ItemsOrder']['id'], 'plus'),
								array('class' => 'btn btn-success'));
							$quantity_minus = $this->Html->link('-', 
								array('controller' => 'admin', 
									'action' => $this->action, $item['ItemsOrder']['id'], 'minus'),
								array('class' => 'btn btn-success'));

							echo $this->Html->div('col-10');

								echo $this->Html->div('name col-4', $item['name']);
								echo $this->Html->div('quantity col-2', $quantity_plus.$item['ItemsOrder']['quantity'].$quantity_minus);
								echo $this->Html->div('price col-2', $item['price']);
								$price_all = $item['price']*$item['ItemsOrder']['quantity'];
								$pay_all += $price_all;
								echo $this->Html->div('quantity_price col-2', $price_all);
								echo $this->Html->div('submit col-2' , $this->Html->link('Х', 
									array('controller' => 'admin', 'action' => 'delete_item_from_order', $item['ItemsOrder']['id']),
									array('class' => 'btn btn-danger')));

							echo '</div>';
						}
						echo $this->Html->div('pay_all col-10','Итого: '.$pay_all);
					}

				}

				// echo $this->Form->input('ItemsOrder.yes', array('label' => 'Добавить?', 'type' => 'checkbox'));
				echo $this->Form->input('ItemsOrder.item_id', array('value' => $items_list, 'options' => $items_list, 
					'label' => 'Добавить наименование',	'default' => 0));
				echo $this->Form->input('ItemsOrder.quantity', array('label' => 'Количество'));
				echo $this->Html->div('submit', $this->Form->submit('OK',array('class' => 'btn btn-success')));
				
				echo '<hr>';

				echo $this->Form->input('Order.name', array('label' => 'ФИО*', 'required'=> true));
				echo $this->Form->input('Order.email', array('label' => 'Е-mail'));
				echo $this->Form->input('Order.address', array('label' => 'Адресс*', 'required'=> true));
				echo $this->Form->input('Order.phone', array('label' => 'Телефон*', 'required'=> true));
				echo $this->Form->input('Order.message', array('label' => 'Коментарии', 'type' => 'textarea'));
				echo $this->Form->hidden('Order.stiker');
				echo $this->Form->hidden('Order.id');
				echo $this->Html->div('submit col-1', $this->Form->submit('Изменить',array('class' => 'btn btn-success')));
				echo $this->Form->end();

				echo $this->Html->div('table', $table_html);
			}
			echo $this->element('order-all-out');
		?>
	</div>
</div>