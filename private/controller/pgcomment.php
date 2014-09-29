<?php

class pgcomment_controller extends controller{
	public function __construct($args){
		$this->check_login_final();
		if(!isset($args) || empty($args)){
			errorocc("Invalid URL!");
		}
		
	}
	private function vote_comment($id, $aos){
		if($this->model->check_voted($id, $aos) == TRUE){
			$st = $this->model->vote($id, $aos, "REMOVE");
		}else{
			$st = $this->model->vote($id, $aos, "ADD");
		}
		if($st == TRUE){
			$this->view->print_sc_msg();
		}
	}
}