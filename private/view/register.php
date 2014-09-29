<?php


class register_view extends view{
	public function __construct(){
		$this->title = "Register";
		self::$btext = "";
		$this->logged = FALSE;
	}
	
	
	public function render_form(){

		self::$btext .="
		<div class='row'><div class='col-md-12'><div class='title'><h1>Sign Up</h1></div></div></div>
		<form id='register'>
		<div class='row'>	
		<div class='col-md-2'></div><div class='col-md-2'>Email</div><div class='col-md-6'>
		<input class='text' type='text' name='mail' {$this->render_val_field('mail')}/></div></div>
		<div class='row'>	
		<div class='col-md-2'></div><div class='col-md-2'>User ID</div><div class='col-md-6'>
		<input class='text' type='text' name='uid' {$this->render_val_field('uid')}/></div></div>
<div class='row'>	
		<div class='col-md-2'></div><div class='col-md-2'>Password</div><div class='col-md-6'>
		<input class='text' type='password' name='pwd1' /></div></div>
		<div class='row'>	
		<div class='col-md-2'></div><div class='col-md-2'>Confirm Password</div><div class='col-md-6'>
		<input class='text' type='password' name='pwd2' /></div></div>
		<div class='row'><div class='col-md-2'></div><div class='col-md-3'>Enter the text seen in the image inside the text box</div></div>
		<div class='row'><div class='col-md-2'></div><div class='col-md-4'>
		<img src='data:image/jpeg;base64, ".generate_captcha()."' /></div><div class='col-md-4'><input class='text' type='text' name='capt' /></div></div>
			<div class='row'>
			<div class='col-md-3'></div>
			<div class='col-md-6'>
			<input onclick='post_data(\"register\", \"".SERVER_ROOT_ONLY."register/submit\")' class='button' value='Sign Up' />
			</div>
			</div>
		</form>
		";
		
		
	}
	
	
	
}