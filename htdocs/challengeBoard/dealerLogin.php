<?php

include_once('defines.php');
include_once('mysqliUtils.php');
include_once('classes/ChallengeBoardAuthentication.class.php');
include_once('formvalidator/ARFormValidator.php');
$challengeName = $_GET['name'];
require_once('classes/ARSession.class.php');
$session = new ARSession('challengeBoards');
$db = new ARDB();


$loginFailed = false;
if(isset($_POST['text1'])){
	$POST = ARFV::cleanArray($_POST);
	$cbAuthentication = new ChallengeBoardAuthentication();
	$attemptResult = $cbAuthentication->login($POST['text1'], $POST['text2'], $POST['pageName']);
	if($attemptResult == "success"){
		header('Location: '. AR_SECURE_URL.'challengeBoard/index.php?name='.$POST['pageName']);
		exit;
	}

	$loginFailed = true;
}
if($challengeName != ""){
	$challengeBoardObj = ChallengeBoard::where('challengeName', $challengeName)->getOne();

	if(count($challengeBoardObj->challengeBackground) > 0 && $challengeBoardObj->challengeBackground[0] instanceof ChallengeBackground){
		$backgroundImage = $challengeBoardObj->challengeBackground[0]->url;
	}
	else{
		$backgroundImage = AR_SECURE_URL.'challengeBoard/images/backgrounds-upload/0.jpg';
	}

	$bannerImage = "";
	if(count($challengeBoardObj->challengeHeader) > 0 && $challengeBoardObj->challengeHeader[0] instanceof challengeHeader){
		$bannerImage = $challengeBoardObj->challengeHeader[0]->url;
	}
}


?>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link rel="stylesheet" href="<?= AR_SECURE_URL ?>bootstrap/css/bootstrap-theme.min.css">
<link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>

<style>
	body {
	    background: url(<?= $backgroundImage ?>) no-repeat center center fixed;
	    -webkit-background-size: cover; /* For WebKit*/
	    -moz-background-size: cover;    /* Mozilla*/
	    -o-background-size: cover;      /* Opera*/
	    background-size: cover;         /* Generic*/
	}
	.container{
		margin: 0 auto;
		max-width: 900px;
	}
	.errorDiv{
		color: red;
		font-size: 1.2em;
		font-weight:bold;
		margin-bottom: 25px;
		font-family: Montserrat;
	}
	.login-block {
	    max-width: 450px;
	    min-width: 240px;
	    padding: 30px;
	    background: #fff;
	    border-radius: 10px;
	    border-top: 5px solid #D71E3E;
	    margin: 0 auto;
	    background-color: rgba(0,0,0,0.7);
	}

	.login-block input {
	    width: 100%;
	    height: 42px;
	    box-sizing: border-box;
	    border-radius: 5px;
	    border: 1px solid #ccc;
	    margin-bottom: 20px;
	    font-size: 13px;
	    font-family: Montserrat;
	    padding: 0 20px 0 50px;
	    outline: none;
	}

	.login-block input#text1 {
	    background: #fff url('https://eric.ardevel.net/challengeBoard/images/login-user.png') 20px top no-repeat;
	    background-size: 16px 80px;
	}

	.login-block input#text1:focus {
	    background: #fff url('https://eric.ardevel.net/challengeBoard/images/login-user.png') 20px bottom no-repeat;
	    background-size: 16px 80px;
	}

	.login-block input#text2 {
	    background: #fff url('https://eric.ardevel.net/challengeBoard/images/login-lock.png') 20px top no-repeat;
	    background-size: 16px 80px;
	}

	.login-block input#text2:focus {
	    background: #fff url('https://eric.ardevel.net/challengeBoard/images/login-lock.png') 20px bottom no-repeat;
	    background-size: 16px 80px;
	}

	.login-block input:active, .login-block input:focus {
	    border: 1px solid #D71E3E;
	}

	.login-block button {
	    width: 100%;
	    height: 40px;
	    background: #D71E3E;
	    box-sizing: border-box;
	    border-radius: 5px;
	    border: none;
	    color: #fff;
	    font-weight: bold;
	    text-transform: uppercase;
	    font-size: 14px;
	    font-family: Montserrat;
	    outline: none;
	    cursor: pointer;
	}

	.login-block button:hover {
	    background: #ff7b81;
	}
	.more-info{
		color: #D71E3E;
		margin-top: 10px;
	}

</style>

<div class="container">
	<?php
		$temp = strtoupper($challengeName) .'<br> SALES CHALLENGE';
		if($challengeBoardObj instanceof ChallengeBoard && $challengeBoardObj->language->major == "fr"){
			$temp = 'DÉFI DES VENTES<br>'.strtoupper($challengeName);
		}
		else if($challengeBoardObj instanceof ChallengeBoard && $challengeBoardObj->languageID == 65){
			$temp = '<font style="font-size: 45pt;"> '. strtoupper($challengeName) .'<br> CHALLENGE </font>';
		}

		if($bannerImage != ""){
			?><img src="<?= $bannerImage ?>" style="width: 100%; margin-top: 20px;"><?php
		}
		else{
			?><div style="font-size:55pt;color:white;font-family:MyFont,arial;width:800px; margin-bottom: 0px;"> <?= $temp ?></div><?php
		}
	?>
	<div style="text-align:center; margin-top: 30px;">

		<form action="" id="loginForm" method="POST">
			<input type="hidden" name="pageName" value="<?= $_GET['name'] ?>">
			<?php
			if($loginFailed){ ?>
				<div class="row">
					<div class="col-sm-12 errordiv">
						<?= $attemptResult ?>
					</div>
				</div>
			<?php
			}
			?>

			<div class="row">
				<div class="logo"></div>
				<div class="login-block">
				    <input type="text" value="" placeholder="Username" id="text1" name = "text1" />
				    <input type="password" value="" placeholder="Password" id="text2" name="text2" />
				    <button>Login</button>

				    <!-- <div class="more-info">
				    	Need help with your login?
				    </div> -->
				</div>
			</div>
		</form>
	</div>
</div>