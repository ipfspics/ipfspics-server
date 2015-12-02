<?php
include "../pswd.php";
include "class/ipfs.class.php";

if( !isset($_GET['hash']) ) {
	$hash = "QmYqA8GiZ4MCeyJkERReLwGRnjSdQBx5SzjvMgiNwQZfx6";
} else {
	if (preg_match('/^[a-z0-9]+$/i', $_GET['hash']) ) {
		$hash = $_GET['hash'];
	} else {
		exit("wrong hash");
	}
}

$db = new PDO('mysql:host=localhost;dbname=hashes;charset=utf8', $db_user, $db_pswd);
$ipfs = new IPFS("localhost", "8080", "5001"); 
$hostname = $_SERVER['HTTP_HOST'];

define("defaultTitle", "A picture hosted on the permanent web");
define("defaultThumbnail", "https://ipfs.pics/ipfs/$hash");
define("defaultDescription", "Click to see it");

$info = $db->query("SElECT * FROM hash_info WHERE hash='$hash'")->fetch();

if ( $info['hash'] ) {
	$dirContent = $ipfs->ls($hash);
	$isDir = ($dirContent[0]['Name'] != "");
	$isBanned = $info['banned'];
	$isBackendWorking = True;
	$isSafe = $info['sfw'];
} else {
	$isBanned = false;
	$isSafe = false;
	$dirContent = $ipfs->ls($hash);
	$isBackendWorking = $dirContent != "";
	$isDir = ($dirContent[0]['Name'] != "");
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
		$ipfs->pinAdd($hash);
	}
}

$title = constant("defaultTitle");
$thumbnail = constant("defaultThumbnail");
$description = constant("defaultDescription");

if ($isDir and !$isBanned and $isBackendWorking) {
	$size = $ipfs->size($hash);
	$firstElementSize = $dirContent[0]['Size'];
	$firstElementHash = $dirContent[0]['Hash'];
	if ( $firstElementSize < 400 ) {
		$title = $ipfs->cat($firstElementHash);
		foreach ( $dirContent as $e ) {
			if ($e['Size'] > 400) {
				$thumbnail = "https://ipfs.pics/ipfs/" . $e['Hash'];
				break;
			}	
		}	
		foreach ( $dirContent as $e ) {
			if ($e['Size'] < 400 AND $e['Hash'] != $firstElementHash) { 
				$description = $ipfs->cat($e['Hash']);
				break;
			}	
		}	
	} else {
		$title = 'A photo album hosted on the permanent web';
		$thumbnail = "https://ipfs.pics/ipfs/$firstElementHash";
	}
}

if ($isBanned) {
	$title = "Not available for legal reasons";
	$thumbnail = "";
}

$title = sanitize($title);
$description = sanitize($description);



