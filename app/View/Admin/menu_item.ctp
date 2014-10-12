<?
	echo $this->Form->create('Item', array('type' => 'file'));
	echo $this->Form->hidden('id');
	echo $this->Form->input('parent_id', array('label' => 'выберите категорию', 'options' => $list_category));
	echo $this->Form->input('name',array('label' => 'Название'));
	echo $this->Form->input('price',array('label' => 'Цена за 4 шт'));
	echo $this->Form->input('price_2',array('label' => 'Цена за 8 шт'));
	echo $this->Form->input('description',array('label' => 'Описание'));

	if (!empty($this->data['Image']) && $this->data['Image']['id'] !== null) {
		$link = $this->Html->link('Удалить', 
			array('controller' => 'admin', 'action' => 'image_delete', $this->data['Image']['id'], $this->data['Item']['id'],$this->action), 
			array('class' => 'btn btn-danger'));
		$images_html = $this->Html->div('item', $this->Html->image(unserialize($this->data['Image']['medium'])) . $link);
		echo $this->Html->div('images', $images_html);
	} else {
		echo $this->Form->input('image', 			array('type' => 'file', 'div' => 'attach-file', 'label' => 'Прикрепите изображение'));
	}


	echo $this->element('admin-form-buttons');
	echo $this->Form->end();
?>