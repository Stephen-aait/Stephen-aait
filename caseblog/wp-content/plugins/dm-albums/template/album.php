<?php  
error_reporting(0);
/*************************************************************** 
 * Application Name: DM Albums									
 * Application URI: http://www.dutchmonkey.com/wp-plugins/
 * Description: In-Line Version of DM PhotoAlbums.
 * Author: Frank D. Strack
 * Author Email: development@dutchmonkey.com
 * Author URI: http://www.dutchmokney.com
 ***************************************************************/

// Protect against mallicious users attempting to access these files directly
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'album.php' == basename($_SERVER['SCRIPT_FILENAME']))
{
	header('Location: http://www.google.com/');
	
	die ('Please do not load this page directly. Thanks!');
}

$currdir = $_GET["currdir"];

$DM_APPNAME = "Photo Albums";
$DM_APPVERSION = "v2.1.0";

require_once(dirname(dirname(__FILE__)) . '/security.php');

//*********************************************************************

$USER_AGENT = strtolower($_SERVER['HTTP_USER_AGENT']);

$IS_SAFARI = FALSE;

//if(strpos($USER_AGENT, "safari") !== FALSE)		$IS_SAFARI = TRUE;	

$html_scrollleft = "<a href='javascript:void(0);' onMouseDown='g_MouseDown = true; ResetScrollspeed(); ScrollThumbnailsLeft();' onMouseUp='g_MouseDown = false;' class='moreslides'><img src='ui/moreslides_left.png' border='0'></a>";

$html_scrollright = "<a href='javascript:void(0);' onMouseDown='g_MouseDown = true; ResetScrollspeed(); ScrollThumbnailsRight();' onMouseUp='g_MouseDown = false;' class='moreslides'><img src='ui/moreslides_right.png' border='0'></a>";

$DM_PHOTOALBUM_COOKIE	= "DM_PHOTOALBUM";
$DM_PHOTOALBUM_VERSION 	= $DM_APPVERSION;

$DM_PHOTOALBUM_CHK = "";

if($_COOKIE[$DM_PHOTOALBUM_COOKIE] == $DM_PHOTOALBUM_VERSION)		
{
	$DM_PHOTOALBUM_CHK = "checked";
}

$ALBUM_TITLE = trim(htmlentities(gettitle()));

$THUMBNAILS_HEIGHT = $THUMBNAIL_SIZE + ($THUMBNAIL_PADDING * 5);
$MARGINHEIGHTS = $THUMBNAILS_HEIGHT + $CAPTION_HEIGHT + $PHOTO_PADDING;

if($IS_SAFARI)	$MARGINHEIGHTS = $MARGINHEIGHTS + 20;

if($DISPLAY_CAPTIONS == 0 && $DISPLAY_PHOTOCOUNT == 0)
{
	$MARGINHEIGHTS = $MARGINHEIGHTS - $CAPTION_HEIGHT;
}

if($_GET["download"] == "yes")
{
	$file = basename($_GET["file"]);
	
	global $HOME_DIR, $currdir;

	$fullfile = dm_sanitize($currdir, 1) . $file;
	
	$filename = $HOME_DIR . $fullfile;

	if(dm_is_image($filename))
	{
		$filesize = filesize($filename);
		
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	    header('Content-Description: File Transfer');
	    header('Content-Type: application/octet-stream');
	    header('Content-Length: ' . $filesize);
	    header('Content-Disposition: attachment; filename="' . $file . '"');
	    readfile($filename);
	}
}

if(!isset($HIDE_LOADING_MESSAGE) || empty($HIDE_LOADING_MESSAGE))						$HIDE_LOADING_MESSAGE = 0;
if(!isset($HIDE_LOADING_MESSAGE_SLIDESHOW) || empty($HIDE_LOADING_MESSAGE_SLIDESHOW))	$HIDE_LOADING_MESSAGE_SLIDESHOW = 0;

$DEFAULT_PROPERTIES_HTML = "<table border='0' cellpadding='0' cellspacing='0' width='100%'><tr><td width='1' colspan='2'><img src='ui/spacer.gif' height='5'></td></tr><tr><td colspan='2'><img src='ui/spacer.gif'></td></tr><tr><td align='left' nowrap valign='top' width='125'><span>Name</span>: <img src='ui/spacer.gif' width='5'></td><td align='left' valign='top'><span><img src='ui/smallloading.gif' height='16' width='16'></span></td></tr><tr><td width='1' colspan='2'><img src='ui/spacer.gif' height='5'></td></tr><tr><td align='left' nowrap valign='top' width='125'><span>Size</span>: <img src='ui/spacer.gif' width='5'></td><td align='left' valign='top'><span><img src='ui/smallloading.gif' height='16' width='16'></span></td></tr><tr><td width='1' colspan='2'><img src='ui/spacer.gif' height='5'></td></tr><tr><td align='left' nowrap valign='top' width='125'><span>Make</span>: <img src='ui/spacer.gif' width='5'></td><td align='left' valign='top'><span><img src='ui/smallloading.gif' height='16' width='16'></span></td></tr><tr><td width='1' colspan='2'><img src='ui/spacer.gif' height='5'></td></tr><tr><td align='left' nowrap valign='top' width='125'><span>Model</span>: <img src='ui/spacer.gif' width='5'></td><td align='left' valign='top'><span><img src='ui/smallloading.gif' height='16' width='16'></span></td></tr><tr><td width='1' colspan='2'><img src='ui/spacer.gif' height='5'></td></tr><tr><td align='left' nowrap valign='top' width='125'><span>Date Taken</span>: <img src='ui/spacer.gif' width='5'></td><td align='left' valign='top'><span><img src='ui/smallloading.gif' height='16' width='16'></span></td></tr><tr><td width='1' colspan='2'><img src='ui/spacer.gif' height='5'></td></tr><tr><td align='left' nowrap valign='top' width='125'><span>Aperture</span>: <img src='ui/spacer.gif' width='5'></td><td align='left' valign='top'><span><img src='ui/smallloading.gif' height='16' width='16'></span></td></tr><tr><td width='1' colspan='2'><img src='ui/spacer.gif' height='5'></td></tr><tr><td align='left' nowrap valign='top' width='125'><span>F Stop</span>: <img src='ui/spacer.gif' width='5'></td><td align='left' valign='top'><span><img src='ui/smallloading.gif' height='16' width='16'></span></td></tr><tr><td width='1' colspan='2'><img src='ui/spacer.gif' height='5'></td></tr><tr><td align='left' nowrap valign='top' width='125'><span>Focal Length</span>: <img src='ui/spacer.gif' width='5'></td><td align='left' valign='top'><span'>440/10</span></td></tr><tr><td width='1' colspan='2'><img src='ui/spacer.gif' height='5'></td></tr><tr><td align='left' nowrap valign='top' width='125'><span>Metering Mode</span>: <img src='ui/spacer.gif' width='5'></td><td align='left' valign='top'><span><img src='ui/smallloading.gif' height='16' width='16'></span></td></tr><tr><td width='1' colspan='2'><img src='ui/spacer.gif' height='5'></td></tr><tr><td align='left' nowrap valign='top' width='125'><span>Exposure Program</span>: <img src='ui/spacer.gif' width='5'></td><td align='left' valign='top'><span><img src='ui/smallloading.gif' height='16' width='16'></span></td></tr><tr><td width='1' colspan='2'><img src='ui/spacer.gif' height='5'></td></tr><tr><td align='left' nowrap valign='top' width='125'><span>Exposure Time</span>: <img src='ui/spacer.gif' width='5'></td><td align='left' valign='top'><span><img src='ui/smallloading.gif' height='16' width='16'></span></td></tr><tr><td width='1' colspan='2'><img src='ui/spacer.gif' height='5'></td></tr><tr><td align='left' nowrap valign='top' width='125'><span>Exposure Bias</span>: <img src='ui/spacer.gif' width='5'></td><td align='left' valign='top'><span><img src='ui/smallloading.gif' height='16' width='16'></span></td></tr><tr><td width='1' colspan='2'><img src='ui/spacer.gif' height='5'></td></tr><tr><td align='left' nowrap valign='top' width='125'><span>Flash</span>: <img src='ui/spacer.gif' width='5'></td><td align='left' valign='top'><span><img src='ui/smallloading.gif' height='16' width='16'></span></td></tr><tr><td width='1' colspan='2'><img src='ui/spacer.gif' height='5'></td></tr><tr><td align='left' nowrap valign='top' width='125'><span>Light Source</span>: <img src='ui/spacer.gif' width='5'></td><td align='left' valign='top'><span><img src='ui/smallloading.gif' height='16' width='16'></span></td></tr><tr><td width='1' colspan='2'><img src='ui/spacer.gif' height='5'></td></tr><tr><td align='left' nowrap valign='top' width='125'><span>Compression</span>: <img src='ui/spacer.gif' width='5'></td><td align='left' valign='top'><span><img src='ui/smallloading.gif' height='16' width='16'></span></td></tr><tr><td width='1' colspan='2'><img src='ui/spacer.gif' height='5'></td></tr></table>";
?>
<!--
/*************************************************************** 
 * Application Name: DM Albums									
 * Application URI: http://www.dutchmonkey.com/wp-plugins/
 * Description: In-Line Version of DM Photo Albums.
 * Author: Frank D. Strack
 * Author Email: development@dutchmonkey.com
 * Author URI: http://www.dutchmokney.com
 ***************************************************************/
 -->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php  print("$ALBUM_TITLE"); ?></title>

