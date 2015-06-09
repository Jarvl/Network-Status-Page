<?php

$config_path = '/var/www/hill364.net/config.ini'; //path to config file, recommend you place it outside of web root

Ini_Set( 'display_errors', true);
include '../../init.php';
include 'lib/phpseclib0.3.5/Net/SSH2.php';
$config = parse_ini_file($config_path);


// Import variables from config file

// Network Details
$wan_domain = $config['wan_domain'];
$wan1_ip = $config['wan1_ip'];
$ping_ip = $config['ping_ip'];
$plex_server_ip = $config['plex_server_ip'];
$plex_port = $config['plex_port'];

// Credentials
$plex_username = $config['plex_username'];
$plex_password = $config['plex_password'];
$trakt_username = $config['trakt_username'];

// API Keys
$forecast_api = $config['forecast_api'];

// Misc
$cpu_cores = $config['cpu_cores'];
$weather_always_display = $config['weather_always_display'];
$weather_lat = $config['weather_lat'];
$weather_long = $config['weather_long'];
$weather_name = $config['weather_name'];

// Set the path for the Plex Token
$plexTokenCache = ROOT_DIR . '/assets/caches/plex_token.txt';
// Check to see if the plex token exists and is younger than one week
// if not grab it and write it to our caches folder
if (file_exists($plexTokenCache) && (filemtime($plexTokenCache) > (time() - 60 * 60 * 24 * 7))) {
	$plexToken = file_get_contents(ROOT_DIR . '/assets/caches/plex_token.txt');
} else {
	file_put_contents($plexTokenCache, getPlexToken());
	$plexToken = file_get_contents(ROOT_DIR . '/assets/caches/plex_token.txt');
}

// Calculate server load
if (strpos(strtolower(PHP_OS), "Darwin") === false)
	$loads = sys_getloadavg();
else
	$loads = Array(0.55,0.7,1);

$mainHDDTotalSpace = 79999008768; // in bytes


// This is if you want to get a % of cpu usage in real time instead of load.
// After using it for a week I determined that it gave me a lot less information than load does.
function getCpuUsage()
{
	$top = shell_exec('top -l 1 -n 0');
	$findme = 'idle';
	$cpuIdleStart = strpos($top, $findme);
	$cpuIdle = substr($top, ($cpuIdleStart - 7), 2);
	$cpuUsage = 100 - $cpuIdle;
	return $cpuUsage;
}

function makeCpuBars()
{
	printBar(getCpuUsage(), "Usage");
}	

function makeTotalDiskSpace()
{
	$du = getDiskspaceUsed("/")/* + getDiskspaceUsed("/Volumes/Time Machine") + getDiskspaceUsed("/Volumes/Isengard") + getDiskspaceUsed("/Volumes/1TB Portable") + getDiskspaceUsed("/Volumes/WD2.2") + getDiskspaceUsed("/Volumes/WD2.1") + getDiskspaceUsed("/Volumes/Barad-dur") + getDiskspaceUsed("/Volumes/Erebor") + getDiskspaceUsed("/Volumes/Television") + getDiskspaceUsed("/Volumes/Television 2")*/;
	$dts = disk_total_space("/")/* + disk_total_space("/Volumes/Time Machine") + disk_total_space("/Volumes/Isengard") + disk_total_space("/Volumes/1TB Portable") + disk_total_space("/Volumes/WD2.2") + disk_total_space("/Volumes/WD2.1") + disk_total_space("/Volumes/Barad-dur") + $GLOBALS['ereborTotalSpace'] + $GLOBALS['televisionTotalSpace'] + $GLOBALS['television2TotalSpace']*/;
	$dfree = $dts - $du;
	printTotalDiskBar(sprintf('%.0f',($du / $dts) * 100), "Total Capacity", $dfree, $dts);
}

