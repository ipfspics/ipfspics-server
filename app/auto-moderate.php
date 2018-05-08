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
# Imports the Google Cloud client library
use Google\Cloud\Vision\VisionClient;

# Your Google Cloud Platform project ID
$projectId = $google_cloud_project;


print("gc \n \n");

if (getenv('IPFSPICS_DB') != "") {
	        $mongo = new MongoDB\Client(getenv('IPFSPICS_DB'));
} else {
	        $mongo = new MongoDB\Client("mongodb://localhost:27017");
}
$db = $mongo->ipfspics;
$ipfs = new IPFS("localhost", "8080", "5001");

# Instantiates a client
$vision = new VisionClient([
    'projectId' => $projectId,
    'keyFilePath' => '/etc/ipfspics/gcloud.key'
]);


$unmoderated = $db->hashes->find(['gcloud.adult' => ['$exists'=> false], "views"=>  ['$exists'=> true]], ["sort"=> ["views"=> -1 ], "limit" => 10]);

foreach($unmoderated as $i) {
	$hash = $i['hash'];
	# The name of the image file to annotate
	$fileName = "https://ipfs.io/ipfs/" . $hash;
    print( $hash);
    print( "<br>");

	$content = file_get_contents($fileName);
	if ($content) {
		# Prepare the image to be annotated
		$image = $vision->image($content, [
		    "SAFE_SEARCH_DETECTION"
		]);

		# Performs label detection on the image file
		$annotations = $vision->annotate($image);
		$safe = $annotations->safeSearch();
		$db->hashes->updateOne(["hash" => $hash], ['$set' => ["gcloud.adult"=> $safe->adult(), "gcloud.spoof"=> $safe->spoof(), "gcloud.medical"=> $safe->medical(), "gcloud.violence"=>$safe->violence(), "gcloud.racy"=> $safe->racy()]]);

	} else {
 
		echo "Could not download hash: $hash \n";
	}
}

