<?php  

/*************************************************************** 
 * Application Name: DM Albums									
 * Application URI: http://www.dutchmonkey.com/wp-plugins/
 * Description: In-Line Version of DM PhotoAlbums.
 * Author: Frank D. Strack
 * Author Email: development@dutchmonkey.com
 * Author URI: http://www.dutchmokney.com
 ***************************************************************/

global $DM_PHOTOALBUM_APP_VERSION;
global $DM_PHOTOALBUM_APP_DOCS;
global $DM_PHOTOALBUM_APP;
global $DM_ALBUM_PLUGIN_APP;
global $DM_PHOTOALBUM_PREFIX;
global $DM_PHOTOALBUM_PRESERVE_LINK;
global $DM_PHOTOALBUM_APP_WIDTH;
global $DM_PHOTOALBUM_APP_HEIGHT;
global $HOME_DIR;
global $THUMBNAIL_LOCATION;
global $THUMBNAIL_SIZE;
global $THUMBNAIL_PADDING;
global $DISPLAY_CAPTIONS;
global $DISPLAY_PHOTOCOUNT;
global $CAPTION_HEIGHT;
global $PHOTO_PADDING;
global $TITLE_PREFIX;
global $LEFTMARGIN;
global $RIGHTMARGIN;
global $TOPMARGIN;
global $BOTTOMMARGIN;

global $userdata;
global $current_user;
global $user_level;

get_currentuserinfo();

$APP_CONFIGURED_CORRECTLY = 0;

$DIRECTORY = str_replace(realpath(get_option('DM_HOME_DIR')), "", dirname(__FILE__)) . "/preview/";
$URL = str_replace(realpath(get_option('DM_HOME_DIR')), $_SERVER['SERVER_NAME'], dirname(__FILE__)) . "/preview/";

if(!file_exists(get_option('DM_HOME_DIR') . $DIRECTORY . DM_CACHE_DIRECTORY)) 
{
	clearstatcache();
	
	if(eregi("WIN", strtoupper(php_uname()))) 
	{
	    dm_mkdir(get_option('DM_HOME_DIR') . $DIRECTORY . DM_CACHE_DIRECTORY);	//mkdir(get_option('DM_HOME_DIR') . $DIRECTORY . DM_CACHE_DIRECTORY, 0777, true);
	}
	else
	{
		dm_mkdir(get_option('DM_HOME_DIR') . $DIRECTORY . DM_CACHE_DIRECTORY); 	//mkdir(get_option('DM_HOME_DIR') . $DIRECTORY . DM_CACHE_DIRECTORY, 0777, true);
	}
}

if(get_option('DM_HOME_DIR') != $_SERVER['DOCUMENT_ROOT'] . "/")
{
	$URL = str_replace($_SERVER['SERVER_NAME'] ."/", "", $URL);
}

$PATH = "?currdir=" . $DIRECTORY;

if(file_exists(get_option('DM_HOME_DIR') . $DIRECTORY) && get_option('DM_PHOTOALBUM_APP') != '')	
{
	$APP_CONFIGURED_CORRECTLY = 1;
	add_option('DM_PHOTOALBUM_CONFIGURED_CORRECTLY', 'YES', "Setting indicating if the Album has been configured.", true);
	add_option('DM_PHOTOALBUM_CONFIGURED', 'YES', "Setting indicating if the Album has been configured.", true);
}

//dm_display_config()

?>

<link rel="stylesheet" type="text/css" href="http://dutchmonkey.com/public/resources/dm.css" />

<style type="text/css">

#dm_config_settings
{
	padding: 10px;
	border: 1px solid #dddddd;
}


.dm_warning
{
	padding: 10px;
	margin: 10px;
	border: 1px solid #CC0000;
	background-color: #FFFBCC;
	
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
}
 
 
</style>

<script type="text/javascript">

function ShowHide()
{
	if(document.getElementById("dm_config_settings").style.display == "none")
	{
		document.getElementById("dm_config_settings").style.display = "";
		document.getElementById("btnShowHide").value = "Hide Configuration Options"; 
	}
	
	else
	{
		document.getElementById("dm_config_settings").style.display = "none";
		document.getElementById("btnShowHide").value = "Show Configuration Options"; 
	}
}

function WarnChangeUploadDir()
{
	var recommended_upload_root = "<?php print(addslashes(dirname(get_option('DM_ALBUMS_CORE_DEFAULT_UPLOADDIR')))); ?>";

	if(document.getElementById("DM_ALBUMS_UPLOADDIR").value.indexOf(recommended_upload_root) != 0)
	{
		return confirm("You are about to set your Album Upload Folder to a location outside the WordPress designated upload area.\n\nThis can cause problems uploading photos to your albums and presents some risks in terms of file security.\n\n'Cancel' to stop, 'OK' to continue.");
	}
}

</script>

<div class="wrap" style="height:100%">

<?php  if(!function_exists("imagecreatefromjpeg"))
	{
		dm_showMessage("DM Albums requires that the <a href='http://www.libgd.org/Main_Page'>GD Library</a> is installed and configured; it appears that you are missing this library.  Please contact your system administrator for more information.");
	}
	
	if(intval(ini_get('memory_limit')) < 32 && intval(ini_get('memory_limit')) >= 0)
	{
		dm_showMessage("Opening images in PHP can use a lot of memory; the memory limit on your server appears to be too low.  DM Albums requires at least 32M of memory, although DutchMonkey Productions recommends setting this value to 100M or more in order to support high-resolution images.  Your current memory limit is " . ini_get('memory_limit') . ".  This value can be adjusted by setting the <a href='http://us3.php.net/ini.core'>memory_limit</a> parameter in your PHP.ini file.  Please contact your system administrator for more information.");
	}
?>	

<h2>DM Albums Options</h2>

