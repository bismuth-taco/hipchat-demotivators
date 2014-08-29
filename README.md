# hipchat-demotivators

A HipChat add-on that allows any user to type e.g. `/demotivator pants` and have a relevant image posted to the channel.

Requirements
------------

* PHP for the callback

Usage
-----

Get your API key from HipChat (see setup.html for details)

Open setup.html in your browser to create, view, and delete demotivator callbacks.

After they're ready, just say `/demotivator whatever` in your chat room for a fun picture.

Testing/Using this library without hosting or PHP
-------------------------------------------------------------

If you'd just like to see how the library works, you can get an ephemeral bin from http://requestb.in .  

1. Use the bin as your webhook URL
2. Activate the webhook by posting a relevant message in the HipChat room
3. Copy the raw body from RequestBin
4. Paste the token and raw json into setup.html, click Submit, and your image should appear in the HipChat room