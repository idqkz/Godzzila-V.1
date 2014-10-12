<?php 
	echo $this->Form->create('CategoryItem');
	echo $this->Form->hidden('id');
	echo $this->Form->input('name', array('label' => 'Название категории продуктов'));
	echo $this->Form->input('alias', array('label' => 'Ссылка на категорию'));
	echo $this->Form->input('order', array('label' => 'порядок для вывода', 'type' => 'number'));
	echo $this->element('admin-form-buttons');
	echo $this->Form->end();

	// Cписок категорий
	$html_content = null;
	foreach ($category_item as $category) {
		$col_list = null;
		$num = $category['CategoryItem']['order'];
		$name = $category['CategoryItem']['name'];
		$link_del =	$this->Html->link('X', 
			array('controller' => 'admin', 'action' => 'delete_'.$this->action, $category['CategoryItem']['id']),
			array('class' => 'btn btn-danger'));
		$link_edit = $this->Html->link('Ред.', 
			array('controller' => 'admin', 'action' => $this->action, $category['CategoryItem']['id']),
			array('class' => 'btn btn-success'));
		$col_list .= $this->Html->div('col-1', $num);
		$col_list .= $this->Html->div('col-7', $name);
		$col_list .= $this->Html->div('col-1', $link_edit);
		$col_list .= $this->Html->div('col-1', $link_del);
		
		$html_content .= $this->html->div('row', $col_list);
	}

	echo $this->Html->div('list',$html_content);
?>