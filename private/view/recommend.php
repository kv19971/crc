<?php
class recommend_view extends view{
	public function show_title($uid, $content){
		self::$btext .= "<div id='title_block'><div class='container'><div class='row'><div class='col-md-12'>{$content} for $uid </div></div></div></div>";
	}
	public function append_page($id, $caption, $uid){
		$this->append_result('pg', array('id'=>$id, 'tag'=>$caption, 'uid'=>$uid));
	}
	public function append_people($id){
		$this->append_result('pl', array('uid'=>$uid));
	}
	public function append_tag($tag){
		$this->append_result('tg', array('tag'=>$tag));
	}

}