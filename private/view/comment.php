<?php
include(VIEW.'literature.php');
class comment_view extends literature_view{
	public function ae_comment_form($type, $data){
	ob_start();
	$this->title = "Comment";
	?>
	<div class='row'><div class='col-md-12'><div class='title'>Comment</div></div></div>
	<form id="<?php echo $type; ?>comment">
<div class='row'><div class='col-md-2'></div><div class='col-md-2'>	
	Your Comment</div></div>
	<div class='row'><div class='col-md-2'></div><div class='col-md-6'><textarea class='text' name='comment' maxlength='300'><?php
			if($type == "edit"){
				echo $data['prev_comment'];
			}
		?></textarea></div></div>
	<?php
			if($type == "edit"){
				echo "<input type='hidden' name='comment_id' value='".$data["comment_id"]."' />";
			}
		?>
	<div class='row'><div class='col-md-2'></div><div class='col-md-6'><input class='button' value='Post Comment' onclick='post_data("<?php echo $type; ?>comment", "<?php echo SERVER_ROOT_ONLY.'comment/'.$data['lit_id'].'/'.$type.'/';
		if($type == "add"){
			echo $data['reply_id'];
		}elseif($type == "edit"){
			echo $data['comment_id'];
		}
	?>");  '/></div></div>
	</form>
	<div class='row'><div class='col-md-3'>
	<a href="<?php echo SERVER_ROOT_ONLY.'comment/'.$data['lit_id'].'/view'; ?>"><div class='button'>See Other comments</div></a></div></div>
	<?php
	self::$btext .= ob_get_clean();
	}
	public function print_comment_head($lit_id){
		$this->title = "Comments";
		self::$btext .="<div class='row'><div class='col-md-12'><div class='title'>Comments</div></div></div>
		<div class='row'><div class='col-md-3'>
	<a href='".SERVER_ROOT_ONLY."comment/".$lit_id."/add'><div id='pac' class='button'>Post A Comment</div></a></div></div>
		";
	}
	public function print_comment_list($st){
		if($st == "start"){
			self::$btext .="<ul style='list-style:none'>";
		}elseif($st == "end"){
			self::$btext .="</ul>";
		}
	}
	public function print_comment($data){
		$this->append_result("cm", array("comment"=>$data['comment'], "uid"=>$data['uid'], "comment_id"=>$data['comment_id'], "lit_id"=>$data['lit_id']));
	}

	
}