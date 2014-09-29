<?php
class search_view extends view{
	public function show_search(){
		$this->title = "Search";
		self::$btext .= "
		<div class='row'><form action='".SERVER_ROOT_ONLY."/search/' method='POST'>
		<div class='col-md-4'><input class='text' placeholder='Search' type='text' name='kw' /></div><div class='col-md-2'><input class='button' value='Search' type='submit' /></div></form></div>";
	}
	public function print_head($kw){
		$this->title = "Everything Under ".$kw;
		self::$btext .= "<div id='wspace'></div><div class='row'><div class='col-md-12'><div class='title'>Everything under {$kw}</div></div></div>";
	}
	public function no_results(){
		self::$btext .= "Sorry! No results found";
	}
	public function append_lit($data){
		$this->append_result("lt", $data);
	}
}