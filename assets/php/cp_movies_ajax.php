<?php
Ini_Set( 'display_errors', true );
include '../../init.php';
include ROOT_DIR . '/assets/php/functions.php';

makeCpMovies();
?>

<script>
	// Enable bootstrap tooltips
	$(function ()
		{ $("[rel=tooltip]").tooltip();
	});

	$('#cp-movies-carousel').carousel({
  		interval: 5000
	})
</script>
