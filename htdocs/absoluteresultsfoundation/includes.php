<?php
	require_once('classes/ARSession.class.php');
	$session = new ARSession('absoluteresultsfoundation');

	include_once('mysqliUtils.php');
	include_once('displayUtils.php');
	connectMySQL('foundation');

?>