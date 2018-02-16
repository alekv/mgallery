<?php

require_once("config.php");
require_once("static.php");
require_once("functions.php");

// ---

/*
What this file does:

1. recieve input ($photo['id']), if didn't recieve input, redirect to stream
2. grab a list of all photos ($photo_list)
3. check if the photo we want is in that list, if not in the list say so
4. read photo title and keywords from metadata ($title, $keywords)
5. find previous and next photo ($next_photo, $prev_photo)
6. print photo with a link to the next one ($photo['file'])
7. show photo title and keywords
8. print link to prev and next photos, plus a link to the page this photo belongs ($belongs)
*/

recieve_input();

if ($thumbs['stream_order'] == "newer")
	$photo_list = get_list("photos", "newer");
elseif ($thumbs['stream_order'] == "older")
	$photo_list = get_list("photos", "older");

if (!isset($photo['id']))
{
	header("Location: $protocol$host$path/stream.php");
	die();
}

echo $static['begin'];

echo $static['visitor_heading'];

$photo['location'] = search_list($photo['id'], $photo_list);

if ($photo['location'] === NULL)
{
	echo "<div id=\"notfound\">\n";
	echo "\t<p>I can't find that photo.</p>\n";
	echo "</div>";
	echo $static['visitor_footer'];
	echo $static['end'];
	exit();
}

// todo: edw isws na itane kali idea na svisw to name_sep() kai na apothikefsw to onoma kai to extension tou arxeiou sto $photo[] (me ena preg_match EDW!)

$photo['file'] = id_name($photo['id'], $photo_list);

$iptc = fetch_iptc($photo['file']);

// what we want from this function right now is next and prev photos
calc_paging($photo_list, "stream");

if (isset($photo['next']))
{
	echo "\n<a href=\"photo.php?photo=" . $photo['next'] . "#photo\">";
	echo "\n\t<img id=\"photo\" src=\"$photo_dir/" . $photo['file'] . "\" alt=\"" . $iptc['title'] . "\" />";
	echo "\n</a>";
	echo "\n\t";
}
else
{
	echo "\n\t<img id=\"photo\" src=\"$photo_dir/" . $photo['file'] . "\" alt=\"" . $iptc['title'] . "\" />";
	echo "\n\t";
}

// print title
echo "\n<h2>" . $iptc['title'] . "</h2>\n";

// print date
if (empty($iptc['date']))
{
	echo "\n<div id=\"date\">Unknown date</div>\n";
}
else
{
	$date = human_date($iptc['date'], $iptc['time']);
	echo "\n<div id=\"date\">$date</div>\n";
}

// print keywords
/*
$keywords = implode(", ", $iptc['keywords']);
echo "\n<div id=\"keywords\">\n";
echo "\t<p><strong>Keywords</strong></p>\n";
echo "\t<p>$keywords</p>\n";
echo "</div>\n";
*/

// print photo controls
echo "\n<ul id=\"photo-controls\">\n";
if (isset($photo['prev']))
	echo "\t<li><a id=\"prev\" href=\"photo.php?photo=" . $photo['prev'] . "#photo\">Prev</a></li>\n";
else
	echo "\t<li>Prev</li>\n";
if (isset($photo['next']))
	echo "\t<li><a id=\"next\" href=\"photo.php?photo=" . $photo['next'] . "#photo\">Next</a></li>\n";
else
	echo "\t<li>Next</li>\n";
	echo "\t<li class=\"break\">back to that journal <a href=\"stream.php?page=" . $photo['belong'] . "\">page</a></li>";
echo "\n</ul>";

echo $static['visitor_footer'];

echo $static['end'];

?>