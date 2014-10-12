<div class="wrapper">
	<div class="conteiner">
		<div class="aktcii">
			<div class="name">Акции</div>
			<div class="items">
				<?php 
					foreach ($aktcii as $value) {
						echo $this->Html->div('image', 
							$this->Html->image(unserialize($value['image_1']['medium'])));
						echo $this->Html->div('title', $value['Aktcii']['title']);
						echo $this->Html->div('razdelitel', '');
						echo $this->Html->div('text', $value['Aktcii']['description']);
					}
				?>
			</div>
		</div>
	</div>
</div>