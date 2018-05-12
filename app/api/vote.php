<?php
/*
    Manages upvotes, report and downvotes on each object.
    Copyright (C) 2015-2016 Vincent Cloutier
    Copyright (C) 2015-2016 Didier Camus-Ferland

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

require __DIR__ . '/../../vendor/autoload.php';
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

if (getenv('IPFSPICS_DB') != "") {
                $mongo = new MongoDB\Client(getenv('IPFSPICS_DB'));
} else {
                $mongo = new MongoDB\Client("mongodb://localhost:27017");
}
$db = $mongo->ipfspics;

$ip=$_SERVER['REMOTE_ADDR'];
$info = $db->hashes->findOne(["hash" => $hash]);

if ( $info['hash'] ) {
	$hash = $info['hash'];
} else {
	exit("unknown hash");
}
$votes = $db->votes->findOne(["hash" => $hash, "ip" => $ip]);
print_r($votes);
if ( $votes['hash'] ) {
	$db->votes->updateOne(["hash"=> $hash, "ip" => $ip], ['$set' => ["type"=> $type, "timestamp" => time()]]);

	echo "success";	

} else {

	$db->votes->insertOne(["hash"=> $hash, "type" => $type, "ip" => $ip, "timestamp" => time()]);

	echo "success";
}

