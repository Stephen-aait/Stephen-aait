<?php  

error_reporting(0);

$DM_ALBUMS_IMAGETYPE_PNG = 0;
$DM_ALBUMS_IMAGETYPE_GIF = 1;			 
$DM_ALBUMS_IMAGETYPE_JPG = 2;
$DM_ALBUMS_IMAGETYPE_UNKNOWN = -1;

if(!defined(DM_CACHE_DIRECTORY))
{
	if(dm_is_wamp())	define(DM_CACHE_DIRECTORY, "cache");
	else				define(DM_CACHE_DIRECTORY, ".cache");
}

function dm_mkdir($folder)
{
	if(!function_exists("wp_mkdir_p"))
	{
		//mkdir($folder, 0777, true);
		@mkdir($folder);
		$stat = @stat(dirname($folder));
		$dir_perms = $stat['mode'] & 0007777;  // Get the permission bits.
		@chmod($folder, $dir_perms);
	}
	
	else
	{
		wp_mkdir_p($folder);
	}
}

function dm_get_imagetype($filename)
{
	global $DM_ALBUMS_IMAGETYPE_PNG;
	global $DM_ALBUMS_IMAGETYPE_GIF;			 
	global $DM_ALBUMS_IMAGETYPE_JPG;
	global $DM_ALBUMS_IMAGETYPE_UNKNOWN;
	
	if(function_exists("exif_imagetype_XX"))	
	{
		$img_type = exif_imagetype($filename);
		
		if($img_type == IMAGETYPE_PNG) 
			return $DM_ALBUMS_IMAGETYPE_PNG;

		else if($img_type == IMAGETYPE_GIF) 
			return $DM_ALBUMS_IMAGETYPE_GIF;
			
		else if($img_type == IMAGETYPE_JPEG) 
			return $DM_ALBUMS_IMAGETYPE_JPG;
			
		else
			return $DM_ALBUMS_IMAGETYPE_UNKNOWN;
	}
	
	else
	{
		$img_type = strtoupper(pathinfo($filename, PATHINFO_EXTENSION));
		
		if($img_type == "PNG") 
			return $DM_ALBUMS_IMAGETYPE_PNG;

		else if($img_type == "GIF") 
			return $DM_ALBUMS_IMAGETYPE_GIF;
			
		else if($img_type == "JPG" || $img_type == "JPEG") 
			return $DM_ALBUMS_IMAGETYPE_JPG;
		else
			return $DM_ALBUMS_IMAGETYPE_UNKNOWN;
	}
}

function dm_is_image($filename)
{
	global $DM_ALBUMS_IMAGETYPE_UNKNOWN;
	
	if(dm_get_imagetype($filename) == $DM_ALBUMS_IMAGETYPE_UNKNOWN)	return false;
	else															return true;
}

function dm_get_album_list($dirname, $order_by)
{
	$dir = opendir($dirname);

	$contents = array();
	
	if(!file_exists($dirname))	dm_mkdir($dirname);

	$i = 0;
	
	// Read files into array
	while(false !== ($file = readdir($dir)))
	{
		$type = filetype($dirname . $file);
		
		if($type == "dir" && ($file != '.' && $file != '..' && $file != DM_CACHE_DIRECTORY))
		{
			$contents[$i] = array($dirname . $file . "/", filemtime($dirname . $file . "/"));
				
			$i++;
		}
	}
	
	closedir($dir);
		
	if($order_by == "alpha")	usort($contents, 'dm_get_album_alphacmp');
	else						usort($contents, 'dm_get_album_datecmp');
	
	$contents = array_values($contents);

	return $contents;
}

function dm_get_photo_list($photoalbum, $refresh = false)
{
	if(!$refresh)
	{
		dm_refresh_photo_sortorder($photoalbum);
		
		$album_sortorder = dm_get_sortorder($photoalbum);
		
		if(strlen(trim($album_sortorder)) > 0)
		{
			return explode(";", $album_sortorder);
		}
	}
	
	$dir = opendir($photoalbum);

	$contents = array();

	$i = 0;
	
	// Read files into array
	while(false !== ($file = readdir($dir)))
	{
		if(dm_get_imagetype($dir . $file) >= 0)
		{
			$contents[$i] = $file;
				
			$i++;
		}
	}

	closedir($dir);
	
	natcasesort($contents);
	
	$contents = array_values($contents);

	return $contents;
}

