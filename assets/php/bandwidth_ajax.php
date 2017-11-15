<!DOCTYPE html>
<?php
	Ini_Set( 'display_errors', true );
	include '../../init.php';
?>
<html lang="en">
	<script>
	// Enable bootstrap tooltips
	$(function ()
	        { $("[rel=tooltip]").tooltip();
	        });
	</script>
<?php 

echo '<div class="exolight">';
echo 'Server Ping: '.getping($GLOBALS["config"]["networkDetails"]["wanIp"],$GLOBALS["config"]["networkDetails"]["pingIp"]).' ms';
echo '<br>';
echo '';
echo '<br>';
makeBandwidthBars('eth0');
echo '</div>';

?>