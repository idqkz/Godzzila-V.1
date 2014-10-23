<?php
	App::uses('Model', 'Model');

	class Order extends AppModel {
		public $order = 'created DESC'; 

		public $hasAndBelongsToMany = array('Item');

		public $status_order = array(
			1 => 'Новый',
			2 => 'Готовиться',
			3 => 'На доставке',
			4 => 'Доставлен',
			5 => 'Отменён',
			);

		public $time_order = array(
			20 => '20 мин',
			30 => '30 мин',
			45 => '45 мин',
			60 => '60 мин',
			);
		
		public function beforeSave($options = array()){

			if(isset($this->data['Order']['status'])){

				(isset($this->data['info']['time']) ? $time = $this->data['info']['time']: $time = null);
				$this->check_status_change(
					$this->data['Order']['id'], 
					$this->data['Order']['status'], 
					$time
				);

			}

		}

		public function afterSave($created, $options = array()) {
			if(!empty($this->data['Order']['Email'])){
				// $user = $this->findById($user_id);
				// $to = $user['User']['email'];
				$to = 'Alekseiz_000@mail.ru';
				$subject = 'Ваш пароль для входа на сайт godzilla.kz';

				App::uses('CakeEmail', 'Network/Email');
				$Email = new CakeEmail();
				$Email->config('test');
				$Email->to($to);
				$Email->subject($subject);
				$Email->viewVars(array(
					'content' => array(
						'name' 				=> 'Name', 
						'email' 			=> 'email',
						'password'			=> 'pass'
					)
				));
				$Email->template('zakaz_for_user', 'default');
				$Email->emailFormat('html');
				// $Email->send();


				$to = 'Alekseiz_000@mail.ru';
				$subject = 'Ваш пароль для входа на сайт godzilla.kz';

				App::uses('CakeEmail', 'Network/Email');
				$Email = new CakeEmail();
				$Email->config('test');
				$Email->to($to);
				$Email->subject($subject);
				$Email->viewVars(array(
					'content' => array(
						'name' 				=> 'Name', 
						'email' 			=> 'email',
						'password'			=> 'pass'
					)
				));
				
				$Email->template('zakaz_for_operator', 'default');
				$Email->emailFormat('html');
				// $Email->send();
			}
			
		}

		public function check_status_change($id_order, $status_order, $time = null) {
			$order_info = $this->findById($id_order);
			$old_status_order = $order_info['Order']['status'];
			if (($old_status_order == 1) && ($status_order == 2)){
				// Отправка сообщения об подтверждении заказа и он приянат в обработку
				$User_model = ClassRegistry::init('User');
				$user_id = $order_info['Order']['phone'];
				$phone = $order_info['Order']['phone'];

				$message_text = 'Vash zakaz prinyat v obrabotku';
				// debug($message_text);
				// die();
				// $User_model->send_sms($user_id, $message_text, $phone);
			}

			if (($status_order == 3)){
				// Отправка сообщения о том что заказ передан курьеру.
				$User_model = ClassRegistry::init('User');
				$user_id = $order_info['Order']['phone'];
				$phone = $order_info['Order']['phone'];

				$message_text = 'Ваш заказ передан в доставку, ожидайте в течении ' . $time . ' минут';
				debug($message_text);
				die();
				// $User_model->send_sms($user_id, $message_text, $phone);
			}
		}

		public function update_total_order($id_order) {
			$order = $this->findById($id_order);
			$order['Order']['total'] = null;
			foreach ($order['Item'] as $item) {
				$order['Order']['total'] += $item['ItemsOrder']['total'];
			}
			$this->save($order['Order']);
		}

	}

?>