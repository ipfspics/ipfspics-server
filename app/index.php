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
?>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,initial-scale=1" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="p:domain_verify" content="e346f65dd772d167a00b2449567d8aa3"/>
		<link rel="icon" href="//ipfs.pics/static/favicon.ico" type="image/x-icon">
		<link rel="apple-touch-icon-precomposed" href="https://ipfs.pics/ipfs/QmYMQUcyAA8PhLTX5WtyfRU1NZogJiAjkxc5MSDmh76K6A">

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
		<link rel="stylesheet" href="static/cover.css">
		<style>
		#drop {
		  min-height: 150px;
		  width: 250px;
		  border: 1px solid blue;
		  margin: 10px;
		  padding: 10px;
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
							<h3 class="masthead-brand"><img src="//ipfs.pics/ipfs/QmNvuHJbTHafrABhitFcQ5srv7FeCfHr6jFiyoHhuRh8wK"></img></h3>
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
					</div>

					<div id="indexfoot">
						<div id="footer" class="inner">
							<h2 id="aboutLink">	Why ipfs.pics is awesome</h2>
						</div>
					</div>

					<div id="about">
						<div id="closeAbout"><a href='#'>Close</a></div>
							<div id="textAbout">
								<strong style="font-size: 20px">Why ipfs.pics Is Awesome</strong><br></br>

								<p>Just by looking at our website, you don’t see much of a difference and that might be because the interesting part is how it works underneath. 
								To fully understand this, you have to grasp the concept of an application we used for it that is called <i>IPFS</i> - the InterPlanetary File System. 
								The whole application is based on the concept of peer to peer connections, 
								which means that instead of hosting the information in a single location, our servers, the data is stored by everyone who wants to. 
								When a picture is put on IPFS, it is given a 
								<a target="_blank"; href="https://en.wikipedia.org/wiki/Hash_function">hash</a>, a 46 characters long digital fingerprint. 
								No other file will have it and if the same file is added twice then their hashes will be exactly the same, which means the picture can still
								be found on the network simply by knowing the hash, even if our website is down.
								You can find the hash at the end of a picture URL, just like below. 
								We saw potential in that application for an image hosting website, where you can know for sure your pictures will be available forever. </p> 
								<a target="_blank"; href="http://ipfs.pics/QmbuuLzxztp35bEcMp6VXx2y7NSm1aQTPhxRyKr3zZTgCN">
									<img src="https://ipfs.pics/ipfs/QmcT99xWRNDAYunp7Zr8wGiwMKSgVfDpfbXw9hBtLCM4Mm" style="width:100%" />
								</a> 
								<p>
								We also run on an <a href="https://github.com/ipfspics/server">open-source stack</a> and we donate our unused processing power to 
								<a target="_blank"; href="http://folding.stanford.edu/">Folding at Home</a>.</p> 

								<a href="https://twitter.com/IpfsPics" class="twitter-follow-button" data-show-count="false" data-dnt="true">Follow @IpfsPics</a><br>
								<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>

								<center>We are not related to the IPFS project, for more information on them, visit their website: 
								<a target="_blank"; href="http://ipfs.io/">ipfs.io</a>.</center>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
		<script>

			if (uploads[0] != "") {
				$("#viewNav").attr("href", "/" + uploads[uploads.length - 1] + "#view");
			}
			$("#dropoff_image").change(function () {
				$("#dropoff").submit();
				$(".cover").html("<img src='//ipfs.pics/ipfs/QmPYVGMVjPSkz6bQaAFChBtigMb4WPGC922tLsZcAe3wvN'/>");
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
