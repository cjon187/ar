<?php
	include_once('arSession.php');

	include_once('mysqliUtils.php');
	include_once('loginUtils.php');
	include_once('displayUtils.php');
	include_once('pdfDiagUtils.php');

	if(isset($_GET['ekey']))
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
	$sql = 'SELECT * FROM ps_events WHERE eventID = ' . $_GET['eid'];
	$eventResult = mysqli_query($db_data,$sql);
	if(mysqli_num_rows($eventResult) != 1) exit;
	$event = mysqli_fetch_assoc($eventResult);
	$dealerInfo = displayDealerInfo($event['dealerID']);

	$sql = 'SELECT * FROM ps_events WHERE saleEndDate <= "' . $event['saleEndDate'] . '" AND salesTypeID=5 AND confirmed = "confirmed" AND dealerID = ' . $event['dealerID'] . ' ORDER BY saleEndDate DESC';
	$eventsResults = mysqli_query($db_data,$sql);


	$origcY = 25;


	$pdf->addPage('P','Letter');
	$pdf->SetAutoPageBreak(false);

	$pdf->Image(AR_SECURE_URL . 'images/logo.png',10,8,75);



	$pdf->SetX(0);
	$pdf->Rect(5,28,205,1,'F');

	$pdf->SetY(newline(10));

	$pdf->SetFont('Arial','b',18);
	$pdf->Cell(35,0,$dealerInfo['dealerName'] . ' Event History Comparison');
	$pdf->SetY(newline(20));

	$currentEvent = mysqli_fetch_assoc($eventsResults);

	$apptTbl = date("my",strtotime($currentEvent['saleStartDate']));
	$sql = 'SELECT * FROM ps_appointments_' . $apptTbl . ' WHERE eventID = ' . $currentEvent['eventID'];
	$eventDetails = mysqli_query($db_data,$sql);

	$sold = 0;
	$appt = 0;
	$show = 0;
	$param = array();

	while($eventDetail = mysqli_fetch_assoc($eventDetails))
	{

		if($eventDetail['sold1'] == 'y') $sold++;
		if($eventDetail['sold2'] == 'y') $sold++;
		if($eventDetail['sold3'] == 'y') $sold++;
		if($eventDetail['sold4'] == 'y') $sold++;
		if($eventDetail['appointmentTime'] != '') $appt++;
		if($eventDetail['arrivedTime'] != '') $show++;
	}

	$param[$currentEvent['saleStartDate']]['sold'] = $sold;
	$param[$currentEvent['saleStartDate']]['appt'] = $appt;
	$param[$currentEvent['saleStartDate']]['show'] = $show;

	$pdf->SetFont('Arial','i',12);
	$pdf->Cell(15,0,'Event:');
	$pdf->SetFont('Arial','b',16);
	$pdf->Cell(35,0,displayEventDate($currentEvent) . ', ' . date("Y",strtotime($currentEvent['saleEndDate'])));
	$pdf->SetY(newline(5));
	$pdf->SetFont('Arial','i',10);
	$pdf->SetFillColor(50,50,50);
	$pdf->SetTextColor(255,255,255);
	$pdf->Cell(20,7,'Appt',1,0,'C',true);
	$pdf->Cell(30,7,'Total Show',1,0,'C',true);
	$pdf->Cell(20,7,'Deals',1,0,'C',true);
	$pdf->SetY(newline(7));

	$pdf->SetTextColor(0,0,0);
	$pdf->SetFont('Arial','b',14);
	$pdf->Cell(20,10,$appt,1,0,'C');
	$pdf->Cell(30,10,$show,1,0,'C');
	$pdf->Cell(20,10,$sold,1,0,'C');

	$pdf->SetY(newline(25));

	$oldEvents = array();
	while($oldEvent = mysqli_fetch_assoc($eventsResults))
	{
		$apptTbl = date("my",strtotime($oldEvent['saleStartDate']));
		$sql = 'SELECT * FROM ps_appointments_' . $apptTbl . ' WHERE eventID = ' . $oldEvent['eventID'];
		$eventDetails = mysqli_query($db_data,$sql);

		$sold = 0;
		$appt = 0;
		$show = 0;

		while($eventDetail = mysqli_fetch_assoc($eventDetails))
		{

			if($eventDetail['sold1'] == 'y') $sold++;
			if($eventDetail['sold2'] == 'y') $sold++;
			if($eventDetail['sold3'] == 'y') $sold++;
			if($eventDetail['sold4'] == 'y') $sold++;
			if($eventDetail['appointmentTime'] != '') $appt++;
			if($eventDetail['arrivedTime'] != '') $show++;
		}

		if($sold === 0) continue;

		if($oldEvent['eventID'] != $event['eventID'])
		{
			$totalSold += $sold;
			$totalAppt += $appt;
			$totalShow += $show;
		}

		$t['event'] = $oldEvent;
		$t['appt'] = $appt;
		$t['show'] = $show;
		$t['sold'] = $sold;
		$oldEvents[] = $t;

		$param[$oldEvent['saleStartDate']]['sold'] = $sold;
		$param[$oldEvent['saleStartDate']]['appt'] = $appt;
		$param[$oldEvent['saleStartDate']]['show'] = $show;
	}

	$pdf->SetFont('Arial','i',12);
	$pdf->Cell(18,0,'Previous');
	$pdf->SetFont('Arial','bi',12);
	$pdf->Cell(21,0,(mysqli_num_rows($eventsResults)-1) . ' Events');
	$pdf->SetFont('Arial','i',12);
	$pdf->Cell(20,0,'Average');
	$pdf->SetY(newline(5));
	$pdf->SetFont('Arial','i',10);
	$pdf->SetFillColor(50,50,50);
	$pdf->SetTextColor(255,255,255);
	$pdf->Cell(20,7,'Avg. Appt',1,0,'C',true);
	$pdf->Cell(30,7,'Avg. Total Show',1,0,'C',true);
	$pdf->Cell(20,7,'Avg. Deals',1,0,'C',true);
	$pdf->SetY(newline(7));

	$pdf->SetTextColor(0,0,0);
	$pdf->SetFont('Arial','b',14);
	$pdf->Cell(20,10,number_format($totalAppt/count($oldEvents)),1,0,'C');
	$pdf->Cell(30,10,number_format($totalShow/count($oldEvents)),1,0,'C');
	$pdf->Cell(20,10,number_format($totalSold/count($oldEvents)),1,0,'C');

	$pdf->SetY(newline(12));
	/*

	$pdf->SetFont('Arial','b',8);
	$pdf->Cell(25,5,'Event','B',0);
	$pdf->Cell(10,5,'Appt','B',0,'C');
	$pdf->Cell(10,5,'Show','B',0,'C');
	$pdf->Cell(10,5,'Sold','B',0,'C');
	$pdf->SetY(newline(8));

	foreach($oldEvents as $index => $oldEvent)
	{
		$pdf->SetFont('Arial','',9);
		$pdf->Cell(25,0,displayEventDate($oldEvent['event']) . ', ' . date("Y",strtotime($oldEvent['event']['saleEndDate'])));
		$pdf->Cell(10,0,$oldEvent['appt'],0,0,'C');
		$pdf->Cell(10,0,$oldEvent['show'],0,0,'C');
		$pdf->Cell(10,0,$oldEvent['sold'],0,0,'C');

		$pdf->SetY(newline());
	}
	*/
	$paramString['date'] = array_keys($param);
	foreach($param as $date => $info)
	{
		$paramString['appt'][] = $info['appt'];
		$paramString['show'][] = $info['show'];
		$paramString['sold'][] = $info['sold'];
	}

	//$pdf->Image('http://ar.absoluteresults.com/misc/eventHistoryJPGraph.php?type=Appointments&date=' . implode(',',$paramString['date']) . '&data=' . implode(',',$paramString['appt']) . '&.png',95,45,100,66);
	//$pdf->Image('http://ar.absoluteresults.com/misc/eventHistoryJPGraph.php?type=Shows&date=' . implode(',',$paramString['date']) . '&data=' . implode(',',$paramString['show']) . '&.png',95,115,100,66);
	//$pdf->Image('http://ar.absoluteresults.com/misc/eventHistoryJPGraph.php?type=Deals&date=' . implode(',',$paramString['date']) . '&data=' . implode(',',$paramString['sold']) . '&.png',95,185,100,66);
	$pdf->Image(AR_SECURE_URL . 'export/report/history_jpgraph.php?type=&date=' . implode(',',$paramString['date']) . '&appt=' . implode(',',$paramString['appt']) . '&show=' . implode(',',$paramString['show']) . '&sold=' . implode(',',$paramString['sold']) . '&.png',85,58,120,75);

	$pdf->Output($dealerInfo['dealerName'] . ' ' . date('Y-m-d',strtotime($event['saleEndDate'])) . ' History Comparison.pdf','I');
	$pdf->Close();

?>