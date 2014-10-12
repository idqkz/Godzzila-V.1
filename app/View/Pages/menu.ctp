<div class='slider specials'>
	<div class='nav prev'></div>
	<div class='nav next'></div>
	<div class='items'>
		<?php
			$specials = array(
				array(
					'id' => 1,
					'large' => serialize('specials/specials-large-1.jpg'),
					'text' => 'Небольшое описание акции'
				)
			);

			foreach ($specials as $special_item) {
				echo $this->Html->image(unserialize($special_item['large']), array('alt' => $special_item['text']));
			}
		?>
	</div>
</div>
<div class='info-block'>
	<div class='container'>
		<div class='dostavka'>
			<p>Быстрая доставка по городу 500 тенге<br>Заказ свыше 3&nbsp;500 тенге — доставка бесплатная!</p>
		</div>
	</div>
</div>
<?php 
	echo $this->element('block-items_out');
?>