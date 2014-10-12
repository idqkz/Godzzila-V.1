<?php 
	echo $this->Form->create('Aktcii', array('type' => 'file'));
	echo $this->Form->hidden('id');
	echo $this->Form->input('title', array('label' => 'Название акции'));
	echo $this->Form->input('description', array('label' => 'Описание'));
	echo $this->Form->input('order', array('label' => 'Порядок'));

	if (!empty($this->data['image_1']) && $this->data['image_1']['id'] != null) {
		$link = $this->Html->link('Удалить', 
			array('controller' => 'admin', 'action' => 'image_delete', $this->data['image_1']['id'], $this->data['Aktcii']['id'],$this->action), 
			array('class' => 'btn btn-danger'));
		$images_html = $this->Html->div('item', $this->Html->image(unserialize($this->data['image_1']['medium'])) . $link);
		echo $this->Html->div('images', $images_html);
	} else {
		echo $this->Form->hidden('Aktcii.image_1.typeimg', array('value' => 'aktcii_1'));
		echo $this->Form->input('image_1', 			array('type' => 'file', 'div' => 'attach-file', 'label' => 'Прикрепите изображение'));
	}
	echo $this->element('admin-form-buttons');
	echo $this->Form->end();

	echo $this->element('list-aktcii');
?>