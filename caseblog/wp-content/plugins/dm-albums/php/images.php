<?php
/*************************************************************** 
 * Application Name: DM Albums									
 * Application URI: http://www.dutchmonkey.com/wp-plugins/
 * Description: In-Line Version of DM PhotoAlbums.
 * Author: Frank D. Strack
 * Author Email: development@dutchmonkey.com
 * Author URI: http://www.dutchmokney.com
 ***************************************************************/

include_once("includes.php");

ini_set("memory_limit", -1);

$CACHE_DIR = "/.cache/";

if(eregi("WIN", strtoupper(php_uname()))) 
{
    $CACHE_DIR = "/cache/";
}

function imagedebug($message)
{
	$fh = fopen("imagedebug.log", "a+");

	$timestamp = date("D M j G:i:s T Y");
	
	$debug = "[$timestamp]\t$message\n";
	
	fwrite($fh, $debug);
}

function imageopen($filename)
{
	global $DM_ALBUMS_IMAGETYPE_PNG;
	global $DM_ALBUMS_IMAGETYPE_GIF;			 
	global $DM_ALBUMS_IMAGETYPE_JPG;
	
	$type = dm_get_imagetype($filename);
	
	if($type == $DM_ALBUMS_IMAGETYPE_PNG)
	{
		$im_resource = imagecreatefrompng($filename);
		
		$width = imagesx($im_resource);
		$height = imagesy($im_resource);

		$image_p = imagecreatetruecolor($width, $height);
	
		$wht = imagecolorallocate($image_p, 255, 255, 255);
		imagefilledrectangle($image_p, 0, 0, $width, $height, $wht);

		imagecopy($image_p, $im_resource, 0, 0, 0, 0, $width, $height);

		imagedestroy($im_resource);
	
		$im_resource = "";

		return $image_p;
	}

	else if($type == $DM_ALBUMS_IMAGETYPE_GIF)	
	{
		$im_resource = imagecreatefromgif($filename);
		
		$width = imagesx($im_resource);
		$height = imagesy($im_resource);

		$image_p = imagecreatetruecolor($width, $height);
	
		$wht = imagecolorallocate($image_p, 255, 255, 255);
		imagefilledrectangle($image_p, 0, 0, $width, $height, $wht);

		imagecopy($image_p, $im_resource, 0, 0, 0, 0, $width, $height);

		imagedestroy($im_resource);
	
		$im_resource = "";

		return $image_p;
	}

	else if($type == $DM_ALBUMS_IMAGETYPE_JPG)		
	{
		return imagecreatefromjpeg($filename);
	}
	
	else
	{
		return "";
	}
}

function imageisvalid($filename)
{
	$retval = 0;

	$im_resource = imageopen($filename);
	
	if(!empty($im_resource))	$retval = 1;

	imagedestroy($im_resource);
	
	$im_resource = "";

	return $retval;
}

function imagerotate2($source, $angle) 
{
	$source_width = imagesx($source);
	$source_height = imagesy($source);

	$rotate_width = $source_height;
	$rotate_height = $source_width;

	if($angle == 90 || $angle == 270)	$rotate = imagecreatetruecolor($rotate_width, $rotate_height);

	else					
	{
		$rotate_width = $source_width;
		$rotate_height = $source_height;

		imagedestroy($rotate );

		$rotate = "";

		$rotate = imagecreatetruecolor($rotate_width, $rotate_height);
	}

	$rotate_width = $rotate_width - 1;
	$rotate_height = $rotate_height - 1;

	for($y = 0; $y < $source_height; $y++) 
	{
		for ($x = 0; $x < $source_width; $x++) 
		{
			$color = imagecolorat($source, $x, $y);

			if($angle == 90)		imagesetpixel($rotate, $rotate_width - $y, $x, $color);
			else if($angle == 180)	imagesetpixel($rotate, $rotate_width - $x, $rotate_height - $y, $color);
			else if($angle == 270)	imagesetpixel($rotate, $y, $rotate_height - $x, $color);
			else					imagesetpixel($rotate, $x, $y, $color);
		}
	}
	
	imagedestroy($source);

	$source = "";

	return $rotate;
}