<link rel="stylesheet" type="text/css" href="ui/styles.css">

<style type="text/css">

.moreslides
{
  	padding-top : <?php  print(ceil(($THUMBNAILS_HEIGHT - 20)/2)); ?>px;
	padding-bottom : <?php  print(floor(($THUMBNAILS_HEIGHT - 20)/2)); ?>px;
}

<?php  if($DISPLAY_PHOTOCOUNT == 0)
{
	?>#tr_photocount
{
	display: none;
}
	<?php  }

if($DISPLAY_CAPTIONS == 0)
{
	?>#tr_caption
{
	display: none;
}
	<?php  }

if($DISPLAY_CAPTIONS == 0 && $DISPLAY_PHOTOCOUNT == 0)
{
	?>#tr_caption_photocount
{
	display: none;
}
	<?php  }

?>

</style>

<?php 

if(!empty($DM_ALBUMS_EXTERNAL_CSS))	$DM_ALBUMS_EXTERNAL_CSS = '\n<link rel="stylesheet" type="text/css" href="' . $DM_ALBUMS_EXTERNAL_CSS . '">\n';

?>

<script type="text/javascript" src="javascript/browser.js"></script>
<script type="text/javascript" src="javascript/dragdrop.js"></script>
	
<script type="text/javascript" language="Javascript">
<!--

var IMAGE_LARGE = 0;
var IMAGE_BESTFIT = 1;
var DM_PHOTOALBUM_VERSION = "<?php  print($DM_PHOTOALBUM_VERSION); ?>";

var g_DocumentLoaded = false;
var g_InitialImageLoad = true;
var SLIDESHOW_DELAY = 5000;
var g_SlideshowTimeout = null;
var g_ThumbnailTimeout = null;
var g_ScrollTimeout = null;
var g_MouseDown = false;
var g_ImageViewMode = IMAGE_BESTFIT;
var g_Browser = new Browser();
var g_Degrees = 0;
var g_Index = <?php print($slide);?>;
var g_Count = <?php print($COUNT);?>;
var g_ClientHeight = null;
var g_ParentWritable = false; //<?php  print($PARENT_IS_WRITABLE ? 1 : 0); ?>;var g_DisableInlineEdit = true;
var g_CaptionId = null;
var g_CurrDir = "<?php print($currdir);?>";
var g_ScreenHeight = 0;
var g_CurrentPrinterId = null;
var g_PrintWindowOpen = 0;
var g_CaptureKeyStrokes = true;
var g_AvailHeight = 0;
var g_ScrollSpeed = 5;
var g_ScrollDistance = ResetScrollspeed();
var g_LeftArrow = 39;
var g_RightArrow = 37;

var g_HideLoadingMessage = <?php  print($HIDE_LOADING_MESSAGE); ?>;
var g_HideLoadingMessageDuringSlideshow = <?php  print($HIDE_LOADING_MESSAGE_SLIDESHOW); ?>;

var g_PhotoLinks = new Array();

var photoindex = 0;
<?php

global $HOME_DIR, $currdir;

$photoalbum = $HOME_DIR . $currdir;

$photos = dm_get_photo_list($photoalbum);

foreach($photos as $photo)
{
	?>
	g_PhotoLinks[photoindex++] = "<?php print(dm_get_link($photoalbum . "/" . $photo)); ?>";
	<?php
}

?>

if(g_Browser.Safari)
{
	g_LeftArrow = 63235;
	g_RightArrow = 63234;	
}	

function isUrl(s) 
{
	var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/
	return regexp.test(s);
}

function ChangeSlideshowSpeed(amount)
{
	SLIDESHOW_DELAY = SLIDESHOW_DELAY + amount;

	if(SLIDESHOW_DELAY < 500) SLIDESHOW_DELAY = 1000;
}


function SupportedBrowser()
{
	//if(g_Browser.Safari)						return false;
	//else if(!g_Browser.DOM())					return false;
	if(!g_Browser.DOM())			return false;
	else							return true;
}

function SetSelectedThumnailVisible(index, count)
{
	var oFrameElement;
	var nScrollbuttonOffset = 0;
	
	if(g_Browser.explorer)	oFrameElement = document.getElementById("thumbnailframe").contentWindow.document;
	
	else					oFrameElement = document.getElementById("thumbnailframe").contentDocument;
	
	var elem = "";
	
	elem = "thumbid_" + (index);
	
	var offset = oFrameElement.getElementById(elem).offsetLeft;
	var offsetwidth = oFrameElement.getElementById(elem).offsetWidth;
	var clientWidth = oFrameElement.body.clientWidth;
	
	var scrollLeft = oFrameElement.body.scrollLeft;
	var scrollRight = (oFrameElement.body.scrollWidth - clientWidth) - scrollLeft;
	
	var position = g_Browser.GetPosition(oFrameElement.getElementById(elem));
	
	var nVisibleLeftPosition = (position[0] - scrollLeft);
	var nMiddleAxis = (clientWidth / 2) - (offsetwidth / 2);
	var nPadding = 1; //offsetwidth / 2;
	
	clearTimeout(g_ThumbnailTimeout);
	
	if(Math.abs(nVisibleLeftPosition - nMiddleAxis) >= (offsetwidth))	g_ScrollDistance = offsetwidth;
	else																	g_ScrollDistance = Math.round(g_ScrollDistance * .50);
	
	if(g_ScrollDistance <= 0)	g_ScrollDistance = 1;
	
	if(nVisibleLeftPosition < (nMiddleAxis - nPadding) && scrollLeft >= nPadding)
	{
		ScrollThumbnailsLeft();
		
		g_ThumbnailTimeout = setTimeout("SetSelectedThumnailVisible(" + index + ", " + count + ");", 5);
	}
	
	else if(nVisibleLeftPosition > (nMiddleAxis + nPadding) && scrollRight >= nPadding)
	{
		ScrollThumbnailsRight();
		
		g_ThumbnailTimeout = setTimeout("SetSelectedThumnailVisible(" + index + ", " + count + ");", 5);
	}
		
	else return;
}

