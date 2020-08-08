# onkyo_webcontrol
Control your onkyo receiver by browser

I have written these php-files to navigate by browser through the contents of a DLNA-server and play the music files on the onkyo receiver. Your webserver communicates with the receiver by EISCP-protocol.

Prerequisites:
- Webserver (e.g. ngninx)
- DLNA-Server (e.g. minidlna)
- Onkyo receiver connected to your network (tested with TX-NR636)

Installation:
- Copy the php-files to your webserver
- Navigate to <web-path>/dlna.php. This can be done with any device (PC, smartphone, ...)
- Insert Onkyo's IP
- Net-Input is selected automatically
- Search and select your DLNA-Server (need to retry several times)
- Go through the content of your DLNA-Server and play your selected file
- Navigate to <web-path>/status.php
- Here you can change track, pause, play, change the volume and switch your receiver off

This is at the moment proof-of-concept work!
