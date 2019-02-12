<script type="text/javascript">
	function checkEvent(eventID){
		var moveToDealerID = document.getElementById('moveToDealerID').value;
		if (moveToDealerID === '') {
			ARAlertError('Please enter dealerID');
		}else if(moveToDealerID == <?= $currentDealer->dealerID?>){
			ARAlertError('DealerID you put in is as same as current dealerID');
		}else{
			if (eventID != null) {
				$.ajax({data:	{checkEvent: '',
							 eventID: eventID},
					type:	'POST',
					dataType: 'JSON',
					success: function(data){
						var confirmFunction = function(){
							$.ajax({
								data:{	moveEvent:'',
										dataArray:data,
										moveToDealerID: moveToDealerID
									},
								type: 'POST',
								dataType: 'script'
							});
						};
						var message = data;
						if (data == '') {
							ARAlertError('There is no record needs to be updated');
						}else{
							var newMessage = '';
							newMessage += '<div id="errorTblDiv" style="padding:10px;margin-bottom:10px;"><table id="errorTbl" style="margin-bottom:0px;" class="table table-condensed">\
											<tr><td class="errorLabel" style="padding:2px;font-size:14px;color:#333;font-weight:bold;width:30%;">Table Name</td><td class="errorDesc" style="padding:2px;font-size:14px;color:black;font-weight:bold;text-align:left;">Primary Key and DealerID</td>';
							for (var key in message) {
								var value = message[key];
								newMessage += '<tr><td class="errorLabel" style="padding:2px;font-size:14px;color:#333;font-weight:bold;width:30%;">'+value['tableName']+':</td><td class="errorDesc" style="padding:2px;font-size:14px;color:#ff8080;font-weight:bold;text-align:left;">';
								for (var field in value){
									if (field == 'dealerID') {
										newMessage += field + ': ' + value[field] + ' -> ' + moveToDealerID +'<br>';
									}else if (field == 'tableName') {
										newMessage += '';
									}else{
										newMessage += field + ': ' + value[field] +'<br>';
									}
								}
								newMessage += '</td></tr>';
							}
							newMessage += '</table></div>';
							ARAlertConfirmation(newMessage, null, confirmFunction);
						}
					}
				});
			}else{
				ARAlertError('There is no eventID');
				return false;
			}
		}
	}
</script>
<div>
	<h3>Move Event</h3>
	<div>
		<label>Current Dealer: </label><?= $currentDealer->dealerName ?>
		<label>Current DealerID: </label><?= $currentDealer->dealerID ?>
	</div>
	<div>
		<label>Move to Dealer: </label>
		<input id="moveToDealerID" name="moveToDealerID" value="" />
		<button onClick="checkEvent(<?= $_GET['eventID'] ?>);">Save</button>
	</div>
</div>

