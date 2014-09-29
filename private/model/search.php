<?php 
class search_model extends model{
	public function get_results($kw, $pgs){
		$pqrystr = "";
		foreach($kw as $key){
			$pqrystr .= "(title LIKE '%{$key}%' OR content LIKE '%{$key}%') AND ";
		}
		$pqrystr = substr($pqrystr, 0, -4);
		echo $pqrystr;
		return $this->mysqlp_query("SELECT * FROM lit_main WHERE {$pqrystr} ORDER BY `time` DESC LIMIT {$pgs['st']}, {$pgs['et']}");
	}
}