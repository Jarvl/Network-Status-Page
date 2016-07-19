Network Status Page - Linux
===================

A network status page designed to monitor a local Ubuntu server and network activity with forecast.io and CouchPotato integration.

~~[Live site][ls]~~

[ls]: http://hill364.net/
[pft]: http://forums.plexapp.com/index.php/topic/84856-network-status-page/


###Features
---------------
* Responsive web design viewable on desktop, tablet and mobile web browsers 

* Designed using [Bootstrap 3][bs]

* Uses AJAX calls to provide real time data from the server

* Optimized for Linux devices `Tested on Ubuntu 14.04 Trusty Tahr`

* Displays the following:
	* ~~currently playing items from Plex Media Server~~
	* recent activity from CouchPotato
	* current network bandwidth from vnstat
	* current ping to ip of your choosing, e.g. Google DNS
	* online / offline status for custom services
	* minute by minute weather forecast from forecast.io
	* server load
	* total disk space for all hard drives

* Now Playing section adjusts scrollable height on the fly depending on browser window height


[bs]: http://getbootstrap.com


###Screenshots
---------------
![alt tag](http://d.pr/i/1hfF8+)

![alt tag](http://d.pr/i/1eTEu+)


###Requirements
---------------
* ~~[Plex Media Server][pms] (v0.9.8+) and a [myPlex][pp] account `These are both free.`~~
* The weather sidebar requires a [forecast.io API key][fcAPI] `Free up to 1000 calls/day.`
* Web server that supports php (apache, nginx, XAMPP, WampServer, EasyPHP, lighttpd, etc)
* PHP 5.4
* Make sure to install vnstat using `sudo apt-get install vnstat`.

[pms]: https://plex.tv
[pp]: https://plex.tv/subscription/about
[fcAPI]: https://developer.forecast.io


###Additional Notes

This fork of Network-Status-Page is an adaptation for Linux (specifically Ubuntu). I've removed all of the pfSense and OS X specific code, as well as support for Plex (never use it, didn't work the way I wanted it to). Feel free to contribute! Huge thanks to [d4rk22][d4rk22] for making this awesome application.

[d4rk22]: https://github.com/d4rk22
