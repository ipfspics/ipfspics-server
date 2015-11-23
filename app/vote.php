<?php
/*
    Manages upvotes, report and downvotes on each object.
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
include "../pswd.php";
if ( !isset($_GET['id']) or !isset($_GET['type']) ) {
	exit("wrong params");
} else {
	if( preg_match('/^[a-z0-9]+$/i', $_GET['id']) and preg_match('/^[a-z0-9]+$/i', $_GET['type']) ) {
		$hash = $_GET['id'];
		$type = $_GET['type'];
	} else {
		// security problem
		exit("wrong hash");
	}
}

$db = new PDO('mysql:host=localhost;dbname=hashes;charset=utf8', $db_user, $db_pswd);
$ip=$_SERVER['REMOTE_ADDR'];
$info = $db->query("SElECT hash FROM hash_info WHERE hash='$hash'")->fetch();

if ( $info['hash'] ) {
	$hash = $info['hash'];
} else {
	exit("unknown hash");
}
$votes = $db->query("SElECT * FROM votes WHERE hash='$hash' AND ip='$ip'")->fetch();

if ( $votes['hash'] ) {
	//already voted, we change the vote if one is not spamming
	if ($votes['vote_type'] != $type) {
		$add = $db->prepare("UPDATE votes SET vote_type = :type, timestamp = UNIX_TIMESTAMP() WHERE ip = :ip AND hash = :hash");
		$add->bindParam(":hash", $hash);
		$add->bindParam(":type", $type);
		$add->bindParam(":ip", $ip);
		$add->execute();
	} 

	echo "success";	

} else {

	$add = $db->prepare("INSERT INTO votes (hash, vote_type, ip, timestamp) VALUES (:hash, :type, :ip, UNIX_TIMESTAMP())");
	$add->bindParam(":hash", $hash);
	$add->bindParam(":type", $type);
	$add->bindParam(":ip", $ip);
	$add->execute();

	echo "success";
}

