<?php
	require_once('classes/ARSession.class.php');
	$session = new ARSession('bonusCash');

	include_once('mysqliUtils.php');
	include_once('displayUtils.php');
	include_once('defines.php');

	$db = new ARDB();
	$bcc = new BonusCashController();
	$bcc->init();
?>