<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//define('SERVER_ROOT', 'localhost/pagedfinal');

//define('SITE_ROOT', 'www.paged.in/pagedfinal');
define('SERVER_ROOT', $_SERVER['DOCUMENT_ROOT'].'/crc/');
define('SERVER_ROOT_ONLY', '/crc/');

define('CMN', SERVER_ROOT.'private/common.php');
define('LIBS', SERVER_ROOT.'private/libs/');
define('CONTROLLER', SERVER_ROOT."private/controller/");
define('MODEL', SERVER_ROOT."private/model/");
define('VIEW', SERVER_ROOT."private/view/");
define('CONTROLLER_CLS', SERVER_ROOT."private/controller/controller.php");
define('MODEL_CLS', SERVER_ROOT."private/model/model.php");
define('VIEW_CLS', SERVER_ROOT."private/view/view.php");

function errorocc($string = "Something went wrong!"){
	header('Location: '.SERVER_ROOT_ONLY.'err/'.$string);
	exit();
}
function __autoload($clsname){
	$arr = array("_controller", "_model", "_view");
	$clsname = str_replace($arr, "", $clsname).".php";

	if(file_exists(CONTROLLER.$clsname)){
		require_once(CONTROLLER.$clsname);
	}elseif(file_exists(MODEL.$clsname)){
		require_once(MODEL.$clsname);
	}elseif(file_exists(VIEW.$clsname)){
		require_once(VIEW.$clsname);
	}else{
		errorocc("404: File not found");
	}
}
function generate_captcha(){
 
    //Let's generate a totally random string using md5 
    $md5_hash = md5(rand(0,999)); 
    //We don't need a 32 character long string so we trim it down to 6
    $security_code = substr($md5_hash, 15, 6); 

    //Set the session to store the security code
    $_SESSION['sc'] = $security_code;

    //Set the image width and height 
    $width = 300; 
    $height = 70;  

    //Create the image resource 
    $image = ImageCreate($width, $height);  

    //We are making three colors, white, black and gray 
    $white = ImageColorAllocate($image, 255, 255, 255); 
    $black = ImageColorAllocate($image, 0, 0, 0); 
    $grey = ImageColorAllocate($image, rand(0, 255), rand(0, 255), rand(0, 255)); 

    //Make the background black 
    ImageFill($image, 0, 0, $white); 

    //Add randomly generated string in white to the image
    ImageString($image, 5, $width/2, 25, $security_code, $black); 

    //Throw in some lines to make it a little bit harder for any bots to break 
    ImageRectangle($image,0,0,$width-1,$height-1,$grey); 
    imageline($image, 0, $height/4, $width, $height/4, $grey); 
	imageline($image, 0, $height/2, $width, $height/2, $grey); 
	imageline($image, 0, $height, $width, $height/2, $grey); 
    imageline($image, $width, 0, $width/7, $height, $grey); 
	   //Tell the browser what kind of file is come in 
    //header("Content-Type: image/png"); 
    //Output the newly created image in jpeg format 
    ob_start();
	ImagePng($image); 
    return base64_encode(ob_get_clean());
    //Free up resources
    ImageDestroy($image); 

}
//spl_autoload_register(__autoload());

