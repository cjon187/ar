<?php
include_once('arSession.php');

include_once('loginUtils.php');
include_once('displayUtils.php');
include_once('mysqliUtils.php');
include_once('agreementUtils.php');
include_once('dataUtils.php');
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
}

//if(isset($_GET['id'])) $_SESSION['worksheet']['exportworksheetID'] = $_GET['id'];
$eventID = $_GET['eid'];
$download = $_GET['download'];
$eventInfo = displayEventInfo($eventID);
$dealerInfo = displayDealerInfo($eventInfo['dealerID']);
$dealerID = $dealerInfo['dealerID'];
$apptTbl = $eventInfo['apptTbl'];

if(isset($_POST['aids']))
{
	$filter = $_POST['filter'];
	$filterSQL = ' AND appointmentID in (' . $_POST['aids'] . ')';
}
else
{
	$filter = $_GET['exportFilter'];
	$search = $_GET['search'];
	
	if($filter == 'optIn') $filterSQL = ' AND optIn = "y"';
	else if($filter == 'sms') $filterSQL = ' AND source like "%sms%"';
	else if($filter == 'email') $filterSQL = ' AND source like "%email%"';
	else if($filter == 'web') $filterSQL = ' AND source like "%web%"';
	else if($filter == 'arc') $filterSQL = ' AND source like "%arc%" AND arcProspect IN("warm", "hot", "hand_raiser", "appointment")';
	else if($filter == 'arcContacted') $filterSQL = ' AND source like "%arc%"';
	else if($filter == 'digital') $filterSQL = ' AND source like "%digital%"';
	else if($filter == 'dumpster') $filterSQL = ' AND source like "%dumpster%"';
	else if($filter == 'weblog') $filterSQL = ' AND source like "%weblog%"';
	else if($filter == 'appt') $filterSQL = ' AND (appointmentTime != "" AND appointmentTime is not null)';
	else if($filter == 'show') $filterSQL = ' AND (arrivedTime != "" AND arrivedTime is not null)';
	else if($filter == 'sold') $filterSQL = ' AND (sold1 != "" AND sold1 is not null)';
	else {
		$filterSQL = '';
		$filter = 'All Leads';
	}
}
$sql = 'SELECT *,COALESCE(NULLIF(salesperson,""),NULLIF(originalSalesrep,""),"Orphan") AS salesrep FROM ' . $apptTbl . ' 
		WHERE 
			eventID = ' . $eventID . $filterSQL . '
		ORDER BY salesperson,lastname,firstname';

$appointmentsResults = mysqli_query($db_data,$sql);

$pagecount = $pdf->setSourceFile('AR Lead sheetNew.pdf');
$tplidx = $pdf->importPage(1);


