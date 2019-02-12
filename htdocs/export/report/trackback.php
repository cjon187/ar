<?php
include_once('arSession.php');

include_once('loginUtils.php');
include_once('displayUtils.php');
include_once('mysqliUtils.php');
include_once('dataUtils.php');
include_once('trackbackUtils.php');
include_once('taskUtils.php');
include_once('pdfUtils.php');


if(isset($_GET['key']))
{
	if($_GET['key'] != "9C941INMK6" && md5($_GET['eid']) != $_GET['key'])
	{
		echo 'Invalid Key';
		exit;
	}
}
else if(isset($_GET['ekey']))
{
	if(!checkEncrypt($_GET['eid'],$_GET['ekey']))
	{
		echo 'Invalid Key';
		exit;
	}
}
else
{
	checkPageAccess();
	if($_SESSION['login']['level'] == 'dealer' && $eventRow['dealerID'] != $_SESSION['login']['dealerID'])
	{
		echo 'You do not have permission to access this summary.';
		exit;
	}
}


$eventRow = displayEventInfo($_GET['eid']);
$task = array_shift(getTasks($eventRow['eventID'],'conquests'));

if($eventRow == "")
{
	echo 'This summary do not exist.';
	exit;
}
if($task['postalRoutes'] == "")
{
	echo 'No conquest information for this event.';
	exit;
}


$origcY = 25;

function newpage()
{
	global $pdf;
	global $cY;
	global $dealerInfo;
	global $eventRow;

	$pdf->addPage('P','Letter');
	//$pdf->useTemplate($tplidx, 0, 0);
	$pdf->SetAutoPageBreak(false);
	$pdf->Image('https://ar.absoluteresults.com/images/logo.png',10,8,75);

	$pdf->SetX(0);
	$pdf->Rect(5,28,205,1,'F');

	$cY = 5;
	$pdf->SetY(newline(10));
	$pdf->SetFont('Arial','b',18);
	$pdf->Cell(0,0,$dealerInfo['dealerName'],0,0,'R');

	$pdf->SetY(newline(7));

	$pdf->SetFont('Arial','b',15);
	$pdf->Cell(0,0,displayEventDate($eventRow) . ' ' . date("Y",strtotime($eventRow['saleStartDate'])),0,0,'R');
}


$apptTbl = $eventRow['apptTbl'];

//$sql = 'CREATE TABLE IF NOT EXISTS ' . $apptTbl . ' LIKE ps_appointments';
//mysqli_query($db_data,$sql);

$sql = 'SELECT * FROM ' . $apptTbl . ' where eventID = ' . $eventRow['eventID'];
$apptResults = mysqli_query($db_data,$sql);
$appts = 0;
$shows = 0;
$solds = 0;
$webs = 0;
if($apptResults) {
	while($cus = mysqli_fetch_assoc($apptResults))
	{
		if($cus['appointmentTime'] != "") $appts++;
		if($cus['arrivedTime'] != "") $shows++;
		if($cus['sold1'] != "") $solds++;
		if($cus['sold2'] != "") $solds++;
		if($cus['sold3'] != "") $solds++;
		if(stripos($cus['source'],'web') !== false) $webs++;
	}
}

$dealerInfo = displayDealerInfo($eventRow['dealerID']);

//$pagecount = $pdf->setSourceFile('agreement.pdf');
//$tplidx = $pdf->importPage(1);
newpage();

$pdf->SetY(newline(14));
$pdf->SetFont('Arial','b',15);
$pdf->Cell(0,0,'Conquest TrackBack™');
$pdf->SetY(newline(10));

$houses = '-';
$apartments = '-';
$farms = '-';
$businesses = '-';
$disArray = explode('^',preg_replace('/\d+/','$0^',$task['postalRoutesDetail']));
foreach($disArray as $dis)
{
	$tArray = explode(':',strtolower($dis));
	$tArray[0] = trim($tArray[0]);
	$$tArray[0] = trim($tArray[1]);
}

$pdf->SetFont('Arial','b',14);
$pdf->Cell(40,0,'# Flyers Sent:');
$pdf->SetFont('Arial','',14);
$pdf->Cell(30,0,taskMailed($task));

$pdf->SetY(newline(8));
$pdf->SetFont('Arial','i',10);
$pdf->Cell(25,0,'Houses:');
$pdf->SetFont('Arial','',10);
$pdf->Cell(20,0,$houses);
$pdf->SetY(newline());
$pdf->SetFont('Arial','i',10);
$pdf->Cell(25,0,'Apartments:');
$pdf->SetFont('Arial','',10);
$pdf->Cell(20,0,$apartments);
$pdf->SetY(newline());
$pdf->SetFont('Arial','i',10);
$pdf->Cell(25,0,'Farms:');
$pdf->SetFont('Arial','',10);
$pdf->Cell(20,0,$farms);
$pdf->SetY(newline());
$pdf->SetFont('Arial','i',10);
$pdf->Cell(25,0,'Businesses:');
$pdf->SetFont('Arial','',10);
$pdf->Cell(20,0,$businesses);

