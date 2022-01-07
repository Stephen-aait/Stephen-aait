<?php  /*************************************************************** 
 * Application Name: DM Albums									
 * Application URI: http://www.dutchmonkey.com/wp-plugins/
 * Description: In-Line Version of DM PhotoAlbums.
 * Author: Frank D. Strack
 * Author Email: development@dutchmonkey.com
 * Author URI: http://www.dutchmokney.com
 ***************************************************************/

error_reporting(1);

if(file_exists(dirname(__FILE__) . '/../../../../wp-config.php'))	include_once( dirname(__FILE__) . '/../../../../wp-config.php'); 
if(file_exists(dirname(__FILE__) . '/../config.php'))				require_once( dirname(__FILE__) . '/../config.php'); 
 
$slide = $_GET["slide"];

# DON'T CHANGE THIS VALUE
$HOME_DIR = $_GET["HOME_DIR"];

# SET THE LOCATION OF THE SLIDESHOW
# GENERALY, YOU SHOULDN'T NEED TO CHANGE THIS VALUE
$LOCAL_DIR = $_GET["currdir"];

# SET THE LOCATION OF THE SLIDESHOW IMAGES IF DIFFERENT FROM LOCAL DIR
$SLIDE_DIR = "";
$CAPTION_DIR = "";

// SET WHETHER TO MERGE SUBDIRECTORIES OF IMAGES INTO ONE PHOTO ALBUM. 
$DM_PHOTOALBUM_MERGESUBDIRECTORIES = get_dm_option('DM_PHOTOALBUM_MERGESUBDIRECTORIES'); 	// OPTIONS: 1 YES 0 NO

# SET THE NAME OF THE TEMPLATE FILE TO USE
$TEMPLATE = "album_thumbs.php";
$TEMPLATE_DIR = "../template/";
$INCLUDE_DIR = ".";

# LOAD THE SLIDESHOW PROGRAM
require("photoalbum.php");

?>