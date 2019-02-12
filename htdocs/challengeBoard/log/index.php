<?php
	require_once('classes/ARSession.class.php');
	$session = new ARSession('extChallengeBoards');

	include_once('displayUtils.php');
	include_once('mysqliUtils.php');
	use Carbon\Carbon;

	use Philo\Blade\Blade;
	$blade = new Blade(AR_VIEWS_FOLDER,AR_CACHEDVIEWS_FOLDER);

	$db = new ARDB();

	function isLoggedIn() {
		return !empty($_SESSION['dealerID']);
	}

	function clearSession() {
		unset($_SESSION['dealerID']);
	}


	if(isset($_GET['logout'])) {
		clearSession();
		header('location: index.php');
		exit;
	}

	if(isset($_POST['username'])) {
		clearSession();
		$dealer = Dealer::where('username',$_POST['username'])->where('password',$_POST['password'])->getList('dealerID');
		if(count($dealer) == 1) {
			$dealerID = array_shift($dealer);
			$_SESSION['dealerID'] = $dealerID;
			header('location: index.php');
			exit;
		} else {
			$error = 'Invalid Login';
		}
	}

	if(!empty($_SESSION['dealerID'])) {

		$dealer = Dealer::byId($_SESSION['dealerID']);

		if(isset($_POST['logDate'])) {
			$_SESSION['logDate'] = date("Y-m-d",strtotime($_POST['logDate']));
		}

		if(!empty($_SESSION['logDate'])) {
			$date = $_SESSION['logDate'];
		} else {
			$date = date("Y-m-d");
			if($date <= "2016-07-01") $date = date('Y-07-01');
		}

		$cdate = Carbon::parse($date);

		if(isset($_POST['addFirstName'])) {
			$dups = ExternalSalesrep::where('dealerID',$dealer->id)->where('firstName',$_POST['addFirstName'])->where('lastName',$_POST['addLastName'])->get();

			if(!empty($dups)) {
				echo json_encode(['success'=>false,'message'=>'This salesperson appears to be already in the system']);
				exit;
			}

			$rep = new ExternalSalesrep();
			$rep->firstName = $_POST['addFirstName'];
			$rep->lastName = $_POST['addLastName'];
			$rep->dealerID = $dealer->id;
			$rep->status = 1;

			if($rep->save()) {
				echo json_encode(['success'=>true]);
			} else if(is_array($rep->errors)) {
				echo json_encode(['success'=>false,'message'=> array_shift(array_shift($rep->errors))]);
			} else {
				echo json_encode(['success'=>false,'message'=> 'Error saving salesperson.']);
			}

			exit;
		}

		if(isset($_POST['updateSalesreps'])) {
			$reps = [];
			$stats = [];
			foreach($_POST as $k => $v) {
				$keyArr = explode('_',$k);
				switch($keyArr[0]) {
					case('firstName'):
					case('lastName'):
						$reps[$keyArr[1]][$keyArr[0]] = $v;
						break;
					case('stats'):
						$stats[$keyArr[2]][$keyArr[1]] = $v;
						break;
				}
			}

			$errors = [];
			$db->startTransaction();
			if(!empty($reps)) {
				foreach($reps as $id => $r) {
					$rep = ExternalSalesrep::byId($id);
					$rep->firstName = $r['firstName'];
					$rep->lastName = $r['lastName'];
					if(!$rep->save()) {
						$db->rollback();
						if(is_array($rep->errors)) {
							echo json_encode(['success'=>false,'message'=> array_shift(array_shift($rep->errors))]);
						} else {
							echo json_encode(['success'=>false,'message'=> 'Error saving salesperson.']);
						}
						exit;
					} else {
						if(!empty($stats[$rep->id])) {


							foreach($stats[$rep->id] as $statTypeID => $value) {
								$match = ExternalSalesrepStat::where('salesrepID',$rep->id)->where('statTypeID',$statTypeID)->where('date',$date)->get();
								if(is_array($match) && count($match) > 0) {
									$stat = array_shift($match);
								} else {
									$stat = new ExternalSalesrepStat();
								}

								if($value != '') {
									$stat->salesrepID = $rep->id;
									$stat->statTypeID = $statTypeID;
									$stat->date = $date;
									$stat->value = intval($value);
									if(!$stat->save()) {
										$db->rollback();
										if(is_array($stat->errors)) {
											echo json_encode(['success'=>false,'message'=> array_shift(array_shift($stat->errors))]);
										} else {
											echo json_encode(['success'=>false,'message'=> 'Error saving stat.']);
										}
										exit;
									}
								} else if($value == '' && !empty($stat->id)) {
									$stat->delete();
								}
							}
						}
					}
				}
			}

			$db->commit();
			echo json_encode(['success'=>true]);
			exit;
		}

		$salesreps = ExternalSalesrep::where('dealerID',$dealer->id)->orderBy('firstName','ASC')->get();
		$salesrepIDs = ExternalSalesrep::where('dealerID',$dealer->id)->getList('salesrepID');


		$stats = [];
		if(!empty($salesrepIDs)) {
			$results = ExternalSalesrepStat::where('salesrepID',array_values($salesrepIDs),'IN')->where('date',$date)->get();

			if(!empty($results)) {
				foreach($results as $re) {
					$stats[$re->salesrepID][$re->statTypeID] = $re->value;
				}
			}
		}

		$bladeParams = [];
		$bladeParams['dealer'] = $dealer;
		$bladeParams['cdate'] = $cdate;
		$bladeParams['salesreps'] = $salesreps;
		$bladeParams['stats'] = $stats;

	 	echo $blade->view()->make('challengeBoard.log.salesreps',$bladeParams)->render();
	 } else {
		clearSession();
 		echo $blade->view()->make('challengeBoard.log.login',['error' => $error])->render();
	 }
?>