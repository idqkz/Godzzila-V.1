<?php
$model = key($this->request->data);
// debug($this->data);
// debug($model);
// die();

if (!empty($this->data[$model]['id'])) {
	$save_button_text = 'Сохранить';
	$delete_link = $this->Html->link('удалить', 
		array('controller' => 'admin', 'action' => 'delete_'.$this->action, 
			$this->data[$model]['id']),
		array('class' => 'btn btn-danger'));
} else {
	$save_button_text = 'добавить';
	$delete_link = '';
}



$save_button = $this->Form->submit($save_button_text, array('class' => 'btn btn-success', 'div' => false));

echo $this->Html->div('submit', $save_button . $delete_link);


// debug($this->request->params);
?>