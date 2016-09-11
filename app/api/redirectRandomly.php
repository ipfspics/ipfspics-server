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
include __DIR__ ."/../../pswd.php";
$host = $_SERVER['HTTP_HOST'];
$hash = "Qma25ZSNbp9AdjrPczjzKYm7zUAdcu9jQZJXbsPiifW79M";

if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
	$protocol = "https";
} else {
	$protocol = "http";
}

$db = new PDO('mysql:host=localhost;dbname=hashes;charset=utf8', $db_user, $db_pswd, array(
    PDO::ATTR_PERSISTENT => true
));

$hashesByScore = $db->query("SELECT hash, nb_views FROM hash_info WHERE sfw = 1 ORDER BY nb_views DESC;")->fetchAll();
$worstScore = (int) $hashesByScore[sizeof($hashesByScore) - 1]['nb_views'];

// Takes a random number between 1 and the equivalent of the sum of all scores when the values are shifted up so the lowest one is equal to zero.
$randomTrigger = mt_rand(1, ((int) array_sum(array_column($hashesByScore, 'nb_views')) + abs($worstScore) * sizeof($hashesByScore)) );

foreach ($hashesByScore as $e) {
	$randomTrigger -= $e['nb_views'] + abs($worstScore);
	if ($randomTrigger <= 0) {
        	$hash = $e['hash'];
		break;
	}
}

header("Location: $protocol://$host/$hash#random");
