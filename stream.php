<?php

require_once("config.php");
require_once("static.php");
require_once("functions.php");

// ---

/*
what this file does:

1. recieve input ($page)
2. get a list of all photos ($photo_list)
3. see which of these photos I'll be showing on this page and print their thumbnails ($page['current_list'])
4. print paging ($page, $n_pages, $page_prev, $page_next)
*/


// got $page from common.php
recieve_input();

echo $static['begin'];

echo $static['visitor_heading'];

echo "\n\n<h2>stream</h2>\n";

if ($thumbs['stream_order'] == "newer")
	$photo_list = get_list("photos", "newer");
elseif ($thumbs['stream_order'] == "older")
	$photo_list = get_list("photos", "older");
	
// producing: $n_pages, $prev_photo, $next_photo, $page[], $next_page, $prev_page
calc_paging($photo_list, "stream");

// print thumbs
// I will make this a function if it even remotely resembles
// what I'll write in Album
// todo: mallon edw prepei na antikatastisw to $photo_id me $photo['id']
echo "\n<ul id=\"thumbs\">\n";
foreach ($page['current_list'] as $item)
{
	$temp = name_sep($item);
	$photo_id = $temp[0];
	$photo_ext = $temp[1];
	
	echo "\t<li><span><a href=\"photo.php?photo=$photo_id#photo\"><img src=\"$photo_dir/$photo_id" . $thumbs['stream_suffix'] . "$photo_ext\" /></a></span></li>\n";
}
echo "</ul>\n\n";

print_paging("stream");

echo $static['visitor_footer'];

echo $static['end'];

?>