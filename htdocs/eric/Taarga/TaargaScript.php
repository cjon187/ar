<?php
	include_once('defines.php');
	include_once('arSession.php');

	include_once('displayUtils.php');
	include_once('mysqliUtils.php');

	EmailController::sendEmail(
		'Taarga Phone Scrub Still being used!',
		'TaargaScript by ' . $_SESSION['login']['staffID'] . ' ' . $_SESSION['login']['name'],
		'dave@absoluteresults.com',
		'dave@absoluteresults.com'
	);
	echo 'no longer supported';
	exit;
	echo 'no longer supported';
	exit;

	/*
include_once('defines.php');
//-----------------------------------------------------------------------------------------------------------------------------------
//----------TAARGA UPLOADER AND DOWNLOADER-------------------------------------------------------------------------------------------
ob_start();
echo '<h2>Taarga Uploader and Downloader</h2>';
echo '<form action="" method="POST" target="target1" style="display: inline-block;";>DealerID:<input type = "text" name="dealerID"><input type="submit" value="Upload"></form>';
echo '<a href ="'.AR_SECURE_URL.'taarga/phoneScrubProcess.php" target="target1"><button>Download</button></a>';
echo '<br>';
echo '<iframe style="width: 800px; height: 200px; background-color: #EEEEEE; clear: both;" name="target1"></iframe>';

$dealerID = $_POST['dealerID'];
echo $dealerID;
if(isset($dealerID)){
	header('Location: '.AR_SECURE_URL.'taarga/phoneScrubUpload.php?dealerID='.$dealerID);
	exit();
}


//-----------------------------------------------------------------------------------------------------------------------------------
//----------TAARGA CHECKER-----------------------------------------------------------------------------------------------------------
echo '<h2>Taarga Checker</h2>';
echo "<form action='' method='POST'>DealerName:<input type = 'text' name='dName'><input type='submit' value='Search'></form>";
$path = SVDC_AR_DATA_SHARE."TaargaScrubs/complete/";
$array = array();

if ($handle = opendir($path)) {
    while (false !== ($entry = readdir($handle))) {
		if ($entry != "." && $entry != "..") {
			$date = filemtime($path.$entry);
			$array[date("Y-m-d H:i:s",$date)] = $entry;
		}
	}
	if (isset($_POST['dName'])) {
		echo 'Found: <br>';
		$result2 = strposa($array, $_POST['dName']);
	}
	 echo "<br>Entries:</br>";
	krsort($array);
	foreach($array as $file)
	{
		echo $file."<br>";
	}
    closedir($handle);
}

function strposa($haystacks = array(), $needle) {
	$offset=0;
	$chr = array();
	foreach($haystacks as $key => $haystack) {
			$res = strpos(strtolower($haystack), strtolower($needle), $offset);
			if ($res !== false){
				echo "<p style='color:green; margin:3px 0;'>". $haystack."</p>";
			}
	}
}
*/
?>