function SetScrollButtons()
{
	if(!SupportedBrowser())	return;
	
	var offset = 0;
	
	var oThumbnails = document.getElementById("thumbnailframe");
	
	var oScrollLeft = document.getElementById("div_moreslides_left");
	var oScrollRight = document.getElementById("div_moreslides_right");
	
	if(g_Browser.DOM())
	{
		var postion = g_Browser.GetPosition(oThumbnails);
		
		oScrollLeft.style.top = postion[1]; //oThumbnails.offsetTop + offset;
		oScrollRight.style.top = postion[1]; //oThumbnails.offsetTop + offset;
		
		oScrollLeft.style.left = oThumbnails.offsetLeft + offset;
		oScrollRight.style.left = oThumbnails.clientWidth + offset - oScrollRight.clientWidth + 1;
		
		if(navigator.userAgent.indexOf("Firefox/3") != -1 || g_Browser.Safari)
		{
			oScrollLeft.style.top = oScrollLeft.offsetTop + 0;
			oScrollRight.style.top = oScrollRight.offsetTop + 0;
			
			oScrollLeft.style.left = oScrollLeft.offsetLeft + 0;
			//oScrollRight.style.left = oScrollRight.offsetLeft + 1;
		}
		
		else if(g_Browser.explorer)
		{
			oScrollLeft.style.top = oScrollLeft.offsetTop + 0;
			oScrollRight.style.top = oScrollRight.offsetTop + 0;
			
			oScrollLeft.style.left = oScrollLeft.offsetLeft - 0;
			oScrollRight.style.left = oScrollRight.offsetLeft + 0;
		}
	}
	
	else
	{	
		oScrollLeft.style.top = 0; 
		oScrollRight.style.top = 0; 
		
		oScrollLeft.style.left = 1;
		oScrollRight.style.left = g_Browser.Width() - 4;
	}
	
	if(g_Browser.explorer)	
	{
		oScrollLeft.style.filter = "alpha(opacity=80)";
		oScrollRight.style.filter = "alpha(opacity=80)";
	}
}

function ResetScrollspeed()
{
	g_ScrollDistance = 75;
	return g_ScrollDistance;
}

function ScrollThumbnailsLeft()
{
	var oFrameElement;
	
	EnableScrollRight();
	
	if(g_Browser.explorer)	oFrameElement = document.getElementById("thumbnailframe").contentWindow.document.body;
	
	else 					oFrameElement = document.getElementById("thumbnailframe").contentDocument.body;
	
	oFrameElement.scrollLeft -= g_ScrollDistance;
	
	document.getElementById("div_moreslides_left").className = "scrollon";

	if(oFrameElement.scrollLeft == 0)		DisableScrollLeft();
	
	if(g_MouseDown)	g_ScrollTimeout = setTimeout("ScrollThumbnailsLeft()", g_ScrollSpeed);

	else 
	{
		document.getElementById("div_moreslides_left").className = "scrolloff";
		return;
	}
}

function ScrollThumbnailsRight()
{
	var oFrameElement;
	
	EnableScrollLeft();
	
	if(g_Browser.explorer)	oFrameElement = document.getElementById("thumbnailframe").contentWindow.document.body;
	
	else 					oFrameElement = document.getElementById("thumbnailframe").contentDocument.body;
	
	oFrameElement.scrollLeft += g_ScrollDistance;
	
	document.getElementById("div_moreslides_right").className = "scrollon";
	
	var scrollRight = (oFrameElement.scrollWidth - oFrameElement.clientWidth) - oFrameElement.scrollLeft;
	
	//if(oFrameElement.clientWidth + oFrameElement.scrollLeft >= (oFrameElement.scrollWidth - 10))	DisableScrollRight();
	if(scrollRight == 0)	DisableScrollRight();
	
	if(g_MouseDown)	g_ScrollTimeout = setTimeout("ScrollThumbnailsRight()", g_ScrollSpeed);

	else 
	{
		document.getElementById("div_moreslides_right").className = "scrolloff";
		return;
	}
}

function CheckScrollWidth()
{
	var oFrameElement;
	
	if(g_Browser.explorer)	oFrameElement = document.getElementById("thumbnailframe").contentWindow.document.body;
	
	else					oFrameElement = document.getElementById("thumbnailframe").contentDocument.body;
	
	SetScrollButtons();
	
	EnableScrollLeft();
	EnableScrollRight();
	
	if(oFrameElement.scrollWidth <= oFrameElement.clientWidth)
	{
		DisableScrollLeft();
		DisableScrollRight();
	}
	
	var scrollRight = (oFrameElement.scrollWidth - oFrameElement.clientWidth) - oFrameElement.scrollLeft;
	
	if(oFrameElement.scrollLeft == 0)		return DisableScrollLeft();
	if(scrollRight == 0)					return DisableScrollRight();
}

function DisableScrollLeft()
{
	if(!SupportedBrowser())	return;
	
	document.getElementById("div_moreslides_left").style.visibility = "hidden";
	
	g_MouseDown = false;
}

function DisableScrollRight()
{
	if(!SupportedBrowser())	return;
	
	document.getElementById("div_moreslides_right").style.visibility = "hidden";
	
	g_MouseDown = false;
}

function EnableScrollLeft()
{
	if(!SupportedBrowser())	return;
	
	document.getElementById("div_moreslides_left").style.visibility = "visible";
}

function EnableScrollRight()
{
	if(!SupportedBrowser())	return;
	
	document.getElementById("div_moreslides_right").style.visibility = "visible";
}

function ToggleImageView()
{
	var image = "<?php  print("$HOME_DIR$currdir"); ?>" + document.getElementById('jumptolist').options[Number(document.getElementById('jumptolist').selectedIndex)].text;
	var index = Number(document.getElementById('jumptolist').selectedIndex + 1);
	var count = Number(document.getElementById('jumptolist').length);
	
	if(g_ImageViewMode == IMAGE_LARGE)	
	{
		g_ImageViewMode = IMAGE_BESTFIT;
	}
	
	else								
	{
		g_ImageViewMode = IMAGE_LARGE;
	}
	
	OpenImage(image, index, count);
}

var g_SlideShowPlaying = false;

function IsSlideShowPlaying()
{
	return g_SlideShowPlaying;
}

function ShowLoadingMessage(msg)
{
	if(g_HideLoadingMessage)	return true;

	else if(g_HideLoadingMessageDuringSlideshow && IsSlideShowPlaying())	return true;
	
	if(!msg || msg == "")	msg = "Loading Photo...";
	
	document.getElementById("p_loadingmessage").innerHTML = msg;
	
	document.getElementById("lnk_prev").blur();
	document.getElementById("lnk_next").blur();
	
	//document.getElementById("currimage").style.opacity = "0.75";
	
	var LoadingLayer = document.getElementById("div_photoloading");
	var LoadingMessage = document.getElementById("div_loadingmessage");
	
	var availwidth;
	var availheight;
	
	if(g_Browser.explorer)	
	{
		availwidth = document.body.clientWidth;
		availheight = document.body.clientHeight;
		
		LoadingLayer.style.filter = "alpha(opacity=75)";
		
		//document.getElementById("currimage").style.filter = "alpha(opacity=75)";
	}
	
	else
	{
		availwidth = window.innerWidth;
		availheight = window.innerHeight;
	}
	
	var centerleft = (availwidth - LoadingLayer.clientWidth)  / 2;
	
	var fullscreen_buffer = 0;
		
	if(FullScreenMode()) fullscreen_buffer = document.getElementById("td_fullscreenmode").clientHeight;
	
	<?php  if($THUMBNAIL_LOCATION == "TOP")
	{
		?>
		var centertop = <?php  print($THUMBNAILS_HEIGHT - 1); ?> + fullscreen_buffer;
		
		if(!SupportedBrowser())		
		{
			centertop += 20;
		}
		<?php  }
	else if($THUMBNAIL_LOCATION == "BOTTOM")
	{
		?>
		var centertop = -1;
		<?php  }
	?>
	
	if(LoadingLayer)
	{
		LoadingLayer.style.left = centerleft;
		LoadingLayer.style.top = centertop;
		
		LoadingMessage.style.left = LoadingLayer.style.left;
		LoadingMessage.style.top = LoadingLayer.style.top;
		
		LoadingLayer.style.visibility = "visible";
		LoadingMessage.style.visibility = "visible";
	}
}