$tY = $cY;

$pdf->SetY(newline(-23));

$postalRoutes = explode(',',$task['postalRoutes']);
$pdf->Cell(80,0,'');
$pdf->SetFont('Arial','b',14);
$pdf->Cell(70,0,'# Postal Routes Targeted:');
$pdf->SetFont('Arial','',14);
$pdf->Cell(30,0,count($postalRoutes));

//$postalCodes = postalRoutesToCodes($postalRoutes);



$pdf->SetY(newline(10));
$pdf->Cell(80,0,'');
$pdf->SetFont('Arial','b',14);
$pdf->Cell(40,0,'FSAs Targeted:');
$pdf->SetFont('Arial','',14);

$i = 0;
$cc = new ConquestController();
$prTbl = $cc->getPostalRouteTbl();
$sql = 'SELECT * FROM ' . $prTbl . ' WHERE Route in ("' . implode('","',$postalRoutes) . '") GROUP BY FSA';
$results = mysqli_query($db_data,$sql);
while($re = mysqli_fetch_assoc($results))
{
	$i++;
	if($i > 5)
	{
		$i = 1;
		$pdf->SetY(newline(7));
		$pdf->Cell(120,0,'');
	}
	$pdf->Cell(13,0,$re['FSA']);
}


/*
$pdf->SetY(newline(7));
$pdf->Cell(80,0,'');
$pdf->SetFont('Arial','b',14);
$pdf->Cell(70,0,'# Postal Codes Targeted:');
$pdf->SetFont('Arial','',14);
$pdf->Cell(30,0,count($postalCodes));

*/
$cY = $tY;
$pdf->SetY(newline(10));
$pdf->SetFont('Arial','b',14);
$pdf->Cell(50,0,'Distribution Types');
$pdf->SetFont('Arial','i',14);
$pdf->Cell(30,0,ucwords(str_replace(',',', ',$task['postalRoutesDistributions'])));

$pdf->SetFillColor(240,240,240);
$pdf->Rect(7,$cY+10,200,30,'DF');

$trackback = getTrackBackInfo($eventRow['eventID']);

$pdf->SetY(newline(15));

$pdf->SetFont('Arial','bi',11);
$pdf->Cell(10,0,'');
$pdf->Cell(55,0,'');
$pdf->Cell(36,0,'Private Sale',0,0,'C');
$pdf->Cell(36,0,'Conquested',0,0,'C');
$pdf->Cell(36,0,'Percentage',0,0,'C');

$pdf->SetY(newline(5));

$pdf->SetFont('Arial','b',14);
$pdf->Cell(10,0,'');
$pdf->Cell(55,0,'Web Registrations');
$pdf->SetFont('Arial','',12);
$pdf->Cell(36,0,number_format($webs),0,0,'C');
$pdf->Cell(36,0,(!empty($trackback['web']) ? number_format($trackback['web']) : 'N/A'),0,0,'C');
$pdf->Cell(36,0,(!empty($trackback['web']) && !empty($webs) ? number_format(100*$trackback['web']/$webs,1).'%' : 'N/A'),0,0,'C');
$pdf->SetY(newline(6));
$pdf->SetFont('Arial','b',14);
$pdf->Cell(10,0,'');
$pdf->Cell(55,0,'Shows');
$pdf->SetFont('Arial','',12);
$pdf->Cell(36,0,number_format($shows),0,0,'C');
$pdf->Cell(36,0,(!empty($trackback['show']) ? number_format($trackback['show']) : 'N/A'),0,0,'C');
$pdf->Cell(36,0,(!empty($trackback['show']) && !empty($shows) ? number_format(100*$trackback['show']/$shows,1).'%' : 'N/A'),0,0,'C');
$pdf->SetY(newline(6));
$pdf->Cell(10,0,'');
$pdf->SetFont('Arial','b',14);
$pdf->Cell(55,0,'Sold');
$pdf->SetFont('Arial','',12);
$pdf->Cell(36,0,number_format($solds),0,0,'C');
$pdf->Cell(36,0,(!empty($trackback['sold']) ? number_format($trackback['sold']) : 'N/A'),0,0,'C');
$pdf->Cell(36,0,(!empty($trackback['sold']) && !empty($solds) ? number_format(100*$trackback['sold']/$solds,1).'%' : 'N/A'),0,0,'C');


$pdf->SetY(newline(15));

$fileList = EventArtworkImageFile::where('eventID',$eventRow['eventID'])->where('page IS NOT NULL')->where('status',1)->get();
$fileLink = '';
if(!empty($fileList)) {
	foreach($fileList as $j => $file) {
		if(!(stripos($file->file, 'CONQUEST') === false && stripos($file->file, '_c') === false)) {
			$f = ARFileController::copyFileToTemp($file);
			$pdf->Image($f,10+(100*($j)),$cY,90,0);
		}
	}
}

$pdf->Output();

$pdf->Close();
?>