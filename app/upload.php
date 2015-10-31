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

include "../pswd.php";

$errorHash = "QmW3FgNGeD46kHEryFUw1ftEUqRw254WkKxYeKaouz7DJA";
$host = $_SERVER['HTTP_HOST'];

$tmp_name = $_FILES['img']['tmp_name'];
$uploads_dir = '../upload';
$name = date("hms"); 
$originalFile = getcwd().DIRECTORY_SEPARATOR . $uploads_dir . "/" . $name;
move_uploaded_file($tmp_name, $originalFile);

$db = new PDO('mysql:host=localhost;dbname=hashes;charset=utf8', $db_user, $db_pswd);
$uploadsInLastHour = $db->query("SELECT COUNT(*) FROM hash_info WHERE first_seen > UNIX_TIMESTAMP() - 3600")->fetch();

if ($uploadsInLastHour[0] < 100) {
	$n = `convert $originalFile -resize 1080x1080\> -stripe -quality 80% $originalFile`;

	$hash = explode(PHP_EOL, `curl localhost:8090/$originalFile?add`)[0];
	$n = `rm $originalFile`;
} else {
	$hash = $errorHash;
}
if ($hash == "") {
	$hash = $errorHash;
}

header("Location: http://$host/$hash#new"  );
