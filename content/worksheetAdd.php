<style>
	.worksheetAddTbl {width:480px}
	.worksheetAddTbl th {font-size:10pt;white-space:nowrap;text-align:left;padding-bottom:2px;padding-right:10px;vertical-align:middle}
	.worksheetAddTbl td {padding-bottom:2px;vertical-align:middle}
	.worksheetAddTbl select, option, input, textarea {font-size:9pt;}
</style>

<link rel="stylesheet" href="scripts/multiple-select-master/multiple-select.css" />
<script src="scripts/multiple-select-master/jquery.multiple.select.js"></script>

<script>
	var pageChanged = false;
	/*$(document).ready(function(){
		var url = '<?= str_replace(array('www.'),'',$_SESSION['worksheetAdd']['website']) ?>';
		$.ajax({data:	{whois: url},
				type:	'GET',
				dataType: 'script'
		   	    });
	});*/

	$(document).ready(function(){
		showPrintBy();
		//window.opener.location.reload();
		setContact();

		$(':input').on('change', function(){
			pageChanged = true;
		});

		$("#contactEmail").multipleSelect({
			'minimumCountSelected':4
		});
	});

	function showPrintBy()
	{
		//if(document.getElementById('invitationChecked').checked) $('#invitationPrinter').show();
		//else $('#invitationPrinter').hide();
		$('#invitationPrinter').show();

		//if(document.getElementById('conquestChecked').checked) $('#conquestPrinter').show();
		//else $('#conquestPrinter').hide();
		$('#conquestPrinter').show();

		$('#kitPrinterID').show();
	}

	function checkFilled(fieldType)
	{
		if(document.getElementById(fieldType).value == "") document.getElementById(fieldType + 'Checked').checked = false;
		else document.getElementById(fieldType + 'Checked').checked = true;

		showPrintBy();
	}

	function autoFillEventEnd()
	{
		document.getElementById('eventEndMonth').value = document.getElementById('eventStartMonth').value ;
		document.getElementById('eventEndDay').value = document.getElementById('eventStartDay').value ;
		document.getElementById('eventEndYear').value = document.getElementById('eventStartYear').value ;
	}

	function setDataExtract()
	{
		if(document.getElementById('dataExtract').value == 'perry') document.getElementById('dataExtractRate').value = '200';
		else if(document.getElementById('dataExtract').value == 'pbs') document.getElementById('dataExtractRate').value = '55';
		else document.getElementById('dataExtractRate').value = '';
	}

	function setWebsite()
	{
		document.getElementById('website').value = document.getElementById('websiteSelect').value;
	}

	function setDeclined(field)
	{
		if(document.getElementById(field).value != '')
		{
			document.getElementById(field).value = '';
			document.getElementById(field + 'Checked').checked = false;
		}
		else
		{
			if(document.getElementById('isFrench').checked) document.getElementById(field).value = 'refusé';
			else document.getElementById(field).value = 'declined';

			document.getElementById(field + 'Checked').checked = true;
		}
	}
	function setMailedLocal(field)
	{
		if(document.getElementById(field).value != '')
		{
			document.getElementById(field).value = '';
			document.getElementById(field + 'Checked').checked = false;
		}
		else
		{
			if(document.getElementById('isFrench').checked) document.getElementById(field).value = 'poste locale';
			else document.getElementById(field).value = 'Mailed Local';

			document.getElementById(field + 'Checked').checked = true;
		}
	}
	function setTBD(field)
	{
		if(document.getElementById(field).value == 'TBD')
		{
			document.getElementById(field).value = '';
			document.getElementById(field + 'Checked').checked = false;
		}
		else
		{
			document.getElementById(field).value = 'TBD';
			document.getElementById(field + 'Checked').checked = true;
		}

		showPrintBy();
	}
	function setContact()
	{
		$.ajax({data:	{setContact: $('#dealerID').val()},
				type:	'GET',
				dataType: 'script'
		   	    });
	}
	function selectContact()
	{
		val = document.getElementById('contactSelect').value.split('|');

		if(val[0] !== undefined){
			$('#contact').val(val[0]);
		}

		if(val[1] !== undefined && val[1] != ""){
			$('#contactHiddenEmail').val(val[1]);
			$('#contactDisplayEmail').text(val[1]);
		}
		else{
			$('#contactHiddenEmail').val('');
			$('#contactDisplayEmail').text('No Email Set');
		}

		if(val[2] !== undefined){
			$('#contactPhone').val(val[2]);
		}

		if(val[3] !== undefined){
			$('#dealerStaffID').val(val[3]);
		}

		/*val = document.getElementById('contactSelect').value.split('|');

		if(val[0] == undefined) document.getElementById('contact').value = '';
		else document.getElementById('contact').value = val[0];

		if(val[1] == undefined) document.getElementById('contactEmail').value = '';
		else if($('#multipleContact').prop('checked'))
		{
			if(document.getElementById('contactEmail').value == '')	document.getElementById('contactEmail').value = val[1];
			else if($('#contactEmail').val().search(val[1]) == -1) document.getElementById('contactEmail').value += ',' + val[1];


		}
		else document.getElementById('contactEmail').value = val[1];

		document.getElementById('contactEmail').value = document.getElementById('contactEmail').value.replace(",,", ",");

		if(val[2] == undefined) document.getElementById('contactPhone').value = '';
		else document.getElementById('contactPhone').value = val[2];

		if(val[3] == undefined) document.getElementById('dealerStaffID').value = '';
		else document.getElementById('dealerStaffID').value = val[3];*/
	}

	function sendEmail()
	{
		$.ajax({data:	{sendEmail: ''},
				type:	'GET',
				dataType: 'script'
		   	    });

	}
	function sendEmailWelcome()
	{
		$.ajax({data:	{sendEmailWelcome: ''},
				type:	'GET',
				dataType: 'script'
		   	    });

	}
	function sendEmailWelcomeShowNum()
	{
		$.ajax({data:	{sendEmailWelcome: '',
						 showNum: ''},
				type:	'GET',
				dataType: 'script'
		   	    });

	}
	function sendEmailPhoneList()
	{
		$.ajax({data:	{sendPhoneList: ''},
				type:	'GET',
				dataType: 'script'
		   	    });

	}
	function sendEmailPhoneListShowNum()
	{
		$.ajax({data:	{sendPhoneList: '',
						 showNum: ''},
				type:	'GET',
				dataType: 'script'
		   	    });

	}

	function sendVoicecast()
	{
		$.ajax({data:	{sendVoicecast: ''},
				type:	'GET',
				dataType: 'script'
		   	    });

	}

	function sendEmailSelfWelcome()
	{
		document.getElementById('emailSelfWelcomeButton').value="Sending Email...";
		document.getElementById('emailSelfWelcomeButton').disabled=true;

		$.ajax({data:	{sendEmailWelcome: 'self'},
				type:	'GET',
				dataType: 'script'
		   	    });
	}
	function sendEmailSelfWelcomeShowNum()
	{
		document.getElementById('emailSelfWelcomeShowNumButton').value="Sending Email...";
		document.getElementById('emailSelfWelcomeShowNumButton').disabled=true;

		$.ajax({data:	{sendEmailWelcome: 'self',
						 showNum: ''},
				type:	'GET',
				dataType: 'script'
		   	    });
	}
	function sendEmailSelfPhoneList()
	{
		document.getElementById('emailSelfPhoneListButton').value="Sending Email...";
		document.getElementById('emailSelfPhoneListButton').disabled=true;

		$.ajax({data:	{sendPhoneList: 'self'},
				type:	'GET',
				dataType: 'script'
		   	    });
	}
	function sendEmailSelfPhoneListShowNum()
	{
		document.getElementById('emailSelfPhoneListShowNumButton').value="Sending Email...";
		document.getElementById('emailSelfPhoneListShowNumButton').disabled=true;

		$.ajax({data:	{sendPhoneList: 'self',
						 showNum: ''},
				type:	'GET',
				dataType: 'script'
		   	    });
	}
	function sendEmailDataPassword()
	{
		document.getElementById('emailDataPasswordButton').value="Sending Email...";
		document.getElementById('emailDataPasswordButton').disabled=true;

		$.ajax({data:	{sendDataPassword: ''},
				type:	'GET',
				dataType: 'script'
		   	    });
	}
	function sendEmailSelfDataPassword()
	{
		document.getElementById('emailSelfDataPasswordButton').value="Sending Email...";
		document.getElementById('emailSelfDataPasswordButton').disabled=true;

		$.ajax({data:	{sendDataPassword: 'self'},
				type:	'GET',
				dataType: 'script'
		   	    });
	}
	function cancelVoicecast()
	{
		document.getElementById('cancelVoicecastButton').value="Sending Email...";
		document.getElementById('cancelVoicecastButton').disabled=true;

		$.ajax({data:	{cancelVoicecast: ''},
				type:	'GET',
				dataType: 'script'
		   	    });
	}
	function sendVoicecastSelf()
	{
		document.getElementById('voicecastSelfButton').value="Sending Email...";
		document.getElementById('voicecastSelfButton').disabled=true;

		$.ajax({data:	{sendVoicecast: 'self'},
				type:	'GET',
				dataType: 'script'
		   	    });
	}
	function sendPURL()
	{
		document.getElementById('PURLButton').value="Sending Email...";
		document.getElementById('PURLButton').disabled=true;

		$.ajax({data:	{sendPURL: ''},
				type:	'GET',
				dataType: 'script'
		   	    });
	}
	function sendPURLSelf()
	{
		document.getElementById('PURLSelfButton').value="Sending Email...";
		document.getElementById('PURLSelfButton').disabled=true;

		$.ajax({data:	{sendPURL: 'self'},
				type:	'GET',
				dataType: 'script'
		   	    });
	}
	function sendSMSEmail()
	{
		document.getElementById('smsEmailButton').value="Sending Email...";
		document.getElementById('smsEmailButton').disabled=true;

		$.ajax({data:	{sendSMSEmail: ''},
				type:	'GET',
				dataType: 'script'
		   	    });
	}
	function sendWebsite()
	{
		document.getElementById('websiteButton').value="Sending Email...";
		document.getElementById('websiteButton').disabled=true;

		$.ajax({data:	{sendWebsite: ''},
				type:	'GET',
				dataType: 'script'
		   	    });
	}
	function checkEmail()
	{
		if(hasPageChanged()){
			$.ajax({data:	{checkEmail: ''},
					type:	'GET',
					dataType: 'script'
			   	    });
		}
	}
	function checkEmailWelcome()
	{
		if(hasPageChanged()){
			$.ajax({data:	{checkEmailWelcome: ''},
					type:	'GET',
					dataType: 'script'
			   	    });
		}
	}
	function checkEmailWelcomeShowNum()
	{
		if(hasPageChanged()){
			$.ajax({data:	{checkEmailWelcome: '',
							 showNum: ''},
					type:	'GET',
					dataType: 'script'
			   	    });
		}
	}
	function checkEmailPhoneList()
	{
		if(hasPageChanged()){
			$.ajax({data:	{checkEmailPhoneList: ''},
					type:	'GET',
					dataType: 'script'
			   	    });
		}
	}
	function checkEmailDataPassword()
	{
		if(hasPageChanged()){
			$.ajax({data:	{checkEmailDataPassword: ''},
					type:	'GET',
					dataType: 'script'
			   	    });
		}
	}
	function checkEmailPhoneListShowNum()
	{
		if(hasPageChanged()){
			$.ajax({data:	{checkEmailPhoneList: '',
							 showNum: ''},
					type:	'GET',
					dataType: 'script'
			   	    });
		}
	}
	function checkVoicecast()
	{
		if(hasPageChanged()){
			$.ajax({data:	{checkVoicecast: ''},
					type:	'GET',
					dataType: 'script'
			   	    });
		}
	}

	function hasPageChanged(){
		if(pageChanged){
			swal({
				title: "Unsaved Changes Detected",
				text: "Please save event before sending emails",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#61B329",
				confirmButtonText: "Save Agreement",
				closeOnConfirm: true
				},
				function(){
					$('#agreementForm').submit();
				}
			);
			return false;
		}
		else{
			return true;
		}
	}

	function preload(aType)
	{
		$('[item]').val('');
		$('[item_checked]').attr('checked',false);

		if(aType == 'canchryslerprivatesale')
		{
			document.getElementById('postage').value = '0.85';
			document.getElementById('postageChecked').checked = true;
			document.getElementById('numMailed').value = 'TBD';
			document.getElementById('numMailedChecked').checked = true;
			document.getElementById('training').value = '2000';
			document.getElementById('trainingChecked').checked = true;
			document.getElementById('travel').value = '1000';
			document.getElementById('travelChecked').checked = true;
			document.getElementById('perCar').value = '150';
			document.getElementById('perCarChecked').checked = true;
			document.getElementById('freight').value = '300';
			document.getElementById('freightChecked').checked = true;
			document.getElementById('showroomKit').value = '250';
			document.getElementById('showroomKitChecked').checked = true;
		}
		else if(aType == 'uschryslerprivatesale')
		{
			document.getElementById('invitation').value = 'TBD';
			document.getElementById('invitationChecked').checked = true;
			document.getElementById('postage').value = '0.49';
			document.getElementById('postageChecked').checked = true;
			document.getElementById('numMailed').value = 'TBD';
			document.getElementById('numMailedChecked').checked = true;
			document.getElementById('training').value = 'Included';
			document.getElementById('trainingChecked').checked = true;
			document.getElementById('travel').value = 'Included';
			document.getElementById('travelChecked').checked = true;
			document.getElementById('email').value = 'Included';
			document.getElementById('emailChecked').checked = true;
			document.getElementById('techPackage').value = 'Included';
			document.getElementById('techPackageChecked').checked = true;
			document.getElementById('freight').value = 'Included';
			document.getElementById('freightChecked').checked = true;
			document.getElementById('dataScrubbing').value = 'Included';
			document.getElementById('dataScrubbingChecked').checked = true;
			document.getElementById('showroomKit').value = 'Included';
			document.getElementById('showroomKitChecked').checked = true;

		}
		else if(aType == 'canprivatesale')
		{
			document.getElementById('invitation').value = 'TBD';
			document.getElementById('invitationChecked').checked = true;
			document.getElementById('postage').value = '0.85';
			document.getElementById('postageChecked').checked = true;
			document.getElementById('numMailed').value = 'TBD';
			document.getElementById('numMailedChecked').checked = true;
			document.getElementById('training').value = '2500';
			document.getElementById('trainingChecked').checked = true;
			document.getElementById('travel').value = '1000';
			document.getElementById('travelChecked').checked = true;
			document.getElementById('perCar').value = '150';
			document.getElementById('perCarChecked').checked = true;
			document.getElementById('showroomKit').value = '295';
			document.getElementById('showroomKitChecked').checked = true;
			document.getElementById('voicecast').value = 'Included';
			document.getElementById('voicecastChecked').checked = true;
			document.getElementById('sms').value = 'Included';
			document.getElementById('smsChecked').checked = true;
			document.getElementById('email').value = 'Included';
			document.getElementById('emailChecked').checked = true;
			document.getElementById('techPackage').value = '595';
			document.getElementById('techPackageChecked').checked = true;
			document.getElementById('dataScrubbing').value = 'Included';
			document.getElementById('dataScrubbingChecked').checked = true;
		}
		else if (aType == 'ca_luxury')
		{
			document.getElementById('invitation').value = 'TBD';
			document.getElementById('invitationChecked').checked = true;
			document.getElementById('postage').value = '0.85';
			document.getElementById('postageChecked').checked = true;
			document.getElementById('numMailed').value = 'TBD';
			document.getElementById('numMailedChecked').checked = true;
			document.getElementById('training').value = '2750';
			document.getElementById('trainingChecked').checked = true;
			document.getElementById('travel').value = '850';
			document.getElementById('travelChecked').checked = true;
			document.getElementById('perCar').value = '200';
			document.getElementById('perCarChecked').checked = true;
			document.getElementById('showroomKit').value = '295';
			document.getElementById('showroomKitChecked').checked = true;
			document.getElementById('voicecast').value = 'Included';
			document.getElementById('voicecastChecked').checked = true;
			document.getElementById('sms').value = 'Included';
			document.getElementById('smsChecked').checked = true;
			document.getElementById('email').value = 'Included';
			document.getElementById('emailChecked').checked = true;
			document.getElementById('techPackage').value = '595';
			document.getElementById('techPackageChecked').checked = true;
			document.getElementById('dataScrubbing').value = 'Included';
			document.getElementById('dataScrubbingChecked').checked = true;
		}
		else if(aType == 'usprivatesale')
		{
			document.getElementById('postage').value = '0.49';
			document.getElementById('postageChecked').checked = true;
			document.getElementById('numMailed').value = 'TBD';
			document.getElementById('numMailedChecked').checked = true;
			document.getElementById('training').value = '3000';
			document.getElementById('trainingChecked').checked = true;
			document.getElementById('travel').value = '1500';
			document.getElementById('travelChecked').checked = true;
			document.getElementById('freight').value = '300';
			document.getElementById('freightChecked').checked = true;
		}
		else if(aType == 'cainviteonly')
		{
			document.getElementById('invitation').value = 'TBD';
			document.getElementById('invitationChecked').checked = true;
			document.getElementById('postage').value = '0.85';
			document.getElementById('postageChecked').checked = true;
			document.getElementById('numMailed').value = 'TBD';
			document.getElementById('numMailedChecked').checked = true;
		}
		/*else if(aType.search('ca_ch_package_') !== -1 && aType.search('_conquest_') !== -1)
		{
			document.getElementById('conquest').value = 'Included';
			document.getElementById('conquestChecked').checked = true;
			document.getElementById('numMailed').value = '';
			document.getElementById('numMailedChecked').checked = true;
		}
		else if(aType.search('ca_ch_package_') !== -1)
		{
			document.getElementById('invitation').value = 'Included';
			document.getElementById('invitationChecked').checked = true;
			document.getElementById('postage').value = 'Included';
			document.getElementById('postageChecked').checked = true;
			document.getElementById('numMailed').value = '';
			document.getElementById('numMailedChecked').checked = true;
			document.getElementById('training').value = '2000';
			document.getElementById('trainingChecked').checked = true;
			document.getElementById('travel').value = '1000';
			document.getElementById('travelChecked').checked = true;
			document.getElementById('perCar').value = '150';
			document.getElementById('perCarChecked').checked = true;
			document.getElementById('freight').value = 'Included';
			document.getElementById('freightChecked').checked = true;
			document.getElementById('showroomKit').value = 'Included';
			document.getElementById('showroomKitChecked').checked = true;
			document.getElementById('techPackage').value = 'Included';
			document.getElementById('techPackageChecked').checked = true;
			document.getElementById('dataScrubbing').value = 'Included';
			document.getElementById('dataScrubbingChecked').checked = true;
		}*/

		else if(aType == 'usa1500')
		{
			document.getElementById('invitation').value = '4.606';
			document.getElementById('invitationChecked').checked = true;
			document.getElementById('postage').value = '0.490';
			document.getElementById('postageChecked').checked = true;
			document.getElementById('numMailed').value = '1500';
			document.getElementById('numMailedChecked').checked = true;
			document.getElementById('training').value = 'Included';
			document.getElementById('trainingChecked').checked = true;
			document.getElementById('travel').value = 'Included';
			document.getElementById('travelChecked').checked = true;
			document.getElementById('freight').value = 'Mailed Local';
			document.getElementById('freightChecked').checked = true;
			document.getElementById('showroomKit').value = 'Included';
			document.getElementById('showroomKitChecked').checked = true;
			document.getElementById('email').value = 'Included';
			document.getElementById('emailChecked').checked = true;
			document.getElementById('techPackage').value = 'Included';
			document.getElementById('techPackageChecked').checked = true;
			document.getElementById('dataScrubbing').value = 'Included';
			document.getElementById('dataScrubbingChecked').checked = true;
			document.getElementById('item1Description').value = 'Total';
			document.getElementById('item1Rate').value = '$7,645';
		}
		else if(aType == 'usa2500')
		{
			document.getElementById('invitation').value = '3.108';
			document.getElementById('invitationChecked').checked = true;
			document.getElementById('postage').value = '0.490';
			document.getElementById('postageChecked').checked = true;
			document.getElementById('numMailed').value = '2500';
			document.getElementById('numMailedChecked').checked = true;
			document.getElementById('training').value = 'Included';
			document.getElementById('trainingChecked').checked = true;
			document.getElementById('travel').value = 'Included';
			document.getElementById('travelChecked').checked = true;
			document.getElementById('freight').value = 'Mailed Local';
			document.getElementById('freightChecked').checked = true;
			document.getElementById('showroomKit').value = 'Included';
			document.getElementById('showroomKitChecked').checked = true;
			document.getElementById('email').value = 'Included';
			document.getElementById('emailChecked').checked = true;
			document.getElementById('techPackage').value = 'Included';
			document.getElementById('techPackageChecked').checked = true;
			document.getElementById('dataScrubbing').value = 'Included';
			document.getElementById('dataScrubbingChecked').checked = true;
			document.getElementById('item1Description').value = 'Total';
			document.getElementById('item1Rate').value = '$8,995';
		}
		else if(aType == 'usa4000')
		{
			document.getElementById('invitation').value = '2.183';
			document.getElementById('invitationChecked').checked = true;
			document.getElementById('postage').value = '0.490';
			document.getElementById('postageChecked').checked = true;
			document.getElementById('numMailed').value = '4000';
			document.getElementById('numMailedChecked').checked = true;
			document.getElementById('training').value = 'Included';
			document.getElementById('trainingChecked').checked = true;
			document.getElementById('travel').value = 'Included';
			document.getElementById('travelChecked').checked = true;
			document.getElementById('freight').value = 'Mailed Local';
			document.getElementById('freightChecked').checked = true;
			document.getElementById('showroomKit').value = 'Included';
			document.getElementById('showroomKitChecked').checked = true;
			document.getElementById('email').value = 'Included';
			document.getElementById('emailChecked').checked = true;
			document.getElementById('techPackage').value = 'Included';
			document.getElementById('techPackageChecked').checked = true;
			document.getElementById('dataScrubbing').value = 'Included';
			document.getElementById('dataScrubbingChecked').checked = true;
			document.getElementById('item1Description').value = 'Total';
			document.getElementById('item1Rate').value = '$10,695';
		}
		else if(aType == 'usa5000')
		{
			document.getElementById('invitation').value = '1.859';
			document.getElementById('invitationChecked').checked = true;
			document.getElementById('postage').value = '0.490';
			document.getElementById('postageChecked').checked = true;
			document.getElementById('numMailed').value = '5000';
			document.getElementById('numMailedChecked').checked = true;
			document.getElementById('training').value = 'Included';
			document.getElementById('trainingChecked').checked = true;
			document.getElementById('travel').value = 'Included';
			document.getElementById('travelChecked').checked = true;
			document.getElementById('freight').value = 'Mailed Local';
			document.getElementById('freightChecked').checked = true;
			document.getElementById('showroomKit').value = 'Included';
			document.getElementById('showroomKitChecked').checked = true;
			document.getElementById('email').value = 'Included';
			document.getElementById('emailChecked').checked = true;
			document.getElementById('techPackage').value = 'Included';
			document.getElementById('techPackageChecked').checked = true;
			document.getElementById('dataScrubbing').value = 'Included';
			document.getElementById('dataScrubbingChecked').checked = true;
			document.getElementById('item1Description').value = 'Total';
			document.getElementById('item1Rate').value = '$11,745';
		}
		else if(aType == 'usanano')
		{
			document.getElementById('invitation').value = '6.355';
			document.getElementById('invitationChecked').checked = true;
			document.getElementById('postage').value = '0.49';
			document.getElementById('postageChecked').checked = true;
			document.getElementById('numMailed').value = '1000';
			document.getElementById('numMailedChecked').checked = true;
			document.getElementById('training').value = 'Included';
			document.getElementById('trainingChecked').checked = true;
			document.getElementById('travel').value = 'Included';
			document.getElementById('travelChecked').checked = true;
			document.getElementById('freight').value = 'Mailed Local';
			document.getElementById('freightChecked').checked = true;
			document.getElementById('showroomKit').value = 'Included';
			document.getElementById('showroomKitChecked').checked = true;
			document.getElementById('techPackage').value = 'Included';
			document.getElementById('techPackageChecked').checked = true;
			document.getElementById('dataScrubbing').value = 'Included';
			document.getElementById('dataScrubbingChecked').checked = true;
			document.getElementById('item1Description').value = 'Total';
			document.getElementById('item1Rate').value = '$6,845';
		}
		else if(aType == 'usaguarantee2500')
		{
			document.getElementById('invitation').value = '3.508';
			document.getElementById('invitationChecked').checked = true;
			document.getElementById('postage').value = '0.49';
			document.getElementById('postageChecked').checked = true;
			document.getElementById('numMailed').value = '2500';
			document.getElementById('numMailedChecked').checked = true;
			document.getElementById('training').value = 'Included';
			document.getElementById('trainingChecked').checked = true;
			document.getElementById('travel').value = 'Included';
			document.getElementById('travelChecked').checked = true;
			document.getElementById('freight').value = 'Mailed Local';
			document.getElementById('freightChecked').checked = true;
			document.getElementById('showroomKit').value = 'Included';
			document.getElementById('showroomKitChecked').checked = true;
			document.getElementById('techPackage').value = 'Included';
			document.getElementById('techPackageChecked').checked = true;
			document.getElementById('dataScrubbing').value = 'Included';
			document.getElementById('dataScrubbingChecked').checked = true;
			document.getElementById('item1Description').value = 'Total';
			document.getElementById('item1Rate').value = '$9,995';
		}
		else if(aType == 'usaguarantee4000')
		{
			document.getElementById('invitation').value = '2.383';
			document.getElementById('invitationChecked').checked = true;
			document.getElementById('postage').value = '0.49';
			document.getElementById('postageChecked').checked = true;
			document.getElementById('numMailed').value = '4000';
			document.getElementById('numMailedChecked').checked = true;
			document.getElementById('training').value = 'Included';
			document.getElementById('trainingChecked').checked = true;
			document.getElementById('travel').value = 'Included';
			document.getElementById('travelChecked').checked = true;
			document.getElementById('freight').value = 'Mailed Local';
			document.getElementById('freightChecked').checked = true;
			document.getElementById('showroomKit').value = 'Included';
			document.getElementById('showroomKitChecked').checked = true;
			document.getElementById('techPackage').value = 'Included';
			document.getElementById('techPackageChecked').checked = true;
			document.getElementById('dataScrubbing').value = 'Included';
			document.getElementById('dataScrubbingChecked').checked = true;
			document.getElementById('item1Description').value = 'Total';
			document.getElementById('item1Rate').value = '$11,495';
		}
		else if(aType == 'usa_nonfca_1500')
		{
			document.getElementById('invitation').value = '4.807';
			document.getElementById('invitationChecked').checked = true;
			document.getElementById('postage').value = '0.490';
			document.getElementById('postageChecked').checked = true;
			document.getElementById('numMailed').value = '1500';
			document.getElementById('numMailedChecked').checked = true;
			document.getElementById('training').value = 'Included';
			document.getElementById('trainingChecked').checked = true;
			document.getElementById('travel').value = 'Included';
			document.getElementById('travelChecked').checked = true;
			document.getElementById('freight').value = 'Mailed Local';
			document.getElementById('freightChecked').checked = true;
			document.getElementById('showroomKit').value = 'Included';
			document.getElementById('showroomKitChecked').checked = true;
			document.getElementById('email').value = 'Included';
			document.getElementById('emailChecked').checked = true;
			document.getElementById('techPackage').value = 'Included';
			document.getElementById('techPackageChecked').checked = true;
			document.getElementById('dataScrubbing').value = 'Included';
			document.getElementById('dataScrubbingChecked').checked = true;
			document.getElementById('item1Description').value = 'Total';
			document.getElementById('item1Rate').value = '$7,945';
		}
		else if(aType == 'usa_nonfca_2500')
		{
			document.getElementById('invitation').value = '3.228';
			document.getElementById('invitationChecked').checked = true;
			document.getElementById('postage').value = '0.490';
			document.getElementById('postageChecked').checked = true;
			document.getElementById('numMailed').value = '2500';
			document.getElementById('numMailedChecked').checked = true;
			document.getElementById('training').value = 'Included';
			document.getElementById('trainingChecked').checked = true;
			document.getElementById('travel').value = 'Included';
			document.getElementById('travelChecked').checked = true;
			document.getElementById('freight').value = 'Mailed Local';
			document.getElementById('freightChecked').checked = true;
			document.getElementById('showroomKit').value = 'Included';
			document.getElementById('showroomKitChecked').checked = true;
			document.getElementById('email').value = 'Included';
			document.getElementById('emailChecked').checked = true;
			document.getElementById('techPackage').value = 'Included';
			document.getElementById('techPackageChecked').checked = true;
			document.getElementById('dataScrubbing').value = 'Included';
			document.getElementById('dataScrubbingChecked').checked = true;
			document.getElementById('item1Description').value = 'Total';
			document.getElementById('item1Rate').value = '$9,295';
		}
		else if(aType == 'usa_nonfca_4000')
		{
			document.getElementById('invitation').value = '2.259';
			document.getElementById('invitationChecked').checked = true;
			document.getElementById('postage').value = '0.490';
			document.getElementById('postageChecked').checked = true;
			document.getElementById('numMailed').value = '4000';
			document.getElementById('numMailedChecked').checked = true;
			document.getElementById('training').value = 'Included';
			document.getElementById('trainingChecked').checked = true;
			document.getElementById('travel').value = 'Included';
			document.getElementById('travelChecked').checked = true;
			document.getElementById('freight').value = 'Mailed Local';
			document.getElementById('freightChecked').checked = true;
			document.getElementById('showroomKit').value = 'Included';
			document.getElementById('showroomKitChecked').checked = true;
			document.getElementById('email').value = 'Included';
			document.getElementById('emailChecked').checked = true;
			document.getElementById('techPackage').value = 'Included';
			document.getElementById('techPackageChecked').checked = true;
			document.getElementById('dataScrubbing').value = 'Included';
			document.getElementById('dataScrubbingChecked').checked = true;
			document.getElementById('item1Description').value = 'Total';
			document.getElementById('item1Rate').value = '$10,995';
		}
		else if(aType == 'usa_nonfca_nano')
		{
			document.getElementById('invitation').value = '6.655';
			document.getElementById('invitationChecked').checked = true;
			document.getElementById('postage').value = '0.49';
			document.getElementById('postageChecked').checked = true;
			document.getElementById('numMailed').value = '1000';
			document.getElementById('numMailedChecked').checked = true;
			document.getElementById('training').value = 'Included';
			document.getElementById('trainingChecked').checked = true;
			document.getElementById('travel').value = 'Included';
			document.getElementById('travelChecked').checked = true;
			document.getElementById('freight').value = 'Mailed Local';
			document.getElementById('freightChecked').checked = true;
			document.getElementById('showroomKit').value = 'Included';
			document.getElementById('showroomKitChecked').checked = true;
			document.getElementById('techPackage').value = 'Included';
			document.getElementById('techPackageChecked').checked = true;
			document.getElementById('dataScrubbing').value = 'Included';
			document.getElementById('dataScrubbingChecked').checked = true;
			document.getElementById('item1Description').value = 'Total';
			document.getElementById('item1Rate').value = '$7,145';
		}
		else if(aType == 'rmab')
		{
			document.getElementById('invitation').value = 'Included';
			document.getElementById('invitationChecked').checked = true;
			document.getElementById('postage').value = 'Included';
			document.getElementById('postageChecked').checked = true;
			document.getElementById('numMailed').value = '2000';
			document.getElementById('numMailedChecked').checked = true;
			document.getElementById('training').value = '2500';
			document.getElementById('trainingChecked').checked = true;
			document.getElementById('travel').value = '1000';
			document.getElementById('travelChecked').checked = true;
			document.getElementById('perCar').value = '150';
			document.getElementById('perCarChecked').checked = true;
			document.getElementById('showroomKit').value = '250';
			document.getElementById('showroomKitChecked').checked = true;
			document.getElementById('voicecast').value = 'Included';
			document.getElementById('voicecastChecked').checked = true;
			document.getElementById('sms').value = 'Included';
			document.getElementById('smsChecked').checked = true;
			document.getElementById('email').value = 'Included';
			document.getElementById('emailChecked').checked = true;
			document.getElementById('item1Description').value = '2000 Invites + Postage + Tech Package';
			document.getElementById('item1Rate').value = 'RMAB FUNDED';
			document.getElementById('item2Description').value = 'Dealership to pay for invitations and postage over 2000 pieces';
			document.getElementById('item2Rate').value = '';
		}
		else if(aType == 'FCA_uk3_600')
		{
			document.getElementById('invitation').value = 'Included';
			document.getElementById('invitationChecked').checked = true;
			document.getElementById('training').value = '£3,900';
			document.getElementById('trainingChecked').checked = true;
			document.getElementById('travel').value = 'Included';
			document.getElementById('travelChecked').checked = true;
			document.getElementById('perCar').value = '£50';
			document.getElementById('perCarChecked').checked = true;
			document.getElementById('showroomKit').value = 'Included';
			document.getElementById('showroomKitChecked').checked = true;
			/*document.getElementById('techPackage').value = 'Included';
			document.getElementById('techPackageChecked').checked = true;*/
			document.getElementById('sms').value = 'Included';
			document.getElementById('smsChecked').checked = true;
			document.getElementById('email').value = 'Included';
			document.getElementById('emailChecked').checked = true;
			document.getElementById('item1Description').value = '£3,900 Training fee per event. FCA to subsidize £2,500';
			document.getElementById('item1Rate').value = '';
			document.getElementById('item2Description').value = 'All other charges are the responsibility of the Dealer';
			document.getElementById('publicNotes').value = 'A. 600 MAILED INVITATIONS: Includes: Database Scrub & Analysis with Online Dealership Data Portal. Dealer\'s Choice of Invitations (with insert). Premium Coloured Envelope (hand addressed). First Class Stamp and Delivered to Royal Mail.\nB. TWO DAYS OF TELEPHONE TRAINING WITH BLITZ: Includes: Training Fees & Trainer Travel costs. Training Workbooks & Appointment Board & Script Sheets.\nC. DIGITAL MARKETING PACKAGE: Includes: Up to 2,500 Interactive SMS PLUS Up to 2,500 Personalized Emails. Event Website for Customers to RSVP.\nD. EVENT SALE DAY: Includes: Showroom Kit of up to 40 XL Posters, Customer Deal Jackets, Ballot Forms & Sold Signs. 75 Extra Invitations (to hand out in service or active prospects).';
		}
		else if(aType == 'FCA_uk3_1000')
		{
			document.getElementById('invitation').value = 'Included';
			document.getElementById('invitationChecked').checked = true;
			document.getElementById('training').value = '£4,300';
			document.getElementById('trainingChecked').checked = true;
			document.getElementById('travel').value = 'Included';
			document.getElementById('travelChecked').checked = true;
			document.getElementById('perCar').value = '£50';
			document.getElementById('perCarChecked').checked = true;
			document.getElementById('showroomKit').value = 'Included';
			document.getElementById('showroomKitChecked').checked = true;
			/*document.getElementById('techPackage').value = 'Included';
			document.getElementById('techPackageChecked').checked = true;*/
			document.getElementById('sms').value = 'Included';
			document.getElementById('smsChecked').checked = true;
			document.getElementById('email').value = 'Included';
			document.getElementById('emailChecked').checked = true;
			document.getElementById('item1Description').value = '£4,300 Training fee per event. FCA to subsidize £2,500';
			document.getElementById('item1Rate').value = '';
			document.getElementById('item2Description').value = 'All other charges are the responsibility of the Dealer';
			document.getElementById('publicNotes').value = 'A. 1000 MAILED INVITATIONS: Includes: Database Scrub & Analysis with Online Dealership Data Portal. Dealer\'s Choice of Invitations (with insert). Premium Coloured Envelope (hand addressed). First Class Stamp and Delivered to Royal Mail.\nB. TWO DAYS OF TELEPHONE TRAINING WITH BLITZ: Includes: Training Fees & Trainer Travel costs. Training Workbooks & Appointment Board & Script Sheets.\nC. DIGITAL MARKETING PACKAGE: Includes: Up to 2,500 Interactive SMS PLUS Up to 2,500 Personalized Emails. Event Website for Customers to RSVP.\nD. EVENT SALE DAY: Includes: Showroom Kit of up to 40 XL Posters, Customer Deal Jackets, Ballot Forms & Sold Signs. 75 Extra Invitations (to hand out in service or active prospects).';
		}
		else if(aType == 'FCA_uk3_2000')
		{
			document.getElementById('invitation').value = 'Included';
			document.getElementById('invitationChecked').checked = true;
			document.getElementById('training').value = '£4,700';
			document.getElementById('trainingChecked').checked = true;
			document.getElementById('travel').value = 'Included';
			document.getElementById('travelChecked').checked = true;
			document.getElementById('perCar').value = '£50';
			document.getElementById('perCarChecked').checked = true;
			document.getElementById('showroomKit').value = 'Included';
			document.getElementById('showroomKitChecked').checked = true;
			/*document.getElementById('techPackage').value = 'Included';
			document.getElementById('techPackageChecked').checked = true;*/
			document.getElementById('sms').value = 'Included';
			document.getElementById('smsChecked').checked = true;
			document.getElementById('email').value = 'Included';
			document.getElementById('emailChecked').checked = true;
			document.getElementById('item1Description').value = '£4,700 Training fee per event. FCA to subsidize £2,500';
			document.getElementById('item1Rate').value = '';
			document.getElementById('item2Description').value = 'All other charges are the responsibility of the Dealer';
			document.getElementById('publicNotes').value = 'A. 2000 MAILED INVITATIONS: Includes: Database Scrub & Analysis with Online Dealership Data Portal. Dealer\'s Choice of Invitations (with insert). Premium Coloured Envelope (hand addressed). First Class Stamp and Delivered to Royal Mail.\nB. TWO DAYS OF TELEPHONE TRAINING WITH BLITZ: Includes: Training Fees & Trainer Travel costs. Training Workbooks & Appointment Board & Script Sheets.\nC. DIGITAL MARKETING PACKAGE: Includes: Up to 2,500 Interactive SMS PLUS Up to 2,500 Personalized Emails. Event Website for Customers to RSVP.\nD. EVENT SALE DAY: Includes: Showroom Kit of up to 40 XL Posters, Customer Deal Jackets, Ballot Forms & Sold Signs. 75 Extra Invitations (to hand out in service or active prospects).';
		}
		else if(aType == 'NON_FCA_uk_5300_800')
		{
			document.getElementById('invitation').value = 'Included';
			document.getElementById('invitationChecked').checked = true;
			document.getElementById('training').value = 'Included';
			document.getElementById('trainingChecked').checked = true;
			document.getElementById('travel').value = 'Included';
			document.getElementById('travelChecked').checked = true;
			document.getElementById('showroomKit').value = 'Included';
			document.getElementById('showroomKitChecked').checked = true;
			/*document.getElementById('techPackage').value = 'Included';
			document.getElementById('techPackageChecked').checked = true;*/
			document.getElementById('sms').value = 'Included';
			document.getElementById('smsChecked').checked = true;
			document.getElementById('email').value = 'Included';
			document.getElementById('emailChecked').checked = true;
			document.getElementById('item1Description').value = 'Special Event Price ';
			document.getElementById('item1Rate').value = '£5,300';
			document.getElementById('publicNotes').value = 'A. 800 MAILED INVITATIONS: Includes: Database Scrub & Analysis with Online Dealership Data Portal. Dealer\'s Choice of Invitations (with insert). Premium Coloured Envelope (hand addressed). First Class Stamp and Delivered to Royal Mail.\n\nB. TWO DAYS OF TELEPHONE TRAINING WITH BLITZ: Includes: Training Fees & Trainer Travel costs. Training Workbooks & Appointment Board & Script Sheets.\n\nC. DIGITAL MARKETING PACKAGE: Includes: Up to 2,500 Interactive SMS PLUS Up to 2,500 Personalized Emails. Event Website for Customers to RSVP.\n\nD. EVENT SALE DAY: Includes: Showroom Kit of up to 40 XL Posters, Customer Deal Jackets, Ballot Forms & Sold Signs. 75 Extra Invitations (to hand out in service or active prospects).';
		}
		else if(aType == 'be3_500')
		{
			document.getElementById('invitation').value = 'Incl.';
			document.getElementById('invitationChecked').checked = true;
			document.getElementById('training').value = 'Incl.';
			document.getElementById('trainingChecked').checked = true;
			document.getElementById('travel').value = 'Incl.';
			document.getElementById('travelChecked').checked = true;
			document.getElementById('showroomKit').value = 'Incl.';
			document.getElementById('showroomKitChecked').checked = true;
			document.getElementById('sms').value = 'Incl.';
			document.getElementById('smsChecked').checked = true;
			document.getElementById('email').value = 'Incl.';
			document.getElementById('emailChecked').checked = true;
			document.getElementById('freight').value = 'Mailed Local';
			document.getElementById('freightChecked').checked = true;
			document.getElementById('item1Description').value = 'FCA Groupe - BELGIQUE';
			document.getElementById('item1Rate').value = '5250 Euros';
			document.getElementById('publicNotes').value = 'A. 500 MAILED INVITATIONS: Includes: Database Scrub & Analysis with Online Dealership Data Portal. Dealer\'s Choice of Invitations (with insert). Premium Coloured Envelope (hand addressed). First Class Stamp.\nB. TWO DAYS OF TELEPHONE TRAINING WITH BLITZ: Includes: Training Fees & Trainer Travel costs. Training Workbooks & Appointment Board & Script Sheets.\nC. DIGITAL MARKETING PACKAGE: Includes: Up to 2,500 Interactive SMS PLUS Up to 2,500 Personalized Emails. Event Website for Customers to RSVP.\nD. EVENT SALE DAY: Includes: Showroom Kit of up to 40 XL Posters, Customer Deal Jackets, Ballot Forms & Sold Signs. 100 Extra Invitations (to hand out in service or active prospects).';
		}
		//BELGIUM FCA 500
		else if(aType == 'be_fca_500')
		{
			document.getElementById('invitation').value = 'Incl.';
			document.getElementById('invitationChecked').checked = true;
			document.getElementById('numMailed').value = '500';
			document.getElementById('numMailedChecked').checked = true;
			document.getElementById('training').value = 'Incl.';
			document.getElementById('trainingChecked').checked = true;
			document.getElementById('travel').value = 'Incl.';
			document.getElementById('travelChecked').checked = true;
			document.getElementById('showroomKit').value = 'Incl.';
			document.getElementById('showroomKitChecked').checked = true;
			document.getElementById('freight').value = 'Poste locale';
			document.getElementById('freightChecked').checked = true;
			document.getElementById('item1Description').value = 'FCA Groupe - Belgique';
			document.getElementById('item1Rate').value = '5600 Euros';
			document.getElementById('website').value = 'fcaventeprivee.com';
		}
		//FRANCE FCA 500
		else if(aType == 'fr_fca_500')
		{
			document.getElementById('invitation').value = 'Incl.';
			document.getElementById('invitationChecked').checked = true;
			document.getElementById('numMailed').value = '500';
			document.getElementById('numMailedChecked').checked = true;
			document.getElementById('training').value = 'Incl.';
			document.getElementById('trainingChecked').checked = true;
			document.getElementById('travel').value = 'Incl.';
			document.getElementById('travelChecked').checked = true;
			document.getElementById('perCar').value = 'Incl.';
			document.getElementById('perCarChecked').checked = true;
			document.getElementById('showroomKit').value = 'Incl.';
			document.getElementById('showroomKitChecked').checked = true;
			document.getElementById('freight').value = 'Poste locale';
			document.getElementById('freightChecked').checked = true;
			document.getElementById('item1Description').value = 'FCA Groupe - France';
			document.getElementById('item1Rate').value = '5250 Euros';
			document.getElementById('website').value = 'fcaventeprivee.com';
		}
		else if(aType == 'au')
		{
			document.getElementById('invitation').value = '$2.95 AUD';
			document.getElementById('invitationChecked').checked = true;
			document.getElementById('postage').value = 'Included';
			document.getElementById('postageChecked').checked = true;
			document.getElementById('training').value = '$5500 AUD'
			document.getElementById('trainingChecked').checked = true;
			document.getElementById('travel').value = 'Included';
			document.getElementById('travelChecked').checked = true;
			document.getElementById('perCar').value = '$150 AUD';
			document.getElementById('perCarChecked').checked = true;
			document.getElementById('showroomKit').value = 'Included';
			document.getElementById('showroomKitChecked').checked = true;
			document.getElementById('sms').value = 'Included';
			document.getElementById('smsChecked').checked = true;
			document.getElementById('email').value = 'Included';
			document.getElementById('emailChecked').checked = true;
			document.getElementById('item1Description').value = '$5,500 AUD Training fee per event. FCA to subsidize $3500 AUD';
			document.getElementById('item1Rate').value = '';
			document.getElementById('item2Description').value = 'All other charges are the responsibility of the Dealer';
			document.getElementById('publicNotes').value = 'A. Up to 2000 MAILED INVITATIONS: Includes: Database Scrub & Analysis with Online Dealership Data Portal. Dealer\'s Choice of Invitations (with insert). Premium Coloured Envelope (hand addressed). First Class Stamp.\nB. TWO DAYS OF TELEPHONE TRAINING WITH BLITZ: Includes: Training Fees & Trainer Travel costs. Training Workbooks & Appointment Board & Script Sheets.\nC. DIGITAL MARKETING PACKAGE: Includes: Up to 2,500 Interactive SMS PLUS Up to 2,500 Personalized Emails. Event Website for Customers to RSVP. \nD. EVENT SALE DAY: Includes: Showroom Kit of up to 40 XL Posters, Customer Deal Jackets, Ballot Forms & Sold Signs. 100 Extra Invitations (to hand out in service or active prospects).';
		}
		else if(aType == 'nz_fca')
		{
			document.getElementById('invitation').value = '$3.15 NZD';
			document.getElementById('invitationChecked').checked = true;
			document.getElementById('postage').value = 'Included';
			document.getElementById('postageChecked').checked = true;
			document.getElementById('training').value = '$5950 NZD'
			document.getElementById('trainingChecked').checked = true;
			document.getElementById('travel').value = 'Included';
			document.getElementById('travelChecked').checked = true;
			document.getElementById('perCar').value = '$150 NZD';
			document.getElementById('perCarChecked').checked = true;
			document.getElementById('showroomKit').value = 'Included';
			document.getElementById('showroomKitChecked').checked = true;
			document.getElementById('sms').value = 'Included';
			document.getElementById('smsChecked').checked = true;
			document.getElementById('email').value = 'Included';
			document.getElementById('emailChecked').checked = true;
			document.getElementById('item1Description').value = '$5,950 NZD Training fee per event.';
			document.getElementById('item1Rate').value = '';
			document.getElementById('item2Description').value = 'All other charges are the responsibility of the Dealer';
			document.getElementById('publicNotes').value = 'A. Up to 1000 MAILED INVITATIONS: Includes: Database Scrub & Analysis with Online Dealership Data Portal. Dealer\'s Choice of Invitations (with insert). Premium Coloured Envelope (hand addressed). First Class Stamp.\nB. TWO DAYS OF TELEPHONE TRAINING WITH BLITZ: Includes: Training Fees & Trainer Travel costs. Training Workbooks & Appointment Board & Script Sheets.\nC. DIGITAL MARKETING PACKAGE: Includes: Up to 2,500 Interactive SMS PLUS Up to 2,500 Personalized Emails. Event Website for Customers to RSVP. \nD. EVENT SALE DAY: Includes: Showroom Kit of up to 40 XL Posters, Customer Deal Jackets, Ballot Forms & Sold Signs. 100 Extra Invitations (to hand out in service or active prospects).';
		}
		else if(aType == 'nz_non_fca')
		{
			document.getElementById('invitation').value = '$3.15 NZD';
			document.getElementById('invitationChecked').checked = true;
			document.getElementById('postage').value = 'Included';
			document.getElementById('postageChecked').checked = true;
			document.getElementById('training').value = '$5950 NZD'
			document.getElementById('trainingChecked').checked = true;
			document.getElementById('travel').value = 'Included';
			document.getElementById('travelChecked').checked = true;
			document.getElementById('perCar').value = '$175 NZD per car domestic, $250 NZD per car luxury';
			document.getElementById('perCarChecked').checked = true;
			document.getElementById('showroomKit').value = 'Included';
			document.getElementById('showroomKitChecked').checked = true;
			document.getElementById('sms').value = 'Included';
			document.getElementById('smsChecked').checked = true;
			document.getElementById('email').value = 'Included';
			document.getElementById('emailChecked').checked = true;
			document.getElementById('item1Description').value = '$5,950 NZD Training fee per event.';
			document.getElementById('item1Rate').value = '';
			document.getElementById('item2Description').value = 'All other charges are the responsibility of the Dealer';
			document.getElementById('publicNotes').value = 'A. Up to 1000 MAILED INVITATIONS: Includes: Database Scrub & Analysis with Online Dealership Data Portal. Dealer\'s Choice of Invitations (with insert). Premium Coloured Envelope (hand addressed). First Class Stamp.\nB. TWO DAYS OF TELEPHONE TRAINING WITH BLITZ: Includes: Training Fees & Trainer Travel costs. Training Workbooks & Appointment Board & Script Sheets.\nC. DIGITAL MARKETING PACKAGE: Includes: Up to 2,500 Interactive SMS PLUS Up to 2,500 Personalized Emails. Event Website for Customers to RSVP. \nD. EVENT SALE DAY: Includes: Showroom Kit of up to 40 XL Posters, Customer Deal Jackets, Ballot Forms & Sold Signs. 100 Extra Invitations (to hand out in service or active prospects).';
		}
		else if(aType == 'jeepcall')
		{
			document.getElementById('arc').value = '2.95';
			document.getElementById('arcChecked').checked = true;
			document.getElementById('numCalls').value = 'TBD';
			document.getElementById('numCallsChecked').checked = true;
			document.getElementById('item1Description').value = 'Data Cleanup of Manifest List';
			document.getElementById('item1Rate').value = '$295';
			document.getElementById('item2Description').value = 'Calls include 3 attempts';
			document.getElementById('publicNotes').value = 'Jeep Identity Call Campaign';
		}
		else if(aType == 'pullahead1')
		{
			document.getElementById('arc').value = '3.50';
			document.getElementById('arcChecked').checked = true;
			document.getElementById('numCalls').value = 'TBD';
			document.getElementById('numCallsChecked').checked = true;

			if(document.getElementById('isFrench').selected) document.getElementById('item1Description').value = 'Inclus 2 appels direct pour chaque client qualifié';
			else document.getElementById('item1Description').value = 'Includes 2 live appointment phone calls for every qualifying customer';
		}
		else if(aType == 'pullahead2')
		{
			document.getElementById('invitation').value = '1.49';
			document.getElementById('invitationChecked').checked = true;
			document.getElementById('postage').value = '0.65';
			document.getElementById('postageChecked').checked = true;
			document.getElementById('voicecast').value = 'Included';
			document.getElementById('voicecastChecked').checked = true;
			document.getElementById('sms').value = 'Included';
			document.getElementById('smsChecked').checked = true;
			document.getElementById('email').value = 'Included';
			document.getElementById('emailChecked').checked = true;
			document.getElementById('techPackage').value = '595';
			document.getElementById('techPackageChecked').checked = true;
			document.getElementById('arc').value = '3.95';
			document.getElementById('arcChecked').checked = true;
			document.getElementById('numCalls').value = 'TBD';
			document.getElementById('numCallsChecked').checked = true;
			if(document.getElementById('isFrench').selected)
			{
				document.getElementById('website').value = 'www.ChryslerRetourAnticipe.com';
				document.getElementById('item1Description').value = 'Inclus 3 appels direct pour chaque client qualifié';
				document.getElementById('item2Description').value = 'Inclus www.ChryslerRetourAnticipe.com.com';
			}
			else
			{
				document.getElementById('website').value = 'www.ChryslerPullAhead.com';
				document.getElementById('item1Description').value = 'Includes 3 live appointment phone calls for every qualifying customer ';
				document.getElementById('item2Description').value = 'Includes RSVP website www.ChryslerPullAhead.com';
			}
		}
		else if (aType == 'hd_package_a')
		{
			document.getElementById('invitation').value = 'Included';
			document.getElementById('invitationChecked').checked = true;
			document.getElementById('postage').value = 'Included';
			document.getElementById('postageChecked').checked = true;
			document.getElementById('numMailed').value = '500';
			document.getElementById('numMailedChecked').checked = true;
			document.getElementById('training').value = 'Included';
			document.getElementById('trainingChecked').checked = true;
			document.getElementById('travel').value = 'Included';
			document.getElementById('travelChecked').checked = true;
			document.getElementById('voicecast').value = 'Included';
			document.getElementById('voicecastChecked').checked = true;
			document.getElementById('sms').value = 'Included';
			document.getElementById('smsChecked').checked = true;
			document.getElementById('email').value = 'Included';
			document.getElementById('emailChecked').checked = true;
			document.getElementById('techPackage').value = 'Included';
			document.getElementById('techPackageChecked').checked = true;
			document.getElementById('showroomKit').value = 'Included';
			document.getElementById('showroomKitChecked').checked = true;
			document.getElementById('item1Description').value = 'Package A Total';
			document.getElementById('item1Rate').value = '7495';
		}
		else if (aType == 'hd_package_b')
		{
			document.getElementById('invitation').value = 'Included';
			document.getElementById('invitationChecked').checked = true;
			document.getElementById('postage').value = 'Included';
			document.getElementById('postageChecked').checked = true;
			document.getElementById('numMailed').value = '300';
			document.getElementById('numMailedChecked').checked = true;
			document.getElementById('training').value = 'Included';
			document.getElementById('trainingChecked').checked = true;
			document.getElementById('travel').value = 'Included';
			document.getElementById('travelChecked').checked = true;
			document.getElementById('voicecast').value = 'Included';
			document.getElementById('voicecastChecked').checked = true;
			document.getElementById('sms').value = 'Included';
			document.getElementById('smsChecked').checked = true;
			document.getElementById('email').value = 'Included';
			document.getElementById('emailChecked').checked = true;
			document.getElementById('techPackage').value = 'Included';
			document.getElementById('techPackageChecked').checked = true;
			document.getElementById('showroomKit').value = 'Included';
			document.getElementById('showroomKitChecked').checked = true;
			document.getElementById('item1Description').value = 'Package B Total';
			document.getElementById('item1Rate').value = '5595';
		}
		else if (aType == 'ca_nonfca_a')
		{
			document.getElementById('invitation').value = 'Included';
			document.getElementById('invitationChecked').checked = true;
			document.getElementById('postage').value = 'Included';
			document.getElementById('postageChecked').checked = true;
			document.getElementById('numMailed').value = '500';
			document.getElementById('numMailedChecked').checked = true;
			document.getElementById('training').value = 'Included';
			document.getElementById('trainingChecked').checked = true;
			document.getElementById('travel').value = 'Included';
			document.getElementById('travelChecked').checked = true;
			document.getElementById('perCar').value = '150';
			document.getElementById('perCarChecked').checked = true;
			document.getElementById('voicecast').value = 'Included';
			document.getElementById('voicecastChecked').checked = true;
			document.getElementById('sms').value = 'Included';
			document.getElementById('smsChecked').checked = true;
			document.getElementById('email').value = 'Included';
			document.getElementById('emailChecked').checked = true;
			document.getElementById('techPackage').value = 'Included';
			document.getElementById('techPackageChecked').checked = true;
			document.getElementById('showroomKit').value = 'Included';
			document.getElementById('showroomKitChecked').checked = true;
			document.getElementById('item1Description').value = 'Includes 10,000 Flyers';
			document.getElementById('item2Description').value = 'Package A Special Pricing';
			document.getElementById('item2Rate').value = '6,750';
		}
		else if (aType == 'ca_nonfca_b')
		{
			document.getElementById('invitation').value = 'Included';
			document.getElementById('invitationChecked').checked = true;
			document.getElementById('postage').value = 'Included';
			document.getElementById('postageChecked').checked = true;
			document.getElementById('numMailed').value = '800';
			document.getElementById('numMailedChecked').checked = true;
			document.getElementById('training').value = 'Included';
			document.getElementById('trainingChecked').checked = true;
			document.getElementById('travel').value = 'Included';
			document.getElementById('travelChecked').checked = true;
			document.getElementById('perCar').value = '150';
			document.getElementById('perCarChecked').checked = true;
			document.getElementById('voicecast').value = 'Included';
			document.getElementById('voicecastChecked').checked = true;
			document.getElementById('sms').value = 'Included';
			document.getElementById('smsChecked').checked = true;
			document.getElementById('email').value = 'Included';
			document.getElementById('emailChecked').checked = true;
			document.getElementById('techPackage').value = 'Included';
			document.getElementById('techPackageChecked').checked = true;
			document.getElementById('showroomKit').value = 'Included';
			document.getElementById('showroomKitChecked').checked = true;
			document.getElementById('item1Description').value = 'Includes 15,000 Flyers';
			document.getElementById('item2Description').value = 'Package B Special Pricing';
			document.getElementById('item2Rate').value = '8,750';
		}
		else if (aType == 'ca_nonfca_c')
		{
			document.getElementById('invitation').value = 'Included';
			document.getElementById('invitationChecked').checked = true;
			document.getElementById('postage').value = 'Included';
			document.getElementById('postageChecked').checked = true;
			document.getElementById('numMailed').value = '1000';
			document.getElementById('numMailedChecked').checked = true;
			document.getElementById('training').value = 'Included';
			document.getElementById('trainingChecked').checked = true;
			document.getElementById('travel').value = 'Included';
			document.getElementById('travelChecked').checked = true;
			document.getElementById('perCar').value = '150';
			document.getElementById('perCarChecked').checked = true;
			document.getElementById('voicecast').value = 'Included';
			document.getElementById('voicecastChecked').checked = true;
			document.getElementById('sms').value = 'Included';
			document.getElementById('smsChecked').checked = true;
			document.getElementById('email').value = 'Included';
			document.getElementById('emailChecked').checked = true;
			document.getElementById('techPackage').value = 'Included';
			document.getElementById('techPackageChecked').checked = true;
			document.getElementById('showroomKit').value = 'Included';
			document.getElementById('showroomKitChecked').checked = true;
			document.getElementById('item1Description').value = 'Includes 20,000 Flyers';
			document.getElementById('item2Description').value = 'Package C Special Pricing';
			document.getElementById('item2Rate').value = '11,550';
		}
		else if (aType == 'ca_nonfca_d')
		{
			document.getElementById('invitation').value = 'Included';
			document.getElementById('invitationChecked').checked = true;
			document.getElementById('postage').value = 'Included';
			document.getElementById('postageChecked').checked = true;
			document.getElementById('numMailed').value = '1200';
			document.getElementById('numMailedChecked').checked = true;
			document.getElementById('training').value = 'Included';
			document.getElementById('trainingChecked').checked = true;
			document.getElementById('travel').value = 'Included';
			document.getElementById('travelChecked').checked = true;
			document.getElementById('perCar').value = '150';
			document.getElementById('perCarChecked').checked = true;
			document.getElementById('voicecast').value = 'Included';
			document.getElementById('voicecastChecked').checked = true;
			document.getElementById('sms').value = 'Included';
			document.getElementById('smsChecked').checked = true;
			document.getElementById('email').value = 'Included';
			document.getElementById('emailChecked').checked = true;
			document.getElementById('techPackage').value = 'Included';
			document.getElementById('techPackageChecked').checked = true;
			document.getElementById('showroomKit').value = 'Included';
			document.getElementById('showroomKitChecked').checked = true;
			document.getElementById('item1Description').value = 'Includes 25,000 Flyers';
			document.getElementById('item2Description').value = 'Package D Special Pricing';
			document.getElementById('item2Rate').value = '13,150';
		}
		else if (aType == 'ca_nonfca_nissan_special')
		{
			document.getElementById('invitation').value = '1.63';
			document.getElementById('invitationChecked').checked = true;
			document.getElementById('postage').value = '0.85';
			document.getElementById('postageChecked').checked = true;
			document.getElementById('training').value = '2500';
			document.getElementById('trainingChecked').checked = true;
			document.getElementById('travel').value = '1000';
			document.getElementById('travelChecked').checked = true;
			document.getElementById('perCar').value = '150';
			document.getElementById('perCarChecked').checked = true;
			document.getElementById('voicecast').value = 'Included';
			document.getElementById('voicecastChecked').checked = true;
			document.getElementById('sms').value = 'Included';
			document.getElementById('smsChecked').checked = true;
			document.getElementById('email').value = 'Included';
			document.getElementById('emailChecked').checked = true;
			document.getElementById('techPackage').value = 'Included';
			document.getElementById('techPackageChecked').checked = true;
			document.getElementById('showroomKit').value = '295';
			document.getElementById('showroomKitChecked').checked = true;
		}
		else if (aType == 'mazda_ca_a')
		{
			document.getElementById('invitation').value = 'Included';
			document.getElementById('invitationChecked').checked = true;
			document.getElementById('postage').value = 'Included';
			document.getElementById('postageChecked').checked = true;
			document.getElementById('numMailed').value = '500';
			document.getElementById('numMailedChecked').checked = true;
			document.getElementById('training').value = 'Included';
			document.getElementById('trainingChecked').checked = true;
			document.getElementById('travel').value = 'Included';
			document.getElementById('travelChecked').checked = true;
			document.getElementById('perCar').value = '150';
			document.getElementById('perCarChecked').checked = true;
			document.getElementById('voicecast').value = 'Included';
			document.getElementById('voicecastChecked').checked = true;
			document.getElementById('sms').value = 'Included';
			document.getElementById('smsChecked').checked = true;
			document.getElementById('email').value = 'Included';
			document.getElementById('emailChecked').checked = true;
			document.getElementById('techPackage').value = 'Included';
			document.getElementById('techPackageChecked').checked = true;
			document.getElementById('showroomKit').value = 'Included';
			document.getElementById('showroomKitChecked').checked = true;
			document.getElementById('item1Description').value = 'Retail Value $10,445 (SAVE $1000)';
			document.getElementById('item2Description').value = 'Package A Special Pricing';
			document.getElementById('item2Rate').value = '9,445';
		}
		else if (aType == 'mazda_ca_b')
		{
			document.getElementById('invitation').value = 'Included';
			document.getElementById('invitationChecked').checked = true;
			document.getElementById('postage').value = 'Included';
			document.getElementById('postageChecked').checked = true;
			document.getElementById('numMailed').value = '800';
			document.getElementById('numMailedChecked').checked = true;
			document.getElementById('training').value = 'Included';
			document.getElementById('trainingChecked').checked = true;
			document.getElementById('travel').value = 'Included';
			document.getElementById('travelChecked').checked = true;
			document.getElementById('perCar').value = '150';
			document.getElementById('perCarChecked').checked = true;
			document.getElementById('voicecast').value = 'Included';
			document.getElementById('voicecastChecked').checked = true;
			document.getElementById('sms').value = 'Included';
			document.getElementById('smsChecked').checked = true;
			document.getElementById('email').value = 'Included';
			document.getElementById('emailChecked').checked = true;
			document.getElementById('techPackage').value = 'Included';
			document.getElementById('techPackageChecked').checked = true;
			document.getElementById('showroomKit').value = 'Included';
			document.getElementById('showroomKitChecked').checked = true;
			document.getElementById('item1Description').value = 'Retail Value $14,987 (SAVE $3,200)';
			document.getElementById('item2Description').value = 'Package B Special Pricing';
			document.getElementById('item2Rate').value = '11,787';
		}
		else if (aType == 'mazda_ca_c')
		{
			document.getElementById('invitation').value = 'Included';
			document.getElementById('invitationChecked').checked = true;
			document.getElementById('postage').value = 'Included';
			document.getElementById('postageChecked').checked = true;
			document.getElementById('numMailed').value = '1200';
			document.getElementById('numMailedChecked').checked = true;
			document.getElementById('training').value = 'Included';
			document.getElementById('trainingChecked').checked = true;
			document.getElementById('travel').value = 'Included';
			document.getElementById('travelChecked').checked = true;
			document.getElementById('perCar').value = '150';
			document.getElementById('perCarChecked').checked = true;
			document.getElementById('voicecast').value = 'Included';
			document.getElementById('voicecastChecked').checked = true;
			document.getElementById('sms').value = 'Included';
			document.getElementById('smsChecked').checked = true;
			document.getElementById('email').value = 'Included';
			document.getElementById('emailChecked').checked = true;
			document.getElementById('techPackage').value = 'Included';
			document.getElementById('techPackageChecked').checked = true;
			document.getElementById('showroomKit').value = 'Included';
			document.getElementById('showroomKitChecked').checked = true;
			document.getElementById('item1Description').value = 'Retail Value $20,238 (SAVE $3,600)';
			document.getElementById('item2Description').value = 'Package C Special Pricing';
			document.getElementById('item2Rate').value = '16,638';
		}
		else if (aType == 'nonprime')
		{
			document.getElementById('item1Rate').value = '7500';
			document.getElementById('item1Description').value = 'Monthly management Fee and Trainer';

			document.getElementById('item2Rate').value = '119';
			document.getElementById('item2Description').value = 'Pre-paid Lead(s) to be added to your account';

			document.getElementById('item3Rate').value = '500';
			document.getElementById('item3Description').value = 'Price per Vehicle Sold from previous month';
		}
		else if (aType == 'nonprime_fr')
		{
			document.getElementById('item1Rate').value = '7500';
			document.getElementById('item1Description').value = 'Frais d’administration mensuel et formateur';

			document.getElementById('item2Rate').value = '119';
			document.getElementById('item2Description').value = 'Prospect(s) prépayé(s) à être ajouté(s) à votre compte';

			document.getElementById('item3Rate').value = '500';
			document.getElementById('item3Description').value = 'Prix pour chaque véhicule vendu au cours du mois précédent';
		}
		else if(aType.search('ca_fca_package_') !== -1 && aType.search('invites') !== -1)
		{
			document.getElementById('invitation').value = 'Included';
			document.getElementById('invitationChecked').checked = true;
			document.getElementById('postage').value = 'Included';
			document.getElementById('postageChecked').checked = true;
			document.getElementById('numMailedChecked').checked = true;
			document.getElementById('training').value = '2000';
			document.getElementById('trainingChecked').checked = true;
			document.getElementById('travel').value = '1000';
			document.getElementById('travelChecked').checked = true;
			document.getElementById('perCar').value = '150';
			document.getElementById('perCarChecked').checked = true;
			document.getElementById('freight').value = 'Included';
			document.getElementById('freightChecked').checked = true;
			document.getElementById('showroomKit').value = 'Included';
			document.getElementById('showroomKitChecked').checked = true;
			document.getElementById('techPackage').value = 'Included';
			document.getElementById('techPackageChecked').checked = true;
			document.getElementById('dataScrubbing').value = 'Included';
			document.getElementById('dataScrubbingChecked').checked = true;

			if(aType == 'ca_fca_package_mini_all_invites') $('#numMailed').val('500');
			else if(aType == 'ca_fca_package_a_all_invites') $('#numMailed').val('500');
			else if(aType == 'ca_fca_package_b_all_invites') $('#numMailed').val('500');
			else if(aType == 'ca_fca_package_c_all_invites') $('#numMailed').val('500');
			else if(aType == 'ca_fca_package_d_all_invites') $('#numMailed').val('500');
		}
		else if(aType.search('ca_fca_package_') !== -1)
		{
			document.getElementById('conquest').value = 'Included';
			document.getElementById('conquestChecked').checked = true;
			document.getElementById('numMailedChecked').checked = true;

			if(aType.search('ca_fca_package_mini') !== -1) {
				document.getElementById('numMailed').value = '5000';
				$('#item1Rate').val('5700');
				document.getElementById('item1Description').value = 'Package Mini Total';
			}
			else if(aType.search('ca_fca_package_a') !== -1) {
				document.getElementById('numMailed').value = '5000';
				$('#item1Rate').val('4600');
				document.getElementById('item1Description').value = 'Package A Total';
			}
			else if(aType.search('ca_fca_package_b') !== -1) {
				document.getElementById('numMailed').value = '10000';
				$('#item1Rate').val('6200');
				document.getElementById('item1Description').value = 'Package B Total';
			}
			else if(aType.search('ca_fca_package_c') !== -1) {
				document.getElementById('numMailed').value = '15000';
				$('#item1Rate').val('8200');
				document.getElementById('item1Description').value = 'Package C Total';
			}
			else if(aType.search('ca_fca_package_d') !== -1) {
				document.getElementById('numMailed').value = '20000';
				$('#item1Rate').val('10300');
				document.getElementById('item1Description').value = 'Package D Total';
			}
			else if(aType.search('ca_fca_package_ip') !== -1) {
				document.getElementById('numMailed').value = '25000';
				document.getElementById('item1Description').value = 'Package D Total';
			}
			else if(aType.search('ca_fca_package_cbb') !== -1) {
				document.getElementById('numMailed').value = '25000';
				document.getElementById('item1Description').value = 'Package D Total';
			}

			/*if(aType == 'ca_fca_package_mini_8.5x11_1_event')
				$('#item1Rate').val('5400');
			else if(aType == 'ca_fca_package_mini_8.5x11_2+_event')
				$('#item1Rate').val('5100');
			else if(aType == 'ca_fca_package_mini_11x17_1_event')
				$('#item1Rate').val('5900');
			else if(aType == 'ca_fca_package_mini_11x17_2+_event')
				$('#item1Rate').val('5400');
			else if(aType == 'ca_fca_package_mini_17x22_1_event')
				$('#item1Rate').val('6400');
			else if(aType == 'ca_fca_package_mini_17x22_2+_event')
				$('#item1Rate').val('5900');
			else if(aType == 'ca_fca_package_a_8.5x11_1_event')
				$('#item1Rate').val('6500');
			else if(aType == 'ca_fca_package_a_8.5x11_2+_event')
				$('#item1Rate').val('6350');
			else if(aType == 'ca_fca_package_a_11x17_1_event')
				$('#item1Rate').val('6750');
			else if(aType == 'ca_fca_package_a_11x17_2+_event')
				$('#item1Rate').val('6700');
			else if(aType == 'ca_fca_package_a_17x22_1_event')
				$('#item1Rate').val('7800');
			else if(aType == 'ca_fca_package_a_17x22_2+_event')
				$('#item1Rate').val('7300');
			else if(aType == 'ca_fca_package_b_8.5x11_1_event')
				$('#item1Rate').val('8750');
			else if(aType == 'ca_fca_package_b_8.5x11_2+_event')
				$('#item1Rate').val('8400');
			else if(aType == 'ca_fca_package_b_11x17_1_event')
				$('#item1Rate').val('8750');
			else if(aType == 'ca_fca_package_b_11x17_2+_event')
				$('#item1Rate').val('8700');
			else if(aType == 'ca_fca_package_b_17x22_1_event')
				$('#item1Rate').val('10400');
			else if(aType == 'ca_fca_package_b_17x22_2+_event')
				$('#item1Rate').val('9650');
			else if(aType == 'ca_fca_package_c_8.5x11_1_event')
				$('#item1Rate').val('10500');
			else if(aType == 'ca_fca_package_c_8.5x11_2+_event')
				$('#item1Rate').val('10300');
			else if(aType == 'ca_fca_package_c_11x17_1_event')
				$('#item1Rate').val('11550');
			else if(aType == 'ca_fca_package_c_11x17_2+_event')
				$('#item1Rate').val('11050');
			else if(aType == 'ca_fca_package_c_17x22_1_event')
				$('#item1Rate').val('13350');
			else if(aType == 'ca_fca_package_c_17x22_2+_event')
				$('#item1Rate').val('12550');
			else if(aType == 'ca_fca_package_d_8.5x11_1_event')
				$('#item1Rate').val('12000');
			else if(aType == 'ca_fca_package_d_8.5x11_2+_event')
				$('#item1Rate').val('11500');
			else if(aType == 'ca_fca_package_d_11x17_1_event')
				$('#item1Rate').val('13150');
			else if(aType == 'ca_fca_package_d_11x17_2+_event')
				$('#item1Rate').val('12650');
			else if(aType == 'ca_fca_package_d_17x22_1_event')
				$('#item1Rate').val('16150');
			else if(aType == 'ca_fca_package_d_17x22_2+_event')
				$('#item1Rate').val('15150');*/

		}
		else if(aType.search('ca_ip_package_') !== -1 && aType.search('invites') !== -1)
		{
			document.getElementById('invitation').value = 'Included';
			document.getElementById('invitationChecked').checked = true;
			document.getElementById('postage').value = 'Included';
			document.getElementById('postageChecked').checked = true;
			document.getElementById('numMailedChecked').checked = true;
			document.getElementById('training').value = '2000';
			document.getElementById('trainingChecked').checked = true;
			document.getElementById('travel').value = '1000';
			document.getElementById('travelChecked').checked = true;
			document.getElementById('perCar').value = '150';
			document.getElementById('perCarChecked').checked = true;
			document.getElementById('freight').value = 'Included';
			document.getElementById('freightChecked').checked = true;
			document.getElementById('showroomKit').value = 'Included';
			document.getElementById('showroomKitChecked').checked = true;
			document.getElementById('techPackage').value = 'Included';
			document.getElementById('techPackageChecked').checked = true;
			document.getElementById('dataScrubbing').value = 'Included';
			document.getElementById('dataScrubbingChecked').checked = true;

			if(aType == 'ca_ip_package_a_all_invites') $('#numMailed').val('500');
			else if(aType == 'ca_ip_package_b_all_invites') $('#numMailed').val('500');
			else if(aType == 'ca_ip_package_c_all_invites') $('#numMailed').val('500');
			else if(aType == 'ca_ip_package_d_all_invites') $('#numMailed').val('500');
		}
		else if(aType.search('ca_ip_package_') !== -1)
		{
			document.getElementById('conquest').value = 'Included';
			document.getElementById('conquestChecked').checked = true;
			document.getElementById('numMailedChecked').checked = true;

			if(aType == 'ca_ip_package_a_11x17') {
				$('#item1Rate').val('6750');
				$('#item1Description').val('Package A Total');
				$('#numMailed').val('10000');
			}
			else if(aType == 'ca_ip_package_b_11x17') {
				$('#item1Rate').val('8900');
				$('#item1Description').val('Package B Total');
				$('#numMailed').val('15000');
			}
			else if(aType == 'ca_ip_package_c_11x17') {
				$('#item1Rate').val('10500');
				$('#item1Description').val('Package C Total');
				$('#numMailed').val('20000');
			}
			else if(aType == 'ca_ip_package_d_11x17') {
				$('#item1Rate').val('12300');
				$('#item1Description').val('Package D Total');
				$('#numMailed').val('25000');
			}
		}
		else if(aType.search('ca_cbb_package_') !== -1 && aType.search('invites') !== -1)
		{
			document.getElementById('invitation').value = 'Included';
			document.getElementById('invitationChecked').checked = true;
			document.getElementById('postage').value = 'Included';
			document.getElementById('postageChecked').checked = true;
			document.getElementById('numMailedChecked').checked = true;
			document.getElementById('training').value = '2000';
			document.getElementById('trainingChecked').checked = true;
			document.getElementById('travel').value = '1000';
			document.getElementById('travelChecked').checked = true;
			document.getElementById('perCar').value = '150';
			document.getElementById('perCarChecked').checked = true;
			document.getElementById('freight').value = 'Included';
			document.getElementById('freightChecked').checked = true;
			document.getElementById('showroomKit').value = 'Included';
			document.getElementById('showroomKitChecked').checked = true;
			document.getElementById('techPackage').value = 'Included';
			document.getElementById('techPackageChecked').checked = true;
			document.getElementById('dataScrubbing').value = 'Included';
			document.getElementById('dataScrubbingChecked').checked = true;

			if(aType == 'ca_cbb_package_a_all_invites') $('#numMailed').val('500');
			else if(aType == 'ca_cbb_package_b_all_invites') $('#numMailed').val('500');
			else if(aType == 'ca_cbb_package_c_all_invites') $('#numMailed').val('500');
			else if(aType == 'ca_cbb_package_d_all_invites') $('#numMailed').val('500');
		}
		else if(aType.search('ca_cbb_package_') !== -1)
		{
			document.getElementById('conquest').value = 'Included';
			document.getElementById('conquestChecked').checked = true;
			document.getElementById('numMailedChecked').checked = true;

			if(aType == 'ca_cbb_package_a_11x17') {
				$('#item1Rate').val('6750');
				$('#item1Description').val('Package A Total');
				$('#numMailed').val('10000');
			}
			else if(aType == 'ca_cbb_package_b_11x17') {
				$('#item1Rate').val('8900');
				$('#item1Description').val('Package B Total');
				$('#numMailed').val('15000');
			}
			else if(aType == 'ca_cbb_package_c_11x17') {
				$('#item1Rate').val('10500');
				$('#item1Description').val('Package C Total');
				$('#numMailed').val('20000');
			}
			else if(aType == 'ca_cbb_package_d_11x17') {
				$('#item1Rate').val('12300');
				$('#item1Description').val('Package D Total');
				$('#numMailed').val('25000');
			}
		}
		else if(aType.search('us_fca_package_') !== -1)
		{
			document.getElementById('invitationChecked').checked = true;
			document.getElementById('postage').value = '0.490';
			document.getElementById('postageChecked').checked = true;
			document.getElementById('numMailedChecked').checked = true;
			document.getElementById('training').value = 'Included';
			document.getElementById('trainingChecked').checked = true;
			document.getElementById('travel').value = 'Included';
			document.getElementById('travelChecked').checked = true;
			document.getElementById('freight').value = 'Mailed Local';
			document.getElementById('freightChecked').checked = true;
			document.getElementById('showroomKit').value = 'Included';
			document.getElementById('showroomKitChecked').checked = true;
			document.getElementById('email').value = 'Included';
			document.getElementById('emailChecked').checked = true;
			document.getElementById('techPackage').value = 'Included';
			document.getElementById('techPackageChecked').checked = true;
			document.getElementById('dataScrubbing').value = 'Included';
			document.getElementById('dataScrubbingChecked').checked = true;

			if(aType.search('_arc') !== -1) {
				$('#arc').val('Included');
				$('#arcChecked').prop('checked', true);
			}

			if(aType == 'us_fca_package_intro') {
				$('#invitation').val('10.8');
				$('#item1Rate').val('5645');
				$('#item1Description').val('Package Intro Total');
				$('#numMailed').val('500');
			}
			else if(aType == 'us_fca_package_nano') {
				$('#invitation').val('6.505');
				$('#item1Rate').val('6995');
				$('#item1Description').val('Package Nano Total');
				$('#numMailed').val('1000');
			}
			else if(aType == 'us_fca_package_a') {
				$('#invitation').val('4.707');
				$('#item1Rate').val('7795');
				$('#item1Description').val('Package A Total');
				$('#numMailed').val('1500');
			}
			else if(aType == 'us_fca_package_b') {
				$('#invitation').val('3.168');
				$('#item1Rate').val('9145');
				$('#item1Description').val('Package B Total');
				$('#numMailed').val('2500');
			}
			else if(aType == 'us_fca_package_c') {
				$('#invitation').val('2.221');
				$('#item1Rate').val('10845');
				$('#item1Description').val('Package C Total');
				$('#numMailed').val('4000');
			}
			else if(aType == 'us_fca_package_large') {
				$('#invitation').val('1.889');
				$('#item1Rate').val('11895');
				$('#item1Description').val('Package Large Total');
				$('#numMailed').val('5000');
			}
			else if(aType == 'us_fca_package_intro_arc') {
				$('#invitation').val('');
				$('#item1Rate').val('6195');
				$('#item1Description').val('Package Intro & ARC Total');
				$('#numMailed').val('500');

				$('#numCalls').val('250');
				$('#numCallsChecked').prop('checked', true);
			}
			else if(aType == 'us_fca_package_nano_arc') {
				$('#invitation').val('7.505');
				$('#item1Rate').val('7995');
				$('#item1Description').val('Package Nano & ARC Total');
				$('#numMailed').val('1000');

				$('#numCalls').val('500');
				$('#numCallsChecked').prop('checked', true);
			}
			else if(aType == 'us_fca_package_2000_arc') {
				$('#invitation').val('4.808');
				$('#item1Rate').val('10595');
				$('#item1Description').val('Package 2000 & ARC Total');
				$('#numMailed').val('2000');

				$('#numCalls').val('1000');
				$('#numCallsChecked').prop('checked', true);
			}
			else if(aType == 'us_fca_package_3000_arc') {
				$('#invitation').val('3.842');
				$('#item1Rate').val('12995');
				$('#item1Description').val('Package 3000 & ARC Total');
				$('#numMailed').val('3000');

				$('#numCalls').val('1500');
				$('#numCallsChecked').prop('checked', true);
			}
		}
		else if(aType.search('us_nonfca_package_') !== -1)
		{
			document.getElementById('invitationChecked').checked = true;
			document.getElementById('postage').value = '0.490';
			document.getElementById('postageChecked').checked = true;
			document.getElementById('numMailedChecked').checked = true;
			document.getElementById('training').value = 'Included';
			document.getElementById('trainingChecked').checked = true;
			document.getElementById('travel').value = 'Included';
			document.getElementById('travelChecked').checked = true;
			document.getElementById('freight').value = 'Mailed Local';
			document.getElementById('freightChecked').checked = true;
			document.getElementById('showroomKit').value = 'Included';
			document.getElementById('showroomKitChecked').checked = true;
			document.getElementById('email').value = 'Included';
			document.getElementById('emailChecked').checked = true;
			document.getElementById('techPackage').value = 'Included';
			document.getElementById('techPackageChecked').checked = true;
			document.getElementById('dataScrubbing').value = 'Included';
			document.getElementById('dataScrubbingChecked').checked = true;

			if(aType == 'us_nonfca_package_intro') {
				$('#invitation').val('11.8');
				$('#item1Rate').val('6145');
				$('#item1Description').val('Package Intro Total');
				$('#numMailed').val('500');
			}
			else if(aType == 'us_nonfca_package_nano') {
				$('#invitation').val('7.008');
				$('#item1Rate').val('7498');
				$('#item1Description').val('Package Nano Total');
				$('#numMailed').val('1000');
			}
			else if(aType == 'us_nonfca_package_a') {
				$('#invitation').val('5.04');
				$('#item1Rate').val('8295');
				$('#item1Description').val('Package A Total');
				$('#numMailed').val('1500');
			}
			else if(aType == 'us_nonfca_package_b') {
				$('#invitation').val('3.368');
				$('#item1Rate').val('9645');
				$('#item1Description').val('Package B Total');
				$('#numMailed').val('2500');
			}
			else if(aType == 'us_nonfca_package_c') {
				$('#invitation').val('2.346');
				$('#item1Rate').val('11345');
				$('#item1Description').val('Package C Total');
				$('#numMailed').val('4000');
			}
			else if(aType == 'us_nonfca_package_large') {
				$('#invitation').val('1.989');
				$('#item1Rate').val('12395');
				$('#item1Description').val('Package Large Total');
				$('#numMailed').val('5000');
			}
		}
		else if(aType.search('us_gm_package_') !== -1)
		{
			document.getElementById('invitation').value = 'Included';
			document.getElementById('invitationChecked').checked = true;
			document.getElementById('postage').value = '0.490';
			document.getElementById('postageChecked').checked = true;
			document.getElementById('numMailedChecked').checked = true;
			document.getElementById('training').value = 'Included';
			document.getElementById('trainingChecked').checked = true;
			document.getElementById('travel').value = 'Included';
			document.getElementById('travelChecked').checked = true;
			document.getElementById('freight').value = 'Mailed Local';
			document.getElementById('freightChecked').checked = true;
			document.getElementById('showroomKit').value = 'Included';
			document.getElementById('showroomKitChecked').checked = true;
			document.getElementById('email').value = 'Included';
			document.getElementById('emailChecked').checked = true;
			document.getElementById('techPackage').value = 'Included';
			document.getElementById('techPackageChecked').checked = true;
			document.getElementById('dataScrubbing').value = 'Included';
			document.getElementById('dataScrubbingChecked').checked = true;

			if(aType == 'us_gm_package_intro') {
				$('#invitation').val('11.9');
				$('#item1Rate').val('6195');
				$('#item1Description').val('Package Intro Total');
				$('#numMailed').val('500');
			}
			else if(aType == 'us_gm_package_a') {
				$('#invitation').val('4.807');
				$('#item1Rate').val('7945');
				$('#item1Description').val('Package A Total');
				$('#numMailed').val('1500');
			}
			else if(aType == 'us_gm_package_b') {
				$('#invitation').val('3.228');
				$('#item1Rate').val('9295');
				$('#item1Description').val('Package B Total');
				$('#numMailed').val('2500');
			}
			else if(aType == 'us_gm_package_c') {
				$('#invitation').val('2.259');
				$('#item1Rate').val('10995');
				$('#item1Description').val('Package C Total');
				$('#numMailed').val('4000');
			}
		}


		showPrintBy();
	}


	function deleteWorksheet(worksheetID)
	{
		location.href = "?s1=<?= $_GET['s1'] ?>&s2=Add&delete=" + worksheetID;
	}
	function sendAgreementApproval() {
		$.ajax({data:	{sendAgreementApproval: ''},
				type:	'GET',
				dataType: 'script'
		});

	}
	function toggleAgreementSigned(){
		$.ajax({data:	{toggleAgreementSigned: ''},
				type:	'GET',
				dataType: 'html',
				success: function(data){
					if(data == "signed"){
						$('#agreementSignedButton').val("Unsign Agreement");
					}
					else{
						$('#agreementSignedButton').val("Agreement Signed");
					}


					window.opener.location.reload();
				}
		   	    });
	}
