<?php
	require_once('classes/ARSession.class.php');
	$session = new ARSession('ARAustralia');

	require_once('mysqliUtils.php');
	require_once('displayUtils.php');
    $db = new ARDB();

	function checkLogin(){
		if(!isLoggedIn()){
			header('location: login.php');
			exit;
		}
	}

	function isLoggedIn(){
		return $_SESSION['login']['username'] != '';
	}

?>