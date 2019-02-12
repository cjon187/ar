<!--<iframe style="width:1400px;height:1000px" frameborder="0" src="patrick/utils/good/pendingurls.php"></iframe>-->

<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>


<script>
$(document).ready(function(){
	/*$(window).bind('beforeunload', function(){
		$.ajax({data:	{unsetPageSession: 'webSchedule'},
				type:	'POST',
				dataType: 'script'
   	    });
	});*/

	$('.popup').click(function(event) {
	    window.open($(this).attr("href"), "popupWindow", "width=500,height=360,scrollbars=no,resizable=no");
	    return false;
	});

	$( "#datepicker" ).datepicker({	inline: true});

	$("#datepicker1").datepicker({
	        numberOfMonths: 1,
		dateFormat: "yy-mm-dd",
        	onSelect: function(selected) {
          		$("#datepicker2").datepicker("option","minDate", selected)
		}
	});

	$("#datepicker2").datepicker({
		numberOfMonths: 1,
		dateFormat: "yy-mm-dd",
		onSelect: function(selected) {
			$("#datepicker1").datepicker("option","maxDate", selected)
    	}
	});

	updateArtTotal();
	updateView();

});
	function updateArtTotal(){
		var viewType = $('#viewTypeSelect').val();
		$.ajax({data:	{updateEventArtCount: '', viewTypeUpdate: viewType},
				type:	'POST',
				dataType: 'script'
   	    });
	}

	function confirmNo(eventID){
		$.ajax({data:	{confirmNo: '', eventID: eventID},
				type:	'POST',
				dataType: 'script'
   	    });
	}

	function viewEvent(eventID,uslink)
	{
		var win = window.open('?s1=' + uslink + 'calendar&s2=Event&id=' + eventID,'calendarEvent','width=600,height=750,toolbar=0,resizable=1,scrollbars=1');
		win.focus();
		return false;
	}

	function updateView(){
		$('.viewTypeClass').parent().show();
		var viewType = $('#viewTypeSelect').val();
		console.log(viewType);

		$.ajax({data:	{viewType: viewType},
				type:	'POST',
				dataType: 'script'
   	    });

		if(viewType != ""){
	   	    $('.viewTypeClass').each(function(){
	   	    	if( !$(this).children('span').hasClass(viewType) ){
	   	    		$(this).parent().hide();
	   	    	}
	   	    });
	   	}

		/*if(viewType == 'all'){}
		else if(viewType == 'todo'){
			$('.viewTypeClass').each(function(){
				if($(this).html() == "All Live")
					console.log("all were live");
				else
					console.log("not live yet");
			});
		}
		else if(viewType == 'allLive'){
			console.log("hiding all but live")
		}
		else if(viewType == 'noConfirmed'){
			console.log("hidng all but no confirmed");
		}*/

	}
</script>



<style>
	#wrap {
	   font-size: 130%;
	   width: 1260px;
	   min-height: 120px;
	   padding: 20px 50px;
	   margin: 0 auto; }
	#footer {
	   font-size: 130%;
	   width: 960px;
	   padding: 20px 50px;
	   margin: 0 auto;
	}

	input[type=text]{
	    width: 88px;
	    color: #979797;
	    padding: 6px;
	    text-align: center;}
	#errorbox {
	    border: 1pt solid red;
	    float:right; margin-top:10px;
	    width: 180px;
	    display: none;}
	.hide {display: none}
	.subt {background: #f4f4f4;padding: 4px 10px;}
	.clear { clear: both;}
	dt {font-weight: bold;}
	/* Table Styles */
	#wrap table {
	   width: 100%;
	   border: 1px solid #cef;
	   margin: 1em 0 auto;
	   text-align: left; }
	#wrap th {
	   font-weight: bold;
	   background-color: #acf;
	   border-bottom: 1px solid #cef; }
	#wrap td,#wrap th {
	   padding: 4px 5px; }


	.left{
		float: left;
		margin-right: 20px;
	}
	.webTable{
		font-size: 1.0em;
		color: black;
	}
	.webTable td{
		padding: 2px 0px 2px 5px;
		font-size: 0.85em;
		line-height: 1.25em;
	}
		table tr td:first-child {
			font-weight: normal;
		}
	.webTable th{
		text-align: left;
		padding: 5px 0px 5px 5px;
		font-size: 0.85em;
	}
	.extraPage{
		text-decoration: underline;
		font-weight: bold;
		margin-right: 10px;

	}

</style>


