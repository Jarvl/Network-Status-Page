<?php
	Ini_Set( 'display_errors', true );
	include '../../init.php';
	
	$plex_url = composeUrl($GLOBALS["config"]["networkDetails"]["wanDomain"], $GLOBALS["config"]["services"]["plex"]["subomain"]);
	$plexSessionXML = simplexml_load_file($plex_url.'status/sessions?X-Plex-Token=' . $GLOBALS["config"]["apiKeys"]["plexAuthToken"]);

	$plexSessionID = $_GET['id'];

	$duration = $plexSessionXML->Video[$plexSessionID]['duration'];
	$viewOffset = $plexSessionXML->Video[$plexSessionID]['viewOffset'];
	$progress = sprintf('%.0f',($viewOffset / $duration) * 100);
	
	return $progress;
?>