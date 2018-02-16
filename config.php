<?php

// Contains all the things you can tweak, plus some you shouldn't.



//
// Basic setup
//

error_reporting(E_ALL);

date_default_timezone_set('Europe/Athens');
ini_set('default_charset', 'UTF-8');
//mb_internal_encoding("UTF-8");

set_time_limit(30);

header('Content-Type: text/html; charset=UTF-8');
//header('Content-Type: text/plain; chatset=UTF-8');



//
// Debugging
//

// setting this to 1 will make a lot of debug messages to appear
define("debug", 0);



//
// Redirecting
//

// wheather you're using HTTP or HTTPS
$protocol = "http://";

// don't touch these
$host = $_SERVER['HTTP_HOST'];
$path = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');



//
// Files and directories
//

// where the photos are located, relative to config.php
$photo_dir = "./images";

// the extensions your photos have, I'm using this to search $photo_dir
$extensions = ".jpg|.jpeg|.png";

// extension the files beeing processed get, temporarily
$processed = ".new";



//
// Thumbnails
//

$thumbs = array
(
	// suffix added to the Stream and Album thumbnails
	// stream thumbs need to have a different suffix than album thumbs
	'stream_suffix' => '_thumb-s',
	'album_suffix' => '_thumb-a',
	
	// with "older" you'll see photos from older the newer, with "newer" it's vice versa
	'stream_order' => 'newer',
	'album_order' => 'older',
	
	// how many photos do I pressent in a Stream or Album page
	'stream_per_page' => 20,
	'album_per_page' => 10,
	
	// size of the larger side of Stream thumbs in pixels, used by manage.php to generate them
	'stream_size' => 150,
	
	// size of the side of Album thumbs in pixels, used by manage.php to generate them
	// this is square
	'album_size' => 76,
);



//
// Viewing
//

// what should appear as title on pictures that don't have a title
$untitled = "untitled";

$page = array
(
	// how many pages to show arround the current/active one
	'around' => 3,
	
	// how many pages to show at the end and start of paging
	'edge' => 2,
	
	// gap between 'edge' and 'around' it'll tolerate before it starts trimming
	// setting it to 0 will keep the sharp limits
	'tolerance' => 2,
);

// geographic coordinates, used to tell the time in a texttual/verbal way
$geolocation = array
(
	// latitude from North
	'latitude' => 37.9,

	// longitude from West, if it's east, then put a negetive sign
	'longitude' => -23.7,

	// dunno what is this, not touching it
	'zenith' => 90,
);



//
// Albums
//

// Albums are created by adding a keyword in each photo that belongs to an
// album. So adding 'album-concert' to a photo will later allow you to
// place it in the 'concert' album.

$albums = array
(
	// merges with the suffixes bellow, to produce the keyword that denotes in which
	// album a photo belongs. This prefix gets removed, so the album keyword
	// doesn't appear on the keyword list
	'prefix' => 'album-',

	// the name of the album as it appears on site and the suffix for that album
	array("Abstract", "abstract"),
	array("Live concerts", "concert"),
	array("Manequennes", "manequenne"),
	array("Nature", "nature"),
	array("Night photography", "night"),
	array("People", "people"),
	array("Urban enviroment", "urban")
);



//
// Splash page
//

$splash_photos = array
(
	// how many will the splash photos be
	'total' => 5,
);



//
// Manage
//

// used to protect the management operations in manage.php from casual abuse
$passphrase_axiom = "secret";

?>