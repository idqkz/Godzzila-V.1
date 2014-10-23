<?php

App::uses('AppController', 'Controller');

class AdminController extends AppController {

	public $uses = array('Item', 'Order', 'ItemsOrder', 'User', 'Group', 'Image', 'Aktcii', 'CategoryItem');

	public $components = array(
		'Auth' => array(
			'all' => array('userModel' => 'User'),
			'authenticate' => array(
	             'Form' => array(
	                 'fields' => array('username' => 'phone', 'password' => 'password')
	             )
	         ),
		    'loginAction' => array(
		        'controller' => 'admin',
		        'action' => 'login'
		    ),
		    'authError' => 'Неверный логин или пароль',
		),
		'Paginator'
	);

	public function beforeRender() {
		parent::beforeRender();
		$main_menu_items_for_operator = array(
			'Заказы'			=>	array('controller' => 'admin', 'action' => 'orders'),	
			'Меню'				=>	array('controller' => 'admin', 'action' => 'menu_item_all'),
			'Создать заказ'		=>	array('controller' => 'admin', 'action' => 'order_make'/*,
				'sub_menu' => array(
					'Создать заказ'		=>	array('controller' => 'admin', 'action' => 'order_make'),
					)*/
				),
			'Создать заказ'		=>	array('controller' => 'admin', 'action' => 'order_make'),
			);		

		$main_menu_items = array(
			'Настройки'				=>	array('controller' => 'admin', 'action' => 'settings'),

			'Заказы'			=>	array('controller' => 'admin', 'action' => 'orders'/*,
				'sub_menu' => array(
					'Создать заказ'		=>	array('controller' => 'admin', 'action' => 'order_make'),
					)*/
				),	
			'Меню'				=>	array('controller' => 'admin', 'action' => 'menu_item_all',
				'sub_menu' => array(
					'Категории меню' =>	array('controller' => 'admin', 'action' => 'catagory_menu')
					)
				),
			'Пользователи'			=>	array('controller' => 'admin', 'action' => 'users'),
			'Акции'			=>	array('controller' => 'admin', 'action' => 'item_aktcii'),
			
		);

		/*if (!$this->Auth->user()) {
			$main_menu_items = null;
		}*/
		$users_id = $this->Group->find('all', array('conditions' => array('Group.name' => 'Операторы')));

		// if($this->Auth->user('group_id') == $users_id[0]['Group']['id']){
		// 	$main_menu_items = $main_menu_items_for_operator;
		// }

		$title_for_layout = 'Страницы управления';
		$this->set(compact('title_for_layout', 'main_menu_items'));
	}

	function isAuthorized() {
	 	$admins_id = $this->Group->find('all', array('conditions' => array('Group.name' => 'Администраторы')));
		$users_id = $this->Group->find('all', array('conditions' => array('Group.name' => 'Покупатели')));
		$operators_id = $this->Group->find('all', array('conditions' => array('Group.name' => 'Операторы')));

	 	$operaots_action = array('menu_item', 'menu_item_all', 'orders_all', 'delete_item_from_order', 
	 		'order_change');
		// Админиы
	 	if ($this->Auth->user('group_id') == $admins_id[0]['Group']['id']) return true;
	 	// Операторы
	 	if ($this->Auth->user('group_id') == $operators_id[0]['Group']['id']){
	 		foreach ($operaots_action as $action) {
	 			if($this->action == $action) return true;
	 		}
	 		if($this->action == 'menu_item_all') return true;
	 	}
	 	// Покупатели
	 	if ($this->Auth->user('group_id') == $users_id[0]['Group']['id']) 
	 		$this->redirect(array('controller' => 'pages', 'action' => 'home'));
		return false;
	}

	public function beforeFilter() {
		parent::beforeFilter();

		$this->layout = 'admin';
		$this->Auth->allow(array('login'));

		// $this->Auth->authorize = array('Controller');
		// $this->Auth->deny();
		$this->Auth->allow();
	}