function dm_get_caption($photo)
{
	$caption = "";
	
	if(file_exists(dirname($photo) . "/browse.cap"))
	{
		$lines = file(dirname($photo) . "/browse.cap");
		
		foreach($lines as $line) 
		{
			//line starts with the image name, remove image name and leading whitespace, display caption
		   
		   $matches = array();
		   
		   $matchcount = 0;
		   
		   $matchcount = preg_match_all("/(^" . basename($photo) . ":\s)(.*)/i", $line, $matches);
		   
		   if($matchcount > 0)
		   {
				$filename = $matches[0][1];
			   	$caption = trim($matches[2][0]);
			   
			   	if(strlen($caption) > 0)	return trim("$caption");
				else 						return trim($caption);
		   }
		}
	}
	
	return $caption;
}

function dm_put_caption($photo, $displaycaption)
{
	$directory = dirname($photo);
	$picturename = basename($photo);
		
	$captionfilename = $directory . "/browse.cap";

	if(!file_exists($captionfilename))		
	{
		fopen($captionfilename, "x+");
	}
	
	$lines = file($captionfilename);
	
	if(count($lines) == 0)	$lines = array();
	
	$foundcaption = 0;
	
	$linecount = 0;
	
	while($linecount < count($lines)) 
	{
		$line = $lines[$linecount];
		
		//line starts with the image name, remove image name and leading whitespace, display caption
		
		$matches = array();
			
		$matchcount = 0;
			
		$matchcount = preg_match_all("/(^$picturename:\s)(.*)/i", $line, $matches);
			
		if($matchcount > 0)
		{
			$foundcaption = 1;
			$filename = $matches[0][1];
			$caption = $displaycaption;
			
			$lines[$linecount] = "$picturename:\t$caption\n";
		}
			
		$linecount++;
	}
	
	if($foundcaption === 0)
	{
		$lines[$linecount] = $picturename . ":\t$displaycaption\n";
	}
	
	$linecount = 0;
	$captionfilecontents = "";
	
	while($linecount < count($lines)) 
	{
		if(preg_match("/^\s$/", $lines[$linecount])	!= 1)	$captionfilecontents = $captionfilecontents . $lines[$linecount];
		
		$linecount++;
	}
	
	$fh = fopen($captionfilename, "w+");

	$captionfilecontents = html_entity_decode($captionfilecontents);
	
	$captionfilecontents = stripslashes($captionfilecontents);
	
	fwrite($fh, $captionfilecontents);
}

function dm_get_link($photo)
{
	$caption = "";
	
	if(file_exists(dirname($photo) . "/browse.cap"))
	{
		$lines = file(dirname($photo) . "/browse.cap");
		
		foreach($lines as $line) 
		{
			//line starts with the image name, remove image name and leading whitespace, display caption
		   
		   $matches = array();
		   
		   $matchcount = 0;
		   
		   $matchcount = preg_match_all("/(^" . basename($photo) . "_LINK:\s)(.*)/i", $line, $matches);
		   
		   if($matchcount > 0)
		   {
				$filename = $matches[0][1];
			   	$caption = trim($matches[2][0]);
			   
			   	if(strlen($caption) > 0)	return trim("$caption");
				else 						return trim($caption);
		   }
		}
	}
	
	return $caption;
}

