<?php

include_once('mysqliUtils.php');
include_once('displayUtils.php');
include_once('pdfUtils.php');
$db = new ARDB();

if(!empty($_GET['did']))
	$dealerInfo = displayDealerInfo($_GET['did']);
else
	$dealerInfo = array();

$origcY = 25;
$cY = $origcY;

/////////////////////////////////////////////////////////////////
//SHOW ANNUAL CALENDAR
/////////////////////////////////////////////////////////////////

$bWidth = 12;
$bHeight = 9;
$calWidth = 100;
$calHeight = 75;
$perLine = 2;
$perPage = 6;

if(!empty($_GET['start'])) {
	$startDate = date("Y-m-01",strtotime($_GET['start']));
} else if(!empty($customStart)) {
	$startDate = date("Y-m-01",strtotime($customStart));
} else {
	$startDate = '2016-01-01';
}

if(!empty($_GET['end'])) {
	$endDate = date("Y-m-t",strtotime($_GET['end']));
} else if(!empty($customEnd)) {
	$endDate = date("Y-m-t",strtotime($customEnd));
} else {
	$endDate = date('Y-m-d',strtotime('now + 12 months'));
}

$currDate = $startDate;
$calArray = array();

while($currDate <= $endDate)
{
	$calArray[$currDate]['frSkip'] = date("N",strtotime($currDate));
	if($calArray[$currDate]['frSkip'] == 7) $calArray[$currDate]['frSkip'] = 0;
	
	$days = date("t",strtotime($currDate));
	$calArray[$currDate]['endSkip'] = 42-($calArray[$currDate]['frSkip'] + $days);
	
	$currDate = date("Y-m-d",strtotime($currDate . ' + 1 month'));
	
}

$eventStartDates = array();
$eventEndDates = array();
$eventDescriptions = array();

if(!empty($quoteID)) {
	$quote = Quote::byId($quoteID);
	$activity = $quote->activity;
	if($activity instanceof CrmActivity) {
		$bookedEvents = $activity->bookedEvents;
		if(!empty($bookedEvents)) {
			foreach($bookedEvents as $event) {
				$eventStartDates[] = $event->eventStart;
				$eventEndDates[] = $event->eventEnd;
				$eventDescriptions[] = $event->displayEventDate();
			}
		}
	}
} else if(!empty($dealerInfo['dealerID'])) {
	$sql = 'SELECT * FROM ps_events WHERE dealerID=' . $dealerInfo['dealerID'] . ' AND eventStart >= "' . $startDate . '" AND eventStart < "' . $endDate . '"';
	$cEventResults = mysqli_query($db_data,$sql);

	while($cEvent = mysqli_fetch_assoc($cEventResults))
	{
		$eventStartDates[] = $cEvent['eventStart'];
		$eventEndDates[] = $cEvent['eventEnd'];
		$eventDescriptions[] = $cEvent['description'];
	}
}

$highlightDates = array();
foreach($eventStartDates as $index => $sdate)
{
	$i = 0;
	$curDate = $sdate;
	while($i <= 31 && $curDate <= $eventEndDates[$index])
	{
		$highlightDates[$curDate] = ($curDate == $sdate ? $eventDescriptions[$index].'' : '');
		$i++;
		$curDate = date("Y-m-d",strtotime($sdate . ' + ' . $i . ' days'));
	}	
}
$pdf->SetLeftMargin(8);
		
