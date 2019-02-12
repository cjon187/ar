<link rel="stylesheet" href="scripts/sweetalert/sweetalert.css" />
<script src="scripts/sweetalert/sweetalert.min.js"></script>
<script>
	
	function sendDisapproval()
	{
		$('#btn').attr('disabled','disabled');
		$('#btn').val('Sending...');
	
		$.ajax({data:	{disapprove: $('#reason').val()},
				type:	'POST',
				dataType: 'script'
   	    });  
		return false;
		
	}

</script>

<style>
</style>

	<div class="row">
		<div class="col-md-12">
			<h4><?= $event->dealer->name ?> - <?= $event->displayEventDate(false,true) ?></h4>
	<?php if($approved) { ?>		
			<h4><?php
			 if(is_array($msg)){
			 	foreach($msg as $m){
			 		echo $m[0];
			 	}
			 } 
			 else {
			 	echo $msg;
			 }
			 ?></h4>
	<?php } else { ?>	
			<h4>Disapproval Reason</h4>
			<textarea class="form-control" id="reason" name="reason" style="height:100px"></textarea>
			<input type="button" class="btn btn-primary" id="btn" value="Send" onClick="sendDisapproval()" />
	<?php } ?>	
		</div>	
	</div>
