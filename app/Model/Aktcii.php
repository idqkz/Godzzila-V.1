<?php
App::uses('Model', 'Model');

class Aktcii extends AppModel {
	public $hasOne = array(
		'image_1' => array(
			'className' => 'Image',
			'foreignKey'	=> 'item_id',
			'dependent'		=> true,
			'conditions' 	=> array(
				'image_1.type' => 'aktcii_1'
				)
			)
		);

	public function afterSave($created, $options = array()){
		// debug($this->data);
		// die();
		if (!empty($this->data['Aktcii']['image_1'])) {
			$image_type = $this->data['Aktcii']['image_1']['typeimg'];
			//	pass to images with text type and text id....
			$Image_model = ClassRegistry::init('Image');
			$Image_model->new_image($this->data['Aktcii']['image_1'], $image_type, $this->data['Aktcii']['id']);
		} else {
			unset($this->data['Aktcii']['image_1']);
		}
    	return true;
	}

}
?>