<div class="wrapper">
	<div class="conteiner">
		<?php 
			$basket = $this->Session->read('basket');
			if ($basket != null) :
			// echo $this->html->div('empty','Ваша корзина пуста');
			// } else:
		?>
		<div class="order">
			<div class="order-table">
				<div class="head-order">
					<div class="h2">Ваша корзина</div>
					<?php
						echo $this->html->div('name', 'Название');
						echo $this->html->div('kol', 'Количество');
						// echo $this->html->div('price col-1', 'Цена');
						echo $this->html->div('kol_price', 'Цена');
					?>
				</div>
					<?php
						$i = 0;
						$fin_price = null;
						foreach ($basket as $item_id => $kol):

							$kol_plus = $this->Html->div('plus', $this->html->link('+', 
								array('controller' => 'pages', 'action' => $this->action, 'plus', $item_id)));

							$kol_minus = $this->Html->div('minus', $this->html->link('-', 
								array('controller' => 'pages', 'action' => $this->action, 'minus', $item_id)));

							$kol_price = $kol*$items[$item_id]['Item']['price'];

							$fin_price += $kol_price;

							$name_image = $this->Html->div('item-image', 
								$this->Html->image(unserialize($items[$item_id]['Image']['medium'])));

							$name_price = $this->Html->div('item-price', 'Цена за 4шт: '.$items[$item_id]['Item']['price']);

							$name = $this->Html->div('item-name', $items[$item_id]['Item']['name']);

							$name = $name_image.$name.$name_price;

							$kol = $kol_minus.$this->Html->div('price', $kol.' шт').$kol_plus;
							
							$delete = $this->html->link('Удалить', 
								array('controller' => 'pages', 'action' => 'delete_basket', $item_id),
								array('class' => 'btn btn-danger'));

					?>
					<div class="order-item-line">
						<?php
							echo $this->html->div('name', $name);
							echo $this->html->div('kol', $kol);
							// echo $this->html->div('price col-1',$items[$item_id]['Item']['price']);
							echo $this->html->div('kol_price', $kol_price);
							echo $this->html->div('submit', $delete);
						?>
					</div>
					<?php endforeach; 
						$label = $this->Html->div('label', 'Итоговоя стоимость:');
						$razdelitel = $this->Html->div('razdelitel', '');
						$fin_price = $this->Html->div('fin-price', $fin_price);
						$div = $label.$razdelitel.$fin_price;
						echo $this->Html->div('fin_price', $div);
					?>
			</div>
		</div>

		<div class="form-to-send-order">
			<?php
				echo $this->form->create('Order', array('url' => array('controller' => 'pages', 'action' => 'add_order')));
				echo $this->form->hidden('status', array('value' => '1'));
				echo $this->form->input('name', array('label' => 'ФИО'));
				echo $this->form->input('phone', array('label' => 'Контактный телефон'));
				echo $this->form->input('email', array('label' => 'Е-mail'));
				echo $this->form->input('adress', array('label' => 'Адресс доставки'));
				
				echo $this->form->input('message', array('label' => 'Коментарии', 'type' => 'textarea'));
				echo $this->form->hidden('stiker');
				echo $this->html->div('submit', $this->Form->submit('Сделать заказ', array('class' => 'btn btn-success', 'div' => false)));

				echo $this->Form->end();
			?>
		</div>
		<?php 
		else:
			echo $this->html->div('empty','Ваша корзина пуста');
		endif;
		?>
	</div>
</div>