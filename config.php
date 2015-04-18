<?php
	/**
	* @file
	* Configuration file for Facebook API
	*/
	include_once("inc/facebook.php"); //include facebook SDK
		
	######### edit details ##########
	$appId = ''; //Facebook App ID
	$appSecret = ''; // Facebook App Secret
	$return_url = 'http://localhost/PhotoFrame-Re/index.php';  
	
	$homeurl='http://localhost/PhotoFrame/'; //home url
	$fbPermissions = 'user_status,publish_stream,user_photos';  //Required facebook permissions
	##################################

	//Call Facebook API
	$facebook = new Facebook(array(
	  'appId'  => $appId,
	  'secret' => $appSecret,
	   'cookie' => true 
	  ));
	 
	//Constructing Logout Url
	$logoutUrl = $facebook->getLogoutUrl(array( 'next' => ('http://localhost/PhotoFrame/') ));
    $fbuser=0;  
	$fbuser = $facebook->getUser();
?>