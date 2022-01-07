<?php  
error_reporting(0);

include_once("includes.php");

/*************************************************************** 
 * Application Name: DM Albums									
 * Application URI: http://www.dutchmonkey.com/wp-plugins/
 * Description: In-Line Version of DM PhotoAlbums.
 * Author: Frank D. Strack
 * Author Email: development@dutchmonkey.com
 * Author URI: http://www.dutchmokney.com
 ***************************************************************/
 
ini_set("memory_limit", -1);

if(!$TEMPLATE_DIR)	$TEMPLATE_DIR = "../template/";
if(!$INCLUDE_DIR)	$INCLUDE_DIR = "php";

$homedir = $HOME_DIR;

include("$INCLUDE_DIR/images.php");

//****************************************************************************
// CHECK THAT DIR EXISTS AND HAS IMAGES.  IF NOT, RETURN MESSAGE SCREEN

if(file_exists($HOME_DIR . $LOCAL_DIR) === FALSE || getslidecount() == 0)
{
	//die(throwerror(file_exists($HOME_DIR . $LOCAL_DIR), getslidecount()));
}

function throwerror($file_exists, $slide_count)
{
	global $HOME_DIR, $LOCAL_DIR, $DM_PHOTOALBUM_MERGESUBDIRECTORIES;
	
	if(!$file_exists)
	{
		$ROOT_CAUSE = "Directory does not exist or can not be accessed.";
	}
	
	else if($slide_count == 0)
	{
		$ROOT_CAUSE = "Directory does not contain photos.";
	}
	
	?>
	<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>DM Albums&#153: An Error Ocurred While Loading Photo Album.</title>
	<link rel="stylesheet" type="text/css" href="ui/styles.css">
	</head>
	<body>
	<table cellpadding="0" cellspacing="0" border="0" width="100%" height="100%">
	<tr height="100%">
	<td align="center" valign="middle">
	
	<table id="tbl_error" cellpadding="5" cellspacing="0" border="0">
	
	<tr>
	<td><img id="warning" src="ui/warning.png" border="0" title="Warning"></td>
	<td><span class="error_header">An error occurred while loading this photo album.</span></td>
	</tr>
	<tr>
	<td></td>
	<td><span class="error_message_detail"><b>Error:</b> <span class="error_root_cause"><?php  print($ROOT_CAUSE); ?></span></span></td>
	</tr>
	<tr>
	<td></td>
	<td>
	<span class="error_message">
	DM Albums&#153 was unable to open the photo album. This usually means one of three things happened:
	<ul>
	<li>The directory that DM Albums&#153 was pointed at does not exist or can not be accessed.</li>
	<li>The directory that DM Albums&#153 was pointed at does not contain any photos.</li>
	<li>The Home Directory setting is not set correctly.</li>
	</ul>
	</span></td>
	</tr>
	<tr>
	<td></td>
	<td>
	<table cellpadding="5" cellspacing="0" border="0">
	<tr>
	<td><span class="error_details">Details:</span></td>
	<td></td>
	</tr>
	<tr>
	<td><span class="error_details">Home Directory:</span></td>
	<td><span class="error_details"><?php  print($HOME_DIR); ?></span></td>
	</tr>
	<tr>
	<td><span class="error_details">Album Directory:</span></td>
	<td><span class="error_details"><?php  print($LOCAL_DIR); ?></span></td>
	</tr>
	<tr>
	<td><span class="error_details">Merge SubDirectories:</span></td>
	<td><span class="error_details"><?php  print($DM_PHOTOALBUM_MERGESUBDIRECTORIES); ?></span></td>
	</tr>
	</table>
	</td>
	</tr>
	</tr>
	</table>
	
	</td>
	</tr>
	</table>
	</body>
	</html>
	<?php  }

//****************************************************************************
// CONSTANTS AND GLOBALS

$LOCAL_DIR = str_replace($HOME_DIR, "", $LOCAL_DIR);

$DISPLAY_INDEX = 1;
$DISPLAY_FILENAME = 2;

if(!$slide) $slide = 1;

if($byfile == "yes" && $file)
{
	$slide = getslideindex($file);
}

$COUNT_CURR = $slide;
$COUNT_PREV = $slide - 1;
$COUNT_NEXT = $slide + 1;
$COUNT = getslidecount();

