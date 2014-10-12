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

		// debug($basket);
		// die();

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

		if(($this->data['Order']['name'] != null)
		&& ($this->data['Order']['phone'] != null)
		&& ($this->data['Order']['adress'] !=null)
		&& ($this->Session->read('basket') != null) ){
			$user_data = $this->User->get_user_data($this->data['Order']);
			
			if (!empty($this->data)){
				$this->request->data['Order']['user_id'] = $user_data['id'];
				$this->Order->save($this->data['Order']);
				$order_id = $this->Order->getInsertID();
				$order = $this->Session->read('basket');

				foreach ($order as $item_id => $item_info) {
					$items_orders[$item_id] = array(
						'item_id' => $item_id,
						'order_id' => $order_id,
						'kol' => $item_info['quantity'],
						);
					$this->ItemsOrder->create();
					$this->ItemsOrder->save($items_orders[$item_id]);
					$items_orders = null;
				}
				unset($this->data);
			}

			$this->Session->write('basket', null);
		} else {
			$this->Session->setFlash('Заполните все поля');
			$this->redirect(array('controller' => 'pages', 'action' => 'basket'));
		}
		$this->redirect(array('controller' => 'pages', 'action' => 'menu'));
	}

	public function send_order(){

	}
}
