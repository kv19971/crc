<?php
class search_controller extends controller{
	public function __construct($args = array()){
		$this->model = new search_model;
		$this->view = new search_view;
		$this->check_login_final();
		$this->pgs = $this->page_generate($args);
		if(!isset($args[0]) || empty($args[0])){
			$args[0] = NULL;
		}
		
		$this->search($args[0]);
	}
	private function search($kw){
		
			$this->view->show_search();
			if($kw == NULL){
				if(isset($_POST) || !empty($_POST)){
					if(isset($_POST['kw']) || !empty($_POST['kw'])){
						header("Location: ".SERVER_ROOT_ONLY."search/".$_POST['kw']);
					}
				}
			}else{
				$this->view->print_head($kw);
				$kw = explode(" ", $kw);
				$st = $this->model->get_results($kw, array('st'=>$this->pgs['st'], 'et'=>$this->pgs['et']));
				$this->print_items($st, $this->pgs['limit'], $this->pgs['nxt_st'], "lt");
				
			}
		}
	}