$DISPLAY_SLIDE = $SLIDE_DIR . getslide($slide);
$DISPLAY_CAPTION = getcaption(getslide($slide));

$CURR_SLIDE = $PHP_SELF . "?slide=$COUNT_CURR";
$FIRST_SLIDE = $PHP_SELF . "?slide=1";
$PREV_SLIDE = $PHP_SELF . "?slide=$COUNT_PREV";
$NEXT_SLIDE = $PHP_SELF . "?slide=$COUNT_NEXT";
$LAST_SLIDE = $PHP_SELF . "?slide=$COUNT";

$JUMPTO_LIST = getjumptolist($COUNT_CURR, $DISPLAY_INDEX);
$JUMPTO_NAME = getjumptolist($COUNT_CURR, $DISPLAY_FILENAME);

$homedir = $HOME_DIR;

$DIRECTORY_IMAGE_FILES = array();

if(!$SCALING)				$SCALING = "BEST";
if(!$THUMBNAIL)				$THUMBNAIL = $COUNT_CURR;
if($THUMBNAIL < 1)			$THUMBNAIL = 1;
if($THUMBNAIL > $COUNT)		$THUMBNAIL = $COUNT;

$OVERSIZED_IMAGE = false;	//!imageisvalid("$homedir$currdir$DISPLAY_SLIDE");

$oversized = "";

if(!$degrees)		$degrees = 0;
if($degrees >= 360)	$degrees -= 360;

$rotated = "";

if($degrees != 0)	$rotated = "<br>[ image rotated $degrees degrees ]<br>";

//****************************************************************************
// FUNCTIONS

function getjumptolist($curr, $display_type)
{
	global $USERINTERFACE, $DISPLAY_INDEX, $DISPLAY_FILENAME, $PHP_SELF, $currdir, $HOME_DIR;
	
	//$htmllist = "<select class=\"jumptolist\" id=\"jumptolist\" onChange=\"document.location.href='$PHP_SELF?slide=' + Number(this.selectedIndex + 1) + '&currdir=$currdir';\">\n";
	
	$htmllist = "<select class=\"jumptolist\" id=\"jumptolist\" onChange=\"OpenImage('$HOME_DIR$currdir' + this.options[this.selectedIndex].text, Number(this.selectedIndex + 1), Number(this.length));\">\n";
	
	$contents = getfiles();
	
	$i = 0;
	
	while($i < count($contents)) 
	{	
		$selected = "";
		
		$display_name =  $i + 1;
		
		if($display_name == $curr)		$selected = " selected class=\"selected\"";
		
		if($display_type == $DISPLAY_FILENAME)	$display_name = $contents[$i];							
		
		$htmllist = $htmllist . "<option value=\"$i\" $selected>$display_name</option>\n";
		
		$i++;
	}
	
	$htmllist = $htmllist . "</select>\n";
	
	return $htmllist;
}

function getslide($currslide)
{
	$contents = getfiles();
	
	return $contents[$currslide - 1];
}

function getslidecount()
{
	return count(getfiles());
}

function getcaption($currslide)
{
	global $USERINTERFACE, $HOME_DIR;
	global $LOCAL_DIR, $CAPTION_DIR;
	
	$dirname = $HOME_DIR . $LOCAL_DIR . $CAPTION_DIR;
	
	$parts = split("\.", $currslide);
	
	$rootname = $parts[0];
	
	$extentions = array(".cap", ".caption", ".txt");
	
	foreach($extentions as $ext)
	{
		if(file_exists($dirname . $rootname . $ext))
		{		
			$caption = trim(file_get_contents($dirname . $rootname . $ext));
			
			if(strlen($caption) > 0)	return "$caption";
			else 						return $caption;
		}
	}
	
	$captionfiles = array("browse.cap", "slideshow.cap", "images.cap", "pictures.cap");
	
	foreach($captionfiles as $captionfile)
	{
		if(file_exists($dirname . $captionfile))
		{
			$lines = file($dirname . $captionfile);
	
			foreach($lines as $line) 
			{
			   //line starts with the image name, remove image name and leading whitespace, display caption
			   
			   $matches = array();
			   
			   $matchcount = 0;
			   
			   $matchcount = preg_match_all("/(^$currslide:\s)(.*)/i", $line, $matches);
			   
			   if($matchcount > 0)
			   {
					$filename = $matches[0][1];
				   	$caption = trim($matches[2][0]);
				   
				   	if(strlen($caption) > 0)	return "$caption";
					else 						return $caption;
			   }
			}
		}
	}
}

