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
echo 'Server Ping: '.getping($GLOBALS["config"]["wan1_ip"],$GLOBALS["config"]["ping_ip"]).' ms';
echo '<br>';
echo '';
echo '<br>';
makeBandwidthBars('eth0');
/*
echo '<br>';
echo 'WAN2 Ping: '.getping($wan2_ip,$GLOBALS["config"]["ping_ip"]).' ms';
echo '<br>';
echo '';
echo '<br>';
makeBandwidthBars('rl4');
*/
echo '</div>';

?>