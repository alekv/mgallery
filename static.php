<?php

/////////////////////////////
//
// begin
//
/////////////////////////////

$static['begin']='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>migrus</title>
	<link rel="stylesheet" type="text/css" media="all" href="screen.css" />
	<link rel="stylesheet" type="text/css" media="all" href="debug.css" />
	<script type="text/javascript" src="keys.js"></script>
</head>

<body>';

/////////////////////////////
//
// end
//
/////////////////////////////

$static['end']='

</body>

</html>';


/////////////////////////////
//
// static heading
//
/////////////////////////////

$static['visitor_heading'] = '

<h1><a href="main.php">photography exhibition - Alek Von Newman</a></h1>
';


/////////////////////////////
//
// footer
//
/////////////////////////////

$static['visitor_footer']='

<div id="footer">
	<p>All work is licensed under <a href="http://creativecommons.org/licenses/by-nc-nd/4.0/">BY-NC-ND-4.0</a>.</p>
	<p>Use the <a href="manage.php">manage tool</a> to process the already uploaded pictures.</p>
</div>';

//////////////////////////////
//
// manage footer
//
//////////////////////////////

$static['manage_footer']='
<div id="footer">
	<p>When you\'re finished, head back to the <a href="main.php">index</a>.</p>
</div>';

/////////////////////////////
//
// manage heading
//
/////////////////////////////


$static['manage_heading'] = '<h1><a href="manage.php">Manage panel</a></h1>';


/////////////////////////////
//
// manage form
//
/////////////////////////////

$static['manage_form']='

<form id="manage" action="?" method="post">

	<fieldset>
		<legend class="nodisplay">Passphrase</legend>
		
		<label class="" for="passphrase">Passphrase</label>
		<input name="passphrase" size="30" maxlength="200" value="lola" type="text" id="passphrase" />
	</fieldset>
	
	<fieldset class="">
		<legend class="">Naming</legend>
		<ul>
			<li>
				<input name="rename" type="checkbox" value="yes" id="rename" />
				<label for="rename">rename photos based on IPTC date and time <em>(this will not touch already named ones)</em></label>
			</li>
		</ul>
	</fieldset>

	<fieldset class="">
		<legend class="">Metadata</legend>
		<ul>
			<li>
				<input name="strip_photos" type="checkbox" value="yes" id="strip_photos" />
				<label for="strip_photos">strip useless metadata from photos</label>
			</li>
			<li>
				<input name="strip_stream_thumbs" type="checkbox" value="yes" id="strip_stream_thumbs" />
				<label for="strip_stream_thumbs">strip useless metadata from Stream thumbs</label>
			</li>
			<li>
				<input name="strip_album_thumbs" type="checkbox" value="yes" id="strip_album_thumbs" />
				<label for="strip_album_thumbs">strip useless metadata from Album thumbs</label>
			</li>
		</ul>
	</fieldset>
	
	<fieldset class="">
		<legend class="">Generate thumbnails</legend>
		
		<p class="small"><em>All operations will overwrite existing files.</em></p>
		
		<ul>
			<li>
				<input name="generate_missing_stream_thumbs" type="checkbox" value="yes" id="generate_missing_stream_thumbs" />
				<label for="generate_missing_stream_thumbs">generate missing Stream thumbnails</label>
			</li>
			<li>
				<input name="generate_missing_album_thumbs" type="checkbox" checked="checked" value="yes" id="generate_missing_album_thumbs" />
				<label for="generate_missing_album_thumbs">generate missing Album thumbnails</label>
			</li>
			<li>
				<input name="generate_all_stream_thumbs" type="checkbox" value="yes" id="generate_all_stream_thumbs" />
				<label for="generate_all_stream_thumbs">generate all Stream thumbnails</label>
			</li>
			<li>
				<input name="generate_all_album_thumbs" type="checkbox" value="yes" id="generate_all_album_thumbs" />
				<label for="generate_all_album_thumbs">generate all Album thumbnails</label>
			</li>
		</ul>
	</fieldset>
	
	<fieldset class="">
		<legend class="">Delete thumbnails</legend>
		<ul>
			<li>
				<input name="delete_stream_thumbs" type="checkbox" value="yes" id="delete_stream_thumbs" />
				<label for="delete_stream_thumbs">delete Stream thumbnails</label>
			</li>
			<li>
				<input name="delete_album_thumbs" type="checkbox" value="yes" id="delete_album_thumbs" />
				<label for="delete_album_thumbs">delete Album thumbnails</label>
			</li>
		</ul>
	</fieldset>

	<fieldset class="">
		<legend class="">Misc</legend>
		<ul>
			<li>
				<input name="delete_processed" type="checkbox" value="yes" id="delete_processed" />
				<label for="delete_processed">delete (unfinished) processed files</label>			
			</li>
		</ul>
	</fieldset>
	
	<fieldset class="">
		<input name="submit" value="perform" title="" type="submit" />
	</fieldset>

</form>

';

	
	
?>

