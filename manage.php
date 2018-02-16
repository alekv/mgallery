<?php

// bug: generates thumb-a for all files, instead only for those that have 'album-'

require_once("config.php");
require_once("static.php");
require_once("functions.php");

set_time_limit(0);

// ---

echo $static['begin'];

echo $static['manage_heading'];

make_manage_lists();	

// give us info about the number of photos and thumbs
if (isset($lists['photos']))
{
	if ($lists['total']['photos'] > 1)
		echo "<p>There are " . $lists['total']['photos'] . " photos.</p>";
	else
		echo "<p>There is one photo.</p>";

	if (isset($lists['total']['missing_thumbs_stream']))
	{
		if ($lists['total']['missing_thumbs_stream'] > 1)
			echo "<p>There are " . $lists['total']['missing_thumbs_stream'] . " missing Stream thumbs.</p>";
		elseif ($lists['total']['missing_thumbs_stream'] > 0)
			echo "<p>There is 1 missing Stream thumb.</p>";
	}
	else
	{
		echo "<p>There are <em>no</em> missing Stream thumbs.</p>";
	}

	if (isset($lists['total']['missing_thumbs_album']))
	{
		if ($lists['total']['missing_thumbs_album'] > 1)
			echo "<p>There are " . $lists['total']['missing_thumbs_album']. " missing Album thumbs.</p>";
		elseif ($lists['total']['missing_thumbs_album'] > 0)
			echo "<p>There is 1 missing Album thumb.</p>";
	}
	else
	{
		echo "<p>There are <em>no</em> missing Album thumbs.</p>";
	}
}
else
{
	echo "<p>There are <em>no</em> photos.</p>";
}

// gives us info about the thumbs suffix
if ($thumbs['stream_suffix'] == $thumbs['album_suffix'])
{
	echo "<p>Stream thumbs have the same suffix as Album thumbs, <code>" . $thumbs['stream_suffix'] . "</code>.</p>";
}
else
{
	echo "<p>Stream thumbs have the suffix <code>" . $thumbs['stream_suffix'] . "</code>.</p>";
	echo "<p>Album thumbs have the suffix <code>" . $thumbs['album_suffix'] . "</code>.</p>";
}

// if the user submited the form
if ($_POST)
{
	// check if passphrase is correct
	if ($_POST['passphrase'] !== $passphrase_axiom)
	{
		echo "<p class=\"error\">Incorrect passphrase.</p>";
		echo $static['manage_form'];
		echo $static['manage_footer'];
		echo $static['end'];
		exit();
	}

	// recieve input and execute operations requested
	// todo: check if the arrays that I take as input actually exist

	if (!empty($_POST['rename']))
		if ($_POST['rename'] === "yes")
		{
			make_manage_lists();
			rename_photos($lists['photos']);
		}

	if (!empty($_POST['strip_photos']))
		if ($_POST['strip_photos'] === "yes")
		{
			make_manage_lists();
			strip_exif($lists['photos']);
		}

	if (!empty($_POST['strip_stream_thumbs']))
		if ($_POST['strip_stream_thumbs'] === "yes")
		{
			make_manage_lists();
			strip_exif($lists['thumbs']['stream']);
		}

	if (!empty($_POST['strip_album_thumbs']))
		if ($_POST['strip_album_thumbs'] === "yes")
		{
			make_manage_lists();
			strip_exif($lists['thumbs']['album']);
		}

	if (!empty($_POST['generate_missing_stream_thumbs']))
		if ($_POST['generate_missing_stream_thumbs'] === "yes" && isset($lists['missing_thumbs']['stream']))
		{
			make_manage_lists();
			gen_thumbs($lists['missing_thumbs']['stream'], "stream");
		}

	if (!empty($_POST['generate_missing_album_thumbs']))
		if ($_POST['generate_missing_album_thumbs'] === "yes" && isset($lists['missing_thumbs']['album']))
		{
			make_manage_lists();
			gen_thumbs($lists['missing_thumbs']['album'], "album");
		}

	if (!empty($_POST['generate_all_stream_thumbs']))
		if ($_POST['generate_all_stream_thumbs'] === "yes")
		{
			make_manage_lists();
			gen_thumbs($lists['photos'], "stream");
		}

	if (!empty($_POST['generate_all_album_thumbs']))
		if ($_POST['generate_all_album_thumbs'] === "yes")
		{
			make_manage_lists();
			gen_thumbs($lists['photos'], "album");
		}

	if (!empty($_POST['delete_stream_thumbs']))
		if ($_POST['delete_stream_thumbs'] === "yes")
		{
			make_manage_lists();
			delete_list($lists['thumbs']['stream']);
		}

	if (!empty($_POST['delete_album_thumbs']))
		if ($_POST['delete_album_thumbs'] === "yes")
		{
			make_manage_lists();
			delete_list($lists['thumbs']['album']);
		}

	if (!empty($_POST['delete_processed']))
		if ($_POST['delete_processed'] === "yes")
		{
			make_manage_lists();
			delete_list($lists['processed']);
		}
}

echo $static['manage_form'];
echo $static['manage_footer'];
echo $static['end'];

?>