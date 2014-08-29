<?php

// PHP's curious syntax for getting the POST body
echo webhook(file_get_contents('php://input')); 

// respond to incoming data
function webhook($data) {
	$data = json_decode($data, true);
	$img = get_img_url($data);
	$token = get_token($data);
	$room = get_room($data);
	
	// TODO: only set the width if the image is > 600px wide
	$message = "Demoti-bot found the following highly relevant image for you:<br><img width='600' src='{$img}' />";
	
	return hipchat_send_msg($message, $room, $token);
}

// find the image we want to send back
function get_img_url($data) {
	// search Google's image API for a vaguely appropriate image
	$searchterm = substr($data['item']['message']['message'], 1); // strip off the "/" at the beginning
	$r = file_get_contents("https://ajax.googleapis.com/ajax/services/search/images?v=1.0&q=".rawurlencode($searchterm)); // raw encode to get %20 for spaces
	$response = json_decode($r, true);
	
	$img = $response['responseData']['results'][0]['unescapedUrl'];
	return $img;	
}

// tease out the oauth token we should be posting back with 
function get_token($data) {
	// in this api, we just send it as a URL param, ignore $data
	return $_REQUEST['token'];
}

// figure out what room to post to
function get_room($data) {
	// the room name is buried deep in the JSON payload
	return $data['item']['room']['name'];
}

// send a message off to a Hipchat chatroom
function hipchat_send_msg($message, $room, $token) {
	// auth_token in the URL again	
	$url = "https://api.hipchat.com/v2/room/{$room}/notification?auth_token=$token";
	// curl requires the payload as an array
	// other options are available, e.g. text-only, see https://www.hipchat.com/docs/apiv2/method/send_room_notification
	$data = array("message" => $message);
	
	// the curl function does all the hard work	
	return curl_hipchat_post($url, $data);
}

// nitty gritty curl details
function curl_hipchat_post($url, $data) {
	global $CACERT_PATH; // required on my computer, ymmv
		
	// api from https://www.hipchat.com/docs/apiv2/method/send_room_notification, should work with other URLs too
	$ch = curl_init();
	$data_string = json_encode($data);
	setup_cacert($ch);	
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
	    'Content-Type: application/json',                                                                                
	    'Content-Length: ' . strlen($data_string))                                                                       
	);
	
	// send the request and close the handle
	$body = curl_exec($ch);
	curl_close($ch);
	
	// errors will be fed back here
	return $body;
}

// on the developer's computer this does not work automatically for some reason
function setup_cacert($ch) {
	if (strpos($_SERVER["DOCUMENT_ROOT"], 'rad') !== false) { // brad wamp
		curl_setopt($ch, CURLOPT_CAINFO, "C:/Users/Brad/Documents/GitHub/swish/swish-backend/shipping/vip_console/cacert.pem");
	}
}

?>