function byteFormat($bytes, $unit = "", $decimals = 2) {
	$units = array('B' => 0, 'KB' => 1, 'MB' => 2, 'GB' => 3, 'TB' => 4, 
			'PB' => 5, 'EB' => 6, 'ZB' => 7, 'YB' => 8);
 
	$value = 0;
	if ($bytes > 0) {
		// Generate automatic prefix by bytes 
		// If wrong prefix given
		if (!array_key_exists($unit, $units)) {
			$pow = floor(log($bytes)/log(1000));
			$unit = array_search($pow, $units);
		}
 
		// Calculate byte value by prefix
		$value = ($bytes/pow(1000,floor($units[$unit])));
	}
 
	// If decimals is not numeric or decimals is less than 0 
	// then set default value
	if (!is_numeric($decimals) || $decimals < 0) {
		$decimals = 2;
	}
 
	// Format output
	return sprintf('%.' . $decimals . 'f '.$unit, $value);
  }

  function autoByteFormat($bytes) {
  	// If we are working with more than 0 and less than 1000GB (Apple filesystem).
  	if (($bytes >= 0) && ($bytes < 1000000000000)) {
  		$unit = 'GB';
  		$decimals = 0;
  	// 1TB to 999TB
   	} elseif (($bytes >= 1000000000000) && ($bytes < 1.1259e15)) {
   		$unit = 'TB';
   		$decimals = 2;
   	}
   	return array($bytes, $unit, $decimals);
  }

function makeDiskBars()
{
	// For special drives like my Drobos I have to set the total disk space manually.
	// That is why you see the total space in bytes.

	// TODO: Figure out why /dev/sda1 has wrong values for free and total space
	printDiskBar(getDiskspace("/"), "Primary HDD", disk_free_space("/"), disk_total_space("/"));
	//printDiskBar(getDiskspace("/Volumes/Time Machine"), "Time Machine", disk_free_space("/Volumes/Time Machine"), disk_total_space("/Volumes/Time Machine"));
	//printDiskBar(getDiskspace("/Volumes/Isengard"), "Isengard", disk_free_space("/Volumes/Isengard"), disk_total_space("/Volumes/Isengard"));
	//printDiskBar(getDiskspace("/Volumes/WD2.2"), "Minas Tirith", disk_free_space("/Volumes/WD2.2"), disk_total_space("/Volumes/WD2.2"));
	//printDiskBar(getDiskspace("/Volumes/WD2.1"), "Minas Morgul", disk_free_space("/Volumes/WD2.1"), disk_total_space("/Volumes/WD2.1"));
	//printDiskBar(getDiskspace("/Volumes/Barad-dur"), "Barad-dûr", disk_free_space("/Volumes/Barad-dur"), disk_total_space("/Volumes/Barad-dur"));
	//printDiskBar(getDiskspaceErebor("/Volumes/Erebor"), "Erebor", ($GLOBALS['ereborTotalSpace'] - getDiskspaceUsed("/Volumes/Erebor")), $GLOBALS['ereborTotalSpace']);
	//printDiskBar(getDiskspaceTV1("/Volumes/Television"), "Narya", ($GLOBALS['televisionTotalSpace'] - getDiskspaceUsed("/Volumes/Television")), $GLOBALS['televisionTotalSpace']);
	//printDiskBar(getDiskspaceTV2("/Volumes/Television 2"), "Nenya", ($GLOBALS['television2TotalSpace'] - getDiskspaceUsed("/Volumes/Television 2")), $GLOBALS['television2TotalSpace']);
}

function makeRamBars()
{
	printRamBar(getFreeRam()[0],getFreeRam()[1],getFreeRam()[2],getFreeRam()[3]);
}

function makeLoadBars()
{
	printBar(getLoad(0), "1 min");
	printBar(getLoad(1), "5 min");
	printBar(getLoad(2), "15 min");
}

function getFreeRam()
{
	// This is newly customized for Linux
	// Keep in mind that this is using MemAvailable not MemFree

	// Parse Meminfo file and store in array (specific to Linux)
	$data = explode("\n", file_get_contents("/proc/meminfo"));
    $meminfo = array();
    foreach ($data as $line) {
    	list($key, $val) = explode(":", $line);
    	// delete kB in meminfo output and trim
    	$meminfo[$key] = trim(str_replace("kB", "", $val));
    }
	
	// Grab the unused ram amount
	$availableRam = $meminfo["MemAvailable"]/1024/1024; // GB
	
	// What is the total ram in the computer
	$totalRam = $meminfo["MemTotal"]/1024/1024; // GB
	
	// Find the amount of used ram
	$usedRam = $totalRam - $availableRam; // Find how much ram is used in GB.
	
	return array (sprintf('%.0f',($usedRam / $totalRam) * 100), 'Used Ram', $usedRam, $totalRam);
}

