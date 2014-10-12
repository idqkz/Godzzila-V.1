<div class="header">
	<div class="wrapper">
		<div class="container">
			<div class="logo"></div>
			<div class="main-menu">
				<a></a>
				<?php
					echo $this->Html->link('Корзина', 
						array('controller' => 'pages', 'action' => 'basket'),
						array('class' => 'menu-link'));
				?>
			</div>
		</div>
	</div>
</div>