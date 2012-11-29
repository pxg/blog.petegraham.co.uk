<?php

function head()
{
	ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<title>petegraham.co.uk - The Pete Graham Website</title>

<link rel="stylesheet" href="/main/css/style.css" type="text/css" media="screen" />

</head>


<body>
	<div class="content">
			<div class="preheader">
				<div class="padding">
					<a href="http://petegraham.co.uk">Home</a>&nbsp;|&nbsp;
					<a href="http://blog.petegraham.co.uk">Peteamania</a>&nbsp;|&nbsp;

					<a href="http://tech.petegraham.co.uk">Optimus Pete</a>

				</div>
			</div>
		
			<div class="header">
				<div class="title"><a href="http://petegraham.co.uk"><span id="one">Pete</span><span id="two">Graham</span><span id="three">.co</span><span id="three">.uk</span></a></div>
				<div class="slogan">Your #1 resource for Pete Graham information</div>
			</div>

			<div id="nav"></div>

<div class="main_content">
	<div class="sd_right">
	<div class="text_padding">

<?php
echo sidebar_content();
?>
	</div>
</div>	<div class="sd_left">
<?php
	$head = ob_get_clean();

	return $head;
}


function foot()
{
	ob_start();
?>
<!--
		</div>
	 -->	

	</div>
	
	<hr style="width:760px; color:black;" />
<div style="text-align:center"> 
	<a href="http://petegraham.co.uk">Home</a>&nbsp;|&nbsp;

	<a href="http://blog.petegraham.co.uk">Peteamania</a>&nbsp;|&nbsp;
	<a href="http://tech.petegraham.co.uk">Optimus Pete</a>
</div>


<div class="footer">
			<div class="padding">
			&nbsp;</div>
</div>
			
				
			</div>

	</div>
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "UA-1161798-1";
urchinTracker();
</script>
	
</body>
</html>

<?php
	$foot = ob_get_clean();
	
	return $foot;
}

function sidebar_content()
{
	ob_start();
?>
	<h2>News</h2>
	
	<p><strong>18/01/07 - </strong>I have finally got around to styling this homepage so its styling matches the blogs, oh and it doesn't look like it was made in 1997 anymore!</p>

	<p><strong>16/01/07 - </strong>We launched the Beta version of <a href="http://phuser.com">phuser.com</a> today. phuser is the mobile/social networking website that I have been working developing. We need people to trial the site so request an invite on the <a href="http://phuser.com">phuser Homepage</a> if your interested.</p> 
<?php
	$content = ob_get_clean();

	return $content;
}

?>
