<div class="wrapper">
	<div class="conteiner">
		<?php
			echo $this->form->create('Order', array('url' => array('controller' => 'pages', 'action' => $this->action,'success')));
			echo $this->form->hidden('status', array('value' => '1'));
			echo $this->form->input('name', array('label' => 'ФИО'));
			echo $this->form->input('phone', array('label' => 'Контактный телефон'));
			// echo $this->form->input('email', array('label' => 'Е-mail'));
			echo $this->form->input('adress', array('label' => 'Адресс доставки'));
			
			echo $this->form->input('message', array('label' => 'Коментарии', 'type' => 'textarea'));
			echo $this->form->hidden('stiker');
			echo $this->html->div('submit', $this->Form->submit('Сделать заказ', array('class' => 'btn btn-success', 'div' => false)));

			echo $this->Form->end();
		?>
	</div>
</div>