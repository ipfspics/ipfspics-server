/*
    Wrapper to IPFs
    Copyright (C) 2015 IpfsPics 2015

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
var http = require('http');
var url = require('url');
var exec = require('child_process').exec;
http.createServer(function (req, res) {
	var hash = url.parse(req.url).pathname.substr(1);
	var query = url.parse(req.url).search.substr(1);
	if (ipfsHashOK(hash)) {
		if (query == "content") {
			ipfsContent(hash, function (data) {
				res.writeHead(200, {'Content-Type': 'image/png'});
				res.write(data, 'binary');
				res.end();
			});
		}

		if (query == "add") {
			ipfsAdd(hash, function (hash) {
				res.writeHead(200, {'Content-Type': 'text/plain'});
				res.write(hash);
				res.end();
			});
		}
		
		if (query == "size") {
			ipfsSize(hash, function (size) {
				res.writeHead(200, {'Content-Type': 'text/plain'});
				res.write(size);
				res.end();
			});
		}

		if (query == "dirContent") {
			ipfsDirContent(hash, function (content) {
				res.writeHead(200, {'Content-Type': 'text/plain'});
				res.write(content);
				res.end();
			});
		}

	}


}).listen(8090, '127.0.0.1');

var ipfsHashOK = function (hash) {
	return true;
}

var ipfsContent = function (hash, callback) {
	exec("ipfs cat " + hash, function (err, stdout, stderr) {
		callback(stdout);
	});

}

var ipfsSize = function (hash, callback) {

	exec("ipfs object stat " + hash, function (err, stdout, stderr) {
		var re = new RegExp(/\d{4,}/g);
		ans = String(stdout.match(re));
		callback(ans);
	});
};

var ipfsAdd = function (path, callback) {

	exec("ipfs add -q " + path, function (err, stdout, stderr) {
		callback(stdout);
	});
};

var ipfsDirContent = function (hash, callback) {
	exec("ipfs ls " + hash, function (err, stdout, stderr) {
		if (stdout == "") {
			stdout = "empty";
		}
		callback(stdout);
	});

};

