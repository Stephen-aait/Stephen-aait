<?php
/*************************************************************** 
 * Application Name: DM Albums									
 * Application URI: http://www.dutchmonkey.com/wp-plugins/
 * Description: In-Line Version of DM PhotoAlbums.
 * Author: Frank D. Strack
 * Author Email: development@dutchmonkey.com
 * Author URI: http://www.dutchmokney.com
 ***************************************************************/

ini_set("memory_limit", -1);

$image = $_GET["image"];
$width = $_GET["width"];
$height = $_GET["height"];
$quality = $_GET["quality"];
$degrees = $_GET["degrees"];
$scale = $_GET["scale"];
$maintain_aspect = $_GET["maintain_aspect"];
$grayscale = $_GET["grayscale"];
$crop = $_GET["crop"];

$original_x = $_GET["width"];
$original_y = $_GET["height"];

if(!isset($DISPLAY_ERRORS))	$DISPLAY_ERRORS = TRUE;

require("images.php");

$errors = false;

$image = str_replace("//", "/", $image);

if(!$quality)					$quality = 100;
if(!$degrees)					$degrees= 0;
if(!$grayscale == "yes")		$grayscale = "no";

if($rounding == "nearest")		
{
	$width = round($width, -1);
	$height = round($height, -1);
}

if($scale == "yes" && $maintain_aspect == "yes")
{
	list($width, $height, $scale, $rotate, $type, $attr) = getattributes($image, $width, $height, $degrees);
}

$im_resource = imageopennearest($image, $width, $height, $degrees);

if($crop == "yes" && $original_x == $original_y)
{
	$im_resource = imagethumb(imageopen($image), $original_x, $original_y);	
}

if($crop == "yes" && $original_x != $original_y)
{
	$im_resource = imagecrop($im_resource, $original_x, $original_y);	
}

if($scale == "yes")
{
	if($degrees == 0 || $degrees == 180)		
	{
		$im_resource = imageresize($im_resource, $width, $height, $quality, $image, $degrees);
	}
	
	else if($degrees == 90 || $degrees == 270)	
	{
		$im_resource = imageresize($im_resource, $height, $width, $quality, $image, $degrees);
	}
}

if($degrees != 0)
{
	$im_resource = imagerotate($im_resource, $degrees, true);
}

if($grayscale == "yes")
{
	$im_resource = imagegrayscale($im_resource);
}

if($scale == "yes" || $degrees != 0 || $grayscale == "yes")
{
	if($crop != "yes")	cacheimage($im_resource, $image, $width, $height, $degrees);
}

if(empty($im_resource)) 
{ 
	$im_resource = imageerror($filename, $width, $height);
	
	deletecachedimage($image, $width, $height, $degrees);
}

imageinterlace($im_resource, 1);

header("Content-type: image/jpeg");

imagejpeg($im_resource, "", $quality);

ob_flush();
flush();

imagedestroy($im_resource);

ob_end_flush();

?>