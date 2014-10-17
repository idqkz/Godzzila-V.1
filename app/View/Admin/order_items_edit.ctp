<?php
	echo $this->Form->create('ItemsOrder');

	foreach ($category_item as $category) {
		echo $this->Html->tag('h2', $category['CategoryItem']['name']);
		foreach ($category['Item'] as $item) {
			$input_name = 'Item.'.$item['id'].'.ItemsOrder.';

			($item['price'] != null ? $change_count = '4' : $change_count = '1');

			$quantity_plus = $this->Html->link('+', $input_name.'quantity',
				array('class' => 'btn btn-success', 'data-quantity' => $change_count, 'data-item-id' => $item['id']));
			$quantity_minus = $this->Html->link('-', $input_name.'quantity',
				array('class' => 'btn btn-success', 'data-quantity' => $change_count * -1, 'data-item-id' => $item['id']));
			
			// echo $quantity_plus;
			echo $this->Form->hidden($input_name.'id');
			echo $this->Form->input($input_name.'quantity', array('label' => $item['name']));
		}
	}

	echo $this->Form->submit('Сохранить', array('class' => 'btn btn-success btn-fixed'));
	echo $this->Form->end();
?>