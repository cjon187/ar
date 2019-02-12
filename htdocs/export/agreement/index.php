<?php
	include_once('defines.php');
	include_once('displayUtils.php');
	include_once('mysqliUtils.php');

	use Philo\Blade\Blade;
	$blade = new Blade(AR_VIEWS_FOLDER,AR_CACHEDVIEWS_FOLDER);

	$db = new ARDB();

	if(!empty($_GET['hash'])) {
		$worksheetObj = Worksheet::where('hash',$_GET['hash'])->getOne();
		if($worksheetObj instanceof Worksheet) {
			$aid = $worksheetObj->id;
		}  else {
			http_response_code(404);
			exit;
		}
	} else if(!checkEncrypt($_GET['id'],$_GET['ekey'],'agreement')) {
		http_response_code(404);
		exit;
	} else if(empty($_GET['id'])) {
		http_response_code(404);
		exit;
	} else {
		$aid = $_GET['id'];
	}

	$ac = new AgreementController();
	$bladeParams = $ac->getAgreementData($aid);


	$tmp = TEMP_FOLDER .'DA_' . $aid . '.pdf';
	$bladeParams['pdf'] = true;
	
	PdfBuilder::saveFromHTML($blade->view()->make('agreement.'.$bladeParams['template'],$bladeParams)->render(),$tmp);

	$pdf = PdfBuilder::getInstance();
	//include_once('pdfUtils.php');

	$pages = $pdf->setSourceFile($tmp);
	for($i = 1;$i<=$pages;$i++) {
		$pdf->addPage();
		$page = $pdf->importPage($i);
		$pdf->useTemplate($page);
	}

	if(!empty($bladeParams['appends'])) {
		foreach($bladeParams['appends'] as $page) {
			$pages = $pdf->setSourceFile(AGREEMENT_APPENDS_ROOT . $page);
			for($i = 1;$i <= $pages; $i ++) {
				$pdf->addPage();
				$page = $pdf->importPage($i);
				$pdf->useTemplate($page);
			}
		}
	}

	if(isset($_GET['copyToFolder'])) {

		$worksheetObj = Worksheet::byId($aid);
		$arf = new ARFileController();
		$tempFile = $arf->tempFile();
		$pdf->Output($tempFile,'F');

		if(!$arf->saveAgreementFile($worksheetObj->id, $worksheetObj->da_num.'.pdf',  0, $_SESSION['login']['staffID'], $tempFile, true)) {
			die($arf->getFirstError());
		}
	} else {
		$pdf->Output($worksheetObj->da_num . '.pdf','I');
	}
	$pdf->Close();

	unlink($tmp);
 ?>