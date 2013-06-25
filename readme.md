Bloubell
=========

Bloubell is a minimalist youtube PHP API.

Search youtube videos from keywords.

<a href="http://www.youtube.com/" target="_blank">http://www.youtube.com</a> does not sponsor this API.

Requirements
------------
* PHP 5.2.0 or newer
* <a href="https://github.com/92bondstreet/swisscode" target="_blank">SwissCode</a>


What comes in the package?
--------------------------
1. `bloubell.php` - The Bloubell class functions to get results from request to youtube.com
2. `example.php` - All Bloubell functions call


Example.php
-----------

	// Init constructor with false value: no dump log file
	$bloubelle = new Bloubell();

	// Get videos
	$videos = $bloubelle->search_videos("get lucky cover",10); 
	print_r($videos);

	// Get videos with default number results
	$videos = $bloubelle->search_videos("justin timberlake"); 
	print_r($videos);


To start the demo
-----------------
1. Upload this package to your webserver.
4. Open `example.php` in your web browser and check screen output. 
5. Enjoy !


Project status
--------------
Bloubell is currently maintained by Yassine Azzout.


Authors and contributors
------------------------
### Current
* [Yassine Azzout][] (Creator, Building keeper)

[Yassine Azzout]: http://www.92bondstreet.com


License
-------
[MIT license](http://www.opensource.org/licenses/Mit)

