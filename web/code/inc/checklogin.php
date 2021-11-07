<?php
//NOT use global authorization
define('REQUIRE_AUTH',0);

define('DISALLOW_GOOGLEBOT',0);

session_start();

if(!isset($_SESSION['user']) && (DISALLOW_GOOGLEBOT || !isset($_SERVER['HTTP_USER_AGENT']) || FALSE===strstr($_SERVER['HTTP_USER_AGENT'],'Googlebot'))){
	require 'inc/cookie.php';
	if(!check_cookie()) {
		if(REQUIRE_AUTH) {
		  header("location: auth.php");
		  exit;
		}
	}else{
		require_once 'inc/database.php';
		require_once 'inc/userlogin.php';
		if(TRUE===login($_SESSION['user'], TRUE))
			write_cookie();
		mysql_close();
	}
}
