<?php

App::uses('AppController', 'Controller');

class AdminsController extends AppController {

	var $uses = array('Admin', 'User', 'Town', 'Photo', 'Message', 'Payment', 'Order');

	var $components = array('Session', 'Auth', 'Paginator');

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->authorize = array('Controller');
		$this->Auth->deny();
		$this->layout = 'admin';
	}

	function isAuthorized() {

		($this->Auth->user('type') == 1 ? $answer = true : $answer = false);
		return $answer;
	}

	public function home() {

	}

	public function users($user_id = null) {

		if (!empty($this->data['User'])) {
			$this->request->data['Ad']['user_id'] = $this->request->data['User']['id'];
			$this->User->saveAssociated($this->data, $options = array('validate' => false));
			$this->Session->setFlash('Изменения сохранены');
		}

		if (!empty($this->data['Change'])) {
			$this->request->data['Change']['date'] = date('Y-m-d H:i:s');
			$Change = ClassRegistry::init('Change');
			$Change->create();
			$Change->save($this->data['Change']);
		}

		if ($user_id != null) {
			$this->request->data = $this->User->findById($user_id);
		}

		$this->User->recursive = -1;

		$this->Paginator->settings = array(
	    	'limit' => 50,
			'order' => 'User.registerdate DESC',
			'fields' => array('User.id', 'User.name', 'User.lastname', 'User.phone', 'User.status', 'User.registerdate'),
	    );

	    if (!empty($this->data['Search'])) {
			$this->Paginator->settings['conditions'] = array('OR' => array(
				'User.name LIKE' => '%' . $this->data['Search']['keyword'] . '%',
				'User.phone LIKE' => '%' . $this->data['Search']['keyword'] . '%'
			));
		}

		$users = $this->Paginator->paginate('User');
		$this->set(compact('users'));

	}

	public function orders($id = null) {
		$this->Paginator->settings = array(
	    	'limit' => 50,
			'order' => 'Order.created DESC',
			'fields' => array('User.id', 'User.name', 'User.lastname', 'User.phone', 'Order.status', 'Order.sum', 'Order.option', 'Order.created'),
	    );
		$orders = $this->Paginator->paginate('Order');
		$this->set(compact('orders'));
	}

	public function balances($id = null) {
		$Change = ClassRegistry::init('Change');
		$user_ids = $Change->find('list', array('fields' => array('Change.id', 'user_id', )));
		$this->User->Behaviors->load('Containable');
		$users = $this->User->find('all', array(
			'conditions' => array('User.id' => $user_ids),
			'contain' => array('Change'),
			'fields' => array('User.id', 'User.name', 'User.lastname', 'User.phone'),
			// 'group by' => 'user_id',
			// 'joins' => array(
			// 	array(
			// 		'table' => 'changes',
			// 		'alias' => 'Change',
			// 		'conditions' => array('User.id = Change.user_id'),
			// 		'type' => 'left',
			// 		// 'fields' => array('SUM(Change.number) as Total')
			// 	)
			// )
		));
		// debug($users);
		// debug($user_ids);
		// die();
		$this->set(compact('users'));
	}

	public function contacts($message_id = null) {

		if (!empty($this->data)) {
			if (array_key_exists('Smsmessage', $this->data)) {
				//	отправка сообщения
				$answer = $this->User->send_sms(null, $this->data['Smsmessage']['message'], $this->User->clear_phone($this->data['Smsmessage']['phone']), $this->data['Smsmessage']['message_id']);
				$this->Session->setFlash('сообщение отправлено');
			} else {
				$this->Message->save($this->data);
				$this->Session->setFlash('Изменения сохранены');
			}
		}

		if ($message_id != null) {
			$this->request->data = $this->Message->findById($message_id);
		}

		$this->Paginator->settings = array(
	    	'limit' => 22,
			'order' => array('Message.created' => 'DESC'),
			// 'fields' => array('User.id', 'User.name', 'User.lastname', 'User.phone', 'User.status', 'User.registerdate'),
	    );

		$contacts = $this->Paginator->paginate('Message');
		$this->set(compact('contacts'));
	}

	public function delete_photo($user_id, $photo_id) {
		$this->Photo->delete($photo_id);
		$this->Session->setFlash('Фотография удалена');
		$this->redirect(array('controller' => 'admin', 'action' => 'users', $user_id));
	}

	public function user_delete($id) {
		$this->User->delete($id);
		$this->Session->setFlash('пользователь удален');
		$this->redirect(array('controller' => 'admin', 'action' => 'users'));
	}

}