</script>

<div style="padding:10px 20px">
<?= ($_SESSION['worksheetError'] == "" ? '' : '<div style="background-color:yellow;color:red;font-weight:bold;padding:10px;font-size:12pt"><center>' . $_SESSION['worksheetError'] . '</center></div>') ?>
<?php unset($_SESSION['worksheetError']); ?>
<?= ($dealerInfo['suspended'] == 1 ? '<div style="background-color:yellow;color:red;font-weight:bold;padding:10px;font-size:12pt"><center>THIS DEALER IS SUSPENDED</center></div>' : '') ?>
<?php
	if($dealerInfo['countryID'] == COUNTRY_CA && $dealerInfo['hasCredit'] != 1) {
?>
	<div style="background-color:yellow;color:red;font-weight:bold;padding:10px;font-size:12pt">
		<center>THIS DEALER MUST SUBMIT A CREDIT APPLICATION.
			<br>
			<a href="?s1=worksheet&s2=Add&seeDealerContact" target="_blank">Click here to send the application.</a>
		</center>
	</div>
<?php
		exit;
	}
 ?>

<?php
	if(
		(
			$dealerInfo['countryID'] == COUNTRY_US
			|| $dealerInfo['nationID'] == NATION_UK
			|| $dealerInfo['countryID'] == COUNTRY_AU
			|| $dealerInfo['countryID'] == COUNTRY_NZ
			|| $dealerInfo['countryID'] == COUNTRY_FR
			|| $dealerInfo['countryID'] == COUNTRY_DE
			|| $dealerInfo['countryID'] == COUNTRY_CH
			|| $dealerInfo['provinceID'] == PROVINCE_QC
			|| ($dealerObj instanceof Dealer && $dealerObj->hasDealerGroup(DealerGroup::QBC))
		)
		&& $_SESSION['worksheetAdd']['approvalSent'] == ''
	){
?>
	<div style="background-color:blue;color:yellow;font-weight:bold;padding:10px;font-size:12pt">
		<center>
			** YOU MUST SEND THIS AGREEMENT FOR APPROVAL **
		</center>
	</div>
<?php
	}
 ?>