function gettitle()
{
	global $USERINTERFACE, $HOME_DIR;
	global $LOCAL_DIR, $CAPTION_DIR;
	
	$dirname = $HOME_DIR . $LOCAL_DIR . $CAPTION_DIR;
	
	$parts = split("\.", $currslide);
	
	$rootname = $parts[0];
	
	$extentions = array(".cap", ".caption", ".txt");
	
	foreach($extentions as $ext)
	{
		if(file_exists($dirname . $rootname . $ext))
		{		
			$caption = trim(file_get_contents($dirname . $rootname . $ext));
			
			if(strlen($caption) > 0)	return "$caption";
			else 						return $caption;
		}
	}
	
	$captionfiles = array("browse.cap", "slideshow.cap", "images.cap", "pictures.cap");
	
	foreach($captionfiles as $captionfile)
	{
		if(file_exists($dirname . $captionfile))
		{
			$lines = file($dirname . $captionfile);
	
			foreach($lines as $line) 
			{
			   //albumtitle name is albumtitle:
			   
			   $matches = array();
			   
			   $matchcount = 0;
			   
			   $matchcount = preg_match_all("/(^DM_ALBUM_TITLE:\s)(.*)/i", $line, $matches);
			   
			   if($matchcount > 0)
			   {
					$filename = $matches[0][1];
				   	$caption = trim($matches[2][0]);
				   
				   	if(strlen($caption) > 0)	return "$caption";
					else 						return $caption;
			   }
			}
		}
	}
	
	return basename($LOCAL_DIR);
}

function getfiles()
{
	global $USERINTERFACE, $HOME_DIR;
	global $USERINTERFACE, $LOCAL_DIR;
	global $USERINTERFACE, $SLIDE_DIR, $DISPLAY_ORDER;
	global $DIRECTORY_IMAGE_FILES;
	global $DM_PHOTOALBUM_MERGESUBDIRECTORIES;
	global $DM_ALBUMS_IMAGETYPE_PNG;
	global $DM_ALBUMS_IMAGETYPE_GIF;			 
	global $DM_ALBUMS_IMAGETYPE_JPG;

	if(count($DIRECTORY_IMAGE_FILES) > 0)	return $DIRECTORY_IMAGE_FILES;
	
	//$images = array(IMAGETYPE_PNG, IMAGETYPE_GIF, IMAGETYPE_JPEG);
	//$images = array($DM_ALBUMS_IMAGETYPE_PNG, $DM_ALBUMS_IMAGETYPE_GIF, $DM_ALBUMS_IMAGETYPE_JPG);
		
	$dirname = $HOME_DIR . $LOCAL_DIR . $SLIDE_DIR;
	
	dm_refresh_photo_sortorder($dirname);
		
	$album_sortorder = dm_get_sortorder($dirname);
	
	if(strlen(trim($album_sortorder)) > 0)
	{
		return explode(";", $album_sortorder);
	}
	
	$subdirs = array($dirname);
	
	if($DM_PHOTOALBUM_MERGESUBDIRECTORIES == "TRUE" || $DM_PHOTOALBUM_MERGESUBDIRECTORIES == 1)	$contents = array_merge($subdirs, getdirectories($dirname)); //$subdirs = getdirectories($dirname);
	
	$contents = array();
	
	$next = 0;
	$i = 0;
	
	foreach($subdirs as $dirname)
	{
		$dir = opendir($dirname);
		
		$subdircontents = array();
		
		while(false !== ($file = readdir($dir))) 
		{	
			//echo $dirname . $file . "<br>";
		
			$type = filetype($dirname . $file);
			
			if($type != "dir") 
			{		
				//$exif = dm_get_imagetype($dirname . $file); //exif_imagetype($dirname . $file);
			
				// Is the file an image?
				if(dm_get_imagetype($dirname . $file) >= 0)
				{		
					$subdircontents[$i] = str_replace($HOME_DIR . $LOCAL_DIR . $SLIDE_DIR, "", $dirname . $file);
					
					$i++;
				}
			}	
		}
		
		natcasesort($subdircontents);
		$subdircontents = array_values($subdircontents);
		
		if($DISPLAY_ORDER == "REVERSE")	$subdircontents = array_reverse($subdircontents);
		
		closedir($dir);	
		
		$contents = array_merge($contents, $subdircontents);		
	}
	
	$DIRECTORY_IMAGE_FILES = $contents;

	return $DIRECTORY_IMAGE_FILES;
}

