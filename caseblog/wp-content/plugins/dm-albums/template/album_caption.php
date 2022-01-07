<?php  /*************************************************************** 
 * Application Name: DM Albums									
 * Application URI: http://www.dutchmonkey.com/wp-plugins/
 * Description: In-Line Version of DM Photo Albums.
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
	
$slide = $_GET["slide"];
$directory = str_replace("//", "/", $_GET["currdir"]);
$captionsave = $_GET["captionsave"];
$displaycaption = $_GET["displaycaption"];
$titlesave = $_GET["titlesave"];
$displaytitle = $_GET["displaytitle"];

$PARENT_IS_WRITABLE = true;

$picturename = getslide($slide);

function ClientDebug($msg)
{
	?>
	<script>
	parent.alert("<?php  print($msg); ?>");
	</script>
	<?php  }

if($PARENT_IS_WRITABLE && $captionsave == "yes")
{
	$captionfilename = $directory . "browse.cap";

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
	
	$DISPLAY_CAPTION = getcaption(getslide($slide));
}

else if($PARENT_IS_WRITABLE && $titlesave == "yes")
{
	$title = html_entity_decode($displaytitle);
	
	$captionfilename = $directory . "browse.cap";

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
		
		//line starts with DM_ALBUM_TITLE:, remove it and leading whitespace, display caption
		
		$matches = array();
			
		$matchcount = 0;
			
		$matchcount = preg_match_all("/(^DM_ALBUM_TITLE:\s)(.*)/i", $line, $matches);
			
		if($matchcount > 0)
		{
			$foundcaption = 1;
			$filename = $matches[0][1];
			$title = $displaytitle;
			
			$lines[$linecount] = "DM_ALBUM_TITLE:\t$title\n";
		}
			
		$linecount++;
	}
	
	if($foundcaption === 0)
	{
		$lines[$linecount] = "DM_ALBUM_TITLE:\t$displaytitle\n";
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
<?php  if(!($PARENT_IS_WRITABLE && $captionsave == "yes" && $titlesave == "yes"))
{
?>
<script type="text/javascript" src="../javascript/browser.js"></script>

<script type="text/javascript" language="JavaScript">

var g_Browser = new Browser();

parent.SetCaption(g_Browser.Encode("<?php  print(htmlentities($DISPLAY_CAPTION)); ?>"));
parent.SetCaptionPosition();

parent.document.getElementById("tbl_caption").style.visibility = "visible";

</script>
<?php  }
?>
</head>
<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0">
</body>
</html>