<form method="POST" id="agreementForm">
	<input type="hidden" name="worksheetID" value="<?= $_SESSION['worksheetAdd']['worksheetID'] ?>">
	<input type="hidden" name="eventID" value="<?= $_SESSION['worksheetAdd']['eventID'] ?>">
<?php
	if($dealerInfo['dealerNotes'] != "")
	{
?>
<div style="padding:10px;background-color:red;color:yellow;font-weight:bold;font-size:13pt">
	<?= str_replace("\r\n","<br>",htmlentities($dealerInfo['dealerNotes'])) ?>
</div>
<br>
<?php
	}
?>
<?php
	//print_r2($_SESSION);
?>
<table cellspacing="0" cellpadding="0" class="worksheetAddTbl">
	<tr>
		<th>Dealership</th>
		<td>
			<select id="dealerID" name="dealerID" onChange="setContact()">
				<option></option>
	<?php
		while($dealer = mysqli_fetch_assoc($dealerResults))
		{
	?>
				<option value="<?= $dealer['dealerID'] ?>" <?= ($_SESSION['worksheetAdd']['dealerID'] == $dealer['dealerID'] ? 'selected' : '') ?>><?= $dealer['dealerName'] ?></option>
	<?php
		}
	?>
			</select>
		</td>
	</tr>
	<tr>
		<th>Main Contact</th>
		<td>
			<div id="contactDiv" style="display:inline;padding-right:10px"><select style="width:100px"><option></option></select></div>
			<!-- <input type="checkbox" id="multipleContact" onChange="selectContact()" <?= (stripos($_SESSION['worksheetAdd']['contactEmail'],',') !== false ? 'CHECKED' : '') ?>> Multiple -->
			<span id="contactDisplayEmail">
				<?php
				    $dealerStaffObj = DealerStaff::ById($_SESSION['worksheetAdd']['dealerStaffID']);
				    $dealerStaffEmail = 'No Email set for contact';
				    if($dealerStaffObj instanceof DealerStaff && $dealerStaffObj->email != ""){
				    	$dealerStaffEmail = $dealerStaffObj->email;
				    }
				    echo $dealerStaffEmail;
				?>
			</span>
			<input type="hidden" id="contact" name="contact" value="<?= $_SESSION['worksheetAdd']['contact'] ?>">
			<input type="hidden" id="contactHiddenEmail" name="contactHiddenEmail" value="<?= $dealerStaffEmail ?>">
			<input type="hidden" id="contactPhone" name="contactPhone" value="<?= $_SESSION['worksheetAdd']['contactPhone'] ?>">
			<input type="hidden" id="dealerStaffID" name="dealerStaffID" value="<?= $_SESSION['worksheetAdd']['dealerStaffID'] ?>">
		</td>
	</tr>
	<tr>
		<th>Additional CC</th>
		<td>
			<select id="contactEmail" name="contactEmail[]" multiple="multiple" style="width: 99%;" >
				<?php
				$dealerObj = Dealer::ById($_SESSION['worksheetAdd']['dealerID']);
				$worksheetObj = Worksheet::ById($_SESSION['worksheetAdd']['worksheetID']);

				if(count($dealerObj->dealerStaff) > 0){
					foreach($dealerObj->dealerStaff as $ds){
						if($ds->email != ""){
							echo '<option value="'. $ds->email .'" '. (stripos(trim($worksheetObj->contactEmail), trim($ds->email)) !== false  ? 'SELECTED' : '' ) .'>'. $ds->name .'</option>';
						}
					}
				}
				?>
			</select>
		</td>
	</tr>
	<tr>
		<th>Dealership Type</th>
		<td>
			<select name="dealerType">
				<option></option>
				<option value="canchrysler" <?= ($_SESSION['worksheetAdd']['dealerType'] == 'canchrysler' ? 'selected' : '') ?>>Canadian Chrysler</option>
				<option value="usachrysler" <?= ($_SESSION['worksheetAdd']['dealerType'] == 'usachrysler' ? 'selected' : '') ?>>USA Chrysler</option>
				<option value="regular" <?= ($_SESSION['worksheetAdd']['dealerType'] == 'regular' ? 'selected' : '') ?>>Other</option>
			</select>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="checkbox" id="isFrench" name="isFrench"  <?= ($dealerInfo['isFrench'] == 'on' ? 'checked' : '') ?>>
			French?
		</td>
	</tr>
	<tr>
		<th>Event Start</th>
		<td>
			<select id="eventStartMonth" name="eventStartMonth" onChange="autoFillEventEnd()">
		<?php
		for($i=1;$i<=12;$i++)
		{
		?>
				<option value="<?= str_pad($i,2,"0",STR_PAD_LEFT) ?>" <?= ($_SESSION['worksheetAdd']['eventStartMonth'] == str_pad($i,2,"0",STR_PAD_LEFT) ? 'selected' : '') ?>><?= date("M",strtotime("2011-" . $i)) ?></option>
		<?php
		}
		?>
			</select>
			<select id="eventStartDay" name="eventStartDay" onChange="autoFillEventEnd()">
		<?php
		for($i=1;$i<=31;$i++)
		{
		?>
				<option value="<?= str_pad($i,2,"0",STR_PAD_LEFT) ?>" <?= ($_SESSION['worksheetAdd']['eventStartDay'] == str_pad($i,2,"0",STR_PAD_LEFT) ? 'selected' : '') ?>><?= $i ?></option>
		<?php
		}
		?>
			</select>
			<select id="eventStartYear" name="eventStartYear" onChange="autoFillEventEnd()">
		<?php
		for($i=2008;$i<=date("Y")+1;$i++)
		{
		?>
				<option value="<?= $i ?>" <?= ($_SESSION['worksheetAdd']['eventStartYear'] == $i ? 'selected' : '') ?>><?= $i ?></option>
		<?php
		}
		?>
			</select>
		</td>
	</tr>
	<tr>
		<th>Event End</th>
		<td>
			<select id="eventEndMonth" name="eventEndMonth">
		<?php
		for($i=1;$i<=12;$i++)
		{
		?>
				<option value="<?= str_pad($i,2,"0",STR_PAD_LEFT) ?>" <?= ($_SESSION['worksheetAdd']['eventEndMonth'] == str_pad($i,2,"0",STR_PAD_LEFT) ? 'selected' : '') ?>><?= date("M",strtotime("2011-" . $i)) ?></option>
		<?php
		}
		?>
			</select>
			<select id="eventEndDay" name="eventEndDay">
		<?php
		for($i=1;$i<=31;$i++)
		{
		?>
				<option value="<?= str_pad($i,2,"0",STR_PAD_LEFT) ?>" <?= ($_SESSION['worksheetAdd']['eventEndDay'] == str_pad($i,2,"0",STR_PAD_LEFT) ? 'selected' : '') ?>><?= $i ?></option>
		<?php
		}
		?>
			</select>
			<select id="eventEndYear" name="eventEndYear">
		<?php
		for($i=2008;$i<=date("Y")+1;$i++)
		{
		?>
				<option value="<?= $i ?>" <?= ($_SESSION['worksheetAdd']['eventEndYear'] == $i ? 'selected' : '') ?>><?= $i ?></option>
		<?php
		}
		?>
			</select>
		</td>
	</tr>
