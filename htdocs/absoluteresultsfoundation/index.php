<?php include_once('header.php'); ?>
<script src="//f.vimeocdn.com/js/froogaloop2.min.js"></script>
<link href='http://fonts.googleapis.com/css?family=Homemade+Apple' rel='stylesheet' type='text/css'>
<script>
	$(function() {
	    var iframe = $('#vimeoplayer')[0];
	    var player = $f(iframe);
	
	    // When the player is ready, add listeners for pause, finish, and playProgress
	    player.addEvent('ready', function() {
	    });	
	
	});
</script>
<style>
	#home {
		background-image:url('images/i2.jpg');
		background-position: center center;
		background-size: cover;
		background-repeat: no-repeat;
		width:100%;
		padding:20px 0px;
	}
	#brandLogos {
		text-align:center;
		padding:5px;
	}

	#brandLogos > div{
		display:inline-block;
		padding:5px 10px;
	}
</style>
<div id="home">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="embed-responsive embed-responsive-16by9">
					<iframe class="embed-responsive-item" id="vimeoplayer" vid="150869427" src="http://player.vimeo.com/video/150869427?api=1&player_id=vimeoplayer&autoplay=1&loop=1" width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe >
				</div>
			</div>
		</div>
	</div>
</div>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div id="brandLogos">
				<div><img src="images/hopeOfLifeLogo.png"></div>
				<div><img src="images/guatamelaProspera.png"> </div>
				<div><img src="images/transformationParaguayLogo.png"> </div>
			</div>
			<div style="background-color:#f7f7f7;padding:10px 20px 20px 20px;margin-top:10px">
				<h4 style="font-weight:bold;color:#cf1f2e">What we do</h4>
				The Absolute Results Foundation began with one Canadian company, Absolute Results, whose leaders wanted to make a measurable difference in the world helping those less fortunate. It began with a project to help build homes, wells, a preschool, and set up self sustaining businesses for orphans in Zimbabwe.
				<br><br>
				Today the Absolute Results Foundation represents multiple business, their leaders, staff, and associates. 
				<br><br>
				In addition to supporting several local charities, we have partnered with Hope of Life in Guatemala where we have built an entire village for orphans, and we are training values and leadership principles in Guatemala, Paraguay, and here in Canada. The needs, the good works, and the amazing results continue to grow….

				<br><br>
			</div>
		</div>
	</div>
</div>


<?php include_once('footer.php'); ?>