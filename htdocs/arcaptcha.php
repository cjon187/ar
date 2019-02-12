<?php
include_once('defines.php');
$db = new ARDB();
header('Access-Control-Allow-Origin: *');
include_once('classes/ArCaptcha.class.php');
$OneClickCaptchaServiceFactory = new \OneClickCaptcha\Service\OneClickCaptchaServiceFactory();
$oneClickCaptcha = $OneClickCaptchaServiceFactory->getOneClickCaptcha();

$request = isset($_GET['get_captcha']) ? $_GET['get_captcha'] : '';
if ($request === 'true') {
	$captchaID = 1;
	if(is_numeric($_GET['captchaID'])){
		$captchaID = $_GET['captchaID'];
	}
	$arCap = new ArCaptcha($captchaID);
	if($_GET['blank'] === 'true'){
		$arCap->buildConfig(ArCaptcha::CAPTCHA_EMPTY);
		$arCap->showCaptcha();
	}
	else {
		if(!empty($_GET['backgroundHeight'])){
			$arCap->setBackgroundHeight($_GET['backgroundHeight']);
		}
		if(!empty($_GET['backgroundWidth'])){
			$arCap->setBackgroundWidth($_GET['backgroundWidth']);
		}
		if(!empty($_GET['backgroundColor'])){
			$arCap->setBackgroundColor($_GET['backgroundColor']);
		}
		if(!empty($_GET['circleAmount'])){
			$arCap->setCircleAmount($_GET['circleAmount']);
		}
		if(!empty($_GET['circleColor'])){
			$arCap->setCircleColor($_GET['circleColor']);
		}
		if(!empty($_GET['circleSize'])){
			$arCap->setCircleSize($_GET['circleSize']);
		}
		$arCap->buildConfig(ArCaptcha::CAPTCHA_DEFAULT);
		$arCap->showCaptcha();
	}
}

if(isset($_POST['validate'])){
	if(is_numeric($_POST['captchaID'])){
		$arCaptcha = new ArCaptcha($_POST['captchaID']);
		$result = $arCaptcha->validate($_POST['posX'], $_POST['posY']);

		if($result !== false){
			$captcha = ARCaptchaKey::ById($_POST['captchaID']);
			if($captcha instanceof ARCaptchaKey){
				$key = $captcha->generateKey();
				if($key !== false){
					echo json_encode(['success'=>true, 'validateCode' => $key]);
					exit;
				}
			}
		}
	}
	echo json_encode(['success'=>false]);
	exit;
}
?>