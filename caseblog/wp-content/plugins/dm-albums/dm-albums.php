<?php  //error_reporting(0);

if(file_exists(dirname(__FILE__) . '/../../../wp-config.php'))	include_once( dirname(__FILE__) . '/../../../wp-config.php'); 
if(file_exists(dirname(__FILE__) . '/config.php'))				require_once( dirname(__FILE__) . '/config.php'); 

/*************************************************************** 
 * Application Name: DM Albums									
 * Application URI: http://www.dutchmonkey.com/wp-plugins/
 * Description: In-Line Version of DM Photo Albums.
 * Author: Frank D. Strack
 * Author Email: development@dutchmonkey.com
 * Author URI: http://www.dutchmokney.com
 ***************************************************************/

// SET THIS TO THE ROOT DIRECTORY WEB SPACE
$HOME_DIR = get_dm_option('DM_HOME_DIR');

// SET WHETHER TO DISPLAY THUMBNAIL BAR ALONG THE TOP OR BOTTOM 
// OF THE PHOTO ALBUM
$THUMBNAIL_LOCATION = get_dm_option('DM_THUMBNAIL_LOCATION'); // OPTIONS: "TOP", "BOTTOM"

// SET THE THUMBNAIL BAR HEIGHT
$THUMBNAIL_SIZE = (int) get_dm_option('DM_THUMBNAIL_SIZE'); 		// DEFAULT: 60

// SET THE PADDING AMOUNT AROUND IMAGES IN THE THUMBNAIL BAR
$THUMBNAIL_PADDING = (int) get_dm_option('DM_THUMBNAIL_PADDING'); 	// DEFAULT: 5

// SET WHETHER TO ALLOW DIRECT DOWNLOAD VIA CONTEXT MENU 
$DM_PHOTOALBUM_ALLOWDOWNLOAD = get_dm_option('DM_PHOTOALBUM_ALLOWDOWNLOAD'); 	// OPTIONS: 1 YES 0 NO

// SET WHETHER TO MERGE SUBDIRECTORIES OF IMAGES INTO ONE PHOTO ALBUM. 
$DM_PHOTOALBUM_MERGESUBDIRECTORIES = get_dm_option('DM_PHOTOALBUM_MERGESUBDIRECTORIES'); 	// OPTIONS: 1 YES 0 NO

// SET WHETHER TO DISPLAY IMAGE CAPTIONS (BELOW IMAGE)
$DISPLAY_CAPTIONS = (int) get_dm_option('DM_DISPLAY_CAPTIONS'); 		// OPTIONS: 1 YES 0 NO

// SET WHETHER TO DISPLAY PHOTO COUNT (e.g. Photo 1 of 30)
$DISPLAY_PHOTOCOUNT = (int) get_dm_option('DM_DISPLAY_PHOTOCOUNT'); 	// OPTIONS: 1 YES 0 NO

// HEIGHT OF THE CAPTION BAR (BELOW PHOTO)
// NOTE: IF $DISPLAY_CAPTIONS AND $DISPLAY_PHOTOCOUNT ARE SET TO 
// NO, THIS WILL HAVE NO EFFECT
$CAPTION_HEIGHT = (int) get_dm_option('DM_CAPTION_HEIGHT'); 		// DEFAULT: 32

// PADDING AROUND IMAGE
$PHOTO_PADDING = (int) get_dm_option('DM_PHOTO_PADDING'); 		// DEFAULT: 0

// SET THE PREFIX THAT SHOWS UP IN THE TITLE BAR IN ADDITION TO 
// THE PHOTO ALBUM NAME
$TITLE_PREFIX = get_dm_option('DM_TITLE_PREFIX'); 		// DEFAULT: ""

$CAPTION_EDITORS = get_dm_option("DM_CAPTION_EDITORS");

$DM_PHOTOALBUM_PHOTO_QUALITY = get_dm_option("DM_PHOTOALBUM_PHOTO_QUALITY");

$DM_PHOTOALBUM_SLIDESHOW_AUTOPLAY = get_dm_option("DM_PHOTOALBUM_SLIDESHOW_AUTOPLAY");

$HIDE_LOADING_MESSAGE_SLIDESHOW = get_dm_option("DM_PHOTOALBUM_HIDE_LOADING_MESSAGE_SLIDESHOW");

$HIDE_LOADING_MESSAGE = get_dm_option("DM_PHOTOALBUM_HIDE_LOADING_MESSAGE");

$DM_ALBUMS_EXTERNAL_CSS = get_dm_option("DM_ALBUMS_EXTERNAL_CSS");

$DM_SHOW_NAVIGATION_HINTS = get_dm_option("DM_SHOW_NAVIGATION_HINTS");

$DM_ALBUMS_EXTERNAL_LINK_TARGET = get_dm_option("DM_ALBUMS_EXTERNAL_LINK_TARGET");

// SET THE PAGE MARGINS.  
$LEFTMARGIN 	= (int) get_dm_option('DM_LEFTMARGIN'); 		// DEFAULT: 0
$RIGHTMARGIN 	= (int) get_dm_option('DM_RIGHTMARGIN'); 		// DEFAULT: 0
$TOPMARGIN 		= (int) get_dm_option('DM_TOPMARGIN'); 		    // DEFAULT: 0
$BOTTOMMARGIN 	= (int) get_dm_option('DM_BOTTOMMARGIN'); 		// DEFAULT: 0

/*************************************************************** 
 * DON'T EDIT BELOW THIS LINE UNLESS 
 * YOU KNOW EXACTLY WHAT YOU ARE DOING
 ***************************************************************/

// SUPPORT DIRECTORY as PARAM INSTEAD OF CURRDIR
if(isset($_GET["directory"])) $currdir = $_GET["directory"];

// SET THE LOCATION OF THE SLIDESHOW
// GENERALY, YOU SHOULDN'T NEED TO CHANGE THIS VALUE
$LOCAL_DIR = $_GET["currdir"];
$slide = $_GET["slide"];
$byfile = $_GET["byfile"];
$file = $_GET["file"];

// SET THE NAME OF THE TEMPLATE FILE TO USE
$TEMPLATE = "album.php";

// LOAD THE SLIDESHOW PROGRAM
require("php/photoalbum.php");

?>