function imagegrayscale($source) 
{
	$source_width = imagesx($source);
	$source_height = imagesy($source);

	$grayscale = imagecreatetruecolor($source_width, $source_height);

	for($y = 0; $y < $source_height; $y++) 
	{
		for ($x = 0; $x < $source_width; $x++) 
		{
			$rgb = imagecolorat($source, $x, $y);

			$red  = ($rgb >> 16) & 0xFF;
			$green = ($rgb >> 8)  & 0xFF;
			$blue  = $rgb & 0xFF;
			
			$gray = round(.299*$red + .587*$green + .114*$blue);

			// shift gray level to the left
			$gray_r = $gray << 16;
			$gray_g = $gray << 8;
			$gray_b = $gray;

			// OR operation to compute gray value
			$gray = $gray_r | $gray_g | $gray_b;

			imagesetpixel($grayscale, $x, $y, $gray);
		}
	}
	
	imagedestroy($source);

	$source = "";

	return $grayscale;
}

function imageresize($source, $width, $height, $quality, $filename, $degrees)
{
	$src_x = imagesx($source);
	$src_y = imagesy($source);

	$image_p = imagecreatetruecolor($width, $height);
	
	if(imagecopyresampled($image_p, $source, 0, 0, 0, 0, $width, $height, $src_x, $src_y))
	{
		imagedestroy($source);

		$source = "";
		
		//cacheimage($image_p, $filename, $width, $height, $degrees);
		
		$sharpen = array(array(-1,-1,-1), array(-1,24,-1), array(-1,-1,-1)); //array(-1,-1,-1,-1,16,-1,-1,-1,-1);
		$divisor = 16;
		
		if($width <= 32 || $height <= 32)
		{
			$sharpen = array(array(-1,-1,-1), array(-1,16,-1), array(-1,-1,-1)); //array(-1,-1,-1,-1,16,-1,-1,-1,-1);
			$divisor = 8;
		}
		
		$offset = 0;
		
		if (function_exists('imageconvolution')) imageconvolution($image_p, $sharpen, $divisor, $offset);
		
		return $image_p;
	}

	else
	{
		imagedestroy($image_p);

		$image_p = "";

		return $source;
	}
}

function imagecrop($source, $width, $height)
{
	$src_x = imagesx($source);
	$src_y = imagesy($source);
	
	if($src_x < $width)
	{	
		$src_y = floor(($width * $src_y) / $src_x);	
		$src_x = floor($width);	
		
		$source = imageresize($source, $src_x, $src_y, 100);
		
		$sharpen = array(array(-1,-1,-1), array(-1,16,-1), array(-1,-1,-1)); //array(-1,-1,-1,-1,16,-1,-1,-1,-1);
		$divisor = 8;
		$offset = 0;
		
		if(function_exists('imageconvolution')) imageconvolution($source, $sharpen, $divisor, $offset);
	}
	
	if($src_y > $height)
	{
		$src_dw = $width;
		$src_dh = $height;
		$src_sw = $width;
		$src_sh = $height;
			
		$src_sx = 0;
		$src_sy = floor(($src_y - $src_dh) / 2);
		
		$image_p = imagecreatetruecolor($src_dw, $src_dh);
		
		if(imagecopyresampled($image_p, $source, 0, 0, $src_sx, $src_sy, $src_dw, $src_dh, $src_sw, $src_sh))
		{
			imagedestroy($source);
	
			$source = "";
			
			return $image_p;
		}
	
		else
		{
			imagedestroy($image_p);
	
			$image_p = "";
	
			return $source;
		}
	}
	
	else
	{
		return $source;
	}
}

function imagethumb($source)
{
	$src_x = imagesx($source);
	$src_y = imagesy($source);
	
	if($src_y > $src_x)
	{
		$src_sx = 0;
		$src_sy = ($src_y - $src_x) / 2;
		$src_dw = $src_y;
		$src_sw = $src_x;
	}
	else
	{
		$src_sx = ($src_x - $src_y) / 2;
		$src_sy = 0;
		$src_dw = $src_x;
		$src_sw = $src_y;
	}

	$image_p = imagecreatetruecolor($src_dw, $src_dw);
	
	if(imagecopyresampled($image_p, $source, 0, 0, $src_sx, $src_sy, $src_dw, $src_dw, $src_sw, $src_sw))
	{
		imagedestroy($source);

		$source = "";
		
		$sharpen = array(array(-1,-1,-1), array(-1,16,-1), array(-1,-1,-1)); //array(-1,-1,-1,-1,16,-1,-1,-1,-1);
		$divisor = 8;
		$offset = 0;
		
		if(function_exists('imageconvolution')) imageconvolution($image_p, $sharpen, $divisor, $offset);
		
		return $image_p;
	}

	else
	{
		imagedestroy($image_p);

		$image_p = "";

		return $source;
	}
}