	public function login() {
		if ($this->request->is('post')) {
	        if ($this->Auth->login()) {
	        	$this->User->update_last_login($this->Auth->user('id'));

	        	$this->Session->setFlash($this->Auth->user('name') .' добро пожаловать ');
	            return $this->redirect(array('controller' => 'admin', 'action' => null));
	        } else {
	        	$this->Session->setFlash('Войти не удалось');
	        }
	    }
	}

	public function logout() {
		$this->Auth->logout();
		$this->redirect('/');
	}

	public function menu_item($id = null){
		
		if (!empty($this->data)){
			$this->Item->save($this->data);
			unset($this->request->data['Item']);
			$this->Session->setFlash('Изменения сохранены!');
		}

		if ($id != null) {
			$this->Item->recursive = 2;
			$this->request->data = $this->Item->findById($id);	
		}
		$list_category['none'] = 'Выберете категорию';
		$list_category += $this->CategoryItem->find('list', array('id', 'name'));
		$this->set(compact('list_category'));
	}

	public function menu_item_all(){
		$items = $this->Item->find('all');
		$this->set(compact('items'));
	}

	public function catagory_menu($id = null) {
		if (!empty($this->data)){
			$this->CategoryItem->Save($this->data);
			$this->Session->setFlash('Изменения сохранены!');
		}

		if ($id != null){
			$this->request->data = $this->CategoryItem->findById($id);
		}	
		$category_item	= $this->CategoryItem->find('all', array('order' => 'CategoryItem.order'));
		$this->set(compact('category_item'));
	}

	public function orders() {
		//	get a list of orders
		$this->Order->recursive = 0;
		$orders = $this->Order->find('all', array(
			'conditions' => array(),
			'order' => 'created ASC'
		));
		$order_status = $this->Order->status_order;
		$this->set(compact('orders', 'order_status'));
	}

	public function order_details($id = null) {
		if(!empty($this->data)){
			$this->Order->save($this->data);
			$this->Session->setFlash('Изменения сохранены');
		}

		if($id != null){
			$this->request->data = $this->Order->findById($id);
		}

		$options_status =$this->Order->status_order;
		$options_time =$this->Order->time_order;

		$this->set(compact('options_status', 'options_time', 'id'));
	}

	public function order_items_edit($id = null) {
		if(!empty($this->data)){
			$order_total = null;
			foreach ($this->data['Item'] as $item_id => $items_order) {
				// Проверка на пустоту поля или на значени 0
				if(	($items_order['ItemsOrder']['quantity'] != null) 
				&&	($items_order['ItemsOrder']['quantity'] != 0))
				{
					// Сохраняем наименование
					$items_order['ItemsOrder']['total'] = 
						$this->Item->get_sub_total_for_item($item_id, $items_order['ItemsOrder']['quantity']);
					$items_order['ItemsOrder']['item_id'] = $item_id;
					$items_order['ItemsOrder']['order_id'] = $id;
					$this->ItemsOrder->save($items_order['ItemsOrder']);
					$order_total += $items_order['ItemsOrder']['total'];
				}
			}
			// Сохроняем итоговую сумму
			$this->Order->save(array(
				'id' => $id,
				'total' => $order_total,
				));
			$this->Session->setFlash('Заказ изменён!');
			$this->redirect(array('controller' => 'admin', 'action' => 'order_details', $id));
		}

		$category_item = $this->CategoryItem->find('all'); 
		$this->request->data = $this->Order->findById($id);

		$this->request->data['Item'] = Hash::combine($this->data['Item'], '{n}.id', '{n}');

		$this->set(compact('category_item', 'id'));
	}

