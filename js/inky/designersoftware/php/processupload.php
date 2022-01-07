<?php
//echo "anadi kumar";
//include __DIR__.'./../../../../app/Mage.php';

//umask(0);
//Mage::app();
//session_start();
//$dir_root = Mage::getBaseDir();
$imageMagickPath ='convert';//Mage::Helper('designertool')->getImageMagickPath();
$userUpdOriginalImage = "userImages/original/";
$userUpdLargeImage = "userImages/large/";
$userUpdThumbImage = "userImages/thumb/";
$fileName = $_FILES['ImageFile']['name'];
$file_tmp = $_FILES['ImageFile']['tmp_name'];
$file_type = $_FILES['ImageFile']['type'];
$file_size = $_FILES['ImageFile']['size']; 

$s = filesize($file_tmp);
$size = ($s / 1024);
$size1 = round($size / 1024);

$text = '';
if ($size1 <= 20) {
    $explFile = explode('.', $fileName);
    $countExpFile = count($explFile);
    $countVal = $countExpFile - 1;
    $fileExt = $explFile[$countVal];
    $file_type = "image/" . $explFile[$countVal];
    $randomValue = 'UP' . rand();
    $fileName = $randomValue . "." . $fileExt;
    $dest = $userUpdOriginalImage . $fileName;
    //$file_type = $imgTypeArr['mime'];
    if ($file_type == "image/pjpeg" || $file_type == "image/jpeg" || $file_type == "image/jpg" || $file_type == "image/x-png" || $file_type == "image/png" || $file_type == "image/PJPEG" || $file_type == "image/JPEG" || $file_type == "image/JPG" || $file_type == "image/X-PNG" || $file_type == "image/PNG" || $file_type == "image/gif" || $file_type == "image/GIF") {
        move_uploaded_file($file_tmp, $dest);
        list($width, $height) = getimagesize($dest);
        $imgTypeArr = getimagesize($dest);
		$imgW=$width;
		$imgH=$height;
        if ($width >= 50 || $height >= 50) 
        {
        	if(strtolower($fileExt)=="gif" || strtolower($fileExt)=="GIF")
	        {
	             $fileName = $randomValue . "." . 'png';
	            
	        }


			
            $thumbW = 51;
            $thumbH = 53;
            $thumbsize = getNewImageRatio($thumbW, $thumbH, $dest);
            $usrImgThumb = $userUpdThumbImage . $fileName;
		//	echo $usrImgThumb;
			
            exec($imageMagickPath . " $dest -thumbnail $thumbsize $usrImgThumb");
            $largeW = 415;
            $largeH = 415;
            $largetImageSize = getNewImageRatio($largeW, $largeH, $dest);
            $usrImgLarge = $userUpdLargeImage . $fileName;
			//echo $usrImgLarge;
            exec($imageMagickPath . " $dest -thumbnail $largetImageSize $usrImgLarge");
            $status = 1;
			//exit;
        } else {
            @unlink($dest);
            $status = 0;
            $text = 'The uploaded file is very small. So it will not appear properly on t-shirt. Please upload images in size more than 50x50 px.';
        }
    } else if ($file_type == "image/pdf" || $file_type == "image/PDF") {
        move_uploaded_file($file_tmp, $dest);
        $fileName = rand() . ".png";
        $pdfOgininalImg = "userImages/original/" . $fileName;
        exec($imageMagickPath . "  -density 96 -depth 8 -quality 85  $dest PNG32:$pdfOgininalImg");
        list($width, $height) = getimagesize($pdfOgininalImg);
        if ($width >= 50 || $height >= 50) {
            $thumbW = 85;
            $thumbH = 80;
            $thumbsize = getImageRatio($thumbW, $thumbH, $pdfOgininalImg);
            $usrImgThumb = $userUpdThumbImage . $fileName;
            exec($imageMagickPath . " $pdfOgininalImg -thumbnail $thumbsize $usrImgThumb");
            $largeW = 415;
            $largeH = 415;
            $largetImageSize = getImageRatio($largeW, $largeH, $pdfOgininalImg);
            $usrImgLarge = $userUpdLargeImage . $fileName;
            exec($imageMagickPath . " $pdfOgininalImg -thumbnail $largetImageSize $usrImgLarge");
            $status = 1;
        } else {
            @unlink($dest);
            $status = 0;
            $text = 'The uploaded file is very small. So it will not appear properly on t-shirt. Please upload images in size more than 50x50 px.';
        }
    } else if ($file_type == "image/eps" || $file_type == "image/EPS") {
        move_uploaded_file($file_tmp, $dest);
        $fileName = rand() . ".png";
        $epsoriginalImg = "userImages/original/" . $fileName;
        exec($imageMagickPath . "  -density 300 -channel RGBA -colorspace RGB -background none -fill none -dither None  $dest $epsoriginalImg");

        list($width, $height) = getimagesize($epsoriginalImg);
//echo $width.$height;exit;
        if ($width >= 50 || $height >= 50) {
            $thumbW = 85;
            $thumbH = 80;
            $thumbsize = getImageRatio($thumbW, $thumbH, $epsoriginalImg);
            $usrImgThumb = $userUpdThumbImage . $fileName;
            exec($imageMagickPath . " $epsoriginalImg -thumbnail $thumbsize $usrImgThumb");
            $largeW = 415;
            $largeH = 415;
            $largetImageSize = getImageRatio($largeW, $largeH, $epsoriginalImg);
            $usrImgLarge = $userUpdLargeImage . $fileName;
            exec($imageMagickPath . " $epsoriginalImg -thumbnail $largetImageSize $usrImgLarge");
            $status = 1;
        } else {
            @unlink($dest);
            $status = 0;
            $text = 'The uploaded file is very small. So it will not appear properly on t-shirt. Please upload images in size more than 50x50 px.';
        }
    } else if ($file_type == "image/svg" || $file_type == "image/SVG") {

        move_uploaded_file($file_tmp, $dest);
        $fileName = rand() . ".png";
        $svgOriginalImg = "userImages/original/" . $fileName;
        exec($imageMagickPath . " -background none  $dest PNG32: $svgOriginalImg");
        list($width, $height) = getimagesize($svgOriginalImg);
        if ($width >= 50 || $height >= 50) {
            $thumbW = 85;
            $thumbH = 80;
            $thumbsize = getImageRatio($thumbW, $thumbH, $svgOriginalImg);
            $usrImgThumb = $userUpdThumbImage . $fileName;
            exec($imageMagickPath . " $svgOriginalImg -thumbnail $thumbsize $usrImgThumb");
            $largeW = 415;
            $largeH = 415;
            $largetImageSize = getImageRatio($largeW, $largeH, $svgOriginalImg);
            $usrImgLarge = $userUpdLargeImage . $fileName;
            exec($imageMagickPath . " $svgOriginalImg -thumbnail $largetImageSize $usrImgLarge");
            $status = 1;
        } else {
            @unlink($dest);
            $status = 0;
            $text = 'The uploaded file is very small. So it will not appear properly on t-shirt. Please upload images in size more than 50x50 px.';
        }
    } else {
        @unlink($dest);
        $status = 0;
        $fileExt = explode('/', $file_type);
        $fileEXT = $fileExt[1];
        $text = 'You have uploaded wrong file format [ ' . $fileEXT . ' ]. This is not supported.';
    }
} else {
    $status = 0;
    $text = 'The maximum file size you can upload is 20KB. Please try a smaller file size.';
}
$thumb = $userUpdThumbImage . $fileName;
//respond with our images
if ($text == ''){
$returnArray = array();
	//list($width,$height) =  getimagesize(str_replace('/thumb/', '/large/', Mage::getBaseUrl('web').'designertool/php/'.$usrImgThumb));
	if(strtolower($fileExt)=="gif" || strtolower($fileExt)=="GIF")
    {
         $usrImgThumb=str_replace('.png', '-0.png', $usrImgThumb);
        
    }
	//echo $width;
	//exit;
	$returnArray['thumb'] = $usrImgThumb;
	$returnArray['maxH'] = $imgW;
	$returnArray['maxW'] = $imgH;
	$returnArray['errorStr'] = 'No';
    echo json_encode($returnArray);
}
else
	{
		$returnArray = array();
	
	$returnArray['text'] = $text;
	$returnArray['errorStr'] = 'Error';
	
    
    echo json_encode($returnArray);
	}
    //echo $text . 'Error';

