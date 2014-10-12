<?php
	foreach ($list_category as $category_id => $name) {
		($id == $category_id ? $class =  'active' : $class = null);
		echo $this->Html->link($name, 
			array('controller' => 'pages', 'action' => $this->action, $category_id),
			array('class' => $class)
		);
	}
?>