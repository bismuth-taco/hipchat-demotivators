<html>
	<head></head>
	
	<body>
		
<?php
$hello = "HipChat Demotivator Setup<hr>";
echo $hello;
?>

	<p>
		This is a simple addon for HipChat that allows users to post random demotivators with "/demotivator foo".  
		It searches the Google Image Search API for foo and posts the first image found. 
	</p>
	
	<p>
		To get started you'll need to set up a webhook with HipChat.  The callback URL has been prefilled based on 
		the current Window's URL.  If you are running this file on localhost, you probably want to set up a requestb.in or similar and paste the data into <a href='debug.php'>debug.php</a> instead.
	</p>
	
	
	<!--script src="../lib/jquery-1.8.2.min.js" ></script-->
    
	<script>
	</script>
	
	</body>
</html>