</table>
<table cellspacing="0" cellpadding="0" class="worksheetAddTbl">
	<?= buildForm('trainingDays','# Days Training') ?>
</table>
<table cellspacing="0" cellpadding="0" class="worksheetAddTbl">
	<tr>
		<th colspan="2">Agreement Type</th>
		<td>

			<select name="worksheetTypeID" id="worksheetType" onChange="preload($('#worksheetType :selected').attr('code'))">
				<option value=""></option>
	<?php
		uasort($worksheetTypeGroups,'worksheetTypeSort');
		foreach($worksheetTypeGroups as $group) {
			if(!$group->status) continue;
			$types = $group->types;
	?>
				<optgroup label="<?= $group->name ?>">
		<?php
			uasort($types,'worksheetTypeSort');
			foreach($types as $type) {

				if(!$type->status && $type->id != $_SESSION['worksheetAdd']['worksheetTypeID']) continue;
		?>
					<option value="<?= $type->id ?>" code="<?= $type->code ?>" <?= ($_SESSION['worksheetAdd']['worksheetTypeID'] == $type->id ? 'selected' : '') ?>><?= $type->name ?></option>
		<?php
			}
		?>
				</optgroup>
	<?php
		}
	?>
			</select>
			<!-- <select name="eventType" onChange="preload(this.value)">
				<option value="">Blank</option>
				<optgroup label="Single Events">
					<option value="canchryslerprivatesale" <?= ($_SESSION['worksheetAdd']['eventType'] == 'canchryslerprivatesale' ? 'selected' : '') ?>>CA Chrysler Private Sale</option>
					<option value="uschryslerprivatesale" <?= ($_SESSION['worksheetAdd']['eventType'] == 'uschryslerprivatesale' ? 'selected' : '') ?>>US Chrysler Private Sale</option>
					<option value="canprivatesale" <?= ($_SESSION['worksheetAdd']['eventType'] == 'canprivatesale' ? 'selected' : '') ?>>CA Non-Chrysler Private Sale</option>
					<option value="usprivatesale" <?= ($_SESSION['worksheetAdd']['eventType'] == 'usprivatesale' ? 'selected' : '') ?>>US Non-Chrysler Private Sale</option>

					<option value="cainviteonly" <?= ($_SESSION['worksheetAdd']['eventType'] == 'cainviteonly' ? 'selected' : '') ?>>CA Invite Only</option>
				</optgroup>
				<optgroup label="CA Chrysler Packages">
					<option value="ca_ch_package_ABC" <?= ($_SESSION['worksheetAdd']['eventType'] == 'ca_ch_package_ABC' ? 'selected' : '') ?>>CA Package A / B / C - Invites</option>
					<option value="ca_ch_package_DE" <?= ($_SESSION['worksheetAdd']['eventType'] == 'ca_ch_package_DE' ? 'selected' : '') ?>>CA Package D / E - Invites</option>
					<option value="ca_ch_package_A_conquest_single" <?= ($_SESSION['worksheetAdd']['eventType'] == 'ca_ch_package_A_conquest_single' ? 'selected' : '') ?>>CA Package A - Conquest Single</option>
					<option value="ca_ch_package_A_conquest_multiple" <?= ($_SESSION['worksheetAdd']['eventType'] == 'ca_ch_package_A_conquest_multiple' ? 'selected' : '') ?>>CA Package A - Conquest Multiple</option>
					<option value="ca_ch_package_A_conquest_single_upgrade" <?= ($_SESSION['worksheetAdd']['eventType'] == 'ca_ch_package_A_conquest_single_upgrade' ? 'selected' : '') ?>>CA Package A - Conquest Single Upgrade</option>
					<option value="ca_ch_package_A_conquest_multiple_upgrade" <?= ($_SESSION['worksheetAdd']['eventType'] == 'ca_ch_package_A_conquest_multiple_upgrade' ? 'selected' : '') ?>>CA Package A - Conquest Multiple Upgrade</option>
					<option value="ca_ch_package_B_conquest_single" <?= ($_SESSION['worksheetAdd']['eventType'] == 'ca_ch_package_B_conquest_single' ? 'selected' : '') ?>>CA Package B - Conquest Single</option>
					<option value="ca_ch_package_B_conquest_multiple" <?= ($_SESSION['worksheetAdd']['eventType'] == 'ca_ch_package_B_conquest_multiple' ? 'selected' : '') ?>>CA Package B - Conquest Multiple</option>
					<option value="ca_ch_package_B_conquest_single_upgrade" <?= ($_SESSION['worksheetAdd']['eventType'] == 'ca_ch_package_B_conquest_single_upgrade' ? 'selected' : '') ?>>CA Package B - Conquest Single Upgrade</option>
					<option value="ca_ch_package_B_conquest_multiple_upgrade" <?= ($_SESSION['worksheetAdd']['eventType'] == 'ca_ch_package_B_conquest_multiple_upgrade' ? 'selected' : '') ?>>CA Package B - Conquest Multiple Upgrade</option>
					<option value="ca_ch_package_C_conquest_single" <?= ($_SESSION['worksheetAdd']['eventType'] == 'ca_ch_package_C_conquest_single' ? 'selected' : '') ?>>CA Package C - Conquest Single</option>
					<option value="ca_ch_package_C_conquest_multiple" <?= ($_SESSION['worksheetAdd']['eventType'] == 'ca_ch_package_C_conquest_multiple' ? 'selected' : '') ?>>CA Package C - Conquest Multiple</option>
					<option value="ca_ch_package_C_conquest_single_upgrade" <?= ($_SESSION['worksheetAdd']['eventType'] == 'ca_ch_package_C_conquest_single_upgrade' ? 'selected' : '') ?>>CA Package C - Conquest Single Upgrade</option>
					<option value="ca_ch_package_C_conquest_multiple_upgrade" <?= ($_SESSION['worksheetAdd']['eventType'] == 'ca_ch_package_C_conquest_multiple_upgrade' ? 'selected' : '') ?>>CA Package C - Conquest Multiple Upgrade</option>
					<option value="ca_ch_package_D_conquest_single" <?= ($_SESSION['worksheetAdd']['eventType'] == 'ca_ch_package_D_conquest_single' ? 'selected' : '') ?>>CA Package D - Conquest Single</option>
					<option value="ca_ch_package_D_conquest_multiple" <?= ($_SESSION['worksheetAdd']['eventType'] == 'ca_ch_package_D_conquest_multiple' ? 'selected' : '') ?>>CA Package D - Conquest Multiple</option>
					<option value="ca_ch_package_D_conquest_single_upgrade" <?= ($_SESSION['worksheetAdd']['eventType'] == 'ca_ch_package_D_conquest_single_upgrade' ? 'selected' : '') ?>>CA Package D - Conquest Single Upgrade</option>
					<option value="ca_ch_package_D_conquest_multiple_upgrade" <?= ($_SESSION['worksheetAdd']['eventType'] == 'ca_ch_package_D_conquest_multiple_upgrade' ? 'selected' : '') ?>>CA Package D - Conquest Multiple Upgrade</option>
				</optgroup>

				<optgroup label="US Chrysler Packages">
					<option value="usanano" <?= ($_SESSION['worksheetAdd']['eventType'] == 'usanano' ? 'selected' : '') ?>>USA Nano</option>
					<option value="usa1500" <?= ($_SESSION['worksheetAdd']['eventType'] == 'usa1500' ? 'selected' : '') ?>>USA 1,500</option>
					<option value="usa2500" <?= ($_SESSION['worksheetAdd']['eventType'] == 'usa2500' ? 'selected' : '') ?>>USA 2,500</option>
					<option value="usa4000" <?= ($_SESSION['worksheetAdd']['eventType'] == 'usa4000' ? 'selected' : '') ?>>USA 4,000</option>
					<option value="usa5000" <?= ($_SESSION['worksheetAdd']['eventType'] == 'usa5000' ? 'selected' : '') ?>>USA 5,000</option>
				</optgroup>

				<optgroup label="US Chrysler Guarantee Conquest">
					<option value="usaguarantee2500" <?= ($_SESSION['worksheetAdd']['eventType'] == 'usaguarantee2500' ? 'selected' : '') ?>>USA Guarantee Conquest 2,500</option>
					<option value="usaguarantee4000" <?= ($_SESSION['worksheetAdd']['eventType'] == 'usaguarantee4000' ? 'selected' : '') ?>>USA Guarantee Conquest 4,000</option>
				</optgroup>

				<optgroup label="US Non-FCA Packages">
					<option value="usa_nonfca_nano" <?= ($_SESSION['worksheetAdd']['eventType'] == 'usa_nonfca_nano' ? 'selected' : '') ?>>USA Non-FCA Nano</option>
					<option value="usa_nonfca_1500" <?= ($_SESSION['worksheetAdd']['eventType'] == 'usa_nonfca_1500' ? 'selected' : '') ?>>USA Non-FCA 1,500</option>
					<option value="usa_nonfca_2500" <?= ($_SESSION['worksheetAdd']['eventType'] == 'usa_nonfca_2500' ? 'selected' : '') ?>>USA Non-FCA 2,500</option>
					<option value="usa_nonfca_4000" <?= ($_SESSION['worksheetAdd']['eventType'] == 'usa_nonfca_4000' ? 'selected' : '') ?>>USA Non-FCA 4,000</option>
				</optgroup>


				<optgroup label="UK Chrysler Packages">
					<option value="uk3_600" <?= ($_SESSION['worksheetAdd']['eventType'] == 'uk3_600' ? 'selected' : '') ?>>UK 3/600</option>
					<option value="uk3_1000" <?= ($_SESSION['worksheetAdd']['eventType'] == 'uk3_1000' ? 'selected' : '') ?>>UK 3/1000</option>
					<option value="uk3_2000" <?= ($_SESSION['worksheetAdd']['eventType'] == 'uk3_2000' ? 'selected' : '') ?>>UK 3/1500</option>
					<option value="uk_5300_800" <?= ($_SESSION['worksheetAdd']['eventType'] == 'uk_5300_800' ? 'selected' : '') ?>>UK £4800/800</option>
				</optgroup>
				<optgroup label="France Packages">
					<option value="fr_fca_500" <?= ($_SESSION['worksheetAdd']['eventType'] == 'fr_fca_500' ? 'selected' : '') ?>>FCA 500 France</option>
				</optgroup>
				<optgroup label="Belgium Packages">
					<option value="be3_500" <?= ($_SESSION['worksheetAdd']['eventType'] == 'be3_500' ? 'selected' : '') ?>>BE 3/500 Chrysler</option>
					<option value="be_fca_500" <?= ($_SESSION['worksheetAdd']['eventType'] == 'be_fca_500' ? 'selected' : '') ?>>FCA 500 Belgium </option>
				</optgroup>
				<optgroup label="Australia Packages">
					<option value="au" <?= ($_SESSION['worksheetAdd']['eventType'] == 'au' ? 'selected' : '') ?>>Australia FCA</option>
				</optgroup>

				<optgroup label="Mazda Canada">
					<option value="mazda_ca_a" <?= ($_SESSION['worksheetAdd']['eventType'] == 'mazda_ca_a' ? 'selected' : '') ?>>Mazda Canada Package A</option>
					<option value="mazda_ca_b" <?= ($_SESSION['worksheetAdd']['eventType'] == 'mazda_ca_b' ? 'selected' : '') ?>>Mazda Canada Package B</option>
				</optgroup>

				<optgroup label="Harley Davidson">
					<option value="hd_package_a" <?= ($_SESSION['worksheetAdd']['eventType'] == 'hd_package_a' ? 'selected' : '') ?>>Harley Davidson Package A</option>
					<option value="hd_package_b" <?= ($_SESSION['worksheetAdd']['eventType'] == 'hd_package_b' ? 'selected' : '') ?>>Harley Davidson Package B</option>
				</optgroup>

				<optgroup label="Non Prime">
					<option value="nonprime" <?= ($_SESSION['worksheetAdd']['eventType'] == 'nonprime' ? 'selected' : '') ?>>Non Prime - EN</option>
					<option value="nonprime_fr" <?= ($_SESSION['worksheetAdd']['eventType'] == 'nonprime_fr' ? 'selected' : '') ?>>Non Prime - FR</option>
				</optgroup>

			</select> -->
		</td>
	</tr>
