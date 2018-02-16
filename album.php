<?php

require_once("config.php");
require_once("static.php");
require_once("functions.php");

// ---

/*
In short, what we do: 

1. recieve input: $photo['id'], $keyword, $page['current'], $album['current']
2. find which photos have this keyword, if no photos found then say so
3. if the photo you requested is not in the group or no photo requested then show the first one
4. print photo, and if there is a next photo make this one a link to that
5. if there is a next and prev photos, give us links for them
6. print thumbs, based on paging
7. print paging for thumbs
*/

recieve_input();

echo $static['begin'];

echo $static['visitor_heading'];

if (!isset($albums['current']))
{
	echo "<div id=\"notfound\">\n";
	echo "\t<p>You didn't give me an album code.</p>\n";
	echo "</div>";
	echo $static['visitor_footer'];
	echo $static['end'];
	exit();
}

$photo_list = get_list("photos", "newer");

$photo_list = keyword_seek($albums['prefix'] . $albums['current'], $photo_list, FALSE, FALSE);

// bail if we can't find photos with this keyword
if(!isset($photo_list))
{
	echo "<div id=\"notfound\">\n";
	echo "\t<p>No such album found.</p>\n";
	echo "</div>";
	echo $static['visitor_footer'];
	echo $static['end'];
	exit();
}

// find the location of the photo inside $photo_list
if (isset($photo['id']))
{
	$counter = 0;
	foreach ($photo_list as $item)
	{
		$name = name_sep($item);
		$name = $name[0];

		if ($name == $photo['id'])
		{
			$photo['location'] = $counter;
			$photo['file'] = $item;
		}
		
		$counter = $counter + 1;
	}

	unset($counter);
}

// and if that photo is not in the list, show the first one
// I could also have checked for $photo['file'] instead of
// $photo['location']
if (!isset($photo['location']))
{
	$photo['location'] = 0;
	$photo['file'] = $photo_list[0];
	
	$temp = name_sep($photo['file']);
	$photo['id'] = $temp[0];
	unset($temp);
}

calc_paging($photo_list, "album");

$iptc = fetch_iptc($photo['file']);

// show us the human-redable name of the album we're viewing
foreach($albums as $item)
{
	if (is_array($item))
		if (isset($item[0]))
			if ($item[1] == $albums['current'])
				echo "\n\n<h2>album: " . $item[0] . "</h2>\n\n";
}

// print (the markup for) the photo
if (isset($photo['next']))
{
	echo "\n\t<a href=\"album.php?album=" . $albums['current'] . "&amp;photo=" . $photo['next'] . "&amp;page=" . $photo['next_belong'] . "#photo\">";
	echo "\n\t\t<img id=\"photo\" src=\"$photo_dir/" . $photo['file'] . "\" alt=\"" . $iptc['title'] . "\" />";
	echo "\n\t</a>";
	echo "\n\t";
}
else
{
	echo "\n\t<img id=\"photo\" src=\"$photo_dir/" . $photo['file'] . "\" alt=\"" . $iptc['title'] . "\" />";
	echo "\n\t";
}

// print photo controls
echo "\n\t<ul id=\"photo-controls\">\n";
if (isset($photo['prev']))
	echo "\t\t<li><a id=\"prev\" href=\"album.php?album=" . $albums['current'] . "&amp;photo=" . $photo['prev'] . "&amp;page=" . $photo['prev_belong'] . "#photo\">Prev</a></li>\n";
else
	echo "\t\t<li>Prev</li>\n";
if (isset($photo['next']))
	echo "\t\t<li><a id=\"next\" href=\"album.php?album=" . $albums['current'] . "&amp;photo=" . $photo['next'] . "&amp;page=" . $photo['next_belong'] . "#photo\">Next</a></li>\n";
else
	echo "\t\t<li>Next</li>\n";
if ($page['total'] >= 2)
	echo "\t\t<li class=\"break\">back to that thumbs <a href=\"album.php?album=" . $albums['current'] . "&amp;photo=" . $photo['id'] . "&amp;page=" . $photo['belong'] . "#thumbs_a\">page</a></li>";
echo "\n\t</ul>";

// print thumbs
echo "\n\t<ul id=\"thumbs_a\">\n";
foreach ($page['current_list'] as $item)
{
	$temp = name_sep($item);
	$name = $temp[0];
	$ext = $temp[1];
	
	if ($name == $photo['id'])
	{
		echo "\t\t<li class=\"highlight\"><span><img src=\"$photo_dir/" . $photo['id'] . $thumbs['album_suffix'] . $ext . "#photo\" /></a></span></li>\n";
	}
	else
	{
		echo "\t\t<li><span><a href=\"album.php?album=" . $albums['current'] . "&amp;photo=$name&amp;page=" . $page['current'] . "#photo\"><img src=\"$photo_dir/$name" . $thumbs['album_suffix'] . $ext . "\" /></a></span></li>\n";
	}
}
echo "\t</ul>";

// print paging
print_paging("album");

echo $static['visitor_footer'];
echo $static['end'];

?>