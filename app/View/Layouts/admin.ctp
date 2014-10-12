<?php

?>
<!DOCTYPE html>
<html lang='ru-RU'>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $title_for_layout;?>
	</title>
	<?php
		echo $this->Html->meta('icon');

		echo $this->Html->css(array('init', 'admin'));
		echo $this->Html->script(array('jquery-2.0.3.min', 'tinymce/tinymce.min'));

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>

</head>
<body class='admin'>
	
	<div class='header'>
		<?php
			if ($this->Session->check('Message.flash')) {
				echo $this->Session->flash();
			}

			if (AuthComponent::user('id') !== null) {
				echo $this->Html->link('выйти', array('controller' => 'logout', 'action' => null), array('class' => 'btn btn-danger'));
			}
			
		?>
	</div>

	<?php
		if (isset($main_menu_items)) :
	?>
	<div class='menu-main'>
		<?php

			echo $this->element('main-menu');

		?>
	</div>
	<?php
		endif;
	?>

	<div class='content'>

		<?php 
			echo $this->fetch('content');
			// echo $this->element('sql_dump');

		?>

	</div>

	<script type="text/javascript">
		$(document).ready(function() {
			var style_formats = [
				{title: "Headers", items: [
			        {title: 'Заголовок', block: 'h3', styles: {color: '#000', fontWeight: '800', textAlign: 'center'}},
			        {title: 'Подзаголовок', block: 'h4', styles: {color: '#000', fontWeight: '300', textAlign: 'center'}},
			        {title: 'Обычный текст', block: 'p', styles: {color: '#000', fontWeight: '300', textAlign: 'center', fontSize: '16px'}},
			        {title: 'Обычный выделенный текст', block: 'p', styles: {color: 'rgb(0, 164, 182)', fontWeight: '300', textAlign: 'center', fontSize: '16px', fontStyle: 'italic'}},
			    ]},
			    {title: "Цвета", items: [
			        {title: "Блэк", inline: 'span', styles: {color: '#000'}},
			        {title: "Грей", inline: 'span', styles: {color: 'rgb(136, 136, 136)'}},
			        {title: "Тил", inline: 'span', styles: {color: 'rgb(0, 164, 182)'}},
			        {title: "Вайт", inline: 'span', styles: {color: '#fff'}},
			        {title: "Рэд", inline: 'span', styles: {color: '#f00'}}
			    ]},
			    {title: "Размеры", items: [
			        {title: "Премиум 20", inline: 'span', styles: {fontSize: '20px'}, inline: 'span'},
			        {title: "Стандарт 16",inline: 'span', styles: {fontSize: '16px'}, inline: 'span'},
			        {title: "Эконом 14", inline: 'span', styles: {fontSize: '14px'}, inline: 'span'},
			        {title: "Мини 12", inline: 'span', styles: {fontSize: '12px'}, inline: 'span'},
			        {title: "Микро 10", inline: 'span', styles: {'font-size': '10px'}},
			    ]},
			    {title: "Inline", items: [
			        {title: "Bold", icon: "bold", format: "bold"},
			        {title: "Italic", icon: "italic", format: "italic"},
			        {title: "Underline", icon: "underline", format: "underline"},
			        {title: "Strikethrough", icon: "strikethrough", format: "strikethrough"},
			        {title: "Superscript", icon: "superscript", format: "superscript"},
			        {title: "Subscript", icon: "subscript", format: "subscript"},
			        {title: "Code", icon: "code", format: "code"}
			    ]},
			    {title: "Blocks", items: [
			        {title: "Paragraph", format: "p"},
			        {title: "Blockquote", format: "blockquote"},
			        {title: "Div", format: "div"},
			        {title: "Pre", format: "pre"},
			        {title: "Обертка для таблицы с рамкой", block: "div", classes: "border"},
			    ]},
			    {title: "Alignment", items: [
			        {title: "Left", icon: "alignleft", format: "alignleft"},
			        {title: "Center", icon: "aligncenter", format: "aligncenter"},
			        {title: "Right", icon: "alignright", format: "alignright"},
			        {title: "Justify", icon: "alignjustify", format: "alignjustify"}
			    ]}
			];
			tinymce.init({
				selector: ".tinymce",
				menu: 'false',
		        height: 'auto',
		        plugins: ' link table code',
		        forced_root_block : "",
		        tools: 'inserttable',
		        toolbar: '| undo redo | styleselect | bold italic | link unlink | bullist | table | removeformat | responsivefilemanager | image media | code',
		        style_formats: style_formats,
		        height: 200
			});
		})
	</script>

</body>
</html>
