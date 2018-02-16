<?php

// recieve and store input from GET
function recieve_input()
{
	global $page,$photo,$albums;

	if (!empty($_GET['page']))
	{
		$page['current'] = $_GET['page'];
		
		// sanitization: allow only numbers
		$page['current'] = preg_replace("/[^0-9]/i", "", $page['current']);
		
		// there is no page 0, we start from page 1
		if ($page['current'] < 1) $page['current'] = 1;
	}
	else
	{
		$page['current'] = 1;
	}

	if (!empty($_GET['photo']))
	{
		$photo['id'] = $_GET['photo'];
		
		// allow only numbers, letters, the underscore, and the dash
		$photo['id'] = preg_replace('/[^A-Za-z0-9-_]/', '', $photo['id']);
	}

	if (!empty($_GET['album']))
	{
		$albums['current'] = $_GET['album'];
		
		// allow only numbers, letters, the underscore, and the dash
		$albums['current'] = preg_replace('/[^A-Za-z0-9-_]/', '', $albums['current']);
	}
}

// prints the state of a variable - used while debugging the program
function debug_show($var,$var_name="unknown name")
{
	echo "\n\n<p class=\"debug\">";
	
	if (isset($var))
	{
		echo "<span>$var_name</span> = ";
		print_r($var);
	}
	else
	{
		echo "Variable \$$var_name is not set.";
	}
	
	echo"</p>\n\n";
}

// prints a debug message
function debug_msg($msg)
{
	echo "<p class=\"debug\">$msg</p>\n\n";
}

// will return an array with either photos, stream thumbs,
// album thumbs, or processed - the last three are used
// only in manage.php
function get_list($choice="photos", $order="newer")
{
	global $photo_dir,$extensions,$thumbs,$processed;

	$list = scandir($photo_dir);
	$list = array_slice($list, 2);
	
	switch ($choice)
	{
		case "photos":
			$list = preg_grep("/{$extensions}/i",$list);
			$list = preg_grep("/{$thumbs['stream_suffix']}/i",$list,PREG_GREP_INVERT);
			$list = preg_grep("/{$thumbs['album_suffix']}/i",$list,PREG_GREP_INVERT);
			$list = preg_grep("/{$processed}/i",$list,PREG_GREP_INVERT);
			break;
		case 'stream_thumbs':
			$list = preg_grep("/{$extensions}/i",$list);
			$list = preg_grep("/{$thumbs['stream_suffix']}/i",$list);
			$list = preg_grep("/{$processed}/i",$list,PREG_GREP_INVERT);
			break;
		case 'album_thumbs':
			$list = preg_grep("/{$extensions}/i",$list);
			$list = preg_grep("/{$thumbs['album_suffix']}/i",$list);
			$list = preg_grep("/{$processed}/i",$list,PREG_GREP_INVERT);
			break;
		case 'processed':
			$list = preg_grep("/{$processed}/i",$list);
			break;
	}

	if ($order == "newer")
		$list = array_reverse($list);

	return $list;
}

