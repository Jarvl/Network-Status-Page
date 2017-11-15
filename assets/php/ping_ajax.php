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
echo ping();
?>