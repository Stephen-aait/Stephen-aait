<?php 
//Written by Ronald Huereca
//Last modified on 10/16/2007
?>
<?php if (!function_exists('add_action'))
{
	require_once("../../../../wp-config.php");
}
if (isset($viv_imgResizer)) {
	$options = $viv_imgResizer->getAdminOptions();
	$maxWidth = $options['max_width'];
	$cropRatio = round($options['crop_ratio_h']/$options['crop_ratio_w'],3);
	$cropRatioOn = $options['crop_ratio_on'];
	$showLink = $options['show_link'];
}
?>
jQuery(document).ready(function(){
   VivTigerUpload.init();
});
var VivTigerUpload = function() {
	var $j = jQuery;
	var PluginUrl = "<?php bloginfo('wpurl') ?>/wp-content/plugins/vivtiger-image-resizer";
	var attachmentId = 0;
	var imgHtml = '';
	function UploadTakeover() {
		
		var imgSrc = $j('#upload-file-view img').attr('src');
		var imgWidth = $j('#upload-file-view img').attr('width');
		var imgHeight = $j('#upload-file-view img').attr('height');
		//Get the image's size and calculate the overlay
		
		GetImageSize(imgWidth, imgHeight, imgSrc);
		
	}
	function GetImageSize(width, height, path) {
		if (width == undefined) { return; }
		var regex = /(\d+)/;
		var imgId = $j('#file-title a').attr('onclick');
		attachmentId = GetNumber(imgId);
		//$j('#viv-image-loading').remove();
		$j('#upload-file-view a').after("<br/><span id='viv-image-loading'><img src='" + PluginUrl + "/spinner.gif' alt='Loading'/><?php _e('Vivtiger Image Resizer loading...', 'VivtigerImageResizer') ?></span>");
		$j.ajax({
			type: "post",
			url: PluginUrl + '/php/VivTiger.php',
			timeout: 30000,
			global: false,
			data: {
				GetImageSize: attachmentId},
			success: function(msg) { GetImageSizeComplete(msg, width, height, path); }
		});
	}
	//Calculates the overlay that goes over the image
	function CalculateOverlay(thumbWidth, thumbHeight, imgWidth, imgHeight, path, maxWidth, cropRatioOn, cropRatio) {
		//Read in the resizer options
		if (imgWidth > maxWidth) {
			//create overlay
			thumbWidthRatio = Math.round((thumbWidth / imgWidth)*100)/100;
			thumbHeightRatio = Math.round((thumbHeight / imgHeight)*100)/100;
			newHeight = Math.round(maxWidth * (imgHeight / imgWidth));
			overlayWidth = Math.round(maxWidth * thumbWidthRatio)-1;
			if (cropRatioOn == "true") {
				overlayHeight = Math.round((overlayWidth*cropRatio)*100)/100;
				if (overlayHeight > thumbHeight) {
					overlayHeight = parseInt(thumbHeight)-1;
					overlayWidth = Math.round(overlayHeight * (1/cropRatio))-1;
				}
			} else {
				overlayHeight = Math.round(newHeight * thumbHeightRatio);
			}
			var style = "position: absolute; z-index: 2; border: 1px solid #F00; width: " + overlayWidth + "px; height: " + overlayHeight + "px; top: 0; left: 0;"
			var dragStyle = "width: " + overlayWidth + "px; height: " + overlayHeight + "px;";
			$j("#viv-image-overlay").attr("style", style);
			$j("#viv-image-drag").attr("style", "width: 100%; height: 100%;");
			var left = thumbWidth - overlayWidth-2;
			var top = thumbHeight - overlayHeight-2;
			$j('#viv-image-overlay').jqDrag('.jqDrag', left, top, thumbWidth, thumbHeight).jqResize('.jqResize', left, top, thumbWidth, thumbHeight, cropRatio, cropRatioOn)
			//Set up crop link
			$j("#viv-image-crop a").unbind()
			$j("#viv-image-crop a").bind("click", function() { Crop(thumbWidthRatio, thumbHeightRatio, maxWidth); return false;} ).show();
		}
	}
	function Crop(widthRatio, heightRatio, maxWidth) {
		var left = $j("#viv-image-overlay").css("left");
		var top = $j("#viv-image-overlay").css("top");
		var width = $j("#viv-image-overlay").css("width");
		var height = $j("#viv-image-overlay").css("height");
		left = Math.round(GetNumber(left)/widthRatio);
		top = Math.round(GetNumber(top)/heightRatio);
		width = Math.round(((GetNumber(width)+1)/widthRatio)*100)/100;
		height = Math.round(((GetNumber(height)+1)/heightRatio)*100)/100;
		
		$j("#viv-image-crop").hide();
		$j("#viv-image-loading").show();
		$j.ajax({
			type: "post",
			url: PluginUrl + '/php/VivTiger.php',
			timeout: 30000,
			global: false,
			data: {
				CropId: attachmentId,
				CropX: left,
				CropY: top,
				CropWidth: width,
				CropHeight: height,
				MaxWidth: maxWidth},
			success: function(msg) { CropImageComplete(msg); }
		});
	}
	//Takes in a CSS value (0pt, 0px)
	function GetNumber(number) {
	number = number + " "; //IE fix
		number = number.match(/(\d+)/)[1];
		return Number(number);
	}
	function IsNumber(number) {
		var regex=/(\d+)/;
		return regex.test(number);
	}
	function GetImageSizeComplete(msg, width, height, path) {
		if (msg.match(/(\d+),(\d+)/)) {
			var sizes = msg.split(',');
			var imgWidth = sizes[0];
			var imgHeight = sizes[1];
			imgHtml = "<div id='viv-image' style='position: relative; z-index: 1'><img id='viv-image-img' src='" + path + "' width='" + width + "' height='" + height + "' /><br/><span id='viv-image-crop'><a href='#'><?php _e('Crop', 'VivtigerImageResizer') ?></a></span><span id='viv-image-loading' style='display: none;'><img src='" + PluginUrl + "/spinner.gif' alt='Loading'/><?php _e('Cropping...', 'VivtigerImageResizer') ?></span><div id='viv-image-overlay' style='position: absolute; z-index: 2; left: 0; top: 0; display: none;'><div id='viv-image-drag' class='jqHandle jqDrag' style='position: absolute; z-index: 2; left: 0; top: 0;'><div id='viv-image-resize' class='jqHandle jqResize' style='width: 5px; height: 5px; background-color: #000; position: absolute; bottom: 0; right: 0;cursor: se-resize; z-index:4'></div></div></div><div><br/><input id='vivtiger-crop-option' <?php if ($cropRatioOn == "true") { echo " checked='checked' "; } ?>type='checkbox' name='vivtiger-crop-option' /><label for='vivtiger-crop-option'>Crop Ratio on</label><br/><input id='vivtiger-max-width' value='<?php echo $maxWidth ?>' size='3' /><label for='vivtiger-max-width'>Max Width</label><br/><input id='vivtiger-crop-width'  value='<?php echo $options['crop_ratio_w'] ?>'  size='3' /><label for='vivtiger-crop-width'>Crop Width Ratio</label><br/><input id='vivtiger-crop-height' value='<?php echo $options['crop_ratio_h'] ?>' size='3' /><label for='vivtiger-crop-height'>Crop Height Ratio</label></div>";
			$j('#upload-file-view').html(imgHtml);
			CalculateOverlay(width, height, imgWidth, imgHeight, path, <?php echo $maxWidth; ?>,String(<?php echo $cropRatioOn; ?>),<?php echo $cropRatio; ?> );
			//Setup Advanced Options Events
			$j('#vivtiger-crop-option').bind("click", function() { PreCalculateOverlay(width, height, imgWidth, imgHeight, path, $j("#vivtiger-max-width").val(), $j(this).attr("checked"),   $j("#vivtiger-crop-width").val(), $j("#vivtiger-crop-height").val()); } );
			$j("#vivtiger-crop-width").bind("change", function() { if (IsNumber($j(this).val()) && $j("#vivtiger-crop-option").attr("checked") == true) {  PreCalculateOverlay(width, height, imgWidth, imgHeight, path, $j("#vivtiger-max-width").val(), $j("#vivtiger-crop-option").attr("checked"),   $j("#vivtiger-crop-width").val(), $j("#vivtiger-crop-height").val());  } });
			$j("#vivtiger-crop-height").bind("change", function() { if (IsNumber($j(this).val()) && $j("#vivtiger-crop-option").attr("checked") == true) {  PreCalculateOverlay(width, height, imgWidth, imgHeight, path, $j("#vivtiger-max-width").val(), $j("#vivtiger-crop-option").attr("checked"),   $j("#vivtiger-crop-width").val(), $j("#vivtiger-crop-height").val());  } });
			$j("#vivtiger-max-width").bind("change", function() { if (IsNumber($j(this).val())) {  PreCalculateOverlay(width, height, imgWidth, imgHeight, path, $j("#vivtiger-max-width").val(), $j("#vivtiger-crop-option").attr("checked"),   $j("#vivtiger-crop-width").val(), $j("#vivtiger-crop-height").val());  } });
			//todo - add events that bind onblur events to vivtiger-crop-height, vivtiger-crop-width, vivtiger-max-width;
			//todo - add events for onclick for vivtiger-crop-option
			//All of the above todos will re-calculate the overlay if acted upon - Suggest you re-write the CalculateOverlay function so that this overlay can be re-processed.
		} else if (msg != 0) {
			imgHtml = "<div id='viv-image' style='position: relative; z-index: 1'><img id='viv-image-img' src='" + path + "' width='" + width + "' height='" + height + "' /><br/><span id='viv-image-crop'></span></div>";
			$j('#upload-file-view').html(imgHtml);
			InsertImageLink(msg + ' alt="' + $j("#file-title h2").html() + '" /></a>');
		} else {
			$j("#viv-image-loading").remove();
		}
	}
	function PreCalculateOverlay(thumbWidth, thumbHeight, imgWidth, imgHeight, path, maxWidth, cropRatioOn, cropRatioW, cropRatioH) {
		cropRatio = Math.round((cropRatioH/cropRatioW)*1000)/1000;
		if (cropRatioOn == true) {
			cropRatioOn = "true";
		} else { cropRatioOn ="false"; }
		CalculateOverlay(thumbWidth, thumbHeight, imgWidth, imgHeight, path, Number(maxWidth), cropRatioOn, Number(cropRatio));
	}
	function CropImageComplete(msg) {
		$j("#viv-image-loading").hide();
		$j("#viv-image-crop").html('<?php _e('Image successfully cropped', 'VivtigerImageResizer') ?><br/>').show();
		if (msg != 0) {
			imgUrl = msg + ' alt="' + $j("#file-title h2").html() + '" /></a>';
			InsertImageLink(imgUrl);
		}	
	}
	function InsertImageLink(imgUrl) {
		$j("#viv-image-crop").html($j("#viv-image-crop").html() + <?php if ($showLink == "true") { ?>'<a href="#" id="vivtiger-insert-link">Insert Image</a><?php } else { echo "'"; } ?>').show();
		<?php if ($showLink == "true") { ?>
		$j("#vivtiger-insert-link").bind("click", function() { 
		/*Code Borrowed from WordPress - I promise to give it back someday */
		var win = window.opener ? window.opener : window.dialogArguments;
		if ( !win )
			win = top;
		tinyMCE = win.tinyMCE;
		if ( typeof tinyMCE != 'undefined' && tinyMCE.getInstanceById('content') ) {
			tinyMCE.selectedInstance.getWin().focus();
			tinyMCE.execCommand('mceInsertContent', false, imgUrl);
		} else
			win.edInsertContent(win.edCanvas, imgUrl);
		});
		<?php } ?>
	}
	return {
			init : function() { //AKA the constructor - Plugin authors can tap into the plugin by calling AjaxEditComments.init()
				UploadTakeover();
				$j('a.file-link').bind("click", function() { UploadTakeover(); });
			}
	};
	
}();