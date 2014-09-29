<?php
// get user notifications 
class paged_user_notify{
	public $limit = 10;
	public $db;
	public function __construct(){
		$this->db = new paged_database;
	}
	/*
	public function show_notifications(){
		$r = $this->db->run_mysql_query("(SELECT time, notify FROM paged_page_main WHERE notify like '%RPID $_SESSION[paged_uname]%' OR notify LIKE '%TAGGED $_SESSION[paged_uname] %') UNION (SELECT time, notify FROM paged_user_follow WHERE notify like '%UID $_SESSION[paged_uname] %') UNION (SELECT time, notify FROM paged_page_comments WHERE notify like '%TAGGED $_SESSION[paged_uname] %' OR notify LIKE '%CPID $_SESSION[paged_uname]_%') ORDER BY time DESC LIMIT $this->limit ");
		if($r && mysqli_num_rows($r) > 0 ){
			while($e = $this->db->get_row_data($r)){
			
			$string = explode("AND", $e['notify']);
			foreach ($string as $string){
				if($string === " "){
					continue;
				}
				
				$string = trim($string, " ");
				if(strpos($string, "FOLLOWED BY ") !== FALSE){
					$string = str_replace("FOLLOWED BY ", "", $string);
					$l = explode(" UID ", $string);
					echo "-> <a href='profile.php?uid={$l[0]}'>".$l[0]."</a> followed you";
					
				}
				if(strpos($string, "REPOST BY ") !== FALSE){
					$string = str_replace("REPOST BY ", "", $string);
					$l = explode(" RPID ", $string);
					$not_str = "-> <a href='profile.php?uid={$l[0]}'>".$l[0]."</a> reposted <a href='pageview.php?pid=".$l[1]."'>this page</a> of yours";
					echo $not_str;
				}

				if(strpos($string, "COMMENT BY ") !== FALSE){
					$string = str_replace("COMMENT BY ", "", $string);
					$l = explode(" CPID ", $string);
					$not_str = "-> <a href='profile.php?uid={$l[0]}'>".$l[0]."</a> commented on <a href='pageview.php?pid={$l[1]}'>this page</a> of yours";
					echo $not_str;
				}
	
				if(strpos($string, "TAGGED ") !== FALSE){
					$string = str_replace("TAGGED ", "", $string);
					$string = str_replace("UID", "", $string);
					$string = str_replace("PID", "", $string);
					$string = explode("  ", $string);
					if($string[0] === $_SESSION['paged_uname']){
					$not_str = "-> <a href='profile.php?uid={$string[2]}'>".$string[2]."</a> mentioned you on <a href='pageview.php?pid={$string[1]}'>this page</a>";
					
					echo $not_str;
					}else{
						continue;
					}
				}
				echo "<br />";
			}
			
		}
			
		}else{
			echo "No notifications";
		}
	}
	*/
	public function show_notifications(){
		$e = $this->db->run_mysql_query("(SELECT `time`, page_id as a, title as b, prev_page_id as c, 'page' as type FROM paged_page_main WHERE (prev_page_id LIKE '$_SESSION[paged_uname]_%' OR title LIKE '%@$_SESSION[paged_uname] %') AND user_id != '$_SESSION[paged_uname]') UNION (SELECT time, comment_id as a, page_id as b, comment as c, 'comment' as type FROM paged_page_comments WHERE page_id LIKE '$_SESSION[paged_uname]_%' OR comment LIKE '%@$_SESSION[paged_uname] %') UNION ( SELECT time, follower as a, following as b, '' as c, 'follow' as type FROM paged_user_follow WHERE following='$_SESSION[paged_uname]') ORDER BY time DESC LIMIT $this->limit");
		
		while($r = $this->db->get_row_data($e)){
			$not_str = "";
			if($r['type'] === 'page'){
				$l = -1*(strlen(time()) + 6);
				$user = substr_replace($r['a'], "", $l);
				$user = "<a href='profile.php?uid={$user}'>{$user}</a>";
				if(strpos($r['c'], $_SESSION['paged_uname']."_")!== FALSE){
					
					$not_str = $user." reposted <a href='pageview.php?pid={$r['c']}'>this page</a> of yours";
				}else{
					$not_str = $user." mentioned you on <a href='pageview.php?pid={$r['c']}'>this page</a>";
				}
			}
			elseif($r['type'] === 'comment'){
				$l = -1*(strlen(time()) + 9);
				$user = substr_replace($r['a'], "", $l);
				$user = "<a href='profile.php?uid={$user}'>{$user}</a>";
				if(strpos($r['b'], $_SESSION['paged_uname']."_")!== FALSE){
					
					$not_str = $user." commented on <a href='pageview.php?pid={$r['b']}'>this page</a> of yours";
				}else{
					$not_str = $user." mentioned you in a comment on <a href='pageview.php?pid={$r['b']}'>this page</a>";
				}
			}
			elseif($r['type'] === 'follow'){
				
				$not_str = "<a href='profile.php?uid={$r['a']}'>{$r['a']}</a> is following you";
			}
			echo $not_str."<br />";
		}
	}
}


?>