<?php
	include_once('displayUtils.php');
	include_once('mysqliUtils.php');

	use Philo\Blade\Blade;
	$blade = new Blade(AR_VIEWS_FOLDER,AR_CACHEDVIEWS_FOLDER);

	$db = new ARDB();

	if(empty($_GET['hashes']) && empty($_GET['aids']) && empty($_GET['id'])) {
		echo 'Invalid Agreement';
		exit;
	} else if(!empty($_GET['aids']) && !checkEncrypt($_GET['aids'],$_GET['ekey'],'agreement')) {
		echo 'Invalid Key';
		exit;
	} else if(!empty($_GET['id']) && !checkEncrypt($_GET['id'],$_GET['ekey'],'agreement')) {
		echo 'Invalid Key';
		exit;
	}


	if(isset($_POST['signature'])) {
		if(empty($_POST['agreementIDs'])) {
			echo json_encode(['success'=>false,'error' => 'Please select at least 1 agreement from the list']);
			exit;
		}

		$ac = new AgreementController();
		if($ac->signAgreements($_POST['agreementIDs'],$_POST['signature'],$_POST['signature_name'])) {
			echo json_encode(['success'=>true]);
		} else {
			echo json_encode(['success'=>false,'error' => $ac->getFirstError()]);
		}

		exit;
	}
	else if(isset($_POST['remove_signature'])) {
		$sql = 'UPDATE ps_worksheets SET signature = null,signature_name = null,agreementSigned = null WHERE worksheetID=' . $_GET['id'];
		mysqli_query($db_data,$sql);
		?>
		alert('This agreement signature has been removed.');
		location.href = location.href;
		<?php
		exit;
	}

	if(!empty($_GET['id'])) {
		$agreementIDs = [$_GET['id']];
	} else {
		$agreementIDs = explode(',',$_GET['aids']);
	}

	$firstAgreement = Worksheet::byId(current($agreementIDs));

	if($firstAgreement instanceof Worksheet) {
		$bladeParams = [];
		$bladeParams['trans'] = $firstAgreement->getTranslator();
		$bladeParams['agreements'] = [];
		$agreements = Worksheet::where('worksheetID',$agreementIDs,'IN')->get();
		if(count($agreements)) {
			foreach($agreements as $agreement) {
				if(empty($agreement->agreementSigned)) {
					$bladeParams['signatureRequired'] = true;
				}

				$bladeParams['agreements'][$agreement->id] = $agreement;
			}
		}
		echo $blade->view()->make('agreement.signature',$bladeParams)->render();
	} else {
		echo 'Invalid Agreement';
		exit;
	}

?>