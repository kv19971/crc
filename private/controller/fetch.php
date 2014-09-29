<?php 

class fetch_controller extends controller{
	public function __construct($args = array()){
		$this->model = new fetch_model;
		$this->view = new fetch_view;
		$this->check_login_final();
		if(!isset($args[0]) || empty($args[0])){
			errorocc("404: Link not found");
		}
		if($args[0] == "add"){
			$this->fetch_add();
		}elseif($args[0] == "edit"){
			if(!isset($args[1]) || empty($args[1])){
				errorocc("Invalid request");
			}
			if($this->validate_fetch_id($args[1]) == FALSE){
				errorocc("Invalid request");
			}
			$this->fetch_edit($args[1]);
		}elseif($args[0] == "schemes"){
			if(!isset($args[1]) || empty($args[1])){
				$args[1] = $_SESSION['uid'];
			}else{
				if(validate_user_id($args[1])){
					if(!$this->check_uid_exists($args[1])){
						errorocc("Invalid request");
					}
				}else{
					errorocc("Invalid request");
				}
			}
			$uid = $args[1];
			$this->fetch_view_schemes($uid);
		}elseif($args[0] == "adopt"){
			if(!isset($args[1]) || empty($args[1])){
				errorocc("Invalid request");
			}
			if($this->validate_fetch_id($args[1]) == FALSE){
				errorocc("Invalid request");
			}
			$this->fetch_adopt($args[1]);
		}else{
			errorocc("404:Link not found");
		}
	}
	private function validate_fetch_id($id){
		if(strlen($id) > 42 || substr($id, -5) != "fetch"){
			return FALSE;
		}else{
			return $this->model->check_fetch_id($id);
		}
	
	}
	private function fetch_add(){
		if(!isset($_POST) || empty($_POST)){
			$this->view->fetch_form('start', "add");
			$this->view->fetch_form('people');
			//include(SERVER_ROOT."private/model/model.php");
			include(SERVER_ROOT."private/model/follow.php");
			$follow = new follow_model;
			foreach($follow->select_following(TRUE) as $row){
				$this->view->fetch_form_element("people", $row['following']);
			}
			$follow = NULL;
			include(SERVER_ROOT."private/model/tags.php");
			$follow = new tags_model;
			$this->view->fetch_form('tags');
			foreach($follow->get_tags($_SESSION['uid']) as $row){
				$this->view->fetch_form_element("tags", $row['tag']);
			}
			$this->view->fetch_form('end');
		}else{
			if(empty($_POST['people']) && empty($_POST['tags'])){
				errorocc("Please give some data!");
				
			}
			if(!isset($_POST['scheme_name']) || empty($_POST['scheme_name'])){
				errorocc("Please give a scheme name!");
			}
			$nm = $_POST['scheme_name'];
			$fetch_id = $_SESSION['uid']."_".time()."_fetch";
			if(empty($_POST['people'])){
				$qry_part1 = "";
			}else{
				$qry_part1 = "('{$nm}, {$fetch_id}', '{$_SESSION['uid']}', '".implode("', 'user'), ('{$nm}, {$fetch_id}', '{$_SESSION['uid']}', '", $_POST['people'])."', 'user')";
			}
			if(empty($_POST['tags'])){
				$qry_part2 = "";
			}else{
				$qry_part2 = "('{$nm}, {$fetch_id}', '{$_SESSION['uid']}', '".implode("', 'tag'), ('{$nm}, {$fetch_id}', '{$_SESSION['uid']}', '", $_POST['tags'])."', 'tag')";
			}
			if($this->model->fetch_add($qry_part1, $qry_part2)){
				echo "Success";
			}
		}
	}
}