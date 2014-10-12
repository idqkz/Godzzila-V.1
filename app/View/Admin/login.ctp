<?php

	echo $this->Form->create('User', 		array('inputDefaults' => array('class' => 'input-text', 'label' => false)));
	echo $this->Form->input('phone', 	array('placeholder' => 'Ваш сотовый телефон', 'type' => 'tel', 'pattern' => '8[0-9]{10}'));
?>
	<div class="input-password visible">
		<?php echo $this->Form->input('User.password', array('placeholder'=>'Пароль', 'div' => false)); ?>
		<i class='glyphicon link link-primary'>показать</i>
	</div>
	<div class="input-password hidden">
		<?php echo $this->Form->input('User.password', array('placeholder'=>'Пароль', 'type'=>'text', 'div' => false)); ?>
		<i class='glyphicon link link-primary'>скрыть</i>
	</div>
<?
	echo $this->Form->submit('войти', array('class' => 'btn btn-success'));
	echo $this->Form->end();

	echo $this->Html->script('view-pass');

?>