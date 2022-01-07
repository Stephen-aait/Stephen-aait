=== DM Albums ===
Contributors: fstrack
Donate link: http://www.dutchmonkey.com/
Tags: Photo Album Plugin, Inline Photo Album, Inline Photo Gallery, Slideshow, Photo Manager, Photo Sorter, AJAX
Requires at least: 2.7
Tested up to: 2.9
Stable tag: 2.4.8.2

DM Albums&#153; is an inline photo album/gallery plugin that displays high quality images and thumbnails perfectly sized to your blog.

== Description ==

DM Albums&#153; is an inline photo album/gallery plugin that displays high quality images and thumbnails perfectly sized to your blog.

Features:

* New in version 2.4: Support for drag-n-drop photo sorting directly through the WordPress editor, as well as the ability to add photo links to the the main photo.  (This functionality is buggy in IE8, but works in all other major browsers.  We are working on a solution to offer full support for IE8.)
* New in version 2.3: Album Detail Manager which allows you to add/edit captions from within the WordPress editor.  After uploading your album, clikc the "Manage Captions and Photos" link below the album name.  This allows you to add/edit photo captions as well as delete individual photos from an album.  Also new to version 2.3 is full WordPress MU (Multi-User) support.  Please note, some users have had trouble with displaying photo albums after upgrading; to remedy this, Log into the DM Albums Options panel and refresh your settings (click "Show Settings" and then "Update").  If that doesn't do the trick, anecdotal evidence suggests that setting the "Merge Directories" option to "No" and "Album Prefix" to "Photo Album" and then back to "Album".  We have also pushed a soft update which has a "Reset Configuration" button under Advanced Settings which can be used to reset DM Albums to it's default setup.  If you don't see these button, every option shows the default settings, and you can manually set them back to their defaults and click "update options".  
* New in 2.0: Full Album management capabilities available in the WordPress editor.  This allows you to add, modify, and delete photo albums right in the WordPress editor, complete with full multi-file uploading.
* DM Albums&#153; takes full resolution photos and automatically builds thumbnails and allows browsing of the photo album in-line with your post, meaning your visitors can view the entire photo album without leaving your post
* DM Albums&#153; provides a link to the full screen version of your photo album in case your readers wish to admire the full beauty of your photos
* Fully configurable through the Admin panel.

<p>DM Albums&#153; is a powerful WordPress plugin based on DM PhotoAlbums&#153;. DM Albums&#153; takes full resolution photos and automatically builds thumbnails and allows browsing of the photo album in-line with your post, meaning your visitors can view the entire photo album without leaving your post.  Once you follow the simple configuration to set up and customize the look of DM Albums&#153; in wordpress, simply upload full-resolution photos to a directory on your web site, and link to it in your blog post - DM Albums&#153; will do the rest, even allowing administrator users of your blog to add captions to the Photo Album - directly through the blog post (as long as they're logged in).</p>

<p>As of version 2.0, you can now also manage your photo albums directly through the WordPress editor and even insert the code required for embedding your photo album!</p>

<p>Of course, you don't have to use DM Albums&#153; in a blog post. With just a basic understanding of HTML, You can use it on any website simply by copying and pasting a few lines of HTML into your webpage.</p>
<p>DM Albums&#153; uses AJAX to provide smooth interaction while viewing the photo album. DM Albums&#153; is different from other photo management applications in that it strives to provide the highest-resolution photos possible to the user. Simply upload full resolution images to your web site, point DM Albums&#153; at the directory they are located in, and DM Albums&#153; does the rest, scaling the photos to the perfect size to fit the user's screen. As if that's not good enough, it also caches that size of the photo on the server in case it needs to use that size again. The next time it needs to scale the photo, it doesn't automatically go back to the original, full-sized photo; it will find the photo in the cache that has the nearest size bigger than it needs and scales that one instead, improving performance and lightening the load on your server.   <a href="http://www.dutchmonkey.com/?file=products/dm-albums/dm-albums.html" class="nounderline">Learn More and try the Live Demo</a>!</p>

== Installation ==

<p><b>Installation</b><br>

<p><b>Automatic (recommended)</b></p>
Click "Add New" in the WordPress Plugins menu.  When the page loads, type "DM Albums" into the Search box.  Find DM Albums in the list (accept no imitations!) Click "Install" and follow the on-screen instructions.
<br>
<br>

<p><b>Do-It-Your-Self</b></p>
To install, simply download the zip file, upload it to your WordPress plugins directory, and unzip the file there. Then log into your WordPress Admin and activate  (listed as DM-Albums) in the Plugins panel.  After that, you can configure DM Albums&#153; by clicking the link "DM-Albums" in the WordPress Options panel.  For more information on how to install WordPress Plugins, visit the <a href="http://codex.wordpress.org/Managing_Plugins#Installing_Plugins" class="normal">WordPress Codex</a>.</p>

