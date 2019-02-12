<?php
	include_once('mysqliUtils.php');
	include_once('displayUtils.php');
	include_once('emailUtils.php');
	include_once('includes.php');


	if(isset($_POST['signature']))
	{
		$sql = 'UPDATE ps_quotes SET signature = "' . strip_tags($_POST['signature']) . '",signature_name = "' . strip_tags($_POST['signature_name']) . '",quoteSigned = "' . date("Y-m-d H:i:s") . '" WHERE quoteID=' . $_GET['id'];
		mysqli_query($db_data,$sql);

		$db = new ARDB();
		$quote = Quote::byId($_GET['id']);
		$qCon = new QuoteController($quote);
		$qCon->emailSignedNotification();


		?>
		ARAlertSuccess('<?= ajaxHTML($lang['sign_thank']) ?>');
		location.href = location.href;
		<?php
		exit;
	}
	else if(isset($_POST['remove_signature']))
	{
		$sql = 'UPDATE ps_quotes SET signature = null,signature_name = null,quoteSigned = null WHERE quoteID=' . $_GET['id'];
		mysqli_query($db_data,$sql);
		?>
		ARAlertSuccess('This agreement signature has been removed.');
		location.href = location.href;
		<?php
		exit;
	}


	echo $blade->view()->make('quote.signature',$bladeParams)->render();
?>