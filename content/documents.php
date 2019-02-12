<?php
	if(isset($_GET['autoLogin']))
	{
		?>
		<script>
			$(function() {
				$('#autoLoginForm').submit();
			});
			</script>
		<form action="<?= $_GET['url'] ?>/login.php" method="POST" id="autoLoginForm">
			<input type="hidden" name="username" value="<?= $_GET['user'] ?>">
			<input type="hidden" name="password" value="<?= $_GET['pass'] ?>">
			<input type="hidden" name="fromHref" value="true">
		</form>
		<?php
		exit;
	}
?>

<style>
	.row table, .row tr, .row td,.row  th {padding:5px;}
	.row td,.row th {font-size:0.9em}
	.row thead {background-color:#d71921}
</style>
<font style="font-weight:bold;font-size:13pt">AR Documents</font>
<br><br>
<?php
	foreach($documents as $sectionID => $docs) {
?>
	<table cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<th colspan="2" nowrap style="width:100%;text-align:center"><?= DocumentsController::$sections[$sectionID] ?></th>
			</tr>
		</thead>
		<tbody>
		<?php
			foreach($docs as $doc){
		?>
				<tr>
					<td style="width:85%;"><?= $doc['file_name'] ?></td>
					<td style="text-align:center">
						<a href="<?= $doc['url'] ?>" target="_blank"><?= (!empty($doc['button']) ? $doc['button'] : 'Download') ?></a>
					</td>
				</tr>
		<?php
			}
		?>
		</tbody>
	</table>
<?php
	}
?>
<!-- <table cellspacing="0" cellpadding="0">
	<thead>
	<tr>
		<th colspan="2" nowrap style="width:100%;text-align:center">Photo Gallery</th>
	</tr>
	</thead>
	<tr>
		<td style="width:85%;">Convention/Dinner 2015-2016</td>
		<td style="text-align:center"><a href="?s1=pictures&gallery=gala2016" target="_blank">view</td>
	</tr>
	<tr>
		<td style="width:85%;">Christmas Gala 2014-2015</td>
		<td style="text-align:center"><a href="?s1=pictures&gallery=gala2015" target="_blank">view</td>
	</tr>
	<tr>
		<td style="width:85%;">T3 January 2015</td>
		<td style="text-align:center"><a href="?s1=pictures&gallery=t3jan2015" target="_blank">view</td>
	</tr>
	<tr>
		<td style="width:85%;">T3 January 2014</td>
		<td style="text-align:center"><a href="?s1=pictures&gallery=t3jan2014" target="_blank">view</td>
	</tr>
	<tr>
		<td style="width:85%;">Staff Meeting January 2014</td>
		<td style="text-align:center"><a href="?s1=pictures&gallery=staffmeetingjan2014" target="_blank">view</td>
	</tr>
	<tr>
		<td style="width:85%;">John Maxwell 2014 Photo Shoot</td>
		<td style="text-align:center"><a href="?s1=pictures&gallery=johnmaxwell2014photoshoot" target="_blank">view</td>
	</tr>
	<tr>
		<td style="width:85%;">Christmas Party 2013</td>
		<td style="text-align:center"><a href="?s1=pictures&gallery=christmas2013" target="_blank">view</td>
	</tr>
	<tr>
		<td style="width:85%;">Christmas Party 2013 Photo Shoot</td>
		<td style="text-align:center"><a href="?s1=pictures&gallery=christmas2013photoshoot" target="_blank">view</td>
	</tr>
	<tr>
		<td style="width:85%;">Christmas Party 2012</td>
		<td style="text-align:center"><a href="?s1=pictures&gallery=christmas2012" target="_blank">view</td>
	</tr>
	<tr>
		<td style="width:85%;">Village Of Dreams - Guatemala</td>
		<td style="text-align:center"><a href="upload/guatemala" target="_blank">view</td>
	</tr>
</table> -->
<?php
if(in_array($_SESSION['login']['level'],array('master','management')) || $_SESSION['login']['section']['admin'])
{
?>
<a href="?s1=oemPortals">Go to OEM Portals</a>

<?php
}
?>