// calculate paging
// pay attention to the second argument
function	calc_paging($list,$phppage)
{
	global $extensions,$thumbs,$page,$photo;
	
	if ($phppage == "album")
		$temp_thumbs_per_page = $thumbs['album_per_page'];
	elseif ($phppage == "stream")
		$temp_thumbs_per_page = $thumbs['stream_per_page'];

	$temp_n_photos = count($list);
	$temp_n_pages_full = (int) ($temp_n_photos / $temp_thumbs_per_page);
	$temp_n_pages_le = $temp_n_photos % $temp_thumbs_per_page;

	if ($temp_n_pages_le == 0)
		$page['total'] = $temp_n_pages_full;
	else
		$page['total'] = $temp_n_pages_full + 1;

	// we don't have that many pages, but here's the last one
	if ($page['current'] > $page['total']) $page['current'] = $page['total'];

	$temp_first_photo = $page['current'] * $temp_thumbs_per_page - $temp_thumbs_per_page;
	$temp_last_photo = $page['current'] * $temp_thumbs_per_page - 1;

	if ($temp_last_photo > $temp_n_photos) $temp_last_photo = $temp_n_photos;

	$page['current_list'] =
		array_slice($list, $temp_first_photo, $temp_last_photo-$temp_first_photo+1);

	// when in an album (that exists) with a photo that doesn't exist,
	// $photo['file'] is empty and some errors pop up. This shouldn't happen.
	$temp_photo_location = array_search($photo['file'], $list);
	
	$photo['belong'] = ceil(($temp_photo_location+1)/$temp_thumbs_per_page);	

	if (array_key_exists($temp_photo_location-1, $list))
		$photo['prev'] = preg_replace("/".$extensions."/", "", $list[$temp_photo_location-1]);

	if (array_key_exists($temp_photo_location+1, $list))
		$photo['next'] = preg_replace("/".$extensions."/", "", $list[$temp_photo_location+1]);
		
	$photo['prev_belong'] = ceil(($temp_photo_location)/$temp_thumbs_per_page);
	$photo['next_belong'] = ceil(($temp_photo_location+2)/$temp_thumbs_per_page);

	if (($page['current'] + 1) <= $page['total']) $page['next'] = $page['current'] + 1;
	if (($page['current'] - 1) >= 1) $page['prev'] = $page['current'] -1;
}

// seperates a name from the extension and returns them in a two-element array
function name_sep($file)
{
	global $extensions;
	
	$name = preg_replace("/".$extensions."/", "", $file);
	$ext = preg_replace("/".$name."/", "", $file);

	return array($name, $ext);
}

