<?php
/*
    Choses a random picture to display
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
include "var/pswd.php";
$host = $_SERVER['HTTP_HOST'];
$hash = "Qma25ZSNbp9AdjrPczjzKYm7zUAdcu9jQZJXbsPiifW79M";


$db = new PDO('mysql:host=localhost;dbname=hashes;charset=utf8', $db_user, $db_pswd);

$randomHashes = $db->query("SELECT hash FROM hash_info WHERE sfw = 1 ORDER BY RAND() LIMIT 0,1;")->fetch();
$hash = $randomHashes['hash'];
header("Location: http://$host/$hash#random");


?>
