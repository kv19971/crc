<?php

class err_view extends view{
	public function __construct($errmsg = "Something went wrong"){
		self::$btext = $errmsg;
	}
}