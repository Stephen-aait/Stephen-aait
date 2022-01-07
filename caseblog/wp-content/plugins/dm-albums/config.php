<?php  function get_dm_option($option)
{
	global $_GET;
	
	$value = "";
	
	// Get WP Config if it's available
	if(function_exists("get_option"))
	{
		$value = get_option($option);
	}
	// Default options if WP is not available
	else
	{
		$value = dm_albums_defaults($option);	
	}
	
	// Allow URL Parameters to override setting
	switch ($option) 
	{
		case "DM_HOME_DIR":
		    return $value;
			break;
			
		case "DM_THUMBNAIL_LOCATION":
		    return isset($_GET["thumbnail_location"]) ? $_GET["thumbnail_location"] : strtoupper($value);
		    break;
			
		case "DM_PHOTOALBUM_ALLOWDOWNLOAD":
		    return isset($_GET["allow_download"]) ? $_GET["allow_download"] : strtoupper($value);
		    break;

		case "DM_PHOTOALBUM_SLIDESHOW_CONTROLS":
		    return isset($_GET["controls"]) ? $_GET["controls"] : strtoupper($value);
		    break;
		    
		case "DM_PHOTOALBUM_SLIDESHOW_AUTOPLAY":
		    return isset($_GET["autoplay"]) ? $_GET["autoplay"] : strtoupper($value);
		    break;
		    
		case "DM_PHOTOALBUM_MERGESUBDIRECTORIES":
		    return isset($_GET["merge_subdirs"]) ? $_GET["merge_subdirs"] : strtoupper($value);
		    break;
			
		case "DM_THUMBNAIL_SIZE":
		    return isset($_GET["thumbnail_size"]) ? $_GET["thumbnail_size"] : $value;
		    break;
				
		case "DM_THUMBNAIL_PADDING":
		    return isset($_GET["thumbnail_padding"]) ? $_GET["thumbnail_padding"] : $value;
		    break;
				
		case "DM_DISPLAY_CAPTIONS":
		    return isset($_GET["captions"]) ? $_GET["captions"] : $value;
		    break;
				
		case "DM_DISPLAY_PHOTOCOUNT":
		    return isset($_GET["photo_count"]) ? $_GET["photo_count"] : $value;
		    break;
				
		case "DM_CAPTION_HEIGHT":
		    return isset($_GET["caption_height"]) ? $_GET["caption_height"] : $value;
		    break;
				
		case "DM_PHOTO_PADDING":
		    return isset($_GET["photo_padding"]) ? $_GET["photo_padding"] : $value;
		    break;
				
		case "DM_TITLE_PREFIX":
		    return isset($_GET["title_prefix"]) ? $_GET["title_prefix"] : $value;
		    break;
				
		case "DM_LEFTMARGIN":
		    return isset($_GET["left_margin"]) ? $_GET["left_margin"] : $value;
		    break;
					
		case "DM_RIGHTMARGIN":
		    return isset($_GET["right_margin"]) ? $_GET["right_margin"] : $value;
		    break;
					
		case "DM_TOPMARGIN":
		    return isset($_GET["top_margin"]) ? $_GET["top_margin"] : $value;
		    break;
					
		case "DM_BOTTOMMARGIN":
		    return isset($_GET["bottom_margin"]) ? $_GET["bottom_margin"] : $value;
		    break;
		    
		case "":
		    return "";
		    break;
		    
		default:
		    return $value;
		    break;
	}
}

function dm_albums_defaults($option)
{
	switch ($option) 
	{
		case "DM_HOME_DIR":
		    return $_SERVER["DOCUMENT_ROOT"];
			break;
		
		case "DM_PHOTOALBUM_ALLOWDOWNLOAD":
		    return "true";
		    break;
			
		case "DM_PHOTOALBUM_SLIDESHOW_AUTOPLAY":
		    return "false";
		    break;
		    
		case "DM_PHOTOALBUM_MERGESUBDIRECTORIES":
		    return 0;
		    break;
		
		case "DM_THUMBNAIL_LOCATION":
		    return "TOP";
		    break;
				
		case "DM_THUMBNAIL_SIZE":
		    return 60;
		    break;
				
		case "DM_THUMBNAIL_PADDING":
		    return 5;
		    break;
				
		case "DM_DISPLAY_CAPTIONS":
		    return 1;
		    break;
				
		case "DM_DISPLAY_PHOTOCOUNT":
		    return 1;
		    break;
				
		case "DM_CAPTION_HEIGHT":
		    return 32;
		    break;
				
		case "DM_PHOTO_PADDING":
		    return 0;
		    break;
				
		case "DM_TITLE_PREFIX":
		    return "";
		    break;
				
		case "DM_LEFTMARGIN":
		    return 0;
		    break;
					
		case "DM_RIGHTMARGIN":
		    return 0;
		    break;
					
		case "DM_TOPMARGIN":
		    return 0;
		    break;
					
		case "DM_BOTTOMMARGIN":
		    return 0;
		    break;
		   
		case "DM_PHOTOALBUM_PHOTO_QUALITY":
		    return 85;
		    break;

		case "DM_ALBUMS_EXTERNAL_LINK_TARGET":
		    return "_newWindow";
		    break;
		    
		case "":
		    return "";
		    break;
		    
		default:
			return "";
			break;
	}
}
?>