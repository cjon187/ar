<?php
	include_once('includes.php');
	include_once('defines.php');

	checkLogin();
	

	$videos = array();
	//$videos[151427960] = 'FCA Australia 2015';
	//$videos[151837059] = 'GM Greatest Auto Sale – Oct. 2015';
	//$videos[129940719] = 'BMW Invitational Event - May 2015';
	//$videos[150968505] = 'Audi Canada Challenge 2012';
	$videos[146060863] = 'FCA Deutschland September 2015';   //FCA Germany September 2015
	//$videos[142035649] = 'Vente Privée France et Belgique';

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
		});

		$('#showModal').on('click', function(){
			$("#sendEmailButton").text('Abschicken');
		});
		$('#showModalA').on('click', function(){
			$("#sendEmailButton").text('Abschicken');
		});
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

	

	function sendEmail(){
		/*$.ajax({
				url:  'sendEmail.php',
				data:	{sendEmail: '', data: $('#emailForm').serialize(), 'g-recaptcha-response': grecaptcha.getResponse()},
				type:	'POST',
				dataType: 'json',
				success: function(data){
					console.log(data);
					if(data.captcha == "success"){
						$("#sendEmailButton").text('E-Mail gesendet');
					}
					else{
						grecaptcha.reset($('.g-recaptcha', form).attr('id'));
					}
				}
		   	   });	*/
		$.ajax({
				url:  'sendEmail.php',
				data:	{sendEmail: '', data: $('#emailForm').serialize()},
				type:	'POST',
				dataType: 'json',
				success: function(data){
					$("#sendEmailButton").text('E-Mail gesendet');
				}
		   	   });	

	}

</script>	

<div class="row" style="margin-bottom:10px">
	<div class="col-sm-12">
		<div class="pull-right">
			<button class="btn btn-default btn-sm" onClick="location.href='login.php'">Log out</button>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-4">
		<div class="section">
			<div class="title">2016 Weltweit</div>  <!-- 2016 Global -->
			<div class="row">
				<div class="sectionButtons">
					<div class="col-sm-12">
						<button class="btn btn-default" onClick="window.open('assets/Global Launch Deck 2016_German-web.pdf')">Informationsmaterial</button>  <!-- Launch Deck -->
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
			<?php if(strtolower($_SESSION['login']['username']) == "fcagermany"){ ?>
				<div class="title">FCA Deutschland</div>  <!-- FCA Germany -->
			<?php } else { ?>
				<div class="title">Deutschland</div>  <!-- Germany -->
			<?php } ?>
			<div class="row">
				<div class="sectionButtons">
					<div class="col-sm-12">
						<button class="btn btn-default" onClick="window.open('assets/Einfuehrung in exklusive Verkaufs-Events.pdf')">Schritte zum Verkaufsevent</button> <!-- Private Sale Steps -->
					</div>
<!-- 					<div class="col-sm-4">
	<button class="btn btn-primary" onClick="window.open('assets/Chevrolet_Sell_Sheet_rev.pdf')">Price Sheet</button>
</div>
<div class="col-sm-4">
	<button class="btn btn-primary" onClick="window.open('assets/Chevrolet NE States Private Sale Analysis v3.pdf')">Private Sale Analysis</button>
