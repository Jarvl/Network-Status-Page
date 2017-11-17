<!DOCTYPE html>
<?php
Ini_Set( 'display_errors', true );
include '../../init.php';
include("service.class.php");

$services = array();

foreach ($GLOBALS['config']['services'] as $service) {
	// Build URL
	$url = composeUrl($GLOBALS["config"]["networkDetails"]["wanDomain"], $service['subdomain'], $service['urlDirectory'], $service["https"]);
	array_push($services, new Service($service["name"], $service["port"], $url));
}
?>
<html lang="en">
	<script>
	// Enable bootstrap tooltips
	$(function ()
	        { $("[rel=tooltip]").tooltip();
	        });
	</script>

	<table class="center">
		<?php foreach($services as $service){ ?>
			<tr>
				<td style="text-align: right; padding-right:5px;" class="exoextralight"><?php echo $service->name; ?></td>
				<td style="text-align: left;"><?php echo $service->makeButton(); ?></td>
			</tr>
		<?php }?>
	</table>
