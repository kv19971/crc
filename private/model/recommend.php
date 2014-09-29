<?php

class recommend_model extends model{
	public function recommend_pages_model(){
		try{
			$sth = $this->con->query("SELECT * FROM page_main
			WHERE pid IN (SELECT DISTINCT pid FROM page_tag WHERE uid='$_SESSION[uid]') OR uid IN (SELECT following FROM user_follow WHERE follower = '$_SESSION[uid]') LIMIT 10");
		}catch(PDOException $e){
			$this->mysql_error_handle();
		}
		return $sth;
	}
	public function recommend_people_model(){
		try{
			$sth = $this->con->query("SELECT * FROM user_main WHERE uid NOT in (SELECT following FROM user_follow WHERE follower = '$_SESSION[uid]') AND uid IN (SELECT DISTINCT uid FROM page_tag WHERE tag IN (SELECT tag FROM page_tag WHERE uid = '$_SESSION[uid]')) AND uid != '$_SESSION[uid]' LIMIT 10");
		}catch(PDOException $e){
			$this->mysql_error_handle();
		}
		return $sth;
	}
	public function recommend_tags_model(){
		try{
			$sth = $this->con->query("SELECT tag FROM page_tag WHERE uid IN (SELECT following FROM user_follow WHERE follower = '$_SESSION[uid]') AND uid !='$_SESSION[uid]' LIMIT 10");
		}catch(PDOException $e){
			$this->mysql_error_handle();
		}
		return $sth;
	}
}