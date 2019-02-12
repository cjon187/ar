<?php
	include_once('defines.php');
	include_once('arSession.php');

	include_once('displayUtils.php');
	include_once('mysqliUtils.php');

	EmailController::sendEmail(
		'Taarga Phone Scrub Still being used!',
		'psud by ' . $_SESSION['login']['staffID'] . ' ' . $_SESSION['login']['name'],
		'dave@absoluteresults.com',
		'dave@absoluteresults.com'
	);
	echo 'no longer supported';
	exit;

	/*
include_once('defines.php');

ob_start();
echo '<form action="" method="POST" target="target1" style="display: inline-block;";>DealerID:<input type = "text" name="dealerID"><input type="submit" value="Upload"></form>';
echo '<a href ="http://ar.absoluteresults.com/taarga/phoneScrubProcess.php" target="target1"><button>Download</button></a>';
echo '<br>';
echo '<iframe style="width: 800px; height: 200px; background-color: #EEEEEE; clear: both;" name="target1"></iframe>';

$dealerID = $_POST['dealerID'];
echo $dealerID;
if(isset($dealerID)){
header('Location: ' . AR_SECURE_URL  . 'taarga/phoneScrubUpload.php?dealerID='.$dealerID);
exit();
}
*/
?>