<?= buildForm('invitation','Invitations',false,true) ?>
<?= buildForm('inserts','Inserts',false,true) ?>
<?= buildForm('postage','Postage',false,true) ?>
<?= buildForm('conquest','Conquest Flyers',false,true) ?>
<?= buildForm('numMailed','# Mailed') ?>
<tr><td style="height:20px">&nbsp;</td></tr>
<?= buildForm('training','Training Rate') ?>
<?= buildForm('travel','Travel Expense') ?>
<?= buildForm('perCar','Per Car Rate') ?>
<?= buildForm('saleDay','Sale Day Rate') ?>
<?= buildForm('showroomKit','Showroom Kit') ?>
<?= buildForm('voicecast','Voicecast',true) ?>
<?= buildForm('email','Email Campaign',true) ?>
<?= buildForm('sms','Text Campaign',true) ?>
<?= buildForm('techPackage','Tech Package',true) ?>
<?= buildForm('freight','Freight',false,true) ?>
<?= buildForm('dataScrubbing','Data Scrubbing') ?>
<?= buildForm('purchaseConquest','Purchase Conquest') ?>
<?= buildForm('arc','ARC Calls',false,true) ?>
<?= buildForm('telnek','Telnek Calls',false,true) ?>
<?= buildForm('numCalls','# Calls') ?>
<?= buildForm('leadBridge','LeadBridge',false,true) ?>
<?= buildForm('digital','Digital',false,true) ?>
<tr>
	<th colspan="2">Data Extract&nbsp;
		<select id="dataExtract" name="dataExtract" onChange="setDataExtract()">
			<option></option>
			<option value="perry" <?= ($_SESSION['worksheetAdd']['dataExtract'] == 'perry' ? 'selected' : '') ?>>Perry</option>
			<option value="pbs" <?= ($_SESSION['worksheetAdd']['dataExtract'] == 'pbs' ? 'selected' : '') ?>>PBS</option>
			<option value="other" <?= ($_SESSION['worksheetAdd']['dataExtract'] == 'other' ? 'selected' : '') ?>>Other</option>
		</select></th>
	<td nowrap>
		<input type="text" style="width:100%" id="dataExtractRate" name="dataExtractRate" value="<?= $_SESSION['worksheetAdd']['dataExtractRate'] ?>">
	</td>
