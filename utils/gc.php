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
error_reporting(0);

include "../pswd.php";
include "../app/class/ipfs.class.php";

print("gc \n \n");

$db = new PDO('mysql:host=localhost;dbname=hashes;charset=utf8', $db_user, $db_pswd);
$ipfs = new IPFS("localhost", "8080", "5001");

// Makes sure all the banned hashes are removed
$bannedHashes = $db->query("SElECT * FROM hash_info WHERE banned = 1;")->fetchAll();

foreach($bannedHashes as $i) {
	$hash = $i['hash'];
	$ipfs->pinRm($hash);
	print "Removed banned hash: $hash \n";
}


$notBannedHashes = $db->query("SElECT * FROM hash_info WHERE banned != 1;")->fetchAll();

foreach($notBannedHashes as $i) {
	$hash = $i['hash'];
	$ipfs->pinAdd($hash);
	print("Pinned hash: $hash \n");
}

// This section if to optionnaly unpin porn on your instance. 
// You have to add "removeSomeNSFW" as an argument when running gc.php.
// For now, it removes NSFW pictures which did not get votes and are more than two weeks old. 
// If you want to keep them, make sure they are replicated on another computer before running this.

if (in_array("removeSomeNSFW", $argv) ) {

	$NSFWtoRemove = $db->query("SELECT hash as p_hash FROM hash_info WHERE nsfw = 1 AND first_seen < UNIX_TIMESTAMP() - 1209600 HAVING (SELECT COUNT(*) FROM votes WHERE hash = p_hash) < 2;")->fetchAll();

	foreach($NSFWtoRemove as $i) {
		$hash = $i['hash'];
		$ipfs->pinRm($hash);
		print "Removed randomly: $hash \n";

	}
} 

print "You can now run ipfs built-in garbage collector \n";