function HideLoadingMessage()
{
	var LoadingLayer = document.getElementById("div_photoloading");
	var LoadingMessage = document.getElementById("div_loadingmessage");
	
	if(LoadingLayer)
	{
		LoadingLayer.style.visibility = "hidden";
		LoadingMessage.style.visibility = "hidden";
	}
	
	//document.getElementById("currimage").style.opacity = "1.0";
	
	//if(g_Browser.explorer)	document.getElementById("currimage").style.filter = "alpha(opacity=100)";
}

function OpenImage(image, index, count, degrees, quality)
{
	try
	{
		if(parent && parent.NotifyImageUnLoaded)	parent.NotifyImageUnLoaded();
	}
	catch(e)
	{
	}

	g_Degrees = 0;
	g_Index = index;
	g_Count = count;
	
	hidecontextmenu();	
	hideproperties();
	
	ShowLoadingMessage();
	
	HideHints();
	
	HideCaption();
	
	SetSelectedDropDown(index, count);
	
	SetSelectedThumnail(index, count);

	GetSelectedCaption(index);
	
	LoadImage(image, degrees, quality, index, count);
}

function LoadImage(image, degrees, quality, index, count)
{
	UpdatePageSize();
		
	var width = document.getElementById("td_currimage").clientWidth;
	var height = g_AvailHeight; 
	
	if(g_Browser.explorer)	height -= 15;
	
	if(!degrees)	degrees = 0;
	if(!quality)	quality = 90;
	
	g_Degrees += degrees;
	
	while(g_Degrees > 360)	g_Degrees = g_Degrees - 360;
	
	var sApplication = "php/image.php?";
	
	var sImageParam = "image=" + image;
	
	var sDegrees = "&degrees=" + g_Degrees;
	
	var sScale = "&scale=yes";
	
	var sWidth = "&width=" + width;
	
	var sHeight = "&height=" + height;
	
	var sQuality = "&quality=<?php print($DM_PHOTOALBUM_PHOTO_QUALITY); ?>";
	
	var sRound = "&rounding=nearest";
	
	var sAspect = "&maintain_aspect=yes";
	
	var sTitle = image.substr((image.lastIndexOf("/") + 1), image.length);

	if(g_ImageViewMode == IMAGE_LARGE)
	{
		var sWidth = "&width=" + (width - 50);
		sHeight = "&height=" + (width - 50);
		
		var divheight = g_AvailHeight;
		
		if(g_Browser.explorer)	divheight -= 15;
		
		document.getElementById("div_currimage").style.height = divheight + "px";
		document.getElementById("div_currimage").style.overflow = "auto";
	
		if(g_ClientHeight == null)	g_ClientHeight = height;
	}
	
	else	
	{
		document.getElementById("div_currimage").style.overflow = "visible";
	}

	if(height <= 300)		
	{
		sHeight = "&height=" + g_AvailHeight;
	}
	
	var style = "class=\"img_noborder\"";
	
	if(ImageType(image) == "JPG" || "JPEG")		style = "class=\"img_border\"";

	if(g_Browser.Opera)
	{
		document.getElementById("td_currimage").innerHTML = "<img name=\"currimage\" id=\"currimage\" src=\"" + 
				sApplication + sImageParam + sDegrees + sScale + sWidth + sHeight + sQuality + sAspect + sRound + 
				"\" " + style + " onload=\"ImageLoaded(" + index + ", " + count + ");\">";
	}
	else
	{
		document.getElementById("td_currimage_preload").innerHTML = "<img name=\"currimage_preload\" id=\"currimage_preload\" src=\"" + 
				sApplication + sImageParam + sDegrees + sScale + sWidth + sHeight + sQuality + sAspect + sRound + 
				"\" " + style + " onload=\"ImageLoaded(" + index + ", " + count + ");\">";
	}

	var anchor_open = "";
	var anchor_close = "";

	if(isUrl(g_PhotoLinks[Number(document.getElementById('jumptolist').selectedIndex)]))
	{
		anchor_open  = "<a href='" + g_PhotoLinks[Number(document.getElementById('jumptolist').selectedIndex)] + "' target='<?php print($DM_ALBUMS_EXTERNAL_LINK_TARGET); ?>'>";
		anchor_close = "</a>";
	}
	
	document.getElementById("td_currimage").innerHTML = anchor_open + "<img name=\"currimage\" id=\"currimage\" src=\"" + document.getElementById("currimage").src + "\" " + style + "\"/>" + anchor_close;
}

function ImageLoaded(index, count)
{		
	if(!g_DocumentLoaded)	LoadPage();
	
	try
	{
		if(parent && parent.NotifyImageLoaded)	parent.NotifyImageLoaded();
	}
	catch(e)
	{
	}

	if(!g_Browser.Opera)	FadeSwap();
	
	ShowBrowsingHints();
	
	HideLoadingMessage();
	
	ShowCaption();
	
	if(g_SlideshowTimeout != null)	g_SlideshowTimeout = setTimeout("Slideshow()", SLIDESHOW_DELAY);
}

function FadeSwap()
{
	Fade('currimage', 100, 0, 500, "SwapImageSources()");
}

function SwapImageSources()
{
	if(!g_Browser.explorer)
	{
		document.getElementById("currimage").width 	= document.getElementById("currimage_preload").width;
		document.getElementById("currimage").height = document.getElementById("currimage_preload").height;
	}

	document.getElementById("currimage").src = document.getElementById("currimage_preload").src;

	// add a delay to compensate for Safari's slow image updating 
	var delay = 100;
	if(g_Browser.Safari || g_Browser.Chrome)	delay = 1500;
	
	setTimeout("Fade('currimage', 0, 100, 500);", delay);
}

function Fade(id, opacStart, opacEnd, millisec, callback) 
{
	var speed = Math.round(millisec / 100);
    var timer = 0;

	if(opacStart > opacEnd) 
	{
        for(i = opacStart; i >= opacEnd; i--) 
        {
            if(i == opacEnd)	setTimeout("SetOpacity(" + i + ",'" + id + "','" + callback + "')",(timer * speed));
            else 				setTimeout("SetOpacity(" + i + ",'" + id + "',null)",(timer * speed));
            
            timer++;
        }
    } 
    
    else if(opacStart < opacEnd) 
    {
        for(i = opacStart; i <= opacEnd; i++)
		{
        	if(i == opacEnd)	setTimeout("SetOpacity(" + i + ",'" + id + "','" + callback + "')",(timer * speed));
            else 				setTimeout("SetOpacity(" + i + ",'" + id + "',null)",(timer * speed));
            
            timer++;
        }
    }
}

//change the opacity for different browsers
function SetOpacity(opacity, id, callback) 
{		
	var object = document.getElementById(id).style;

    object.opacity = (opacity / 100);
    object.MozOpacity = (opacity / 100);
    object.KhtmlOpacity = (opacity / 100);
    object.filter = "alpha(opacity=" + opacity + ")";

    if(callback != null)	setTimeout("eval(" + callback + ");", 5);
} 

function ImageType(image)
{
	return image.substr(image.lastIndexOf(".") + 1, image.length).toUpperCase();
}

function ShowCaption()
{
	return;
}

function HideCaption()
{
	document.getElementById("tbl_caption").style.visibility = "hidden";
	SetCaption("");
	return;
}

function SetCaptionPosition()
{
	var oCaption = document.getElementById("tbl_caption");
	
	oCaption.style.visibility = "visible";
}

function GetSelectedCaption(index)
{
	if(g_CaptionId)		ajax_KillAsyncCall(g_CaptionId);
	
	g_CaptionId = ajax_MakeAsyncCall("php/caption.php?slide=" + index + "&currdir=<?php  print("$HOME_DIR$currdir"); ?>");
}

