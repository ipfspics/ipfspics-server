# ipfs.pics
[ipfs.pics](http://ipfs.pics/) is a open-source and distributed image hosting website.
It aims to be an alternative to non-libre image hosting websites such as imgur, flickr and others.

It is based on [IPFS - the InterPlanetary File System](https://github.com/ipfs/ipfs). 
The whole application runs on the concept of peer to peer connections, which means that instead of 
hosting the information in a single location, our servers, the data is stored by everyone who wants to. 
When a picture is put on IPFS, it is given a hash, a 46 characters long digital fingerprint. 
No other file will have it and if the same file is added twice then their hashes will be exactly the same, 
which means the picture can still be found on the network simply by knowing the hash, even if our website is down. 
You can find the hash at the end of a picture URL, just like below. 

http://ipfs.pics/QmX7mna7G3BLx2UCdAHviaDastbnvLiVmM2pQ5azBa1H7D

https://ipfs.io/ipfs/QmX7mna7G3BLx2UCdAHviaDastbnvLiVmM2pQ5azBa1H7D

We saw potential in that application for an image hosting website, where you can know for sure your pictures 
will be available forever. 

# Bootstrapping

You can also use our ipfs node as a bootstrap node:

``` ipfs bootstrap add /ip4/45.55.151.20/tcp/4001/ipfs/QmdkJZUWnVkEc6yfptVu4LWY8nHkEnGwsxqQ233QSGj8UP ```

# License

This program is free software: you can redistribute it and/or modify
    it under the terms of the [GNU Affero General Public License](https://www.gnu.org/licenses/agpl-3.0.html) as
    published by the Free Software Foundation, either version 3 of the
    License, or (at your option) any later version.
