<?php
/*
    Redirect to a random picture
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
use Cloutier\PhpIpfsApi\IPFS;

$host = $_SERVER['HTTP_HOST'];
$hash = "Qma25ZSNbp9AdjrPczjzKYm7zUAdcu9jQZJXbsPiifW79M";

if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
	$protocol = "https";
} else {
	$protocol = "http";
}

if (getenv('IPFSPICS_DB') != "") {
	        $mongo = new MongoDB\Client(getenv('IPFSPICS_DB'));
} else {
	        $mongo = new MongoDB\Client("mongodb://localhost:27017");
}
$db = $mongo->ipfspics;
$ipfs = new IPFS("localhost", "8080", "5001");
$showables = $db->hashes->aggregate([
	['$match'=> ['gcloud.adult' => "VERY_UNLIKELY"]],
	['$sample'=> ['size'=> 1]]]);

foreach ($showables as $i) {
	$hash = $i['hash'];
}

header("Location: $protocol://$host/$hash#random");
