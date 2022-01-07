<?php
/*************************************************************** 
 * Application Name: DM Albums									
 * Application URI: http://www.dutchmonkey.com/wp-plugins/
 * Description: In-Line Version of DM Photo Albums.
 * Author: Frank D. Strack
 * Author Email: development@dutchmonkey.com
 * Author URI: http://www.dutchmokney.com
 ***************************************************************/

include_once("includes.php");

$slide = $_GET["slide"];
$directory = str_replace("//", "/", $_GET["currdir"]);

$file = $_GET["properties"];

function ClientDebug($msg)
{
	?>
	<script>
	parent.alert("<?php  print($msg); ?>");
	</script>
	<?php  }

$ajax_width_desc = "(not available)";
$ajax_height_desc = "(not available)";
$ajax_size_desc = "(not available)";

list($ajax_width_desc, $ajax_height_desc) = getimagesize($file, $info);

$ajax_size_desc = round((filesize($file) / 100000), 2) . "MB";

//$exif = exif_read_data($file, "EXIF", true);

if(function_exists("exif_read_data"))		$exif = exif_read_data($file, "EXIF", true);
else										$exif = false;

$EXIF_properties = array("FileName", "ApertureFNumber", "Make", "Model", "ExposureTime", "FNumber", "ExposureProgram", "DateTimeOriginal", "CompressedBitsPerPixel", "ExposureBiasValue", "MeteringMode", "LightSource", "Flash", "FocalLength");

$properties = array();

if($exif !== false)
{
	foreach ($exif as $key => $section) 
	{
		foreach ($section as $name => $val) 
		{
			//echo("<p>$name</p>");
			
	        if(in_array($name, $EXIF_properties) && $name != "0")
			{
				//echo("<p>$name: $val</p>");
				
				if(empty($val))	$val = "(not available)";
				
				else if($name == "DateTimeOriginal")
				{
					$val = date("F j, Y, g:i a", strtotime($val));
				}
				
				switch ($name) 
				{
					case "FileName":
						array_push($properties, "00Name: $val");
						break;
					
					case "ApertureFNumber":
						array_push($properties, "04Aperture: $val");
						break;
						
					case "Make":
						array_push($properties, "01Make: $val");
						break;
						
					case "Model":
						array_push($properties, "01Model: $val");
						break;
						
					case "ExposureTime":
						array_push($properties, "08Exposure Time: $val");
						break;
						
					case "FNumber":
						array_push($properties, "05F Stop: $val");
						break;
						
					case "ExposureProgram":
						array_push($properties, "08Exposure Program: $val");
						break;
						
					case "DateTimeOriginal":
						array_push($properties, "03Date Taken: $val");
						break;
						
					case "CompressedBitsPerPixel":
						array_push($properties, "13Compression: $val");
						break;
						
					case "ExposureBiasValue":
						array_push($properties, "10Exposure Bias: $val");
						break;
						
					case "MeteringMode":
						array_push($properties, "07Metering Mode: $val");
						break;
						
					case "LightSource":
						array_push($properties, "12Light Source: $val");
						break;
						
					case "Flash":
						array_push($properties, "11Flash: $val");
						break;
						
					case "FocalLength":
						array_push($properties, "06Focal Length: $val");
						break;
				}
			}
	    }
	}

}	

natcasesort($properties);
	
$html = "";

$table_header = "<table border='0' cellpadding='0' cellspacing='0' width='100%'><tr><td width='1' colspan='2'><img src='ui/spacer.gif' height='5'></td></tr><tr><td colspan='2'><img src='ui/spacer.gif'></td></tr>";
$tr_opening = "<tr><td align='left' nowrap valign='top' width='125'><span>";
$tr_middle = "</span>: <img src='ui/spacer.gif' width='5'></td><td align='left' valign='top'><span>";
$tr_closing = "</span></td></tr><tr><td width='1' colspan='2'><img src='ui/spacer.gif' height='5'></td></tr>";
$table_footer = "</table>";

$html = $table_header;

$size = $tr_opening . "Size" . $tr_middle . $ajax_width_desc . " x " . $ajax_height_desc . " px (". $ajax_size_desc . ")" . $tr_closing;

if(count($properties) == 0)
{
	$html .= "<tr><td align='left' nowrap valign='top' colspan='2' width='190'>Sorry, photo properties are not available. Either the photo contains no EXIF data or PHP\'s EXIF extensions are not installed.</td></tr><tr><td width='1' colspan='2'><img src='ui/spacer.gif' height='5'></td></tr>";
}

else
{
	foreach ($properties as $prop) 
	{
		$value = substr($prop, 2);
		
		print($prop . "<br>");
		
		$html .= $tr_opening . str_replace(": ", $tr_middle, $value) . $tr_closing;
		
		if(strpos($prop, "00Name:") !== FALSE)	$html .= $size;
	}
	
}

$html .= $table_footer;

//print($html);


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

window.parent.UpdateProperties(g_Browser.Encode("<?php  print($html); ?>"))

</script>
<?php  }
?>
</head>
<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0">
</body>
</html>