function SaveCaption(url)
{	if(g_DisableInlineEdit)	return;
	if(!document.getElementById("displaycaption"))	return;
	
	g_CaptureKeyStrokes = true;
	
	if(!g_ParentWritable) return;
	
	var app = "php/caption.php?captionsave=yes&slide=" + Number(Number(document.getElementById('jumptolist').selectedIndex) + 1) + "&currdir=<?php  print("$HOME_DIR$currdir"); ?>&displaycaption=" + escape(document.getElementById("displaycaption").value);
	
	SetCaption(document.getElementById("displaycaption").value);

	g_CaptionId = ajax_MakeAsyncCall(app);
}

function EditCaption()
{	if(g_DisableInlineEdit)	return;	
	var saveapp = "javascript:SaveCaption();"
	var save = "&nbsp;&nbsp;[ <a href=\"" + saveapp + "\" class=\"editcaption\">save</a> ]";

	var contents = g_Browser.Encode(document.getElementById("td_caption").innerHTML);
	
	document.getElementById("td_caption").innerHTML = '<input type="text" name="displaycaption" id="displaycaption" value="' + contents + '" class="caption" onblur="' + saveapp + '"  onKeyPress="if(event.keyCode == 13) ' + saveapp + ';" onFocus="g_CaptureKeyStrokes = false;" onBlur="g_CaptureKeyStrokes = true;">';
	document.getElementById("td_crud").innerHTML = save;
	
	document.getElementById("displaycaption").focus();
	document.getElementById("displaycaption").select();
}

function SetCaption(contents)
{
	contents = g_Browser.Decode(contents);
	
	var edit = "&nbsp;[ <a href=\"javascript:EditCaption();\" class=\"editcaption\">edit</a> ]";

	var add = "[ <a href=\"javascript:EditCaption();\" class=\"editcaption\">add caption</a> ]&nbsp;";

	if(!g_ParentWritable)
	{
		edit = "";
		add = "";
	}
		
	document.getElementById("td_caption").innerHTML = contents;
	
	if(contents == "")
	{
		document.getElementById("td_crud").innerHTML = add;
	}
	
	else
	{
		document.getElementById("td_crud").innerHTML = edit;
	}
}

function SaveTitle()
{	if(g_DisableInlineEdit)	return;	
	if(!document.getElementById("displayalbumtitle"))	return;
	
	g_CaptureKeyStrokes = true;
		
	var edit = "&nbsp;<a href=\"javascript:EditTitle();\" class=\"editcaption\">[ edit ]</a>";
	
	if(!g_ParentWritable)	edit = "";
	
	var app = "php/caption.php?tilesave=yes&slide=" + Number(Number(document.getElementById('jumptolist').selectedIndex) + 1) + "&currdir=<?php  print("$currdir"); ?>&displaytitle=" + escape(document.getElementById("displayalbumtitle").value);
		
	document.title = "<?php  print($TITLE_PREFIX); ?>: " + document.getElementById("displayalbumtitle").value;
	document.getElementById("td_albumtitle").innerHTML = "" + document.getElementById("displayalbumtitle").value;
	document.getElementById("td_albumtitle_edit").innerHTML = edit;

	if(g_ParentWritable)	g_TitlenId = ajax_MakeAsyncCall(app);
}

function EditTitle()
{	if(g_DisableInlineEdit)	return;
	var saveapp = "javascript:SaveTitle();"
	var save = "&nbsp;<a href=\"" + saveapp + "\" class=\"editcaption\">[ save ]</a>";
	
	if(!g_ParentWritable)	save = "";

	var contents = g_Browser.Encode(document.getElementById("td_albumtitle").innerHTML).replace(/^<?php  print($TITLE_PREFIX); ?>:\s/, "");
	
	document.getElementById("td_albumtitle").innerHTML = '<input type="text" name="displayalbumtitle" id="displayalbumtitle" value="' + contents + '" class="edittitle" onblur="' + saveapp + '"  onKeyPress="if(event.keyCode == 13) ' + saveapp + ';" onFocus="g_CaptureKeyStrokes = false;" onBlur="g_CaptureKeyStrokes = true;">';
	
	document.getElementById("displayalbumtitle").focus();
	document.getElementById("displayalbumtitle").select();
	
	document.getElementById("td_albumtitle_edit").innerHTML = save;
}

function SetSelectedDropDown(index, count)
{
	var oDropDownList = document.getElementById("jumptolist");
	var oDropDownLabel = document.getElementById("postion_label");
	
	oDropDownList.selectedIndex = index - 1;
	
	for(var i = 0; i < count; i++)
	{
		oDropDownList.options[i].className = "";
	}
	
	oDropDownList.options[oDropDownList.selectedIndex].className = "selected";
	
	oDropDownLabel.innerHTML = oDropDownList.selectedIndex + 1;
}

function GetSelectedDropDown()
{
	return document.getElementById("jumptolist").options[document.getElementById("jumptolist").selectedIndex].text;
}

function SetSelectedThumnail(index, count)
{
	var oFrameElement;
	
	if(g_Browser.explorer)	oFrameElement = document.getElementById("thumbnailframe").contentWindow.document;
	
	else					oFrameElement = document.getElementById("thumbnailframe").contentDocument;
	
	var elem = "";
	
	for(var i = 1; i <= count; i++)
	{
		elem = "thumbid_" + i;
		
		if(oFrameElement.getElementById(elem))	oFrameElement.getElementById(elem).className = "thumb";
	}
	
	elem = "thumbid_" + (index);
	
	if(oFrameElement.getElementById(elem))		oFrameElement.getElementById(elem).className = "selectedthumb";
	
	SetSelectedThumnailVisible(index, count);
}

function Previous()
{
	var imageindex = document.getElementById('jumptolist').selectedIndex - 1;
	
	if(imageindex < 0)	imageindex += document.getElementById('jumptolist').length;
	
	var thumbnailindex = imageindex + 1;
	
	SetSelectedThumnailVisible(thumbnailindex, Number(document.getElementById('jumptolist').length));

	g_ImageViewMode = IMAGE_BESTFIT;
	
	OpenImage('<?php  print("$HOME_DIR$currdir"); ?>' + document.getElementById('jumptolist').options[Number(imageindex)].text, Number(thumbnailindex), Number(document.getElementById('jumptolist').length));
}

function Next()
{	
	var imageindex = document.getElementById('jumptolist').selectedIndex + 1;
	
	if(imageindex >= document.getElementById('jumptolist').length)	imageindex -= document.getElementById('jumptolist').length;
	
	var thumbnailindex = imageindex + 1;
	
	SetSelectedThumnailVisible(thumbnailindex, Number(document.getElementById('jumptolist').length));

	g_ImageViewMode = IMAGE_BESTFIT;
	
	OpenImage('<?php  print("$HOME_DIR$currdir"); ?>' + document.getElementById('jumptolist').options[Number(imageindex)].text, Number(thumbnailindex), Number(document.getElementById('jumptolist').length));
}

function Slideshow()
{
	Next();
}

function StartStopSlideshow(abort)
{
	var oSlideshowIconImage = document.getElementById("img_context_slideshow");

	if(abort || g_SlideshowTimeout != null)						
	{
		g_SlideShowPlaying = false;

		clearTimeout(g_SlideshowTimeout);
		g_SlideshowTimeout = null;
		
		oSlideshowIconImage.src = "ui/startslideshow.png";

		try
		{
			if(parent && parent.document.getElementById("slideshowcontrol"))	
			{
				parent.document.getElementById("slideshowcontrol").className="ui_slideshow";
			}
		}
		catch(e)
		{
		}
	}
	
	else
	{
		g_SlideShowPlaying = true;
		
		Next();
		
		g_SlideshowTimeout = 0;
		
		oSlideshowIconImage.src = "ui/stopslideshow.png";

		try
		{
			if(parent && parent.document.getElementById("slideshowcontrol"))	
			{
				parent.document.getElementById("slideshowcontrol").className="ui_slideshowstop";
			}
		}
		catch(e)
		{
		}
	}
	
	try
	{
		if(parent && parent.ToggleSlideShowButton)	parent.ToggleSlideShowButton();
	}
	catch(e)
	{
	}
}