function getdirectories($dirname)
{
	global $PHP_SELF;
	global $domain, $HOME_DIR, $disallowed_dirs, $allowed_dirs;
	
	$dir = opendir($dirname);

	$contents = array();

	$contents[0] = $dirname;
	
	$i = 1;
	
	// Read files into array
	while(false !== ($file = readdir($dir)))
	{
		$type = filetype($dirname . $file);
		
		if($type == "dir" && ($file != '.' && $file != '..' && $file != DM_CACHE_DIRECTORY))
		{
			$contents[$i] = $dirname . $file . "/";
			
			if(is_dir($contents[$i])) 
			{		
				$tmp = getdirectories($contents[$i]);
				
				$contents = array_merge($contents, $tmp);
			}
			
			$i++;
		}
	}

	closedir($dir);
	
	natcasesort($contents);
	$contents = array_values($contents);

	return $contents;
}

function getslideindex($file)
{
	$contents = getfiles();

	$i = 0;
	
	while($i < count($contents)) 
	{	
		if($file == $contents[$i])
		{
			return $i + 1;
		}
		
		$i++;
	}
}

function getimageattributes($image)
{
	global $USERINTERFACE, $homedir, $currdir, $OVERSIZED_IMAGE, $DISPLAY_SLIDE, $CURR_SLIDE, $FIRST_SLIDE, $LAST_SLIDE, $PREV_SLIDE, $NEXT_SLIDE, $COUNT, $COUNT_CURR, $SCALING, $degrees, $grayscale;
	global $USERINTERFACE, $SCREEN_WIDTH, $SCREEN_HEIGHT;

	// GET IMAGE SIZE (ACCORDING TO FINAL ORIENTATION
	if($degrees == 90 || $degrees == 270)
	{
		$rotate = "yes";

		list($height, $width, $type, $attr) = getimagesize("$image");
	}	

	else
	{
		$rotate = "no";

		list($width, $height, $type, $attr) = getimagesize("$image");
	}

	// GET IMAGE ASPECT RATIO (FOR SCALING)
	$ratio = $width / $height;

	// GET MAXIMUM SIZE FOR SCREEN AND FIT (LARGE OR BEST)
	$max_width = $SCREEN_WIDTH - 100;
	$max_height = $SCREEN_HEIGHT - 510;

	if($SCALING == "LARGE")
	{
		$max_width = $SCREEN_WIDTH - 200;
		$max_height = round($SCREEN_WIDTH / $ratio);
	}

	// SCALE IMAGE IF LARGER THAN MAX SIZE
	if($width > $max_width)
	{
		$width = round($max_width);
		$height = round($width / $ratio);

		$scale = "yes";
	}

	if($height > $max_height)
	{
		$height = round($max_height);
		$width = round($height * $ratio);

		$scale = "yes";
	}
	
	return array($width, $height, $scale, $rotate, $type, $attr, $max_width, $max_height);
}

function getfilelist($dirname)
{
	global $PHP_SELF;
	global $domain, $homedir, $currdir, $trashdir;

	$dirname = $homedir . $dirname;

	$dir = opendir($dirname);

	$contents = array();

	$i = 0;

	while(false !== ($file = readdir($dir)))
	{
		$type = filetype($dirname . $file);

		if($type != "dir" && $type != "link" && $file != ".bash_history")
		{
			$contents[$i] = $file;

			$i++;
		}
	}

	closedir($dir);

	//sort($contents);
	
	natcasesort($contents);
	$contents = array_values($contents);
		
	return $contents;
}

if(!$SKIP_TEMPLATE == "yes")	require(dirname(__FILE__) . "/" . $TEMPLATE_DIR . $TEMPLATE); 