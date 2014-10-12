<?php
App::uses('Model', 'Model');

class CategoryItem extends AppModel {
	public $order = 'CategoryItem.order';
	public $hasMany = array(
		'Item' => array(
			'className' => 'Item',
			'foreignKey'	=> 'parent_id',
			'dependent'		=> false,
			)
		);
}
?>