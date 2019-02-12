<?php

	include_once('defines.php');
	include_once('mysqliUtils.php');
	include_once('displayUtils.php');

	use Philo\Blade\Blade;

	$page = rtrim($_GET['page']);
	if($page) {
		$pageArray = explode('/',$page);

		$bidEvent = '';
		$view = '';
		switch (strtolower($pageArray[0])) {
			case('bid'):
				$bidEvent = 'BidSep2018';
				$view = 'bid_sep_2018';
				break;
			case('niagara'):
				$bidEvent = 'NiagaraSep2018';
				$view = 'niagara_sep_2018';
				break;
		}

		if($bidEvent) {
			define('HOL_URL',AR_SECURE_URL . 'hol/');
			define('HOL_EVENT_URL',HOL_URL . strtolower($pageArray[0]) . '/');
			$blade = new Blade(AR_VIEWS_FOLDER,AR_CACHEDVIEWS_FOLDER);

			switch (strtolower($pageArray[1])) {
				case('get_bids_html'):
					include_once("models/hopeOfLife/{$bidEvent}Model.php");
					$bidsArray = [];
					$giftsTotal = 0;
					$bids = $bidEvent::where('isValid',1)->orderBy('bid','DESC')->get();
					if(!empty($bids)) {
						foreach($bids as $bid) {
							if($bid->item == 'gift') {
								$giftsTotal += $bid->bid;
							} else {
								$bidsArray[$bid->item][] = $bid;
							}
						}
					}

					$htmls = [];
					$trans = new ARTranslator();
					$htmls['giftsTotal'] = $trans->currency($giftsTotal);
					foreach($bidEvent::$items as $item => $desc) {
						$bladeParams = [];
						if($bidsArray[$item]) {
							$bladeParams = [
								'bids' => $bidsArray[$item],
								'trans' => $trans
							];
						} 

						$htmls[$item] = $blade->view()->make('hopeOfLife.partials.bids',$bladeParams)->render();
					}

					echo json_encode($htmls);

					exit;

			}

			$bladeParams = [];
			echo $blade->view()->make("hopeOfLife.{$view}",$bladeParams)->render();
			exit;
		} else {
			http_response_code(500);
		}
	}
?>