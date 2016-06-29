<?php
/*
    Simple router, .htaccess interpreter for webservers other then Apache HTTPD
    Copyright (C) 2016 Sjon Hortensius

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

$path = ltrim($_SERVER['DOCUMENT_URI'], '/');

foreach (file('.htaccess', FILE_IGNORE_NEW_LINES) as $line) {
	if ( !preg_match('~^RewriteRule (?P<match>\^.*) (?P<page>.*?)(?P<params>\?.*)?$~', $line, $rule) )
		continue; # not a rewrite rule

	if ( !preg_match('~'. $rule['match'] .'~', $path, $parts) )
		continue; # no match

	// Convert numeric array into ['$1' => '25'], suitable for str_replace
	$replace = [];
	foreach ($parts as $idx => $part)
		$replace[ '$'. $idx ] = $part;

	if ( isset($rule['params']) ) {
		parse_str(ltrim($rule['params'], '?'), $params);
		foreach ($params as $key => $value) {
			if (false !== strpos($value, '$'))
				$value = str_replace(array_keys($replace), $replace, $value);

			$_GET[$key] = $value;
		}
	}

	if (!is_readable($rule['page']) || __DIR__ !== substr(dirname(__DIR__ .'/'. $rule['page']), 0, strlen(__DIR__)))
		die('it seems we escaped our docroot, this shouldn\'t happen');

	return require($rule['page']);
}

require('index.php');