// prints the paging, Flickr style
function print_paging($phppage)
{
	global $page,$albums,$photo;
	
	if ($page['total'] < 2)
		return NULL;
	
	$page['tolerance'] = $page['tolerance'] - 1;

	// just to be sure
	if ($page['current'] > $page['total'])
		$page['current'] = $page['total'];
		
	// when calling from stream.php these will be empty, so
	// lets prevent the interpreter from spitting warnings
	if (!isset($albums['current'])) $albums['current'] = "";
	if (!isset($photo['id'])) $photo['id'] = "";
	
	echo "\t<ul id=\"pages\">\n";
	
	// print the 'prev' button
	if (isset($page['prev']))
	{
		if ($phppage == "stream")
			echo "\t\t<li><a id=\"prev\" href=\"stream.php?page=" . $page['prev'] ."\">Prev</a></li>\n";
		elseif ($phppage == "album")
			echo "\t\t<li><a id=\"prev\" href=\"album.php?album=" . $albums['current'] . "&amp;photo=" . $photo['id'] . "&amp;page=" . $page['prev'] ."#thumbs_a\">Prev</a></li>\n";
	}
	else
	{
		echo "\t\t<li>Prev</li>\n";
	}
	
	if ($page['total'] > 1+$page['around']*2+$page['edge']*2) // I'll show paging
	{
		// print what is there before the current page
		if ($page['current']-1-1 <= $page['edge']+$page['around']+$page['tolerance'])
		{
			for ($i=1; $i<=$page['current']-1; $i++)
				if ($phppage == "stream")
					echo "\t\t<li><a href=\"stream.php?page=$i\">$i</a></li>\n";
				elseif ($phppage == "album")
					echo "\t\t<li><a href=\"album.php?album=" . $albums['current'] . "&amp;photo=" . $photo['id'] . "&amp;page=$i#thumbs_a\">$i</a></li>\n";
		}
		else
		{
			for ($i=1; $i<=$page['edge']; $i++)
				if ($phppage == "stream")
					echo "\t\t<li><a href=\"stream.php?page=$i\">$i</a></li>\n";
				elseif ($phppage == "album")
					echo "\t\t<li><a href=\"album.php?album=" . $albums['current'] . "&amp;photo=" . $photo['id'] . "&amp;page=$i#thumbs_a\">$i</a></li>\n";
			
			echo "\t\t<li>...</li>\n";
			
			for ($i=$page['current']-$page['around']; $i<=$page['current']-1; $i++)
				if ($phppage == "stream")
					echo "\t\t<li><a href=\"stream.php?page=$i\">$i</a></li>\n";
				elseif ($phppage == "album")
					echo "\t\t<li><a href=\"album.php?album=" . $albums['current'] . "&amp;photo=" . $photo['id'] . "&amp;page=$i#thumbs_a\">$i</a></li>\n";
		}

		// print the current page
		echo "\t\t<li>" . $page['current'] . "</li>\n";

		// print what is there after the current page
		if ($page['total']-$page['current']+1 <= $page['edge']+$page['around']+$page['tolerance'])
		{
			for ($i=$page['current']+1; $i<=$page['total']; $i++)
				if ($phppage == "stream")
					echo "\t\t<li><a href=\"stream.php?page=$i\">$i</a></li>\n";
				elseif ($phppage == "album")
					echo "\t\t<li><a href=\"album.php?album=" . $albums['current'] . "&amp;photo=" . $photo['id'] . "&amp;page=$i#thumbs_a\">$i</a></li>\n";
		}
		else
		{
			for ($i=$page['current']+1; $i<=$page['current']+$page['around']; $i++)
				if ($phppage == "stream")
					echo "\t\t<li><a href=\"stream.php?page=$i\">$i</a></li>\n";
				elseif ($phppage == "album")
					echo "\t\t<li><a href=\"album.php?album=" . $albums['current'] . "&amp;photo=" . $photo['id'] . "&amp;page=$i#thumbs_a\">$i</a></li>\n";
			
			echo "\t\t<li>...</li>\n";
			
			for ($i=$page['total']-$page['edge']+1; $i<=$page['total']; $i++)
				if ($phppage == "stream")
					echo "\t\t<li><a href=\"stream.php?page=$i\">$i</a></li>\n";
				elseif ($phppage == "album")
					echo "\t\t<li><a href=\"album.php?album=" . $albums['current'] . "&amp;photo=" . $photo['id'] . "&amp;page=$i#thumbs\">$i</a></li>\n";
		}
	}
	else // I'll not show paging
	{
		// todo: to current page den prepei na exei link!
		for ($i=1; $i<=$page['total']; $i++)
			if ($i == $page['current'])
				echo "\t\t<li>$i</a></li>\n";
			else		
				if ($phppage == "stream")
					echo "\t\t<li><a href=\"stream.php?page=$i\">$i</a></li>\n";
				elseif ($phppage == "album")
					echo "\t\t<li><a href=\"album.php?album=" . $albums['current'] . "&amp;photo=" . $photo['id'] . "&amp;page=$i#thumbs\">$i</a></li>\n";
	}
	
	// prin the 'next' button
	if (isset($page['next']))
	{
		if ($phppage == "stream")
			echo "\t\t<li><a id=\"next\" href=\"stream.php?page=" . $page['next'] . "\">Next</a></li>\n";
		elseif ($phppage == "album")
			echo "\t\t<li><a id=\"prev\" href=\"album.php?album=" . $albums['current'] . "&amp;photo=" . $photo['id'] . "&amp;page=" . $page['next'] ."#thumbs_a\">Next</a></li>\n";
	}
	else
	{
		echo "\t\t<li>Next</li>\n";
	}

	echo "\t</ul>";
}

// searches an array for a photo, and if found returns it's position,
// otherwise it returns null
function search_list($photo_id, $list)
{
	global $extensions;

	$counter = 0;
	foreach ($list as $item)
	{
		$name = preg_replace("/".$extensions."/", "", $item);
		$ext = preg_replace("/".$name."/", "", $item);
		
		if ($photo_id == $name)
			return $counter;
		
		$counter = $counter + 1;
	}
	
	return NULL;
}

// give it a photo_id and will locate the full filename in an array
// returns the filename if found, or null if not
function id_name($id,$list)
{
	global $extensions;

	foreach ($list as $item)
	{
		$name = preg_replace("/".$extensions."/", "", $item);
		$ext = preg_replace("/".$name."/", "", $item);
		
		if ($name == $id)
			return $item;
	}
	
	return NULL;
}