var g_HideHintsTimeout = null;

function ShowBrowsingHints(e)
{
	<?php if($DM_SHOW_NAVIGATION_HINTS == "false")	print("return;");?>
	
	if(!g_DocumentLoaded) return;
	
	if(document.getElementById("div_directoryproperties").style.visibility == "visible")
	{
		HideHints();
		return;
	}
	
	if(g_HideHintsTimeout != null)	clearTimeout(g_HideHintsTimeout);	
	
	g_HideHintsTimeout = setTimeout("HideHints()", 5000);
	
	if(document.getElementById("currimage").clientHeight < 200)	return;
	
	var screenwidth, screenheight, x, y;
	
	var nexthintbuffer = 0; //31;
	var prevhintbuffer = 0; //-9;
	var verticalbuffer = 0; //-20;
	var padding = 0;
	var opacity = "30";
	var top, left;
	var oNextHint = document.getElementById("div_nexthint");
	var oPrevHint = document.getElementById("div_prevhint");
	var oImage = document.getElementById("currimage");
	var oImageRow = document.getElementById("tr_currimage");
	var oHint = null;
	
	x = g_MouseX;
	y = g_MouseY;
	
	screenwidth = g_Browser.Width();
	screenheight = g_Browser.Height();
	
	var oImagePosition = g_Browser.GetPosition(oImage);
	
	if(x > ((screenwidth + 150)/2) && x <= ((oImagePosition[0] + oImage.clientWidth) + 10))		
	{
		if(g_Browser.DOM())	top = oImageRow.offsetTop;
		else				top = oImageRow.offsetTop;
		
		left = (oImage.offsetLeft + oImage.clientWidth) - (oNextHint.clientWidth - nexthintbuffer);
		
		padding = Math.floor((g_AvailHeight - document.getElementById("img_hint_next").clientHeight) / 2);
	
		oHint = oNextHint;
		oHintLink = document.getElementById("lnk_next");
		
		document.getElementById("div_prevhint").style.visibility = "hidden";
		
	}
	
	else if(x < ((screenwidth - 150)/2) && x >= (oImagePosition[0] - 10))					
	{
		if(g_Browser.DOM())	top = oImageRow.offsetTop;
		else				top = oImageRow.offsetTop;
		
		left = oImage.offsetLeft;
		
		padding = Math.floor((g_AvailHeight - document.getElementById("img_hint_prev").clientHeight) / 2);
	
		oHint = oPrevHint;
		oHintLink = document.getElementById("lnk_prev");
		
		document.getElementById("div_nexthint").style.visibility = "hidden";
	}
	
	else
	{
		return HideHints();
	}
	
	if(y > (oImagePosition[1] + oImage.clientHeight) || y < oImagePosition[1])		
	{
		return HideHints();
	}
	
	if(g_Browser.explorer)	oHint.style.filter = "alpha(opacity=" + opacity + ")";
	
	else 					
	{
		oHint.style.opacity = "0." + opacity + "";
	}
	
	oHint.style.left = left;
	oHint.style.top = top;
	
	oHintLink.style.paddingTop = padding;
	oHintLink.style.paddingBottom = padding;
		
	oHint.style.visibility = "visible";
}

function HideHints()
{
	document.getElementById("div_nexthint").style.visibility = "hidden";
	document.getElementById("div_prevhint").style.visibility = "hidden";
}

function ajax_MakeAsyncCall(url)
{
	var sElemId = "iframe_" + window.length;
	
	var oAjaxNode = document.createElement("div");
	
	oAjaxNode.id = "div" + sElemId;
	
	oAjaxNode.innerHTML = "<iframe style='position: absolute; visibility: hidden; top: 0px; left: 0px;' id='" + sElemId + "' width='1' height='1' frameborder='0' src='" + url + "'></iframe>";
	
	document.body.appendChild(oAjaxNode);
	
	return sElemId;
}

function ajax_KillAsyncCall(id)
{
	var oAjaxFrame;
	
	if(g_Browser.explorer)	oAjaxFrame = document.getElementById(id).contentWindow.document;
	
	else					oAjaxFrame = document.getElementById(id).contentDocument;
	
	oAjaxFrame.location.replace("thumbs.php");
		
	document.body.removeChild(document.getElementById('div' + id));
}

function UpdatePageSize()
{
	//if(!g_ScreenHeight)
	//{		
		g_ScreenHeight = g_Browser.Height();
	//}
	
	//if(!g_AvailHeight)
	//{
		var fullscreen_buffer = 0;
		
		if(FullScreenMode()) fullscreen_buffer = document.getElementById("td_fullscreenmode").clientHeight;
		
		g_AvailHeight = (g_ScreenHeight ? (g_ScreenHeight - (<?php  print($MARGINHEIGHTS); ?> + fullscreen_buffer)) : 500); //update
	//}
}

function LoadPage()
{
	var oBrowser = new Browser();

	document.onmousemove = mouseMove;
	if (oBrowser.ns40) document.captureEvents(Event.MOUSEMOVE);
		
	document.onmousedown = mouseDown;
	if (oBrowser.ns40) document.captureEvents(Event.MOUSEDOWN);
	
	document.onmouseup = mouseUp;
	if (oBrowser.ns40) document.captureEvents(Event.MOUSEUP);
	
	g_DocumentLoaded = true;

	UpdatePageSize();

	<?php if(strtoupper($DM_PHOTOALBUM_SLIDESHOW_AUTOPLAY) == "TRUE") print('setTimeout("StartStopSlideshow()", SLIDESHOW_DELAY);');?>
}

function UnloadPage()
{
	g_DocumentLoaded = false;
	
	try
	{
		if(parent && parent.RefreshOpener)	parent.RefreshOpener("<?php print($currdir);?>");
	}
	catch(e)
	{
	}
}	

function OpenFullScreen(app, options)
{
	window.top.location = app + "&byfile=yes&file=" + GetSelectedDropDown() + options;
}

function ExitFullScreen()
{
	history.back();
}

function FullScreenMode()
{
	if(window.self == window.top)	return true;
	else							return false;
}

var g_MouseX;
var g_MouseY;

function mouseUp(e)
{
	__mouseUp();
	
	ShowBrowsingHints(e);
	
	return true;
}

function mouseMove(e)
{
	__mouseMove(e);
	
	ShowBrowsingHints(e);
	
	var oBrowser = new Browser();
	
	if(oBrowser.netscape)
	{
		x = e.pageX;
		y = e.pageY;
	}
	
	else
	{
		x = event.x + document.body.scrollLeft;
		y = event.y + document.body.scrollTop;
	}
	
	g_MouseX = x;
	g_MouseY = y;
	
	return true;
}

function mouseDown(e)
{
	var iMenuWidth = 0;

	var iMenuHeight = 0;

	var iLayerX = 0;
	var iLayerY = 0;
	
	iMenuWidth = document.getElementById("contextmenu").clientWidth;
	iMenuHeight = document.getElementById("contextmenu").clientHeight;	
	
	iLayerX = document.getElementById("contextmenu").offsetLeft
	iLayerY = document.getElementById("contextmenu").offsetTop
		
	if(!((x >= iLayerX && x <= (iLayerX + iMenuWidth)) && 
         (y >= iLayerY && y <= (iLayerY + iMenuHeight))))
	{	
		hidecontextmenu();
	}
	
	return true;
}

function getMenuY(oMenu, nMenuY)
{
	if(nMenuY > (g_Browser.Height() - oMenu.offsetHeight))
	{
		return ((nMenuY - oMenu.offsetHeight) + 12);
	}
	
	else	return nMenuY;
}

