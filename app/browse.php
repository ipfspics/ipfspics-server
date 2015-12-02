<?php
include "../pswd.php";
include "class/ipfs.class.php";


$db = new PDO('mysql:host=localhost;dbname=hashes;charset=utf8', $db_user, $db_pswd);
$ipfs = new IPFS("localhost", "8080", "5001"); 
$hostname = $_SERVER['HTTP_HOST'];

$picsToDisplay = $db->query("SELECT hash AS p_hash, (((SELECT COUNT(*) FROM votes WHERE vote_type = 'upvote' AND hash = p_hash) + 1.9208) / (   (SELECT COUNT(*) FROM votes WHERE vote_type = 'upvote' AND hash = p_hash) + (SELECT COUNT(*) FROM votes WHERE vote_type = 'downvote' AND hash = p_hash))   - 1.96 * SQRT(   ((SELECT COUNT(*) FROM votes WHERE vote_type = 'upvote' AND hash = p_hash) * (SELECT COUNT(*) FROM votes WHERE vote_type = 'downvote' AND hash = p_hash)) /   ((SELECT COUNT(*) FROM votes WHERE vote_type = 'upvote' AND hash = p_hash) + (SELECT COUNT(*) FROM votes WHERE vote_type = 'downvote' AND hash = p_hash)) + 0.9604) /   ((SELECT COUNT(*) FROM votes WHERE vote_type = 'upvote' AND hash = p_hash) + (SELECT COUNT(*) FROM votes WHERE vote_type = 'downvote' AND hash = p_hash))) /  (1+3.8416 / ((SELECT COUNT(*) FROM votes WHERE vote_type = 'upvote' AND hash = p_hash) + (SELECT COUNT(*) FROM votes WHERE vote_type = 'downvote' AND hash = p_hash))) AS lower_bound FROM hash_info WHERE first_seen > UNIX_TIMESTAMP() - 11209600 AND type != 'dir' HAVING (SELECT COUNT(*) FROM votes WHERE vote_type = 'upvote' AND hash = p_hash)+(SELECT COUNT(*) FROM votes WHERE vote_type = 'downvote' AND hash = p_hash) > 0 ORDER BY lower_bound DESC;");

?>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,initial-scale=1" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<link rel="icon" href="//ipfs.pics/favicon.ico" type="image/x-icon">
		<meta name="twitter:card" content="summary_large_image">
		<meta name="twitter:site" content="@IpfsPics">

		<!-- Custom styles for this template -->
		<link href="cover.css" rel="stylesheet">


		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
                <title>Trending crypto kittens</title>
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
	</head>

	<body>

		<div class="site-wrapper">

			<div class="site-wrapper-inner">

				<div class="cover-container">

					<div id="masthead" class="masthead clearfix">
						<div id="mastheadBackground" style="display: none;" ></div>
						<div class="inner">
							<h3 class="masthead-brand"><a href="/"><img src="//ipfs.pics/ipfs/QmNvuHJbTHafrABhitFcQ5srv7FeCfHr6jFiyoHhuRh8wK"></a></h3>
							<nav>
								<ul class="nav masthead-nav">
									<li><a href="/">Upload</a></li>
									<li><a href="/random">Random</a></li>
									<li class="active"><a href="browse.html">Trending</a></li>
								</ul>
								<ul class="">
									<li></li>
								</ul>
							</nav>
						</div>
					</div>

					<div class="inner cover">
						<?php
						$turnForAds = 3;
						while ($pic = $picsToDisplay->fetch()) {
							$hash = $pic['p_hash'];
							?>
							<div class="picture-wrapper">
								<img src="//ipfs.pics/ipfs/<?php echo $hash; ?>" class="picture" />
							</div>
							<div class="underMenu">
								<div class="panel panel-default">
									<div class="panel-body">
										<ul class="nav nav-pills">
											<li role="presentation" class="underMenuButton "><a class="voteButton" data-hash="<?php echo $hash; ?>" data-vote="upvote" href="#" accesskey="U"><span class="glyphicon glyphicon-chevron-up" aria-hidden="true"></span></a></li>
											<li role="presentation" class="voteScore" data-hash="<?php echo $hash; ?>"><span class="badge"><?php echo $score; ?></span></li>
											<li role="presentation" class="underMenuButton "><a class="voteButton" data-hash="<?php echo $hash; ?>" data-vote="downvote" href="#" accesskey="D"><span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span></a></li>
											<li role="presentation" class="underMenuButton "><a class="voteButton" data-hash="<?php echo $hash; ?>" data-vote="report" href="#">Report</a></li>
											<li id="underMenuPermalink"  role="presentation" class="underMenuButton "><a href="//ipfs.pics/<?php echo $hash ?>" target="_BLANK">Permalink</a></li>

											<a class="btn btn-primary btn-sm btn-social" href="http://www.facebook.com/sharer.php?u=https://ipfs.pics/<?php echo $hash; ?>" target="_BLANK"><i class="fa fa-facebook fa-2x"></i></a>
											<a class="btn btn-info btn-sm btn-social" href="https://twitter.com/intent/tweet?url=https://ipfs.pics/<?php echo $hash; ?>&via=IpfsPics" target="_BLANK"><i class="fa fa-twitter fa-2x"></i></a>
											<a class="btn btn-default btn-sm btn-social" href="http://www.pinterest.com/pin/find/?url=https://ipfs.pics/<?php echo $hash; ?>" target="_BLANK"><i class="fa fa-pinterest fa-2x" style="color:red;"></i></a>
											<a class="btn btn-default btn-sm btn-social" href="http://reddit.com/submit?url=https://ipfs.pics/<?php echo $hash; ?>" target="_BLANK"><i class="fa fa-reddit fa-2x" style="color:black;"></i></a>
											<a class="btn btn-danger btn-sm btn-social" href="http://plus.google.com/share?url=https://ipfs.pics/<?php echo $hash; ?>" target="_BLANK"><i class="fa fa-google-plus fa-2x" ></i></a>

										</ul>
									</div>
								</div>
							</div>
							<?php
							$turnForAds++;
							$turnForAds = $turnForAds % 3;
							if ($turnForAds == 0) {
								?>
								<br>
								<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
								<!-- Browse responsive -->
								<ins class="adsbygoogle"
								     style="display:block"
								     data-ad-client="ca-pub-7083426718110488"
								     data-ad-slot="2537904257"
								     data-ad-format="auto"></ins>
								<script>
								(adsbygoogle = window.adsbygoogle || []).push({});
								</script>
								<?php
							} else { 
								echo "<br><br><br>";
							} 
						}
						?>


					<div class="mastfoot">
						<div id="footer" class="inner">
							<h3>Come back tomorrow for more pictures!</h3><br>
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


			</script>

			<!-- Bootstrap core JavaScript
			================================================== -->
		
	</body>
</html>