function getDiskspace($dir)
{
	$df = disk_free_space($dir);
	$dt = disk_total_space($dir);
	$du = $dt - $df;
	return sprintf('%.0f',($du / $dt) * 100);
}
/*
function getDiskspaceErebor($dir)
{
	$df = disk_free_space($dir);
	$dt = disk_total_space($dir);
	$du = $dt - $df;
	return sprintf('%.0f',($du / $GLOBALS['ereborTotalSpace']) * 100);
}
*/
function getDiskspaceUsed($dir)
{
	$df = disk_free_space($dir);
	$dt = disk_total_space($dir);
	$du = $dt - $df;
	return $du;
}
/*
function getDiskspaceTV1($dir)
{
	$df = disk_free_space($dir);
	$dt = disk_total_space($dir);
	$du = $dt - $df;
	return sprintf('%.0f',($du / $GLOBALS['televisionTotalSpace']) * 100);
}

function getDiskspaceTV2($dir)
{
	$df = disk_free_space($dir);
	$dt = disk_total_space($dir);
	$du = $dt - $df;
	return sprintf('%.0f',($du / $GLOBALS['television2TotalSpace']) * 100);
}
*/
function getLoad($id)
{
	global $cpu_cores;
	return 100 * ($GLOBALS['loads'][$id] / $cpu_cores);
}

function printBar($value, $name = "")
{
	if ($name != "") echo '<!-- ' . $name . ' -->';
	echo '<div class="exolight">';
		if ($name != "")
			echo $name . ": ";
			echo number_format($value, 0) . "%";
		echo '<div class="progress">';
			echo '<div class="progress-bar" style="width: ' . $value . '%"></div>';
		echo '</div>';
	echo '</div>';
}

function printRamBar($percent, $name = "", $used, $total)
{
	if ($percent < 90)
	{
		$progress = "progress-bar";
	}
	else if (($percent >= 90) && ($percent < 95))
	{
		$progress = "progress-bar progress-bar-warning";
	}
	else
	{
		$progress = "progress-bar progress-bar-danger";
	}

	if ($name != "") echo '<!-- ' . $name . ' -->';
	echo '<div class="exolight">';
		if ($name != "")
			echo $name . ": ";
			echo number_format($percent, 0) . "%";
		echo '<div rel="tooltip" data-toggle="tooltip" data-placement="bottom" title="' . number_format($used, 2) . ' GB / ' . number_format($total, 0) . ' GB" class="progress">';
			echo '<div class="'. $progress .'" style="width: ' . $percent . '%"></div>';
		echo '</div>';
	echo '</div>';
}

function printDiskBar($dup, $name = "", $dsu, $dts)
{
	// Using autoByteFormat() the amount of space will be formatted as GB or TB as needed.
	if ($dup < 90)
	{
		$progress = "progress-bar";
	}
	else if (($dup >= 90) && ($dup < 95))
	{
		$progress = "progress-bar progress-bar-warning";
	}
	else
	{
		$progress = "progress-bar progress-bar-danger";
	}

	if ($name != "") echo '<!-- ' . $name . ' -->';
	echo '<div class="exolight">';
		if ($name != "")
			echo $name . ": ";
			echo number_format($dup, 0) . "%";
		echo '<div rel="tooltip" data-toggle="tooltip" data-placement="bottom" title="' . byteFormat(autoByteFormat($dsu)[0], autoByteFormat($dsu)[1], autoByteFormat($dsu)[2]) . ' free out of ' . byteFormat(autoByteFormat($dts)[0], autoByteFormat($dts)[1], autoByteFormat($dts)[2]) . '" class="progress">';
			echo '<div class="'. $progress .'" style="width: ' . $dup . '%"></div>';
		echo '</div>';
	echo '</div>';
}

function printTotalDiskBar($dup, $name = "", $dsu, $dts)
{
	// Using autoByteFormat() the amount of space will be formatted as GB or TB as needed.
	if ($dup < 95)
	{
		$progress = "progress-bar";
	}
	else if (($dup >= 95) && ($dup < 99))
	{
		$progress = "progress-bar progress-bar-warning";
	}
	else
	{
		$progress = "progress-bar progress-bar-danger";
	}

	if ($name != "") echo '<!-- ' . $name . ' -->';
	echo '<div class="exolight">';
		if ($name != "")
			echo $name . ": ";
			echo number_format($dup, 0) . "%";
		echo '<div rel="tooltip" data-toggle="tooltip" data-placement="bottom" title="' . byteFormat(autoByteFormat($dsu)[0], autoByteFormat($dsu)[1], autoByteFormat($dsu)[2]) . ' free out of ' . byteFormat(autoByteFormat($dts)[0], autoByteFormat($dts)[1], autoByteFormat($dts)[2]) . '" class="progress">';
			echo '<div class="'. $progress .'" style="width: ' . $dup . '%"></div>';
		echo '</div>';
	echo '</div>';
}