function imagesharpen($source)
{
	
}

function imageerror($filename, $width, $height)
{
	$error_image = dirname(__FILE__) . "../ui/warning.png";
	list($error_width, $error_height) = getimagesize("$error_image");

	if($width < $error_width || $height < $error_height)
	{
		$width = 100;
		$height = 100;
	}
	
	$im_resource = imagecreatetruecolor($width, $height); 
	$im_error = imagecreatefromgif($error_image);

	$wht = imagecolorallocate($im_resource, 255, 255, 255);
	$blk = imagecolorallocate($im_resource, 0, 0, 0);
	imagefilledrectangle($im_resource, 0, 0, $width, $height, $blk);
	imagefilledrectangle($im_resource, 1, 1, $width - 2, $height - 2, $wht);

	$center_x = ($width - $error_width) / 2;
	$center_y = (($height - $error_height) / 2) - 2;
       
	imagecopy($im_resource, $im_error, $center_x, $center_y, 0, 0, $error_width, $error_height);

	imagedestroy($im_error);

	$im_error = "";

	return $im_resource;
}

function cacheimage($image_p, $filename, $width, $height, $degrees)
{
	global $CACHE_DIR;
	
	if(empty($image_p)) return;
	
	if(isimagecached($filename, $width, $height, $degrees)) return;
	
	$ext = strtolower(substr($filename, strrpos($filename, ".") + 1, strlen($filename)));
	
	if($ext != "jpg" && $ext != "jpeg")	return;
		
	$filename_cache = getcachedfilename($filename, $width, $height, $degrees);
	
	imagejpeg($image_p, $filename_cache, 95);
}

function deletecachedimage($filename, $width, $height, $degrees)
{
	global $CACHE_DIR;
	
	$filename_cache = getcachedfilename($filename, $width, $height, $degrees);
	
	if(file_exists($filename_cache))	unlink($filename_cache);
}

function isimagecached($filename, $width, $height, $degrees)
{
	global $CACHE_DIR;
	
	$filename_cache = getcachedfilename($filename, $width, $height, $degrees);

	if(file_exists($filename_cache) && filemtime($filename_cache) > filemtime($filename))
	{
		return true;
	}
}

function imageopennearest($filename, $width, $height, $degrees)
{
	return imageopen(getnearestcachedfilename($filename, $width, $height, $degrees));
}

function getcachedimage($filename, $width, $height, $degrees)
{
	global $CACHE_DIR;
	
	$filename_cache = getcachedfilename($filename, $width, $height, $degrees);
	
	return imageopen($filename_cache);
}

function getnearestcachedfilename($filename, $width, $height, $degrees)
{
	global $CACHE_DIR;
	
	clearstatcache(); 
	
	$nearest_filename = $filename;
	
	$dirname = dirname($filename) . $CACHE_DIR;

	$dir = opendir($dirname);

	$files = array();

	$i = 0;

	while(false !== ($file = readdir($dir)))
	{
		$type = filetype($dirname . $file);

		if($type != "dir" && strpos($file, basename($filename)) !== false)
		{
			$files[$i] = $file;

			$i++;
		}
	}

	closedir($dir);

	natcasesort($files);

	$matches = array_values($files);

	foreach($matches as $match)
	{
		list($w, $h, $d) = getimageparameters("$match");
		
		$cached_filename = dirname($filename) . "$CACHE_DIR$match";
		
		if($d == $degrees && $w >= $width && $h >= $height && filemtime($cached_filename) > filemtime($filename))
		{
			$nearest_filename = $cached_filename;
			break;
		}
	}
	
	return $nearest_filename;
}

function getcachedfilename($filename, $width, $height, $degrees)
{
	global $CACHE_DIR;
	
	$cache_dir = dirname($filename) . $CACHE_DIR;
	$cache_file = $width . "x" . $height . "x" . $degrees . "_" . basename($filename); 
	
	if(!file_exists($cache_dir))
	{
		clearstatcache();
		dm_mkdir($cache_dir);
	}
	
	return $cache_dir . $cache_file;
}

function getimageparameters($filename)
{
	list($dims, $name) = split("_", $filename);
	
	list($width, $height, $degrees) = split("x", $dims);
	
	return array($width, $height, $degrees);
}

function getattributes($image, $max_width, $max_height, $degrees)
{
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
	//$max_width = $SCREEN_WIDTH - 100;
	//$max_height = $SCREEN_HEIGHT - 510;

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
?>