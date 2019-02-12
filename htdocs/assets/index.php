<?php
	include_once('displayUtils.php');

	$db = new ARDB();
	extract($_GET);

	if(empty($fileType) || (empty($fileHash) && (empty($fileID) || empty($ekey)))) {
		exit;
	}

	$className = ARFileController::getFileClassName($fileType);
	if(!empty($className)) {
		if(!empty($fileHash)) {
			$file = $className::where('hash',$fileHash)->getOne();
			if($file instanceof $className) {
				$file->getAsset($_GET['download']);
			}
		} else {
			$file = $className::byId($fileID);
			if($file instanceof $className) {
				if($file->checkEkey($ekey)) {
					$file->getAsset($_GET['download']);
				}
			}
		}
	} else {
		exit;
	}
	exit;
?>