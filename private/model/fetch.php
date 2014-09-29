<?php 
class fetch_model extends model{
	public function fetch_id_validate($id){
		return $this->mysqlp_query("SELECT 1 FROM fetch_main WHERE fetch_id = '$id'")->rowcount();
		
	}
	public function fetch_add($qry1, $qry2){
		return $this->mysqlp_query("INSERT INTO fetch_main (name, fetch_id, uid, data, type) VALUES ".$qry1.", ".$qry2);
	}
}