// fetches IPTC data from a file
// I'm using this approach on metadata: http://www.photometadata.org/meta-resources-field-guide-to-metadata
// returns results as a two-element array
// if such data is not found, it returns null
// activate $hide_album=FALSE if you want to include the keyword 'album-' in the list
function fetch_iptc($file, $hide_album=TRUE)
{
	global $photo_dir,$untitled,$albums;
		
	$file = $photo_dir . "/" . $file;
	
	// read IPTC data
	
	if (file_exists($file))
		$file = getimagesize($file, $info);	
	else
		return NULL;
		
	if (isset($info['APP13']))
		$iptc = iptcparse($info['APP13']);
	else
		return NULL;

	if(isset($info['APP13']))
		$iptc = iptcparse($info['APP13']);

	// fetch pic title
	
	if(isset($iptc['2#005']))
	{
		$title = $iptc['2#005'];
		$title = $title[0];
	}
	
	if (!isset($title))
		$title = $untitled;
	
	// fetch date
	
	if(isset($iptc['2#055']))
	{
		$date = $iptc['2#055'];
		$date = $date[0];
	}
	
	if (!isset($date))
		$date = "";
	
	// fetch time
	
	if(isset($iptc['2#060']))
	{
		$time = $iptc['2#060'];
		$time = $time[0];
	}
	
	if (!isset($time))
		$time = "";

	// fetch keywords
	
	if ($hide_album === TRUE)
	{
		if (isset($iptc["2#025"]))
		{
			$keywords = $iptc["2#025"];
			
			for ($i=0; $i <= count($keywords)-1; $i++)
			{
				preg_match("/" . $albums['prefix'] . "/", $keywords[$i], $temp_current_keyword);
				
				if (!isset($temp_current_keyword[0]))
					$keywords_clean[] = $keywords[$i];
			}
		}
		else
		{
			$keywords = NULL;
		}
	}
	elseif ($hide_album === FALSE)
	{
		if (isset($iptc["2#025"]))
			$keywords_clean = $iptc["2#025"];
		else
			$keywords_clean = NULL;
	}

	if (!isset($keywords_clean))
		$keywords_clean = '';
		
	// todo: if date not visible on metadata, then don't print any date or say 'unknown date'
	
	return array('title' => $title, 'keywords' => $keywords_clean, 'date' => $date, 'time' => $time);
}

// Returns an array of photos that contain a keyword.
// Activating inclusive will include keywords that have
// part of what you request - used to spot 'album-', from manage.php.
function keyword_seek($keyword, $list, $inclusive=FALSE, $hide_album=TRUE)
{
	global $photo_dir;
	
	// we repeat a lot of code in the $inclusive loop so that execution time will be briefer
	
	if ($inclusive == FALSE)
	{	
		foreach($list as $item)
		{
			if ($hide_album === TRUE)
				$keywords = fetch_iptc($item);
			elseif ($hide_album === FALSE)
				$keywords = fetch_iptc($item, FALSE);
			
			$keywords = $keywords['keywords'];

			if (isset($keywords) && !empty($keywords)) 
			{
				foreach ($keywords as $item2)
				{
					if ($item2 == $keyword)
						$results[] = $item;
					// else: this photo doesn't have this keyword
				}
			}
			// else: this photo doesn't have keywords
		}
	}
	elseif ($inclusive == TRUE)
	{
		foreach($list as $item)
		{
			if ($hide_album === TRUE)
				$keywords = fetch_iptc($item);
			elseif ($hide_album === FALSE)
				$keywords = fetch_iptc($item, FALSE);
						
			$keywords = $keywords['keywords'];

			if (!empty($keywords)) // used to be 'isset'
			{
				foreach ($keywords as $item2)
				{
					if ((strpos($item2,$keyword)) !== FALSE)
						$results[] = $item;
					
					// else: this photo doesn't have this keyword
				}
			}
			// else: this photo doesn't have keywords
		}
	}

	if (isset($results))
		return $results;
	else
		return NULL;
}