function ping()
{
	global $ping_ip;

	$clientIP = get_client_ip();
	$pingIP = '8.8.8.8';

	$terminal_output = shell_exec('ping -c 5 -q '.$ping_ip);
	// If using something besides OS X you might want to customize the following variables for proper output of average ping.
	$findme_start = '= ';
	$start = strpos($terminal_output, $findme_start);
	$ping_return_value_str = substr($terminal_output, ($start +2), 100);
	$findme_stop1 = '.';
	$stop = strpos($ping_return_value_str, $findme_stop1);
	$findme_avgPing_decimal = '.';
	$avgPing_decimal = strpos($ping_return_value_str, $findme_avgPing_decimal, 6);
	$findme_forward_slash = '/';
	$avgPing_forward_slash = strpos($ping_return_value_str, $findme_forward_slash);
	$avgPing = substr($ping_return_value_str, ($stop + 5), ($avgPing_decimal - $avgPing_forward_slash - 1));
	return $avgPing;
}

function getNetwork($subdomain = "")
{
	// It should be noted that this function is designed specifically for getting the local / wan name for Plex.
	// I kept this function here so I didn't have to refactor a bunch of code
	global $wan_domain;
	if ($subdomain != "") {
		$network='http://'.$subdomain.".".$wan_domain;
	}
	else {
		$network='http://'.$wan_domain;
	}
	return $network;
}

function get_client_ip() 
{
	if ( isset($_SERVER["REMOTE_ADDR"])) { 
		$ipaddress = $_SERVER["REMOTE_ADDR"];
	}else if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
		$ipaddress = $_SERVER["HTTP_X_FORWARDED_FOR"];
	}else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
		$ipaddress = $_SERVER["HTTP_CLIENT_IP"];
	} 
	return $ipaddress;
}

function makeRecenlyViewed()
{
	//global $local_pfsense_ip;
	global $trakt_username;
	global $weather_lat;
	global $weather_long;
	global $weather_name;
	$network = getNetwork();
	$plexNetwork = getNetwork("plex");
	$clientIP = get_client_ip();
	$plexSessionXML = simplexml_load_file($plexNetwork.'/status/sessions');
	$trakt_url = 'http://trakt.tv/user/'.$trakt_username.'/widgets/watched/all-tvthumb.jpg';
	$traktThumb = '/var/www/hill364.net/public_html/assets/caches/thumbnails/all-tvthumb.jpg';

	echo '<div class="col-md-12">';
	echo '<a href="http://trakt.tv/user/'.$trakt_username.'" class="thumbnail">';
	if (file_exists($traktThumb) && (filemtime($traktThumb) > (time() - 60 * 15))) {
		// Trakt image is less than 15 minutes old.
		// Don't refresh the image, just use the file as-is.
		echo '<img src="'.$network.'/assets/caches/thumbnails/all-tvthumb.jpg" alt="trakt.tv" class="img-responsive"></a>';
	} else {
		// Either file doesn't exist or our cache is out of date,
		// so check if the server has different data,
		// if it does, load the data from our remote server and also save it over our cache for next time.
		$thumbFromTrakt_md5 = md5_file($trakt_url);
		$traktThumb_md5 = md5_file($traktThumb);
		if ($thumbFromTrakt_md5 === $traktThumb_md5) {
			echo '<img src="'.$network.'/assets/caches/thumbnails/all-tvthumb.jpg" alt="trakt.tv" class="img-responsive"></a>';
		} else {
			$thumbFromTrakt = file_get_contents($trakt_url);
			file_put_contents($traktThumb, $thumbFromTrakt, LOCK_EX);
			echo '<img src="'.$network.'/assets/caches/thumbnails/all-tvthumb.jpg" alt="trakt.tv" class="img-responsive"></a>';

		}
	}
	// This checks to see if you are inside your local network. If you are it gives you the forecast as well.
	//if($clientIP == $local_pfsense_ip && count($plexSessionXML->Video) == 0) {
		echo '<hr>';
		echo '<h1 class="exoextralight" style="margin-top:5px;">';
		echo 'Forecast</h1>';
		echo '<iframe id="forecast_embed" type="text/html" frameborder="0" height="245" width="100%" src="http://forecast.io/embed/#lat='.$weather_lat.'&lon='.$weather_long.'&name='.$weather_name.'"> </iframe>';
	//}
	echo '</div>';
}

