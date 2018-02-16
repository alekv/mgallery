<?php

require_once("config.php");
require_once("functions.php");
require_once("static.php");

// ---

echo $static['begin'];

echo $static['visitor_heading'];

echo '<div id="warning">
	<p>Some intro text. Anything you want, really.</p>
</div>

<div id="intro">

<p>I take pictures as I experience, imagine, and live. By looking at my pictures, you\'re walking along with me, and seeing the world through my eyes.</p>

<p>My exhibition is this unfoldment of consciousness from one moment to the next, from one sense to another, taking the form of a mutable <a href="stream.php">stream</a>. It\'s like Instagram or Tumblr.</p>

<p>Random photos from the stream:</p>

<ul id="thumbs">
';

if ($thumbs['stream_order'] == "newer")
	$photo_list = get_list("photos", "newer");
elseif ($thumbs['stream_order'] == "older")
	$photo_list = get_list("photos", "older");
	
// just run you piece of ****!
/*
if (!isset($splash_photos['list']))
{
	$splash_photos['list'] = "";
	while (count($splash_photos['list']) <= $splash_photos['total']-1)
	{
		$splash_photos['list'][] = $photo_list[mt_rand(0, count($photo_list)-1)];
		$splash_photos['list'] = array_unique($splash_photos['list'], SORT_STRING);
	}
}
*/

foreach($splash_photos['list'] as $item)
{
	$temp = name_sep($item);

	$item = $photo_dir . "/" . $temp[0] . $thumbs['stream_suffix'] . $temp[1];
	
	//debug_show($temp);
	echo "\t<li><a href=\"photo.php?photo=" . $temp[0] . "#photo\"><img src=\"$item\" alt=\"thumnail\" /></a></li>\n";
}

echo '</ul>

<p>Out of that, I\'ve noticed patterns and made Albums:</p>

<ul>
	<li><a href="album.php?album=manequenne#photo">Mannequenns</a></li>
	<li><a href="album.php?album=abstract#photo">Abstract</a></li>
	<li><a href="album.php?album=concert#photo">Live concerts</a></li>
	<li><a href="album.php?album=nature#photo">Nature</a></li>
	<li><a href="album.php?album=night#photo">Night photography</a></li>
	<li><a href="album.php?album=people#photo">People</a></li>
</ul>

</div>';

echo $static['visitor_footer'];

echo $static['end'];

?>
