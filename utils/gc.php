<?php 
/*
    Removes banned pictures
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
error_reporting(1);

require __DIR__ . '/../vendor/autoload.php';

use Cloutier\PhpIpfsApi\IPFS;

print("gc \n \n");

if (getenv('IPFSPICS_DB') != "") {
	        $mongo = new MongoDB\Client(getenv('IPFSPICS_DB'));
} else {
	        $mongo = new MongoDB\Client("mongodb://localhost:27017");
}
$db = $mongo->ipfspics;
$ipfs = new IPFS("localhost", "8080", "5001");

$toUnpin = $db->hashes->find(['gcloud.adult' => "VERY_LIKELY"]);

foreach($toUnpin as $i) {
	$hash = $i['hash'];
	$ipfs->pinRm($hash);
	echo "unpined $hash\n";
}