function makeRecenlyReleased()
{
	// Various items are commented out as I was playing with what information to include.
	$network = getNetwork("plex");
	$clientIP = get_client_ip();
	$plexNewestXML = simplexml_load_file($network.'/library/recentlyAdded');
	
	//echo '<div class="col-md-10 col-sm-offset-1">';
	echo '<div class="col-md-12">';
	echo '<div id="carousel-example-generic" class=" carousel slide">';
	echo '<div class="thumbnail">';
	echo '<!-- Wrapper for slides -->';
	echo '<div class="carousel-inner">';
	echo '<div class="item active">';
	$mediaKey = $plexNewestXML->Video[0]['key'];
	$mediaXML = simplexml_load_file($network.$mediaKey);
	$movieTitle = $mediaXML->Video['title'];
	$movieArt = $mediaXML->Video['thumb'];
	echo '<img src="plex.php?img='.urlencode($network.$movieArt).'" alt="'.$movieTitle.'">';
	echo '</div>'; // Close item div
	$i=1;
	for ( ; ; ) {
		if($i==15) break;
		$mediaKey = $plexNewestXML->Video[$i]['key'];
		$mediaXML = simplexml_load_file($network.$mediaKey);
		$movieTitle = $mediaXML->Video['title'];
		$movieArt = $mediaXML->Video['thumb'];
		$movieYear = $mediaXML->Video['year'];
		echo '<div class="item">';
		echo '<img src="plex.php?img='.urlencode($network.$movieArt).'" alt="'.$movieTitle.'">';
		//echo '<div class="carousel-caption">';
		//echo '<h3>'.$movieTitle.$movieYear.'</h3>';
		//echo '<p>Summary</p>';
		//echo '</div>';
		echo '</div>'; // Close item div
		$i++;
	}
	echo '</div>'; // Close carousel-inner div
	echo '</div>'; // Close thumbnail div
	echo '<!-- Controls -->';
	echo '<a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">';
	//echo '<span class="glyphicon glyphicon-chevron-left"></span>';
	echo '</a>';
	echo '<a class="right carousel-control" href="#carousel-example-generic" data-slide="next">';
	//echo '<span class="glyphicon glyphicon-chevron-right"></span>';
	echo '</a>';
	echo '</div>'; // Close carousel slide div
	echo '</div>'; // Close column div
}

