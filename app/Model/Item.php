<?php
	App::uses('Model', 'Model');

	class Item extends AppModel {
		public $hasOne = array(
			'Image' => array(
				'foreignKey'	=> 'item_id',
				'dependent'		=> true,
				'conditions' 	=> array('Image.type' => 'items'),
				)
			);

		public $belongsTo = array(
			'Category' => array(
				'foreignKey' => 'parent_id',
				'className' => 'CategoryItem'
			)
		);

		public function beforeSave($options = array()) {
	    	return true;
		}
		
		public function afterSave($created, $options = array()) {
    	if (!empty($this->data['Item']['image'])) {
    			$image_type = 'items';
				//	pass to images with text type and text id....
				$Image_model = ClassRegistry::init('Image');
				$Image_model->new_image($this->data['Item']['image'], $image_type, $this->data['Item']['id']);
			} else {
				unset($this->data['Item']['image']);
			}
	    	return true;
	    }

	    public function basket_item_data($basket){
	    	$data = null;
	    	if ($basket != null)
		    	foreach ($basket['items'] as $id_item => $item_value) {
		    		$data[$id_item] = $this->find('first', array(
							'fields' => array('Item.id', 'Image.medium', 'Item.name', 'Item.price', 'Item.price_2',
								'Item.parent_id'),
							'conditions' => array('Item.id' => $id_item),
						));
		    	}
	    	return $data;
	    }

	    public function update_basket_totals($basket) {
	    	$order_total = 0;
	    	foreach ($basket['items'] as $item_id => $value) {
	    		// Считаем цену наименования
    			$basket['items'][$item_id]['sub_total'] = 
	    			$this->get_sub_total_for_item($item_id, $value['quantity']); 

    			//	прибавляем подитог к сумме заказа
    			$order_total += $basket['items'][$item_id]['sub_total'];
	    	}
	    	$basket['order_total'] = $order_total;
	    	return $basket;
	    }

	    public function get_sub_total_for_item($item_id, $quantity) {
	    	$item_data = $this->find('first', array(
    			'conditions' => array('Item.id' => $item_id),
    			'fields' => array('price_2', 'price')
    		));
    		// debug($item_data);
    		// die();
			//	если у наименования прописаны две цены, это роллы и нужно считать по 8 и потом по 4
			if ($item_data['Item']['price'] == null) {
				$sub_total = $quantity * $item_data['Item']['price_2'];
			} else {
				// считаем полные порции
				$number_of_full_portions = floor($quantity / 8);
				$sub_total = 
				//	полные порции по цене полных
					$number_of_full_portions * $item_data['Item']['price_2'] 
				//	отнимаем от общего количества, остаток или 0 или 4
				+ 	($quantity - ($number_of_full_portions * 8)) * $item_data['Item']['price']/4;
			}

			return $sub_total;
	    }

	}
?>