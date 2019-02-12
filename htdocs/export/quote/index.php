<?php
	include_once('includes.php');

	require_once('pdfUtils.php');
    //require_once('html2pdf/html2pdf.class.php');
    use Carbon\Carbon;
    
	$tmp = sys_get_temp_dir().'/SalesOrder' . $_GET['id'] . '.pdf';
	$bladeParams['pdf'] = true;

	PdfBuilder::saveFromHTML($blade->view()->make('quote.'.$template,$bladeParams)->render(),$tmp);

    /*$html2pdf = new HTML2PDF('P','A4','en');
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->WriteHTML($blade->view()->make('export.quote.'.$template,$bladeParams)->render());
    $html2pdf->Output($tmp,'F');*/

	$quotePDF = clone($pdf);

	$pages = $quotePDF->setSourceFile($tmp);
	for($i = 1;$i<=$pages;$i++) {
		$quotePDF->addPage();
		$page = $quotePDF->importPage($i);
		$quotePDF->useTemplate($page);
	}

	foreach($append as $page) {
		if($page == 'calendar') {
			$quoteID = $_GET['id'];
			$customStart = Carbon::now()->format('Y-m');
			$customPath = AR_ROOT . 'htdocs/export/calendar/templates/calendar_qid' . $quoteID . '.pdf';

			if(true || !file_exists($customPath)) {
				include_once(AR_ROOT . 'htdocs/export/calendar/index.php');
			}

			$pages = $quotePDF->setSourceFile($customPath);
			
		} else {
			$pages = $quotePDF->setSourceFile('assets/' . $page . '.pdf');
		}
		for($i = 1;$i <= $pages; $i ++) {
			$quotePDF->addPage();
			$page = $quotePDF->importPage($i);
			$quotePDF->useTemplate($page);
		}
	}

	$quotePDF->Output('Sales Order ' . $_GET['id'] . '.pdf','I');
	$quotePDF->Close();	

	unlink($tmp);
?>