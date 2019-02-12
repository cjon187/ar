<?php
	include_once('mysqliUtils.php');
	include_once('displayUtils.php');
	include_once('synergyUtils.php');
	
	$json = array();
	
	if(!checkAPIKey($_POST['key']))
	{
		$json['Error']['Message'] = 'Invalid API Key';
	}
	else if($_POST['service'] == 'getStaff')
	{
		$staff = claimAuthenticationToken($_POST['authToken']);
		if($staff['staffID'] == '') $json['Error']['Message'] = 'Invalid Authentication Token';
		else
		{			
			$json['staff']['staffID'] = $staff['staffID'];
			$json['staff']['name'] = $staff['name'];
			$json['staff']['email'] = $staff['email'];
			$json['staff']['type'] = $staff['level'];
			
			$nameArr = explode(' ',$staff['name']);			
			$json['staff']['firstname'] = array_shift($nameArr);
			$json['staff']['lastname'] = implode(' ',$nameArr);
		}
	}
	else if($_POST['service'] == 'getMenu')
	{
		$staff = displayStaffInfo($_POST['staffID']);
		if($staff['staffID'] == '') $json['Error']['Message'] = 'Invalid Staff ID';
		else
		{
			include_once('loginUtils.php');
			$menu = getStaffMenu($staff);
			foreach($menu as $s1 => $desc)
			{
				if($desc == '') continue;
				if($s1 == 'webmail') $link = 'http://mail.office365.com';
				else $link = 'https://ar.absoluteresults.com/?s1=' . $s1;
				
				$json['menu'][] = array('menu_name' => $desc,
										'menu_link' => $link);
			}
		}		
	}
	
	echo json_encode($json);
?>