function getMenuX(oMenu, nMenuX)
{
	if(nMenuX > (g_Browser.Width() - oMenu.offsetWidth))
	{
		return ((nMenuX - oMenu.offsetWidth) + 12);
	}
	
	else	return nMenuX;
}

function download()
{
	window.location = "?download=yes&file=" + document.getElementById("jumptolist").options[Number(document.getElementById("jumptolist").selectedIndex)].text + "&currdir=" + g_CurrDir;
	
	hidecontextmenu();
}

function context(x,y)
{
	if(g_Browser.ie60) return;
	
	hideproperties();
	
	var contextMenu = document.getElementById("contextmenu");
	
	contextMenu.style.left = getMenuX(contextMenu, x);;
	contextMenu.style.top = getMenuY(contextMenu, y);
	
	contextMenu.style.visibility = "visible";
}

function hidecontextmenu()
{
	var contextMenu = document.getElementById("contextmenu");
	contextMenu.style.visibility = "hidden";
}

function properties()
{
	if(g_Browser.ie60) return;
	
	HideHints();
	
	StartStopSlideshow(true);
	
	MakeDraggable(document.getElementById("tbl_directorypropertiesheader"), document.getElementById("div_directoryproperties"));
	
	hidecontextmenu()
	
	var properties = document.getElementById("div_directoryproperties");
	
	var width = document.body.offsetWidth;
	var height = document.body.offsetHeight;
	
	var left = (Math.floor((width - properties.clientWidth) / 2) + 10) + "px";
	var top = (Math.floor((height - properties.clientHeight) / 2) - 10) + "px";

	properties.style.top = top;
	properties.style.left = left;
	
	if(g_Browser.explorer)	
	{
		properties.style.filter = "alpha(opacity=90)";
	}
	
	else 					
	{
		properties.style.opacity = "0.90";
	}
	
	document.getElementById("div_propertiescontent").innerHTML = "<?php  print($DEFAULT_PROPERTIES_HTML); ?>";
	
	var app = "php/EXIF.php?properties=<?php  print($HOME_DIR); ?>" + g_CurrDir + GetSelectedDropDown();
	
	g_PropertiesId = ajax_MakeAsyncCall(app);
	
	properties.style.visibility = "visible";
}

function hideproperties()
{
	var properties = document.getElementById("div_directoryproperties");
	properties.style.visibility = "hidden";
	properties.style.left = -1000;
	properties.style.top = -1000;
}

function UpdateProperties(html)
{
	document.getElementById("div_propertiescontent").innerHTML = g_Browser.Decode(html);
}	

if(g_Browser.explorer)	document.onkeydown = function (){

	if(!g_CaptureKeyStrokes)	return true;
 
	if(g_CaptureKeyStrokes && event.keyCode == g_LeftArrow) Next(); 
	if(g_CaptureKeyStrokes && event.keyCode == g_RightArrow) Previous(); 
} 

else					document.onkeydown = function (event){ 

	if(!g_CaptureKeyStrokes)	return true;
	
	if(g_CaptureKeyStrokes && event.keyCode == g_LeftArrow) Next(); 
	if(g_CaptureKeyStrokes && event.keyCode == g_RightArrow) Previous(); 
	
	if(event.keyCode == g_LeftArrow || event.keyCode == g_RightArrow) return false;
} 

function FireOnLoadEvents()
{

}

//-->
</script>

<?php  

$THUMBNAILS_HTML = '<tr id="tr_thumbnails" height="' . ($THUMBNAILS_HEIGHT - 2). '">';
$THUMBNAILS_HTML .= '<td id="td_thumbnails" align="center">';
				
if($IS_SAFARI)
{
	$THUMBNAILS_HTML .= '<iframe id="thumbnailframe" scrolling="auto" width="100%" height="' . (($THUMBNAILS_HEIGHT - 2) + 20) . '" frameborder="0" src="php/thumbnails.php?slide=' . "$slide&currdir=$currdir&HOME_DIR=$HOME_DIR&THUMBNAIL_SIZE=$THUMBNAIL_SIZE&THUMBNAIL_PADDING=$THUMBNAIL_PADDING&DM_ALBUMS_EXTERNAL_CSS=$DM_ALBUMS_EXTERNAL_CSS" . '"></iframe>';
}
else
{
	$THUMBNAILS_HTML .= '<iframe id="thumbnailframe" scrolling="no" width="100%" height="' . ($THUMBNAILS_HEIGHT - 2) . '" frameborder="0" src="php/thumbnails.php?slide=' . "$slide&currdir=$currdir&HOME_DIR=$HOME_DIR&THUMBNAIL_SIZE=$THUMBNAIL_SIZE&THUMBNAIL_PADDING=$THUMBNAIL_PADDING&DM_ALBUMS_EXTERNAL_CSS=$DM_ALBUMS_EXTERNAL_CSS" . '"></iframe>';
}
										
$THUMBNAILS_HTML .= '</td>';
$THUMBNAILS_HTML .= '</tr>';

?>

</head>
<body topmargin="<?php  print($TOPMARGIN); ?>" leftmargin="<?php  print($LEFTMARGIN); ?>" rightmargin="<?php  print($RIGHTMARGIN); ?>" bottommargin="<?php  print($BOTTOMMARGIN); ?>" onload="CheckScrollWidth(); SetCaptionPosition(); FireOnLoadEvents();" onunload="UnloadPage();" onResize="SetScrollButtons(); SetCaptionPosition();" OnContextMenu="context(x, y); return false;">
<table border="0" cellpadding="0" cellspacing="0" width="100%" <?php  if(!$IS_SAFARI) print("height=\"100%\""); ?> class="tbl_photoalbum">

<tr>
<td id="td_fullscreenmode" valign="middle" align="center" style="display: none;"><table cellpadding="3" cellspacing="0" border="0" width="100%" height="28"><tr height="28"><td align="left" valign="top"><table cellpadding="0" cellspacing="0" border="0" width="100%" height="28"><tr><td width="20"><img src="ui/dm_tag_16x16.png" align="left" style="padding-left: 1px;"></td><td nowrap><b><?php  print("$ALBUM_TITLE"); ?></b></td><td width="400" align="right" valign="middle" nowrap><table cellpadding="3" cellspacing="0" border="0"><tr><td align="right" valign="middle"><a href="javascript:ExitFullScreen();" title="Exit FullScreen Mode">Exit FullScreen Mode</a></td><td valign="middle"><a href="javascript:ExitFullScreen();" title="Exit FullScreen Mode"><img src="ui/close_box.gif" align="right" border="0" hspace="0" style="padding-top: 1px"></a></td></tr></table></td></tr></table></td></tr></table></td> 
</tr>

<?php  If($THUMBNAIL_LOCATION == "TOP")	print($THUMBNAILS_HTML); ?>

<tr id="tr_currimage" height="100%">
<td id="td_image" valign="middle" align="center"><div id="div_currimage"><table cellpadding="0" cellspacing="0" border="0" width="100%" height="100%"><tr height="100%"><td valign="middle" align="center" id="td_currimage"><img name="currimage" id="currimage" src="ui/spacer.gif" border="0" class="noborder"></td></tr></table></div></td> 
</tr>

<tr height="<?php  print($CAPTION_HEIGHT); ?>" id="tr_caption_photocount">
<td align="center">
<table cellpadding="1" cellspacing="0" border="0" id="tbl_caption">
<tr valign="middle" id="tr_caption">
<td align="center" valign="middle" id="td_caption" class="td_caption" onmouseover="this.style.cursor='default'" onmouseout="this.style.cursor='default'"></td><td align="center" class="editcaption" nowrap valign="middle" id="td_crud"></td>
</tr>
<tr id="tr_photocount"><td align="center" class="td_photocount" colspan="2">(Photo <span id="postion_label"><?php  print("$COUNT_CURR"); ?></span> of <?php  print("$COUNT"); ?>)</td></tr>
</table>
</td>
</tr>

