<?php
include "../pswd.php";
//ini_set('display_errors', 1);
if( !isset($_GET['hash']) ) {
	$hash = "QmYqA8GiZ4MCeyJkERReLwGRnjSdQBx5SzjvMgiNwQZfx6";
} else {
	if (preg_match('/^[a-z0-9]+$/i', $_GET['hash']) ) {
		$hash = $_GET['hash'];
	} else {
		exit("wrong hash");
	}
}


define("defaultTitle", "A picture hosted on the permanent web");
define("defaultThumbnail", "http://ipfs.pics/ipfs/$hash");
define("defaultDescription", "Click to see it");

$db = new PDO('mysql:host=localhost;dbname=hashes;charset=utf8', $db_user, $db_pswd);

$info = $db->query("SElECT * FROM hash_info WHERE hash='$hash'")->fetch();

if ( $info['hash'] ) {
	$isDir = ($info['type'] == "dir");
	$isBanned = $info['banned'];
	$dirContent = `curl localhost:8090/$hash?dirContent`;
	$isBackendWorking = True;
	$isSafe = $info['sfw'];
} else {
	$isBanned = false;
	$isSafe = false;
	$dirContent = `curl localhost:8090/$hash?dirContent`;
	$isBackendWorking = $dirContent != "";
	$isDir = ($dirContent != "empty" and count(split(" ", explode(PHP_EOL, $dirContent)[0])) > 3);
	if ($isDir) {
		$type = "dir";
	} else {
		$type = "file";
	}
	if($dirContent != "") {
		$add = $db->prepare("INSERT INTO hash_info (hash, type, first_seen) VALUES (:hash, :type, UNIX_TIMESTAMP())");
		$add->bindParam(":hash", $hash);
		$add->bindParam(":type", $type);
		$add->execute();
		$x = `curl localhost:8090/$originalFile?add`;
	}
}


$title = constant("defaultTitle");
$thumbnail = constant("defaultThumbnail");
$description = constant("defaultDescription");

if ($isDir and !$isBanned and $isBackendWorking) {
	$size = `curl localhost:8090/$hash?size`;
	$lines = explode(PHP_EOL, $dirContent);	
	array_pop($lines);
	list ($firstElementHash, $firstElementSize, $firstElementName) = split(" ", $lines[0]);
	if ( $firstElementSize < 400 ) {
		$title = `curl localhost:8080/ipfs/$firstElementHash`;
		foreach ( $lines as $e ) {
			list ($eHash, $eSize, $eName) = split(" ", $e);
			if ($eSize > 400) {
				$thumbnail = "http://ipfs.pics/ipfs/$eHash";
				break;
			}	
		}	
		foreach ( $lines as $e ) {
			list ($eHash, $eSize, $eName) = split(" ", $e);
			if ($eSize < 400 AND $eHash != $firstElementHash) { 
				$description = `curl localhost:8080/ipfs/$eHash`;
				break;
			}	
		}	
	} else {
		$title = 'A photo album hosted on the permanent web';
		$thumbnail = "http://ipfs.pics/ipfs/$firstElementHash";
	}
}

if (!$isDir and !$isBanned and $isBackendWorking) {
//	$title = "Picture";
}

if ($isBanned) {
	$title = "Not available for legal reasons";
	$thumbnail = "";
}

