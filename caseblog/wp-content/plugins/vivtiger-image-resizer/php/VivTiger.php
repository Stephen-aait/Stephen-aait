<?php
//Written by Ronald Huereca
//Last modified on 10/10/2007 
if (!function_exists('add_action'))
{
	require_once("../../../../wp-config.php");
}
if (!isset($viv_imgResizer)) { die(''); }
//Gets the image size
//Return 0 if image sizing isn't necessary
if (isset($_POST['GetImageSize'])) {
	//Strip out everything but the ID
	if (wp_attachment_is_image($_POST['GetImageSize'])) {
		$filePath = get_attached_file( $_POST['GetImageSize'] );
		//See if the large image already exists 
		$largePath = $viv_imgResizer->getLargePath($filePath);
		if (file_exists($largePath)) {
			$vivOptions = $viv_imgResizer->getAdminOptions();
			if ($vivOptions['show_link'] === 'true') {
				$size =  $viv_imgResizer->getLargeHtml(wp_get_attachment_url($_POST['GetImageSize']));
			}
		} elseif (file_exists($filePath)) {
			list($width, $height, $type, $attr) = getimagesize($filePath);
			if ($width > $viv_imgResizer->getMaxWidth()) {
				$size = $width . "," . $height;
			} else { 
				$size = 0;
			}
		} else {
			$size = 0;
		}
		echo $size;
	} else { 
			echo 0;
	}
}
if (isset($_POST['CropId'])) {
	//Strip out everything but the ID
	echo $viv_imgResizer->attachmentResize($_POST['CropId'], $_POST['CropX'],$_POST['CropY'], $_POST['CropWidth'], $_POST['CropHeight'], $_POST['MaxWidth']);
}
?>