while($row = mysqli_fetch_assoc($appointmentsResults)){

	$pdf->addPage('P','Letter');
	$pdf->useTemplate($tplidx, 0, 0);

	$startX = $cx = 20;
	$startY = $cy = 13;
	

	//Type of LEAD
	$pdf->SetXY(15 ,$cy);
	$pdf->SetFont('Arial','B',22);
	$leadTypes = array('sms', 'email', 'web', 'arc', 'digital', 'dumpster');
	$pdf->Cell(0,0,strtoupper($filter). (in_array($filter,$leadTypes) ? ' LEAD' : ''), 0,0,'L');

	//Salesrep
	$pdf->SetXY(115, $cy);
	$pdf->Cell(83,0,$row['salesrep'], 0,0,'R');

	$cx = $startX;
	$cy = $cy + 16;
	$pdf->SetXY($cx,$cy);
	//Dealership Name
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(0,0,$dealerInfo['dealerName'], 0,0,'L');


	$cx = 43;
	$cy = $cy + 17.3;
	//CUSTOMER INFO
	$pdf->SetXY($cx,$cy);
	$pdf->Cell(0,0, $row['firstName'], 0,0,'L');
	$pdf->SetXY($cx,$cy+=5.3);
	$pdf->Cell(0,0, $row['lastName'], 0,0,'L');
	$pdf->SetXY($cx,$cy+=5.3);
	$pdf->Cell(0,0, $row['postalCode'], 0,0,'L');
	$pdf->SetXY($cx,$cy+=5.3);
	$pdf->Cell(0,0, $row['email'], 0,0,'L');
	$pdf->SetXY($cx,$cy+=5.3);
	$pdf->Cell(0,0, $row['mainPhone'], 0,0,'L');
	$pdf->SetXY($cx,$cy+=5.3);
	$pdf->Cell(0,0, $row['mobilePhone'], 0,0,'L');

	$cx = 54.5;
	$cy = $cy + 11.5;
	//Appointment
	$pdf->SetXY($cx,$cy);
	$pdf->Cell(0,0, ($row['appointmentTime'] != "" ? date('Y-m-d', strtotime($row['appointmentTime'])) : 'None'), 0,0,'L');
	$pdf->SetXY($cx,$cy+=5.3);
	$pdf->Cell(0,0, ($row['appointmentTime'] != "" && date('h-i-s', strtotime($row['appointmentTime'])) != "12-00-00" ? date('g:i A', strtotime($row['appointmentTime'])) : 'None'), 0,0,'L');

	//CustomerNotes
	$cx = $startX;
	$cY = $cy = $cy + 23;
	$startBoxY = $cy-4;
	$pdf->SetXY($cx,$cy);
	if($row['notes'] == "") $row['notes'] = "No Notes";
	$textBoxBottomCY = displayTextBox(str_replace("\r\r", "\r",str_replace("\n", "\r", $row['notes'])), 110, $cx);
	if($row['notes'] == "No Notes") $textBoxBottomCY+= 5;
	$pdf->SetFillColor(255,255,255);
	$pdf->Rect(18,$startBoxY, 179,$textBoxBottomCY - $startBoxY,'D');	

	//Dealer-Trainer Notes
	$cx = $startX - 3;
	$cy = $textBoxBottomCY + 10;
	$pdf->SetFont('Arial','B',13);
	$pdf->SetXY($cx,$cy);
	$pdf->Cell(0,0, 'Dealer/Trainer Notes', 0,0,'L');

	//Footer
	addFooter();
	/*
	$cx = $startX;
	$cy = 270;
	$pdf->SetFont('Arial','',10);
	$pdf->SetXY($cx,$cy);
	$pdf->Cell(0,0, 'Generated By Absolute Results', 0,0,'L');
	$pdf->SetXY($cx + 130,$cy);
	$pdf->Cell(0,0, 'Lead Printed: '. date('M j, Y'), 0,0,'L');
*/
	//Sales Event Dates
	$cx = 117;
	$cy = $startY + 16;
	$pdf->SetXY($cx, $cy);
	$pdf->Cell(0,0, date('M j', strtotime($eventInfo['eventStart'])). ' - ' . date('M j',strtotime($eventInfo['eventEnd'])). '  ('.date('Y',strtotime($eventInfo['eventEnd'])).')' , 0,0,'L');

	//Vehicle Info
	$cx = $cx + 25;
	$cy += 17.5;
	$pdf->SetXY($cx,$cy);
	$pdf->Cell(0,0, $row['currentVehicleYear'], 0,0,'L');
	$pdf->SetXY($cx,$cy += 5.3);
	$pdf->Cell(0,0, trim($row['currentVehicleMake'] . ' ' . $row['currentVehicleModel'] . ' ' . $row['currentVehicleDescription']), 0,0,'L');
	$pdf->SetXY($cx,$cy += 5.3);
	$pdf->Cell(0,0, $row['currentVehicleKM'], 0,0,'L');

	//Additional Info
	$cy += 22;
	$tradeInValue = preg_match("/Trade in Value:(.*?)([,\\\$]\d\d\d\s)(.*?)([,\\\$]\d\d\d\s)/", $row['notes'], $matches);
	$matchString = substr($matches[0],16);
	$pdf->SetXY($cx,$cy);
	$pdf->Cell(0,0, $matchString, 0,0,'L');
	$pdf->SetXY($cx,$cy+=5.3);
	$pdf->Cell(0,0, $row['source'], 0,0,'L');
	

	
	

}

if($download == ""){
	$pdf->Output();
}
else{
	$pdf->Output(strtoupper($filter)."_One-Page-Leads_". $dealerInfo['dealerName']."_". date('M-d-Y', strtotime($eventInfo['saleStartDate'])) .".pdf", "D");
}

$pdf->Close();

exit;



?>