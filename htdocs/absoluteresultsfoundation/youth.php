<?php
	include_once('includes.php');

	if(isset($_POST['validateForm']))
	{
		$_SESSION['youth']['uploadFolder'] = 'uploads/' . date("Y_m_d_His");
		?>
		if($('#employee_firstname').val() == '') alert('Please tell us the first name of employee');
		else if($('#employee_lastname').val() == '') alert('Please tell us the last name of employee');
		else if($('#phone').val() == '') alert('Please tell us your telephone');
		else if($('#email').val() == '') alert('Please tell us your email');
		else if($('#youth').val() == '') alert('Please tell us name of youth');
		else if($('#age').val() == '') alert('Please tell us age of youth');
		else if($('#passport_expiry').val() == '') alert('Please tell us the expiry date of your passport');
		else if(sendObj_story == '') alert('Please upload a document with your story');
		else if(sendObj_video == '') alert('Please upload a video of your story');
		else sendObj_story.submit();
		<?php

		exit;
	}

	if(isset($_POST['employee_firstname']))
	{
		if($_POST['story'] != '') $_POST['story'] = $_SESSION['youth']['uploadFolder'] . '/' . $_POST['story'];
		if($_POST['video'] != '') $_POST['video'] = $_SESSION['youth']['uploadFolder'] . '/' . $_POST['video'];


		foreach($_POST as $k => $v){}

		$sql = 'INSERT INTO ps_youth_signup (' . implode(',',array_keys($_POST)) . ',dateRegistered) VALUES ("' . implode('","',$_POST) . '","' . date('Y-m-d H:i:s') . '")';

		if(mysqli_query($db_foundation,$sql))
		{
			include_once('Emailer/swift_required.php');

			try
			{
				$transport = Swift_SmtpTransport::newInstance('absoluteresults.smtp.com',2525)->setUsername('smtp@absoluteresults.com')->setPassword('@B5oLut3');
				$mailer = Swift_Mailer::newInstance($transport);

				$message = Swift_Message::newInstance('Absolute Results Foundation - Youth Program Submission');

				$body = '<font style="font-size:11pt;font-family:Arial">
						 	Employee First Name: ' . $_POST['employee_firstname'] . '<br>
						 	Employee Last Name: ' . $_POST['employee_lastname'] . '<br>
						 	Phone: ' . $_POST['phone'] . '<br>
						 	Email: ' . $_POST['email'] . '<br><br>
						 	Youth First Name: ' . $_POST['youth_firstname'] . '<br>
						 	Youth Last Name: ' . $_POST['youth_lastname'] . '<br>
						 	Age: ' . $_POST['age'] . '<br>
						 	Passport Expiry: ' . $_POST['passport_expiry'] . '<br><br>
						 	Story Link: <a href="http://www.absoluteresultsfoundation.org/' . $_POST['story'] . '">http://www.absoluteresultsfoundation.org/' . $_POST['story'] . '</a><br>
						 	Video Link: <a href="http://www.absoluteresultsfoundation.org/' . $_POST['video'] . '">http://www.absoluteresultsfoundation.org/' . $_POST['video'] . '</a><br>
						 </font>
						';

				$message->setBody($body, 'text/html');
				$message->setTo(array('devteam@absoluteresults.com'));
				//$message->setTo(array('dave@absoluteresults.com'));
				$message->setBcc(array('dave@absoluteresults.com'));
				$message->setReturnPath('web@absoluteresults.com');
				$message->setFrom('web@absoluteresults.com');
				$mailer->send($message);
			}
			catch (Swift_RfcComplianceException $e){}
			catch (Swift_TransportException $e) {}
			catch (Swift_Message_MimeException $e) {}

			?>
			alert('Thank you for your submission. We will review and contact you shortly.');
			location.href='youth.php';
			<?php
		}
		else
		{
			?>
			alert('Error Occurred. Please try again.');
			<?php
		}
		exit;
	}

	unset($_SESSION['youth']['uploadFolder']);
	include_once('header.php');
?>
<script src="scripts/uploader/js/vendor/jquery.ui.widget.js"></script>
<script src="scripts/uploader/js/jquery.iframe-transport.js"></script>
<script src="scripts/uploader/js/jquery.fileupload.js"></script>
<script>
	var sendObj_story= '';
	var sendObj_video = '';
	$(function () {
	    $('#fileupload_story').fileupload({
	        dataType: 'json',
	        add: function (e, data) {
	        	var ext = data.files[0].name.substr(data.files[0].name.lastIndexOf('.') + 1);
	        	if(ext != 'doc' && ext != 'docx')
	        	{
	        		alert('Invalid File Type');
	        	}
	        	else
	        	{
		        	$('#progress_story').show();
		        	$('#storyName').html(data.files[0].name);
		            sendObj_story = data;
		        }
	        },
		    progressall: function (e, data) {
		        var progress = parseInt(data.loaded / data.total * 100, 10);
		        $('#progress_story .bar').css('width',progress + '%');

		        if(data.loaded == data.total)
		        {
		        	$('#story').val(sendObj_story.files[0].name);
					sendObj_video.submit();
		        }
		    }
	    });

	    $('#fileupload_video').fileupload({
	        dataType: 'json',
	        add: function (e, data) {
	        	var ext = data.files[0].name.substr(data.files[0].name.lastIndexOf('.') + 1);
	        	if(ext != 'MOV' && ext != 'mov' && ext != 'mp4')
	        	{
	        		alert('Invalid File Type');
	        	}
	        	else
	        	{
		        	$('#progress_video').show();
		        	$('#videoName').html(data.files[0].name);
		            sendObj_video = data;
		        }
	        },
		    progressall: function (e, data) {
		        var progress = parseInt(data.loaded / data.total * 100, 10);
		        $('#progress_video .bar').css('width',progress + '%');

		        if(data.loaded == data.total)
		        {
		        	$('#video').val(sendObj_video.files[0].name);
					submitYouth();
		        }
		    }
	    });
	});

	function validateForm()
	{
		$.ajax({data:	{validateForm: ''},
				type:	'POST',
				dataType: 'script'
   	    });

		return false;
	}
	function submitYouth()
	{
		$.ajax({data:	$('#youthForm').serializeArray(),
				type:	'POST',
				dataType: 'script'
   	    });

   	    return false;
	}
