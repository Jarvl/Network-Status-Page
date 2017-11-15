<!DOCTYPE html>
<?php
	Ini_Set( 'display_errors', false);
	include '../../init.php';
?>
<html lang="en">
	<script>
	// Enable bootstrap tooltips
	$(function ()
	        { $("[rel=tooltip]").tooltip();
	        });
	</script>
	<!-- Disk Space -->
<?php makeTotalDiskSpace(); ?>
<!-- <hr> -->
<?php makeDiskBars(); ?>