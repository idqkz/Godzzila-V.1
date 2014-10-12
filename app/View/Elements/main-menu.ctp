<?php

	foreach ($main_menu_items as $name => $url) {

		($this->request->url !== false ? $address = str_replace('/', '', urldecode($this->request->url))  : $address = $this->request->url);

		($url['controller'] == 'admin' ? $check = $url['action'] : $check = $url['controller']);

		if (($address === false && $url['controller'] == '/') || ($address !== false && (mb_stripos($address, $check) !== false))) {
			$class = 'item active';
		} else {
			$class = 'item';
		}
		
		$link = $this->Html->link($name, array('controller' => $url['controller'], 'action' => $url['action']));
		echo $this->Html->div($class, $link);

		if (array_key_exists('sub_menu', $url) !== false) {
			foreach ($url['sub_menu'] as $sub_name => $sub_url) {
				((mb_stripos($address, $sub_url['action']) !== false) ? $class = 'item active sub-item' : $class = 'item sub-item');
				$link = $this->Html->link($sub_name, array('controller' => $sub_url['controller'], 'action' => $sub_url['action']));
				echo $this->Html->div($class, $link);
			}
		}

	}

?>