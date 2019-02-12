<?php
	include_once('includes.php');
	include_once('emailUtils.php');

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
			$subject = 'Absolute Results Foundation - Youth Program Submission';
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
			$toArray = array('devteam@absoluteresults.com');
			$ccArray = array();
			$bcc = [];
			$from = 'web@absoluteresults.com';
			sendEmail($subject,$body,$from,$toArray,$ccArray,$bcc);

			?>
			alert('Thank you for your submission. We will review and contact you shortly.');
			location.href='programs.php';
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
<style>
	.section {
		background-color:#f7f7f7;
		margin-bottom:10px;
		padding:20px;
	}
	.sectionTitle {
		font-weight:bold;
		color:#cf1f2e;
		font-size:1.2em;
	}
</style>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="sectionTitle">Programs Envisioned</div>
			<div style="margin-bottom:10px">At this time, the Absolute Results is considering the following programs, subject to regulatory approval.</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="section">
				<div class="sectionTitle">Financial Support of Selected Charities</div>
				Building on the history of charitable giving ingrained into our corporate culture we will support capital investment by charities with proven track records. The Absolute Results Foundation aims to amplify direct financial support of selected charities, as chosen by the Board of the Foundation. These will be charities solving social problems. The Board believes that a focused and concerted effort based on equipping the recipient charity with both funding and manpower for capital projects, plus operational best practices knowledge, will have a lasting impact. The Foundation does not plan to fund operational budgets of charities, instead it will continue a program of knowledge transfer to ensure the sustainability of the chosen charities.
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="section">
				<div class="sectionTitle">Teenage Children Experience</div>
				The Foundation will pay to send teenage children of Absolute Results stakeholders to chosen charities as working volunteers. As parents of first world children, many of Absolute Results stakeholders wish for their children to avoid an entitlement attitude by witnessing social development in action, and by contributing their own physical efforts to projects. One of Absolute Results corporate values is "We get our hands dirty", meaning we are not afraid of doing the work ourselves.

				<br><br>
				<div class="row">
					<div class="col-md-12">
						<div class="sectionTitle">Youth Enrollment</div>
						<div style="font-weight:bold;">To qualify for the Youth Program</div>
						<form id="youthForm" onSubmit="return validateForm()">
							<input type="hidden" id="video" name="video">
							<input type="hidden" id="story" name="story">
							<div class="row">
								<div class="col-md-12">
									<div style="font-weight:bold"></div>
									<ul style="margin:10px 20px;list-style-type: square">
										<li> Youth must be between 12 and 19 years of age</li>
										<li> Parent of youth must be an employee of Absolute Results for at least 6 months.</li>
									</ul>
									Absolute Results Foundation will cover the round-trip airfare to Guatemala, transportation to Hope Of Life and Food & Accommodation while in Hope of Life. Youth responsible for spending cash and other expenses en route.
								</div>
							</div>
							<br>
							<div class="row">
								<div class="col-md-6">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>Employee First Name</label>
												<input class="form-control" type="text" name="employee_firstname" id="employee_firstname" />
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Employee Last Name</label>
												<input class="form-control" type="text" name="employee_lastname" id="employee_lastname" />
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>Phone</label>
												<input class="form-control" type="text" name="phone" id="phone" />
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Email</label>
												<input class="form-control" type="text" name="email" id="email" />
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>Youth First Name</label>
												<input class="form-control" type="text" name="youth_firstname" id="youth_firstname" />
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Youth Last Name</label>
												<input class="form-control" type="text" name="youth_lastname" id="youth_lastname" />
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-3">
											<div class="form-group">
												<label>Youth Age</label>
												<select class="form-control" name="age" id="age">
													<option></option>
									<?php for($i = 12;$i<=19;$i++) { ?>
													<option value="<?= $i ?>"><?= $i ?></option>
									<?php } ?>
												</select>
											</div>
										</div>
										<div class="col-md-9">
											<div class="form-group">
												<label>Passport Expiry Date</label>
												<input class="form-control" type="text" name="passport_expiry" id="passport_expiry" />
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div style="margin-top:10px">
										<font style="font-weight:bold">Tell us your story/reason why you want to be approved for this trip to Guatemala and Hope of Life.</font>
										<br>
										We ask that you submit both a written document and a video explaining why you want to join this trip.
										<div style="font-size:0.8em;font-style:italic;line-height:1.1em;padding:10px 0px 15px 0px">
											<div>Story Supported Formats: <font style="font-weight:bold">.doc .docx</font></div>
											<div>Video Supported Formats: <font style="font-weight:bold">.mp4 .mov</font></div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-12">
											<div style="display:inline-block;margin-right:5px">
										    	<div class="btn btn-primary btn-sm" onClick="$('#fileupload_story').click();">+ Upload Story</div>
										    </div>
										    <div style="display:inline-block;">
										    	<div class="btn btn-primary btn-sm" onClick="$('#fileupload_video').click();">+ Upload Video</div>
										    </div>
										    <div>
											    <input id="fileupload_story" type="file" name="files[]" data-url="scripts/uploader/server/php/" accept="application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" multiple style="display:none">
											    <input id="fileupload_video" type="file" name="files[]" data-url="scripts/uploader/server/php/" accept="video/mp4,video/quicktime" multiple style="display:none">
										    </div>

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
									</div>

									<div class="row" style="padding-top:5px;">
										<div class="col-md-12">
											<input  class="btn btn-success" type="submit" id="submitBtn" value="Submit">
										</div>
									</div>
								</div>
							</div>

						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="section">
				<div class="sectionTitle">Transfer of Best Operational Practices. </div>
				Charities face the same strategic questions, leadership development, and management challenges, as any other human organizations. The Absolute Results stakeholders include experts in all areas of business operations. The Absolute Results growth and success are testimony to the level of combined expertise. The Foundation will facilitate the twinning of our experts with charity leaders in a structured mentoring program.
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="section">
				<div class="sectionTitle">Stakeholders' Sponsored Initiatives </div>
				The fourth program will be to support charities where Absolute Results employees or franchisees have made a real personal commitment.
				<ul style="margin:20px;list-style-type: square;font-size:0.9em">
					<li>Employee or Franchisee must have served at one of the AR companies for at least two years.</li>
					<li>Employee must document at least two years of personal commitment with the charity as a board member or an active volunteer. We will require documentation from the charity confirming their involvement.</li>
					<li>Charity must be a non-political charity registered with the CRA. The charity filings must be current, and audited for large charities, or independently reviewed for smaller ones.</li>
					<li>Only capital projects will be considered, no operational grants will be given. Our focus is to equip the charities for the long term.</li>
					<li>Projects merits will be judged by a committee made of Absolute Results leaders and external advisors who cannot ask for grants themselves.</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="section">
				<div class="sectionTitle">Guatemala Program</div>
				<div style="font-weight:bold">Hope of Life International</div>
				<!-- <a href="downloads/Hope Of Life International.pdf" target="_blank">Experience Transformation Brochure</a> -->
			</div>
		</div>
	</div>
</div>

<?php include_once('footer.php'); ?>