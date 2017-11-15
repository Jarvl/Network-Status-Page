<!DOCTYPE html>
<?php
	Ini_Set( 'display_errors', true );
	include '../../init.php';
	include("service.class.php");
?>
<html lang="en">
	<script>
	// Enable bootstrap tooltips
	$(function ()
	        { $("[rel=tooltip]").tooltip();
	        });
	</script>
<?php
$services = array(
	new service("Plex", 32400, "http://plex." . $GLOBALS["config"]["networkDetails"]["wanDomain"] . "/web/index.html#!/dashboard"),
	new service("CouchPotato", 5050, "http://couchpotato." . $GLOBALS["config"]["networkDetails"]["wanDomain"]),
	new service("Transmission", 9091, "http://transmission." . $GLOBALS["config"]["networkDetails"]["wanDomain"]),
	new service("Sick Beard", 8081, "http://sickbeard." . $GLOBALS["config"]["networkDetails"]["wanDomain"]),
);
?>
<table class="center">
	<?php foreach($services as $service){ ?>
		<tr>
			<td style="text-align: right; padding-right:5px;" class="exoextralight"><?php echo $service->name; ?></td>
			<td style="text-align: left;"><?php echo $service->makeButton(); ?></td>
		</tr>
	<?php }?>
</table>
