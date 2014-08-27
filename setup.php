<html>
	<head>
		<title>HipChat Demotivator Setup</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" media="screen" />
		<style type="text/css">
		  body {
		  	padding:5em;
		  }
		  input {
		  	width: 300px;
		  }
		  textarea {
		  	width: 700px;
		  	height: 10em;
		  }
		</style>
	</head>
	<body>
		
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" ></script>
		
	<h4>
		HipChat Demotivator Setup
	</h4>
	<hr>
	
	<p>
		This is a simple addon for HipChat that allows users to post random demotivators with "/demotivator foo".  
		It searches the Google Image Search API for `foo` and posts the first image found to the room. 
	</p>
	
	<p>
		To get started you'll need to set up a webhook with HipChat.  The callback URL has been prefilled based on 
		the current URL for this browser window.
		If you are running this file on localhost, you probably want to set up a <a href='http://requestb.in'>requestb.in</a> 
		or similar and paste the data into <a href='debug.php'>debug.php</a> instead.
	</p>
	<input id='token' placeholder='Token'> Token<br>
	<input id='webhookurl' value='http://requestb.in/1f3ncog1'> Webhook URL<br>
	<input id='room' value='Theswish'> Room<br>
	<button id='addwebhook' name='addwebhook'>Add Webhook</button>
	
	<br><br>
	Get current webhooks<br>
	<textarea id='currenthooks' readonly></textarea><br>
	<button id='getcurrenthooks' name='getcurrenthooks'>Get current webhooks</button>
	
	<br><br>
	Delete a webhook<br>
	<input id='deleteid' placeholder='ID'> ID<br>
	<button id='deletehook' name='deletehook'>Delete webhook</button>
	
	
	<script>
	
		// click handlers for the buttons
		$(document).ready( function() {
			$('#addwebhook').click(function() {
				addwebhook();
			});
			
			$('#getcurrenthooks').click(function() {
				getcurrenthooks();
			});
			
			$('#deletehook').click(function() {
				deletewebhook();
			});
		});
			
		// add a webhook -- https://www.hipchat.com/docs/apiv2/method/create_webhook
		function addwebhook() {
			var token = $('#token').val();
			var baseurl = $('#webhookurl').val();
			var room = $('#room').val();

			// add the token onto the webhook url so that it can post the images with proper credentials
			// depending on whether baseurl already contains a '?', use an ampersand or question mark to append the token parameter 
			url =  baseurl + ((baseurl.indexOf("?") > -1) ? "&" : "?" ) + "token="+token;

			var param = {
				url: url,
				pattern: "/mot.*",
				event: "room_message",
				name: "hcdm-" + Math.random()
			};	
			$.ajax({
				type: "POST",
			  	url: "https://api.hipchat.com/v2/room/"+room+"/webhook?auth_token="+token,
				jsonp: 'callback',
		  	  	data: JSON.stringify(param), // must be a JSON payload to add webhook
		  	  	contentType:"application/json; charset=utf-8",
  				success: function (data) {
					getcurrenthooks();
					alert("Webhook created successfully!");
		  	  	},
		  	  	error: function (data) {
		  	  		console.log(data);
		  	  		alert("Failed to create webhook -- see console for data dump");
		  	  	}
			});
		}
		
		// get all webhooks -- https://www.hipchat.com/docs/apiv2/method/get_all_webhooks
		function getcurrenthooks() {
			var token = $('#token').val();
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
					var str = "";
					$(data['items']).each( function() {
						// only show HipChatDeMotivator callbacks
						if (this['name'].slice(0, 5) == "hcdm-") {
							line = this['id'] + " - " + this['pattern'] + " - " + this['url'] + "&#13;";
							str += line;
						}
					});
					$('#currenthooks').html(str);
		  	  	},
		  	  	error: function (data) {
		  	  		console.log(data);
		  	  		alert("Failed to get webhooks -- see console for data dump");
		  	  	}
			});
		}
		
		// delete webhook -- https://www.hipchat.com/docs/apiv2/method/delete_webhook
		function deletewebhook() {
			var token = $('#token').val();
			var room = $('#room').val();
			var hookid = $('#deleteid').val();

			// request must be DELETE
			$.ajax({
				type: "DELETE",
				url: "https://api.hipchat.com/v2/room/"+room+"/webhook/"+hookid+"?auth_token="+token,
				// no data for a DELETE
  				success: function (data) {
					getcurrenthooks();
					alert("Webhook deleted successfully!");
		  	  	},
		  	  	error: function (data) {
		  	  		console.log(data);
		  	  		alert("Failed to delete webhook -- see console for data dump");
		  	  	}
			});
		}
		
	</script>
	
	</body>
</html>