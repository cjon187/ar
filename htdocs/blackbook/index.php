<?php	
	include_once('blackBookUtils.php');
	
	if($_GET['loc'] == '') $_GET['loc'] = 'ca';
	if($_GET['command'] == '') $_GET['command'] = 'years';
	$arg = array();
	
	if($_GET['year'] != '') $arg['year'] = $_GET['year'];
	if($_GET['make'] != '') $arg['make'] = $_GET['make'];
	if($_GET['model'] != '') $arg['model'] = $_GET['model'];
	if($_GET['trim'] != '') $arg['trim'] = $_GET['trim'];
	if($_GET['style'] != '') $arg['style'] = $_GET['style'];
	if($_GET['kilometers'] != '') $arg['kilometers'] = $_GET['kilometers'];
	
	$xml = blackBookLookup($_GET['loc'],$_GET['command'],$arg);
	
	if(!empty($xml)) {
		echo trim($xml->asXML());	
	}
?>