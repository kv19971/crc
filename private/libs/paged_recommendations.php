<?php
// paged_recommendations
class paged_recommendations{
	public $uname;
	public $db;
	public $tile;
	public function __construct(){
		$this->db= new paged_database();
		$this->uname = new paged_sessions();
		$this->tile = new paged_tile;
		if($this->uname->check_log_in() == FALSE){
			header("Location: $this->uname->linktologin");
			exit();
		}
		
		$this->uname = $this->uname->uname;
	}
	public function recommended_tags($st_limit = 0, $et_limit = 10){
		// query = 
		$t = $this->db->run_mysql_query("SELECT tag, COUNT(tag) as cnt FROM paged_user_tags  WHERE tag NOT in (SELECT tag FROM paged_user_tags WHERE user_id = '$this->uname') AND tag NOT in (SELECT tag FROM paged_tag_as WHERE user_id = '$this->uname') AND (user_id IN (SELECT following FROM paged_user_follow WHERE follower='$this->uname') OR tag IN (SELECT tag from paged_user_tags WHERE user_id IN (SELECT user_id FROM paged_user_tags WHERE tag IN (SELECT tag FROM paged_user_tags WHERE user_id = '$this->uname')))) AND user_id != '$this->uname' AND active = '1' GROUP BY tag ORDER BY time DESC, cnt DESC LIMIT $st_limit , $et_limit");
		if($t && mysqli_num_rows($t) > 0){
			while($r = $this->db->get_row_data($t)){
				$this->tile->content = $r['tag'];
				$this->tile->upvote_count = $r['cnt'];
				$this->tile->make_thing_tile();
			}
		}else{
			echo "<br />No tags available";
		}
	}
	public function recommended_people($st_limit = 0, $et_limit = 10){

		$t =$this->db->run_mysql_query("SELECT IFNULL( b.following, UUID_SHORT(
) ) AS followuid, COUNT(b.following) as cnt, a.about, a.user_id FROM paged_user_main a LEFT OUTER JOIN paged_user_follow b ON a.user_id = b.following WHERE (a.user_id in (SELECT user_id FROM paged_user_tags WHERE tag in (SELECT tag FROM paged_user_tags WHERE user_id='$this->uname') AND user_id NOT in (SELECT following FROM paged_user_follow WHERE follower = '$this->uname')) OR a.user_id IN (SELECT following FROM paged_user_follow WHERE follower IN (SELECT following FROM paged_user_follow WHERE user_id = '$this->uname'))) AND a.user_id != '$this->uname' AND a.active = '1' GROUP BY followuid ORDER BY cnt DESC LIMIT $st_limit , $et_limit");
		if($t && mysqli_num_rows($t) > 0){
			while($r = $this->db->get_row_data($t)){
				
				$this->tile->user_name = $r['user_id'];
				$this->tile->content = $r['about'];
				$this->tile->upvote_count = $r['cnt'];
				$this->tile->make_person_tile();
		
			
			
			}
		}else{
			echo "<br />No people available";
		}
	}
	public function recommended_pages($st_limit = 0, $et_limit = 20){
		$e = $this->db->run_mysql_query("SELECT shownsfw FROM paged_user_main where user_id = '$_SESSION[paged_uname]'");
		$r = $this->db->get_row_data($e);
		if($r['shownsfw'] == 1){
			$nsfw = " AND a.nsfw = '0' ";
		}else{
			$nsfw = "";
		}
		$query = "SELECT * FROM paged_page_main WHERE ((page_id IN (SELECT DISTINCT page_id FROM paged_page_as WHERE user_id IN (SELECT following FROM paged_user_follow WHERE follower = '$_SESSION[paged_uname]') AND user_id !='$_SESSION[paged_uname]' ) OR user_id IN (SELECT following FROM paged_user_follow WHERE follower = '$_SESSION[paged_uname]'))
		OR page_id IN (SELECT DISTINCT tag FROM paged_user_tags WHERE user_id = '$_SESSION[paged_uname]' AND tag NOT in (SELECT tag FROM paged_tag_as WHERE user_id = '$_SESSION[paged_uname]'))
		) AND user_id != '$_SESSION[paged_uname]' AND prev_page_id NOT like '$_SESSION[paged_uname]%' AND active = '1'
        		
		ORDER BY time DESC 
 LIMIT $st_limit , $et_limit"; 
		$t = $this->db->run_mysql_query($query);
		if($t && mysqli_num_rows($t) > 0){
			while($r = $this->db->get_row_data($t)){
			$this->tile->hidebtn = "<div class='tile_hidebtn'><a href='#' onclick=\"reportthis('$r[page_id]', 'page')\" >Report</a></div>";
				if($r['repost'] == 1){
					$viauid = explode("_",$r['prev_page_id']);
					$this->tile->user_name = "@<a href='profile.php?uid={$r['user_id']}'>".$r['user_id']."</a> <span class='notbold'>via @<a href='profile.php?uid={$viauid[0]}'>".$viauid[0]."</a></span>"; 
				}else{
					$this->tile->user_name = "@<a href='profile.php?uid={$r['user_id']}'>".$r['user_id']."</a>";
				}
				$this->tile->page_id = $r['page_id'];
				$this->tile->pg_source = $r['org_user'];
				$this->tile->content = $r['title'];
				if($r['NSFW'] == 1){
					$this->tile->upvote_count = "<b class='red'>NSFW</b>";
				}else{
					$this->tile->upvote_count = "";
				}
				$this->tile->make_page_tile();
				
			}
			
		}else{
			echo "<br />No pages available";
		}
	}
	
}





?>