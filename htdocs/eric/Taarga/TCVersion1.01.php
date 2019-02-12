<?php
	include_once('defines.php');
	include_once('arSession.php');

	include_once('displayUtils.php');
	include_once('mysqliUtils.php');

	EmailController::sendEmail(
		'Taarga Phone Scrub Still being used!',
		'TCVersion1.01 by ' . $_SESSION['login']['staffID'] . ' ' . $_SESSION['login']['name'],
		'dave@absoluteresults.com',
		'dave@absoluteresults.com'
	);
	echo 'no longer supported';
	exit;

	/*
include_once('defines.php');
session_start();

//-----------------------------------------------------------------------------------------------------------------------------------
//----------TAARGA UPLOADER AND DOWNLOADER-------------------------------------------------------------------------------------------
ob_start();
echo '<h2>Taarga Uploader and Downloader</h2>';
echo '<form action="" method="POST" target="target1" style="display: inline-block;";>DealerID:<input type = "text" name="dealerID"><input type="submit" value="Upload"></form>';
echo '<a href ="http://ar.absoluteresults.com/taarga/phoneScrubProcess.php" target="target1"><button>Download</button></a>';
echo '<br>';
echo '<iframe style="width: 800px; height: 200px; background-color: #EEEEEE; clear: both;" name="target1"></iframe>';

$dealerID = $_POST['dealerID'];
echo $dealerID;
if(isset($dealerID)){
header('Location: ' . AR_SECURE_URL . 'taarga/phoneScrubUpload.php?dealerID='.$dealerID);
exit();
}


//-----------------------------------------------------------------------------------------------------------------------------------
//----------TAARGA CHECKER-----------------------------------------------------------------------------------------------------------
echo '<h2>Taarga Checker</h2>';
echo "<form action='' method='POST'>DealerName:<input type = 'text' name='dName'><input type='submit' value='Search'></form>";
$path = "../../taarga/complete/";

if (isset($_POST['dName'])) {
	$result2 = strposa($_SESSION['list'], $_POST['dName']);
}
function strposa($haystacks = array(), $needle) {
	$offset=0;
	$chr = array();
	foreach($haystacks as $key => $haystack) {
			$res = strpos(strtolower($haystack), strtolower($needle), $offset);
			if ($res !== false){
				echo "<p style='color:green;margin-bottom:-30px;'>". $haystack."</p></br>";
			}
	}
}
$array = array();

if ($handle = opendir($path)) {
    echo "<br>Entries:</br>";
    while (false !== ($entry = readdir($handle))) {
		if ($entry != "." && $entry != "..") {
			$date = filemtime($path.$entry);
			$array[date("Y-m-d H:i:s",$date)] = $entry;
		}
	}

	krsort($array);
	foreach($array as $file)
	{
		echo $file."<br>";
	}
	$_SESSION['list'] = $array;
    closedir($handle);
}
*/
?>