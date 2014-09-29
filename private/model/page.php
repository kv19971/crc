<?php
class page_model extends model{
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
					throw new PDOException("");
				}
				if(!$this->con->query("INSERT INTO
				page_tag 
				(uid, pid, tag) 
				VALUES 
				{$tags}")){
					throw new PDOException("");
				}
				$this->con->commit();
				return TRUE;
			}catch(PDOException $e){
				$this->con->rollback();
				echo $e->getmessage();
				echo $tags;
				$this->mysql_error_handle();
			}
			
		}elseif($type=="repost"){
			try{
				$this->con->begintransaction();
				if(!$this->con->query("INSERT INTO page_main (pid, uid, caption, rp_id) VALUES ('$pid[0]', '$_SESSION[uid]', '$_POST[caption]', '$pid[1]')")){
					throw new PDOException("");
				}
				if(!$this->con->query("INSERT INTO
				page_tag 
				(uid, pid, tag) 
				VALUES 
				{$tags}")){
					throw new PDOException("");
				}
				$this->con->commit();
				return TRUE;
			}catch(PDOException $e){
				$this->con->rollback();
				echo $e->getmessage();
				echo $tags;
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
				if(!$this->con->query("INSERT INTO page_tag (uid, pid, tag) VALUES {$tags}")){
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
	public function show_user_pages_model($uid){
		try{
			$sth = $this->con->query("SELECT * FROM page_main WHERE uid='$uid'");
		}catch(PDOException $e){
			$this->mysql_error_handle();
		}
		return $sth;
	}
	public function show_user_pages_voted_model($uid, $aos){
		try{
			$sth = $this->con->query("SELECT page_main.pid as pid, page_main.caption as caption, page_aos.aos FROM page_aos JOIN page_main ON page_aos.pid = page_main.pid WHERE page_aos.uid='$uid' AND page_aos.aos = '$aos'");
		}catch(PDOException $e){
			$this->mysql_error_handle();
		}
		return $sth;
	}
	
	public function check_voted($id, $aos){
		try{
			$sth = $this->con->query("SELECT 1 FROM page_aos WHERE pid='$id' AND uid='$_SESSION[uid]' AND aos='$aos LIMIT 1'");
		}
		catch(PDOException $e){
			$this->mysql_error_handle();
		}
		if($sth->rowcount() == 1){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	public function vote($id, $aos, $aor){
		
		try{
			$st = $this->con->query("DELETE FROM page_aos WHERE pid='$id' AND uid='$_SESSION[uid]' LIMIT 1");
			
			if($aor == "ADD"){
				$st = $this->con->query("INSERT INTO page_aos (uid, pid, aos) VALUES ('$_SESSION[uid]', '$id', '$aos')");
			}
		}catch(PDOException $e){
			$this->mysql_error_handle();
		}
		
		if($st){
			return TRUE;
		}
	}
	public function fetch_page_link($id){
		try{
			$sth = $this->con->query("SELECT link FROM page_main WHERE pid='$id' LIMIT 1");
		}catch(PDOException $e){
			$this->mysql_error_handle();
		}
		$r = $sth->fetch();
		return $r['link'];
	}


	
}