function dm_put_link($photo, $displaycaption)
{
	$directory = dirname($photo);
	$picturename = basename($photo);
		
	$captionfilename = $directory . "/browse.cap";

	if(!file_exists($captionfilename))		
	{
		fopen($captionfilename, "x+");
	}
	
	$lines = file($captionfilename);
	
	if(count($lines) == 0)	$lines = array();
	
	$foundcaption = 0;
	
	$linecount = 0;
	
	while($linecount < count($lines)) 
	{
		$line = $lines[$linecount];
		
		//line starts with the image name, remove image name and leading whitespace, display caption
		
		$matches = array();
			
		$matchcount = 0;
			
		$matchcount = preg_match_all("/(^" . $picturename . "_LINK:\s)(.*)/i", $line, $matches);
			
		if($matchcount > 0)
		{
			$foundcaption = 1;
			$filename = $matches[0][1];
			$caption = $displaycaption;
			
			$lines[$linecount] = $picturename . "_LINK:\t$caption\n";
		}
			
		$linecount++;
	}
	
	if($foundcaption === 0)
	{
		$lines[$linecount] = $picturename . "_LINK:\t$displaycaption\n";
	}
	
	$linecount = 0;
	$captionfilecontents = "";
	
	while($linecount < count($lines)) 
	{
		if(preg_match("/^\s$/", $lines[$linecount])	!= 1)	$captionfilecontents = $captionfilecontents . $lines[$linecount];
		
		$linecount++;
	}
	
	$fh = fopen($captionfilename, "w+");

	$captionfilecontents = html_entity_decode($captionfilecontents);
	
	$captionfilecontents = stripslashes($captionfilecontents);
	
	fwrite($fh, $captionfilecontents);
}

function dm_get_title($photoalbum)
{
	$ablum_title = "";
	
	if(file_exists($photoalbum . "/browse.cap"))
	{
		$lines = file($photoalbum . "/browse.cap");
		
		foreach($lines as $line) 
		{
			//line starts with the image name, remove image name and leading whitespace, display caption
		   
		   $matches = array();
		   
		   $matchcount = 0;
		   
		   $matchcount = preg_match_all("/(^DM_ALBUM_TITLE:\s)(.*)/i", $line, $matches);
		   
		   if($matchcount > 0)
		   {
				$filename = $matches[0][1];
			   	$ablum_title = trim($matches[2][0]);
			   
			   	if(strlen($ablum_title) > 0)	return trim("$ablum_title");
				else 							return trim($ablum_title);
		   }
		}
	}
	
	return $ablum_title;
}

function dm_put_title($album, $displaycaption)
{
	$directory = $album;
		
	$captionfilename = $directory . "/browse.cap";

	if(!file_exists($captionfilename))		
	{
		fopen($captionfilename, "x+");
	}
	
	$lines = file($captionfilename);
	
	if(count($lines) == 0)	$lines = array();
	
	$foundcaption = 0;
	
	$linecount = 0;
	
	while($linecount < count($lines)) 
	{
		$line = $lines[$linecount];
		
		//line starts with the image name, remove image name and leading whitespace, display caption
		
		$matches = array();
			
		$matchcount = 0;
			
		$matchcount = preg_match_all("/(^DM_ALBUM_TITLE:\s)(.*)/i", $line, $matches);
			
		if($matchcount > 0)
		{
			$foundcaption = 1;
			$filename = $matches[0][1];
			$caption = $displaycaption;
			
			$lines[$linecount] = "DM_ALBUM_TITLE:\t$caption\n";
		}
			
		$linecount++;
	}
	
	if($foundcaption === 0)
	{
		$lines[$linecount] = "DM_ALBUM_TITLE:\t$displaycaption\n";
	}
	
	$linecount = 0;
	$captionfilecontents = "";
	
	while($linecount < count($lines)) 
	{
		if(preg_match("/^\s$/", $lines[$linecount])	!= 1)	$captionfilecontents = $captionfilecontents . $lines[$linecount];
		
		$linecount++;
	}
	
	$fh = fopen($captionfilename, "w+");

	$captionfilecontents = html_entity_decode($captionfilecontents);
	
	$captionfilecontents = stripslashes($captionfilecontents);
	
	fwrite($fh, $captionfilecontents);
}