<table width="60%" cellpadding="10">
<tr>
<td valign="top">
<p>Settings for the DM Albums plugin. Visit <a href="<?php echo $DM_PHOTOALBUM_APP_DOCS; ?>">DM Productions</a> for help and project news.</p>

<p>Current version: <strong><?php echo $DM_PHOTOALBUM_APP_VERSION; ?></strong> (Visit <a href="<?php echo $DM_PHOTOALBUM_APP_DOCS; ?>">DM Productions</a> to check for updates)</p>
</td>
<td width="30%" valign="top">
<p><b>Donations</b><br>
This product is available free of charge.  However, donations are appreciated in order to help fund continued development and support.  Please click the link below to donate safely and securely via <a href="http://paypal.com/" class="normal">PayPal</a>.</p>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="7162488">
<input type="image" src="http://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
</p>
</td>
</tr>
</table>

<h3>Usage</h3>
<hr size="1">

<p>There are three ways to use the DM Albums Plugin in a blog post:

<ol>
<li><b>Album Prefix</b>. <p>When using the Album prefix (as defined below) followed by a link to the DM Albums Application (as defined below) in a blog post, the prefix and link will be replaced by the DM Album.</p>
<p>Example:&nbsp;&nbsp;&nbsp;&nbsp;<?php echo( get_option("DM_PHOTOALBUM_PREFIX") ); ?> <a href="<?php echo( get_option("DM_PHOTOALBUM_APP") ); ?><?php echo $PATH; ?>">Demo Album</a><br/>
<p>Example Code:&nbsp;&nbsp;&nbsp;&nbsp;<code><?php echo( get_option("DM_PHOTOALBUM_PREFIX") ); ?> &lt;a href="<?php echo( get_option("DM_PHOTOALBUM_APP") ); ?><?php echo $PATH; ?>"&gt;Demo Album&lt;/a&gt;</code></p>
</li>
<li><b>[album:] Syntax</b>. 
<p>Simply type [album: {album url}] in your blog post and the Album will be inserted into the post at that point.  This will usually be the direct URL to your photos, or to one of the photos in your album: [album: http://yourdomain.com/path/to/albums/].  It may also be the URL to your Album including the Album Application (as specified in the DM Albums admin panel) plus the currdir parameter: http://yourdomain.com/path/to/album/application.php?currdir={path}</p>

<p>Example:&nbsp;&nbsp;&nbsp;&nbsp;[album: http://<?php echo $URL ; ?>]</p>
<p>Example:&nbsp;&nbsp;&nbsp;&nbsp;[album: http://<?php  echo $URL ; ?>DSC0001.jpg]</p>
<p>Example:&nbsp;&nbsp;&nbsp;&nbsp;[album: <?php echo( get_option("DM_PHOTOALBUM_APP") ); ?>?currdir=<?php echo $DIRECTORY ; ?>]</p>
</li>
<li><b>[album:] Syntax with parameters (for advanced users).</b>

<p>DM Albums&#153 uses defaults you set up to control how each album looks.  However, you can customize the look of any DM Albums&#153 by using parameters.  When using the [album:] syntax, pass the parameters using a pipe-delimited name=value list.  Simply type [album: {album url}|width={width}|height={height}|etc...] in your blog post and the Album will be inserted into the post at that point using the parameters you specified.</p>

<p>Supported Parameters: thumbnail_location={TOP, BOTTOM}, thumbnail_size={NUMBER}, thumbnail_padding={NUMBER}, captions={0,1}, photo_count={0,1}, caption_height={NUMBER} , photo_padding={NUMBER}, title_prefix={ANY STRING}, left_margin={NUMBER}, right_margin={NUMBER}, top_margin={NUMBER}, and bottom_margin={NUMBER}.</p>
</ol>

<b>How It Works</b>:
<p>The DM Albums App (defined in <b>DM Album Application</b> takes in one parameter: <code>currdir</code>.  This parameter is the path to the directory where the photos for the Album exist on your server.  The program then takes that parameter and connects it to the <b>Home Directory</b> setting (as defined below under Advanced Settings) to build the full path to the photos on your server.  If either the <b>Home Directory</b> or the <code>currdir</code> value do not correctly point to the photos, the album will be displayed, but the Album will not display your photos.  If you are having any difficulty at all, please do not hesitate to contact the developer directly at <a href="mailto:development@dutchmonkey.com">development@dutchmonkey.com</a>.</p>

<b>Current Appearance:</b>

<div align="center"><?php print(dm_getalbum($PATH)) ?></div>

</p>

<?php  //if($user_level == 10)  
if(TRUE)
{

?>
<p class="submit" style="align: left;">
<input type="button" id="btnShowHide" value="Show Configuration Options" onClick="ShowHide();">
</p>

<div id="dm_config_settings">

<h3>Display Settings</h3>
<hr size="1">

<form method="post" onsubmit="return WarnChangeUploadDir();">

<p class="submit">
<input name="Submit" value="Update Options &raquo;" type="submit" />
</p>

<!-- PROPERTY: DM_SHOW_TAGLINE -->

<fieldset class="options">

<h3>Display Powered By DM Albums Tagline</h3>

<p>This setting turns on/off the "Powered By DM Albums" tagline.  This is off by default, but we appreciate you turning it on!</p>

<table class="editform" cellpadding="5" cellspacing="2">
<tr>
<th width="250" valign="top"><label for="DM_SHOW_TAGLINE">Display Tagline:</label></th>
<td>
<select id="DM_SHOW_TAGLINE" name="DM_SHOW_TAGLINE">
<option <?php  if(get_option("DM_SHOW_TAGLINE") == "true") echo "SELECTED" ?> value="true">YES</option>
<option <?php  if(get_option("DM_SHOW_TAGLINE") == "false") echo "SELECTED" ?> value="false">NO</option>
</select>Default: <code>NO</code>
</td>
</tr>
</table>

</fieldset>

<!-- PROPERTY: DM_SHOW_TAGLINE -->

<!-- PROPERTY: DM_SHOW_FULLSCREEN -->

<fieldset class="options">

<h3>Display Fullscreen Link</h3>

<p>This setting turns on/off the "Fullscreen" link.  This is on by default.</p>

<table class="editform" cellpadding="5" cellspacing="2">
<tr>
<th width="250" valign="top"><label for="DM_SHOW_FULLSCREEN">Display Fullscreen Link:</label></th>
<td>
<select id="DM_SHOW_FULLSCREEN" name="DM_SHOW_FULLSCREEN">
<option <?php  if(get_option("DM_SHOW_FULLSCREEN") == "true") echo "SELECTED" ?> value="true">YES</option>
<option <?php  if(get_option("DM_SHOW_FULLSCREEN") == "false") echo "SELECTED" ?> value="false">NO</option>
</select>Default: <code>YES</code>
</td>
</tr>
</table>

</fieldset>

<!-- PROPERTY: DM_SHOW_TAGLINE -->

<!-- PROPERTY: DM_PHOTOALBUM_SLIDESHOW_CONTROLS -->

<fieldset class="options">

<h3>Show Slide Show Controls</h3>

<p>Display controls for playing slide shows in a small menu beneath the photo alumbs.  If you don't want to show this, set this to "No".</p>

<table class="editform" cellpadding="5" cellspacing="2">
<tr>
<th width="250" valign="top"><label for="DM_PHOTOALBUM_SLIDESHOW_CONTROLS">Show Slide Show Controls:</label></th>
<td>
<select id="DM_PHOTOALBUM_SLIDESHOW_CONTROLS" name="DM_PHOTOALBUM_SLIDESHOW_CONTROLS">
<option <?php  if(get_option("DM_PHOTOALBUM_SLIDESHOW_CONTROLS") == "true") echo "SELECTED" ?> value="true">YES</option>
<option <?php  if(get_option("DM_PHOTOALBUM_SLIDESHOW_CONTROLS") == "false") echo "SELECTED" ?> value="false">NO</option>
</select>Default: <code>Yes</code>
</td>
</tr>
</table>

</fieldset>

<!-- PROPERTY: DM_PHOTOALBUM_SLIDESHOW_CONTROLS -->

<!-- PROPERTY: DM_PHOTOALBUM_SLIDESHOW_AUTOPLAY -->

<fieldset class="options">

<h3>Autoplay Slide Show</h3>

<p>Automatically play slideshow when album loads.  This is not dependent on showing Slide Show Controls (above).  If you want to start playing your slideshow automatically, set this to "Yes".</p>

<table class="editform" cellpadding="5" cellspacing="2">
<tr>
<th width="250" valign="top"><label for="DM_PHOTOALBUM_SLIDESHOW_AUTOPLAY">Autoplay Slide Show:</label></th>
<td>
<select id="DM_PHOTOALBUM_SLIDESHOW_AUTOPLAY" name="DM_PHOTOALBUM_SLIDESHOW_AUTOPLAY">
<option <?php  if(get_option("DM_PHOTOALBUM_SLIDESHOW_AUTOPLAY") == "true") echo "SELECTED" ?> value="true">YES</option>
<option <?php  if(get_option("DM_PHOTOALBUM_SLIDESHOW_AUTOPLAY") == "false") echo "SELECTED" ?> value="false">NO</option>
</select>Default: <code>No</code>
</td>
</tr>
</table>

</fieldset>

<!-- PROPERTY: DM_PHOTOALBUM_SLIDESHOW_AUTOPLAY -->

<!-- PROPERTY: DM_SHOW_NAVIGATION_HINTS -->

<fieldset class="options">

<h3>Display Navigation Hints</h3>

<p>This setting turns on/off the navigation hints that display when mousing over an image (left/right navigation).  This is on by default.</p>

<table class="editform" cellpadding="5" cellspacing="2">
<tr>
<th width="250" valign="top"><label for="DM_SHOW_NAVIGATION_HINTS">Display Navigation Hints:</label></th>
<td>
<select id="DM_SHOW_NAVIGATION_HINTS" name="DM_SHOW_NAVIGATION_HINTS">
<option <?php  if(get_option("DM_SHOW_NAVIGATION_HINTS") == "true") echo "SELECTED" ?> value="true">YES</option>
<option <?php  if(get_option("DM_SHOW_NAVIGATION_HINTS") == "false") echo "SELECTED" ?> value="false">NO</option>
</select>Default: <code>YES</code>
</td>
</tr>
</table>

</fieldset>

<!-- PROPERTY: DM_SHOW_NAVIGATION_HINTS -->

<!-- PROPERTY: DM_ALBUMS_EXTERNAL_LINK_TARGET -->

<fieldset class="options">

<h3>Photo Link Target</h3>

<p>This is the target for external links (Photo Link set in the DM Albums Detail Manager).  Choose "Main Window" to open links in the main window (user will leave your page) and "New Window" top open a new window.</p>

<table class="editform" cellpadding="5" cellspacing="2">
<tr>
<th width="250" valign="top"><label for="DM_ALBUMS_EXTERNAL_LINK_TARGET">Photo Link Target:</label></th>
<td>
<select id="DM_ALBUMS_EXTERNAL_LINK_TARGET" name="DM_ALBUMS_EXTERNAL_LINK_TARGET">
<option <?php  if(get_option("DM_ALBUMS_EXTERNAL_LINK_TARGET") == "_top") echo "SELECTED" ?> value="_top">Main Window</option>
<option <?php  if(get_option("DM_ALBUMS_EXTERNAL_LINK_TARGET") == "_newWindow") echo "SELECTED" ?> value="_newWindow">New Window</option>
</select>Default: <code>Main Window</code>
</td>
</tr>
</table>

</fieldset>

<!-- PROPERTY: DM_ALBUMS_EXTERNAL_LINK_TARGET -->

<!-- PROPERTY: DM_ALBUMS_EXTERNAL_CSS -->

<fieldset class="options">

<h3>Custom Stylesheet</h3>

<p>This allows you to enter the Full URL to a custom style sheet to override the default settings found in DM Albums.  Leave this blank if you don't need a custom style sheet.</p>

<table class="editform" cellpadding="5" cellspacing="2">
<tr>
<th width="250" valign="top"><label for="DM_ALBUMS_EXTERNAL_CSS">Custom Stylesheet (URL):</label></th>
<td>
<input type="text" id="DM_ALBUMS_EXTERNAL_CSS" name="DM_ALBUMS_EXTERNAL_CSS" size="100" value="<?php echo( get_option("DM_ALBUMS_EXTERNAL_CSS") ); ?>" />
Default: <code></code> (blank)
</td>
</tr>
</table>

</fieldset>

<!-- PROPERTY: DM_ALBUMS_EXTERNAL_CSS -->

<!-- PROPERTY: DM_PHOTOALBUM_HIDE_LOADING_MESSAGE -->

<fieldset class="options">

<h3>Show Loading Message</h3>

<p>This setting allows you to turn off the loading message that appears while a photo is loading. If you don't want the loading message to be displayed, set this to "No".</p>

<table class="editform" cellpadding="5" cellspacing="2">
<tr>
<th width="250" valign="top"><label for="DM_PHOTOALBUM_HIDE_LOADING_MESSAGE">Show Loading Message:</label></th>
<td>
<select id="DM_PHOTOALBUM_HIDE_LOADING_MESSAGE" name="DM_PHOTOALBUM_HIDE_LOADING_MESSAGE">
<option <?php  if(get_option("DM_PHOTOALBUM_HIDE_LOADING_MESSAGE") == "false") echo "SELECTED" ?> value="false">YES</option>
<option <?php  if(get_option("DM_PHOTOALBUM_HIDE_LOADING_MESSAGE") == "true") echo "SELECTED" ?> value="true">NO</option>
</select>Default: <code>Yes</code>
</td>
</tr>
</table>

</fieldset>

<!-- PROPERTY: DM_PHOTOALBUM_HIDE_LOADING_MESSAGE -->

<!-- PROPERTY: DM_PHOTOALBUM_HIDE_LOADING_MESSAGE_SLIDESHOW -->

<fieldset class="options">

<h3>Show Loading Message (Slideshows)</h3>

<p>This setting allows you to turn off the loading message that appears while a photo is loading while a slideshow is playing. If you don't want the loading message to be displayed, set this to "No".  Please note that if the "Show Loading Message" setting above already has the loading message switched off, this setting will have no effect.</p>

<table class="editform" cellpadding="5" cellspacing="2">
<tr>
<th width="250" valign="top"><label for="DM_PHOTOALBUM_HIDE_LOADING_MESSAGE_SLIDESHOW">Show Loading Message (Slideshows):</label></th>
<td>
<select id="DM_PHOTOALBUM_HIDE_LOADING_MESSAGE_SLIDESHOW" name="DM_PHOTOALBUM_HIDE_LOADING_MESSAGE_SLIDESHOW">
<option <?php  if(get_option("DM_PHOTOALBUM_HIDE_LOADING_MESSAGE_SLIDESHOW") == "false") echo "SELECTED" ?> value="false">YES</option>
<option <?php  if(get_option("DM_PHOTOALBUM_HIDE_LOADING_MESSAGE_SLIDESHOW") == "true") echo "SELECTED" ?> value="true">NO</option>
</select>Default: <code>Yes</code>
</td>
</tr>
</table>

</fieldset>

<!-- PROPERTY: DM_PHOTOALBUM_HIDE_LOADING_MESSAGE_SLIDESHOW -->

<!-- PROPERTY: DM_PHOTOALBUM_PHOTO_QUALITY -->

<fieldset class="options">

<h3>Photo Quality</h3>

<p>Choose the quality of the photos displayed in DM Albums.  This is the output quality of the images and does not impact the quality of the cached or original images.  The higher the quality, the better the photos look, but also the slower the album will load.  85 (thecdefault) is a good balance between quality and loading speed, but we don't recommend going below 75.</p>

<table class="editform" cellpadding="5" cellspacing="2">
<tr>
<th width="250" valign="top"><label for="DM_PHOTOALBUM_PHOTO_QUALITY">Photo Quality:</label></th>
<td>
<select id="DM_PHOTOALBUM_PHOTO_QUALITY" name="DM_PHOTOALBUM_PHOTO_QUALITY">
<?php 

for($quality_level = 50; $quality_level <= 100; $quality_level+=5)
{
	?><option <?php  if(get_option("DM_PHOTOALBUM_PHOTO_QUALITY") == $quality_level) echo "SELECTED" ?> value="<?php  print($quality_level); ?>"><?php  print($quality_level); ?></option><?php  }

?>
</select>

Default: <code>85</code>
</td>
</tr>
</table>

</fieldset>

<!-- PROPERTY: DM_PHOTOALBUM_PHOTO_QUALITY -->

<!-- PROPERTY: DM_PHOTOALBUM_MERGESUBDIRECTORIES -->

<fieldset class="options">

<h3>Merge Subdirectories</h3>

<p>Set this option to "YES" if you would like DM Albums to merge subdirectories of photos into one Photo Alubm.  This allows you to organize your photos into directories while still viewing all photos in on album.  If you only want to display photos in the directory itself (no subdirectories), set this option to "NO".</p>

<table class="editform" cellpadding="5" cellspacing="2">
<tr>
<th width="250" valign="top"><label for="DM_PHOTOALBUM_MERGESUBDIRECTORIES">Merge Subirectories:</label></th>
<td>
<select id="DM_PHOTOALBUM_MERGESUBDIRECTORIES" name="DM_PHOTOALBUM_MERGESUBDIRECTORIES">
<option <?php  if(get_option("DM_PHOTOALBUM_MERGESUBDIRECTORIES") == "true") echo "SELECTED" ?> value="true">YES</option>
<option <?php  if(get_option("DM_PHOTOALBUM_MERGESUBDIRECTORIES") != "true") echo "SELECTED" ?> value="false">NO</option>
</select>Default: <code>No</code>
</td>
</tr>
</table>

</fieldset>

<!-- PROPERTY: DM_PHOTOALBUM_MERGESUBDIRECTORIES -->

<!-- PROPERTY: DM_PHOTOALBUM_ALLOWDOWNLOAD -->

<fieldset class="options">

<h3>Allow Direct Download</h3>

<p>Direct download of the original image via the context menu.  If you don't want people to download the original image, set this to "No".</p>

<table class="editform" cellpadding="5" cellspacing="2">
<tr>
<th width="250" valign="top"><label for="DM_PHOTOALBUM_ALLOWDOWNLOAD">Allow Direct Download:</label></th>
<td>
<select id="DM_PHOTOALBUM_ALLOWDOWNLOAD" name="DM_PHOTOALBUM_ALLOWDOWNLOAD">
<option <?php  if(get_option("DM_PHOTOALBUM_ALLOWDOWNLOAD") == "true") echo "SELECTED" ?> value="true">YES</option>
<option <?php  if(get_option("DM_PHOTOALBUM_ALLOWDOWNLOAD") == "false") echo "SELECTED" ?> value="false">NO</option>
</select>Default: <code>Yes</code>
</td>
</tr>
</table>

</fieldset>

<!-- PROPERTY: DM_PHOTOALBUM_ALLOWDOWNLOAD -->

<!-- PROPERTY: DM_PHOTOALBUM_PREFIX -->

<fieldset class="options">

<h3>Album Prefix</h3>

<p>The prefix to identify a Album to embed.  Using the prefix syntax means that a link preceeded by the prefix will be replaced by the embedded Album.  Current setting: <code><?php  echo $DM_PHOTOALBUM_PREFIX; ?>&lt;link&gt;</code></p>

<table class="editform" cellpadding="5" cellspacing="2">
<tr>
<th width="250" valign="top"><label for="DM_PHOTOALBUM_PREFIX">Album Prefix:</label></th>
<td>
<input type="text" id="DM_PHOTOALBUM_PREFIX" name="DM_PHOTOALBUM_PREFIX" size="40" value="<?php echo( get_option("DM_PHOTOALBUM_PREFIX") ); ?>" /><br />
Default: <code>Album: </code>
</td>
</tr>
</table>

</fieldset>

<!-- PROPERTY: DM_PHOTOALBUM_PRESERVE_LINK -->

<fieldset class="options">

<h3>Preserve Album Link</h3>

<p>Preserve the Album link (as defined above) after Album has been embedded.  This results in <code><?php  echo $DM_PHOTOALBUM_PREFIX; ?>&lt;link&gt;</code> being displayed below the embedded Album.</p>

<table class="editform" cellpadding="5" cellspacing="2">
<tr>
<th width="250" valign="top"><label for="DM_PHOTOALBUM_PRESERVE_LINK">Preserve Album Link:</label></th>
<td>
<select id="DM_PHOTOALBUM_PRESERVE_LINK" name="DM_PHOTOALBUM_PRESERVE_LINK">
<option <?php  if(get_option("DM_PHOTOALBUM_PRESERVE_LINK") == "true") echo "SELECTED" ?> value="true">YES</option>
<option <?php  if(get_option("DM_PHOTOALBUM_PRESERVE_LINK") == "false") echo "SELECTED" ?> value="false">NO</option>
</select>
<br />
Default: <code>NO</code>
</td>
</tr>
</table>

</fieldset>

<!-- PROPERTY: DM_PHOTOALBUM_APP_WIDTH -->

<fieldset class="options">

<h3>Album Width</h3>

<p>Width of the Album</p>

<table class="editform" cellpadding="5" cellspacing="2">
<tr>
<th width="250" valign="top"><label for="DM_PHOTOALBUM_APP_WIDTH">Width:</label></th>
<td>
<input type="text" id="DM_PHOTOALBUM_APP_WIDTH" name="DM_PHOTOALBUM_APP_WIDTH" size="40" value="<?php echo( get_option("DM_PHOTOALBUM_APP_WIDTH") ); ?>" /><br />
Default: <code>500</code>
</td>
</tr>
</table>

</fieldset>

<!-- PROPERTY: DM_PHOTOALBUM_APP_HEIGHT -->

<fieldset class="options">

<h3>Album Height</h3>

<p>Height of the Album</p>

<table class="editform" cellpadding="5" cellspacing="2">
<tr>
<th width="250" valign="top"><label for="DM_PHOTOALBUM_APP_HEIGHT">Height:</label></th>
<td>
<input type="text" id="DM_PHOTOALBUM_APP_HEIGHT" name="DM_PHOTOALBUM_APP_HEIGHT" size="40" value="<?php echo( get_option("DM_PHOTOALBUM_APP_HEIGHT") ); ?>" /><br />
Default: <code>492</code>
</td>
</tr>
</table>

</fieldset>

<!-- PROPERTY: DM_THUMBNAIL_LOCATION -->

<fieldset class="options">

<h3>Thumbnail Location</h3>

<p>The location of the Album's thumbnail bar.  The bar may be displayed along the top or the bottom.</p>

<table class="editform" cellpadding="5" cellspacing="2">
<tr>
<th width="250" valign="top"><label for="DM_THUMBNAIL_LOCATION">Location:</label></th>
<td>
<select id="DM_THUMBNAIL_LOCATION" name="DM_THUMBNAIL_LOCATION">
<option <?php  if(get_option("DM_THUMBNAIL_LOCATION") == "TOP") echo "SELECTED" ?> value="TOP">TOP</option>
<option <?php  if(get_option("DM_THUMBNAIL_LOCATION") == "BOTTOM") echo "SELECTED" ?> value="BOTTOM">BOTTOM</option>
</select>
<br />
Default: <code>TOP</code>
</td>
</tr>
</table>

</fieldset>

<!-- PROPERTY: DM_THUMBNAIL_SIZE -->

<fieldset class="options">

<h3>Thumbnail Size</h3>

<p>The maximum height and width of thumbnails.  This value constitutes the majority of the height of the thumbnail bar.</p>

<table class="editform" cellpadding="5" cellspacing="2">
<tr>
<th width="250" valign="top"><label for="DM_THUMBNAIL_SIZE">Size:</label></th>
<td>
<input type="text" id="DM_THUMBNAIL_SIZE" name="DM_THUMBNAIL_SIZE" size="40" value="<?php echo( get_option("DM_THUMBNAIL_SIZE") ); ?>" /> (square)<br />
Default: <code>60</code>
</td>
</tr>
</table>

</fieldset>

<!-- PROPERTY: DM_THUMBNAIL_PADDING -->

<fieldset class="options">

<h3>Thumbnail Padding</h3>

<p>The amount of padding around each thumbnail in the thumbnail bar.</p>

<table class="editform" cellpadding="5" cellspacing="2">
<tr>
<th width="250" valign="top"><label for="DM_THUMBNAIL_PADDING">Padding:</label></th>
<td>
<input type="text" id="DM_THUMBNAIL_PADDING" name="DM_THUMBNAIL_PADDING" size="40" value="<?php echo( get_option("DM_THUMBNAIL_PADDING") ); ?>" /><br />
Default: <code>5</code>
</td>
</tr>
</table>

</fieldset>

<!-- PROPERTY: DM_DISPLAY_CAPTIONS -->

<fieldset class="options">

<h3>Display Captions</h3>

<p>A setting to turn captions on and off.  When captions are turned on, you have the ability to add/edit captions for images when you are logged in as and administrator of WordPress.  The add/edit link appears below the photo.</p>

<table class="editform" cellpadding="5" cellspacing="2">
<tr>
<th width="250" valign="top"><label for="DM_DISPLAY_CAPTIONS">Display Captions:</label></th>
<td>
<select id="DM_DISPLAY_CAPTIONS" name="DM_DISPLAY_CAPTIONS">
<option <?php  if(get_option("DM_DISPLAY_CAPTIONS") == "1") echo "SELECTED" ?> value="1">YES</option>
<option <?php  if(get_option("DM_DISPLAY_CAPTIONS") == "0") echo "SELECTED" ?> value="0">NO</option>
</select>
<br />
Default: <code>YES</code>
</td>
</tr>
</table>

</fieldset>

<!-- PROPERTY: DM_CAPTION_EDITORS -->
<!-- 
<fieldset class="options">

<h3>Captions Editors</h3>

<p>When captions are turned on, Administrators can set what level a user has to be at (at least) in order to add/edit captions.  DutchMonkey Productions recommends user level 2 (Authors).  If you want to learn more about user levels in WordPress, visit the <a href="http://codex.wordpress.org/User_Levels" target="_newWindow">Codex</a>.</p>

<table class="editform" cellpadding="5" cellspacing="2">
<tr>
<th width="250" valign="top"><label for="DM_DISPLAY_CAPTIONS">Caption Editors:</label></th>
<td>
<select id="DM_CAPTION_EDITORS" name="DM_CAPTION_EDITORS">
<?php 

for($editor_level = 0; $editor_level <= 10; $editor_level++)
{
	?><option <?php  if(get_option("DM_CAPTION_EDITORS") == $editor_level) echo "SELECTED" ?> value="<?php  print($editor_level); ?>"><?php  print($editor_level); ?></option><?php  }

?>
</select>
<br />
Default: <code>2</code>
</td>
</tr>
</table>

</fieldset>
-->

<!-- PROPERTY: DM_DISPLAY_PHOTOCOUNT -->

<fieldset class="options">

<h3>Display Photo Count</h3>

<p>A setting to turn the photo count on and off.  When the photo count is turned on, "Photo x of y" is displayed below the photo.</p>

<table class="editform" cellpadding="5" cellspacing="2">
<tr>
<th width="250" valign="top"><label for="DM_DISPLAY_PHOTOCOUNT">Display Photo Count:</label></th>
<td>
<select id="DM_DISPLAY_PHOTOCOUNTCAPTIONS" name="DM_DISPLAY_PHOTOCOUNT">
<option <?php  if(get_option("DM_DISPLAY_PHOTOCOUNT") == "1") echo "SELECTED" ?> value="1">YES</option>
<option <?php  if(get_option("DM_DISPLAY_PHOTOCOUNT") == "0") echo "SELECTED" ?> value="0">NO</option>
</select>
<br />
Default: <code>YES</code>
</td>
</tr>
</table>

</fieldset>

<!-- PROPERTY: DM_CAPTION_HEIGHT -->

<fieldset class="options">

<h3>Caption/Photo Count bar height</h3>

<p>Determines the height of the space where the caption and/or photo count is displayed.  Overflow is clipped (not visible).  This value is ignored if both Captions and Photo Count are turned off.</p>

<table class="editform" cellpadding="5" cellspacing="2">
<tr>
<th width="250" valign="top"><label for="DM_CAPTION_HEIGHT">Caption/Photo Count Height:</label></th>
<td>
<input type="text" id="DM_CAPTION_HEIGHT" name="DM_CAPTION_HEIGHT" size="40" value="<?php echo( get_option("DM_CAPTION_HEIGHT") ); ?>" /><br />
Default: <code>32</code>
</td>
</tr>
</table>

</fieldset>

<!-- PROPERTY: DM_PHOTO_PADDING -->

<fieldset class="options">

<h3>Current Photo Padding</h3>

<p>Determines the height of the space where the caption and/or photo count is displayed.  Overflow is clipped (not visible).</p>

<table class="editform" cellpadding="5" cellspacing="2">
<tr>
<th width="250" valign="top"><label for="DM_PHOTO_PADDING">Current Photo Padding:</label></th>
<td>
<input type="text" id="DM_PHOTO_PADDING" name="DM_PHOTO_PADDING" size="40" value="<?php echo( get_option("DM_PHOTO_PADDING") ); ?>" /><br />
Default: <code>0</code>
</td>
</tr>
</table>

</fieldset>

<!-- PROPERTY: DM_TITLE_PREFIX -->

<fieldset class="options">

<h3>Album Title Prefix</h3>

<p>Determines the displayed in the title bar next to the Album name.  This is only visible when viewing Albums in Fullscreen mode.</p>

<table class="editform" cellpadding="5" cellspacing="2">
<tr>
<th width="250" valign="top"><label for="DM_TITLE_PREFIX">Title Prefix:</label></th>
<td>
<input type="text" id="DM_TITLE_PREFIX" name="DM_TITLE_PREFIX" size="40" value="<?php echo( get_option("DM_TITLE_PREFIX") ); ?>" /><br />
Default: <code></code>
</td>
</tr>
</table>

</fieldset>

<!-- PROPERTY: DM_MARGINS -->

<fieldset class="options">

<h3>Album Margins</h3>

<p>Determines margins displayed around the Albums.</p>

<table class="editform" cellpadding="5" cellspacing="2">
<tr>
<th width="250" valign="top"><label for="DM_LEFTMARGIN">Left Margin:</label></th>
<td>
<input type="text" id="DM_LEFTMARGIN" name="DM_LEFTMARGIN" size="40" value="<?php echo( get_option("DM_LEFTMARGIN") ); ?>" /><br />
Default: <code>0</code>
</td>
</tr>
<tr>
<th width="250" valign="top"><label for="DM_RIGHTMARGIN">Right Margin:</label></th>
<td>
<input type="text" id="DM_RIGHTMARGIN" name="DM_RIGHTMARGIN" size="40" value="<?php echo( get_option("DM_RIGHTMARGIN") ); ?>" /><br />
Default: <code>0</code>
</td>
</tr>
<tr>
<th width="250" valign="top"><label for="DM_TOPMARGIN">Top Margin:</label></th>
<td>
<input type="text" id="DM_TOPMARGIN" name="DM_TOPMARGIN" size="40" value="<?php echo( get_option("DM_TOPMARGIN") ); ?>" /><br />
Default: <code>0</code>
</td>
</tr>
<tr>
<th width="250" valign="top"><label for="DM_BOTTOMMARGIN">Bottom Margin:</label></th>
<td>
<input type="text" id="DM_BOTTOMMARGIN" name="DM_BOTTOMMARGIN" size="40" value="<?php echo( get_option("DM_BOTTOMMARGIN") ); ?>" /><br />
Default: <code>0</code>
</td>
</tr>
</table>

</fieldset>

<p class="submit">
<input name="Submit" value="Update Options &raquo;" type="submit" />
</p>

<?php 

if(dm_isUserAdmin())
{
?>
	
<h3>Advanced Options</h3>
<hr size="1">
<!-- PROPERTY: HOME_DIR -->

<fieldset class="options">

<h3>Home Folder</h3>

<p>This is a critical setting.  When reading a path to an image, the full path to the image is required.  This setting is assumed to be the root directory to be appended to the path passed via the <code>directory</code> or <code>currdir</code> parameter.  This is typically assumed to be the root of your webspace directory as defined in the PHP <code>DOCUMENT_ROOT</code> server variable.  The <code>DOCUMENT_ROOT</code> for your blog is displayed below to help determine what your home folder might be.  If the demo above works, this value has been set correctly.</p>

<table class="editform" cellpadding="5" cellspacing="2">
<tr>
<th width="250" valign="top"><label for="DM_HOME_DIR">Home Folder:</label></th>
<td>
<?php
if(dm_is_wamp())	$DOCUMENT_ROOT = realpath($_SERVER['DOCUMENT_ROOT']) . "\\";
else				$DOCUMENT_ROOT = realpath($_SERVER['DOCUMENT_ROOT']) . "/";	
?>
<input type="text" id="DM_HOME_DIR" name="DM_HOME_DIR" size="100" value="<?php echo( get_option("DM_HOME_DIR") ); ?>" /><br />
Web Space Root (<code>DOCUMENT_ROOT</code>): <code><?php echo $DOCUMENT_ROOT; ?></code><br/>
Default: <code><?php echo( get_option('DM_ALBUMS_CORE_DEFAULT_HOME_DIR') ); ?></code>
</td>
</tr>
</table>

</fieldset>

<!-- PROPERTY: DM_ALBUMS_UPLOADDIR -->

<fieldset class="options">

<h3>Album Upload Folder</h3>

<p>The directory to where DM Albums will upload your photo albums from the Edit/Add page and post editor.  This location has to be full server path pointing to a location below the Home Directory.  Please note that all users will upload photos into their own directories below this point, preventing users from seeing (or accidentally modifying) eachother's album.</p>
<?php 
if(dm_is_wpmu())
{
?>
<p>WordPressMU Users: To ensure that photos are uploaded into your user's own blog folders, make sure the {BLOG_ID} identifier appears somewhere in the Album Upload Folder path.  This identifier will be replaced by the user's blog id.</p>
<?php 
}
?>
<table class="editform" cellpadding="5" cellspacing="2">
<tr>
<th width="250" valign="top"><label for="DM_ALBUMS_UPLOADDIR">Album Upload Folder:</label></th>
<td>
<input type="text" id="DM_ALBUMS_UPLOADDIR" name="DM_ALBUMS_UPLOADDIR" size="100" value="<?php echo( get_option("DM_ALBUMS_UPLOADDIR") ); ?>" /><br />
Home Directory: <code><?php echo( get_option("DM_HOME_DIR") ); ?></code><br/>
Default: <code><?php echo( get_option('DM_ALBUMS_CORE_DEFAULT_UPLOADDIR') ); ?></code>

</td>
</tr>
</table>


</fieldset>

<!-- PROPERTY: DM_ALBUMS_UUP -->

<fieldset class="options">

<h3>Unique Author Upload Folders</h3>

<p>This setting allows blogs with multiple authors to upload their albums into their own unique folder.  The default allows all authors of the blog to share/modify each other's albums.  Turning this off (setting it to YES) will upload albums into a separate folder for each author, preventing authors of the blog from seeing each other's albums.</p>

<table class="editform" cellpadding="5" cellspacing="2">
<tr>
<th width="250" valign="top"><label for="DM_ALBUMS_UUP">Unique Author Upload Folders:</label></th>
<td>
<select id="DM_ALBUMS_UUP" name="DM_ALBUMS_UUP">
<option <?php  if(get_option("DM_ALBUMS_UUP") == "1") echo "SELECTED" ?> value="1">YES</option>
<option <?php  if(get_option("DM_ALBUMS_UUP") == "0") echo "SELECTED" ?> value="0">NO</option>
</select>
<br />
Default: <code>NO</code>
</td>
</tr>
</table>


</fieldset>

<!-- PROPERTY: DM_PHOTOALBUM_APP -->

<fieldset class="options">

<h3>Album Application</h3>

<p>The url of the fullscreen DM Album application.</p>

<table class="editform" cellpadding="5" cellspacing="2">
<tr>
<th width="250" valign="top"><label for="DM_PHOTOALBUM_APP">DM Album Application:</label></th>
<td>
<input type="text" id="DM_FULLSCREEN_APP" name="DM_FULLSCREEN_APP" size="100" value="<?php echo( get_option("DM_PHOTOALBUM_APP") ); ?>" /><br />

Default: <code><?php  print(get_option('DM_ALBUMS_CORE_DEFAULT_PHOTOALBUM_APP')); ?></code>

</td>
</tr>
</table>

</fieldset>

<!-- PROPERTY: DM_ALBUM_PLUGIN_APP -->

<fieldset class="options">

<h3>Album Plugin Application</h3>

<p>The url of the fullscreen DM Album application.  This should not need to be changed unless you are not using the default WP Plugin directory or have renamed the plugin.</p>

<table class="editform" cellpadding="5" cellspacing="2">
<tr>
<th width="250" valign="top"><label for="DM_ALBUM_PLUGIN_APP">DM Album Plugin:</label></th>
<td>
<input type="text" id="DM_ALBUM_PLUGIN_APP" name="DM_ALBUM_PLUGIN_APP" size="100" value="<?php echo( get_option("DM_ALBUM_PLUGIN_APP") ); ?>" /><br />
Default: <code><?php  print(get_option('DM_ALBUMS_CORE_DEFAULT_ALBUM_PLUGIN_APP')); ?></code>
</td>
</tr>
</table>

</fieldset>

<!-- PROPERTY: DM_PHOTOALBUM_INCLUDE_COMMENTS -->

<fieldset class="options">

<h3>Include Comments</h3>

<p>By default, DM Albums checks for album syntax in comments.  If you wish to disable this functionality, set this option to "NO".</p>

<table class="editform" cellpadding="5" cellspacing="2">
<tr>
<th width="250" valign="top"><label for="DM_PHOTOALBUM_INCLUDE_COMMENTS">Include Comments:</label></th>
<td>
<select id="DM_PHOTOALBUM_INCLUDE_COMMENTS" name="DM_PHOTOALBUM_INCLUDE_COMMENTS">
<option <?php  if(get_option("DM_PHOTOALBUM_INCLUDE_COMMENTS") != "NO") echo "SELECTED" ?> value="YES">YES</option>
<option <?php  if(get_option("DM_PHOTOALBUM_INCLUDE_COMMENTS") == "NO") echo "SELECTED" ?> value="NO">NO</option>
</select>
<br />
Default: <code>YES</code>
</td>
</tr>
</table>

</fieldset>

<p class="submit">
<input name="Submit" value="Update Options &raquo;" type="submit" />
</p>

<div class="dm_warning">
<fieldset class="options">

<h3>Reset Configuration</h3>

<p>If you are having trouble with your installation, click the button below to reset your installation to it's default settings.</p>

<table class="editform" cellpadding="5" cellspacing="2">
<tr>
<th width="250" valign="top"><label for="defaults">Reset Default Configuration:</label></th>
<td>
<input name="reset_config" value="&laquo; RESET CONFIGURATION &raquo;" type="submit" onClick="return confirm('This will reset all the setting set on this page to the default settings.  Are you sure you want to do this?\n\n\'Cancel\' to stop, \'OK\' to continue.');"/></td>
</tr>
</table>

</fieldset>
</div>
<?php }?>

</form>

</div>
</div>

<script language="JavaScript">
<?php  if($APP_CONFIGURED_CORRECTLY)
{
?>
	ShowHide();
<?php  }
?>
</script>

<?php  }

else
{
?>
<p>Sorry, you do not have the user level required to adjust these settings.</p>
<?php  }

?>