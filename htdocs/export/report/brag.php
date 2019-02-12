<?php
include_once('arSession.php');

include_once('loginUtils.php');
include_once('displayUtils.php');
include_once('mysqliUtils.php');
include_once('dataUtils.php');
include_once('statsUtils.php');
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
else if(isset($_GET['hash']))
{
	$eventObj = Event::byHash($_GET['hash']);
	$_GET['eid'] = $eventObj->id;
	if(empty($_GET['eid'])) {
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
$eventObj = Event::byid($_GET['eid']);

if($eventRow == "")
{
	echo 'This summary do not exist.';
	exit;
}
/*
if($eventRow['dealerID'] != $_SESSION['login']['dealerID'])
{
	echo 'You do not have permission to access this summary.';
	exit;
}
*/

$origcY = 25;

$eventStats = displayStats($eventRow,array('hotWarmCold'));


$mailed = displayEventMailed($_GET['eid']);
$inviteListTotal = $mailed['invites'];


$dealerInfo = displayDealerInfo($eventRow['dealerID']);
$numSalesrep = $eventStats['salesrepCount'];

if(isset($_GET['approved']))
{
	$sql = 'SELECT * FROM ps_invoices WHERE hasTraining=1 AND dealerID = ' . $dealerInfo['dealerID'] . ' AND eventEnd = "' . $eventRow['saleEndDate'] . '"';
	$approved = mysqli_num_rows(mysqli_query($db_acct,$sql));
}

if(isset($_GET['export'])) {
	$customLeftMargin = $lmargin = 25;
	$numImages = 2;
	$charPerLine = 110;
} else {
	$lmargin = 10;
	$numImages = 3;
	$charPerLine = 125;
}
//$pdf = new PDF_Diag();


//$pagecount = $pdf->setSourceFile('agreement.pdf');
//$tplidx = $pdf->importPage(1);

$pdf->addPage('P','Letter');
//$pdf->useTemplate($tplidx, 0, 0);
$pdf->SetAutoPageBreak(false);
$pdf->Image(AR_SECURE_URL.'images/logo.png',$lmargin,8,75);

if(!empty($lmargin)) {
	$pdf->SetLeftMargin($lmargin);
}

$pdf->SetX(0);
$pdf->Rect($lmargin-5,28,205-$lmargin,1,'F');

$pdf->SetY(newline(10));
$pdf->SetFont('Arial','b',12);
$pdf->Cell(35,0,'Dealership');
$pdf->SetFont('Arial','',12);
$pdf->Cell(60,0,$dealerInfo['dealerName']);


$pdf->SetY(newline(7));

$pdf->SetFont('Arial','b',12);
$pdf->Cell(35,0,'Sale Date');
$pdf->SetFont('Arial','',12);
$pdf->Cell(60,0,displayEventDate($eventRow,false,false,true));

$pdf->SetFont('Arial','b',12);
$pdf->Cell(35,0,'Contact Person');
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,0,$eventRow['contact']);

$pdf->SetY(newline(7));

$pdf->SetFont('Arial','b',12);
$pdf->Cell(35,0,'Total Invites');
$pdf->SetFont('Arial','',12);
$pdf->Cell(60,0,number_format($inviteListTotal));

$pdf->SetFont('Arial','b',12);
$pdf->Cell(35,0,'Trainers');
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,0,$eventRow['trainerName']);

$pdf->SetY(newline(7));

$pdf->SetFont('Arial','b',12);
$pdf->Cell(35,0,'# Salespeople');
$pdf->SetFont('Arial','',12);
$pdf->Cell(60,0,$numSalesrep );

$pdf->SetFont('Arial','b',12);
$pdf->Cell(35,0,'Monthly Volume');
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,0,number_format($eventRow['monthVolume']));

$pdf->SetY(newline(5));
$pdf->Cell(35,0,'');
$pdf->SetFont('Arial','b',12);
$pdf->Cell(60,5,'Appointments',0,0,'C');
$pdf->SetY(newline(6));

$pdf->Cell(35,0,'');
$pdf->Cell(60,0,'','B');
$pdf->SetY(newline(4));
$pdf->Cell(35,0,'');
$pdf->SetFont('Arial','b',12);
$pdf->Cell(20,0,'Made',0,0,'C');
$pdf->Cell(20,0,'Showed',0,0,'C');
$pdf->Cell(20,0,'Traffic %',0,0,'C');

$pdf->SetY(newline(4));
hr(5,95);

