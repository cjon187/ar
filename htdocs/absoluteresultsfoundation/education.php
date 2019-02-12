<?php
	exit;
	include_once('displayUtils.php');
	$ar = new ARDB();
	$vimeo = new Vimeo();
	$vimeoResults = $vimeo->getAllVideos()->data;

	$videos = [];
	if(!empty($vimeoResults)) {
		foreach ($vimeoResults as $v) {
			$vid = basename($v->uri);
			if(in_array($vid,array(161523933,161523955,161523980,161523981))) {
				$videos[$vid]['thumbnail'] = $v->pictures->sizes[2]->link;
				$videos[$vid]['name'] = $v->name;			
			}
		}
	}

	if(!empty($_GET['v'])) {
		$showVid = $_GET['v'];
	} else {
		$showVid = 161523955;
	}
?>
<?php include_once('header.php'); ?>
<script src="//f.vimeocdn.com/js/froogaloop2.min.js"></script>
<script>
	$(function() {
	    var iframe = $('#vimeoplayer')[0];
	    var player = $f(iframe);
	
	    // When the player is ready, add listeners for pause, finish, and playProgress
	    player.addEvent('ready', function() {
	        player.addEvent('finish', onFinish);
	    });	
	
	    function onFinish(id) {
	        vid = $('#'+id).attr('vid');
	        /*if(vid == 115841355) vid = '150869427';
	       	else if(vid == 150869427) vid = '115844502';
	        else vid = '115841355';*/
	        
	        $('#'+id).attr('vid',vid);
	        $('#'+id).attr('src','http://player.vimeo.com/video/' + vid + '?api=1&player_id=vimeoplayer&autoplay=1');
	    }

	    $('[vimeoID]').click(function() {
	    	location.href='?v=' + $(this).attr('vimeoID');
	    });
	});
</script>
<style>
	#education {
		background-image:url('images/i2.jpg');
		background-position: center center;
		background-size: cover;
		background-repeat: no-repeat;
		width:100%;
		padding:20px 0px;
	}
	#brandLogos {
		text-align:center;
	}

	#brandLogos > div{
		display:inline-block;
		padding:5px 10px;
	}
	.vid {
		text-align:center;
		margin:10px 0px;
	}
	.vidThumbnail {
		text-align:center;
	}
	.vidTitle {
		font-size:0.8em;
		line-height:1em;
		font-weight:bold;
	}
	[vimeoID] {
		cursor:pointer;
	}
	[vimeoID]:hover .vidTitle {
		color:blue;
	}
</style>
<link href='http://fonts.googleapis.com/css?family=Homemade+Apple' rel='stylesheet' type='text/css'>

<div id="education">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="embed-responsive embed-responsive-16by9">
					<iframe class="embed-responsive-item" id="vimeoplayer" vid="<?= $showVid ?>" src="http://player.vimeo.com/video/<?= $showVid ?>?api=1&player_id=vimeoplayer&autoplay=1" width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe >
				</div>
			</div>
		</div>
	</div>
</div>
<div class="container">
	<div class="row">
		<div class="col-md-7">
			<div style="background-color:#f7f7f7;padding:10px 20px 20px 20px;margin-top:10px">
				<h4 style="font-weight:bold;color:#cf1f2e">About John Maxwell Leadership Foundation</h4>
				<div>
				The John Maxwell Leadership Foundation was started by Dr Maxwell to facilitate a social transformation process. The Absolute Results Foundation has joined donors from around the world who believe that the process led by Dr Maxwell will indeed transform entire nations and elevate their social, justice and economic performance.
				<br><br>
				The Maxwell Transformation program serves all sectors of a society. It is a grass root movement that helps small groups examine together the values that make communities grow and change for the better. The programs are designed specifically for each nation served. The program is entirely funded by The John Maxwell Leadership Foundation. Dr Maxwell receives no financial benefit for his work for the foundation.
				<br><br>
				</div>
				<br><br>
			</div>
		</div>
		<div class="col-md-5">		
			<div class="row">
				<?php
					$i = 0;
					foreach($videos as $vid => $info) {
						if($i % 2 == 0 && $i != 0) {
							echo '</div><div class="row">';
						}
				?>
				<div class="col-md-6">
					<div class="vid" vimeoID =<?= $vid ?>>
						<div class="vidThumbnail">
							<center>
								<img src="<?= $videos[$vid]['thumbnail'] ?>" class="img-responsive">
							</center>
						</div>
						<div class="vidTitle">
							<?= $videos[$vid]['name'] ?>
						</div>
					</div>
				</div>
				<?php
						$i++;
					}
				?>	
			</div>	
			<br><br>

			<div id="brandLogos">
				<div><img src="images/guatamelaProspera.png" class="img-responsive"> </div>
				<div><img src="images/transformationParaguayLogo.png" class="img-responsive"> </div>
			</div>
		</div>
	</div>
</div>


<?php include_once('footer.php'); ?>