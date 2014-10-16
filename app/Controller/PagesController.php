<?php

App::uses('AppController', 'Controller');

class PagesController extends AppController {
	public $components = array('Auth');

	public $uses = array('Item','Order','ItemsOrder','User','Aktcii', 'CategoryItem');

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow();
	}

	public function beforeRender() {
		parent::beforeRender();
		$title_for_layout = 'suhi';

		$menu_items = array(
			// array(
			// 	'name' => 'доставка и оплата',
			// 	'controller' => '',
			// 	'action' => '',
			// ),
			// array(
			// 	'name' => 'меню',
			// 	'controller' => 'pages',
			// 	'action' => 'menu',
			// ),
			array(
				'name' => 'акции',
				'controller' => 'pages',
				'action' => 'akciy',
			),
			array(
				'name' => 'контакты',
				'controller' => 'pages',
				'action' => 'contacts',
			),
			array(
				'name' => 'отзывы',
				'controller' => 'pages',
				'action' => 'otzivy',
			),
			array(
				'name' => 'корзина',
				'controller' => 'pages',
				'action' => 'basket',
			),
		);

		$this->set(compact('menu_items'));	
	}

	public function index() {
		$this->redirect(array('controller' => 'pages', 'action' => 'home'));	
	}

	public function home($id = '7') {
		// $this->CategoryItem->recursive = 2;
		$items = $this->Item->find('all', array(
			'conditions' => array('Item.parent_id' => $id)
		));

		$list_category = $this->CategoryItem->find('list', array('alias', 'name'));

		$this->set(compact('items', 'list_category', 'id'));
	}

	public function order_menu(){

	}

	public function menu($id = null) {
		$id_rolls = $this->id_rolls;
		$this->CategoryItem->recursive = 2;
		if ($id != null){
			$items = $this->CategoryItem->findById($id);
		} else {
			$items = $this->CategoryItem->find('first');
		}

		$list_category = $this->CategoryItem->find('list', array('alias', 'name'));

		$this->set(compact('items', 'list_category', 'id_rolls'));
	}

	public function aktsiya(){
		$aktcii = $this->Aktcii->find('all');
		$this->set(compact('aktcii'));
	}

	public function kotakts(){
	
	}

	public function otzivy(){
		
	}

	public function basket($id = null, $item_id = null) {
		$basket = $this->Session->read('basket');
		$items = $this->Item->find('all', array(
				'fields' => array('Item.id', 'Image.medium'),
			));
		$items = $this->Item->find('list', array('Item.id', 'Image.small'));

		$items_data = $this->Item->basket_item_data($basket);

		$this->set(compact('basket', 'items_data'));
	}

	public function change_basket() {
		$this->autoRender = false;
		//	ответ по умолчанию
		$answer = true;

		$item_id = $_GET['item_id']; $quantity = $_GET['quantity'];
		($this->Session->check('basket') ? $basket = $this->Session->read('basket') : $basket = array('items' => array()));
		(array_key_exists($item_id, $basket['items']) !== false ? $basket['items'][$item_id]['quantity'] += $quantity : $basket['items'][$item_id]['quantity'] = $quantity);

		if ($basket['items'][$item_id]['quantity'] <= 0) {
			unset($basket['items'][$item_id]);
		}
		//	обновляем под итог для каждого наименования и общий итог
		$basket = $this->Item->update_basket_totals($basket);

		$this->Session->write('basket', $basket);

		//	если нужно, возвращаем общий итог и под итог
		$answer = json_encode($basket);

		return $answer;
	}

	public function remove_basket($id) {
		$this->autoRender = false;
		$basket = $this->Session->read('basket');
		unset($basket['items'][$id]);
		$basket = $this->Item->update_basket_totals($basket);
		$this->Session->write('basket', $basket);
		return json_encode($basket);
	}
	
	public function add_order() {

		//	проверка пользователя и получение данных
		$user_data = $this->User->get_user_data($this->data['Order']);
		$basket = $this->Session->read('basket');
		
		$this->request->data['Order']['user_id'] = $user_data['id'];
		$this->request->data['Order']['total'] = $basket['order_total'];
		$this->Order->save($this->data['Order']);
		$order_id = $this->Order->getInsertID();
		
		foreach ($basket['items'] as $item_id => $item_info) {
			$items_orders[$item_id] = array(
				'item_id' => $item_id,
				'order_id' => $order_id,
				'quantity' => $item_info['quantity'],
				'total' => $item_info['sub_total']
				);
			$this->ItemsOrder->create();
			$this->ItemsOrder->save($items_orders[$item_id]);
		}

		$this->Session->write('basket', null);

		$this->Session->setFlash('Спасибо, ваш заказ принят в обработку, сейчас всё проверим и перезвоним!');

		$this->redirect(array('controller' => 'pages', 'action' => 'thank_you'));
	}

	public function thank_you() {
		//	отправка заказа
	}

	public function send_order(){

	}
}
