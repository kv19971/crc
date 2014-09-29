<?php
class tags_model extends model{
	public function add_tag($tag, $uid){
		return $this->mysqlp_query("INSERT INTO page_tag(uid, tag) VALUES ('$uid', '$tag')");
	}
	public function delete_tag($tag, $uid){
		return $this->mysqlp_query("DELETE FROM page_tag WHERE uid ='$uid' AND tag = '$tag' LIMIT 1");
			
		
	}
	public function check_tag_exists($tag, $uid){
		$sth = $this->mysqlp_query("SELECT row FROM page_tag WHERE uid='$uid' AND tag = '$tag' LIMIT 1");
		return $sth->rowcount();
	}
	public function get_tags($uid){
		return $this->mysqlp_query("SELECT tag FROM page_tag WHERE uid = '$uid' ");
	}
}