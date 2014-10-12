<?
	echo $this->Html->link('Создать','menu_item', array('class' => 'btn btn-success'));
	foreach ($items as $item) {
		echo $this->Html->div('items');
		echo $this->Html->link($item['Item']['name'], 'menu_item/'.$item['Item']['id']);
		echo '</div>';
	}
	

?>