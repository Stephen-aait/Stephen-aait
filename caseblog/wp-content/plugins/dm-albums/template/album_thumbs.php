<?php  error_reporting(0);

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
$slide = $_GET["slide"];

$THUMBNAIL_SIZE = $_GET["THUMBNAIL_SIZE"];
$THUMBNAIL_PADDING = $_GET["THUMBNAIL_PADDING"];
$DM_ALBUMS_EXTERNAL_CSS = $_GET["DM_ALBUMS_EXTERNAL_CSS"];

$TR_HEIGHT = $THUMBNAIL_SIZE + ($THUMBNAIL_PADDING * 2);

$html_thumbnails = "";

$js_thumbnails = "";

$thumbs = getfiles();
$thumbcount = $COUNT;
$maxthumbsize = $THUMBNAIL_SIZE;
$cellwidth = $TR_HEIGHT;
$thumnailquality = 100;
$thumbindex = 0;

$html_thumbnails = "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" height=\"100%\">\n";

$html_thumbnails .= "<tr height=\"$TR_HEIGHT\">\n";

$js_thumbnails = "var g_oImages = new Array();\n\n";
	
foreach($thumbs as $thumb)
{
	$thumbindex++;
	$jsindex = $thumbindex - 1;

	$image = "$homedir$currdir" . $SLIDE_DIR . $thumb;
	
	$js_thumbnails .= "g_oImages[$jsindex] = \"$image\";\n";
	
	if($thumbindex == $COUNT_CURR)
	{

		$html_thumbnails .= "<td height=\"$cellwidth\" class=\"selectedthumb\" id=\"thumbid_$thumbindex\" valign=\"middle\" align=\"center\" width=\"$cellwidth\">";
	}

	else
	{
		$html_thumbnails .= "<td height=\"$cellwidth\" class=\"thumb\" id=\"thumbid_$thumbindex\" valign=\"middle\" align=\"center\" width=\"$cellwidth\">";
	}

	$html_thumbnails .= "<img src=\"../ui/spacer.gif\" border=\"0\" width=\"5\" height=\"1\"><img src=\"../ui/spacer.gif\" border=\"0\" width=\"$cellwidth\" height=\"1\"><br><a href=\"javascript:void(0);\" onClick=\"parent.StartStopSlideshow(true); parent.g_ImageViewMode = parent.IMAGE_BESTFIT; parent.OpenImage('$image', $thumbindex, $thumbcount);\"><img id=\"img_thumbid_$thumbindex\" src=\"../ui/throbber.gif\" border=\"0\"></a><img src=\"../ui/spacer.gif\" border=\"0\" width=\"$cellwidth\" height=\"1\"></td>\n";

	if($jsindex < ($thumbcount - 1))	$html_thumbnails .= "<td class=\"thumb\" width=\"1\"><img src=\"../ui/spacer.gif\" border=\"0\" width=\"1\" height=\"$maxthumbsize\"></td>";
}

$html_thumbnails .= "<td class=\"thumb\" width=\"$THUMBNAIL_PADDING\"><img src=\"../ui/spacer.gif\" border=\"0\" width=\"$THUMBNAIL_PADDING\" height=\"$maxthumbsize\"></td></tr>\n";

$html_thumbnails .= "</table>\n";

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
<title></title>

<link rel="stylesheet" type="text/css" href="../ui/styles.css">

<?php 

if(!empty($DM_ALBUMS_EXTERNAL_CSS))	$DM_ALBUMS_EXTERNAL_CSS = '\n<link rel="stylesheet" type="text/css" href="' . $DM_ALBUMS_EXTERNAL_CSS . '">\n';

?>

<script type="text/javascript" src="../javascript/browser.js"></script>

<script language="Javascript">
<!--

var g_Browser = new Browser();
var g_LoadingImage = new Image();
g_LoadingImage.src = "gif/throbber.gif";

<?php  print($js_thumbnails); ?>

function LoadThumbnails()
{
	for(var i = 0; i < g_oImages.length; i++)
	{
		setTimeout("LoadThumbnail('" + g_oImages[i] + "', " + Number(i + 1) + ");", 5);
	}
	
	parent.SetSelectedThumnailVisible(<?php  print($slide); ?>, <?php  print($COUNT); ?>);
}

function LoadThumbnail(image, index)
{
	var oTimestamp = new Date();
	
	var oImage = document.getElementById("img_thumbid_" + index);
	oImage.src = g_LoadingImage.src;
	
	var oBufferImage = new Image();
	
	var width = <?php  print($THUMBNAIL_SIZE); ?>;
	var height = <?php  print($THUMBNAIL_SIZE); ?>;
	
	var degrees = 0;
	var quality = 85;
	
	var sApplication = "image.php?";
	
	var sImageParam = "image=" + image;
	
	var sDegrees = "&degrees=" + degrees;
	
	var sScale = "&scale=yes";
	
	var sWidth = "&width=" + width;
	
	var sHeight = "&height=" + height;
	
	var sQuality = "&quality=80";
	
	var sAspect = "&maintain_aspect=yes";
	
	var sTimestamp = "&timestamp=" + oTimestamp.getTime();
		
	oImage.src = sApplication + sImageParam + sDegrees + sScale + sWidth + sHeight + sQuality + sAspect; 
	
	oBufferImage = null;
}

if(g_Browser.explorer)	document.onkeydown = function (){

	if(!parent.g_CaptureKeyStrokes)	return true;
 
	if(parent.g_CaptureKeyStrokes && event.keyCode == parent.g_LeftArrow) parent.Next(); 
	if(parent.g_CaptureKeyStrokes && event.keyCode == parent.g_RightArrow) parent.Previous(); 
} 

else					document.onkeydown = function (event){ 

	if(!parent.g_CaptureKeyStrokes)	return true;
	
	if(parent.g_CaptureKeyStrokes && event.keyCode == parent.g_LeftArrow) parent.Next(); 
	if(parent.g_CaptureKeyStrokes && event.keyCode == parent.g_RightArrow) parent.Previous(); 
	
	if(event.keyCode == parent.g_LeftArrow || event.keyCode == parent.g_RightArrow) return false;
} 

//-->
</script>

</head>
<body topmargin="<?php  print($THUMBNAIL_PADDING); ?>" leftmargin="0<?php  print($THUMBNAIL_PADDING); ?>" rightmargin="<?php  print($THUMBNAIL_PADDING); ?>" bottommargin="<?php  print($THUMBNAIL_PADDING); ?>" class="thumb" OnContextMenu="return false;">
<table cellpadding="0" cellspacing="0" border="0" width="100%" height="<?php  print($TR_HEIGHT); ?>"><tr height="100%"><td align="center" valign="middle"><?php  print($html_thumbnails); ?></td></tr></table>
</body>
<?php  print("<script language=\"JavaScript\">\n\nLoadThumbnails();parent.CheckScrollWidth();\n\n</script>"); ?>
</html>
