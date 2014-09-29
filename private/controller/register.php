<?php

class register_controller extends controller{
	public function __construct($args = array()){
		$this->view = new register_view;
		$this->model = new register_model;
		if(!isset($_SESSION)){
			$this->inst_sess();
		}
		if(isset($args[1]) && !empty($args[1])){	
			$args[1] = 2;
		}else{
			$args[1] = 1;
		}
		if(in_array('submit', $args) && isset($_POST) && $args[1] == 2){
			$this->register();
		}else{
			
			$this->view->render_form();
			
		}
	}
	private function register(){
		if(in_array(FALSE, check_array_existince($_POST))){
			$this->print_err_msg("Please fill in all the fields!");
		}
		$errstr = "";
		if(!isset($_POST['capt']) || empty($_POST['capt']) || $_POST['capt'] != $_SESSION['sc']){
			$errstr .= "The code in the image does not match that in the text box";
		}
		$_POST['mail'] = validate_user_email($_POST['mail']);
		if($_POST['mail'] == FALSE){
			$errstr .= " Please fill the email field correctly";
		}
		$_POST['uid'] = validate_user_id($_POST['uid']);
		if($_POST['uid'] == FALSE){
			$errstr .= " Please fill the user id field correctly";
		}
		if($_POST['pwd1'] !== $_POST['pwd2']){
			$_POST['pwd1'] = FALSE;
			$errstr .= "\n Your passwords do not match";
		}else{
			unset($_POST['pwd2']);
			if(strlen($_POST['pwd1']) >30 || strlen($_POST['pwd1']) < 4){
				$errstr .= "\n Please fill the password fields correctly";
			}
			$_POST['pwd1'] = SHA1($_POST['pwd1']);
			
		}
		if($errstr !== ""){
			$this->print_err_msg($errstr);
		}
		if($this->model->final_register()){
			$this->view->print_sc_msg();
		}
		
		
	}
	
	

}
