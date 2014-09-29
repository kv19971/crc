<?php
class search_controller extends controller{
	public function __construct($args){
		$this->model = new search_model;
		$this->view = new search_view;
		$this->check_login_final();
		if($args[0] == "tag"){
			if(isset($args[1]) && is_string($args[1])){
				if(isset($args[2])){
					if($args[2] == "pages"){
						$this->get_info_tags($args[1], 'pages');
					}elseif($args[2] == "people"){
						$this->get_info_tags($args[1], 'people');
					}else{
						$this->get_info_tags($args[1]);
					}
				}
				else{
					$this->get_info_tags($args[1]);
				}
			}else{
				errorocc("404: Link not found");
			}
		}elseif($args[0] == "kw"){
			if(isset($args[1]) && is_string($args[1])){
				if(isset($args[2])){
					if($args[2] == "pages"){
						$this->get_info_search($args[1], 'pages');
					}elseif($args[2] == "people"){
						$this->get_info_search($args[1], 'people');
					}else{
						$this->get_info_search($args[1]);
					}
				}
				else{
					$this->get_info_search($args[1]);
				}
			}else{
				errorocc("404: Link not found");
			}
		}else{
			errorocc("404: Link not found");
		}
	}
	public function get_info_tags($tag, $object = 'both'){
		$this->view->print_tagsearch_title($tag);
		foreach($this->model->get_tagsearch_results($tag, $object) as $sth){
			$this->view->append_tag_result($sth['id'], $sth['txt']);
		}
	}
	public function get_info_search($kw, $object= 'both'){
		$this->view->print_tagsearch_title($kw);
		foreach($this->model->get_kwsearch_results($kw, $object) as $sth){
			$this->view->append_kw_result($sth['id'], $sth['txt']);
		}
	}
}