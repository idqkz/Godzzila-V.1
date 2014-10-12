<?php
	App::uses('Model', 'Model');

	class Image extends AppModel {
	
		public function afterDelete() {
		}

	    public function beforeDelete($casade = true) {
	    	$image = $this->findById($this->id);
	    	
	    	foreach ($this->image_options[$image['Image']['type']]['sizes'] as $size => $value) {
	    			// need to add img/

	    		if (!empty($image['Image'][$size])) {
	    			unlink('img/' . unserialize($image['Image'][$size]));
	    		}
	    	}
	    }

	    public function delete_files($image_id) {
	    	$image = $this->findById($image_id);
	    	foreach ($this->image_options[$image['Image']['type']]['sizes'] as $size => $value) {
	    		//	need to add img/
	    		if (!empty($image['Image'][$size])) {
	    			unlink('img/' . unserialize($image['Image'][$size]));
	    		}
	    	}
	    	
	    	if ($this->delete($image_id)) {
	    		return array('action' => $image['Image']['type'], 'text_id' => $image['Image']['text_id']);
	    	} else {
	    		return false;
	    	}
	    }
	
		public function beforeSave($options = array()) {
	        return true;
	    }

	    public function afterSave($created, $options = array()) {
	    	if (array_key_exists('image', $this->data['Image'])) {
	    		//	pass to images with text type and text id....
				$this->data['Image']['image']['id'] = $this->id;
				$this->new_image($this->data['Image']['image'], $this->data['Image']['type']);
			} else {
				return false;
			}
			
	    	return true;
	    }
		
	    public function new_image($images_data, $image_type, $parent_id = null) {
	    	
	    	if (is_array($images_data) && array_key_exists('name', $images_data)) {
		    	$this->new_image_process($images_data, $image_type, $parent_id);
		    } else {
		    	foreach ($images_data as $image) {
		    		//	depending on type process images
		    		$this->new_image_process($image, $image_type, $parent_id);
		    	}
		    }
	    	return true;
	    }

	    public function new_image_process($image, $image_type, $parent_id = null) {
	    	(isset($image['id']) ? $id = $image['id'] : $id = null);

	    	if ($image['size'] > 0) {
				$image_data = array(
					'Image' => array(
	    				'date' => date('Ymd'),
	    				'type' => $image_type,
	    				'item_id' => $parent_id,
	    				'id' => $id
					)
				);
				$this->create();
				$this->save($image_data);
				$image_data['Image']['id'] = $image_id = $this->getInsertID();
	    		foreach ($this->image_options[$image_type]['sizes'] as $size => $value) {	
	    			$image_data['Image'][$size] = serialize($this->process_image($image, $image_id, $image_type, $size, $value['width'], $value['height']));
	    		}
	    		$this->save($image_data);
	    	}
	    }

	    public function process_image($image, $image_id, $image_type, $size, $width, $height) {

	    	$extension = mb_substr($image['name'], mb_stripos($image['name'], '.'));
	    	$file_name = $this->images_folder . $this->image_options[$image_type]['folder'] . "$image_type-$image_id-$size$extension";

			$fileHanle = fopen($image['tmp_name'], 'r');
			$tmp_image = fread($fileHanle, filesize($image['tmp_name']));
			fclose($fileHanle);

			if ($extension == '.svg' || $extension == '.pdf' || $extension == '.docx' || $extension == '.doc') {
	    		file_put_contents($file_name, $tmp_image);
	    	} else {
				$tmp_image = imagecreatefromstring($tmp_image);
				$image_width = imagesx($tmp_image);
				$image_height = imagesy($tmp_image);

				$original_aspect = $image_width / $image_height;
				$new_aspect = $width / $height;

				//	check if image is already smaller than needed
				if ($image_width <=  $width && $image_height <= $height) {
					$new_width = $image_width;
					$new_height = $image_height;
				} else {
					$new_height = $height;
					$new_width = $image_width*($height/$image_height);

					if ($new_width > $width) {
						$new_height = $image_height*($width/$image_width);
						$new_width = $width;
					} 
				}

				$new_photo = imagecreatetruecolor($new_width, $new_height);
				($extension == '.png' ? imagefill($new_photo, 0, 0, imagecolortransparent($new_photo)): imagefill($new_photo, 0, 0, imagecolorallocate($new_photo, 255, 255, 255)));

				imagecopyresampled(
					$new_photo, 
					$tmp_image, 
					0,
					0,
					0, 
					0, 
					$new_width, 
					$new_height, 
					$image_width, 
					$image_height
				);
				//	надо сохранить
				if ($extension == '.png') {
					imagesavealpha($new_photo, true);
					imagepng($new_photo, $file_name, 1);
				} else {
					imagejpeg($new_photo, $file_name, 85);
				}

				imagedestroy($new_photo);
			}

			//	strip out the img bit
			$file_name = mb_substr($file_name, mb_strpos($file_name, '/') + 1);
	    	return $file_name;
	    }
	    
	    var $images_folder = 'img/';
	    
	    var $image_options = array(
	    	'items' => array(
	    		'sizes'		=> array(
		    		'small' 	=> array('width' => 64, 'height' => 64),
		    		'medium' 	=> array('width' => 150, 'height' => 150),
		    		'large' 	=> array('width' => 200, 'height' => 200),
		    	),
				'folder'	=> 'items/',
	    	),
	    	'aktcii_1' => array(
	    		'sizes'		=> array(
		    		'small' 	=> array('width' => 64, 'height' => 64),
		    		'medium' 	=> array('width' => 150, 'height' => 150),
		    		'large' 	=> array('width' => 200, 'height' => 200),
		    	),
				'folder'	=> 'aktcii/',
	    	)
	    );
	}
?>