$pdf->SetY(newline(6));
$pdf->SetFont('Arial','b',12);
$pdf->Cell(35,0,'Appointments');
$pdf->SetFont('Arial','',12);

$pdf->Cell(20,0,number_format($eventStats['appt']),0,0,'C');
$pdf->Cell(20,0,number_format($eventStats['apptShow']),0,0,'C');
if($eventStats['show']) {
	$pdf->Cell(20,0,number_format(100*($eventStats['apptShow']/($eventStats['show']))) . '%',0,0,'C');
}

$pdf->SetY(newline(6));
$pdf->SetFont('Arial','b',12);
$pdf->Cell(35,0,'Walk-In');
$pdf->SetFont('Arial','',12);

$pdf->Cell(20,0,'-',0,0,'C');
$pdf->Cell(20,0,number_format($eventStats['upShow']),0,0,'C');
if($eventStats['show']) {
	$pdf->Cell(20,0,number_format(100*($eventStats['upShow']/($eventStats['show']))) . '%',0,0,'C');
}


//GRAPH
$data = array('Appts' => $numAppts['appointments']['sold'], 'Walk-In' => $numAppts['walkIn']['sold']);
/*
//Pie chart
$pdf->SetXY(145,72);
$col1=array(100,100,255);
$col2=array(255,100,100);
$col3=array(255,255,100);
$pdf->PieChart(80, 25, $data, '%l (%p)', array($col1,$col2,$col3));
/////////////////////////////////////
*/
$pdf->SetY(newline(6));
hr(5,95);

$pdf->SetY(newline(-38));

$pdf->SetY(newline(15));
$pdf->Cell(130,0,'');
$pdf->SetFont('Arial','b',12);
$pdf->Cell(20,0,'Sold',0,0,'C');
$pdf->Cell(20,0,'Sold %',0,0,'C');

$pdf->SetY(newline(4));
hr(5,61,110);


$soldArray['newSold'] = 'New';
$soldArray['usedSold'] = 'Used';

foreach($soldArray as $type => $desc)
{
	$pdf->SetY(newline(6));
	$pdf->Cell(110,0,'');
	$pdf->SetFont('Arial','b',12);
	$pdf->Cell(20,0,$desc);
	$pdf->SetFont('Arial','',12);

	$pdf->Cell(20,0,number_format($eventStats[$type]),0,0,'C');
	if($eventStats['sold']) {
		$pdf->Cell(20,0,displayPercentage($eventStats[$type]/($eventStats['sold'])),0,0,'C');
	}
}
$pdf->SetY(newline(6));
hr(5,61,110);

$pdf->SetY(newline(4));
$pdf->SetFont('Arial','b',12);
$pdf->Cell(55,0,'Total');
$pdf->Cell(20,0,number_format($eventStats['show']),0,0,'C');
//$pdf->Cell(20,0,$numAppts['appointments']['sold']+$numAppts['walkIn']['sold'],0,0,'C');
$pdf->Cell(22,0,'',0,0,'C');
$pdf->Cell(13,0,'');
$pdf->Cell(20,0,'Total');
$pdf->SetFont('Arial','',12);

$pdf->Cell(20,0,number_format($eventStats['sold']),0,0,'C');
//$pdf->Cell(20,0,displayPercentage(($numAppts['appointments']['sold']+$numAppts['walkIn']['sold'])/($numAppts['appointments']['show']+$numAppts['walkIn']['show'])),0,0,'C');

$pdf->SetFillColor(255,255,0);
$pdf->Rect(65+$lmargin,$cY+6.5,72,6,'F');
$pdf->SetFillColor(0,0,0);

$pdf->SetY(newline(10));
$pdf->SetFont('Arial','b',15);
$pdf->Cell(70,0,'');
if($eventStats['show']) {
	$pdf->Cell(0,0,'CLOSING RATIO: ' . displayPercentage(($eventStats['sold'])/($eventStats['show'])));
}

if(!isset($_GET['approved']) || $approved)
{
	$pdf->SetY(newline(5));
	$pdf->SetFont('Arial','b',12);
	//$pdf->Cell(1,0,'');
	$pdf->Cell(0,0,'Comments');

	$pdf->SetY(newline());
	//$pdf->Cell(1,0,'');

	$com = $eventRow['comments'];
	$pdf->SetFont('Arial','',10);
	if(trim($com) == "")
	{
		$pdf->SetFont('Arial','i',10);
		$com = 'none';
	}


	displayTextBox($com,$charPerLine);

	$pdf->SetY(newline(10));
	$pdf->SetFont('Arial','b',12);
	//$pdf->Cell(1,0,'');
	$pdf->Cell(0,0,'Recommendations');

	$pdf->SetY(newline());
	//$pdf->Cell(1,0,'');

	$com = $eventRow['recommendations'];
	$pdf->SetFont('Arial','',10);
	if(trim($com) == "")
	{
		$pdf->SetFont('Arial','i',10);
		$com = 'none';
	}

	displayTextBox($com,$charPerLine);
}