function makeNowPlaying()
{
	$network = getNetwork("plex");
	$plexSessionXML = simplexml_load_file($network.'/status/sessions');

	if (!$plexSessionXML):
		makeRecenlyViewed();
	elseif (count($plexSessionXML->Video) == 0):
		makeRecenlyReleased();
	else:
		$i = 0; // Initiate and assign a value to i & t
		$t = 0; // T is the total amount of sessions
		echo '<div class="col-md-10 col-sm-offset-1">';
		//echo '<div class="col-md-12">';
		foreach ($plexSessionXML->Video as $sessionInfo):
			$t++;
		endforeach;
		foreach ($plexSessionXML->Video as $sessionInfo):
			$mediaKey = $sessionInfo['key'];
			$playerTitle = $sessionInfo->Player['title'];
			$mediaXML = simplexml_load_file($network.$mediaKey);
			$type = $mediaXML->Video['type'];
			echo '<div class="thumbnail">';
			$i++; // Increment i every pass through the array
			if ($type == "movie"):
				// Build information for a movie
				$movieArt = $mediaXML->Video['thumb'];
				$movieTitle = $mediaXML->Video['title'];
				$duration = $plexSessionXML->Video[$i-1]['duration'];
				$viewOffset = $plexSessionXML->Video[$i-1]['viewOffset'];
				$progress = sprintf('%.0f',($viewOffset / $duration) * 100);
				$user = $plexSessionXML->Video[$i-1]->User['title'];
				$device = $plexSessionXML->Video[$i-1]->Player['title'];
				$state = $plexSessionXML->Video[$i-1]->Player['state'];
				// Truncate movie summary if it's more than 50 words
				if (countWords($mediaXML->Video['summary']) < 51):
					$movieSummary = $mediaXML->Video['summary'];
				else:
					$movieSummary = limitWords($mediaXML->Video['summary'],50); // Limit to 50 words
					$movieSummary .= "..."; // Add ellipsis
				endif;
				echo '<img src="plex.php?img='.urlencode($network.$movieArt).'" alt="'.$movieTitle.'">';
				// Make now playing progress bar
				//echo 'div id="now-playing-progress-bar">';
				echo '<div class="progress now-playing-progress-bar">';
				echo '<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="'.$progress.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$progress.'%">';
				echo '</div>';
				echo '</div>';
				echo '<div class="caption">';
				//echo '<h2 class="exoextralight">'.$movieTitle.'</h2>';
				echo '<p class="exolight" style="margin-top:5px;">'.$movieSummary.'</p>';
				if ($state == "playing"):
					// Show the playing icon
					echo '<span class="glyphicon glyphicon-play"></span>';
				else:
					echo '<span class="glyphicon glyphicon-pause"></span>';
				endif;
				if ($user == ""):
					echo '<p class="exolight">'.$device.'</p>';
				else:
					echo '<p class="exolight">'.$user.'</p>';
				endif;
			else:
				// Build information for a tv show
				$tvArt = $mediaXML->Video['grandparentThumb'];
				$showTitle = $mediaXML->Video['grandparentTitle'];
				$episodeTitle = $mediaXML->Video['title'];
				$episodeSummary = $mediaXML->Video['summary'];
				$episodeSeason = $mediaXML->Video['parentIndex'];
				$episodeNumber = $mediaXML->Video['index'];
				$duration = $plexSessionXML->Video[$i-1]['duration'];
				$viewOffset = $plexSessionXML->Video[$i-1]['viewOffset'];
				$progress = sprintf('%.0f',($viewOffset / $duration) * 100);
				$user = $plexSessionXML->Video[$i-1]->User['title'];
				$device = $plexSessionXML->Video[$i-1]->Player['title'];
				$state = $plexSessionXML->Video[$i-1]->Player['state'];
				//echo '<div class="img-overlay">';
				echo '<img src="plex.php?img='.urlencode($network.$tvArt).'" alt="'.$showTitle.'">';
				// Make now playing progress bar
				//echo 'div id="now-playing-progress-bar">';
				echo '<div class="progress now-playing-progress-bar">';
				echo '<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="'.$progress.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$progress.'%">';
				echo '</div>';
				echo '</div>';
				//echo '</div>';
				// Make description below thumbnail
				echo '<div class="caption">';
				//echo '<h2 class="exoextralight">'.$showTitle.'</h2>';
				echo '<h3 class="exoextralight" style="margin-top:5px;">Season '.$episodeSeason.'</h3>';
				echo '<h4 class="exoextralight" style="margin-top:5px;">E'.$episodeNumber.' - '.$episodeTitle.'</h4>';
				// Truncate episode summary if it's more than 50 words
				if (countWords($mediaXML->Video['summary']) < 51):
					$episodeSummary = $mediaXML->Video['summary'];
				else:
					$episodeSummary = limitWords($mediaXML->Video['summary'],50); // Limit to 50 words
					$episodeSummary .= "..."; // Add ellipsis
				endif;
				echo '<p class="exolight">'.$episodeSummary.'</p>';
				if ($state == "playing"):
					// Show the playing icon
					echo '<span class="glyphicon glyphicon-play"></span>';
				else:
					echo '<span class="glyphicon glyphicon-pause"></span>';
				endif;
				if ($user == ""):
					echo '<p class="exolight">'.$device.'</p>';
				else:
					echo '<p class="exolight">'.$user.'</p>';
				endif;
			endif;
			// Action buttons if we ever want to do something with them.
			//echo '<p><a href="#" class="btn btn-primary">Action</a> <a href="#" class="btn btn-default">Action</a></p>';
			echo "</div>";
			echo "</div>";
			// Should we make <hr>? Only if there is more than one video and it's not the last thumbnail created.
			if (($i > 0) && ($i < $t)):
				echo '<hr>';
			else:
				// Do nothing
			endif;
		endforeach;
		echo '</div>';
	endif;
}

