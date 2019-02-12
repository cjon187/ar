<?php
	require_once('classes/ARSession.class.php');
	$session = new ARSession('absoluteresultsfoundation');

	include_once('defines.php');
	include_once('displayUtils.php');
	define('GUATEMALA_URL',AR_SECURE_URL . 'absoluteresultsfoundation/guatemala/');
	define('GUATEMALA_PATH',AR_ROOT . 'htdocs/absoluteresultsfoundation/guatemala/');

	use Philo\Blade\Blade;

	$currentPage = $_GET['currentPage'];
	if(empty($currentPage)) {
		$currentPage = 'index';
	}

	if(!$_SESSION['guatemala']['login'] && $currentPage != 'login') {
		header('location: ' . GUATEMALA_URL . 'login');
		exit;
	}

	if(isset($_GET['logout'])) {
		unset($_SESSION['guatemala']['login']);
		unset($_SESSION['guatemala']['community']);
		unset($_SESSION['guatemala']['hero']);

		header('location: ' . GUATEMALA_URL . 'login');
		exit;
	}



	$bladeParams = [];

	switch($currentPage) {
		case('login'):
			if(isset($_POST['username'])) {
				unset($_SESSION['guatemala']['login']);
				unset($_SESSION['guatemala']['community']);
				unset($_SESSION['guatemala']['hero']);

				if(strtolower($_POST['username']) == 'guatemala' && strtolower($_POST['password']) == 'transformation') {
					$_SESSION['guatemala']['login'] = true;
					$res = ['success' => true];
				} else {
					$res = ['success' => false,'error' => 'Invalid Login'];
				}

				echo json_encode($res);
				exit;
			}
			break;

		case('index'):
			$bladeParams['communities'] = GuatemalaCommunity::orderBy('communityName','ASC')->get();
			$bladeParams['heroes'] = GuatemalaHero::orderBy('heroName','ASC')->get();
			break;

		case('community'):
			if(is_numeric($_GET['param'])) {
				$community = GuatemalaCommunity::byId($_GET['param']);
			} else if($_GET['param'] == 'add') {
				$community = new GuatemalaCommunity();
			}


			if(isset($_POST['communityName'])) {
				$error = '';
				if($_POST['communityName'] == '') $error = 'Please tell us the community name';
				else if($_POST['lat'] == '') $error = 'Please update the location';
				else if($_POST['lng'] == '') $error = 'Please update the location';

				if($error != '') {
					echo json_encode(['success' => false, 'error' => $error]);
					exit;
				}

				foreach($_POST as $key => $val) {
					$community->$key = $val;
				}

				if($community->save()) {
					echo json_encode(['success' => true,'communityID' => $community->id]);
				} else {
					echo json_encode(['success' => false, 'error' => $community->getLastError()]);
				}

				exit;
			}

			if(isset($_POST['deleteCommunity'])) {
				if(empty($community->id)) {
					echo json_encode(['success' => false, 'error' => 'Invalid community']);
					exit;
				}
				$heroes = $community->heroes;
				if(!empty($heroes)) {
					echo json_encode(['success' => false, 'error' => 'There are heros in this community.<br>Please remove all heros before deleting this community.']);
					exit;
				}

				if($community->delete()) {
					echo json_encode(['success' => true]);
				} else {
					echo json_encode(['success' => false, 'error' => $community->getLastError()]);
				}

				exit;
			}


			if($community instanceof GuatemalaCommunity) {
				$bladeParams['community'] = $community;
			} else {
				$currentPage = '404';
			}

			break;

		case('hero'):
			if(is_numeric($_GET['param'])) {
				$hero = GuatemalaHero::byId($_GET['param']);
			} else if($_GET['param'] == 'add') {
				$hero = new GuatemalaHero();
			}

			if(isset($_POST['heroName'])) {
				$error = '';
				if($_POST['heroName'] == '') {
					$error = 'Please tell us the hero name';
				}

				if(!empty($error)) {
					echo json_encode(['success' => false, 'error' => $error]);
					exit;
				}

				foreach($_POST as $key => $val) {
					$hero->$key = $val;
				}

				if($hero->save()) {
					echo json_encode(['success' => true]);
				} else {
					echo json_encode(['success' => false, 'error' => $hero->getLastError()]);
				}
				exit;
			}

			if(isset($_POST['deleteHero'])) {
				if(empty($hero->id)) {
					echo json_encode(['success' => false, 'error' => 'Invalid hero']);
					exit;
				}
				if($hero->delete()) {
					echo json_encode(['success' => true]);
				} else {
					echo json_encode(['success' => false, 'error' => $hero->getLastError()]);
				}

				exit;
			}

			if(isset($_POST['rotatePhoto'])) {
				$media = GuatemalaHeroMedia::byId($_POST['rotatePhoto']);

				if(!$media instanceof GuatemalaHeroMedia) {
					echo json_encode(['success' => false,'error' => 'Invalid Media']);
					exit;
				}
				$server = AR_ROOT. 'htdocs/absoluteresultsfoundation/guatemala/uploads/';
				$filePath = $server . $media->fileName;
				$thumbnailFilePath = $server . $media->fileName_thumbnail;
				exec('convert -rotate 90 "' . $filePath . '" "' . $filePath . '"');
				exec('convert -rotate 90 "' . $thumbnailFilePath . '" "' . $thumbnailFilePath . '"');


				echo json_encode(['success' => true]);
				exit;
			}

			if(isset($_POST['deleteMedia'])) {
				$media = GuatemalaHeroMedia::byId($_POST['deleteMedia']);

				if(!$media instanceof GuatemalaHeroMedia) {
					echo json_encode(['success' => false,'error' => 'Invalid Media']);
					exit;
				}

				$media->status = 0;
				if(!$media->save()) {
					echo json_encode(['success' => false,'error' => $media->getFirstError()]);
					exit;
				}

				echo json_encode(['success' => true]);
				exit;
			}

			if(isset($_GET['uploadHandler'])) {

				if (empty($_FILES) || $_FILES['file']['error']) {
					die('{"OK": 0, "info": "Failed to move uploaded file."}');
				}

				$ext = strtolower(pathinfo($_REQUEST['name'],PATHINFO_EXTENSION));

				if(in_array($ext,['jpg','jpeg','png'])) {
					$fileType = GuatemalaHeroMedia::TYPE_IMAGE;
				} else if(in_array($ext,['mp4'])) {
					$fileType = GuatemalaHeroMedia::TYPE_VIDEO;
				} else {
					die('{"OK": 0, "info": "Invalid file extension \"' . $ext . '\""}');
				}

				$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
				$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;

				$tempFilePath = GUATEMALA_PATH . 'uploads/tmp/' . basename($_REQUEST['name']);


				if (empty($tempFilePath)) {
					die('{"OK": 0, "info": "Temp File Path is empty"}');
				}
				// Open temp file
				$out = @fopen("{$tempFilePath}.part", $chunk == 0 ? "wb" : "ab");
				if ($out) {
				  // Read binary input stream and append it to temp file
				  $in = @fopen($_FILES['file']['tmp_name'], "rb");

				  if ($in) {
				    while ($buff = fread($in, 4096))
				      fwrite($out, $buff);
				  } else {
				    die('{"OK": 0, "info": "Failed to open input stream."}');
				  }

				  @fclose($in);
				  @fclose($out);

				  @unlink($_FILES['file']['tmp_name']);
				} else {
					die('{"OK": 0, "info": "Failed to open output stream."}');
				}

				if (!$chunks || $chunk == $chunks - 1) {
					$uuid = uniqid('',true);
					$filePath = GUATEMALA_PATH . 'uploads/' . $uuid;
					copy("{$tempFilePath}.part","{$filePath}");

					$media = new GuatemalaHeroMedia();
					$media->heroID = $hero->id;
					$media->mediaName = $_REQUEST['name'];
					$media->fileName = $uuid;
					$media->extension = $ext;
					$media->type = $fileType;
					$media->status = 1;
					if($fileType == GuatemalaHeroMedia::TYPE_IMAGE) {
						$thumbnailFilePath = $filePath . '_thumb';
						$media->fileName_thumbnail = $uuid . '_thumb';
						rename("{$tempFilePath}.part","{$thumbnailFilePath}");
						exec('convert "' . $thumbnailFilePath . '" -resize 100x "' . $thumbnailFilePath . '"');
					}
					if(!$media->save()) {

						die('{"OK": 0, "info": "' . $media->getLastError() . '"}');
					}
				}

				die('{"OK": 1}');

				exit;
			}

			if($hero instanceof GuatemalaHero) {
				$bladeParams['hero'] = $hero;

				if(!empty($hero->id)) {
					$bladeParams['media'] = GuatemalaHeroMedia::where('heroID',$hero->id)->where('status',1)->get();
				}
			} else {
				$currentPage = '404';
			}

			break;
	}

	$blade = new Blade(AR_VIEWS_FOLDER,AR_CACHEDVIEWS_FOLDER);
	$bladeFile = 'guatemala.' . $currentPage;
	if(!$blade->view()->exists($bladeFile)) {
		$bladeFile = 'guatemala.404';
	}

	echo $blade->view()->make($bladeFile, $bladeParams)->render();
	exit;