//IMAGE INCLUDE
$imgList = EventTrainerImageFile::where('eventID',$eventRow['eventID'])->where('status',1)->get();


//IS THE PRINT IMAGES ON NEXT PAGE SET?
if(isset($_GET['imagesNextPage'])){
	$pdf->addPage('P','Letter');
	if(count($imgList) > 0) {
		$i = 0;
		$x = 0;
		foreach($imgList as $img){
			$f = ARFileController::copyFileToTemp($img);
			$pdf->Image($f,10 + (100*$x),$pdf->getY(),98,0);
			$x++;
			if($i == 1) {
				$pdf->SetY(130);
				$x = 0;
			}
			$i++;
			if($i >= 4) break;
		}
	}
} else {
	$pdf->SetY(newline(10));
	if(count($imgList) > 0) {
		$i = 0;
		foreach($imgList as $img) {
			$f = ARFileController::copyFileToTemp($img);
			$pdf->Image($f,$lmargin + (67*$i),$pdf->getY(),65,0);
			$i++;
			if($i >= $numImages) break;
		}
	}
}





addFooter();

$order = $eventStats['sold'] . ' Sold - ' . $eventRow['trainerName'] . ' - ' . date('Y-m-d',strtotime($eventRow['saleEndDate'])) . ' - ' .  $dealerInfo['dealerName'];
if(isset($_GET['order'])){
	if($_GET['order'] == "sold"){

	}
	else if($_GET['order'] == "trainerName"){
		$order = $eventRow['trainerName'] . ' - ' . $eventStats['sold'] . ' Sold - ' .  date('Y-m-d',strtotime($eventRow['saleEndDate'])) . ' - ' .  $dealerInfo['dealerName'];
	}
	else if($_GET['order'] == "eventDate"){
		$order = date('Y-m-d',strtotime($eventRow['saleEndDate'])) . ' - ' . $eventRow['trainerName'] . ' - ' .$eventStats['sold'] .' Sold - ' . $dealerInfo['dealerName'];
	}
	else if($_GET['order'] == "dealerName"){
		$order = $dealerInfo['dealerName']. ' - ' .$eventStats['sold'] . ' Sold - ' . $eventRow['trainerName'] . ' - ' . date('Y-m-d',strtotime($eventRow['saleEndDate']));
	}
}

if(isset($_GET['full'])) {
	$tempFile = ARFileController::tempFile();
	$pdf->Output($tempFile,'F');
} else {
	if($_GET['staffID'] != "") {
		$pdf->Output(utf8_decode(SVDC_AR_SHARE . 'Brag Reports/' . $_GET['staffID'] . '/' . $order . ' Summary.pdf'),'F');
	} else {
		$pdf->Output();
	}
}

$pdf->Close();


if(isset($_GET['full'])) {
	$event = Event::byId($_GET['eid']);
	$dlc = new DealerLoginController($event->dealer);
	$html = $dlc->getScoreboardHTML($event);
	$tempFile2 = ARFileController::tempFile();
	PdfBuilder::saveFromHTML($html,$tempFile2,['orientation' => 'Landscape']);

	$pdf = PdfBuilder::getInstance();
	//include_once('pdfUtils.php');

	$pages = $pdf->setSourceFile($tempFile);
	for($i = 1;$i<=$pages;$i++) {
		$pdf->addPage();
		$page = $pdf->importPage($i);
		$pdf->useTemplate($page);
	}
	$currentPage = $i;

	$pages = $pdf->setSourceFile($tempFile2);
	for($i = 1;$i<=$pages;$i++) {
		$pdf->addPage('L');
		$page = $pdf->importPage($i);
		$pdf->useTemplate($page);
	}

	if($_GET['staffID'] != "") {
		$pdf->Output(utf8_decode(SVDC_AR_SHARE . 'Brag Reports/' . $_GET['staffID'] . '/' . $order . ' Summary.pdf'),'F');
	} else {
		$pdf->Output();
	}

	$pdf->Close();

	unlink($tempFile);
	unlink($tempFile2);
}

?>