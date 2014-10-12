<?php
App::uses('Model', 'Model');

class Group extends AppModel {
	
	function new_user_group() {
		$group_id = $this->find('first', array('fields' => 'id', 'conditions' => array('name' => 'Покупатели')));
		return $group_id['Group']['id'];
	}
}

