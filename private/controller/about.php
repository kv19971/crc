<?php

class about_controller extends controller{
	public function __construct($args = array()){
		$this->view = new about_view;
	}
}