$descriptionsArray = array();
$month = 0;
foreach($calArray as $currDate => $details)
{	
	$month++;
	$currMonth = date("Y-m",strtotime($currDate));
	if(($month-1)%$perPage === 0)
	{
		$cY = $origcY;
		$pdf->addPage('P','Letter');
		$pdf->SetAutoPageBreak('off');
		if(file_exists('../../images/logo.png')) $imagePath = '../../images/logo.png';
		else $imagePath = 'images/logo.png';
		$pdf->Image($imagePath,10,8,75);
		
		$pdf->SetFillColor(0);
		$pdf->SetX(0);
		$pdf->Rect(5,28,205,1,'F');
		
		$pdf->SetY(15);
		$pdf->SetFont('Arial','B',18);
		$pdf->Cell(200,0,mb_convert_case($dealerInfo['dealerName'],MB_CASE_UPPER),0,0,'R');
		
		$pdf->SetY(22);
		$pdf->SetFont('Arial','B',15);
		$pdf->Cell(200,0,$lang['Annual Private Sale Plan'],0,0,'R');
		
		$pdf->SetY(newline(12));
			

		addFooter();	
	}
	
	$cY = 40 + ((floor((($month-1)%$perPage)/$perLine))*$calHeight);
	
	$pdf->SetY($cY);
	$frSkip = $details['frSkip'];
	$endSkip = $details['endSkip'];
	$lines = 0;
	$pdf->SetFont('Arial','B',15);
	$pdf->Cell(((($month-1)%$perLine) * $calWidth) + 1,0, '');
	if(isset($_GET['fr'])) $pdf->Cell($calWidth,0,date("Y ",strtotime($currDate)). monthFR(date("F",strtotime($currDate))));
	else $pdf->Cell($calWidth,0,strtoupper(date("F Y",strtotime($currDate))));
	
	$pdf->SetY(newline());
	$pdf->Cell(((($month-1)%$perLine) * $calWidth) + 1,0, '');
	$pdf->SetFillColor(200);
	$pdf->SetFont('Arial','B',8);

	
	$pdf->Cell($bWidth,5,'S',1,0,'C',true);
	$pdf->Cell($bWidth,5,'M',1,0,'C',true);
	$pdf->Cell($bWidth,5,'T',1,0,'C',true);
	$pdf->Cell($bWidth,5,'W',1,0,'C',true);
	$pdf->Cell($bWidth,5,'T',1,0,'C',true);
	$pdf->Cell($bWidth,5,'F',1,0,'C',true);
	$pdf->Cell($bWidth,5,'S',1,0,'C',true);		
	$pdf->SetY(newline(2));	
	
	$pdf->Cell(((($month-1)%$perLine) * $calWidth) + 1,0, '');
	
	$pdf->SetFont('Arial','',8);
	
	for($i = 1; $i <= 42;$i++)
	{	
		$pdf->Cell($bWidth,$bHeight*2,strval ($i <= $frSkip || $i > 42-$endSkip ? '':($i-$frSkip)),0,0,'C');
		if($i % 7 == 0) 
		{
			$pdf->SetY(newline($bHeight));
			$pdf->Cell(((($month-1)%$perLine) * $calWidth) + 1,0, '');
		}
	}
	
	
	$pdf->SetY(newline(0-(($bHeight*6)-3)));
	$pdf->Cell(((($month-1)%$perLine) * $calWidth) + 1,0, '');
	for($i = 1; $i <= 42;$i++)
	{	
		$pdf->Cell($bWidth,$bHeight,'',($i <= $frSkip || $i > 42-$endSkip ? 0:1),0,'L');
		if($i % 7 == 0)
		{
			$lines++;
			$pdf->SetY(newline($bHeight));
			$pdf->Cell(((($month-1)%$perLine) * $calWidth) + 1,0, '');
		}
	}
	
	

	$tY = $pdf->GetY();
	
	$pdf->SetFillColor(255,255,0);
	$pdf->SetY(newline(0-(($bHeight*6))));
	
	$pdf->Cell(((($month-1)%$perLine) * $calWidth) + 1,0, '');
	
	for($i = 1; $i <= 42;$i++)
	{	
		$fill = false;
		if(!($i <= $frSkip || $i > 42-$endSkip))
		{
			$date = date("Y-m-d",strtotime($currMonth."-".($i-$frSkip)));
			
			if(isset($highlightDates[$date]))
			{
				$fill = true;
				
				if($highlightDates[$date] != "")
				{
					$curX = $pdf->GetX();
					$curY = $pdf->GetY();
					$descriptionsArray[$date] = array('x' => $pdf->GetX(), 'y' => $pdf->GetY(), 'desc' => $highlightDates[$date]);
				}
			}
			
		}
		$pdf->Cell($bWidth,$bHeight,'',0,0,'L',$fill);
		
		
		if($i % 7 == 0)
		{
			$lines++;
			$pdf->SetY(newline($bHeight));			
			$pdf->Cell(((($month-1)%$perLine) * $calWidth) + 1,0, '');
		}
	}
	
	$pdf->SetFillColor(255,255,255);
	$cY = $tY;
	
	$pdf->SetFont('Arial','',8);
	foreach($descriptionsArray as $date => $info)
	{
		$pdf->SetY($info['y']+3);
		$pdf->Cell($info['x']-8,$bHeight,'');
		$pdf->MultiCell(0,3,$info['desc']);
		unset($descriptionsArray[$date]);
	}
}		

if(!empty($customPath)) {
	$pdf->Output($customPath,'F');
} else {
	$pdf->Output();
}


$pdf->Close();

?>