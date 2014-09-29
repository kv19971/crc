<?php
class settings_controller extends controller{
	protected $db_vals;
	public function __construct($args = array()){
		if($this->check_login() == FALSE){
			errorocc("Please log in to continue");
		}
		$this->view = new settings_view;
		$this->model = new settings_model;
		$this->db_vals = $this->model->get_current_vals($_SESSION['uid']);
		if(in_array('submit', $args) && isset($_POST)){
			$this->apply();
		}else{
			$this->view->render_form($this->db_vals);
			
		}
	}
	private function apply(){
		if(check_existince($_POST['pwd1']) && check_existince($_POST['pwd2'])){
			if($_POST['pwd1'] !== $_POST['pwd2']){
				$_POST['pwd1'] = FALSE;
				errorocc("Your passwords do not match");
			}else{
				$_POST['pwd2'] = 'foo';
				if(strlen($_POST['pwd1']) >30 || strlen($_POST['pwd1']) < 4){
					errorocc("Please fill in the password fields correctly!");
			}
			$_POST['pwd1'] = SHA1($_POST['pwd1']);
			
			
		}
		}else{
			$_POST['pwd1'] = $this->db_vals['pass'];
			$_POST['pwd2'] = 'foo';
		}
		if(in_array(FALSE, $_POST)){
			errorocc("Please fill in all the fields!");
			exit();
		}
		$errstr = "";
		$_POST['name'] = validate_user_name($_POST['name']);
		if($_POST['name'] == FALSE){
			$errstr .= " Please fill the name field correctly";
		}
		$_POST['mail'] = validate_user_email($_POST['mail']);
		if($_POST['mail'] == FALSE){
			$errstr .= " Please fill the email field correctly";
		}
		
		if($errstr !== ""){
			errorocc($errstr);
		}
		$this->model->final_apply($_POST);
		
		
	}
	
	

}