	public function order_items_change_quantity($id_order = null, $id_items_order, $quantity) {
		// Получаем ItemsOrder
		$order_item = $this->ItemsOrder->findById($id_items_order);
		// Меняем количество
		$quantity += $order_item['ItemsOrder']['quantity'];

		// Проверяем если количество <=0 удаляем ItemsOrder
		if($quantity <= 0){
			$this->ItemsOrder->delete($id_items_order);
			$this->redirect(array('controller' => 'admin', 'action' =>'order_details', $id_order));
		}

		// Считаем итого
		$sub_total = $this->Item->get_sub_total_for_item($order_item['ItemsOrder']['item_id'], $quantity);

		// Сохраняем
		$order_item['ItemsOrder']['total'] = $sub_total;
		$order_item['ItemsOrder']['quantity'] = $quantity;
		$this->ItemsOrder->save($order_item['ItemsOrder']);
		// Обновляем итогов заказе
		$this->Order->update_total_order($id_order);

		$this->redirect(array('controller' => 'admin', 'action' =>'order_details', $id_order));
	}

	/*public function orders_all($id = null, $znak = null){
		$category_items = $this->CategoryItem->find('all', array(
				'fields' => array('CategoryItem.alias', 'CategoryItem.name'),
			));

		$items_list['empty'] = 'Выберете наименование';
		foreach ($category_items as $category_item) {
			$item_list = array();
			foreach ($category_item['Item'] as $item) {
				$item_list[$item['id']] = $item['name'];
			}
			$items_list[$category_item['CategoryItem']['name']] = $item_list;
		}		

		if(($znak == 'plus')||($znak == 'minus')){ //Добавление/удаление суши
			$items_quantity = $this->ItemsOrder->findById($id);
			if($znak == 'plus'){
				$items_quantity['ItemsOrder']['quantity']++;
			}
			if($znak == 'minus'){
				$items_quantity['ItemsOrder']['quantity']--;
				if ($items_quantity['ItemsOrder']['quantity'] < 0){
					$items_quantity['ItemsOrder']['quantity'] = 0;
				}
			}
			$this->ItemsOrder->save($items_quantity['ItemsOrder']);
			$this->redirect(array('controller' => 'admin', 'action' => $this->action,$items_quantity['ItemsOrder']['order_id']));
		}

		if($id != null){
			$this->request->data = $this->Order->findById($id);
		}

		$orders = $this->Order->find('all');
		$this->set(compact('orders','id','items_list'));
	}*/

	/*public function order_change($id = null) {
		if (!empty($this->data)) {
			if($this->data['ItemsOrder']['item_id'] != 0) {
				$this->request->data['ItemsOrder']['order_id'] = $id;
				$this->ItemsOrder->save($this->data['ItemsOrder']);
			}
			$this->Order->check_status_change($this->data['Order']['id'],$this->data['Order']['status']);
			$this->Order->save($this->data['Order']);
		}
		unset($this->data);
		$this->redirect(array('controller' => 'admin', 'action' => 'orders_all',$id));
	}*/

	/*public function order_make(){
		$category_items = $this->CategoryItem->find('all', array(
				'fields' => array('CategoryItem.alias', 'CategoryItem.name'),
			));

		$items_list['empty'] = 'Выберете наименование';
		foreach ($category_items as $category_item) {
			$item_list = array();
			foreach ($category_item['Item'] as $item) {
				$item_list[$item['id']] = $item['name'];
			}
			$items_list[$category_item['CategoryItem']['name']] = $item_list;
		}

		$this->set(compact('items_list'));
	}*/

	public function users($user_id = null){
		$admins_id = $this->Group->find('all', array('conditions' => array('Group.name' => 'Администраторы')));
		$users_id = $this->Group->find('all', array('conditions' => array('Group.name' => 'Покупатели')));
		$operators_id = $this->Group->find('all', array('conditions' => array('Group.name' => 'Операторы')));
		$groups = $this->Group->find('all');
		$groups_list = $this->Group->find('list');
		$users = $this->User->find('all');
		$sort_users = null;
		foreach ($users as $i => $user) {
			if ($admins_id[0]['Group']['id'] == $user['User']['group_id']) {
				$sort_users[$admins_id[0]['Group']['name']][$i] = $user['User'];
			}
			if ($users_id[0]['Group']['id'] == $user['User']['group_id']) {
				$sort_users[$users_id[0]['Group']['name']][$i] = $user['User'];
			}
			if ($operators_id[0]['Group']['id'] == $user['User']['group_id']) {
				$sort_users[$operators_id[0]['Group']['name']][$i] = $user['User'];
			}
		}
		if (!empty($this->data)){
			// debug($this->data);
			// die();
			$this->User->save($this->data['User']);
			$this->redirect(array('controller' => 'admin', 'action' => 'users')); 
		}
		
		$users_id = $users_id[0]['Group']['id'];
		if ($user_id != null){
			$this->request->data = $this->User->findById($user_id);
		}
		$this->set(compact('user_id', 'sort_users', 'groups', 'users_id', 'groups_list'));
	}

