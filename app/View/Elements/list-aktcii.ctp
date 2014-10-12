<?php 
	foreach ($aktcii as $value) {
		$num = $this->Html->div('col-1', $value['Aktcii']['id']);
		$name = $this->Html->div('col-4', $value['Aktcii']['title']);
		$button = $this->Html->div('col', $this->Html->link('Ред.',
			array('controller' => 'admin', 'action' => 'item_aktcii', $value['Aktcii']['id']), 
			array('class' => 'btn btn-success')));
		echo $this->Html->div('col-10', $num.$name.$button);
	}
?>