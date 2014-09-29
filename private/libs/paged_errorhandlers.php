<?php
function paged_error_handler($error_level, $error_message, $error_file, $error_line){
	$err_str = "<br /><b>".date('d M Y H:i:s')."</b><br/><p>"."Error Level: ".$error_level."<br />Message:".$error_message."<br />File: ".$error_file."<br />In line: ".$error_line."</p><br />";
	error_log($err_str, 3, 'error.log.html');
	header("Location: somethingwentwrong.php");
	exit();
}
set_error_handler("paged_error_handler");
function paged_error_simple($string = "Sorry! Something went wrong. Please try again"){
	echo $string."<br />";
	exit();
}
function paged_success_simple($string = "All done!"){
	echo $string;
	exit();
}
?>