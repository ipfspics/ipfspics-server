<?php
/*
    Displays pictures with the hash and manages the cache
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
include "class/ipfs.class.php";

$fallbacks = array("http://104.154.250.16", "https://ipfs.io");

if ( !isset($_GET['hash']) ) {
	$hash = "QmX6kHmFXsadTqLDMMnuV5dFqcGQAfNeKAArStw1BKqFW7";
} else {
	if( preg_match('/^[a-z0-9]+$/i', $_GET['hash']) ) {
		$hash = $_GET['hash'];
	} else {
		// security problem
		exit("wrong hash");
	}
}

$ipfs = new IPFS("localhost", "8080", "5001");

$imageContent = $ipfs->cat($hash);

if ($imageContent == "") {
	header("Location: ". $fallbacks[array_rand($fallbacks)] ."/ipfs/$hash");
} else {
	$imageSize = $ipfs->size($hash);  

	if ($imageSize > 7866189) {
//		exit("image is too big");
	}
	session_cache_limiter('none');
	header("Content-type: image/png");
	header('Cache-control: max-age='.(60*60*24*365));
	header('Expires: '.gmdate(DATE_RFC1123,time()+60*60*24*365));
	header('Last-Modified: Thu, 01 Dec 1983 20:00:00 GMT');
	if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
	   header('HTTP/1.1 304 Not Modified');
	   die();
	}
	
	echo $imageContent;
}
