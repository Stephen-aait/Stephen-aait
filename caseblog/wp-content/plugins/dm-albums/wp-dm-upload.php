<?php  /*************************************************************** 
 * Author: Frank D. Strack
 * Author Email: development@dutchmonkey.com
 * Author URI: http://www.dutchmokney.com
 * 
 * Thanks to safety of nDarkness.com for alerting us to a 
 * vulnerability and recommending a solution.
 ***************************************************************/

require_once('../../../wp-config.php');
require_once('php/includes.php');

if (isset($_POST["PHPSESSID"])) {
	session_id($_POST["PHPSESSID"]);
} else if (isset($_GET["PHPSESSID"])) {
	session_id($_GET["PHPSESSID"]);
}

session_start();

if($_SESSION["DM_AUTH_UPLOAD"] != 1)
{
	print("User not authorized.</br>");
	exit(0);
}

$DM_UPLOAD_DIRECTORY = dm_getuploaddirectory();

$album_name = isset($_POST["album_name"]) ? $_POST["album_name"] : $_GET["album_name"];

$user_upload_directory = dm_user_uploaddirectory();

$album_name = dm_sanitize($album_name);

$dm_albums_uploaddir = $DM_UPLOAD_DIRECTORY . $user_upload_directory . $album_name;

$DM_UUP = get_option('DM_ALBUMS_UUP');

if($DM_UUP == 1 && (empty($user_upload_directory) || strlen($user_upload_directory) <= 0))
{
	print("Request not authorized.</br>");
	exit(0);
}

if($DM_UPLOAD_DIRECTORY == "" || $DM_UPLOAD_DIRECTORY == "/")
{
	print("Upload directory not set.</br>");
	exit(0);
}

if(!empty($album_name) && strlen($album_name) > 0)
{	
	if(!file_exists($dm_albums_uploaddir))
	{
		if(eregi("WIN", strtoupper(php_uname())))	$cache = "/cache";
		else										$cache = "/.cache";
		
		dm_mkdir($dm_albums_uploaddir . $cache); 	//mkdir($dm_albums_uploaddir . $cache, 0777, true);
		
		// If the file was not created, user does not have upload rights.
		// Create folder in default upload dir location
		if(!file_exists($dm_albums_uploaddir))
		{
			$dm_albums_uploaddir = get_option('DM_ALBUMS_CORE_DEFAULT_UPLOADDIR') . $user_upload_directory . $album_name;
			dm_mkdir($dm_albums_uploaddir . $cache); 	//mkdir($dm_albums_uploaddir . $cache, 0777, true);
			update_option('DM_ALBUMS_UPLOADDIR', get_option('DM_ALBUMS_CORE_DEFAULT_UPLOADDIR'));
		}
	}
	
	$extension_whitelist = array("jpg", "gif", "png", "jpeg");	// Allowed file extensions
	$MAX_FILENAME_LENGTH = 260;
	
	$file_name = dm_sanitize($_FILES['Filedata']['name']);
	
	if (strlen($file_name) == 0 || strlen($file_name) > $MAX_FILENAME_LENGTH) {
		HandleError("Invalid file name");
		exit(0);
	}
	
	$path_info = pathinfo($_FILES['Filedata']['name']);
	$file_extension = $path_info["extension"];
	$is_valid_extension = false;
	
	foreach ($extension_whitelist as $extension) {
		if (strcasecmp($file_extension, $extension) == 0) {
			$is_valid_extension = true;
			break;
		}
	}
	
	if (!$is_valid_extension) {
		HandleError("Invalid file extension");
		exit(0);
	}
	
	@ move_uploaded_file($_FILES['Filedata']['tmp_name'], $dm_albums_uploaddir . "/" . $file_name);
	
	dm_add_photo_to_sortorder($dm_albums_uploaddir . "/", $file_name);
}

else
{
	print("Request not authorized.</br>");
	exit(0);
}

/* Handles the error output. This error message will be sent to the uploadSuccess event handler.  The event handler
will have to check for any error messages and react as needed. */
function HandleError($message) 
{
	echo $message;
}
?>
<html>
<head><title></title>
</head>
<body>
<p><?php  print("Uploading of album $album_name to $dm_albums_uploaddir complete"); ?></p>
</body>
</html>