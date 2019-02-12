<?php

include_once('loginUtils.php');
include_once('displayUtils.php');
include_once('mysqliUtils.php');
include_once('dataUtils.php');
include_once('pdfUtils.php');



if(isset($_GET['key']))
{
	if($_GET['key'] != md5($_GET['eid']))
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
	exit;
}

if($_GET['eid'] == "") exit;




$sql = 'SELECT * FROM
		(SELECT * FROM ps_surveys WHERE eventID= ' . $_GET['eid'] . ' AND dealerResponded is not null AND dealerResponded != "") as t1
		INNER JOIN
		(SELECT * FROM ps_events) as t2
		USING (eventID)';

$surveyResults = mysqli_query($db_data,$sql);
if(mysqli_num_rows($surveyResults) != 1)
{
	echo '<font style="font-family:arial">Survey not initialized.</font>';
	exit;
}

$survey = mysqli_fetch_assoc($surveyResults);
$dealerInfo = displayDealerInfo($survey['dealerID']);

$db = new ARDB();
$dealer = Dealer::ById($survey['dealerID']);
//if($_SESSION['survey']['dealer']['isFrench'] == 'on') include_once('fr.php');
if($dealer->language->major == 'fr') include_once('fr.php');
else include_once('eng.php');

$am = displayStaffInfo($survey['accountManagerID']);

$origcY = 25;

$pdf->addPage('P','Letter');
/*
$pagecount = $pdf->setSourceFile('annual2013Plan.pdf');
$tplidx = $pdf->importPage(1);


$pdf->useTemplate($tplidx, 0, 0);
*/
$pdf->SetAutoPageBreak('off');
$imagePath = '../../images/logo.png';
$pdf->Image($imagePath,10,8,75);

$pdf->SetX(0);
$pdf->Rect(5,28,205,1,'F');

$pdf->SetY(15);
$pdf->SetFont('Arial','B',18);
$pdf->Cell(200,0,mb_strtoupper($dealerInfo['dealerName']),0,0,'R');

$pdf->SetY(22);
$pdf->SetFont('Arial','B',15);
if($dealer->language->major == 'fr'){
	$pdf->Cell(200,0,displayEventDate($survey, false, true),0,0,'R');
}else{
	$pdf->Cell(200,0,displayEventDate($survey, false, false),0,0,'R');
}


$pdf->SetY(newline(12));
$pdf->SetFont('Arial','B',12);
$pdf->Cell(25,0,$lang['name']);
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,0,$survey['surveyName']);
$pdf->SetY(newline(7));
$pdf->SetFont('Arial','B',12);
$pdf->Cell(25,0,$lang['phone']);
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,0,$survey['surveyPhone']);
$pdf->SetY(newline(7));
$pdf->SetFont('Arial','B',12);
$pdf->Cell(25,0,$lang['titre']);
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,0,$survey['trainerName']);
$pdf->SetY(newline(7));
$pdf->SetFont('Arial','B',12);
$pdf->Cell(45,0,$lang['accountManager']);
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,0,$am['name']);

$pdf->SetY(newline(12));
$pdf->SetFont('Arial','B',12);
$pdf->Cell(20,0,$lang['ratestaff']);
$pdf->SetY(newline(10));
$pdf->SetFont('Arial','B',12);
$pdf->Cell(15,0,$lang[$survey['productionContact']]);
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,0,$lang['contacted']);
$pdf->SetY(newline(7));
$pdf->SetFont('Arial','B',12);
$pdf->Cell(15,0,$lang[$survey['productionResponse']]);
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,0,$lang['respond']);
if($dealer->language->major == 'fr'){
	$pdf->SetY(newline(5));
	$pdf->Cell(15,0,'');
	$pdf->Cell(0,0,$lang['respond2']);
}
$pdf->SetY(newline(5));
$pdf->Cell(15,0,'');
$pdf->SetY(newline(5));
$pdf->SetFont('Arial','B',12);
$pdf->Cell(15,0,$survey['productionProcess']);
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,0,$lang['process']);
$pdf->SetY(newline(5));
$pdf->Cell(15,0,'');
$pdf->Cell(0,0,$lang['process2']);
$pdf->SetY(newline(7));
$pdf->SetFont('Arial','B',12);
$pdf->Cell(15,0,$lang[$survey['productionPortal']]);
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,0,$lang['portal']);