</script>
<div class="row">
	<h4 style="font-weight:bold;color:#cf1f2e">Youth Enrollment</h4>
	Opportunity for your child to experience.
	<br><br>
	<form id="youthForm" onSubmit="return validateForm()">
		<input type="hidden" id="video" name="video">
		<input type="hidden" id="story" name="story">
		<div class="row">
			<div class="twelve columns" style="padding-bottom:20px;font-size:0.9em">
				<div style="font-weight:bold">To qualify for the Youth Program</div>
				<ul style="margin:10px 20px;list-style-type: square">
					<li>- Youth must be between 12 and 19 years of age</li>
					<li>- Parent of youth must be an employee of Absolute Results for at least 6 months.</li>
				</ul>
				Absolute Results Foundation will cover the round-trip airfare to Guatemala, transportation to Hope Of Life and Food & Accommodation while in Hope of Life. Youth responsible for spending cash and other expenses en route.
			</div>
		</div>
		<div class="row">
			<div class="six columns">
				<div class="row">
					<div class="six columns">
						<div style="font-weight:bold">Employee First Name</div>
						<div class="field"><input class="input" type="text" name="employee_firstname" id="employee_firstname" /></div>
					</div>
					<div class="six columns">
						<div style="font-weight:bold">Employee Last Name</div>
						<div class="field"><input class="input" type="text" name="employee_lastname" id="employee_lastname" /></div>
					</div>
				</div>
				<div class="row">
					<div class="six columns">
						<div style="font-weight:bold">Phone</div>
						<div class="field"><input class="input" type="text" name="phone" id="phone" /></div>
					</div>
					<div class="six columns">
						<div style="font-weight:bold">Email</div>
						<div class="field"><input class="input" type="text" name="email" id="email" /></div>
					</div>
				</div>
			</div>
			<div class="six columns">
				<div class="row">
					<div class="six columns">
						<div style="font-weight:bold">Youth First Name</div>
						<div class="field"><input class="input" type="text" name="youth_firstname" id="youth_firstname" /></div>
					</div>
					<div class="six columns">
						<div style="font-weight:bold">Youth Last Name</div>
						<div class="field"><input class="input" type="text" name="youth_lastname" id="youth_lastname" /></div>
					</div>
				</div>
				<div class="row">
					<div class="three columns">
						<div style="font-weight:bold">Youth Age</div>
						<div class="field">
							<div class="picker">
								<select name="age" id="age">
									<option></option>
					<?php for($i = 12;$i<=19;$i++) { ?>
									<option value="<?= $i ?>"><?= $i ?></option>
					<?php } ?>
								</select>
							</div>
						</div>
					</div>
					<div class="nine columns">
						<div style="font-weight:bold">Passport Expiry Date</div>
						<div class="field"><input class="input" type="text" name="passport_expiry" id="passport_expiry" /></div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<font style="font-weight:bold">Tell us your story/reason why you want to be approved for this trip to Guatemala and Hope of Life.</font>
			<br>
			AR foundation also asked you to submit a a video application as well even if the story is similar.
			<div style="font-size:0.8em;font-style:italic;line-height:1.1em;padding:10px 0px 15px 0px">
				<div>Story Supported Formats: <font style="font-weight:bold">.doc .docx</font></div>
				<div>Video Supported Formats: <font style="font-weight:bold">.mp4 .mov</font></div>
			</div>
		</div>
		<div class="row">
			<div class="twelve columns">
				<div class="row">
					<div style="float:left;width:100px;">
				    	<div class="small primary btn" onClick="$('#fileupload_story').click();"><a>+ Upload Story</a></div>
				    </div>
				    <div style="float:left;width:100px;">
				    	<div class="small primary btn" onClick="$('#fileupload_video').click();"><a>+ Upload Video</a></div>
				    </div>
				    <div style="clear:both;"></div>

				    <input id="fileupload_story" type="file" name="files[]" data-url="scripts/uploader/server/php/" accept="application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" multiple style="display:none">
				    <input id="fileupload_video" type="file" name="files[]" data-url="scripts/uploader/server/php/" accept="video/mp4,video/quicktime" multiple style="display:none">


				    <div style="padding-top:1px;width:100%">
				    	<div id="progress_story" style="display:none">
				    		<div style="position:absolute;padding:5px;font-size:0.9em;font-style:italic;color:black;font-weight:bold;"><div id="storyName"></div></div>
				    		<div class="bar" style="width:0%;height:30px;background-color:green"></div>
				    	</div>
				    </div>

				    <div style="padding-top:5px;width:100%">
				    	<div id="progress_video" style="display:none">
				    		<div style="position:absolute;padding:5px;font-size:0.9em;font-style:italic;color:black;font-weight:bold;"><div id="videoName"></div></div>
				    		<div class="bar" style="width:0%;height:30px;background-color:green"></div>
				    	</div>
				    </div>
				</div>

				<div class="row" style="padding-top:20px">
					<div class="six columns">
						<div class="medium secondary btn pretty"><input type="submit" id="submitBtn" value="Submit"></div>
					</div>
				</div>
			</div>
		</div>

	</form>
</div>

<?php include_once('footer.php'); ?>