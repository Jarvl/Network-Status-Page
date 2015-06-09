Network Status Page - Linux
===================

Designed to monitor a local server and network with forecast.io and Plex.

[Live site][ls]

[Plex forum thread][pft]

[ls]: http://hill364.net/
[pft]: http://forums.plexapp.com/index.php/topic/84856-network-status-page/


###Features
---------------
* Responsive web design viewable on desktop, tablet and mobile web browsers 

* Designed using [Bootstrap 3][bs]

* Uses jQuery to provide near real time feedback

* Optimized for Linux devices `Tested on Ubuntu 14.04 Trusty Tahr`

* Displays the following:
	* currently playing items from Plex Media Server
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
* [Plex Media Server][pms] (v0.9.8+) and a [myPlex][pp] account `These are both free.`
* The weather sidebar requires a [forecast.io API key][fcAPI] `Free up to 1000 calls/day.`
* Web server that supports php (apache, nginx, XAMPP, WampServer, EasyPHP, lighttpd, etc)
* PHP 5.4
* Make sure to install vnstat using `sudo apt-get install vnstat`.

[pms]: https://plex.tv
[pp]: https://plex.tv/subscription/about
[fcAPI]: https://developer.forecast.io


###Additional Notes

This fork of Network-Status-Page is an adaptation for Linux (specifically Ubuntu). I also removed all of the code that uses pfSense, as well as OS X specific code. Feel free to clone if you're using Linux! Huge thanks to [d4rk22][d4rk22] for making this awesome application.

[d4rk22]: https://github.com/d4rk22