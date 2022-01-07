<?php
/*************************************************************** 
 * Application Name: DM Albums									
 * Application URI: http://www.dutchmonkey.com/wp-plugins/
 * Description: In-Line Version of DM PhotoAlbums.
 * Author: Frank D. Strack
 * Author Email: development@dutchmonkey.com
 * Author URI: http://www.dutchmokney.com
 ***************************************************************/

error_reporting(1);
 
$slide = $_GET["slide"];

# SET THE LOCATION OF THE SLIDESHOW
# GENERALY, YOU SHOULDN'T NEED TO CHANGE THIS VALUE
$LOCAL_DIR = $_GET["currdir"];

# SET THE LOCATION OF THE SLIDESHOW IMAGES IF DIFFERENT FROM LOCAL DIR
$SLIDE_DIR = "";
$CAPTION_DIR = "";

# SET THE NAME OF THE TEMPLATE FILE TO USE
$TEMPLATE = "album_caption.php";
$TEMPLATE_DIR = "../template/";
$INCLUDE_DIR = ".";

# LOAD THE SLIDESHOW PROGRAM
require("photoalbum.php");

?>