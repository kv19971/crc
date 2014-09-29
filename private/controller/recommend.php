<?php

class recommend_controller extends controller{
	public function __construct($args = array()){
		$this->view = new recommend_view;
		$this->model = new recommend_model;
		$this->check_login_final();
		
		if(!isset($args[0]) || empty($args[0]) || $args[0] == "pages"){
			$this->recommend_pages();
		}elseif($args[0] == "people"){
			$this->recommend_people();
		}elseif($args[0] == "tags"){
			$this->recommend_tags();
		}else{
			errorocc("Link not found");
		}
	}
	public function recommend_pages(){
		$sth = $this->model->recommend_pages_model();
		$this->view->show_title($_SESSION['uid'], "pages");
		foreach($sth as $row){
			$this->view->append_page($row['pid'], $row['caption'], $row['uid']);
		}
	}
	public function recommend_people(){
		$sth = $this->model->recommend_people_model();
		$this->view->show_title($_SESSION['uid'], "people");
		foreach($sth as $row){
			$this->view->append_people($row['uid']);
		}
	}
	public function recommend_tags(){
		$sth = $this->model->recommend_tags_model();
		$this->view->show_title($_SESSION['uid'], "tags");
		foreach($sth as $row){
			$this->view->append_tags($row['tag']);
		}
	}
}