<style>
	.rsvpTbl th ,.rsvpTbl td {font-size:10pt;text-align:left;padding:3px 20px 3px 0px}
	.rsvpTbl td input {width:300px}
	.rsvpTbl td textarea {width:300px;height:55px;font-family:arial}
</style>
<script>
	$(function () {
	});
</script>
<div style="color:red;font-weight:bold"><?= $_SESSION['rsvpwebsiteStatus'] ?></div>
<?php
	unset($_SESSION['rsvpwebsiteStatus']);

	$campaigns = getCampaigns(true);

	foreach($sharedArray as $rsvpWebsite)
	{
?>
	<div style="padding:5px 0px;font-size:12pt"><a href="http://<?= $rsvpWebsite['url'] ?><?= ($rsvpWebsite['code'] != "" ? '?dealer=' . $rsvpWebsite['code'] : '') ?>" target="_blank"><?= $rsvpWebsite['url'] ?></a></div>
	<form method="POST">
		<input type="hidden" name="rsvpWebsiteID" value="<?= $rsvpWebsite['rsvpWebsiteID'] ?>">
		<table cellspacing="0" cellpadding="0" style="padding:10px;background-color:#ccc;border:1px solid #999">
			<tr>
				<td>
					<table cellspacing="0" cellpadding="0" class="rsvpTbl">
						<tr>
							<th>RSVP WEBSITE ID</th>
							<td><?= $rsvpWebsite['rsvpWebsiteID'] ?></td>
						</tr>
						<tr>
							<th>URL</th>
							<td><input type="text" name="url" value="<?= $rsvpWebsite['url'] ?>"></td>
						</tr>
						<tr>
							<th>Campaign</th>
							<td>
								<select class="customPageSelectClass"  id="campaign_<?= $rsvpWebsite['rsvpWebsiteID'] ?>" name="campaign" customPage="<?= $rsvpWebsite['customPage'] ?>" style="width: 100%">
								<!-- Function is in rsvpWebsiteUtils.php -->
									<option value='' SELECTED></option>
									<?php
									if(count($campaigns['new']) > 0){
										foreach($campaigns['new'] as $c){
											?>
												<option value="<?= trim($c) ?>" <?= ( strtolower(trim($c)) == strtolower($rsvpWebsite['campaign']) ? 'SELECTED' : '' ) ?> ><?= ucfirst(trim($c))?></option>
											<?php
										}
									}
									?>


								<?php
									//echo getCampaignsHTML($rsvpWebsite['campaign']);
								?>
								</select>
								<!-- Function is in rsvpWebsiteUtils.php -->

							</td>
						</tr>
						<tr>
							<th>Description</th>
							<td><input type="text" name="campaignDescription" value="<?= $rsvpWebsite['campaignDescription'] ?>"></td>
						</tr>
						<tr>
							<th>Code</th>
							<td><input type="text" name="code" value="<?= $rsvpWebsite['code'] ?>"></td>
						</tr>
						<tr>
							<th>Custom Page</th>
							<td><input type="text" name="customPage" value="<?= $rsvpWebsite['customPage'] ?>"></td>
						</tr>
						<tr>
							<th>Language</th>
							<td>
								<select name="languageID">
									<option value="<?= ENGLISH_CA ?>" <?= ($rsvpWebsite['languageID'] == ENGLISH_CA ? 'SELECTED' : '') ?>>English</option>
									<option value="<?= FRENCH_CA ?>" <?= ($rsvpWebsite['languageID'] == FRENCH_CA ? 'SELECTED' : '') ?>>French</option>
									<option value="<?= GERMAN_DE ?>" <?= ($rsvpWebsite['languageID'] == GERMAN_DE ? 'SELECTED' : '') ?>>German</option>
									<option value="<?= DUTCH_NL ?>" <?= ($rsvpWebsite['languageID'] == DUTCH_NL ? 'SELECTED' : '') ?>>Dutch</option>
								</select>
							</td>
						</tr>
						<tr>
							<th>Online?</th>
							<td>
								<select name="online">
									<option value="no" <?= ($rsvpWebsite['online'] == "no" ? 'SELECTED' : '') ?>>no</option>
									<option value="yes" <?= ($rsvpWebsite['online'] != "no" ? 'SELECTED' : '') ?>>yes</option>
								</select>
							</td>
						</tr>
						<tr>
							<th>This page asks for a code?</th>
							<td>
								<select name="hasCode">
									<option value="no" <?= ($rsvpWebsite['hasCode'] != "yes" ? 'SELECTED' : '') ?>>no</option>
									<option value="yes" <?= ($rsvpWebsite['hasCode'] == "yes" ? 'SELECTED' : '') ?>>yes</option>
								</select>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="2" style="padding-top:10px">
					<center>
						<input type="submit" value="<?= ($rsvpWebsite['rsvpWebsiteID'] == '' ? 'Add' : 'Edit') ?>" style="font-size:18pt">
				<?php if($rsvpWebsite['rsvpWebsiteID'] != "") { ?>
						<input type="button" value="Delete" style="font-size:18pt" onClick="if(confirm('Are you sure you want to delete this page?')) location.href='?s1=webSchedule&s2=Shared&delete=<?= $rsvpWebsite['rsvpWebsiteID'] ?>'">
				<?php } ?>
					</center>
				</td>
			</tr>
		</table>
	</form>
	<br>
<?php
	}
?>