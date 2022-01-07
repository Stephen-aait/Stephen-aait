<?php 

$DM_RESOURCE_ROOT = get_bloginfo('home') . '/wp-content/plugins/dm-albums/';

?>
<div class="dm_master_container hide-if-no-js" id="dm_albums_manager_container" style="display: none;">

<h4>Existing Albums</h4>

<p style="float: right; margin-top: -26px;"><a href="#" onClick="dm_ShowHideHelp();"><img id="img_dm_help_top" src="<?php print($DM_RESOURCE_ROOT); ?>ui/help.gif" border="0"/></a></p>

<p style="margin-left: -2px; margin-bottom: 10px;"><small><strong>&raquo; show albums by: </strong><a href="#" onClick="dm_albums_UpdateAlbumList('newest'); this.blur();" style="font-weight: bold; padding-left: 5px;"><img  id="img_dm_sort_newest" src="<?php print($DM_RESOURCE_ROOT); ?>ui/sort_newest.gif" border="0"/></a> <a href="#" onClick="dm_albums_UpdateAlbumList('alpha'); this.blur();" style="font-weight: bold; padding-left: 5px;"><img id="img_dm_sort_name" src="<?php print($DM_RESOURCE_ROOT); ?>ui/sort_name.gif" border="0"/></a></small></p>
<div id="dm_ExistingAlbums" class="dm_container"></div>

<h4>Update Existing or Add New Album</h4>

<div id="div_dm_uploader" style="display: none; padding-top: 5px;">

<div style="padding-left: 7px; margin-bottom: 15px; white-space: nowrap;">
<table width="100%"><tr><td width="100">Album Name:</td><td style="padding-right: 5px;"><input type="text" id="dm_album_albumname" name="dm_album_albumname" value="" style="width: 100%;"/></td></tr></table>
</div>
<div class="dm_container">
<span id='uploadtarget'></span>
<div class="fieldset flash" id="fsUploadProgress">
<span class="legend">Upload Queue</span>
</div>
</div>
<div id="dm_upload_buttons_container"></div>

</div>

<p id="p_dm_bottom_help" style="float: right; margin-top: -20px; display: none;"><a href="#" onClick="dm_ShowHideHelp();"><img id="img_dm_help_bottom" src="<?php print($DM_RESOURCE_ROOT); ?>ui/help.gif" border="0"/></a></p>

<div class="dm_container">
<div id="div_dm_help" class="fieldset flash" style="display: none;">
<p><small><b>Creating Photo Albums:</b><br/>In order to create photo albums, simply choose an album name (type it into the "Album Name" field under "Update Existing or Add New Album", click "Browse" to browse your computer's hard drive to find the photos you'd like to put in your album.  (You can choose multiple photos and upload them all at once.)  To start the upload, click "Start Upload".  Once the upload is complete, your album name will appear in the "Existing Albums" list.  To insert an album into your post, click the "Insert" button next to the album name.</small></p>
<p><small><b>Updating Photo Albums:</b><br/>If you'd like to add photos to an existing photo album, simply click the existing album's name, and then follow the procedure above to upload more photos into the photo album.  Please note that any photos with the same name will be overwritten by the uploaded photos.</small></p>
<p><small><b>Insert Album into Post:</b><br/>To insert an album into your post, click the "Insert" button next to the album name.</small></p>
<p><small><b>Deleting an Existing Album:</b><br/>To delete an album, click the red cross to the right of the album's Insert button.  Please be aware this will permanently delete the album, all the photos, and all the captions.</small></p>
<p><small><b>How Existing Albums Are Displayed:</b><br/>By default, the albums in the "Existing Albums" list are displayed with the newest album on top of the list.  If you prefer to see the list in alphabetical order, simply click the "name" button next to "show albums by" heading.  Clicking either will also refresh the list and display any new album added outside the DM Albums&#153; Album Manager (as long as they were uploaded to the same directory.)</small></p>
<p><small><b>Moving the DM Albums&#153; Album Manager:</b><br/>You can drag-and-drop the DM Albums&#153; Album Manager to either the sidebar or below the editor, just like all the other boxes in the WordPress editor.  However, once you've moved it, the upload system needs to reset itself in order to continue functioning.  Under most circumstances, this is not a problem and you will most likely not even notice.  However, if you have photos in the upload queue or if photos are currently uploading, this will reset (empty) the queue.</small></p>
</div>
</div>
<div class="fieldset flash" id="divErrorQueueContainer" style="display: none; visibility: hidden; margin-top: 25px;">
<span class="legend">Cancellation/Error Log</span>
<a href="#" class="button" style="position: relative; left: -124px;" onClick="document.getElementById('divErrorQueue').innerHTML=''; document.getElementById('divErrorQueueContainer').style.visibility = 'hidden';">&nbsp;Clear Log&nbsp;</a>
<div id="divErrorQueue" style="padding-left: 10px"></div>
</div>

</div>

<div id="dm_albums_darkener" style="position:absolute; background-color: #000000; display: block;" onClick="dm_HideDetailManager(); dm_HideAlbumSorter();"></div>