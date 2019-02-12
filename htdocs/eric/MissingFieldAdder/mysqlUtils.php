<?php

	$link = mysql_connect('legacymotivation.netfirmsmysql.com', 'u70814874', '561ea0')
	    or die('Could not connect: ' . mysqli_error($db_data));
	mysql_select_db('d60744927') or die('Could not select database');
	
	mysqli_query($db_data,'set @@SESSION.max_allowed_packet=64M;');

	function tableExists($table)
	{
		$res = mysqli_query($db_data,"show table status like '$table'")
		or die(mysqli_error($db_data));
		return mysqli_num_rows($res) == 1;
	}	
	
?>