$title = sanitize($title);
$description = sanitize($description);
?>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,initial-scale=1" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<link rel="icon" href="//ipfs.pics/favicon.ico" type="image/x-icon">
		<meta name="twitter:card" content="summary_large_image">
		<meta name="twitter:site" content="@IpfsPics">
		<meta property="og:image" content="<?php echo $thumbnail ?>">
		<meta name="twitter:title" content="<?php echo $title ?>">
		<link rel="image_src" href="<?php echo $thumbnail ?>"/>
		<meta name="twitter:image" content="<?php echo $thumbnail ?>">
		<meta name="twitter:description" content="<?php echo $description ?>">

		<meta name="description" content="<?php echo $description ?>">
		<link rel="canonical" href="http://ipfs.pics/<?php echo $hash ?>" />

		<!-- Custom styles for this template -->
		<link href="cover.css" rel="stylesheet">


		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
                <title><?php echo $title ?></title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
                <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
		<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
                <link rel="stylesheet" href="cover.css">
		<style>
			body {
				overflow-y: scroll;
			}
			.mastfoot {
				/*visibility: hidden;*/
			}
		</style>
	</head>

	<body>

		<div class="site-wrapper">

			<div class="site-wrapper-inner">

				<div class="cover-container">

					<div id="masthead" class="masthead clearfix">
						<div id="mastheadBackground"></div>
						<div class="inner">
							<h3 class="masthead-brand"><a href="/"><img src="http://ipfs.pics/ipfs/QmNvuHJbTHafrABhitFcQ5srv7FeCfHr6jFiyoHhuRh8wK"></img></a></h3>
							<nav>
								<ul class="nav masthead-nav">
									<li><a href="/">Upload</a></li>
									<li><a href="/random">Random</a></li>
									<li class="active"><a href="#">View</a></li>
								</ul>
							</nav>
						</div>
					</div>

					<div class="inner cover">
						<div id="message-new" class="alert alert-success" role="alert">
							<b>Congratulations</b> you uploaded a picture, you can share it now
							<!-- AddToAny BEGIN -->
							<div class="a2a_kit a2a_kit_size_32 a2a_default_style">
							<a class="a2a_dd" href="https://www.addtoany.com/share_save"></a>
							<a class="a2a_button_reddit"></a>
							<a class="a2a_button_facebook"></a>
							<a class="a2a_button_email"></a>
							<a class="a2a_button_twitter"></a>
							<a class="a2a_button_pinterest"></a>
							</div>
							<script type="text/javascript" src="//static.addtoany.com/menu/page.js"></script>
							<!-- AddToAny END -->

							<br>
							You can also copy and paste the address:
							<div class="input-group">
								<span class="input-group-addon">address</span>
							      <input type="text" id="shareUrl" class="form-control shareFields" value="http://ipfs.pics/<?php echo $hash ?>" readonly>
							</div>
							<br>
							You can also use the embed code on your own page:
							<div class="input-group">
								<span class="input-group-addon">embed</span>
							      <input type="text" id="embedCode" class="form-control shareFields" value="<a target='_BLANK' href='http://ipfs.pics/<?php echo $hash ?>'><img src='http://ipfs.pics/ipfs/<?php echo $hash ?>'/></a>" readonly>
							</div>
						</div>
						<div id="message-last-upload" class="alert alert-info" role="alert">This is the last picture you uploaded</div>
						<div class="picture-wrapper">
							<?php
							 if (!$isDir and !$isBanned and $isBackendWorking) { 
								echo "<img src='http://ipfs.pics/ipfs/$hash' class='picture'></img>";
							} 

							if ($isDir and !$isBanned and $isBackendWorking) {
								echo "<!--";
								echo sanitize($dirContent);
								echo "-->";							
								$isFirstElement = true;
								foreach( $lines as $i ) {
									list( $eHash, $eSize, $eName ) = split(" ", $i);
									if ( $eSize > 400 ) {
										echo "<div class='dir-elem'>";
										echo "<a href='http://ipfs.pics/$eHash' target='_BLANK'><img src='http://ipfs.pics/ipfs/$eHash' class='picture'></img></a>";
										echo "</div>";
									}
									if ( $eSize < 400 ) {
										echo "<div class='dir-elem ".($isFirstElement ? "dir-title" : "")."'>";
										echo sanitize(`curl localhost:8080/ipfs/$eHash`);
										echo "</div>";
									}
									$isFirstElement = false;
								}
							}
							if ($isBanned and $isBackendWorking) {
								echo "This picture has been removed from our servers for legal reasons.<br \>You can still access it through your own gateway.";
								echo "<style> .underMenu { visibility: hidden !important; } </style>";
							}
							if (!$isBackendWorking) {
								//Server.js or the db is not running
								echo "Sorry there's been a error on the server";

							}
							?>
						</div>
					</div> 					
					<div id="ads">
						<?php 
						if ($isSafe) {

						?>
							<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
							<!-- preview responsive -->
							<ins class="adsbygoogle"
							     id="googleAd"
							     style="display:block"
							     data-ad-client="ca-pub-7083426718110488"
							     data-ad-slot="7940261057"
							     data-ad-format="auto"></ins>
							<script>
							if(window.matchMedia("only screen and (max-width: 760px)").matches) {
								$("#googleAd").attr("data-ad-type", "text/image");
							}
							(adsbygoogle = window.adsbygoogle || []).push({});
							</script>
						<?php } else { ?>
						<?php } ?>
					</div>
					<div class="underMenu">
						<div class="panel panel-default">
							<div class="panel-body">
								<ul class="nav nav-pills">
									<li role="presentation" class="underMenuButton "><a class="voteButton" data-vote="upvote" href="#">Upvote</a></li>
									<li role="presentation" class="underMenuButton "><a class="voteButton" data-vote="downvote" href="#">Downvote</a></li>
									<li role="presentation" class="underMenuButton "><a class="voteButton" data-vote="report" href="#">Report</a></li>
									<?php 
									if ($isDir) {
										?>
										<li id="underMenuDownload" role="presentation" class="underMenuButton disabled"><a href="#">Download</a></li>
									<?php } else { ?>
										<li id="underMenuDownload"  role="presentation" class="underMenuButton "><a href="http://ipfs.pics/ipfs/<?php echo $hash ?>?dl=1" target="_BLANK">Download</a></li>
									<?php } ?>
									<!-- AddToAny BEGIN -->
									<li>
										<div class="a2a_under a2a_kit a2a_kit_size_32 a2a_default_style">
											<a href="https://www.addtoany.com/share"></a>
											<a class="a2a_button_facebook"></a>
											<a class="a2a_button_twitter"></a>
											<a class="a2a_button_pinterest"></a>
											<a class="a2a_button_pocket"></a>
											<a class="a2a_button_reddit"></a>
											<a class="a2a_button_tumblr"></a>
										</div>
									</li>
									<!-- AddToAny END -->
								</ul>
							</div>
						</div>
					</div>
					<div class="mastfoot">
						<div id="footer" class="inner">
							This is free software, you can see the <a href="https://github.com/ipfspics/server">source code</a>
						</div>	
					</div>
				</div>

			</div>
			<div id="fullscreenOverlay"><div id="fullscreenImage"></div></div>
			<script>
			    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

			   ga('create', 'UA-65093513-1', 'auto');
			   ga('send', 'pageview');


				var isMobile = window.matchMedia("only screen and (max-width: 760px)").matches;
				$("#mastheadBackground").hide();
				if (!isMobile) {
					$(document).scroll(function(){
						if($(this).scrollTop() > 40) {   
							$('#mastheadBackground').show("slide", {direction: "up"}, 300);
						} else {
							$('#mastheadBackground').hide("slide", {direction: "up"}, 200);
						}
					});
					if (<?php echo var_export(!$isDir) ?>) {
						$(".picture").click(function () {
							$(this).toggleClass("fullscreenPicture");
						});
					}
				}

				//setCookie("uploads", "", 40);
				hash = "<?php echo $hash ?>";
				uploads = getCookie("uploads").split(",");

				function setCookie(cname, cvalue, exdays) {
				    var d = new Date();
				    d.setTime(d.getTime() + (exdays*24*60*60*1000));
				    var expires = "expires="+d.toUTCString();
				    document.cookie = cname + "=" + cvalue + "; " + expires;
				} 

				function getCookie(cname) {
				    var name = cname + "=";
				    var ca = document.cookie.split(';');
				    for(var i=0; i<ca.length; i++) {
					var c = ca[i];
					while (c.charAt(0)==' ') c = c.substring(1);
					if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
				    }
				    return "";
				}

				if (window.location.hash) {
					ancher = window.location.hash.substring(1);
					history.pushState("", document.title, window.location.pathname);
					if (ancher == "new") {
						$("#message-new").show();
						if(uploads[0] == "") {
							uploads[0] = hash;
						} else {
							uploads.push(hash);
						}
						setCookie("uploads", uploads, 30);
						$("#footer").hide();
						ga('send', 'event', 'upload', 'upload');
					}
					if (ancher == "view") {
						if (uploads[0] == "") {
							$("#message-last-upload").html("When you upload a picture, it will appear here").show();
						} else {
							$("#message-last-upload").show();
						}
					}
				}

				$(".shareFields").click(function () {
					$(this).select();
				});
				$("#underMenuDownload").click(function (e) {
				//	e.preventDefault();
				//	document.execCommand('SaveAs',true,'ipfs/' + hash);
				});
				$(".voteButton").click(function (e) {
					e.preventDefault();
					voteType = $(e.currentTarget).data("vote");
					vote(voteType, function () { 
						$(e.currentTarget).effect("highlight");
					});
				});
			function vote (type, callback) {
				$.ajax("/api/v1/"+type+"/"+hash).done(callback());
			}
			function halfHalfAds() {
				var randomNumb = Math.floor(Math.random()*11);
				var randomBool = randomNumb%3;
				return randomBool;
			}

			

			</script>

			<!-- Bootstrap core JavaScript
			================================================== -->
		
	</body>
</html>
<?php

function sanitize ($text) {
	return htmlspecialchars($text, ENT_QUOTES);
}