There are two options for installing DM Albums&#153;:

Option 1:

1. Download zip file
1. Upload the zip file to your 'wp-content/plugins' folder
1. Log in to your server using Telnet or SSH.  
1. Navigate to your 'wp-contents/plugins' folder and unzip dm-albums.zip

Option 2:

1. Download zip file
1. Extract (unzip) the contents of the archive
1. Upload the folder 'dm-albums' and all it's contents to your 'wp-content/plugins' folder.  	

Active the Plugin:

1. Activate the DM Albums&#153; plugin through the 'Plugins' menu in WordPress
1. Click Options > DM Albums&#153; to congifure the plugin.
1. Place one of the many options for embedding the album in your post.  Example: [album: http://yourdomain.com/path/to/album/]
</p>

<p><strong>UPGRADE INSTRUCTIONS</strong><br>

Most files have been updated, so the best thing to do is to delete the old installation and reinstall from scratch.  If you made any UI customizations to the stylesheet, backup dm-albums/ui/styles.css before updating, and copy it back after updating.  Then update your original dm-albums/ui/styles.css with the code in the following style sheet:
http://dutchmonkey.com/products/dm-albums/dm-albums.update.css.<br/>
Remember to empty your browser's cache in order to make sure you get all the new files loaded!<br/>
<br/>
DutchMonkey Productions always recommends that you backup up your entire DM Albums&#153; installation before installing the updated version in order to ensure you can revert back to the previous version in case you liked it better!<br/>

<br/>

</p>

== Changelog ==
= 2.4.8.2 = <ul><li>Misc bug fixes including AJAX and improved caption support.</ul>
= 2.4.8.1 = 
<ul>
<li>Further improvements for Windows server (i.e. WAMP) support.
</ul>

= 2.4.8 = 
<ul>
<li>Improved Windows server (i.e. WAMP) support.
</ul>	

= 2.4.7 = 
<ul>
<li>Fixed some small CSS bugs</li>
<li>Improved support for Google Chrome and Safari</li>
<li>Misc bug fixes</li>
<li>Added support in the image processing API for cropping images (not used in photo rendering)</li>
</ul>	
		
= 2.4.6 = 
<ul>
<li>Fixed typos</li>
</ul>

= 2.4.5 = 
<ul>
<li>Added option to parse comments for [album:] syntax</li>
<li>Improved WAMP support</li>
<li>Misc bug fixes</li>
</ul>


= 2.4.4 = 
<ul>
<li>Fixed bug in the function_exists logic to test for the wp_mkdir_p function.</li>
</ul>

= 2.4.3 =
<ul>
<li>Added controls for viewing/disabling all controls including "powered by", "slideshow", "fullscreen", and "browsing hints".</li>
<li>Misc minor bug fixes.</li>
</ul>

= 2.4.2 =
<ul>
<li>In compliance with the fourth restriction of the WordPress Plugin Doctrine (http://wordpress.org/extend/plugins/about/), we have updated our plugin to make the Powered By tagline optionally displayed (off by default)</li>
</ul>

= 2.4.1 =
<ul>
<li>More fault-tollerant approach to making folders.
</ul>

= 2.4 =
<ul>
<li>Security service updates, including an important download vulnerability patch
<li>Photo Sorter (by popular request).  This gives drag-n-drop photo sorting capabilities through the DM Albums WordPress editor.  This functionality is buggy in Internet Explorer, but works well in Firefox, Safari, and Chrome.  We will continue to work on getting an IE-compatible version out.  It works better in IE8 Compatibility mode, but in this mode has some ugly rendering issues.
<li>Photo Link support (by popular request).  The ability to add main photo links has been added.  Add your links through the DM Albums Album Detail Manager.
<li>Misc minor bug fixes
</ul>
		
= 2.3.3 =
<ul>
<li>Security service updates
<li>Enhanced (beta) WAMP support
<li>Misc minor bug fixes
</ul>

= 2.3.2 =
<ul>
<li>Added php include for including DM Albums outside your WordPress installation.  More information: <a href="http://blog.dutchmonkey.com/product-release/dm-albums-external-demo/">http://blog.dutchmonkey.com/product-release/dm-albums-external-demo/</a>
<li>Improved support for Safari
</ul>

= 2.3.1 =
<ul>
<li>Service release containing several minor enhancements
<li>Misc Bug fixes
</ul>
		
= 2.3 =
<ul>
<li>Album Detail Manager added to allow adding captions and deleting individual photos out of albums.
<li>Add support for external CSS style sheet for overriding default styles.  Place this stylesheet outside the dm-albums plugin directory to ensure WordPress does not delete your stylesheet during automatic updates.
<li>Continued hardnening of upload/delete security.  Thanks to safety of nDarkness.com for continuing to assist in tightening things up.
<li>Continued expansion of WPMU support and multi-user security.  Continued thanks to Adam of nDarkness.com for his tireless testing, coding, and recommendations.
<li>Path mapping and upload permissions updates for more reliable file uploading and album management.  If you are having difficulty uploading and seeing your albums, use the recommended default settings as found in the DM Albums Options (Admin) panel.
<li>PHP EXIF bug fix (for users without EXIF support on their systems.  This most often manifests itself in showing a blank DM Albums Options (Admin) panel.
<li>Misc PHP Bug fixes
<li>Misc CSS Bug fixes
</ul>

= 2.1.1 =
<ul>
<li>Added support for WordPress Multi User (MU) Eidtion
<li>Misc bug fixes (php for caption editing, css for slideshow controls).
</ul>

= 2.1.1 =
<ul>
<li>Additional Security enhancements as outlined here: http://secunia.com/advisories/37119/
<li>Misc Bug fixes to support PNG and GIF photo albums.
</ul>

= 2.1 =
<ul>
<li>Security enhancements.  Thanks to safety of nDarkness.com for alerting us to a vulnerability and recommending a solution.
<li>Feature Request: Allow users to separate user upload directories.  Log into the DM Albums Admin Panel and set "Unique Author Upload Folders" to YES under "Advanced Settings".  This is turned off by default for backward compatibility.
<li>Feature Request: Restrict "Advanced Settings" area to Administrators only.
</ul>

= 2.0.1 =
<ul>
<li>Emergency security path.  All users on v.2.0 should upgrade.  Thanks to safety of nDarkness.com for alerting us to a vulnerability and recommending a solution.
</ul>

= 2.0 =
<ul>
<li>Added support for IE 8
<li>Added Photo Upload/Album Management support (directly through the WordPress Editor)
<li>Added Support to automatically insert the [album: ] code syntax into post or page
</ul>

== Upgrade Notice ==

= 2.4.4 = 
Users who upgraded from previous versions to 2.4 and got broken images should upgrade.

= 2.4.3 =
Added controls for viewing/disabling all controls including "powered by", "slideshow", "fullscreen", and "browsing hints". DutchMonkey Productions considers this a low-priority update.

= 2.4.2 =
In compliance with the fourth restriction of the WordPress Plugin Doctrine (http://wordpress.org/extend/plugins/about/), we have updated our plugin to make the Powered By tagline optionally displayed (off by default).  DutchMonkey Productions considers this a low-priority update.

= 2.4.1 =
If you are experiencing errors since upgrading to 2.4, you should upgrade to 2.4.1.

= 2.4 =
All users should upgrade to the latest version as, amoung many updates and features, contains critical security updates that patch a serious security flaw.

== Frequently Asked Questions ==

= How do I use DM Albums outside my blog? = 

You can do this by including dm-albums-external.php and making a call to dm_printalbum.  For more full coverage on this, please see http://blog.dutchmonkey.com/product-release/dm-albums-external-demo/.

= After Upgrading to v2.3, everything works except now my posts don't show the photo album but instead show the code: [album: http://mydomain.com/path/to/album/] =

Some users have had trouble with displaying photo albums after upgrading; to remedy this, Log into the DM Albums Options panel and refresh your settings (click "Show Settings" and then "Update").  If that doesn't do the trick, anecdotal evidence suggests that setting the "Merge Directories" option to "No" and "Album Prefix" to "Photo Album" and then back to "Album".  We have also pushed a soft update which has a "Reset Configuration" button under Advanced Settings which can be used to reset DM Albums to it's default setup.  If you don't see these button, every option shows the default settings, and you can manually set them back to their defaults and click "update options".  

= When I try to create an album, all the photos upload normally, but then the album doesn't show up or isn't created. =

Some users have mentioned that DM Albums are not being created when uploading: all photos seems to upload normally, but then the album doesn't show up in the list, and when you log in with FTP to check on them, the album is not here, either.

DutchMonkey Productions is working on a fix for this, but here is a workaround in the meantime:

First, check that the dm albums upload folder exists.  (You can find the path to this folder in the DM Albums Admin Panel if you're not sure where to look.)  By efault, the folder is called "dm-albums" and should be located just off the root of your blog.

If it exists, make sure the permissions are 0755 or 0777.  If it doesn't exist, you'll have to create it, set the permissions to 0755 or 0777 and then you should be good to go.

= I updated my plugin through automatically through wordpress, and now my pictures are missing! =

DutchMonkey Productions does not recommend storing your pictures in the dm-albums plugin directory.  WordPress's automatic upgrade service appears to delete the plugin and then downloads and installs a fresh copy.  If your photos are in the plugin directory (.../wp-plugins/dm-albums/) that means all your pictures will also be deleted.  So, it's best to store your pictures somewhere outside the plugin directory where they are safe from the upgrade service!

= How do I use the DM Albums&153 Album Management System in the WordPress Editor? = 
Creating Photo Albums:
In order to create photo albums, simply choose an album name (type it into the "Album Name" field under "Update Existing or Add New Album", click "Browse" to browse your computer's hard drive to find the photos you'd like to put in your album.  (You can choose multiple photos and upload them all at once.)  To start the upload, click "Start Upload".  Once the upload is complete, your album name will appear in the "Existing Albums" list.  To insert an album into your post, click the "Insert" button next to the album name.

Updating Photo Albums:
If you'd like to add photos to an existing photo album, simply click the existing album's name, and then follow the procedure above to upload more photos into the photo album.  Please note that any photos with the same name will be overwritten by the uploaded photos.

Insert Album into Post:
To insert an album into your post, click the "Insert" button next to the album name.

Deleting an Existing Album:
To delete an album, click the red cross to the right of the album's Insert button.  Please be aware this will permanently delete the album, all the photos, and all the captions.

= The DM Albums&#153; Admin Panel Complains That There Is Not Enough Memory and My Images Don't Load =

DM Albums takes high resolution images and scales (and caches) them for the viewing area.  The issue with memory is base on the fact that JPEGs, PNGs, and other compressed image formate are quite large when you open them.  Since they are uncompressed in memory, their size is a function of their dimension and color depth plus overhead.  

The best solution is to increase the amount of memory a PHP script is allowed to consume; this can be done by updating the PHP.ini file's memory_limit value.  (DM Albums&#153; attempts to do this automatically, but some server configurations ignore this.)

If you are on a shared host which doesn't allow changing PHP.ini settings or you don't want to update this value for other reasons, you can get around this limitation by loading smaller photos into DM Albums. (Finding a photo dimention that works on your server will be a trial-and-error process.

= How can I change the look of DM Albums&#153;? =

The layout of DM Albums&#153; can be changed the DM Ablums Admin Panel, while the color scheme can be edited using CSS by editing the style sheet  found in the dm-albums/ui directory (/wp-content/plugins/dm-albums/ui/styles.css).

= When I try to load DM Albums&#153; I get an error box saying "Error: Directory does not exist or can not be accessed." =

DM Albums is most likely not configured correctly.  

The error is due to the fact that the directory mapping for your Home Directory Setting and the album location specified in currdir don't resolve to an actual folder location.   DM Albums&#153; takes in a parameter called "currdir" via the URL (in the case of the DM Ablums Admin Panel preview, dm-albums.php?currdir=/wp-content/plugins/dm-albums/preview/).  This lets you point dm-albums to any directory on your server.  DM Albums then takes the currdir parameter and combines it with the Home Directory parameter set in the DM Ablums Admin Panel to find the absolute filesystem path to the photo album directory so that it can properly process the photos.

= I get an error message: "Warning: mkdir() [function.mkdir]: Permission denied..." =

In order to utilize it's state-of-the-art caching algorithm, DM Albums&#153; requires write-access to the directory containing your photo album.  In most cases, this means the directory permissions for the directory containing the photo album needs to be set to 0755.  You can accomplish this via a command line by typing chmod -R 755 directory.  (0755 is prefectly safe premission to set on a web-accessible directory.)

In rare cases, 0755 is not enough, in those cases the folder's permissions may need to be set to 0777.

= I get an error message: "Warning: mkdir() [function.mkdir]: Invalid argument in..." =

There was a bug in all DM Albums versions up to 1.2 which caused this error when DM Albums was running on a PHP installation on a Windows Server.  This bug has been fixed in release 1.2.

= I installed the plugin, but I get the following error: Call to undefined function get_option() =

The zip file for the plugin contains an extra folder.  when properly installed, the folder/file structure of the folder at /wp-content/plugins/ should look like this:

<p><b>[] dm-albums</b></p>
<p>&nbsp;&nbsp;&nbsp;&nbsp;dm-albums.php</p>
<p>&nbsp;&nbsp;&nbsp;&nbsp;screenshot-1.png</p>
<p>&nbsp;&nbsp;&nbsp;&nbsp;screenshot-2.png</p>
<p>&nbsp;&nbsp;&nbsp;&nbsp;readme.txt</p>

<p>Note: the folder dm-albums contains 5 folders: 'javascript', 'php', 'preview', 'template', 'ui' and four php files: 'config.php', 'dm-adminoptions.php', 'dm-albums.php', 'security.php'</p>

To correct this problem, follow these instructions:

1. Log into the WordPress admin panel and deactivate DM Albums. <b>THIS IS IMPORTANT!!</b>
1. Move dm-albums.php, readme.txt, and the two screen shots from /wp-content/plugins/dm-albums/ to /wp-content/plugins/
1. Move everything in the folder /wp-content/plugins/dm-albums/dm-albums to /wp-content/plugins/dm-albums (there should only be one dm-albums folder under the plugins directory) so that when you're done, the path /wp-content/plugins/dm-albums/ contains five folders (javascript, php, preview, template, ui) and 4 php files (config.php, dm-adminoptions.php, dm-albums.php, and security)
1. Log into the WordPress admin panel and active the DM Albums plugin
1. Configure DM Albums using the admin panel at Options > DM Albums

= Where Can I Find Usage Examples? =

Visit the DM Albums&#153; Online Demo for a full explaination of how to use the plugin: [DM Albums&#153; Online Demo](http://www.dutchmonkey.com/?file=products/dm-albums/dm-albums.html "DM Albums&#153; Online Demo")

= If I have further questions, can I email the developer? =

Always.  Please email development@dutchmonkey.com with any questions or suggestions.  We are always looking for ways to improve our plugin.

== Screenshots ==

1. DM-Albums inline mode
1. DM-Albums in full screen mode
1. DM-Albums Album Manager: Photo album has been named and photos ready for upload.	
1. DM-Albums Album Manager: Uploading photos to album.	
1. DM-Albums Album Manager: Upload complete, clicked "Insert" to insert [album:] syntax into post.	
1. DM-Albums Photo Sorter: Drag-n-drop Photo Sorting.	
1. DM-Albums Photo Sorter: Drag-n-drop Photo Sorting.	

== Usage ==


<p>There are three ways to use the DM Photo Albums Plugin in a blog post:</p>

<ol>
<li><p>Photo Album Prefix.</p>

<p>When using the photo album prefix (as defined below) followed by a link to the DM Albums&#153; Application (as defined below) in a blog post, the prefix and link will be replaced by the DM Photo Album.</p>

<p>Example: Photo Album: <a href="/products/dm-albums/dm-albums/dm-albums.php?currdir=/dutchmonkey.com/products/dm-albums/dm-albums/preview/" class="normal">Demo Photo Album</a></p>

<p>Example Code: Photo Album: &lt;a href="{album url}"&gt;Demo Photo Album&lt;/a&gt;</p></li>

<li><p>[album:] Syntax.</p>

<p>Simply type [album: {album url}] in your blog post and the photo album will be inserted into the post at that point.  This will usually be the direct URL to your photos, or to one of the photos in your album: </p>

<p>[album: http://yourdomain.com/path/to/albums/]</p>

<p>It may also be the URL to your photo album including the Album Application (as specified in the DM Albums&#153; admin panel) plus the currdir parameter:</p> 

<p>[album: http://yourdomain.com/path/to/album/application.php?currdir={path}]</p>

</p>
</li>

<li><p>[album:] Syntax with parameters (for advanced users).</p>

<p>DM Albums&#153; uses defaults you set up to control how each album looks.  However, you can customize the look of any DM Albums&#153; by using parameters.  When using the [album:] syntax, pass the parameters using a pipe-delimited name=value list.  Simply type </p>

<p>[album: {album url}|width={width}|height={height}|etc...]</p> 

<p>in your blog post and the photo album will be inserted into the post at that point using the parameters you specified.</p>

<p>Supported Parameters:<p>
<dir>
<p>controls={true,false}</p>
<p>autoplay={true, false}</p>
<p>thumbnail_location={TOP, BOTTOM}</p>
<p>thumbnail_size={NUMBER}</p>
<p>thumbnail_padding={NUMBER}</p>
<p>captions={0,1}</p>
<p>photo_count={0,1}</p>
<p>caption_height={NUMBER}</p> 
<p>photo_padding={NUMBER}</p>
<p>title_prefix={ANY STRING}</p>
<p>left_margin={NUMBER}</p>
<p>right_margin={NUMBER}</p>
<p>top_margin={NUMBER}</p>
<p>bottom_margin={NUMBER}</p>
</dir>

</li>


</ol>