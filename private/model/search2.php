<?php 
class search_model extends model{
	public function get_tagsearch_results($tag, $object){
		try{
			if($object == "people"){
				$sth = $this->con->query("SELECT uid as id, mail as txt FROM user_main WHERE uid in (SELECT DISTINCT uid FROM page_tag WHERE tag='$tag') AND uid!='$_SESSION[uid]' LIMIT 15");				
			}elseif($object == "pages"){
				$sth = $this->con->query("SELECT pid as id, caption as txt FROM page_main WHERE pid IN (SELECT DISTINCT pid FROM page_tag WHERE tag='$tag') LIMIT 15");				
			}else{
				$sth = $this->con->query("(SELECT uid as id, mail as txt FROM user_main WHERE uid in (SELECT DISTINCT uid FROM page_tag WHERE tag='$tag') AND uid!='$_SESSION[uid]') UNION (SELECT pid as id, caption as txt FROM page_main WHERE pid IN (SELECT DISTINCT pid FROM page_tag WHERE tag='$tag')) LIMIT 15");
			}
		}
		catch(PDOException $e){
			echo $e->getmessage();
			exit();
		}
		return $sth;
	}
	public function get_kwsearch_results($kw, $object){
		try{
			if($object == "people"){
				$sth = $this->con->query("SELECT uid as id, mail as txt FROM user_main WHERE uid LIKE '%$kw%' LIMIT 15");				
			}elseif($object == "pages"){
				$sth = $this->con->query("SELECT pid as id, caption as txt FROM page_main WHERE caption LIKE '%$kw%' OR link LIKE '%$kw%' OR uid LIKE '%$kw%' LIMIT 15");				
			}else{
				$sth = $this->con->query("(SELECT uid as id, mail as txt FROM user_main WHERE uid LIKE '%$kw%') UNION (SELECT pid as id, caption as txt FROM page_main WHERE caption LIKE '%$kw%' OR link LIKE '%$kw%' OR uid LIKE '%$kw%') LIMIT 15");
			}
		}
		catch(PDOException $e){
			echo $e->getmessage();
			exit();
		}
		return $sth;
	}
}