function dm_get_sortorder($photoalbum)
{
	$ablum_sortorder = "";
	
	if(file_exists($photoalbum . "/browse.cap"))
	{
		$lines = file($photoalbum . "/browse.cap");
		
		foreach($lines as $line) 
		{
			//line starts with the image name, remove image name and leading whitespace, display caption
		   
		   $matches = array();
		   
		   $matchcount = 0;
		   
		   $matchcount = preg_match_all("/(^DM_ALBUM_SORTORDER:\s)(.*)/i", $line, $matches);
		   
		   if($matchcount > 0)
		   {
				$filename = $matches[0][1];
			   	$ablum_sortorder = trim($matches[2][0]);
			   
			   	if(strlen($ablum_sortorder) > 0)	return trim("$ablum_sortorder");
				else 								return trim($ablum_sortorder);
		   }
		}
	}
	
	$album_cleansed_sortorder;
	
	foreach($ablum_sortorder as $photo) 
	{
		if(file_exists($photoalbum . "/$photo"))	$album_cleansed_sortorder[] = $photo;
	}
	
	return $album_cleansed_sortorder;
}

function dm_put_sortorder($album, $ablum_sortorder)
{
	$directory = $album;
		
	$captionfilename = $directory . "/browse.cap";

	if(!file_exists($captionfilename))		
	{
		fopen($captionfilename, "x+");
	}
	
	$lines = file($captionfilename);
	
	if(count($lines) == 0)	$lines = array();
	
	$foundsortorder = 0;
	
	$linecount = 0;
	
	while($linecount < count($lines)) 
	{
		$line = $lines[$linecount];
		
		//line starts with the image name, remove image name and leading whitespace, display caption
		
		$matches = array();
			
		$matchcount = 0;
			
		$matchcount = preg_match_all("/(^DM_ALBUM_SORTORDER:\s)(.*)/i", $line, $matches);
			
		if($matchcount > 0)
		{
			$foundsortorder = 1;
			$filename = $matches[0][1];

			$lines[$linecount] = "DM_ALBUM_SORTORDER:\t$ablum_sortorder\n";
		}
			
		$linecount++;
	}
	
	if($foundsortorder === 0)
	{
		$lines[$linecount] = "DM_ALBUM_SORTORDER:\t$ablum_sortorder\n";
	}
	
	$linecount = 0;
	$captionfilecontents = "";
	
	while($linecount < count($lines)) 
	{
		if(preg_match("/^\s$/", $lines[$linecount])	!= 1)	$captionfilecontents = $captionfilecontents . $lines[$linecount];
		
		$linecount++;
	}
	
	$fh = fopen($captionfilename, "w+");

	$captionfilecontents = html_entity_decode($captionfilecontents);
	
	$captionfilecontents = stripslashes($captionfilecontents);
	
	fwrite($fh, $captionfilecontents);
}

function dm_remove_photo_from_sortorder($photoalbum, $photo)
{
	$album_sortorder = dm_get_sortorder($photoalbum);
	
	if(strlen(trim($album_sortorder)) > 0)
	{
		$album_sortorder = str_replace($photo . ";", "", $album_sortorder);
		dm_put_sortorder($photoalbum, $album_sortorder);
	}
}

function dm_add_photo_to_sortorder($photoalbum, $photo)
{
	$album_sortorder = dm_get_sortorder($photoalbum);
	
	if(strlen(trim($album_sortorder)) > 0)
	{
		if(strpos($album_sortorder, $photo) === FALSE)
		{
			$album_sortorder = $album_sortorder . ";$photo";
			dm_put_sortorder($photoalbum, $album_sortorder);
		}
	}
}

function dm_reset_photo_sortorder($photoalbum)
{
	$album_sortorder = dm_get_sortorder($photoalbum);
	
	if(strlen(trim($album_sortorder)) > 0)
	{
		dm_put_sortorder($photoalbum, "");
	}
}

