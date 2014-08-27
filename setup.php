<html>
<head>
		<title>HipChat Demotivator Setup</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" media="screen" />
		<style type="text/css">
		  body {
		  	padding:5em;
		  }
		</style>
	</head>
	<body>
		
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" ></script>
		
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
		the current Window's URL.  If you are running this file on localhost, you probably want to set up a <a href='http://requestb.in'>requestb.in</a> or similar and paste the data into <a href='debug.php'>debug.php</a> instead.
	</p>
	Token:<input id='token'><br>
	Webhook URL:<input id='webhookurl' value='http://requestb.in/1f3ncog1'><br>
	Room:<input id='room' value='Theswish'><br>
	<button id='addwebhook' name='addwebhook'>Add Webhook</button>
	
	<br><br>
	<textarea id='currenthooks'></textarea><br>
	<button id='getcurrenthooks' name='getcurrenthooks'>Get current webhooks</button>
	
	<script>
	
		// click handlers for the buttons
		$('#addwebhook').click(function() {
			addwebhook();
		});
		
		$('#getcurrenthooks').click(function() {
			getcurrenthooks();
		});
		
		// add a webhook -- https://www.hipchat.com/docs/apiv2/method/create_webhook
		function addwebhook() {
			var token = $('#token').val();
			var url = $('#webhookurl').val();
			var room = $('#room').val();

			var param = {
				url: url,
				pattern: "/mot.*",
				event: "room_message",
				name: (Math.random() + "").substr(2)
			};	
			$.ajax({
				type: "POST",
			  	url: "https://api.hipchat.com/v2/room/"+room+"/webhook?auth_token="+token,
				jsonp: 'callback',
		  	  	data: JSON.stringify(param), // must be a JSON payload to add webhook
		  	  	contentType:"application/json; charset=utf-8",
  				success: function (data) {
					console.log('b');
		  	  	}
			});
			console.log('a');
		}
		
		// get all webhooks -- https://www.hipchat.com/docs/apiv2/method/get_all_webhooks
		// TODO: actually put this data into the textarea
		function getcurrenthooks() {
			var token = $('#token').val();
			var url = $('#webhookurl').val();
			var room = $('#room').val();

			// these are optional
			var param = {
				"start-index": 0,
				"max-results": 100
			};	
			// request must be GET with urlencoded parameters
			$.ajax({
				type: "GET",
				url: "https://api.hipchat.com/v2/room/"+room+"/webhook?auth_token="+token,
			  	jsonp: 'callback',
		  	  	data: param, // must be URL encoded to get webhooks
  				success: function (data) {
					console.log('b');
		  	  	}
			});
			console.log('a');
		}
		
	</script>
	
	</body>
</html>