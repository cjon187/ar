
<script>

	var conn = new WebSocket('ws://<?= WEBSOCKET_HOST ?>:<?= WEBSOCKET_PORT ?>');
	conn.onopen = function(e) {
	    console.log('Connected to Web Socket');
	};
	conn.onmessage = function(e) {
		data = jQuery.parseJSON(e.data);
		if(typeof data =='object') {
			if(data.key == '<?= $_SESSION['loadContacts']['socketKey'] ?>') {
				if(data.success != undefined) {
					if(data.success == 1) {
						$('#doneBtn').show();
					} else {
						alert('Error.');
					}
				} else {
				    $('#statusDiv').append(data.msg);
				  	$('#statusDiv').scrollTop($('#statusDiv')[0].scrollHeight);
				}
			}
		}
	};

	function start() {
		$('#startBtn').hide();
		$.ajax({data:	{start: ''},
				type:	'GET',
				dataType: 'json',
				success: function (data) {
					if(data.success == 1) {
						//DO NOTHING
					} else  {
						$('#statusDiv').html('Error executing script');
					}
				}
		});
	}

	/*function logIt() {
		$.ajax({data:	{logIt: ''},
				type:	'GET',
				dataType: 'json',
				success: function (data) {
					if(data.success == 1) {
						$('#doneBtn').show();
					} else  {
						console.log(data.errors);
						$('#statusDiv').html('Error logging the upload.');
					}
				}
		});
	}*/
</script>
<style>
	#statusDiv {
		overflow-y: auto;
		height:250px;
		margin:10px 0px;
		padding:10px;
		border:1px solid #ddd;
		border-radius:5px;
	}

	#doneBtn {
		display:none;
	}
</style>

<?php
	if(!empty($dealer->id)) {
?>
	<div class="row">
		<div class="col-md-12">
			<h3>Dealer: <?= $dealer->id ?> - <?= $dealer->name ?> </h3>
		</div>
	</div>


	<div id="statusDiv">
		<button class="btn btn-primary btn-sm" id="startBtn" onClick="start();">Start</button>
	</div>
	<button class="btn btn-primary" id="doneBtn" onClick="location.href='?s1=contacts&did=<?= $dealer->id ?>'">Finished</button>
<?php
	} else {
?>
	Invalid Dealer.
<?php
	}
?>