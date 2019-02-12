<?php
	include_once('includes.php');

	checkLogin();


	$videos = array();
	$videos[151427960] = 'FCA Australia 2015';
	$videos[129940719] = 'BMW Invitational Event - May 2015';
	$videos[150968505] = 'Audi Canada Challenge 2012';
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
	<div class="col-sm-4">
		<div class="section">
			<div class="title">2016 Global</div>
			<div class="row">
				<div class="sectionButtons">
					<div class="col-sm-6">
						<button class="btn btn-primary" onClick="window.open('assets/AbsoluteResultsGlobalLaunchDeck2016.pdf')">Launch Deck</button>
					</div>
					<!-- <div class="col-xs-6">
						<button class="btn btn-primary" onClick="window.open('assets/.pdf')">Price Sheet</button>
					</div> -->
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-4">
		<div class="section">
			<div class="title">FCA Australia</div>
			<div class="row">
				<div class="sectionButtons">
					<div class="col-sm-6">
						<button class="btn btn-primary" onClick="window.open('assets/AbsoluteResultsServiceProposal.pdf')">Service Proposal</button>
					</div>
					<div class="col-sm-6">
						<button class="btn btn-primary" onClick="window.open('assets/AbsoluteResultsPriceSheet.pdf')">Price Sheet</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-4">
		<div class="section">
			<div class="title">Sample Portal - FCA France</div>
			<div class="row">
				<div class="sectionButtons">
					<div class="col-sm-6">
						<button class="btn btn-primary" onClick="window.open('https://ar.absoluteresults.com/oem/fcafrance/?user=fca&pass=france')">View Sample Portal</button>
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