function getTranscodeSessions()
{
	$network = getNetwork("plex");
	$plexSessionXML = simplexml_load_file($network.'/status/sessions');

	if (count($plexSessionXML->Video) > 0):
		$i = 0; // i is the variable that gets iterated each pass through the array
		$t = 0; // t is the total amount of sessions
		$transcodeSessions = 0; // this is the number of active transcodes
		foreach ($plexSessionXML->Video as $sessionInfo):
			$t++;
		endforeach;
		foreach ($plexSessionXML->Video as $sessionInfo):
			if ($sessionInfo->TranscodeSession['videoDecision'] == 'transcode') {
				$transcodeSessions++;
			};
			$i++; // Increment i every pass through the array
		endforeach;
		return $transcodeSessions;
	endif;
}

function makeBandwidthBars($interface)
{
	$array = getBandwidth($interface);
	$dPercent = sprintf('%.0f',($array[0] / 55) * 100);
	$uPercent = sprintf('%.0f',($array[1] / 5) * 100);
	printBandwidthBar($dPercent, 'Download', $array[0]);
	printBandwidthBar($uPercent, 'Upload', $array[1]);
}

function getBandwidth($interface)
{
	// For this to work with pfSense you have to have vnstat package installed and
	// you need to change the -i rl0 to the name of your interface for WAN e.g. -i <interface>
	// You will also probably need to do a var_dump of $output below and figure out exactly which array 
	// values you need as they might be off by one or two each.

	$dump = shell_exec('vnstat -i '.$interface.' -tr');
	$output = preg_split('/[,;| \s]/', $dump);
	for ($i=count($output)-1; $i>=0; $i--) {
		if ($output[$i] == '') unset ($output[$i]);
	}
	$output = array_values($output);
	$rxRate = $output[50];
	$rxFormat = $output[51];
	$txRate = $output[55];
	$txFormat = $output[56];
	if ($rxFormat == 'kbit/s') {
		$rxRateMB = $rxRate / 1024;
	} else {
		$rxRateMB = $rxRate;
	}
	if ($txFormat == 'kbit/s') {
		$txRateMB = $txRate / 1024;
	} else {
		$txRateMB = $txRate;
	}

	return array(floatval($rxRateMB), floatval($txRateMB));
}

function getPing($sourceIP,$destinationIP)
{
	// $sourceIP is the IP address of the WAN that you want to use to ping with. This allows you to ping the same address from multiple WANs if you need to.

	$terminal_output = shell_exec('ping -c 5 -q -S '.$sourceIP.' '.$destinationIP);
	// If using something besides OS X you might want to customize the following variables for proper output of average ping.
	$findme_start = '= ';
	$start = strpos($terminal_output, $findme_start);
	$ping_return_value_str = substr($terminal_output, ($start +2), 100);
	$findme_stop1 = '.';
	$stop = strpos($ping_return_value_str, $findme_stop1);
	$findme_avgPing_decimal = '.';
	$avgPing_decimal = strpos($ping_return_value_str, $findme_avgPing_decimal, 6);
	$findme_forward_slash = '/';
	$avgPing_forward_slash = strpos($ping_return_value_str, $findme_forward_slash);
	$avgPing = substr($ping_return_value_str, ($stop + 5), ($avgPing_decimal - $avgPing_forward_slash - 1));
	return $avgPing;
}

function printBandwidthBar($percent, $name = "", $Mbps)
{
	if ($name != "") echo '<!-- ' . $name . ' -->';
	echo '<div class="exolight">';
		if ($name != "")
			echo $name . ": ";
			echo number_format($Mbps, 2) . " Mbps";
		echo '<div class="progress">';
			echo '<div class="progress-bar" style="width: ' . $percent . '%"></div>';
		echo '</div>';
	echo '</div>';
}

function getPlexToken()
{
	global $plex_username;
	global $plex_password;
	$myPlex = shell_exec('curl -H "Content-Length: 0" -H "X-Plex-Client-Identifier: my-app" -u "'.$plex_username.'"":""'.$plex_password.'" -X POST https://my.plexapp.com/users/sign_in.xml 2> /dev/null');
	$myPlex_xml = simplexml_load_string($myPlex);
	$token = $myPlex_xml['authenticationToken'];
	return $token;
}

function countWords($string)
{
	$words = explode(" ",$string);
    return count($words);
}

function limitWords($string, $word_limit)
{
	$words = explode(" ",$string);
    return implode(" ",array_splice($words,0,$word_limit));
}

function getDir($b)
{
   $dirs = array('N', 'NE', 'E', 'SE', 'S', 'SW', 'W', 'NW', 'N');
   return $dirs[round($b/45)];
}

