<?php
App::uses('Model', 'Model');

class User extends AppModel {

	// public $hasOne = array('User');

	function generate_password($length= 4, $charset='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789') {
		$str = '';
		$count = strlen($charset);
		while ($length--) {
			$str .= $charset[mt_rand(0, $count-1)];
		}
		return $str;
	}

	public function send_password($user_id = null, $phone_number, $password) {
		$message_text = 'Vash parol dly vhoda na сайт: ' . $password;
		return($this->send_sms($user_id, $message_text, $phone_number));
	}

	public function send_sms($user_id = null, $message_text = null, $phone_number = null, $message_id = null) {

		//	сдюда попадает номер начинающийся с 8 без пробелов и других знаков
		$phone_number = '7' . substr($phone_number, 1);
		//	готовим текст сообщения — заменяем пробелы на плюсы
		$message_data = str_replace(' ', '+', $message_text);
		//	тип сообщения
		$message_type = 'SMS:TEXT';
		//	действие
		$action = 'sendmessage';
		//	логин и пароль
		$user_name = 'interdq';
		$password = 'n1qMeWmmk';
			
		//	формируем запрос на отправку
		$url = "http://212.124.121.186:9501/api";
		$query = 
			"?action=$action"
		.	"&username=$user_name"
		.	"&password=$password"
		.	"&recipient=$phone_number"
		.	"&messagetype=$message_type"
		.	"&messagedata=$message_data"
		;

		// create a new cURL resource
		$ch = curl_init();
		// set URL and other appropriate options
		curl_setopt($ch, CURLOPT_URL, $url . urlencode($query));
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$response = curl_exec($ch);
		curl_close($ch);
			
		//	подключаем xml
		App::uses('Xml', 'Utility');
		//	парсим в массив для формирования данных для сохранения
		$parsedXml =  Xml::toArray(Xml::build($response));
		$sms_data = array(
			'user_id' => $user_id,
			'messagedata' => $message_text,
			//	расшифровка статусов в конце этого файла
			'statuscode' => $parsedXml['response']['data']['acceptreport']['statuscode'],
			'messageid' => $parsedXml['response']['data']['acceptreport']['messageid'],
			'senttime' => date('YmdHis'),
			'message_id' => $message_id
		);
		
		// $Sms_message = ClassRegistry::init('Smsmessage');
		// $Sms_message->create();
		// $Sms_message->save($sms_data);

		return $sms_data;
		
	}

	public function beforeSave($options = array()) {

		if (!empty($this->data['User']['password'])) {
			$this->data['User']['password'] = AuthComponent::password($this->data['User']['password']);
		} else {
			unset($this->data['User']['password']);
		}
        
        return true;
    }

    public function update_last_login($user_id) {
    	$this->id = $user_id;
    	$this->saveField('last_login', date('YmdHis'));
    	return true;
    }

    public function get_user_data($data = null) {
		// $data = $user = $this->findById(AuthComponent::user('id'));
		// debug($data);
    	// die();
    	if (AuthComponent::user('id')) {
			$user = $this->findById(AuthComponent::user('id'));
		} else {
			//	check if user is already in db
			$user = $this->findByEmail($data['email']);
			if (empty($user['User'])) {
				if($this->find('count', array('conditions' => array('phone' => $data['phone']))) == 0){
					
					$Group = ClassRegistry::init('Group');

					$this->create();
					$password = $this->generate_password();
					$user['User'] = array(
						'register_date' => date('Y-m-d H:i:s'),
						'name' => $data['name'],
						'phone' => $data['phone'],
						'email' => $data['email'],
						'adress' => $data['adress'],
						'password' => AuthComponent::password($password),
						'status' => 0,
						'group_id' => $Group->new_user_group()
					);
					$this->save($user);
					$user['User']['id'] = $this->getInsertID();
					// Место для отпарвки сообщения какого-либо...
					// $this->email_password($user['User']['id'], $password);
				} else {
					$user = $this->find('first', array('conditions' => array('phone' => $data['phone'])));
				}
			}

		}
		return $user['User'];
    }

    function email_password($user_id, $password) {

		$user = $this->findById($user_id);
		// debug($user);
		// die();
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
				'name' 				=> $user['User']['name'], 
				'email' 			=> $user['User']['email'],
				'password'			=> $password
			)
		));
		$Email->template('contact', 'default');
		$Email->emailFormat('html');
		$Email->send();
	}
}