// todo: later on, put this function higher in he order
// given a numberic date, this function returns a human-readable date
function human_date($date,$time)
{
	//$date = "20080611";
	//$time = "015619-0700";

	$year = substr($date,0,4);
	$month = substr($date,4,2);
	$day = substr($date,6,2);
	$hour = substr($time,0,2);
	$min = substr($time,2,2);
	$sec = substr($time,4,2);

	$unixtime = mktime($hour, $min, $sec, $month, $day, $year);

	return date("jS \of F, Y", $unixtime);
}

// makes list of photos, thumbs, and missing thumbs for manage.php
function make_manage_lists()
{
	global $local_path,$lists,$photo_dir,$thumbs,$albums;
	
	// reset it
	$lists = "";

	// find the local path in the server - necessary for imagemagick
	$temp = explode("/", $_SERVER['SCRIPT_FILENAME']);
	$temp = array_slice($temp,0,count($temp)-1);
	$local_path = '';
	foreach ($temp as $part) $local_path = $local_path . $part . "/";

	// make a list with all the photos
	$lists['photos'] = get_list("photos");
	
	// make a list with the procecced files
	$lists['processed'] = get_list("processed");
	
	// make a list with all the album thumbs
	$lists['thumbs']['album'] = get_list("album_thumbs");

	// make a list with all the stream thumbs
	$lists['thumbs']['stream'] = get_list("stream_thumbs");

	// make a list of all stream thumbs that are missing
	foreach ($lists['photos'] as $item)
	{
		$temp = name_sep($item);
		$name = $temp[0];
		$ext = $temp[1];
		
		$temp = $photo_dir . "/" . $name . $thumbs['stream_suffix'] . $ext;
		
		if (!file_exists($temp))
			$lists['missing_thumbs']['stream'][] = $item;
	}

	// make a list of all album thumbs that are missing
	// how? first make a list of all photos that have 'album-' in them
	// and then see which ones don't have a thumb
	$lists['temp'] = keyword_seek($albums['prefix'], $lists['photos'], TRUE, FALSE);
	foreach ($lists['temp'] as $item)
	{
		$temp = name_sep($item);
		$name = $temp[0];
		$ext = $temp[1];
		
		$temp = $photo_dir . "/" . $name . $thumbs['album_suffix'] . $ext;
		
		if (!file_exists($temp))
			$lists['missing_thumbs']['album'][] = $item;
	}
	unset($lists['temp']);

	// how many photos do we have
	$lists['total']['photos'] = count($lists['photos']);

	// how many stream thumbs are missing
	if (isset($lists['missing_thumbs']['stream']))
		$lists['total']['missing_thumbs_stream'] = count($lists['missing_thumbs']['stream']);

	// how many album thumbs are missing
	if (isset($lists['missing_thumbs']['album']))
		$lists['total']['missing_thumbs_album'] = count($lists['missing_thumbs']['album']);
}

// takes as input a list of files and removes the useless metadata from them
// used in manage.php
function strip_exif($list)
{
	global $local_path,$photo_dir,$thumbs,$processed;
	
	echo "\n\n<ul id=\"result\">\n";

	foreach($list as $item)
	{
		$input = $local_path . $photo_dir . "/" . $item;
		$output = $input . $processed;
		
		if (file_exists($input))
		{
			if (!copy($input, $output))
			{
				echo "\t<li>copy failed on '$input' - do I have the right permissions?</li>\n";
				die();
				echo "</ul>";
			}

			$i = new Imagick($output);
			$i -> stripImage();
			$i -> writeImage();

			$imagesize = getImageSize($input, $info);
			$content = iptcembed($info['APP13'], $output);
			$fp = fopen($output, "wb");
			fwrite($fp, $content);
			fclose($fp);

			rename($output, $input);

			echo "\t<li>removed EXIF: $input</li>\n";
		}
		else
		{
			echo "\t<li>didn't remove EXIF - can't find: $input</li>\n";
		}
	}
	echo "</ul>";
}

