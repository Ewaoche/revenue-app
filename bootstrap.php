<?php



//Initialize the Config File
if(file_exists(DOT . '/config.php')){
	include(__DIR__ . '/config.php');
	require __DIR__ . '/vendor/autoload.php';

	$Route = new Apps\Route;
	$Session = new Apps\Session;
	$Core = new Apps\Core;
	if( isset($Session->data[auth_session_key]) ){
		$Session->data['UserInfo'] =  $Core->UserInfo($Session->data['igr'],$Session->data['accid']);
		$Session->save();
	}

}else{
	die('config.php not found!');
}

