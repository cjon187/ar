<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script>
	function loadMore(ld)
	{
		$.ajax({data:	{loadMore: ld},
				type:	'POST',
				dataType: 'script'
			   });
	}
</script>
<center>
<div style="width:100%">
<font style="font-weight:bold;font-size:13pt"><?= $_SESSION['pictures']['title'] ?></font>
<br><br>
<?php	

foreach($_SESSION['pictures']['imgList'] as $key => $file)
{
	if(in_array($file,array('.','..','Thumbs.db'))) unset($_SESSION['pictures']['imgList'][$key]);
}

if(count($_SESSION['pictures']['imgList']) > 0)
{				
?>			
	<font style="font-size:1em"><i>* Note: You may click on the image to view</i></font>
	<br><br>
	
	<div id="imagesDiv"></div>
	<script>loadMore('more');</script>
<?php			
}
else echo '<font style="font-family:arial;font-size:1em">No Images Found</font>';
	
?>
</div>
</center><br>
<div id="loadMoreDiv"></div>