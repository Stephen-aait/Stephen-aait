<?php
/* 
Plugin Name: WP Vivtiger Image Resizer
Plugin URI: http://www.inspirationbit.com/wp-vivtiger-image-resizer/
Version: v1.21
Author: Ronald Huereca
Author URI:  http://www.raproject.com
Description: Allows users to crop and resize an image easily using the WordPress attachment system.
 
Copyright 2007  Ronald Huereca  (email : ron alfy [a t ] g m ail DOT com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/
	class WPvivtigerImageResizer {
				var $rootPath = '';
				var $optionsName = "vivtigerImageResizerOptions";
				var $adminOptions = false;
				//constructor
				function WPvivtigerImageResizer() {
					if(!isset($_SERVER['DOCUMENT_ROOT'])){ if(isset($_SERVER['SCRIPT_FILENAME'])){
					$_SERVER['DOCUMENT_ROOT'] = str_replace( '\\', '/', substr($_SERVER['SCRIPT_FILENAME'], 0, 0-strlen($_SERVER['PHP_SELF'])));
					}; };
					if(!isset($_SERVER['DOCUMENT_ROOT'])){ if(isset($_SERVER['PATH_TRANSLATED'])){
					$_SERVER['DOCUMENT_ROOT'] = str_replace( '\\', '/', substr(str_replace('\\\\', '\\', $_SERVER['PATH_TRANSLATED']), 0, 0-strlen($_SERVER['PHP_SELF'])));
					}; };
					if (isset($_SERVER["DOCUMENT_ROOT"])) {
						$this->rootPath = $_SERVER["DOCUMENT_ROOT"];
					} 
				}
				function init() {
					$this->getAdminOptions();
				}
				//Sets default options when plugin is first activated
				function getAdminOptions() {
					if ($this->adminOptions == false) {
						$optionsName = $this->optionsName;
						$adminOptions = array('max_width' => '370',
							'max_height' => '300', 
							'calculate_height' => 'false', 
							'show_link' => 'true', 
							'pop_up' => 'false',
							'crop_ratio_w' => '4',
							'crop_ratio_h' => '1',
							'crop_ratio_on' => 'false'
						);
						$options = get_option($optionsName);
						if (!empty($options)) {
							foreach ($options as $key => $option)
								$adminOptions[$key] = $option;
						}				
						update_option($optionsName, $adminOptions);
						$this->adminOptions = $adminOptions;
					}
					return $this->adminOptions;
				}					
				//Prints out the admin page
				function printAdminPage() {
					$vivtigerOptions = $this->getAdminOptions();
					
					if (isset($_POST['update_vivtigerImageResizerSettings'])) { 
						$error = false;
						$updated = false;
					  //Validate the comment time entered
						$vivtigerErrorMessage = '';
						$settingsClass = 'error';
						if (!preg_match('/^\d+$/i', $_POST['vivtigerMaxWidth']) || !preg_match('/^\d+$/i', $_POST['vivtigerMaxHeight'])) {
							$vivtigerErrorMessage .= "Width and Height must be a numerical value. ";
							$error = true;
						}	elseif($_POST['vivtigerMaxWidth'] < 1 || $_POST['vivtigerMaxHeight'] < 1 ) {
							$vivtigerErrorMessage .= "Width and Height values must be greater than one";
							$error = true;
						} else {
							$vivtigerOptions['max_width'] = $_POST['vivtigerMaxWidth'];
							$vivtigerOptions['max_height'] = $_POST['vivtigerMaxHeight'];
							$updated = true;
						}
						if (!preg_match('/^[0-9]([0-9]+)?[.]?([0-9]+)?$/i', $_POST['vivtigerCropWidth']) || !preg_match('/^[0-9]([0-9]+)?[.]?([0-9]+)?$/i', $_POST['vivtigerCropHeight'])) {
							$vivtigerErrorMessage .= " Crop Ratios must be numerical values (i.e., 1.5)";
							$error = true;
						} else {
							$vivtigerOptions['crop_ratio_w'] = $_POST['vivtigerCropWidth'];
							$vivtigerOptions['crop_ratio_h'] = $_POST['vivtigerCropHeight'];
							$updated = true;
						}
						if (!empty($vivtigerErrorMessage)) {
						?>
<div class="<?php _e($settingsClass, "VivtigerImageResizer");?>"><p><strong><?php _e($vivtigerErrorMessage, "VivtigerImageResizer");?></p></strong></div>
						<?php
						}
						if (isset($_POST['vivtigerAllowHeightCalculation'])) {
							$vivtigerOptions['calculate_height'] = $_POST['vivtigerAllowHeightCalculation'];
							$update = true;
						}	
						if (isset($_POST['vivtigerAllowPopUp'])) {
							$vivtigerOptions['pop_up'] = $_POST['vivtigerAllowPopUp'];
							$update = true;
						}	
						if (isset($_POST['vivtigerAllowLink'])) {
							$vivtigerOptions['show_link'] = $_POST['vivtigerAllowLink'];
							$update = true;
						}	
						if (isset($_POST['vivtigerAllowCropRatio'])) {
							$vivtigerOptions['crop_ratio_on'] = $_POST['vivtigerAllowCropRatio'];
							$update = true;
						}
						if ($update && !$error) {
							update_option($this->optionsName, $vivtigerOptions);
						?>
<div class="updated"><p><strong>Settings successfully updated.</strong></p></div>
					<?php
						}
					} ?>
<div class=wrap>
<form method="post" action="">
<h2><?php _e('Vivtiger Image Resizer', 'VivtigerImageResizer') ?></h2>
<h3><?php _e('Set the Maximum Width and Height for Images', 'VivtigerImageResizer') ?></h3>
<p><?php _e('The images are resized when the attachment is uploaded.', 'VivtigerImageResizer') ?></p>
<p><label for="comment_time"><?php _e('Set the maximum width of the image (pixels): ', 'VivtigerImageResizer') ?></label><input type="text" name="vivtigerMaxWidth" value="<?php echo $vivtigerOptions['max_width'] ?>" /></p>
<p><label for="comment_time"><?php _e('Set the maximum height of the image (pixels): ', 'VivtigerImageResizer') ?></label><input type="text" name="vivtigerMaxHeight" value="<?php echo $vivtigerOptions['max_height'] ?>" /></p>
<h3><?php _e('Set the Crop Ratio for Images', 'VivtigerImageResizer') ?></h3>
<p><?php _e('The crop ratio forces the crop box into a specified ratio', 'VivtigerImageResizer') ?></p>
<p><label for="comment_time"><?php _e('Set the crop ratio for the width: ', 'VivtigerImageResizer') ?></label><input type="text" name="vivtigerCropWidth" value="<?php echo $vivtigerOptions['crop_ratio_w'] ?>" /></p>
<p><label for="comment_time"><?php _e('Set the crop ratio for the height: ', 'VivtigerImageResizer') ?></label><input type="text" name="vivtigerCropHeight" value="<?php echo $vivtigerOptions['crop_ratio_h'] ?>" /></p>
<h3><?php _e('Turn on the crop ratio?', 'VivtigerImageResizer') ?></h3>
<p><?php _e('Selecting "Yes" will limit the crop box to the crop ratio', 'VivtigerImageResizer') ?></p>
<p><label for="vivtigerAllowCropRatio_yes"><input type="radio" id="vivtigerAllowCropRatio_yes" name="vivtigerAllowCropRatio" value="true" <?php if ($vivtigerOptions['crop_ratio_on'] == "true") { _e('checked="checked"', "VivtigerImageResizer"); }?> /> <?php _e('Yes', 'VivtigerImageResizer') ?></label>&nbsp;&nbsp;&nbsp;&nbsp;<label for="vivtigerAllowCropRatio_no"><input type="radio" id="vivtigerAllowCropRatio_no" name="vivtigerAllowCropRatio" value="false" <?php if ($vivtigerOptions['crop_ratio_on'] == "false") { _e('checked="checked"', "VivtigerImageResizer"); }?>/> <?php _e('No', 'VivtigerImageResizer') ?></label></p>
<h3><?php _e('Turn on the height calculation?', 'VivtigerImageResizer') ?></h3>
<p><?php _e('Selecting "No" will only adjust the image according to the maximum width you specify.', 'VivtigerImageResizer') ?></p>
<p><label for="vivtigerAllowHeightCalculation_yes"><input type="radio" id="vivtigerAllowHeightCalculation_yes" name="vivtigerAllowHeightCalculation" value="true" <?php if ($vivtigerOptions['calculate_height'] == "true") { _e('checked="checked"', "VivtigerImageResizer"); }?> /> <?php _e('Yes', 'VivtigerImageResizer') ?></label>&nbsp;&nbsp;&nbsp;&nbsp;<label for="vivtigerAllowHeightCalculation_no"><input type="radio" id="vivtigerAllowHeightCalculation_no" name="vivtigerAllowHeightCalculation" value="false" <?php if ($vivtigerOptions['calculate_height'] == "false") { _e('checked="checked"', "VivtigerImageResizer"); }?>/> <?php _e('No', 'VivtigerImageResizer') ?></label></p>
<h3><?php _e('Link to Larger Image?', 'VivtigerImageResizer') ?></h3>
<p><?php _e('Selecting "Yes" will automatically add an option to link to the larger image (if available).  A larger image will only be present if the cropped area is greater than the maximum width', 'VivtigerImageResizer') ?></p>
<p><label for="vivtigerAllowLink_yes"><input type="radio" id="vivtigerAllowLink_yes" name="vivtigerAllowLink" value="true" <?php if ($vivtigerOptions['show_link'] == "true") { _e('checked="checked"', "VivtigerImageResizer"); }?> /> <?php _e('Yes', 'VivtigerImageResizer') ?></label>&nbsp;&nbsp;&nbsp;&nbsp;<label for="vivtigerAllowLink_no"><input type="radio" id="vivtigerAllowLink_no" name="vivtigerAllowLink" value="false" <?php if ($vivtigerOptions['show_link'] == "false") { _e('checked="checked"', "VivtigerImageResizer"); }?>/> <?php _e('No', 'VivtigerImageResizer') ?></label></p>
<h3><?php _e('Linked Image Shows in New Window?', 'VivtigerImageResizer') ?></h3>
<p><?php _e('Selecting "Yes" will show the larger image in a new window (if links are turned on).', 'VivtigerImageResizer') ?></p>
<p><label for="vivtigerAllowPopUp_yes"><input type="radio" id="vivtigerAllowPopUp_yes" name="vivtigerAllowPopUp" value="true" <?php if ($vivtigerOptions['pop_up'] == "true") { _e('checked="checked"', "VivtigerImageResizer"); }?> /> <?php _e('Yes', 'VivtigerImageResizer') ?></label>&nbsp;&nbsp;&nbsp;&nbsp;<label for="vivtigerAllowPopUp_no"><input type="radio" id="vivtigerAllowPopUp_no" name="vivtigerAllowPopUp" value="false" <?php if ($vivtigerOptions['pop_up'] == "false") { _e('checked="checked"', "VivtigerImageResizer"); }?>/> <?php _e('No', 'VivtigerImageResizer') ?></label></p>
<div class="submit">
<input type="submit" name="update_vivtigerImageResizerSettings" value="<?php _e('Update Settings', 'VivtigerImageResizer') ?>" /></div>
</form>
 </div>
					<?php
				}//End function printAdminPage()
				//returns a max width
				function getMaxWidth() {
					$vivtigerOptions = $this->getAdminOptions();
					return $vivtigerOptions['max_width'];
				}
				//Adds a resized attachment
				function attachmentResize($attachmentID,$srcX, $srcY, $srcW, $srcH, $maxWidth) {
					if (empty($this->rootPath) || !function_exists("imagecreatetruecolor")) { return $attachmentID; }
					$vivtigerOptions = $this->getAdminOptions();
					$resize = false;
					$post = get_post($attachmentID);
					$width = $maxWidth;
					$height = $vivtigerOptions['max_height'];
					$image = '';
					if (wp_attachment_is_image($post->ID)) {
						$file = wp_get_attachment_url($post->ID);
						$fileRelative = $this->rootPath . wp_make_link_relative($file);
						
						$imagesize = getimagesize( $file );
						if ( $imagesize[2] == 1 ) {
							$image = imagecreatefromgif( $file );
						}
						elseif ( $imagesize[2] == 2 ) {
							$image = imagecreatefromjpeg( $file );
						}
						elseif ( $imagesize[2] == 3 ) {
							$image = imagecreatefrompng( $file );
						}
						
						$widthRatio = $srcW/$srcH; //Width divided by height
						$heightRatio = $srcH/$srcW; //Height divided by width
						
						//If width is greater than the default size
						if ($srcW > $maxWidth) {
							$height =  round($width * $heightRatio,2);
							$resize = true;
						}
						//Calculate the height
						if ($vivtigerOptions['calculate_height'] == "true") {
							//If height is greater than the default size
							if ($srcH > $vivtigerOptions['max_height']) {
								$width =  round($height * $widthRatio,2);
								$resize = true;
							}
						} 
						//return $height . "," . $width . "," . $srcW . "," . $srcH;		
						//Crop the image based on what the user has selected
						$imgPath = $this->stripExtension($file);
						$extension = $this->getExtension($file);
						
						//* Check to make sure that cropped image doesn't go outside the bounds of the original
						if ($srcX < 0) { $srcX = 0; } 
						if ($srcY < 0) { $srcY = 0; }
						if ($srcX > $imagesize[0]) { $srcX = $imagesize[0]; }
						if (srcY > $imagesize[1]) { $srcY = $imagesize[1]; }
						if (($srcX + $srcW) > $imagesize[0]) { $srcW = $imagesize[0] - $srcX; }
						if (($srcY + $srcH) > $imagesize[1]) { $srcH = $imagesize[1] - $srcY; }
						// * End check 
						$croppedImage = imagecreatetruecolor( $srcW, $srcH);
						@ imagecopyresampled($croppedImage, $image, 0,0, $srcX, $srcY, $srcW, $srcH, $srcW, $srcH);
						
						$largePath = $imgPath . ".large" . "." . $extension;
						$htmlLargePath = get_bloginfo('wpurl') . "/" .  wp_make_link_relative($largePath);
						$largePath = $this->rootPath . wp_make_link_relative($largePath);
						if ($resize) { //only save the larger image if it's bigger than the resized image
							$this->saveImage($imagesize[2], $croppedImage, $largePath);
						}						
						
						$originalPath = $imgPath . "_original" . "." . $extension;
						$thumbPath = $imgPath . ".thumbnail" . "." . $extension;
						$thumbPath = $this->rootPath . wp_make_link_relative($thumbPath);
						$originalPath = $this->rootPath . wp_make_link_relative($originalPath);
						if ($resize) {			
							//Resize the original image
							$original = imagecreatetruecolor( $width, $height);
							@ imagecopyresampled($original, $croppedImage, 0,0, 0,0, $width, $height, $srcW, $srcH);
							$this->saveImage($imagesize[2], $original, $originalPath);
						} else { $original = $croppedImage;  }
						
						//Save the original to file
						$this->saveImage($imagesize[2], $original, $originalPath);
						
						//Delete the original
						unlink($fileRelative);
						
						//Rename the original
						if (file_exists($originalPath)) {
							rename($originalPath, $fileRelative);						
						}
						
						//Regenerate the thumbnail
						$this->saveThumbnail($imagesize[2], $originalPath = $imgPath .  "." . $extension, $original, 128, $thumbPath);
						
						//Clear up some memory
						@imagedestroy($image);
						@imagedestroy($croppedImage);
						@imagedestroy($original);
						
					}
					if (file_exists($largePath) && $vivtigerOptions['show_link'] === 'true') {  return $this->getLargeHtml($imgPath .  "." . $extension);}
					return 0;
				}
				//Takes in an image path, makes it relative, and returns the path as if it were a larger image
				function getLargePath($path) {
					$largePath = $this->stripExtension($path);
					$extension = $this->getExtension($path);
					$largePath = $largePath . ".large" . "." . $extension;
					return $largePath;
				}
				//Used for inserting html into the editor
				function getLargeHtml($path) {
					$extension = $this->getExtension($path);
					$largePath = $this->stripExtension($path);					
					return '<a href="' . $largePath . '.large' . '.' . $extension . '" class="viv-new-window"><img src="' . $largePath . '.' . $extension . '"';
				}
				//Function mostly "borrowed" from wp_create_thumbnail
				function saveThumbnail($type, $file, $image, $max_side, $thumbpath) {
					if ( function_exists( 'imageantialias' ))
						imageantialias( $image, TRUE );
					$image_attr = getimagesize( $file );
					
					// figure out the longest side
					if ( $image_attr[0] > $image_attr[1] ) {
						$image_width = $image_attr[0];
						$image_height = $image_attr[1];
						$image_new_width = $max_side;
						
						$image_ratio = $image_width / $image_new_width;
						$image_new_height = $image_height / $image_ratio;
					//width is > height
					} else {
						$image_width = $image_attr[0];
						$image_height = $image_attr[1];
						$image_new_height = $max_side;
						
						$image_ratio = $image_height / $image_new_height;
						$image_new_width = $image_width / $image_ratio;
					//height > width
					}
					
					$thumbnail = imagecreatetruecolor( $image_new_width, $image_new_height);
					@ imagecopyresampled( $thumbnail, $image, 0, 0, 0, 0, $image_new_width, $image_new_height, $image_attr[0], $image_attr[1] );
					
					// If no filters change the filename, we'll do a default transformation.
					$this->saveImage($type, $thumbnail, $thumbpath);

				}
				function javascripts() { 
					if (function_exists('wp_enqueue_script') && function_exists('wp_register_script')) {
						wp_register_script('jquery', get_bloginfo('wpurl') . '/wp-content/plugins/vivtiger-image-resizer/js/jquery.js');
						wp_enqueue_script('viv-tiger-upload', get_bloginfo('wpurl') . "/wp-content/plugins/vivtiger-image-resizer/js/vivtiger-upload.js.php", array('jquery'));
						wp_enqueue_script('viv-tiger-dragresize', get_bloginfo('wpurl') . "/wp-content/plugins/vivtiger-image-resizer/js/jqDnR.js", array('jquery'));
					}				
				}
				function postScripts() {
					$vivtigerOptions = $this->getAdminOptions();
					if (function_exists('wp_enqueue_script') && function_exists('wp_register_script') && $vivtigerOptions['pop_up'] == "true") {
						wp_register_script('jquery', get_bloginfo('wpurl') . '/wp-content/plugins/vivtiger-image-resizer/js/jquery.js');
						wp_enqueue_script('viv-tiger-new-window', get_bloginfo('wpurl') . "/wp-content/plugins/vivtiger-image-resizer/js/vivtiger-new-window.js", array('jquery'));
					}	
				}				
				//Saves an image
				//$imageType is a numerical value -  1 = GIF, 2 = JPEG, 3 = PNG
				//$image is the image to be saved
				//$imagePath is the path to save the image at
				//Returns true if saved, false if not
				function saveImage($imageType, $image, $imagePath) {
				//Save the original to file
						if ( $imageType == 1 ) {
							if (!imagegif($image, $imagePath ) ) {
								return false;
							}
						}
						elseif ( $imageType == 2 ) {
							if (!imagejpeg($image, $imagePath,95) ) {
								return false;
							}
						}
						elseif ( $imageType == 3 ) {
							if (!imagepng($image, $imagePath ) ) {
								return false;
							}
						}
						return true;				
				}
				
				//Returns an image extension - Accepts an image file url
				function getExtension($filename) {
					$pattern = '/\.(jpeg|jpg|gif|png)$/i';
					preg_match($pattern, $filename, $matches);
					return $matches[1];
				}
				function stripExtension($filename) {
					$pattern = '/\.(jpeg|jpg|gif|png)$/i';
					return preg_replace($pattern, '', $filename);
				}
				//Deletes the large attachment (if available)
				function attachmentDeleted($attachmentID) {
					if (empty($this->rootPath)) { return $attachmentID; }
					
					$post = get_post($attachmentID);
					if (wp_attachment_is_image($post->ID)) {
						//Delete the attachment
						$file = wp_get_attachment_url($post->ID);
						$fileRelative = $this->rootPath . wp_make_link_relative($file);
						$fileRelative = $this->stripExtension($fileRelative);
						$fileRelative .= ".large" . "." . $this->getExtension($file);
						if (file_exists($fileRelative)) {
							unlink($fileRelative);
						}
					}
					return $attachmentID;
				}
				//Adds the JavaScript file for a new window
								
		}//End Class WPvivtigerImageResizer
if (class_exists("WPvivtigerImageResizer")) {
$viv_imgResizer = new WPvivtigerImageResizer();
}
//Initialize the admin panel
if (!function_exists('WPvivtigerImageResizer_ap')) {
function WPvivtigerImageResizer_ap() {
	global $viv_imgResizer;
	if (!isset($viv_imgResizer)) {
		return;
	}
	if (function_exists('add_options_page')) {
		add_options_page('Vivtiger Image Resizer', 'Vivtiger Image Resizer', 9, __FILE__, array(&$viv_imgResizer, 'printAdminPage'));
	}
}	
}
//Yay, actions.	
if (isset($viv_imgResizer)) {

	add_action('admin_menu', 'WPvivtigerImageResizer_ap');
	add_action('delete_attachment', array(&$viv_imgResizer, 'attachmentDeleted'));
	add_action('activate_vivtiger-image-resizer/wp-vivtiger-image-resizer.php',  array(&$viv_imgResizer, 'init'));
	add_action('upload_files_browse', array(&$viv_imgResizer, 'javascripts'));
	add_action('admin_print_scripts', array(&$viv_imgResizer, 'javascripts'));
	add_action('wp_print_scripts', array(&$viv_imgResizer, 'postScripts') );
}

?>