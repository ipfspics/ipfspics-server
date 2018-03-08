<?php
/*
    Deals with the addition of objects to the IPFS network
    Copyright (C) 2015  IpfsPics Team

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
error_reporting(0);

include __DIR__ ."/../pswd.php";
require __DIR__ . '/../vendor/autoload.php';

use Cloutier\PhpIpfsApi\IPFS;

$ipfs = new IPFS("ipfs");

$errorHash = "QmW3FgNGeD46kHEryFUw1ftEUqRw254WkKxYeKaouz7DJA";
$host = $_SERVER['HTTP_HOST'];

if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
	$protocol = "https";
} else {
	$protocol = "http";
}

$db = new PDO('mysql:host=localhost;dbname=hashes;charset=utf8', $db_user, $db_pswd);
$uploadsInLastHour = $db->query("SELECT COUNT(*) FROM hash_info WHERE first_seen > UNIX_TIMESTAMP() - 3600")->fetch();

if ($uploadsInLastHour[0] < 100) {

	$image = $_FILES['img']['tmp_name'];
	$fo = fopen($_FILES['img']['tmp_name'], "r");
	$imageContent =  fread($fo, filesize($image));
	$hash = $ipfs->add($imageContent);
} else {
	$hash = $errorHash;
}
if ($hash == "") {
	$hash = $errorHash;
}
header("Location: $protocol://$host/$hash#new"  );
