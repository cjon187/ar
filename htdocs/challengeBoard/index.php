<?php

use Carbon\Carbon;
use Philo\Blade\Blade;

require_once('classes/ARSession.class.php');
$session = new ARSession('challengeBoards');

include_once('displayUtils.php');
include_once('defines.php');

$db = new ARDB();

$cbc = new ChallengeBoardController();

if(isset($_POST['toggleNewUsed'])){
	if($_POST['toggleNewUsed'] == 1){
		$_SESSION['newUsed'] = 0;
	}
	else{
		$_SESSION['newUsed'] = 1;
	}
	echo json_encode(['success'=>true]);
	exit;
}

if(!isset($_SESSION['newUsed']) && $_SESSION['newUsed'] !== 0){
	$_SESSION['newUsed'] = 1;
}


if(isset($_GET['logout'])) {
	session_unset();
	header("Location: " . CHALLENGE_BOARD_INDEX .(isset($_GET['name']) ? '?name='.$_GET['name'] : '') );
	exit;
}

if(isset($_GET['locale'])){
	$_SESSION['locale'] = $_GET['locale'];
	unset($_GET['locale']);
}

$cbc->setChallengeTranslator($_SESSION['locale']);

if(isset($_POST['setChallengeID'])){
	$cbObj = ChallengeBoard::ById($_POST['setChallengeID']);
	if($cbObj instanceof ChallengeBoard){
		header('Location: '. CHALLENGE_BOARD_INDEX.'?name='.$cbObj->challengeName);
		exit;
	}
	header('Location: '. CHALLENGE_BOARD_INDEX);
	exit;
}

if(isset($_POST["loginForm"])){
	$clean = ARFV::cleanArray($_POST);
	$cbAuthentication = new ChallengeBoardAuthentication();
	$attemptResult = $cbAuthentication->login($clean['text1'], $clean['text2'], $clean['pageName']);
	if($attemptResult){
		$url = CHALLENGE_BOARD_INDEX.'?'. ($clean['pageName'] != "" ? 'name='.$clean['pageName'] : '');
		echo json_encode(['success'=>true, 'url'=>$url]);
		exit;
	}
	else{
		echo json_encode(['success'=>false]);
		exit;
	}
}

if(isset($_GET['resetpassword'])){
	//check to see if their hash credentials passed
	$email = urldecode($_GET['email']);
	$login = ChallengeBoardLogin::where('username', $email)->where('temporaryHash',$_GET['resetpassword'])->getOne();
	if($login instanceof ChallengeBoardLogin){
		$cbLoginObj = new ChallengeBoardAuthentication();
		$cbLoginObj->loginChallengeBoardUser($login);
	}
	$cbc->buildChangePasswordPage(['email'=>urldecode($_GET['email'])]);
	exit;
}

if(isset($_POST['resetPasswordEmail'])){
	$clean = ARFV::cleanArray($_POST);
	$cblObj = ChallengeBoardLogin::where('username',$clean['email'])->getOne();
	if($cblObj instanceof ChallengeBoardLogin){
		$cbc->sendEmail(null, 'resetPassword', ['loginID'=>$cblObj->loginID] ) ;
		echo json_encode(['success', true]);
		exit;
	}

	$dpuc = new DealerPortalController();
	$dpu = DealerPortalUser::where('email',$clean['email'])->getOne();
	if($dpu instanceof DealerPortalUser){
		$dpuc->emailPasswordReset($dpu, 'en');
		echo json_encode(['success', true]);
		exit;
	}

	http_response_code(400);
	echo json_encode(['error'=>1]);
	exit;
}


if(isset($_GET['quickLogin'])){
	$cbHash = ChallengeBoardLoginHash::where('hash',$_GET['hash'])->getOne();
	if($cbHash instanceof ChallengeBoardLoginHash) {

		$cbAuthentication = new ChallengeBoardAuthentication();

		if($cbHash->loginType == ChallengeBoardLoginHash::LOGIN_TYPE_STAFF){
			$user = Staff::ById($cbHash->userID);
			$attemptResult = $cbAuthentication->loginStaff($user);
		}
		else if($cbHash->loginType == ChallengeBoardLoginHash::LOGIN_TYPE_DEALERPORTAL){
			$user = DealerPortalUser::ById($cbHash->userID);
			$attemptResult = $cbAuthentication->loginDealerPortalUser($user, $_GET['challengeID']);
		}

		$cbHash->delete();

		$cbObj = ChallengeBoard::ById($_GET['challengeID']);
		if($cbObj instanceof ChallengeBoard){
			$_SESSION['locale'] = $cbObj->language->minor;
		}

		if($attemptResult == "success"){
			header('Location: '. CHALLENGE_BOARD_INDEX.'?name='.$_GET['name']);
			exit;
		}
	}
}


