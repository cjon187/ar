<?php include_once('leads_header.php'); ?>

		
<style>
	#conversation {
		background-color:#f5f5f5;
		border-radius:5px;
		padding:10px;
	}
	#conversationList {
		height:300px;
		overflow:auto;
	}
</style>
<script>
	<?php
		if(empty($dedicatedPhone)) {
	?>
		alert('The dealership does not have a assigned phone.');
		location.href = '?s1=<?= $_GET['s1'] ?>&s2=View&id=<?= $lead->id ?>';
	<?php
		} else if(empty($cellPhone)) {
	?>
		alert('This lead does not have a cell phone.');
		location.href = '?s1=<?= $_GET['s1'] ?>&s2=View&id=<?= $lead->id ?>';
	<?php
		}
	?>
	var conversationTimeout;
	
	$(function() {
		$('#disclaimer').modal('show');
		$('#disclaimer').on('hidden.bs.modal', function (e) {
			loadConversation(true);
		});
	});

	function loadConversation(needScroll)
	{
		clearTimeout(conversationTimeout);
		conversationTimeout = setTimeout(function() {loadConversation('false')}, 3000);
		
		$.ajax({data:	{loadConversation: '1',
						 needScroll: needScroll},
				type:	'POST',
				dataType: 'script'
		   	    });
	}

	function sendMessage()
	{
   		$('#sendBtn').attr('disabled','disabled');
		
		
		$.ajax({data:	{message: $('#message').val()},
				type:	'POST',
				dataType: 'script'
		   	    });	
		   	    
		return false; 
	}
</script>
<div class="container-fluid" id="nonprime">
	<div class="row">
		<div class="col-md-12">
			<ol class="breadcrumb">
				<li><a href="?s1=<?= $_GET['s1'] ?>">Leads</a></li>
				<li><a href="?s1=<?= $_GET['s1'] ?>&s2=View&id=<?= $lead->id ?>"><?= $lead->name ?></a></li>
				<li class="active">
					Message Center
				</li>
			</ol>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div id="conversation">
				<div id="conversationList"><div style="font-style:italic;padding:10px">Loading Messages...</div></div>
				<div style="background-color:#333;padding:10px;height:60px;border-radius:5px">
					<form id="messageForm" method="POST" onSubmit="return sendMessage()">
						<input type="hidden" name="phone" id="conversationPhone" value="">
						<input type="hidden" name="name" id="conversationName" value="">
						<div style="display:table-cell;padding-right:10px;width: 100%"><input class="form-control" id="message" name="message" type="text" placeholder="Message"></div>
						<div style="display:table-cell;"><input class="btn btn-primary" id="sendBtn" type="submit" value="Send"></div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="disclaimer" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Disclaimer</h4>
				</div>
				<div class="modal-body">
					<p>Please accept responsibility & liability for any messages sent using the message center.</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" data-dismiss="modal">I Accept</button>
				</div>
			</div>
		</div>
	</div>
</div>