$pdf->SetY(newline(5));
$pdf->Cell(15,0,'');
$pdf->Cell(0,0,$lang['portal2']);

$pdf->SetY(newline(7));
$pdf->SetFont('Arial','B',12);
$pdf->Cell(15,0,$lang[$survey['productionConquest']]);
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,0,$lang['conquest']);
if($dealer->language->major == 'fr'){
	$pdf->SetY(newline(5));
	$pdf->Cell(15,0,'');
	$pdf->Cell(0,0,$lang['conquest2']);
}



$pdf->SetY(newline(12));
$pdf->SetFont('Arial','B',12);
$pdf->Cell(20,0,$lang['ratetrainer']);
$pdf->SetY(newline(10));
$pdf->SetFont('Arial','B',12);
$pdf->Cell(15,0,$survey['trainerEnergy']);
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,0,$lang['energy']);
$pdf->SetY(newline(7));
$pdf->SetFont('Arial','B',12);
$pdf->Cell(15,0,$lang[$survey['trainerTimely']]);
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,0,$lang['timely']);
$pdf->SetY(newline(7));
$pdf->SetFont('Arial','B',12);
$pdf->Cell(15,0,$survey['trainerQuality']);
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,0,$lang['quality']);
$pdf->SetY(newline(7));
$pdf->SetFont('Arial','B',12);
$pdf->Cell(15,0,$survey['trainerInteract']);
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,0,$lang['interact']);
$pdf->SetY(newline(7));
$pdf->SetFont('Arial','B',12);
$pdf->Cell(15,0,$lang[$survey['trainerBack']]);
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,0,$lang['back']);

$pdf->SetY(newline(12));
$pdf->SetFont('Arial','B',12);
$pdf->Cell(20,0,$lang['additionalcomments']);
$pdf->SetY(newline(10));

$com = html_entity_decode($survey['surveyComments']);
$pdf->SetFont('Arial','',12);
if(trim($com) == "")
{
	$pdf->SetFont('Arial','i',12);
	$com = 'none';
}
$charPerLine = 100;
$numLines = ceil(strlen($com)/$charPerLine);
for($i = 0; $i < $numLines; $i ++)
{
	if(($i*$charPerLine) + $charPerLine > strlen($com) || substr($com,($i*$charPerLine) + $charPerLine - 1,1) == " ") $dash = '';
	else $dash = '-';
	$pdf->Cell(10,0,substr($com,$i*$charPerLine, $charPerLine) . $dash);

	$pdf->SetY(newline());
}

$pdf->SetFont('Arial','',8);
$pdf->SetY(270);
$pdf->Rect(5,265,205,1,'F');
$pdf->Cell(0,0,'Copyright © ' . date('Y') . '. Absolute Results Productions Ltd. All rights reserved. Report Generated: ' . date("M j, Y"),0,0,'C');

if($_GET['staffID'] != "") $pdf->Output('//SVDC/Absolute Shared/Absolute Results/Brag Reports/' . $_GET['staffID'] . '/' . $survey['trainers'] . ' - ' . date('Y-m-d',strtotime($survey['saleEndDate'])) . ' ' .  $dealerInfo['dealerName'] . ' Survey.pdf','F');
else if($_GET['trainer'] != "")
{
	$trainer = displayStaffInfo($_GET['trainer']);
	$pdf->Output('//SVDC/absolute shared/absolute results/reports/Trainer Reports/' . $trainer['name']. '/' . $survey['saleEndDate'] . ' ' . $dealerInfo['dealerName'] . ' Survey.pdf','F');
}
else $pdf->Output($dealerInfo['dealerName'] . ' ' . displayEventDate($survey).'.pdf','I');

$pdf->Close();
?>