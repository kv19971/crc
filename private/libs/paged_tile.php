<?php
// class paged_tile 
class paged_tile{
	public $user_name;
	public $page_id;
	public $content;
	public $upvote_count;
	public $hidebtn;
	public $pg_source;
	
	public function __construct(){
		include('stylesheets/tile.css'); 
		echo "<script type='text/javascript' src='jscripts/jquery.min.js' ></script>
		<script type='text/javascript' src='jscripts/tile.js'></script>";
		
	}
	public function content_cutoff($string){
		if(strlen($string > 140)){
			return substr($string, 0, 140)."...";
		}else{
			return $string;
		}
		
		
	}
	public function make_page_tile(){
		if(!isset($this->hidebtn) || empty($this->hidebtn)){
			$this->hidebtn = "";
		}
		if(!isset($this->pg_source) || empty($this->pg_source)){
			$this->pg_source = "";
		}
		else{
			$this->pg_source = "<br />Source : <a href='profile.php?uid=".$this->pg_source."'>{$this->pg_source}</a>";
		}
		
		$this->content = $this->content_cutoff($this->content);
		$tile = "<div class='tile'>
			<div class='tile_inner'>
			
			<div class='tile_username'> {$this->user_name} </div>  $this->hidebtn
			<div class='tile_content'><a href='pageview.php?pid={$this->page_id}'> $this->content </a></div>
			<div class='tile_stats'><a href='#' onclick=\"sendpageid('$this->page_id')\" >Repost</a> | <a href='pageawesome.php?pid={$this->page_id}'>Rate Awesome</a> | <a href='pageshit.php?pid={$this->page_id}'>Rate Shit</a>| {$this->upvote_count} {$this->pg_source}</div>
			</div>
			</div>";
		echo $tile;
	}
	public function make_person_tile(){
		$this->content = $this->content_cutoff($this->content);
		 
		$tile = "<div class='tile_person'>
			<div class='tile_inner'>
			<div class='tile_username'><a href='profile.php?uid={$this->user_name}'>{$this->user_name}</a>  </div>
			<div class='tile_content'><a href='profile.php?uid={$this->user_name}'>$this->content </a></div>
			<div class='tile_stats'><a class='tile_seelater' href='follow.php?uid={$this->user_name}' >Follow $this->user_name</a> </div>
			</div>
			</div>";
		echo $tile;
	}
	public function make_thing_tile(){
		$this->content = $this->content_cutoff($this->content);
		if(!isset($this->hidebtn) || empty($this->hidebtn)){
			$this->hidebtn = "";
		}
		$tile = "<div class='tile_thing'>
			<div class='tile_inner'>$this->hidebtn
			<div class='tile_content'> #<a href='search.php?srch={$this->content}'>$this->content</a> </div>  
			<div class='tile_stats'><a class='tile_seelater' href='addtag.php?tag={$this->content}' >Add this</a> | $this->upvote_count </div>
			</div>
			</div>";
		echo $tile;
	}
	public function make_comment_tile(){
		
		if(!isset($this->hidebtn) || empty($this->hidebtn)){
			$this->hidebtn = "";
		}
		$tile = "<div class='tile_comment'>
			<div class='tile_inner'>
			<div class='tile_username'>@<a href='profile.php?uid={$this->user_name}'>{$this->user_name}</a></div>   $this->hidebtn
			<div class='tile_content'> $this->content </div>
			<div class='tile_stats'><a class='tilehref' href='upvotecomment.php?cid={$this->page_id}' >Upvote this</a> | <a class='tilehref' href='downvotecomment.php?cid={$this->page_id}' >Downvote this</a> $this->upvote_count </div>
			</div>
			</div>";
		echo $tile;
	}
	
}



?>