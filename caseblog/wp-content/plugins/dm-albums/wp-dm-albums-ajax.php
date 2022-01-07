<?php header("Content-Type:text/xml"); print("<?xml version='1.0' ?>\n<root>\n");

/*************************************************************** 
 * Author: Frank D. Strack
 * Author Email: development@dutchmonkey.com
 * Author URI: http://www.dutchmokney.com
 * 
 * Thanks to safety of nDarkness.com for alerting us to a 
 * vulnerability and recommending a solution.
 ***************************************************************/

require_once('../../../wp-config.php');
require_once('php/includes.php');	

if(strpos($_SERVER['HTTP_REFERER'], get_option('siteurl')) === FALSE)
{
	print("\t<error>Request not authorized.</error>");	print("\n</root>\n");	
	exit(0);
}

$delete_album = isset($_POST["delete_album"]) ? $_POST["delete_album"] : $_GET["delete_album"];
$album_detail = isset($_POST['album_detail']) ? $_POST['album_detail'] : $_GET['album_detail'];
$album_order = isset($_POST['album_order']) ? $_POST['album_order'] : $_GET['album_order'];
$album_reset_order = (boolean) (isset($_POST['album_reset_order']) ? $_POST['album_reset_order'] : $_GET['album_reset_order']);
$delete_photo = isset($_POST['delete_photo']) ? $_POST['delete_photo'] : $_GET['delete_photo'];
$photo = isset($_POST['photo']) ? $_POST['photo'] : $_GET['photo'];
$album_name = isset($_POST['album_name']) ? $_POST['album_name'] : $_GET['album_name'];
$value = isset($_POST['value']) ? $_POST['value'] : $_GET['value'];
$type = isset($_POST['type']) ? $_POST['type'] : $_GET['type'];

$DM_UPLOAD_DIRECTORY = dm_getuploaddirectory() . dm_user_uploaddirectory();

if($DM_UPLOAD_DIRECTORY == "" || $DM_UPLOAD_DIRECTORY == "/")
{
	print("\t<error>Upload Directory is not set.  Please visit the <a href='" . get_option('siteurl') . "/wp-admin/options-general.php?page=wp-dm-albums.php'> DM Albums Admin Panel to configure DM Albums properly.</error>\n");	
}
$delete_album = dm_sanitize($delete_album);
$album_detail = dm_sanitize($album_detail);
$delete_photo = dm_sanitize($delete_photo);
$photo = dm_sanitize($photo);
$album_name = dm_sanitize($album_name);
//$value = dm_sanitize($value);
$type = dm_sanitize($type);
if(isset($delete_album) && !empty($delete_album) && strlen($delete_album) > 0 && current_user_can('upload_files'))
{
    //delete the album directory
    dm_get_album_delete($DM_UPLOAD_DIRECTORY . $delete_album);
}
if(isset($album_name) && !empty($album_name) && strlen($album_name) > 0 && current_user_can('upload_files'))
{
	if(strlen($photo) > 0)
	{
		//update caption	
    	if($type == "caption")	dm_put_caption($DM_UPLOAD_DIRECTORY . $album_name . "/" .$photo, $value);
	    //update title	
    	if($type == "title")	dm_put_title($DM_UPLOAD_DIRECTORY . $album_name, $value);
    	//update caption	
    	if($type == "link")		dm_put_link($DM_UPLOAD_DIRECTORY . $album_name . "/" .$photo, $value);
	}
	
	else if(strlen($delete_photo) > 0)
	{
		unlink($DM_UPLOAD_DIRECTORY . $album_name . "/" . $delete_photo);
		dm_remove_photo_from_sortorder($DM_UPLOAD_DIRECTORY . $album_name . "/", $delete_photo);
	}
	
	else if(strlen($album_order) > 0)
	{
		dm_put_sortorder($DM_UPLOAD_DIRECTORY . $album_name . "/", $album_order);
	}
	
	else if($album_reset_order)
	{
		dm_reset_photo_sortorder($DM_UPLOAD_DIRECTORY . $album_name . "/");
	}
}

if(isset($album_detail) && !empty($album_detail) && strlen($album_detail) > 0 && current_user_can('upload_files'))
{
	$dm_albums_location = $DM_UPLOAD_DIRECTORY . $album_detail;
	
	$dm_albums_photos = dm_get_photo_list($dm_albums_location);
	print("\t<album>$album_detail</album>\n");
	print("\t<location>$dm_albums_location</location>\n");
	print("\t<title>" . dm_get_title($dm_albums_location) . "</title>\n");	
	print("\t<photos>\n");
	foreach($dm_albums_photos as $dm_albums_photo)
	{
		print("\t\t<photo>\n");
		print("\t\t\t<name>$dm_albums_photo</name>\n");
		print("\t\t\t<caption>" . dm_get_caption($dm_albums_location . "/" . $dm_albums_photo) . "</caption>\n");
		print("\t\t\t<link>" . dm_get_link($dm_albums_location . "/" . $dm_albums_photo) . "</link>\n");
		print("\t\t</photo>\n");
	}
	
	print("\t</photos>\n"); 
}

else
{
	$orderby = isset($_GET["orderby"]) ? $_GET["orderby"] : "newest";
	$dm_existing_albums = dm_get_album_list($DM_UPLOAD_DIRECTORY, $orderby);
	print("\t<parent>$DM_UPLOAD_DIRECTORY</parent>\n");
	
	print("\t<albums>\n");
	
	foreach($dm_existing_albums as $dm_existing_album)
	{
		print("\t\t<album>\n");
		print("\t\t\t<name>" . basename($dm_existing_album[0]) . "</name>\n");
		print("\t\t\t<directory>$dm_existing_album[0]</directory>\n");
		print("\t\t</album>\n");
	}
	
	print("\t</albums>\n"); 
}
print("\n</root>\n");exit(0);
?>