// takes as input a list of photos and produces thumbnails
// used in manage.php
function gen_thumbs($list,$type="stream")
{
	global $local_path,$photo_dir,$thumbs,$processed;
	
	// $type means the type of thumbs and should be
	// either 'stream' or 'album'
	
	echo "\n\n<ul id=\"result\">\n";
	
	foreach($list as $item)
	{
		$input = $local_path . $photo_dir . "/" . $item;
		
		$temp = name_sep($item);
		
		if ($type == "stream")
			$output = $local_path . $photo_dir . "/" . $temp[0] . $thumbs['stream_suffix'] . $temp[1];
		elseif ($type == "album")
			$output = $local_path . $photo_dir . "/" . $temp[0] . $thumbs['album_suffix'] . $temp[1];
		else
			return NULL;
		
		list($width, $height) = getimagesize($input);
		$temp_thumb_image = new Imagick();
		$temp_thumb_image -> readImage($input);
		
		if ($type == "stream")
		{
			if ($width > $height)
				$temp_thumb_image -> resizeImage($thumbs['stream_size'],0,Imagick::FILTER_LANCZOS,1);
			else
				// catches both, $width<$height and $width==$height
				$temp_thumb_image -> resizeImage(0,$thumbs['stream_size'],Imagick::FILTER_LANCZOS,1);

			$temp_thumb_image -> setImageCompression(Imagick::COMPRESSION_JPEG);
			$temp_thumb_image -> setImageCompressionQuality(85);
			$temp_thumb_image -> writeImage($output);
			
			echo "<li>generated thumb: $output</li>\n";
		}
		elseif ($type == "album")
		{
			// crop before we resize
			if ($width > $height)
				$temp_thumb_image->cropImage($height, $height, 0, 0);
			elseif ($height > $width)
				$temp_thumb_image->cropImage($width, $width, 0, 0);
			// the third else-case is that they are equal, where we don't crop

			// it's square, so leaving 0 is okay
			$temp_thumb_image -> resizeImage($thumbs['album_size'],0,Imagick::FILTER_LANCZOS,1);

			$temp_thumb_image -> setImageCompression(Imagick::COMPRESSION_JPEG);
			$temp_thumb_image -> setImageCompressionQuality(85);
			$temp_thumb_image -> writeImage($output);
			
			echo "<li>generated thumb: $output</li>\n";
		}
	}
	
	echo "\n\n</ul>\n";
}

// give it a list and will attempt to delete all the files in it
// used by manage.php
function delete_list($list)
{
	global $photo_dir;
	
	if (!isset($list) || empty($list))
		return NULL;	
	
	echo "\n\n<ul id=\"result\">\n";
	
	foreach($list as $item)
	{
		$item = $photo_dir . "/" . $item;
	
		if (file_exists($item))
		{
			unlink($item);
			echo "\t<li>removed file: $item</li>\n";
		}
	}
	
	echo "</ul>";
}

// takes as input a list of photos and checks if they have a timestamped name
// and if they don't, it renames them
function rename_photos($list)
{
	global $photo_dir;

	echo "\n\n<ul id=\"result\">\n";
	
	foreach($list as $item)
	{
		$iptc = fetch_iptc($item);
		$timestamp = substr($iptc['date'], 0, 6) . "_" . substr($iptc['time'], 0, 6);
			
		$detect = (strpos($item,$timestamp));

		if ($detect !== 0) // meaning, the filename will have to start with this string
		{
			$old_name = name_sep($item);
			$new_name = $timestamp . "_" . preg_replace("/[^0-9]/i", "", $old_name[0]) . $old_name[1];
			
			$source = $photo_dir . "/" . $item;
			$dest = $photo_dir . "/" . $new_name;
			
			rename($source, $dest);

			echo "\t<li>renamed: $item -> $new_name</li>\n";
		}
		else
		{
			echo "\t<li>already properly named: $item</li>\n";
		}
	}
	
	echo "</ul>";
}


?>