$challengeName = $_GET['name'];
$cbObj = $challengeBoard = ChallengeBoard::where('challengeName', $challengeName)->getOne();
include_once('classes/ChallengeBoardAuthentication.class.php');
$cbLoginObj = new ChallengeBoardAuthentication();

if($cbObj instanceof ChallengeBoard){
	if(!$cbLoginObj->isLoggedIn($cbObj->challengeID) && $cbObj->hasLogin){
		$_SESSION['challengeNameRedirect'] = $cbObj->challengeName;
		if(!isset($_SESSION['locale'])){
			$_SESSION['locale'] = $cbObj->language->minor;
		}
		$cbc->setChallengeTranslator($_SESSION['locale']);
		$cbc->buildChallengeLogin($cbObj->challengeID);
		exit;
	}
}
else if(!$cbLoginObj->isLoggedIn() && ($cbObj->hasLogin || !isset($_GET['name']) ) ) {
	if(!isset($_SESSION['locale'])){
		$_SESSION['locale'] = 'en_US';
	}
	$cbc->setChallengeTranslator($_SESSION['locale']);
	$cbc->buildChallengeLogin();
}

if(isset($_GET['selectChallengeBoard'])){
	if(!empty($_SESSION['challengeBoardAllowedBoards']) && count($_SESSION['challengeBoardAllowedBoards']) == 1){
		$cbObj = ChallengeBoard::ById($_SESSION['challengeBoardAllowedBoards'][0]);
		header("Location: " . CHALLENGE_BOARD_INDEX .'?name='.$cbObj->challengeName );
		//$cbc->buildChallengeBoardPage($cbObj);
		exit;
	}
	else if(!empty($_SESSION['challengeBoardAllowedBoards'])){
		$cbc->buildChangeChallengeBoardPage(['allowedBoards'=>$_SESSION['challengeBoardAllowedBoards']]);
		exit;
	}
	echo 'No Boards associated with this user found';
	exit;
}


//IF YOU ARE LOGGED IN AND TRY SUBMIT YOUR NEW PASSWORD
if(isset($_POST['changePassword'])){
	$challengeBoardLogin = ChallengeBoardLogin::ById($_SESSION['challengeBoardUserLoginID']);
	if($challengeBoardLogin instanceof ChallengeBoardLogin){
		if($challengeBoardLogin->setPassword($_POST['password'])){
			$redirectURL = CHALLENGE_BOARD_INDEX.'?selectChallengeBoard';
			echo json_encode(['success'=>true,'url'=>$redirectURL]);
		}
		else {
			http_response_code(400);
			echo json_encode(['error'=>'Failed to set the password']);
		}
		exit;
	}
}

if(isset($_GET['name'])){
	if(!$cbObj instanceof ChallengeBoard || $cbObj->status != ChallengeBoard::STATUS_LIVE){
		$cbc->buildNoChallengeBoardFoundPage();
		exit;
	}

	$cbc->buildChallengeBoardPage($cbObj, $_GET, $_SESSION);
	exit;
}
else if(!empty($_SESSION['challengeBoardAllowedBoards'])){
	if(!empty($_SESSION['challengeBoardAllowedBoards']) && count($_SESSION['challengeBoardAllowedBoards']) == 1){
		$cbObj = ChallengeBoard::ById($_SESSION['challengeBoardAllowedBoards'][0]);
		header("Location: " . CHALLENGE_BOARD_INDEX .'?name='.$cbObj->challengeName );
		exit;
	}
	else if(!empty($_SESSION['challengeBoardAllowedBoards'])){
		$cbc->buildChangeChallengeBoardPage(['allowedBoards'=>$_SESSION['challengeBoardAllowedBoards']]);
		exit;
	}
	exit;
}
else{
	$cbc->buildNoChallengeBoardFoundPage();
	exit;
}

exit;
?>