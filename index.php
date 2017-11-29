<!DOCTYPE html>
<?php
	Error_Reporting( E_ALL | E_STRICT );
	Ini_Set( 'display_errors', true);
	include 'init.php';
?>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?=$GLOBALS["config"]["misc"]["siteTitle"]?></title>
		<meta name="author" content="Hill364">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<!-- Styles -->
		<link href="assets/fonts/stylesheet.css" rel="stylesheet">
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0-wip/css/bootstrap.min.css">
		<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.0/css/font-awesome.css" rel="stylesheet">
		<style type="text/css">
			::-webkit-scrollbar {
    			display: none;
			}
			body {
				text-align: center;
			}
			.center {
				margin-left:auto;
				margin-right:auto;
			}
			.no-link-color
				a {
					color:#999999;
				}
				a:hover {
					color:#999999;
				}

			.exoextralight {
				font-family:"exoextralight";
			}
			.exolight {
				font-family:"exolight";
			}
			[data-icon]:before {
				font-family: 'MeteoconsRegular';
				content: attr(data-icon);
			}
			.exoregular {
				font-family:"exoregular";
			}
			/*Changes carousel slide transition to fade transition*/
			.carousel {
				overflow: hidden;
			}
			.carousel .item {
				-webkit-transition: opacity 1s;
				-moz-transition: opacity 1s;
				-ms-transition: opacity 1s;
				-o-transition: opacity 1s;
				transition: opacity 1s;
			}
			.carousel .active.left, .carousel .active.right {
				left:0;
				opacity:0;
				z-index:2;
			}
			.carousel .next, .carousel .prev {
				left:0;
				opacity:1;
				z-index:1;
			}
			 /*End of carousel slide transition hack*/
			/* Disables shadowing on right and left sides of carousel images for a crisp look */
			.carousel-control.left {
				background-image: none;
			}
			.carousel-control.right {
				background-image: none;
			}
			/* End of carousel shadow modification */

			/* Now Playing Progress Bar CSS */
			.now-playing-progress-bar {
			    height: 5px;
			    margin-top: 0px;
			    margin-bottom: 0px;
			    -webkit-border-radius: 0px;
			       -moz-border-radius: 0px;
			            border-radius: 0px;
			}
			/* End of Now Playing Progress Bar CSS */

			.panel-white-bg {
				background-color: #ffffff;
			}

			/* Disables ping ID looking like a link and makes it look white */
			a#ping.badge.pull-right {
				color:#FFF;
			}
			/* End of Ping ID hack */
		</style>
		<link rel="shortcut icon" href="assets/ico/favicon.ico">
	</head>
	<body>
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="row">
						<!-- Left sidebar -->
						<div class="col-md-3" style="padding-top: 20px;">
							<!-- Online profiles -->
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title exoextralight">
										Weather
									</h4>
								</div>
								<div class="panel-body">
								<!--	<img src="http://www.gravatar.com/avatar/ae73e9ef5eb134c74df655c0bbb1012a.png" class="img-rounded">
									<hr>	-->
									<div id="left_column_top"></div>
								</div>
							</div>
							<!-- Bandwidth -->
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title exoextralight">
										Bandwidth
									</h4>
								</div>
								<div class="panel-body" >
									<div id="bandwidth"></div>
								</div>
							</div>
							<!-- Services -->
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title exoextralight">
										Services
									</h4>
								</div>
								<div id="services" class="panel-body">
								</div>
							</div>
						</div>
						<!-- Center Area -->
						<div class="col-md-6">
							<div id="plexCurrentlyPlayingTitle"></div>
							<div id="plexCurrentlyPlaying"></div>
							<!--<div id="cp_movies_title"></div>
							<div id="cp_movies"></div>-->
							<hr class="visible-xs visible-sm">
						</div>
						<!-- Right sidebar -->
						<div class="col-md-3">
							<!-- Load Panel -->
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title exoextralight">System Load</h4>
								</div>
								<div class="panel-body">
									<div id="system_load" style="margin-bottom:-10px"></div>
								</div>
							</div>
							<!-- Memory Panel -->
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title exoextralight">Memory</h4>
								</div>
								<div class="panel-body">
									<div id="system_ram" style="height:40px"></div>
								</div>
							</div>
							<!-- Storage Panel -->
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title exoextralight">
										Storage
									</h4>
								</div>
								<div class="panel-body">
									<div id="disk_space" style="margin-bottom:-10px"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Contact modal --><!--
		<div id="contactModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						<h3 id="myModalLabel">Contact info</h3>
					</div>
					<div class="modal-body">
						<p>You can contact me at the following address:</p>
						<img src="assets/img/contact.png" style="width: 150px;"/>
					</div>
					<div class="modal-footer">
						<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
					</div>
				</div>
			</div>
		</div>-->
		<!-- Invisible php div-->
		<!--<div id="plex_check"></div>-->

		<!-- javascript
	    ================================================== -->
	    <!-- Placed at the end of the document so the pages load faster -->
	    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="//code.jquery.com/jquery.js"></script>
		<!-- Latest compiled and minified JavaScript -->
		<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0-wip/js/bootstrap.min.js"></script>
		<script>
		// Enable bootstrap tooltips
		$(function () {
			$("[rel=tooltip]").tooltip();
			$("[rel=popover]").popover();
			});
		// Auto refresh things
		(function($) {
			$(document).ready(function() {
				$.ajaxSetup({
		            		cache: false,
		            		beforeSend: function() {
		            			$('#left_column_top').show();
		            			$('#bandwidth').show();
		            			$('#ping').show();
		            			$('#services').show();
								$('#system_load').show();
								$('#system_ram').show();
								$('#disk_space').show();
								//$('#cp_movies_title').show();
								//$('#cp_movies').show();
		            		},
				            complete: function() {
				            	$('#left_column_top').show();
				            	$('#bandwidth').show();
				            	$('#ping').show();
				            	$('#services').show();
								$('#system_load').show();
								$('#system_ram').show();
								$('#disk_space').show();
								//$('#cp_movies_title').show();
								//$('#cp_movies').show();
				            },
				            success: function() {
				            	$('#left_column_top').show();
				            	$('#bandwidth').show();
				            	$('#ping').show();
				            	$('#services').show();
								$('#system_load').show();
								$('#system_ram').show();
								$('#disk_space').show();
								//$('#cp_movies_title').show();
								//$('#cp_movies').show();
				            }
				});

				// Assign varibles to DOM sections
				var $left_column_top_refresh = $('#left_column_top');
				var $bandwidth_refresh = $('#bandwidth');
				var $ping_refresh = $('#ping');
				var $services_refresh = $('#services');
	        	var $system_load_refresh = $('#system_load');
	        	var $system_ram_refresh = $('#system_ram');
	        	var $disk_space_refresh = $('#disk_space');
	        	//var $cp_movies_title_refresh = $('#cp_movies_title');
	        	//var $cp_movies_refresh = $('#cp_movies');
				var $plex_currently_playing = $("#plexCurrentlyPlaying");
				var $plex_currently_playing_title = $("#plexCurrentlyPlayingTitle");

	        	// Load external php files & assign variables
	        	//$cp_movies_title_refresh.load("assets/php/cp_movies_title_ajax.php");
	        	//$cp_movies_refresh.load("assets/php/cp_movies_ajax.php");
	        	$left_column_top_refresh.load('assets/php/left_column_top_ajax.php');
	        	$bandwidth_refresh.load("assets/php/bandwidth_ajax.php");
	        	$ping_refresh.load("assets/php/ping_ajax.php");
	        	$services_refresh.load("assets/php/services_ajax.php");
	        	$system_load_refresh.load("assets/php/system_load_ajax.php");
	        	$system_ram_refresh.load("assets/php/system_ram_ajax.php");
	        	$disk_space_refresh.load("assets/php/disk_space_ajax.php");
				$plex_currently_playing.load("assets/php/now_playing_ajax.php");
				$plex_currently_playing_title.load("assets/php/now_playing_title_ajax.php");
/*
				var refreshIdfastest = setInterval(function(){
	        		$plex_check_refresh.load('assets/php/plex_check_ajax.php');
	        	}, 10000); // at 3 & 5 seconds python was crashing.
*/
	        	var refreshIdfastest = setInterval(function(){
	            	$system_load_refresh.load('assets/php/system_load_ajax.php');
	        	}, 5000); // 5 seconds

	        	var refreshIdfastest = setInterval(function(){
	            	$system_ram_refresh.load('assets/php/system_ram_ajax.php');
	        	}, 5000); // 5 seconds

	        	var refreshId30 = setInterval(function(){
	        		$bandwidth_refresh.load("assets/php/bandwidth_ajax.php");
	        		$ping_refresh.load("assets/php/ping_ajax.php");
	        		$services_refresh.load("assets/php/services_ajax.php");
	        	}, 10000); // 10 seconds
/*
	        	var refreshId60 = setInterval(function(){
	        		$transcodeSessions.load("assets/php/transcode_sessions_ajax.php");
	        	}, 60000); // 60 seconds
*/
	        	var refreshIdslow = setInterval(function(){
	            	$disk_space_refresh.load('assets/php/disk_space_ajax.php');
	        	}, 120000); // 2 minutes

	        	var refreshtopleft = setInterval(function(){
	            	$left_column_top_refresh.load('assets/php/left_column_top_ajax.php');
	        	}, 300000); // 5 minutes

	        	// Load these sections only if Plex has changed states
	        	//var theResource = "assets/caches/plexcheckfile2.txt";
/*
 				var refreshconditional = setInterval(function(){
				          if(localStorage["resourcemodified"]) {
				               $.ajax({
				                    url:theResource,
				                    type:"head",
				                    success:function(res,code,xhr) {
				                         console.log("Checking to see if plexcheckfile2 changed."+ localStorage["resourcemodified"] + " to "+ xhr.getResponseHeader("Last-Modified"))
				                         if(localStorage["resourcemodified"] != xhr.getResponseHeader("Last-Modified")) getResource();
				                    }
				               })

				          } else getResource();

				          function getResource() {
				               $.ajax({
				                    url:theResource,
				                    type:"get",
				                    cache:false,
				                    success:function(res,code,xhr) {
				                        localStorage["resourcemodified"] = xhr.getResponseHeader("Last-Modified");
				                        console.log("Updating our cache and refreshing appropriate divs.");
				                        $left_column_top_refresh.load('assets/php/left_column_top_ajax.php');
				                        $cp_movies_title_refresh.load("assets/php/cp_movies_title_ajax.php");
			        			   		$cp_movies_refresh.load("assets/php/cp_movies_ajax.php");
				                    }
				               })
				          }
				}, 5000); // 5 seconds
*/
				// Change the size of the now playing div to match the client size
				/*function doResizeCpMovies() {
					var height = 0;
					var body = window.document.body;
					if (window.innerHeight) {
						height = window.innerHeight;
					} else if (body.parentElement.clientHeight) {
						height = body.parentElement.clientHeight;
					} else if (body && body.clientHeight) {
						height = body.clientHeight;
					}
					cp_movies.style.height = ((height - cp_movies.offsetTop) + "px");
					console.log("Div resize complete. New size is: " + height);
				};
				// Detect if we are on a mobile device, if we aren't resize the cp movies div using doResizeCpMovies()
				if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
					// some code..
				} else {
					var resizeTimer;
					$(window).resize(function() {
						clearTimeout(resizeTimer);
						resizeTimer = setTimeout(doResizeCpMovies, 100);
					});
					// Resize the now playing div 5 seconds after page load
					$(function(){
	   					clearTimeout(resizeTimer);
						resizeTimer = setTimeout(doResizeCpMovies, 5000);
					});
				}*/
		    	});
		})(jQuery);
		</script>
	</body>
</html>
