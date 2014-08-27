# hipchat-demotivators

A HipChat add-on that allows any user to type e.g. `/demotivator pants` and have a relevant image posted to the channel.

Requirements
------------

* PHP for the callback

Usage
-----

Open setup.html in your browser to create, view, and delete demotivator callbacks.

You'll need an internet-accessible place to host the php callback file (webhook-endpoint.php).

(Planned future feature) Testing this library without hosting
-------------------------------------------------------------

If you'd just like to see how the library works, you can get an ephemeral bin from http://requestb.in .  

1. Use this as your webhook URL
2. Activate the webhook by posting a relevant message in the HipChat room
3. Copy the raw body from RequestBin and navigate to test.php
4. Paste the raw json, click Submit, and your image should appear in the HipChat room