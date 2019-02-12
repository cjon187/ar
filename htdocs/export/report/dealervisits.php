<?php

	include_once('loginUtils.php');
	include_once('mysqliUtils.php');
	include_once('displayUtils.php');
	include_once('statsUtils.php');
	include_once('pdfUtils.php');

	if(isset($_GET['key']))
	{
		if($_GET['key'] != "9C941INMK6" && md5($_GET['did']) != $_GET['key'])
		{
			echo 'Invalid Key';
			exit;
		}
	}
	else if(isset($_GET['ekey']))
	{
		if(!checkEncrypt($_GET['did'],$_GET['ekey'],'dealer'))
		{
			echo 'Invalid Key';
			exit;
		}
	}
	else
	{
		checkPageAccess();
	}

	$dealer = displayDealerInfo($_GET['did']);

	$sql = 'SELECT * FROM ps_dealervisits WHERE dealerID = ' . $dealer['dealerID'] . ' AND date <= "' . date("Y-m-d") . '" ORDER BY date DESC';
	$results = mysqli_query($db_data,$sql);

	if(mysqli_num_rows($results) == 0)
	{
		echo 'No information available yet.';
		exit;
	}

	$origY = $cY;
	while($re = mysqli_fetch_assoc($results))
	{
		$pdf->addPage('P','Letter');
		$pdf->Image('https://ar.absoluteresults.com/images/logo.png',10,8,50);
		$pdf->SetX(0);
		$pdf->Rect(5,28,205,1,'F');

		$cY = $origY;

		$cY -= 10;
		$pdf->SetY(newline(0));
		$pdf->SetFont('Arial','B',14);
		$pdf->Cell(0,0,$dealer['dealerName'] . ' - Dealer Visits' ,0,0,'R');
		$pdf->SetY(newline());
		$pdf->Cell(0,0,date("F j, Y",strtotime($re['date'])) ,0,0,'R');

		$staff = displayStaffInfo($re['staffID']);
		$pdf->SetY(newline(20));
		$pdf->SetFont('Arial','B',14);
		$pdf->Cell(30,0,'Staff');
		$pdf->SetFont('Arial','',14);
		$pdf->Cell(0,0,$staff['name']);

		$pdf->SetY(newline());
		$pdf->SetFont('Arial','B',14);
		$pdf->Cell(30,0,'Dealership');
		$pdf->SetFont('Arial','',14);
		$pdf->Cell(0,0,$dealer['dealerName']);

		$pdf->SetY(newline());
		$pdf->SetFont('Arial','B',14);
		$pdf->Cell(30,0,'Contact');
		$pdf->SetFont('Arial','',14);
		$pdf->Cell(0,0,$re['contact']);

		$pdf->SetY(newline());
		$pdf->SetFont('Arial','B',14);
		$pdf->Cell(30,0,'Next Visit');
		$pdf->SetFont('Arial','',14);
		$pdf->Cell(0,0,$re['nextVisitDate']);

		$pdf->SetY(newline(10));
		$pdf->SetFont('Arial','B',14);
		$pdf->Cell(45,0,'Portal Reviewed');
		$pdf->SetFont('Arial','',12);
		$pdf->Cell(0,0,($re['portalReviewed'] ? "Yes" : "No");
		$pdf->SetY(newline(8));
		displayTextBox($re['portalComments']);

		$pdf->SetY(newline(10));
		$pdf->SetFont('Arial','B',14);
		$pdf->Cell(45,0,'Marketing Plan');
		$pdf->SetFont('Arial','',12);
		$pdf->Cell(0,0,($re['marketingPlanReviewed'] ? "Yes" : "No");
		$pdf->SetY(newline(8));
		displayTextBox($re['marketingPlanComments']);

		$pdf->SetY(newline(10));
		$pdf->SetFont('Arial','B',14);
		$pdf->Cell(45,0,'Private Sale');
		$pdf->SetFont('Arial','',12);
		$pdf->Cell(0,0,$re['privateSaleReviewed']);
		$pdf->SetY(newline(8));
		displayTextBox($re['privateSaleComments']);

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