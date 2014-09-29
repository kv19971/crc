<?php
class EE_model extends model{
	public function populate_users($names, $emails, $uids, $followers, $following){
		//$this->mysqlp_query("INSERT INTO lits (name, mail, uid, followers, following) VALUES ('$names', '$emails', '$uids', '$followers', '$following')");
		echo "LOL I DO SHIT";
		 
	}
	public function populate_lits($uid, $favs, $title, $content, $tags){
	$cid = rand();
		$this->mysqlp_query("INSERT INTO users (composition_id, uid, favourites, title, content, tags) VALUES ('$cid', '$uid', '$favs', '$title', '$content', '$tags')");
		 
		 
	}
}