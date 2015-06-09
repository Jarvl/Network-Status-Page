<?php
include './assets/php/functions.php';
$image_url = urldecode($_GET['img']);
$plexNetwork = getNetwork("plex");
$addressPosition = strpos($image_url, $plexNetwork);
if($addressPosition !== false && $addressPosition == 0) {
	$image_src = $image_url . '?X-Plex-Token=' . $plexToken;
	header('Content-type: image/jpeg');
	//header("Content-Length: " . filesize($image_src));
	readfile($image_src);
} else {
echo "Bad Plex Image Url";	
}
?>