<div class="wrapper">
	<div class="conteiner">
		<?php 
		if($user_id != null){
			echo $this->Html->tag('h2', 'Добавить пользователя');
			echo $this->Form->create('User');
			echo $this->Form->hidden('id');
			echo $this->Form->hidden('register_date', array('value' => date('Y-m-d H:i:s')));
			echo $this->Form->hidden('staus', array('value' => 1));
			echo $this->Form->input('group_id', array('label' => 'Тип пользователя', 'options' => $groups_list));
			echo $this->Form->input('name', array('label' => 'ФИО'));
			echo $this->Form->input('password', array('value' => ''));
			echo $this->Form->input('email', array('label' => 'Е-mail'));
			$this->Html->link('Добавить пользователя', 
				array('controller' => 'admin', 'action' => 'users/new'),
				array('class' => 'btn btn-success'));
			echo $this->Html->div('submit col-1', $this->Form->submit('Добавить',array('class' => 'btn btn-success')));
			echo $this->Form->end();
		} else{
		echo $this->Html->div('submit col' , $this->Html->link('Добавить пользователя', 
			array('controller' => 'admin', 'action' => 'users/new'),
			array('class' => 'btn btn-success')));
		}

		foreach ($sort_users as $type => $nothing) {
			echo $this->Html->div($type.' col-10');
			echo $this->Html->tag('h2',$type);
			foreach ($sort_users[$type] as $user) {
				echo $this->Html->div('col-10');
				echo $this->Html->div('col-1',$user['status']);
				echo $this->Html->div('col-2',$user['name']);
				echo $this->Html->div('col-2',$user['email']);
				$btn = 'danger';
				if ( $user['group_id'] != $users_id) {
					$title_del = 'X';
				}else {
					if($user['status'] == 2){
						$title_del = 'Разбл.';
						$btn = 'success';
					}else{
						$title_del = 'Заблк.';
					}
				}
				echo $this->Html->div('submit col' , $this->Html->link($title_del, 
							array('controller' => 'admin', 'action' => 'delete_user', $user['id']),
							array('class' => 'btn btn-'.$btn)));
				echo $this->Html->div('submit col' , $this->Html->link('/', 
							array('controller' => 'admin', 'action' => 'users', $user['id']),
							array('class' => 'btn btn-success')));
				echo '</div>';
			}
			echo '</div>';
		}
		?>
	</div>
</div>