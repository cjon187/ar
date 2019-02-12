<?php
	include_once('mysqliUtils.php');
	include_once('rsvpWebsiteUtils.php');
	
	header("Content-Type: image/jpeg");
	
	$websiteInfo = getWebsiteInfo('','',$_GET['tid']);
	$frext = '';	
	if($websiteInfo['lang'] == 'fr') 
	{
		include_once('fr.php');
		$frext = '-fr';
	}
	else include_once('en.php');
	
	if($websiteInfo['lang'] == 'fr') $voucher = $websiteInfo['voucher-fr'];
	else  $voucher = $websiteInfo['voucher'];
	
	$couponName = '../../rsvp/' . $websiteInfo['campaign'] .'/images/email/' . $voucher .  '.jpg';
	
	$img = imagecreatefromjpeg($couponName);
	
	if(stripos($voucher,'IMAGEONLY_') === false)
	{
	
		$fontfile = "fonts/audi.ttf";	
		$fontfile = "fonts/MyriadPro-BoldSemiCn.otf";
		
		// Client's name
		$x = 42;
		$y = 360;
		
		
		$textcolor = imagecolorallocate($img, 0, 0, 0);	
		imagettftext ( $img, 14, 0, $x, $y, $textcolor,$fontfile, trim($_GET['fn'] . ' ' . $_GET['ln']));	
		
		
		if($websiteInfo['hideExpiry'] != "yes")
		{
			// Valid until -> Event Date
			$x = 555;
			$y = 365;
			
			$fontfile = "fonts/MyriadPro-BoldSemiCn.otf";
			if($myLang=='fr') $edate = trim($websiteInfo['eventDate-fr']);
			else $edate = trim($websiteInfo['eventDate']);
			$date = $lang['validon'] . strip_tags($edate) . ', ' . date("Y");
			
			$textcolor = imagecolorallocate($img, 255,255,33 );
			imagettftext ( $img, 16, 0, $x, $y, $textcolor,$fontfile, $date);	
		}
		
		if($websiteInfo['loyaltyBonus'] != "" || $websiteInfo['consumerCash'] != "")
		{
			// Voucher Value
			$x = 50;
			$y = 120;
			
			$fontfile = "MyriadPro-BoldSemiCn.otf";
			$textcolor = imagecolorallocate($img, 255,255,255 );
			imagettftext ( $img, 12, 0, $x, $y, $textcolor,$fontfile, $lang['upto']);		
			
			if($websiteInfo['loyaltyBonus'] != "" || $websiteInfo['consumerCash'] != "")
			{			
				if($websiteInfo['loyaltyBonus'] != "")
				{
					$textcolor = imagecolorallocate($img, 255,255,33 );
					$y += 45;
					imagettftext ( $img, 40, 0, $x, $y, $textcolor,$fontfile, $websiteInfo['loyaltyBonus']);		
					$y += 30;
					if($websiteInfo['bonus1txt'] == ''){
						imagettftext ( $img, 20, 0, $x, $y, $textcolor,$fontfile, $lang['loyaltyBonus']);
					} else {
						if($myLang=='fr'){
							imagettftext ( $img, 20, 0, $x, $y, $textcolor,$fontfile, $websiteInfo['bonus1txt-fr']);
						} else {
							imagettftext ( $img, 20, 0, $x, $y, $textcolor,$fontfile, $websiteInfo['bonus1txt']);
						}
					}
				}
				
				if($websiteInfo['loyaltyBonus'] != "" && $websiteInfo['consumerCash'] != "")
				{
					$textcolor = imagecolorallocate($img, 255,255,255 );
					$y += 25;
					imagettftext ( $img, 12, 0, $x, $y, $textcolor,$fontfile, 'plus up to');
				}
				
				if($websiteInfo['consumerCash'] != "")
				{
					$textcolor = imagecolorallocate($img, 255,255,33 );	
					$y += 45;
					imagettftext ( $img, 40, 0, $x, $y, $textcolor,$fontfile, $websiteInfo['consumerCash']);			
					$y += 30;
					if($websiteInfo['bonus2txt'] == ''){
						imagettftext ( $img, 20, 0, $x, $y, $textcolor,$fontfile, $lang['concash']);
					} else {
						if($myLang=='fr'){
							imagettftext ( $img, 20, 0, $x, $y, $textcolor,$fontfile, $websiteInfo['bonus2txt-fr']);
						} else {
							imagettftext ( $img, 20, 0, $x, $y, $textcolor,$fontfile, $websiteInfo['bonus2txt']);
						}
					}
				}
				$textcolor = imagecolorallocate($img, 255,255,255 );
				$y += 25;
				imagettftext ( $img, 14, 0, $x, $y, $textcolor,$fontfile, $lang['withpurchase']);
			}		
		}
		
		if($websiteInfo['pullAheadCode'] != "")
		{
			
			$x = 40;
			$y = 200;
			
			$fontfile = "fonts/MyriadPro-BoldSemiCn.otf";
			
			$textcolor = imagecolorallocate($img, 255,255,0 );
			imagettftext ( $img, 25, 0, $x, $y, $textcolor,$fontfile, strtoupper(trim($_GET['dCode'])));	
		}
		
		if($websiteInfo['voucher'] == "ChryslerDealerRewards")
		{
			// Valid until -> Event Date
			$x = 485;
			$y = 245;
			
			$fontfile = "fonts/MyriadPro-BoldSemiCn.otf";
			if($_SESSION['registerEmail']['info']['customer']['dr_availablePurchase'] > 500) $rewards = '$1,000';
			else $rewards = '$500';
			
			$textcolor = imagecolorallocate($img, 255,255,0 );
			imagettftext ( $img, 50, 0, $x, $y, $textcolor,$fontfile, $rewards);	
		}
	
		if($_GET['bonus_amount'] != '')
		{
			$x = 375;
			$y = 365;
			
			$fontfile = "fonts/MyriadPro-BoldSemiCn.otf";
			$textcolor = imagecolorallocate($img, 255,255,0 );
			imagettftext ( $img, 18, 0, $x, $y, $textcolor,$fontfile, 'BONUS: $' . $_GET['bonus_amount']);	
		}
		// Dealership's address
		$x = 440;
		$y = 35;
		
		$fontfile = "fonts/arial.ttf";
		$fontfile = "fonts/MyriadPro-BoldSemiCn.otf";
		$textcolor = imagecolorallocate($img, 255, 255, 255);
		imagettftext ( $img, 13, 0, $x, $y, $textcolor,$fontfile, $websiteInfo['name']);	
		$split = explode('<br>',$websiteInfo['address'].'<br>'.$websiteInfo['phone']);
		$i = 1;
		
		$fontfile = "fonts/arial.ttf";	
		$fontfile = "fonts/MyriadPro-BoldSemiCn.otf";
		
		foreach($split as $line)
		{
			imagettftext ( $img, 11, 0, $x, $y + ($i*15), $textcolor,$fontfile, $line);	
			$i++;
		}
	}
	
	imagejpeg($img);
	imagedestroy($img);
?>
