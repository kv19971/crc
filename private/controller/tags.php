<?php 
class tags_controller extends controller{
	public function __construct($args){
		$this->view = new tags_view;
		$this->model = new tags_model;
		$this->check_login_final();
		if(!isset($args[0])){
			errorocc("404: Page not found");
		}
		if(!isset($args[1])){
			errorocc("Invalid request");
		}
		if(strlen($args[1]) >60){
			errorocc("Invalid Request");
		}
		if($args[0] == "add"){
			$this->add_tag($args[1]);
		}elseif($args[0] == "delete"){
			$this->delete_tag($args[1]);
		}elseif($args[0] == "view"){
			$this->see_tag($_SESSION['uid']);
			if(isset($args[1])){
				$args[1] = NULL;
			}
		}
	}
	private function add_tag($tag){
		if($this->model->check_tag_exists($tag, $_SESSION['uid'])){
			errorocc("You already have added this tag!");
		}
		if($this->model->add_tag($tag, $_SESSION['uid'])){
			echo "Success";
		}
	}
	private function delete_tag($tag){
		if($this->model->check_tag_exists($tag, $_SESSION['uid'])){
			if($this->model->delete_tag($tag, $_SESSION['uid'])){
				echo "Success";
			}
		}else{
			errorocc("You didn't have this tag!");
		}
	}
	private function see_tag($uid){
		$this->view->tag_begin($uid);
		foreach($this->model->get_tags($uid) as $row){
			$this->view->append_tag($row['tag']);
		}
		
	}
	
}