<?php

	include_once('loginUtils.php');
	include_once('mysqliUtils.php');
	include_once('displayUtils.php');
	include_once('statsUtils.php');
	include_once('pdfUtils.php');

	if(isset($_GET['key']))
	{
		if($_GET['key'] != "9C941INMK6" && md5($_GET['sid']) != $_GET['key']) 
		{
			echo 'Invalid Key';
			exit;
		}
	}
	else if(isset($_GET['ekey']))
	{
		if(!checkEncrypt($_GET['sid'],$_GET['ekey'],'staff')) 
		{
			echo 'Invalid Key';
			exit;
		}
	}
	else
	{
		checkPageAccess();
	}
	
	$staff = displayStaffInfo($_GET['sid']);
	
	$sql = 'SELECT * FROM ps_mentorsummary WHERE jrTrainerID = ' . $staff['staffID'] . ' AND eventStart <= "' . date("Y-m-d") . '" ORDER BY eventStart DESC';
	$results = mysqli_query($db_data,$sql);

	if(mysqli_num_rows($results) == 0)
	{
		echo 'No information available.';
		exit;
	}
	
	$origY = $cY;
	while($re = mysqli_fetch_assoc($results))
	{	
		$mentor = displayStaffInfo($re['staffID']);
		$dealer = displayDealerInfo($re['dealerID']);
		
		$pdf->addPage('P','Letter');
		$pdf->Image('https://ar.absoluteresults.com/images/logo.png',10,8,50);
		$pdf->SetX(0);
		$pdf->Rect(5,28,205,1,'F');	
		
		$cY = $origY;
		
		$cY -= 10;
		$pdf->SetY(newline(0));
		$pdf->SetFont('Arial','B',14);
		$pdf->Cell(0,0,$staff['name'] . ' - Trainer Visits' ,0,0,'R');
		$pdf->SetY(newline());
		$pdf->Cell(0,0,date("F j, Y",strtotime($re['eventStart'])) ,0,0,'R');
		
		$staff = displayStaffInfo($re['staffID']);
		$pdf->SetY(newline(20));
		$pdf->SetFont('Arial','B',14);
		$pdf->Cell(30,0,'Mentor');
		$pdf->SetFont('Arial','',14);
		$pdf->Cell(0,0,$mentor['name']);
		
		$pdf->SetY(newline());
		$pdf->SetFont('Arial','B',14);
		$pdf->Cell(30,0,'Dealership');
		$pdf->SetFont('Arial','',14);
		$pdf->Cell(0,0,$dealer['dealerName']);
		
		$pdf->SetY(newline(10));
		$pdf->SetFont('Arial','B',14);
		$pdf->Cell(45,0,'Overall Rating');
		$pdf->SetFont('Arial','',12);
		$pdf->Cell(0,0,$re['trainerRating']);
		
		$pdf->SetY(newline(10));
		$pdf->SetFont('Arial','B',14);
		$pdf->Cell(45,0,'Technology');
		$pdf->SetFont('Arial','',12);
		$pdf->SetY(newline(8));
		displayTextBox($re['technology']);
		
		$pdf->SetY(newline(10));
		$pdf->SetFont('Arial','B',14);
		$pdf->Cell(45,0,'Trainer / Leader');
		$pdf->SetFont('Arial','',12);
		$pdf->SetY(newline(8));
		displayTextBox($re['leadership']);
		
		$pdf->SetY(newline(10));
		$pdf->SetFont('Arial','B',14);
		$pdf->Cell(45,0,'Phone Skills');
		$pdf->SetFont('Arial','',12);
		$pdf->SetY(newline(8));
		displayTextBox($re['phoneskills']);
		
		$pdf->SetY(newline(10));
		$pdf->SetFont('Arial','B',14);
		$pdf->Cell(45,0,'Sale Day Management Skills');
		$pdf->SetFont('Arial','',12);
		$pdf->SetY(newline(8));
		displayTextBox($re['management']);
		
		$pdf->SetY(newline(10));
		$pdf->SetFont('Arial','B',14);
		$pdf->Cell(45,0,'Hygiene, Prompt, Personality');
		$pdf->SetFont('Arial','',12);
		$pdf->SetY(newline(8));
		displayTextBox($re['hygiene']);
		
		$pdf->SetY(newline(10));
		$pdf->SetFont('Arial','B',14);
		$pdf->Cell(45,0,'Comments');
		$pdf->SetFont('Arial','',12);
		$pdf->SetY(newline(8));
		displayTextBox($re['comments']);
				
		addFooter();
	}	
	
	if(!isset($_GET['noOutput']))
	{
		$pdf->Output();	
		$pdf->Close();
	}
?>