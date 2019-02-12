<?php
	include_once('displayUtils.php');
	$ar = new ARDB();
	$vimeo = new Vimeo();
	$vimeoResults = $vimeo->getAllVideos()->data;

	$videos = [];
	if(!empty($vimeoResults)) {
		foreach ($vimeoResults as $v) {
			$vid = basename($v->uri);
			if(in_array($vid,array(68924600,83669695,105938897,117226638,161517704,161516960,161516961,161515423))) {
				$videos[$vid]['thumbnail'] = $v->pictures->sizes[2]->link;
				$videos[$vid]['name'] = $v->name;			
			}
		}
	}

	if(!empty($_GET['v'])) {
		$showVid = $_GET['v'];
	} else {
		$showVid = 161517704;
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
<link href='http://fonts.googleapis.com/css?family=Homemade+Apple' rel='stylesheet' type='text/css'>
<style>
	#humanitarianaid {
		background-image:url('images/i2.jpg');
		background-position: center center;
		background-size: cover;
		background-repeat: no-repeat;
		width:100%;
		padding:20px 0px;
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
		text-align:center;
		margin-top:5px;
	}
	[vimeoID] {
		cursor:pointer;
	}
	[vimeoID]:hover .vidTitle {
		color:blue;
	}
</style>

<div id="humanitarianaid">
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
		<?php
			$i = 0;
			foreach($videos as $vid => $info) {
				if($i % 4 == 0 && $i != 0) {
					echo '</div><div class="row">';
				}
		?>
		<div class="col-md-3">
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
	<div class="row">
		<div class="col-md-6">
			<div style="background-color:#f7f7f7;padding:10px 20px 20px 20px;margin-top:10px">
				<h4 style="font-weight:bold;color:#cf1f2e">Project Africa</h4>
				<div style="font-weight:bold">Absolute Outreach: Project Africa</div>
				<a href="http://www.absoluteoutreach.com/projectafrica/" target="_blank">Zimbabwe Orphan Care Website</a>
				<br>
				<a href="downloads/Esiphezini Pre-School Project.pdf" target="_blank">The Esiphezini Pre-School Project</a>
				<br><br>
			</div>
		</div>
		<div class="col-md-6">
			<div style="background-color:#f7f7f7;padding:10px 20px 20px 20px;margin-top:10px">
				<h4 style="font-weight:bold;color:#cf1f2e">Guatemala</h4>
				<div style="font-weight:bold">Hope of Life</div>
				<a href="downloads/Village of Dreams - February 2014.pdf" target="_blank">Hope of Life Brochure</a>
				<br><br>
			</div>
		</div>
	</div>
</div>


<?php include_once('footer.php'); ?>