<?php  If($THUMBNAIL_LOCATION == "BOTTOM")	print($THUMBNAILS_HTML); ?>

</table>

<div id="div_prevhint" onclick="this.blur();"><table cellpadding="0" cellspacing="0" border="0""><tr><td align="center" valign="middle"><a href="javascript:void(0);" onClick="setTimeout('Previous();', 5);" id="lnk_prev" class="hint"><img id="img_hint_prev" src="ui/hint_prev.png" border="0" title="Previous Image"></a></td></tr></table></div>

<div id="div_nexthint" onclick="this.blur();"><table cellpadding="0" cellspacing="0" border="0"><tr><td align="center" valign="middle"><a href="javascript:void(0);" onClick="setTimeout('Next();', 5);" id="lnk_next" class="hint"><img id="img_hint_next" src="ui/hint_next.png" border="0" title="Next Image"></a></td></tr></table></div>

<div id="div_photoloading"></div>

<div id="div_loadingmessage"><center><table cellpadding="5" cellspacing="0" border="0" height="100%"><tr height="100%"><td align="center"><img src="ui/throbber.gif" border="0"></td><td align="center"><p id="p_loadingmessage" class="loadingmessage">Loading Photo...</p></td></tr></table></center></div>

<div id="div_moreslides_left"><?php print($html_scrollleft);?></div>

<div id="div_moreslides_right"><?php print($html_scrollright);?></div>

<div style="position: absolute; display: none; left: 0px; top: 0px"><form action="" onSubmit="return false;" id="frm_jumpto"><?php  print("$JUMPTO_NAME"); ?></form></div>

<div id="contextmenu"><table id="table_contextmenu_container" border="0" cellpadding="0" cellspacing="0" class="menu" width="125"><tr><td>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="menu">

<tr class="menuborder">
<td colspan="6"><img src="ui/spacer.gif"></td>
</tr>

<tr class="context_option">
<td width="1" class="menuborder"><img src="ui/spacer.gif"></td>
<td width="1"><img src="ui/spacer.gif"></td>
<td width="14"><img src="ui/moreslides_left.png" hspace="2" vspace="2"></td>
<td align="left" nowrap><a href="javascript:void(0);" onClick="HideHints(); Previous();" class="context_option">Previous</a></td>
<td width="1"><img src="ui/spacer.gif"></td>
<td width="1" class="menuborder"><img src="ui/spacer.gif"></td>
</tr>

<tr class="context_option">
<td width="1" class="menuborder"><img src="ui/spacer.gif"></td>
<td width="1"><img src="ui/spacer.gif"></td>
<td width="14"><img src="ui/moreslides_right.png" hspace="2" vspace="2"></td>
<td align="left" nowrap><a href="javascript:void(0);" onClick="HideHints(); Next();" class="context_option">Next</a></td>
<td width="1"><img src="ui/spacer.gif"></td>
<td width="1" class="menuborder"><img src="ui/spacer.gif"></td>
</tr>

<tr>
<td colspan="6" class="menuborder"><img src="ui/spacer.gif"></td>
</tr>
<?php  if(strtoupper($DM_PHOTOALBUM_ALLOWDOWNLOAD) == "TRUE")
{
?>
	<tr class="context_option">
	<td width="1" class="menuborder"><img src="ui/spacer.gif"></td>
	<td width="1"><img src="ui/spacer.gif"></td>
	<td width="14"><img src="ui/download.gif" hspace="2" vspace="2"></td>
	<td align="left" nowrap><a href="javascript:void(0);" onClick="download();" class="context_option">Download</a></td>
	<td width="1"><img src="ui/spacer.gif"></td>
	<td width="1" class="menuborder"><img src="ui/spacer.gif"></td>
	</tr>
<?php  }
?>
<tr class="context_option">
<td width="1" class="menuborder"><img src="ui/spacer.gif"></td>
<td width="1"><img src="ui/spacer.gif"></td>
<td width="14"><img src="ui/startslideshow.png" id="img_context_slideshow" hspace="2" vspace="2"></td>
<td align="left" nowrap><a href="javascript:void(0);" onClick="StartStopSlideshow(); hidecontextmenu();" class="context_option">Slide Show</a></td>
<td width="1"><img src="ui/spacer.gif"></td>
<td width="1" class="menuborder"><img src="ui/spacer.gif"></td>
</tr>

<tr>
<td colspan="6" class="menuborder"><img src="ui/spacer.gif"></td>
</tr>

<tr class="context_option">
<td width="1" class="menuborder"><img src="ui/spacer.gif"></td>
<td width="1"><img src="ui/spacer.gif"></td>
<td width="14"><img src="ui/spacer.gif" width="14" height="15" hspace="2" vspace="2"></td>
<td align="left" nowrap><a href="javascript:void(0);" onClick="properties();"class="context_option">Photo Properties</a></td>
<td width="1"><img src="ui/spacer.gif"></td>
<td width="1" class="menuborder"><img src="ui/spacer.gif"></td>
</tr>

<tr>
<td colspan="6" class="menuborder"><img src="ui/spacer.gif"></td>
</tr>
</table></td></tr></table>
</div>

<!-- PROPERTIES WINDOW -->

<div id="div_directoryproperties" onmouseover="this.style.cursor='default'" oncontextmenu="g_SHOW_DEFAULT_CONTEXT = true;">

<table cellpadding="5" cellspacing="0" border="0" height="100%" width="100%">
<tr>
<td align="left" valign="top">

<table cellpadding="0" cellspacing="0" border="0" width="100%" id="tbl_directorypropertiesheader" onmouseover="this.style.cursor='move'"  onmouseout="this.style.cursor='default'">
<tr>
<td width="20"><img src="http://dutchmonkey.com/public/resources/dm_tag_16x16.png" align="left"></td>
<td nowrap><b><span id="photo_name">Photo</span> Properties</b></td>
<td align="right" valign="middle"><a href="javascript:hideproperties();" onMouseUp="hideproperties();" onmouseover="this.style.cursor='hand'" onmouseover="this.style.cursor='default'" title="Close Properties"><img src="ui/close_box.gif" align="right" border="0" hspace="0"></a></td>
</tr>
</table>

<div id="div_propertiescontent" style="height: 200px; overflow: auto;" onmouseover="this.style.cursor='default'">
<?php  print($DEFAULT_PROPERTIES_HTML); ?>
</div>
</td>
</tr>

<tr>
<td align="left" valign="top">

<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
<td align="center"><a href="http://www.spreadfirefox.com/?q=affiliates&amp;id=0&amp;t=219" target="_newWindow"><img border="0" alt="Firefox 2" title="Firefox 2" src="ui/getfirefox.png" align="absmiddle"/></a>
</td>
</tr>
</table>
</td>
</tr>

</table>

</div>

<div id="div_currimage_preload"><table cellpadding="0" cellspacing="0" border="0" width="100%" height="100%"><tr height="100%"><td valign="middle" align="center" id="td_currimage_preload"><img name="currimage_preload" id="currimage_preload" src="ui/spacer.gif" border="0" class="noborder"></td></tr></table></div></td>

<!-- PROPERTIES WINDOW -->

<script type="text/javascript" language="JavaScript">

if(FullScreenMode()) document.getElementById("td_fullscreenmode").style.display = "";

var timer = 0;

if(g_Browser.Safari || g_Browser.Chrome)	timer = 2000;

<?php  $image = "$HOME_DIR$currdir$DISPLAY_SLIDE";

$degrees = 0;

$quality = 90;

print("\n\nSetSelectedDropDown($slide, $COUNT); setTimeout(\"LoadImage('$image', $degrees, $quality);\", timer); GetSelectedCaption($slide); LoadPage();\n");

?>

</script>

</body>

</html>