</tr>
</table>
<br>
<table cellspacing="0" cellpadding="0" class="worksheetAddTbl">
	<tr>
		<th>Item 1</th>
		<td style="width:80%;padding-right:5px"><input type="text" item style="width:100%" id="item1Description" name="item1Description" value="<?= $_SESSION['worksheetAdd']['item1Description'] ?>"></td>
		<td style="width:20%"><input type="text" item style="width:100%" id="item1Rate" name="item1Rate" value="<?= $_SESSION['worksheetAdd']['item1Rate'] ?>"></td>
	</tr>
	<tr>
		<th>Item 2</th>
		<td style="width:80%;padding-right:5px"><input type="text" item style="width:100%" id="item2Description" name="item2Description" value="<?= $_SESSION['worksheetAdd']['item2Description'] ?>"></td>
		<td style="width:20%"><input type="text" item style="width:100%" id="item2Rate" name="item2Rate" value="<?= $_SESSION['worksheetAdd']['item2Rate'] ?>"></td>
	</tr>
	<tr>
		<th>Item 3</th>
		<td style="width:80%;padding-right:5px"><input type="text" item style="width:100%" id="item3Description" name="item3Description" value="<?= $_SESSION['worksheetAdd']['item3Description'] ?>"></td>
		<td style="width:20%"><input type="text" item style="width:100%" id="item3Rate" name="item3Rate" value="<?= $_SESSION['worksheetAdd']['item3Rate'] ?>"></td>
	</tr>
