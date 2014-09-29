<?php

class err_controller extends controller{
	
	
	public function __construct($args = array()){
		$this->model = new err_model;
		$lgn = $this->check_login();
		if(isset($args[0]) && !empty($args[0])){
			
			$this->view = new err_view($args[0]);
		}else{
			$this->view = new err_view();
		}
		
	}
}