function dm_refresh_photo_sortorder($photoalbum)
{
	$album_sortorder = dm_get_sortorder($photoalbum);
		
	if(strlen(trim($album_sortorder)) > 0)
	{
		$album = explode(";", $album_sortorder);
		$photos = dm_get_photo_list($photoalbum, true);
	
		$missing = array_diff($photos, $album);
		
		$album_sortorder = $album_sortorder . ";" . implode(";", $missing);
		
		$album_sortorder = rtrim($album_sortorder, ";");
		
		$album = explode(";", $album_sortorder);
		
		$missing = array_diff($album, $photos);
		
		foreach($missing as $item)
		{
			$album_sortorder = str_replace($item . ";", "", $album_sortorder);
		}
		
		$album_sortorder = rtrim($album_sortorder, ";");
		
		dm_put_sortorder($photoalbum, $album_sortorder);
	}
}

function dm_get_album_datecmp($a, $b)
{
	return ($a[1] > $b[1]) ? -1 : 1;
}

function dm_get_album_alphacmp($a, $b)
{
	return (strtolower(basename($a[0])) < strtolower(basename($b[0]))) ? -1 : 1;
}

function dm_get_album_delete($album)
{
	$handle = opendir($album);
	
	while (false!==($item = readdir($handle)))
	{
		$type = filetype($item);
				
		if($item != '.' && $item != '..')
		{
			if($type != "link" && is_dir($album.'/'.$item)) 
			{
				dm_get_album_delete($album.'/'.$item);
			}
			
			else
			{
				unlink($album.'/'.$item);
			}
		}
	}
	
	closedir($handle);
	
	rmdir($album);
}
	
function dm_sanitize($folder, $soft = 0)
{
	$folder = str_replace("..", "", $folder); 
	
	if($soft == 0)	
	{
		$bad_chars = "/[^\w\s\(\)\:\.-]+/";
		$replacement_chars = "";
		
		$folder = trim(preg_replace($bad_chars, $replacement_chars, $folder), '/\\');
		
		$folder = str_replace("/", "", $folder); 
		$folder = str_replace("\\", "", $folder); 
	}
	
	return $folder;
}

function dm_getuploaddirectory()
{
	global $blog_id; 
	
	if(get_option('DM_ALBUMS_UPLOADDIR') == "" || get_option('DM_ALBUMS_UPLOADDIR') == "/")
	{
		update_option('DM_ALBUMS_UPLOADDIR', get_option('DM_ALBUMS_CORE_DEFAULT_UPLOADDIR'));
	}
	
	return str_replace("{BLOG_ID}", $blog_id, get_option('DM_ALBUMS_UPLOADDIR'));
}

function dm_user_uploaddirectory()
{
	$DM_UUP = get_option('DM_ALBUMS_UUP');
	
	if($DM_UUP == 1)
	{
		global $current_user, $_POST, $_GET;
		get_currentuserinfo();
	
		$user_upload_directory = $current_user->user_email;
		
		if(!isset($user_upload_directory) || empty($user_upload_directory))
		{
			$user_upload_directory = isset($_POST["dm_uud"]) ? $_POST["dm_uud"] : $_GET["dm_uud"];
	
			$user_upload_directory = str_replace("../", "", $user_upload_directory);
			$user_upload_directory = str_replace("/", "", $user_upload_directory);
			$user_upload_directory = str_replace("\\", "", $user_upload_directory);
			$user_upload_directory = str_replace("'", "", $user_upload_directory);
			$user_upload_directory = str_replace("\"", "", $user_upload_directory);
			
			$user_upload_directory = trim($user_upload_directory, '/\\');
		}
		
		//$user_upload_directory = str_replace("@", "_at_", $user_upload_directory);
		
		return $user_upload_directory . "/";
	}
}

function dm_is_wpmu()
{
	if(is_dir($_SERVER['DOCUMENT_ROOT'] . '/wp-content/mu-plugins')) return true;
	else return false;
}

function dm_isUserAdmin()
{
	global $blog_id;
	
	// NON WPMU AND ADMINS
	if(!dm_is_wpmu() && current_user_can('level_10'))	return true;
	
	// WPMU AND BASE BLOG
	if(dm_is_wpmu() && $blog_id == 1)	return true;

	return false;
}

function dm_is_wamp()
{
	//return eregi("WIN", strtoupper(php_uname()));
	if(strpos(ABSPATH, ":/") === TRUE && strpos(ABSPATH, ":/") == 1)	return true;
}

?>