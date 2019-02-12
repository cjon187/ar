<?php
	include_once('includes.php');

	checkLogin();
	

	$videos = array();
	//$videos[151427960] = 'FCA Australia 2015';
	$videos[151837059] = 'GM Greatest Auto Sale – Oct. 2015';
	$videos[129940719] = 'BMW Invitational Event - May 2015';
	//$videos[150968505] = 'Audi Canada Challenge 2012';
	$videos[146060863] = 'FCA Germany September 2015';
	$videos[142035649] = 'Vente Privée France et Belgique';

	include_once('header.php');
?>

<style>
	 
</style>

<script>	
	var currentVideo;
	$(function() {
		viewVideo(<?= array_shift(array_keys($videos)) ?>,false);
		$('[vidID]').click(function(){
			viewVideo($(this).attr('vidID'),true);
		})
	})
	function viewVideo(id,autoplay){
		src = 'https://player.vimeo.com/video/' + id + '?title=0&byline=0&portrait=0';
		if(autoplay) src += '&autoplay=1';
		$('#videoFrame').attr('src',src);

		if(currentVideo != '')
			$('[vidID=' + currentVideo + ']').removeClass('selected');
		currentVideo = id;
		$('[vidID=' + currentVideo + ']').addClass('selected');
	}
	
</script>	

<div class="row" style="margin-bottom:10px">
	<div class="col-sm-12">
		<div class="pull-right">
			<button class="btn btn-primary btn-sm" onClick="location.href='login.php'">Log out</button>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-2">
		<div class="section">
			<div class="title">2016 Global</div>
			<div class="row">
				<div class="sectionButtons">
					<div class="col-sm-12">
						<!-- <button class="btn btn-primary" onClick="window.open('assets/Chevy Launch Deck_113015_V1.pdf')">Launch Deck</button> -->
						<button class="btn btn-primary" onClick="window.open('assets/AbsoluteResultsGlobalLaunchDeck2016 v2.pdf')">Launch Deck</button>
					</div>
					<!-- <div class="col-xs-6">
						<button class="btn btn-primary" onClick="window.open('assets/.pdf')">Price Sheet</button>
					</div> -->
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-7">
		<div class="section">
			<div class="title">Chevrolet</div>
			<div class="row">
				<div class="sectionButtons">
					<div class="col-sm-4">
						<button class="btn btn-primary" onClick="window.open('assets/Private Sale Strategy-V2.pdf')">Strategy Planning</button>
					</div>
					<div class="col-sm-4">
						<button class="btn btn-primary" onClick="window.open('assets/Chevrolet_Sell_Sheet_rev.pdf')">Price Sheet</button>
					</div>
					<div class="col-sm-4">
						<button class="btn btn-primary" onClick="window.open('assets/Chevrolet NE States Private Sale Analysis v3.pdf')">Private Sale Analysis</button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-sm-3">
		<div class="section">
			<div class="title">OEM Portal - Chevrolet</div>
			<div class="row">
				<div class="sectionButtons">
					<div class="col-sm-10">
						<button class="btn btn-primary" onClick="window.open('https://ar.absoluteresults.com/oem/chevrolet/?user=chev&pass=arprivatesale')">View Portal</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="section">
			<div class="title">Success Stories</div>
			<div class="row">
				<div class="col-sm-8">
					<div class="embed-responsive embed-responsive-16by9">
						<iframe id="videoFrame" class="embed-responsive-item" width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
					</div>
				</div>
				<div class="col-sm-4">
					<table class="table" id="videosTable">
						<tbody>
		<?php
			foreach($videos as $id => $vid) {
		?>
							<tr>
								<td vidID=<?= $id ?>><?= $vid ?></td>								
							</tr>
		<?php
			}
		?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<?php include_once('footer.php'); ?>	