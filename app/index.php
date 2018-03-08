<?php
/*
    All of the front page code
    Copyright (C) 2015 IpfsPics Team

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as
    published by the Free Software Foundation, either version 3 of the
    License, or (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU Affero General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
require __DIR__ . '/../vendor/autoload.php';

use Cloutier\PhpIpfsApi\IPFS;

$ipfs = new IPFS("ipfs");

?>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,initial-scale=1" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="p:domain_verify" content="e346f65dd772d167a00b2449567d8aa3"/>
		<link rel="icon" href="/static/favicon.ico" type="image/x-icon">
		<link rel="apple-touch-icon-precomposed" href="/ipfs/QmYMQUcyAA8PhLTX5WtyfRU1NZogJiAjkxc5MSDmh76K6A">

		<!-- Custom styles for this template -->
		<link href="cover.css" rel="stylesheet">

		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->

		<title>Decentralized picture hosting in ipfs</title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
		<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="static/cover.css">
		<style>
		.row {
		  margin-bottom: 100px;
		}
		.mastfoot {
		  z-index: 10 !important;
		}
		</style>
		<script src="/static/common.js"></script>
		<meta name="description" content="Decentralized image hosting website. Never lose your images by uploading them on ipfs.">
	</head>

	<body>

		<div class="site-wrapper">

			<div class="site-wrapper-inner">

				<div class="cover-container">

					<div class="masthead clearfix">
						<div class="inner">
							<div id="mastheadBackground" style="display: none;" ></div>
							<h3 class="masthead-brand"><img src="/ipfs/QmNvuHJbTHafrABhitFcQ5srv7FeCfHr6jFiyoHhuRh8wK"></img></h3>
							<nav>
								<ul class="nav masthead-nav">
									<li class="active"><a href="/">Upload</a></li>
									<li><a href="/random">Random</a></li>
									<li><a href="/trending">Trending</a></li>
									<li><a href="/best">Best</a></li>
								</ul>
							</nav>
						</div>
					</div>

					<div class="inner cover">
						<form id="dropoff" action="/upload.php" enctype="multipart/form-data" method="POST">
							<input type="file" id="dropoff_image" name="img" accept="image/*">
								<br>
								<span>Click here to upload a picture to the permanent web</span>
								<br>
							</input>
						</form>
						<a href="#" id="pasteURLbutton">or click here to paste an URL</a>
                                                <div class="max-filesize">
                                                    <center>Maximum size: <?php echo ini_get("upload_max_filesize"); ?></center>
                                                </div>
					</div>


					<div id="index-characteristics" class="row">
						<div class="col-md-4">
							<i class="fa fa-cloud fa-5x"></i>
							<br><br>
							<h4>Replicated on the IPFS cloud</h4>
							<a href="https://ipfs.io" target="_BLANK">Learn more about IPFS</a>
						</div>
						<div class="col-md-4">
							<i class="fa fa-heart-o fa-5x"></i>
							<br><br>
							<h4>Free and open source</h4>
							<a href="https://github.com/ipfspics/server" target="_BLANK">Check out the code</a>
						</div>
						<div class="col-md-4">
							<i class="fa fa-cubes fa-5x"></i>
							<br><br>
							<h4>Decentralized</h4>
							Use any server to access your content
						</div>
					</div>
					<br>
					<h1> ipfs.pics around the web </h1>
					<br>
					<div class="row">
						<div class="col-md-6">
							<a href="https://www.reddit.com/domain/ipfs.pics" target="_BLANK"><img src="/ipfs/QmdixSJTJWUFuxctcRtHfrxqUqVKcrpyrWjFoojFSZkTZL" width=300/></a>
							<br>
						</div>
						<div class="col-md-6">
							<a href="https://www.voat.co/domains/ipfs.pics" target="_BLANK"><img src="/ipfs/Qmduj4VjKMMWRLwxcrHugxCTWD1cZ66iiKTeW2JGRZtY9u" height=125 /></a>
						</div>
					</div>

					<div id="index-characteristics" class="row">
					</div>

					<br><br>
					<?php 
						$ipfsVersion = $ipfs->version();

						if ($ipfsVersion == "") {
							echo "It seems no ipfs daemon is running on this server";
						} else {
							echo "Running ipfs version ";
							echo  $ipfsVersion;
						}
					?>
					<br><br>
				</div>
			</div>
		</div>
		<script>

			if (uploads[0] != "") {
				$("#viewNav").attr("href", "/" + uploads[uploads.length - 1] + "#view");
			}
			$("#dropoff_image").change(function () {
				$("#dropoff").submit();
				$(".cover").html("<img src='/ipfs/QmPYVGMVjPSkz6bQaAFChBtigMb4WPGC922tLsZcAe3wvN'/>");
			});

			$("#about").hide();
			$("#aboutLink").click(function (event) {
				$("#about").show("drop", {direction: "down"}, 500);
				//event.preventDefault();
			});
			$("#closeAbout").click(function () {
				$("#about").hide("drop", {direction: "down"}, 500);
			});
			$("#pasteURLbutton").click(function () {

			});
			$(document).on("click", function(event) {
				if(!$(event.target).closest("#aboutLink").length && !$(event.target).closest("#about").length) {
					$("#about").hide("drop", {direction: "down"}, 500);
				}
			});

			
		    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
		   ga('create', 'UA-65093513-1', 'auto');
		   ga('send', 'pageview');
		</script>


	</body>
</html>
