$('document').ready(function(){

	$('.login-hide').change(function(){
		var passinput = $('.login-pass');
		
		var value = passinput.val();
		
		var name = passinput.attr('name');
		var classpass = passinput.attr('class');
		var placeholder = passinput.attr('placeholder');
		var type = (passinput.attr('type')=='password' ? 'text' : 'password');
		var id = passinput.attr('id');
		
		passinput.remove();
		
		$('.input.password').after('<input value="'+ value +'" name="'+ name  +'" class="'+ classpass +'" type="'+ type +'" id="' + id + '" />');
	});

	$('.input-password .glyphicon').click(function() {
		visible = $('.input-password.visible');
		hidden = $('.input-password.hidden');
		visible.removeClass('visible').addClass('hidden');
		hidden.removeClass('hidden').addClass('visible');
	});

	$('.input-password input').keypress(function(e) {
		var value = this.value + String.fromCharCode(e.keyCode);
		if($('.input-password.visible input').attr('type') == 'password') {
			$('.input-password input[type=text]').val(value);
		} else {
			$('.input-password input[type=password]').val('').val(value);
		}
	});

});