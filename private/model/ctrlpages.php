<?php
class ctrlpages_model extends model{
	public function link_in_db($link){
		try{
			$sth = $this->con->query("SELECT 1 FROM page_main WHERE link = '$link'");
		}catch(PDOException $e){
			$this->mysql_error_handle();
		}
		return $sth->rowcount();
	}
	public function ae_page_model($type, $tags, $pid){
		if($type=="add"){
			try{
				$this->con->begintransaction();
				if(!$this->con->query("INSERT INTO page_main (pid, uid, caption, link) VALUES ('$pid', '$_SESSION[uid]', '$_POST[caption]', '$_POST[link]')")){
					throw new exception("");
				}
				if(!$this->con->query("INSERT INTO page_tag (pid, uid, tag) VALUES {$tags}")){
					throw new exception("");
				}
				$this->con->commit();
				return TRUE;
			}catch(PDOException $e){
				$this->con->rollback();
				$this->mysql_error_handle();
			}
			
		}elseif($type == "edit"){
			try{
				$this->con->begintransaction();
				if(!$this->con->query("UPDATE page_main SET caption = '$_POST[caption]', link = '$_POST[link]' WHERE pid='$pid' AND uid='$_SESSION[uid]'")){
					throw new exception("");
				}
				if(!$this->con->query("DELETE FROM page_tag WHERE pid='$pid'")){
					throw new exception("");
				}
				if(!$this->con->query("INSERT INTO page_tag (pid, uid, tag) VALUES {$tags}")){
					throw new exception("");
				}
				$this->con->commit();
				return TRUE;
			}catch(PDOException $e){
				$this->con->rollback();
				$this->mysql_error_handle();
			}
		}
	}
	public function del_page_model($id){
		try{
			$sth = $this->con->query("DELETE FROM page_main WHERE pid='$id' LIMIT 1 ");
		}catch(PDOException $e){
			$this->mysql_error_handle();
		}
		if($sth){
			return TRUE;
		}
	}
	public function check_page_id($id){
		try{
			$sth = $this->con->query("SELECT 1 FROM page_main WHERE pid='$id' AND uid='$_SESSION[uid]'");
		}catch(PDOException $e){
			$this->mysql_error_handle();
		}
		return $sth->rowcount();
	}
	public function page_vals($pid){
		try{
			$sth = $this->con->query("SELECT link, pid, caption FROM page_main WHERE pid='$pid' LIMIT 1");
		}catch(PDOException $e){
			$this->mysql_error_handle();
		}
		return $sth->fetch();
	}
}