</table>
<div style="height:30px;font-weight:bold;color:red;padding-top:10px">
<?php
	if(trim($_SESSION['worksheetAdd']['website']) != "")
	{
		$website = str_replace(array('www.'),'',$_SESSION['worksheetAdd']['website']);
		$sql = 'SELECT * FROM ps_worksheets WHERE website like "%' . $_SESSION['worksheetAdd']['website'] . '%" AND dealerID != ' . $_SESSION['worksheetAdd']['dealerID'];
		$dupeWebResults = mysqli_query($db_data,$sql);
		if(mysqli_num_rows($dupeWebResults) > 0)
		{
			$dupeWeb = mysqli_fetch_assoc($dupeWebResults);
			$dupeDealer = displayDealerInfo($dupeWeb['dealerID']);
			echo 'NOTE: Another dealership is using this website (ie. ' . $dupeDealer['dealerName'] . ')';
		}
	}
?>
</div>
<table cellspacing="0" cellpadding="0" class="worksheetAddTbl">
	<tr>
		<th style="vertical-align:top;padding-top:3px">Website</th>
		<td style="width:100%">
			<style>
				#websiteSelect {font-size:15pt}
			</style>
			<input type="text" style="width:80%" id="website" name="website" value="<?= $_SESSION['worksheetAdd']['website'] ?>"><input type="button" style="width:10%" onClick="setTBD('website')" value="TBD">
			<div id="websiteDiv" style="display:inline;padding-right:10px;"><select style="width:100px"><option></option></select></div></td>
	</tr>
	<?php
	if(!empty($_SESSION['worksheetAdd']['emailChecked']) || !empty($_SESSION['worksheetAdd']['techPackageChecked'])) {
	?>
	<tr>
		<th style="vertical-align:top;padding-top:3px">Email Campaign</th>
		<td style="width:100%">
			<select id="website" name="templateID">
				<option value="" <?= (empty($_SESSION['worksheetAdd']['templateID']) ? 'SELECTED' : '') ?>>Artwork-Based</option>
			<?php
			foreach(EmailCampaignTemplate::getTemplatesList($eventObj->eventPromotionID) as $id => $name) {
			?>
				<option value="<?= $id ?>" <?= ($_SESSION['worksheetAdd']['templateID'] == $id ? 'SELECTED' : '') ?>>Text-Based: <?= $name ?></option>
			<?php
			}
			?>
		</td>
	</tr>
	<?php
	}

	if(!empty($_SESSION['worksheetAdd']['smsChecked']) || !empty($_SESSION['worksheetAdd']['techPackageChecked'])) {
	?>
	<tr>
		<th style="vertical-align:top;padding-top:3px">SMS Campaign</th>
		<td style="width:100%">
			<select id="website" name="smsTemplateID">
				<option value="" <?= (empty($_SESSION['worksheetAdd']['smsTemplateID']) ? 'SELECTED' : '') ?>></option>
			<?php
			foreach(SmsCampaignTemplate::getTemplatesList(SmsCampaignTemplate::TYPE_OUTBOUND,$eventObj->eventPromotionID) as $id => $name) {
			?>
				<option value="<?= $id ?>" <?= ($_SESSION['worksheetAdd']['smsTemplateID'] == $id ? 'SELECTED' : '') ?>><?= $name ?></option>
			<?php
			}
			?>
		</td>
	</tr>
	<?php
	}
	?>
	<tr>
		<th style="vertical-align:top;padding-top:3px">FSAs
			<br>
			Postal Routes
		</th>
		<td style="width:100%"><textarea id="fsa" name="fsa" style="width:100%;height:40px"><?= stripslashes($_SESSION['worksheetAdd']['fsa']) ?></textarea></td>
	</tr>
	<tr>
		<th style="vertical-align:top;padding-top:3px">Public Notes</th>
		<td style="width:100%"><textarea id="publicNotes" name="publicNotes" style="width:100%;height:40px"><?= stripslashes($_SESSION['worksheetAdd']['publicNotes']) ?></textarea></td>
	</tr>
	<tr>
		<th style="vertical-align:top;padding-top:3px">Private Notes</th>
		<td style="width:100%"><textarea name="privateNotes" style="width:100%;height:80px"><?= stripslashes($_SESSION['worksheetAdd']['privateNotes']) ?></textarea></td>
	</tr>