// ..................... get expect ratio ...........................///
function getImageRatio($ratioW, $ratioH, $imgPath) {
    $imageRatio = '';
    list($width, $height) = @(getimagesize($imgPath));
    if ($width > $ratioW && $height > $ratioH) {
        $imageRatio = $ratioW . 'x' . $ratioH;
    } else if ($width < $ratioW && $height > $ratioH) {
        $imageRatio = 'x' . $ratioH;
    } else if ($width > $ratioW && $height < $ratioH) {
        $imageRatio = $ratioW . 'x';
    } else {
        $imageRatio = $width . 'x' . $height;
    }
    return $imageRatio;
}

    function getNewImageRatio($ratioW, $ratioH, $imgPath) {
        $imageRatio = '';

        list($width, $height) = @(getimagesize($imgPath));
		
		if($width >= $height && $ratioW >= $ratioH):
		
			$ratio = $width / $height;
			$imageRatio['width'] = $ratioW;
            $imageRatio['height'] = $ratioW / $ratio;
            
        elseif($width > $height && $ratioH > $ratioW):
			
			$ratio = $height / $width;
			$imageRatio['width'] = $ratioH;
            $imageRatio['height'] = $ratioH * $ratio;
            
        elseif($height >= $width && $ratioH >= $ratioW):
        
			$ratio = $height / $width;
			$imageRatio['width'] = $ratioW / $ratio;
            $imageRatio['height'] = $ratioH;
            
        elseif($height > $width && $ratioW > $ratioH):
			
			$ratio = $height / $width;
			$imageRatio['width'] = $ratioW;
            $imageRatio['height'] = $ratioW * $ratio;
			
        else:
        
			$imageRatio['width'] = $ratioW;
            $imageRatio['height'] = $ratioH	;
            
		endif;       

        return $imageRatio['width']. 'x' .$imageRatio['height'];
    }

// ..................... get expect ratio ...........................///   
?>
