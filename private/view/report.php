<?php
class report_view extends view{
	public function show_report_form($type, $id){
		$this->title = "Report Content";
		self::$btext .= "<div class='row'><div class='col-md-12'><div class='title'>Report</div></div></div>
		<div class='row'><div class='col-md-2'></div><div class='col-md-2'>Please select a reason</div></div>
		<form id='report'>
		<div class='row'><div class='col-md-2'></div><div class='col-md-4'>	(User posts) Explicit content not marked as NSFW</div><div class='col-md-4'><input type='radio' name='content_r' value='(User posts) Explicit content not marked as NSFW' /></div></div>
		<div class='row'><div class='col-md-2'></div><div class='col-md-4'>	(User's) Content consists of slurs</div><div class='col-md-4'><input type='radio' name='content_r' value='(User's) Content consists of slurs' /></div></div>
		<div class='row'><div class='col-md-2'></div><div class='col-md-4'>	(User posts) Irrelevant content</div><div class='col-md-4'><input type='radio' name='content_r' value='(User posts) Irrelevant content' /></div></div>
		<div class='row'><div class='col-md-2'></div><div class='col-md-2'>Other</div></div>
		<div class='row'><div class='col-md-2'></div><div class='col-md-8'><textarea class='text' name='content'></textarea></div></div>
		<div class='row'>
			<div class='col-md-3'></div>
			<div class='col-md-6'>
			<input class='button' value='Report' onclick='post_data(\"report\", \"".SERVER_ROOT_ONLY."report/{$type}/{$id}/2\")' />
			</div>
			</div></form>";
	}
	public function print_head($fa){
		$this->title = "Reports";
		if($fa == "by"){
			self::$btext .= "<div class='row'><div class='col-md-12'><div class='title'>All Reports Filed By You</div></div></div>";
		}else{
			self::$btext .= "<div class='row'><div class='col-md-12'><div class='title'>All Reports Filed Against You</div></div></div>";
		}
	}
}