</table>
<table cellspacing="0" cellpadding="0" style="width:100%">
	<tr>
		<td><input type="submit" value="Save"></td>
		<td style="width:100%;padding-right:30px"><input type="button" value="Close" onClick="self.close()"></td>
		<?php if(empty($_SESSION['worksheetAdd']['worksheetID']) && !empty($_SESSION['worksheetAdd']['eventID'])) { ?>
			<td style="text-align:right;">
				<div style="width:155px">
					<a href="?s1=agreement&s2=Add&new=&eventID=<?= $_SESSION['worksheetAdd']['eventID'] ?>">Try V2 Beta</a>
				</div>
			</td>
		<?php } ?>

	</tr>
	<tr>
	<?php if($_SESSION['worksheetAdd']['worksheetID'] != "") { ?>
			<td></td>
			<td></td>
			<td><input type="button" value="View Agreement" style="width:155px" onClick="window.open('export/agreement/<?= $_SESSION['worksheetAdd']['hash'] ?>')"></td>
			<td><input type="button" id="emailButton" value="Email Agreement" onClick="checkEmail()" style="width:155px"></td>
		</tr>
		<tr>
			<td colspan="2"></td>
			<?php if($_SESSION['worksheetAdd']['invitationChecked'] != "") { ?>
			<td><input type="button" id="emailSelfWelcomeButton" value="Email Letter To Self" onClick="sendEmailSelfWelcome()" style="width:155px"></td>
			<td><input type="button" id="emailWelcomeButton" value="Email Welcome Letter" onClick="checkEmailWelcome()" style="width:155px"></td>

		<?php } ?>
	</tr>

	<?php if($_SESSION['worksheetAdd']['worksheetID'] != "") { ?>
	<tr>
		<td colspan="2" style="padding-top:5px">
			<a href="export/infosheet/<?= ($_SESSION['worksheetAdd']['invitationChecked'] == 'on' ? 'privateSale' : 'conquest') ?>.php?id=<?= $_SESSION['worksheetAdd']['worksheetID'] ?>" target="_blank">Print Info Sheet</a>
		</td>
		<?php if($_SESSION['worksheetAdd']['invitationChecked'] != "" || $_SESSION['worksheetAdd']['conquestChecked'] != "" || $_SESSION['worksheetAdd']['arcChecked'] != "" || $_SESSION['worksheetAdd']['telnekChecked'] != "") { ?>
		<td><input type="button" id="emailSelfPhoneListButton" value="Email Phone List To Self" onClick="sendEmailSelfPhoneList()" style="width:155px"></td>
		<td><input type="button" id="emailPhoneListButton" value="Email Phone List" onClick="checkEmailPhoneList()" style="width:155px"></td>
		<?php } ?>
	</tr>
	<?php } ?>
	<?php if($dealerInfo['dataPassword'] != "") { ?>
	<tr>
		<td colspan="2"></td>
		<td><input type="button" id="emailSelfDataPasswordButton" value="Email Data Pwd To Self" onClick="sendEmailSelfDataPassword()" style="width:155px"></td>
		<td><input type="button" id="emailDataPasswordButton" value="Email Data Password" onClick="checkEmailDataPassword()" style="width:155px"></td>
	</tr>
	<?php } ?>

	<tr>
		<td colspan="2" style="padding-top:5px">
			<div style="display:inline;color:#777;"><?= displayWorksheetNum($_SESSION['worksheetAdd']); ?></div>
		<?php if($_SESSION['worksheetAdd']['eventID'] != "") { ?>
			<div style="display:inline;color:#777;padding:10px 0px"><b>Event #</b> <?= $_SESSION['worksheetAdd']['eventID'] ?></div>
		<?php } ?>
			<div style="color:#777;"><b>Created </b> <?= ($_SESSION['worksheetAdd']['created_date'] != "" ? date("M j,Y",strtotime($_SESSION['worksheetAdd']['created_date'])) : '') ?> by <b><?= displayStaffInfo($_SESSION['worksheetAdd']['staffID'])['name'] ?></b></div>
		</td>
		<?php if($_SESSION['worksheetAdd']['voicecastChecked'] != "" || $_SESSION['worksheetAdd']['techPackageChecked'] != "") { ?>
		<td><input type="button" id="voicecastSelfButton" value="Email VC to Self" onClick="sendVoicecastSelf()" style="width:155px"></td>
		<td>
			<input type="button" id="voicecastButton" value="Email Voicecast Instructions" onClick="checkVoicecast()" style="width:155px">
			<input type="button" id="cancelVoicecastButton" value="Cancel Voicecast " onClick="ARAlertConfirmation('Are you sure you want to cancel this voicecast', null, function(){cancelVoicecast();})" style="width:155px">
		</td>
		<?php } ?>
	</tr>

	<tr>
		<td colspan="2" style="padding-top:5px">
		<td style="padding-top: 5px;">
		<?php
			if($dealerInfo['countryID'] == COUNTRY_US
				|| $dealerInfo['nationID'] == NATION_UK
				|| $dealerInfo['countryID'] == COUNTRY_AU
				|| $dealerInfo['countryID'] == COUNTRY_NZ
				|| $dealerInfo['countryID'] == COUNTRY_FR
				|| $dealerInfo['countryID'] == COUNTRY_DE
				|| $dealerInfo['countryID'] == COUNTRY_CH
				|| $dealerInfo['provinceID'] == PROVINCE_QC
				|| ($dealerObj instanceof Dealer && $dealerObj->hasDealerGroup(DealerGroup::QBC))
			){
		?>
			<input type="button" id="agreementApprovalButton" value="Send Agreement Approval" onClick="sendAgreementApproval()" style="width:155px">
		<?php
			if($_SESSION['worksheetAdd']['approvalSent'] != '') echo '<div style="font-size:0.9em">Sent ' . date("M j, Y H:i A",strtotime($_SESSION['worksheetAdd']['approvalSent'])) . '</div>';
			}
		?>

		</td>
		<td style="padding-top: 5px;">
			<input type="button" id="agreementSignedButton" value="<?= ($_SESSION['worksheetAdd']['agreementSigned'] != "" ? 'Unsign Agreement' : 'Agreement Signed' ) ?> " onClick="toggleAgreementSigned()" style="width:155px">
		</td>
	</tr>


	<tr>

		<!-- <td colspan="2" style="padding-top:10px"><input type="button" value="Delete" onClick="if(confirm('Are you sure you want to delete this agreement?')) { deleteWorksheet(<?= $_SESSION['worksheetAdd']['worksheetID'] ?>) }"></td> -->
		<td colspan="2" style="padding-top:10px"><input type="button" value="Delete" onClick="ARAlertConfirmation('Are you sure you want to delete this agreement?', null, function(){deleteWorksheet(<?= $_SESSION['worksheetAdd']['worksheetID'] ?>);})"></td>
	</tr>
<?php } ?>
</table>
</form>
</div>