<div class="wrapper">
	<div class="row">
		<div class="three columns" style="text-align: left;">
			<form method="post" action="#" name="myForm" id="myForm" >
			<div class="clearDiv">
				<div class="left">
					<div>Start Date:</div>
					<div><input type="text" id="datepicker1" name="datepicker1" value="<?= $_SESSION['webSchedule']['date1'] ?>" /></div>
				</div>
				<div class="left">
					<div>End Date:</div>
					<div><input type="text" id="datepicker2" name="datepicker2" value="<?= $_SESSION['webSchedule']['date2'] ?>" /></div>
				</div>
				<div class="left">
					<div>&nbsp;</div>
					<div><input type="submit" name="submit" value="Search"></div>
				</div>
				</form>
			</div>
		</div>

		<div class="four columns" style="text-align: left;">
			<div class="clearDiv">
				<div style="display:inline-block;">
					<div>Area Select:</div>
					<div style="display:inline-block;">
						<form method="POST" style="display:inline-block;">
							<select id = "filterSelect" name="filterSelect" onChange='this.form.submit()'>
								<?php
								foreach($filtersList as $key => $f){
									if($key == "0") $key = 0;
									echo '<option value="'. $key .'" '. ($key == $_SESSION['webSchedule']['filterSelect'] ? 'SELECTED' : '' ) .'>'. $f .'</option>';
								}
								?>
							</select>
						</form>
					</div>
				</div>
				<div style="display:inline-block;">
					<div>View Type:</div>
					<div>
						<form method="POST" style="display:inline-block;">
							<select id = "viewTypeSelect" name="viewTypeSelect" onChange='updateView()'>
								<option value="" 			<?= ($_SESSION['webSchedule']['viewType'] == "" ? 'SELECTED' : '' ) ?> >All</option>';
								<option value="todo" 		<?= ($_SESSION['webSchedule']['viewType'] == "todo" ? 'SELECTED' : '' ) ?> >To Do</option>';
								<option value="saved" 	<?= ($_SESSION['webSchedule']['viewType'] == "saved" ? 'SELECTED' : '' ) ?> >Saved</option>';
								<option value="noConfirmed" <?= ($_SESSION['webSchedule']['viewType'] == "noConfirmed" ? 'SELECTED' : '' ) ?> >No Confirmed</option>';
							</select>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="four columns" style="text-align: left;">
			<div class="clearDiv">
				<div ><!-- Additional Web Schedule Pages: -->&nbsp;</div>
				<div>
					<a class="extraPage" href="?s1=webSchedule&s2=Shared" target="_Blank">Shared Pages</a>
					<a class="extraPage" href="?s1=webSchedule&s2=DomainPurchase" target="_Blank">Domain Purchase</a>
					<a class="extraPage" href="?s1=webSchedule&s2=CampaignDefaults" target="_Blank">Campaign Defaults</a>
					<?php
						if(PageSection::getPageSectionBoolean($_SESSION['login']['staffID'], PageSection::RSVP_WEBSITE_CAMPAIGN_ADMIN)) {
					?>
						<a class="extraPage" href="?s1=webSchedule&s2=CampaignEditor" target="_Blank">Campaign Uploader</a>
					<?php
						}
					?>
				</div>

			</div>
		</div>
	</div>

	<div class="row">
		<table class="webTable">
			<thead >
				<tr >
					<th>Event ID</th>
					<th>Dealership</th>
					<th>Event Date</th>
					<th>Event Type</th>
					<th >Creator</th>
					<th>URL</th>
					<!--<th style="text-align:center">Editor</th>-->
					<th> TOTAL </th>
					<th> ART-Old </th>
					<th> ART </th>
					<th >LIVE</th>
				</tr>
			</thead>
			<?php

			$loadFirstArray = array();
			$count = 0;
			foreach($events as $event)
		  	{
		  		$eventID = $event['eventID'];

		  		$eventType = "";
		  		if($event['salesTypeID'] == SalesType::TECH_ONLY){
		  			$eventType = "Tech";
		  		}

		  		if(empty($event['masterTaskID'])) {
		  			$noWebsiteConfirmed = $event['noWebsiteConfirmed'];
		  			?>
					<tr id = "tr_<?= $eventID ?>" style="background-color:<?= ($noWebsiteConfirmed == "" || $noWebsiteConfirmed == "changed" ? 'white' : '#63B8FF') ?>">
				  		<td onMouseOver="this.style.cursor='pointer'" onClick="return viewEvent(<?= $eventID ?>,'<?= ($event['nation'] == 'ca' ? '' : $event['nation']) ?>')" style="text-align: left;"><?= $eventID ?></td>
				  		<td onMouseOver="this.style.cursor='pointer'" onClick="return viewEvent(<?= $eventID ?>,'<?= ($event['nation'] == 'ca' ? '' : $event['nation']) ?>')" style="text-align: left;"><?= $event['dealerName'] ?></td>
				  		<td style="text-align: left;"><?= date("M j",strtotime($event['saleStartDate'])) . ($event['saleStartDate'] != $event['saleEndDate'] ? " - " . date("j",strtotime($event['saleEndDate'])) : "") ?></td>
				  		<td style="text-align: left;"><?= $eventType ?></td>
				  		<td style="text-align: left;"><a href="?s1=webSchedule&s2=Creator&noAgreement=&eventID=<?= $eventID ?>" target="_blank">VIEW ART</a></td></td>
				  		<td>NONE</td>
				  		<td></td>
				  		<td id="art_<?=$eventID?>" style="text-align: left;">-</td>
				  		<td style="text-align: left; width: 3%;"><?= $event['totalArtwork'] ?></td>
				  		<td class="viewTypeClass" id="confirm_<?=$eventID?>">
				  			<?php
				  			if ($noWebsiteConfirmed == "") {
				  				echo '<span class="todo"></span><a href="#" onclick="confirmNo('.$eventID.'); return false;">Confirm No</a>';
				  				if($_SESSION['webSchedule']['viewType'] == "todo"){
				  					$loadFirstArray[] = $eventID;
				  					unset($_SESSION['webSchedule']['eventIDs'][$count]);
				  				}
				  			}
				  			else if($noWebsiteConfirmed == "changed"){
								echo '<span class="todo"></span>Changed <a href="#" onclick="confirmNo('.$eventID.'); return false;">Confirm No</a>';
								if($_SESSION['webSchedule']['viewType'] == "todo"){
				  					$loadFirstArray[] = $eventID;
				  					unset($_SESSION['webSchedule']['eventIDs'][$count]);
				  				}
				  			}
				  			else{
				  				echo '<span class="noConfirmed"></span>NO CONFIRMED:' . $event["noWebsiteConfirmed"];
				  				if($_SESSION['webSchedule']['viewType'] == "noConfirmed"){
				  					$loadFirstArray[] = $eventID;
				  					unset($_SESSION['webSchedule']['eventIDs'][$count]);
				  				}
				  			}
				  			?>
				  		</td>
			  		</tr>
		  			<?php
		  		}
		  		else{
			  		//$webTasks = getTasks($eventID, "rsvpwebsites");
			  		$urls  = '';
			  		$urlArray = array();
			  		//$total = count($webTasks);
			  		$total = 1;
			  		$live  = 0;
			  		$totalArt= 0;
			  		$done  = true;



			  		//$urls = implode(', ', array_unique($urlArray));
		  			if($event['online'] == 'yes') $live++;
		  			if($event['status'] != "done") $done = false;
		  			if($event['online'] != 'yes') $done = false;

			  		$urls = $event['url'];
			  		if($live == $total) $live = "All Live";

			  		if($_SESSION['webSchedule']['viewType'] == "todo" && !$done){
	  					$loadFirstArray[] = $eventID;
	  					unset($_SESSION['webSchedule']['eventIDs'][$count]);
	  				}
	  				else if($_SESSION['webSchedule']['viewType'] == "saved" && $done)
			  		?>
			  		<tr "tr_<?= $eventID ?>" style="background-color:<?= ($done ? '#c3DDf2' : 'white') ?>">
				  		<td onMouseOver="this.style.cursor='pointer'" onClick="return viewEvent(<?= $eventID ?>,'<?= ($event['nation'] == 'ca' ? '' : $event['nation']) ?>')" style="text-align: left; width: 8%;"><?= $eventID ?></td>
				  		<td onMouseOver="this.style.cursor='pointer'" onClick="return viewEvent(<?= $eventID ?>,'<?= ($event['nation'] == 'ca' ? '' : $event['nation']) ?>')" style="text-align: left; width: 25%;"><?= $event['dealerName'] ?></td>
				  		<td style="text-align: left; width: 10%;"><?= date("M j",strtotime($event['saleStartDate'])) . ($event['saleStartDate'] != $event['saleEndDate'] ? " - " . date("j",strtotime($event['saleEndDate'])) : "") ?></td>
				  		<td style="text-align: left; width: 5%;"><?= $eventType ?></td>
				  		<td style="text-align: left; width: 8%;"><a href="?s1=webSchedule&s2=Creator&masterTaskID=<?= $event['masterTaskID'] ?>" target="_blank">Creator</a></td></td>
				  		<td style="text-align: left; width: 23%;"><?= $urls ?></td>
				  		<td style="text-align: left; width: 3%;"><?= $total ?></td>
				  		<!-- <td id="art_<?=$eventID?>" style="text-align: left;"><?= $totalArt ?></td> -->
				  		<td id="art_<?=$eventID?>" style="text-align: left; width: 3%;">-</td>
				  		<td style="text-align: left; width: 3%;"><?= $event['totalArtwork'] ?></td>
				  		<td class="viewTypeClass" style="text-align: left; width: 16%;"><span class="<?= ($done ? 'saved' : 'todo' ) ?>"></span><?= $live ?></td>
			  		</tr>

		<?php
				}
				$count++;
			}

		?>
		</table>
	</div>
</div>


<?php
	//ajaxError($loadFirstArray);
	//ajaxError($_SESSION['webSchedule']['eventIDs']);

	$_SESSION['webSchedule']['eventIDs'] = array_merge($loadFirstArray, $_SESSION['webSchedule']['eventIDs']);

	//ajaxError($_SESSION['webSchedule']['eventIDs']);

	//ajaxError($_SESSION['webSchedule']['eventIDs']);
?>
<div id="footer">

</div>
