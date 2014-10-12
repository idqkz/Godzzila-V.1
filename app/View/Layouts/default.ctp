<!DOCTYPE html>
<html lang='ru-RU'>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $this->Html->meta('icon');

		echo $this->Html->css(array('init', 'theme'));

		echo $this->Html->script(array('jquery-2.0.3.min', 'TweenLite.min', 'CSSPlugin.min', 'site_functions'));

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
</head>
<body>
	<div class='container'>
		<div class='container'>
			<div class='call-1'>закажи годзиллу! <a href="tel:87172-445177">8&nbsp;(7172)&nbsp;445-177</a></div>
		</div>
		<div class='main-menu'>
			<div class='container'>
			<?php
				echo $this->Html->image('theme/godzilla.kz-logo.png', array('class' => 'logo', 'alt' => 'Godzilla.kz быстрая доставка вкусных суши роолов и десертов в Астане'));

				$menu_html = null;

				foreach ($menu_items as $item) {
					$address = $this->request->url;

					$class = 'item ' . $item['action'];
					
					if ($item['controller'] == null) {
						$href = '#' . $item['action'];
					} else {
						$href = array('controller' => $item['controller'], 'action' => $item['action']);

						if (mb_strstr($address, $item['controller']) === false) {
						} elseif ($item['controller'] != '/') {
							$class .= ' active';
						}
					}
					
					$link = $this->Html->link($item['name'], $href, array('class' => 'link', 'escape' => false));
					$menu_html .= $this->Html->div($class, $link);
				}

				echo $this->Html->div('items', $menu_html);

			?>
			</div>
		</div>

		<?php

			echo $this->Session->flash();

			echo $this->fetch('content'); 

		?>

		<div class='footer'>
			<div class='container'>
				<?php
					echo $this->Html->image('theme/godzilla.kz-logo.png', array('class' => 'logo', 'alt' => 'Godzilla.kz быстрая доставка вкусных суши роолов и десертов в Астане'));
				?>
				<div class='call-1'>закажи годзиллу! <a href="tel:87172-445177">8&nbsp;(7172)&nbsp;445-177</a></div>
			</div>
		</div>

	</div>
</body>
</html>