	// Акции
	public function item_aktcii($id = null){

		if(!empty($this->data)){
			$this->Aktcii->save($this->data);
			$this->Session->setFlash('Сохранение прошло успешно');
			$this->redirect(array('controller' => 'admin', 'action' => 'item_aktcii'));
			unset($this->data);
		}

		if($id != null){
			$this->request->data = $this->Aktcii->findById($id);
		}
		// debug((!empty($this->data['image_1'])) && ($this->data['image_1']['id'] != null));
		// die();
		$aktcii = $this->Aktcii->find('all');
		$this->set(compact('aktcii'));
	}

	// Пустая страница Можно использовать для проверок
	public function index(){

	}
// Функции удаления

	public function delete_user($user_id){

		if($user_id != null){
			if($this->User->findById($user_id) != null){
				$user = $this->User->findById($user_id);
				$users_id = $this->Group->find('all', array('conditions' => array('Group.name' => 'Покупатели')));
				$users_id = $users_id[0]['Group']['id'];
				if($user['User']['group_id'] == $users_id){
					if ($user['User']['status'] == 2){
						$user['User']['status'] = 1;
					} else{
						$user['User']['status'] = 2;
					}
					$this->User->save($user['User']);
				} else {
					$this->User->delete($user_id);
				}
			}
			$this->Session->setFlash('Удаление прошло успешно!');
		}
		$this->redirect(array('controller' => 'admin', 'action' => 'users'));
	}

	public function delete_item_from_order($id = null) {
		if ($id != null){
			$pr = 0;
			$order_id = $this->ItemsOrder->findById($id);
			$order_id = $order_id['ItemsOrder']['order_id'];
			$this->ItemsOrder->delete($id);
			$this->Session->setFlash('Удаление прошло успешно!');
		}
		$this->redirect(array('controller' => 'admin', 'action' => 'orders_all',$order_id));
	}

	public function image_delete($id_image = null, $id_menu = null, $action = null) {
		if($id_image !=null){
			$this->Image->delete($id_image);
			$this->Session->setFlash('Удаление прошло успешно!');
		}
		$this->redirect(array('controller' => 'admin', 'action' => $action, $id_menu));
	}

	public function delete_menu_item($id = null) {
		if($id !=null){
			$this->Item->delete($id, true);
			$this->Session->setFlash('Удаление прошло успешно!');
			$this->redirect(array('controller' => 'admin', 'action' => 'menu_item_all'));
		}
	}

	public function delete_catagory_menu($id = null) {
		if($id != null){
			$this->CategoryItem->delete($id);
			$this->Session->setFlash('Категрия продукта удалена!');
		}
		$this->redirect(array('controller' => 'admin', 'action' => 'catagory_menu'));
	}

	public function remove_item_from_order($id = null) {
		if($id != null){
			$id_order = $this->ItemsOrder->findById($id);
			$id_order = $id_order['ItemsOrder']['order_id'];
			$this->ItemsOrder->delete($id);
			$this->Session->setFlash('Пункт заказа удалён!');
			$this->Order->update_total_order($id_order);
			$this->redirect(array('controller' => 'admin', 'action' => 'order_details', $id_order));
		}
	}

	public function show_order_on_map($id) {
		$order_data = $this->Order->find('first', array(
			'conditions' => array('Order.id' => $id),
			'fields' => array('location')
		));
		$this->set(compact('order_data'));
		$this->layout = 'ajax';
	}
// 

}
