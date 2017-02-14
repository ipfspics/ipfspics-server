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
include __DIR__ ."/../pswd.php";

use Cloutier\PhpIpfsApi\IPFS;
# Imports the Google Cloud client library
use Google\Cloud\Vision\VisionClient;

# Your Google Cloud Platform project ID
$projectId = $google_cloud_project;

# Instantiates a client
$vision = new VisionClient([
    'projectId' => $projectId,
    'keyFilePath' => '/var/www/html/cloud_auth.json'
]);


print("gc \n \n");

$db = new PDO('mysql:host=localhost;dbname=hashes;charset=utf8', $db_user, $db_pswd);
$ipfs = new IPFS("localhost", "8080", "5001");


$unmoderated = $db->query("SElECT * FROM hash_info WHERE banned = 0 AND sfw = 0 AND nsfw = 0;")->fetchAll();

foreach($unmoderated as $i) {
	$hash = $i['hash'];
	# The name of the image file to annotate
	$fileName = "https://ipfs.pics/ipfs/" . $hash;
echo $hash;

	$content = file_get_contents($fileName);
	if ($content) {
		# Prepare the image to be annotated
		$image = $vision->image($content, [
		    "SAFE_SEARCH_DETECTION"
		]);

		# Performs label detection on the image file
		$annotations = $vision->annotate($image);
		$safe = $annotations->safeSearch();
		echo $safe->adult();
	} else {
 
		print "Could now download hash: $hash \n";
	}
}