function makeWeatherSidebar()
{
	global $forecast_api;
	global $weather_lat;
	global $weather_long;
	$forecastExcludes = '?exclude=flags'; // Take a look at https://developer.forecast.io/docs/v2 to configure your weather information.
	$currentForecast = json_decode(file_get_contents('https://api.forecast.io/forecast/'.$forecast_api.'/'.$weather_lat.','.$weather_long.$forecastExcludes));

	$currentSummary = $currentForecast->currently->summary;
	$currentSummaryIcon = $currentForecast->currently->icon;
	$currentTemp = round($currentForecast->currently->temperature);
	$currentWindSpeed = round($currentForecast->currently->windSpeed);
	if ($currentWindSpeed > 0) {
		$currentWindBearing = $currentForecast->currently->windBearing;
	}
	$minutelySummary = $currentForecast->minutely->summary;
	$hourlySummary = $currentForecast->hourly->summary;

	$sunriseTime = $currentForecast->daily->data[0]->sunriseTime;
	$sunsetTime = $currentForecast->daily->data[0]->sunsetTime;

	if ($sunriseTime > time()) {
		$rises = 'Rises';
	} else {
		$rises = 'Rose';
	}

	if ($sunsetTime > time()) {
		$sets = 'Sets';
	} else {
		$sets = 'Set';
	}

	// If there are alerts, make the alerts variables
	if (isset($currentForecast->alerts)) {
		$alertTitle = $currentForecast->alerts[0]->title;
		$alertExpires = $currentForecast->alerts[0]->expires;
		$alertDescription = $currentForecast->alerts[0]->description;
		$alertUri = $currentForecast->alerts[0]->uri;
	}
	// Make the array for weather icons
	$weatherIcons = [
		'clear-day' => 'B',
		'clear-night' => 'C',
		'rain' => 'R',
		'snow' => 'W',
		'sleet' => 'X',
		'wind' => 'F',
		'fog' => 'L',
		'cloudy' => 'N',
		'partly-cloudy-day' => 'H',
		'partly-cloudy-night' => 'I',
	];
	$weatherIcon = $weatherIcons[$currentSummaryIcon];
	// If there is a severe weather warning, display it
	//if (isset($currentForecast->alerts)) {
	//	echo '<div class="alert alert-warning alert-dismissable">';
	//	echo '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
	//	echo '<strong><a href="'.$alertUri.'" class="alert-link">'.$alertTitle.'</a></strong>';
	//	echo '</div>';
	//}
	echo '<ul class="list-inline" style="margin-bottom:-20px">';
	echo '<li><h1 data-icon="'.$weatherIcon.'" style="font-size:500%;margin:0px -10px 20px -5px"></h1></li>';
	echo '<li><ul class="list-unstyled">';
	echo '<li><h1 class="exoregular" style="margin:0px">'.$currentTemp.'°</h1></li>';
	echo '<li><h4 class="exoregular" style="margin:0px;padding-right:10px;width:80px">'.$currentSummary.'</h4></li>';
	echo '</ul></li>';
	echo '</ul>';
	if ($currentWindSpeed > 0) {
		$direction = getDir($currentWindBearing);
		echo '<h4 class="exoextralight" style="margin-top:0px">Wind: '.$currentWindSpeed.' mph from the '.$direction.'</h4>';
	} else {
		echo '<h4 class="exoextralight" style="margin-top:0px">Wind: Calm</h4>';
	}
	echo '<h4 class="exoregular">Next Hour</h4>';
	echo '<h5 class="exoextralight" style="margin-top:10px">'.$minutelySummary.'</h5>';
	echo '<h4 class="exoregular">Next 24 Hours</h4>';
	echo '<h5 class="exoextralight" style="margin-top:10px">'.$hourlySummary.'</h5>';
	echo '<h4 class="exoregular">The Sun</h4>';
	echo '<h5 class="exoextralight" style="margin-top:10px">'.$rises.' at '.date('g:i A', $sunriseTime).'</h5>';
	echo '<h5 class="exoextralight" style="margin-top:10px">'.$sets.' at '.date('g:i A', $sunsetTime).'</h5>';
	echo '<p class="text-right no-link-color" style="margin-bottom:-10px"><small><a href="http://forecast.io/#/f/'.$weather_lat.','.$weather_long.'">Forecast.io</a></small></p> ';
}

?>