$score = $db->query("SELECT ((SELECT COUNT(*) FROM votes WHERE vote_type = 'upvote' AND hash = '$hash')-(SELECT COUNT(*) FROM votes WHERE vote_type = 'downvote' AND hash = '$hash')) score;")->fetch();
$score = $score['score'];
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
		<link rel="canonical" href="https://ipfs.pics/<?php echo $hash ?>" />

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
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
                <link rel="stylesheet" href="cover.css">
		<script src="common.js"></script>
		<style>
			body {
				overflow-y: scroll;
			}
		</style>
		<script>

				hash = "<?php echo $hash ?>";
		</script>
	</head>

	<body>

		<div class="site-wrapper">

			<div class="site-wrapper-inner">

				<div class="cover-container">

					<div id="masthead" class="masthead clearfix">
						<div id="mastheadBackground" style="display: none;" ></div>
						<div class="inner">
							<h3 class="masthead-brand"><a href="/"><img src="//ipfs.pics/ipfs/QmNvuHJbTHafrABhitFcQ5srv7FeCfHr6jFiyoHhuRh8wK"></img></a></h3>
							<nav>
								<ul class="nav masthead-nav">
									<li><a href="/">Upload</a></li>
									<li><a href="/random">Random</a></li>
									<li><a href="/trending">Trending</a></li>
								</ul>
							</nav>
						</div>
					</div>

					<div class="inner cover">
						<div id="message-new" class="alert alert-success" role="alert">
							<b>Congratulations</b> you uploaded a picture, you can share it now <br>
							<a class="btn btn-primary btn-sm btn-social" href="http://www.facebook.com/sharer.php?u=https://ipfs.pics/<?php echo $hash; ?>" target="_BLANK"><i class="fa fa-facebook fa-2x"></i></a>
							<a class="btn btn-info btn-sm btn-social" href="https://twitter.com/intent/tweet?url=https://ipfs.pics/<?php echo $hash; ?>&via=IpfsPics" target="_BLANK"><i class="fa fa-twitter fa-2x"></i></a>
							<a class="btn btn-default btn-sm btn-social" href="http://www.pinterest.com/pin/find/?url=https://ipfs.pics/<?php echo $hash; ?>" target="_BLANK"><i class="fa fa-pinterest fa-2x" style="color:red;"></i></a>
							<a class="btn btn-default btn-sm btn-social" href="http://reddit.com/submit?url=https://ipfs.pics/<?php echo $hash; ?>" target="_BLANK"><i class="fa fa-reddit fa-2x" style="color:black;"></i></a>
							<a class="btn btn-danger btn-sm btn-social" href="http://plus.google.com/share?url=https://ipfs.pics/<?php echo $hash; ?>" target="_BLANK"><i class="fa fa-google-plus fa-2x" ></i></a>

							<br>
							You can also copy and paste the address:
							<div class="input-group">
								<span class="input-group-addon">address</span>
							      <input type="text" id="shareUrl" class="form-control shareFields" value="https://ipfs.pics/<?php echo $hash ?>" readonly>
							</div>
							<br>
							You can also use the embed code on your own page:
							<div class="input-group">
								<span class="input-group-addon">embed</span>
							      <input type="text" id="embedCode" class="form-control shareFields" value="<a target='_BLANK' href='https://ipfs.pics/<?php echo $hash ?>'><img src='https://ipfs.pics/ipfs/<?php echo $hash ?>'/></a>" readonly>
							</div>
						</div>
						<div id="message-last-upload" class="alert alert-info" role="alert">This is the last picture you uploaded</div>
						<div class="picture-wrapper">
							<?php
							 if (!$isDir and !$isBanned and $isBackendWorking) { 
								echo "<img src='//$hostname/ipfs/$hash' class='picture'></img>";
							} 

							if ($isDir and !$isBanned and $isBackendWorking) {
								echo "<!--";
								echo sanitize($dirContent);
								echo "-->";							
								$isFirstElement = true;
								foreach( $dirContent as $e ) {
									if ( $e['Size'] > 400 ) {
										echo "<div class='dir-elem'>";
										$eHash = $e['Hash'];
										echo "<a href='//$hostname/$eHash' target='_BLANK'><img src='//$hostname/ipfs/$eHash' class='picture'></img></a>";
										echo "</div>";
									}
									if ( $e['Size'] < 400 ) {
										echo "<div class='dir-elem ".($isFirstElement ? "dir-title" : "")."'>";
										echo sanitize($ipfs->cat($e['Hash']));
										echo "</div>";
									}
									$isFirstElement = false;
								}
							}
							if ($isBanned and $isBackendWorking) {
								echo "This picture has been removed from our servers for technical or legal reasons.<br>";
								echo "We remove everything that is either not an image or illegal under canadian law. <br>";
								echo "You can probably still access it through your own gateway.";
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
									<li role="presentation" class="underMenuButton "><a class="voteButton" data-hash="<?php echo $hash; ?>" data-vote="upvote" href="#" accesskey="U"><span class="glyphicon glyphicon-chevron-up" aria-hidden="true"></span></a></li>
									<li role="presentation" class="voteScore" data-hash="<?php echo $hash; ?>"><span class="badge"><?php echo $score; ?></span></li>
									<li role="presentation" class="underMenuButton "><a class="voteButton" data-hash="<?php echo $hash; ?>" data-vote="downvote" href="#" accesskey="D"><span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span></a></li>
									<li role="presentation" class="underMenuButton "><a class="voteButton" data-hash="<?php echo $hash; ?>" data-vote="report" href="#">Report</a></li>
									<?php 
									if ($isDir) {
										?>
										<li id="underMenuDownload" role="presentation" class="underMenuButton disabled"><a href="#">Download</a></li>
									<?php } else { ?>
										<li id="underMenuDownload"  role="presentation" class="underMenuButton "><a href="//ipfs.pics/ipfs/<?php echo $hash ?>?dl=1" target="_BLANK">Download</a></li>
									<?php } ?>

									<a class="btn btn-primary btn-sm btn-social" href="http://www.facebook.com/sharer.php?u=https://ipfs.pics/<?php echo $hash; ?>" target="_BLANK"><i class="fa fa-facebook fa-2x"></i></a>
									<a class="btn btn-info btn-sm btn-social" href="https://twitter.com/intent/tweet?url=https://ipfs.pics/<?php echo $hash; ?>&via=IpfsPics" target="_BLANK"><i class="fa fa-twitter fa-2x"></i></a>
									<a class="btn btn-default btn-sm btn-social" href="http://www.pinterest.com/pin/find/?url=https://ipfs.pics/<?php echo $hash; ?>" target="_BLANK"><i class="fa fa-pinterest fa-2x" style="color:red;"></i></a>
									<a class="btn btn-default btn-sm btn-social" href="http://reddit.com/submit?url=https://ipfs.pics/<?php echo $hash; ?>" target="_BLANK"><i class="fa fa-reddit fa-2x" style="color:black;"></i></a>
									<a class="btn btn-danger btn-sm btn-social" href="http://plus.google.com/share?url=https://ipfs.pics/<?php echo $hash; ?>" target="_BLANK"><i class="fa fa-google-plus fa-2x" ></i></a>

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


				if (!isMobile) {
					if (<?php echo var_export(!$isDir) ?>) {
						$(".picture").click(function () {
							$(this).toggleClass("fullscreenPicture");
						});
					}
				}


				if (window.location.hash) {
					ancher = window.location.hash.substring(1);
					history.replaceState("", document.title, window.location.pathname);
					if (ancher == "new") {
						$("#message-new").show();
						if(uploads[0] == "") {
							uploads[0] = hash;
						} else {
							uploads.push(hash);
						}
						setCookie("uploads", uploads, 30);
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
			</script>

	</body>
</html>
<?php

function sanitize ($text) {
	return htmlspecialchars($text, ENT_QUOTES);
}