</div> -->
				</div>
			</div>
		</div>
	</div>

	<div class="col-sm-4">
		<div class="section">
			<div class="title">Eventbuchung</div> <!-- Book an Event -->
			<div class="row">
				<div class="sectionButtons">
					<div class="col-sm-12">
						<button class="btn btn-primary" id="showModal" data-toggle="modal" data-target="#offersModal">Kontaktieren Sie uns</button>  <!-- Create Email -->
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="section">
			<div class="title">Erfolgsgeschichten</div>
			<div class="row">
				<div class="col-sm-12">
					<div class="embed-responsive embed-responsive-16by9">
						<iframe id="videoFrame" class="embed-responsive-item" width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
					</div>
				</div>
				<!-- <div class="col-sm-4">
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
				</div> -->
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="offersModal" tabindex="-1" role="dialog" aria-labelledby="OffersModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title" id="myModalLabel">Kontaktieren Sie uns</h4>
          	</div>
          	<div class="modal-body">
          		<form id="emailForm">
	            	<div class="row">
	            		<div class="col-md-6">
	            			<div class="form-group">
	            				<label>Kontakt Person</label>  <!-- Contact Name -->
	            				<div class="input-group">
	            					<input type="text" class="form-control" id="contactName" name="contactName" value="">
	            				</div>
	            			</div>
	            		</div>
	            		<div class="col-md-6">
	            			<div class="form-group small-margin">
	            				<label for="contactPhone">Telefonnummer</label>  <!-- Contact Phone -->
	            				<div class="input-group">
	            					<input type="text" class="form-control" id="contactPhone" name="contactPhone" value="">
	            				</div>
	            			</div>
	            		</div>
	            	</div>
	            	<div class="row">
	            		<div class="col-md-6">
	            			<div class="form-group">
	            				<label>Händler Name</label>  <!-- Dealer Name -->
	            				<div class="input-group">
	            					<input type="text" class="form-control" id="dealerName" name="dealerName" value="">
	            				</div>
	            			</div>
	            		</div>
	            		<div class="col-md-6">
	            			<div class="form-group small-margin">
	            				<label>Bevorzugter Event Monat</label> <!-- Preferred Event Month -->
	            				<div class="input-group">
	            					<!-- <input type="text" class="form-control" id="eventMonth" name="eventMonth" value=""> -->
	            					<select style="padding-top: 1px;" id="eventMonth" class="form-control" name="eventMonth">
										<option value="Januar">Januar</option>
										<option value="Februar">Februar</option>
										<option value="March">März</option>
										<option value="April">April</option>
										<option value="Mai">Mai</option>
										<option value="Juni">Juni</option>
										<option value="Juli">Juli</option>
										<option value="August">August</option>
										<option value="September">September</option>
										<option value="Oktober">Oktober</option>
										<option value="November">November</option>
										<option value="Dezember">Dezember</option>
									</select>
	            				</div>
	            			</div>
	            		</div>
	            	</div>
    	          	<div class="row">
    	          		<div class="col-md-6">
    	        			<div class="form-group">
    	        				<label>Neufahrzeuge verkauft pro Monat</label>  <!-- Dealer Name -->
    	        				<div class="input-group">
    	        					<input type="text" class="form-control" id="newVehicles" name="newVehicles" value="">
    	        				</div>
    	        			</div>
    	        		</div>
    	        		<div class="col-md-6">
    	        			<div class="form-group">
    	        				<label>Gebrauchtfahrzeuge verkauft pro Monat</label>  <!-- Dealer Name -->
    	        				<div class="input-group">
    	        					<input type="text" class="form-control" id="useVehicles" name="useVehicles" value="">
    	        				</div>
    	        			</div>
    	        		</div>
    	          	</div>

    	          	<div class="row">
    					<div class="col-md-6">
    	        			<div class="form-group">
    	        				<label>Marken</label>  <!-- Dealer Name -->
    	        				<div class="input-group">
    	        					<input type="text" class="form-control" id="brands" name="brands" value="">
    	        				</div>
    	        			</div>
    	        		</div>
    	        	</div>
	          	</div>

	          
	          	<!-- <div class="row">
	          		<div class="col-md-6" style="margin-left: 15px;">
	          			<div class="g-recaptcha" name="g-recaptcha" data-sitekey="6LeL0RYTAAAAACzSlZjhN6Ex60MUrRSfOKjvZt_N"></div>
	          			<div class="g-recaptcha" name="g-recaptcha" data-sitekey="6Lcy1RYTAAAAALZ29AmpqgaRPMohKzQJGGoDQ7Os"></div>
	          	
	          		</div>
	          	</div> -->

	          	<div class="modal-footer">
	          		<button type="button" class="btn btn-primary" id="sendEmailButton" onClick="sendEmail()">Kontaktieren Sie uns</button>
	            	<!-- <button type="button" class="btn btn-default" id="modalClose" data-dismiss="modal">Beenden</button> -->
	          	</div>
	        </form>
        </div>
    </div>
</div>


<?php include_once('footer.php'); ?>	