<?php

include_once(__DIR__ . '/../JSON.php');
include_once("pdf.php");
include_once("zip.class.php");
include_once (__DIR__ . '/../../app/Mage.php');

umask(0);
Mage::app();
Mage::getSingleton("core/session", array("name" => "frontend"));
define('IMAGEMAGICKPATH', Mage::getModel('core/variable')->loadByCode('IMAGE_MAGICK_PATH')->getValue('plain'));

class GenPDF {

    var $db;

    function GenPDF() {
        $this->db = Mage::getSingleton('core/resource')->getConnection('core_write');
    }

    function deletePDF($id, $url) 
    {
    	//echo "delete";exit;
        $orderRecId = $id;
        $order = Mage::getModel('sales/order')->load($id);
        $_totalData = $order->getData();
        $orderId = $_totalData['increment_id'];
        @unlink(Mage::getBaseDir('media').'/files/pdf/' . $orderId . ".zip");
        $this->DELETE_RECURSIVE_DIRS(Mage::getBaseDir('media').'/files/pdf/' . $orderId);
        $div_id = "status" . $id;
        echo '<a href="javascript:void(0);" onclick="generate_pdf(' . $id . ',\'1\');">Generate PDF</a>';
    }

    function DELETE_RECURSIVE_DIRS($dirname) {
        if (is_dir($dirname)) {
            $dir_handle = opendir($dirname);
            while ($file = readdir($dir_handle)) {
                if ($file != "." && $file != "..") {
                    if (!is_dir($dirname . "/" . $file)) {
                        unlink($dirname . "/" . $file);
                    } else {
                        $this->DELETE_RECURSIVE_DIRS($dirname . "/" . $file);
                        @rmdir($dirname . "/" . $file);
                    }
                }
            }
            closedir($dir_handle);
            @rmdir($dirname);
            return true;
        } else
            return false;
    }

    function createDirectory($dirName) {
    	//echo $dirName;exit;
        if (!is_dir($dirName)) {
            @mkdir($dirName);
            @chmod($dirName, 0777);
        }
    }

    function generatePDF($tooldata) {
			//echo 'pawan Kumar';exit;	
        $imageMagickPath = "/usr/bin/convert";//IMAGEMAGICKPATH;
      //  $db = Mage::getSingleton('core/resource')->getConnection('core_write');
        $jsonClass = new JSON;
        
       // $order = Mage::getModel('sales/order')->load($id);
       // $items = $order->getAllItems();
        //$itemcount = 1;//count($items);
        //$_totalData = $order->getData();
       // $orderId = $_totalData['increment_id'];

       // $ordZipFileName = $orderId . ".zip";
        $directoryName = Mage::getBaseDir('media') . "/files/pdf/" ."138.zip";//Mage::getBaseDir('media') . "/files/pdf/" . $ordZipFileName;
        
        
        if (!is_file($directoryName)) {
            //if ($itemcount > 0) 
            //{
                $zipArr = array();
                //foreach ($items as $itemId => $item) 
                ///{
                   // $productId = $item->getProductId();
                    
                   // $catalog_product=Mage::getModel('catalog/product')->load($productId);
                    //$productSku=$catalog_product->getSku();
                    
                    //$dataArr = stripslashes(Mage::getResourceModel('catalog/product')->getAttributeRawValue($productId, 'data_array', 0));
                    //$rawProductId = stripslashes(Mage::getResourceModel('catalog/product')->getAttributeRawValue($productId, 'rawproduct_id', 0));
                  ///  $colorId = stripslashes(Mage::getResourceModel('catalog/product')->getAttributeRawValue($productId, 'rawproduct_color_id', 0));
                    //$jsonEncodedData = $jsonClass->decode($dataArr);
                    $jsonEncodedData = $tooldata['dataArr'];
					$nameNumTable=$tooldata['nameNumberSendInfoArr'];
					
					//echo $productId; 
                    //echo "<pre>"; print_r($jsonEncodedData);exit;
					        $dirName = Mage::getBaseDir('media') . "/files/pdf/" . 138;
                            $this->createDirectory($dirName); // Create First Level Directory	
                           // $dirName = Mage::getBaseDir('media') . "/files/pdf/" . $orderId . "/" . $productId;
                            //$this->createDirectory($dirName); // Create second Level Directory	
                            $designDirName = Mage::getBaseDir('media') . "/files/pdf/" . 138 . "/designimage";
                            $this->createDirectory($designDirName); // Create Second Level Directory
					    //   echo "pawan";exit;
					      // echo "<pre>";
					// print_r($jsonEncodedData);
					// exit;
					        $counterV = 1;
                    foreach ($jsonEncodedData as $datavalue) 
                    {
                       					$datavalue = (object) $datavalue;
					$printAreaW = $datavalue->viewWidth;
					$printAreaH = $datavalue->viewHeight;
                    $printW = ($datavalue->printWidth * 72);
                    $printH = ($datavalue->printHeight * 72);
					$ratioW = $printW / $printAreaW;
                    $ratioH = $printH / $printAreaH;
                    $viewPrintX = $datavalue->printX;
                   	$viewPrintY = $datavalue->printY;
				   $size		= array($printW, $printH);
                   $pdf		= new PDF("P", "pt", $size);
                   $pdf->AddPage(); 
				   $pdfFileName = $datavalue->viewId . "_.pdf";
				  // $pdfFileName = 138 . "-" . $productSku . "-" . $viewTitle . ".pdf";		
			//}
                
                        //if (count($datavalue[1]) > 0) 
                        //{

                            foreach ($datavalue->data as $value) 
                            {
                            	// echo '<pre>';
									// print_r($value);
									// exit;
								$value = (object) $value;
								//echo $value->name;exit;	
                                if ($value->name == 'Text') 
                                {
                                    $rotation = (-($value->rotation));
									$newposX = $value->x - $viewPrintX;
	                                $newposY = $value->y - $viewPrintY;
									$posX = ($newposX * $ratioW);
	                                $posY = ($newposY * $ratioH);
	                                $posW = ($value->width * $ratioW);
	                                $posH = ($value->height * $ratioH);
	                                $textNo = rand();
	                                //echo $designDirName;exit;
	                                $imageDestName = $designDirName . "/" . $textNo . "normal.png";
	                                $query = $this->setTextQuery($value, $imageDestName,'nameNumber');
									//echo $query;exit;
									exec($imageMagickPath . $query);
                                	$pdf->RotatedImage($imageDestName, $posX, $posY, $posW, $posH, $rotation);
                                }// end text if
                                if($value->name == 'Number' || $value->name=="Name" )
								{
									
									$rotation = (-($value->rotation));
									$newposX = $value->x - $viewPrintX;
	                                $newposY = $value->y - $viewPrintY;
									$posX = ($newposX * $ratioW);
	                                $posY = ($newposY * $ratioH);
	                                $posW = ($value->width * $ratioW);
	                                $posH = ($value->height * $ratioH);
	                                $textNo = rand();
	                                //echo $designDirName;exit;
	                                $imageDestName = $designDirName . "/" . $textNo . "normal.png";
	                                $query = $this->setTextQuery($value, $imageDestName,'nameNumber');
									//echo $query;exit;
									exec($imageMagickPath . $query);
                                	$pdf->RotatedImage($imageDestName, $posX, $posY, $posW, $posH, $rotation);
								}//End Name Number If
								
								if($value->name=='clipart')
								{
									
																		$newposX = $value->x - $viewPrintX;
	                                $newposY = $value->y - $viewPrintY;
									$rotation = (-($value->rotation));
                                	$posX = ($newposX * $ratioW);
                               	 	$posY = ($newposY * $ratioH);
                                	$posW = ($value->width * $ratioW);
                               	 	$posH = ($value->height * $ratioH);
									$colorable=($value->colorable);
                                	$designImgSource = str_replace(Mage::getBaseUrl(), Mage::getBaseDir() . '/', $value->src);
									$designImgSource=str_replace('.png','.png',$designImgSource);
																		$txtColor4 ='#'.$value->color;//$this->RGBToHex(255, 0, 0);
											
                                	$usrimg = $designDirName . '/' . rand() . 'usrimg.png';
                                		if($colorable==1)
										{
			                                if ($value->flipX == 'false' && $value->flipY == 'false') 
			                                {
			                        			
												 exec($imageMagickPath .' -alpha off -fill "#ff0000" -colorize 100% -alpha on '. '  "' . $designImgSource . '"  -trim  ' . $usrimg);
												//exec($imageMagickPath .' -alpha off -fill "#ff0000" -colorize 100% -alpha on'.$designImgSource.$usrimg);
											    	$pdf->Rotate($rotation);			
			                                	 //$pdf->Rotate($rotation);
												//$pdf->flipFlopEps($designImgSource, $posW, $posH, $posX, $posY, '', $txtColor4, true);
												//$pdf->ImageEpsColorable($designImgSource, $posX, $posY, $posW, $posH, $link='', true , $txtColor4);
			                                    $pdf->Image($usrimg, $posX, $posY, $posW, $posH);
			                                } else if ($value->flipX == 'true' && $value->flipY == 'false') {
			                                    exec($imageMagickPath .' -alpha off -fill "#ff0000" -colorize 100% -alpha on '. '  "' . $designImgSource . '"  -flop  ' . $usrimg);
			                                    $pdf->Rotate($rotation);
			                                    $pdf->Image($usrimg, $posX, $posY, $posW, $posH);
			                                    //unlink($usrimg);
			                                } else if ($value->flipX == 'false' && $value->flipY == 'true') {
			                                    exec($imageMagickPath .' -alpha off -fill "#ff0000" -colorize 100% -alpha on '. '  "' . $designImgSource . '"  -flip  ' . $usrimg);
			                                    $pdf->Rotate($rotation);
			                                    $pdf->Image($usrimg, $posX, $posY, $posW, $posH);
			                                    //unlink($usrimg);
			                                } else if ($value->flipX == 'true' && $value->flipY == 'true') {
			                                	exec($imageMagickPath .' -alpha off -fill "#ff0000" -colorize 100% -alpha on '. '  "' . $designImgSource . '"  -flip -flop  ' . $usrimg);
			                                    //exec($imageMagickPath . '  "' . $designImgSource . '"  -flip  -flop  ' . $usrimg);
			                                    $pdf->Rotate($rotation);
			                                    $pdf->Image($usrimg, $posX, $posY, $posW, $posH);
			                                    // unlink($usrimg);
			                                }
		                                }
                                       else
									   	{
									   		if ($value->flipX == 'false' && $value->flipY == 'false') 
			                                {
			                        			
												 exec($imageMagickPath . '  "' . $designImgSource . '"  -trim  ' . $usrimg);
												//exec($imageMagickPath .' -alpha off -fill "#ff0000" -colorize 100% -alpha on'.$designImgSource.$usrimg);
											    	$pdf->Rotate($rotation);
			
			                                	 //$pdf->Rotate($rotation);
												//$pdf->flipFlopEps($designImgSource, $posW, $posH, $posX, $posY, '', $txtColor4, true);
												//$pdf->ImageEpsColorable($designImgSource, $posX, $posY, $posW, $posH, $link='', true , $txtColor4);
			                                    $pdf->Image($usrimg, $posX, $posY, $posW, $posH);
			                                } else if ($value->flipX == 'true' && $value->flipY == 'false') {
			                                    exec($imageMagickPath . '  "' . $designImgSource . '"  -flop  ' . $usrimg);
			                                    $pdf->Rotate($rotation);
			                                    $pdf->Image($usrimg, $posX, $posY, $posW, $posH);
			                                    //unlink($usrimg);
			                                } else if ($value->flipX == 'false' && $value->flipY == 'true') {
			                                    exec($imageMagickPath .'  "' . $designImgSource . '"  -flip  ' . $usrimg);
			                                    $pdf->Rotate($rotation);
			                                    $pdf->Image($usrimg, $posX, $posY, $posW, $posH);
			                                    //unlink($usrimg);
			                                } else if ($value->flipX == 'true' && $value->flipY == 'true') {
			                                	exec($imageMagickPath . '  "' . $designImgSource . '"  -flip -flop  ' . $usrimg);
			                                    //exec($imageMagickPath . '  "' . $designImgSource . '"  -flip  -flop  ' . $usrimg);
			                                    $pdf->Rotate($rotation);
			                                    $pdf->Image($usrimg, $posX, $posY, $posW, $posH);
			                                    // unlink($usrimg);
			                                }
									   	}
								}//  end clipart ********************
									
							if($value->name=='UploadImage')
							{
								
								$newposX = $value->x - $viewPrintX;
	                            $newposY = $value->y - $viewPrintY;
								$rotation = (-($value->rotation));
	                        	$posX = ($newposX * $ratioW);
	                       	 	$posY = ($newposY * $ratioH);
	                        	$posW = ($value->width * $ratioW);
	                       	 	$posH = ($value->height * $ratioH);
								
								$colorable=($value->colorable);
	                        	$designImgSource = str_replace(Mage::getBaseUrl(), Mage::getBaseDir() . '/', $value->src);
								$convert1Flag=$value->convert1Flag;
								$clipUpload=$value->clipUpload;
								$convertSrc=$value->convertSrc;
								if($clipUpload=="upload")
								{
									if($convert1Flag=="true")
									{
											
										$designImgSource = str_replace(Mage::getBaseUrl(), Mage::getBaseDir() . '/', $convertSrc);
										$designImgSource=str_replace('/large', '/original', $designImgSource);	
									}
									else 
									{
										$designImgSource = str_replace(Mage::getBaseUrl(), Mage::getBaseDir() . '/', $value->src);
										$designImgSource=str_replace('/large', '/original', $designImgSource);	
										
									}
								}
								else 
								{
									if($convert1Flag=="true")
									{
											
										$designImgSource = str_replace(Mage::getBaseUrl(), Mage::getBaseDir() . '/', $convertSrc);
										//$designImgSource=str_replace('/thumb', '/original', $designImgSource);	
									}
									else 
									{
										$designImgSource = str_replace(Mage::getBaseUrl(), Mage::getBaseDir() . '/', $value->src);
										
									}
								}
								//echo $designImgSource;
								//$designImgSource=str_replace('.png','.png',$designImgSource);
							//************************  111111111111111111
								$usrimg = $designDirName . '/' . rand() . 'usrimg.png';
								if ($value->flipX == 'false' && $value->flipY == 'false') 
		                                {
		                        			//echo "aman";
											//echo $designImgSource;
											 exec($imageMagickPath . '  "' . $designImgSource . '"  -trim  ' . $usrimg);
											 //exit;
											 $pdf->Rotate($rotation);
											 $pdf->Image($usrimg, $posX, $posY, $posW, $posH);
		                                } else if ($value->flipX == 'true' && $value->flipY == 'false') {
		                                    exec($imageMagickPath . '  "' . $designImgSource . '"  -flop  ' . $usrimg);
		                                    $pdf->Rotate($rotation);
		                                    $pdf->Image($usrimg, $posX, $posY, $posW, $posH);
		                                    //unlink($usrimg);
		                                } else if ($value->flipX == 'false' && $value->flipY == 'true') {
		                                    exec($imageMagickPath .'  "' . $designImgSource . '"  -flip  ' . $usrimg);
		                                    $pdf->Rotate($rotation);
		                                    $pdf->Image($usrimg, $posX, $posY, $posW, $posH);
		                                    //unlink($usrimg);
		                                } else if ($value->flipX == 'true' && $value->flipY == 'true') {
		                                	exec($imageMagickPath . '  "' . $designImgSource . '"  -flip -flop  ' . $usrimg);
		                                    //exec($imageMagickPath . '  "' . $designImgSource . '"  -flip  -flop  ' . $usrimg);
		                                    $pdf->Rotate($rotation);
		                                    $pdf->Image($usrimg, $posX, $posY, $posW, $posH);
		                                    // unlink($usrimg);
		                                }

									}// end Upload Image

                            }// end inner loop
                           
							$zipArr[] = $dirName."/".$pdfFileName;						
		                    $pdf->Output($dirName."/".$pdfFileName);
							 
                    }
					if(count($nameNumTable)>0)
					{
						//echo '<pre>';
						//print_r($nameNumTable);exit;
						$printW = 1000;
                        $printH = 1400;
                        $ratioW = 1;
                        $ratioH = 1;
                        $size = array($printW, $printH);
                        $pdf = new PDF("P", "pt", $size);
                        $pdf->AddPage();
                        $pdfFileName = 138 . "-nameANDnumberINFO.pdf";
                        $x = 100;
                        $y = 30;
                        $pdf->SetTextColor(131, 129, 129);
						//echo "pawan";
						//echo "wan";
                        $pdf->SetFont('Arial', '', 30);
						//echo "font";
                        $pdf->Text($x, $y, 'Name');
                        $x = $x + 160;
                        $pdf->Text($x, $y, 'Number');
                        $x = $x + 170;
                        $pdf->Text($x, $y, 'size');
                        $x = $x+120;
                        $pdf->Text($x,$y,'NameSide');						$x = $x+190;
                        $pdf->Text($x,$y,'NumberSide');


                        $pdf->SetLineWidth(3);
                        $pdf->SetDrawColor(221, 221, 221);
                        $pdf->Line(40, 60, 930, 60);

                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetFont('Arial', '', 16);
                        $x = 110;
                        $y = 100;
                       // echo "exit";exit;
                        foreach ($nameNumTable as $nameNumValue) 
                        {
                        	$nameNumValue=(object) $nameNumValue;
                                //echo $nameNumValue->size;exit;
                                if($nameNumValue->nameCheck==true)
                                $pdf->Text($x, $y, $nameNumValue->name);
								else
								$pdf->Text($x, $y, "");	 
                                $x = $x + 170;
								if($nameNumValue->numCheck==true)
                                $pdf->Text($x, $y, $nameNumValue->number);
								else
                                $pdf->Text($x, $y, "");
                                $x = $x + 180;
								$pdf->Text($x,$y,$nameNumValue->size);
								$x = $x + 130;
								if($nameNumValue->nameCheck==true)
								{
									if($nameNumValue->nameSide==0)
	                                $pdf->Text($x, $y, "Front");
									else
									$pdf->Text($x, $y, "Back");
								}
								else
								$pdf->Text($x, $y, "");	 
								$x = $x + 200;
								if($nameNumValue->numCheck==true)
								{
									if($nameNumValue->numberSide==0)
									
	                                $pdf->Text($x, $y, "Front");
									else
									$pdf->Text($x, $y, "Back");	
								}
								else
                                $pdf->Text($x, $y, "");	 
                                $x = 110;
                                $y = $y + 50;
                            }
                            $zipArr[] = $dirName . "/" . $pdfFileName;
                            $pdf->Output($dirName . "/" . $pdfFileName);
					}//End Name Number Table
                //}
                $fileName = Mage::getBaseDir('media') . "/files/pdf/" . 138 . ".zip";
                $fd = fopen($fileName, "wb");
                $createZip = new ZipFile($fd);
                foreach ($zipArr as $filzip) 
                {
                    $zipfileName = substr($filzip, strrpos($filzip, "/") + 1);
                    $createZip->addFile($filzip, $zipfileName, true);
                }
                $createZip->close();
                $urlPath = $sitePath . 'designertool/pdffiles/download.php?fileName=' . 138 . ".zip";
                echo '<a style="display: block;" title="Order Detail" href="' . $urlPath . '">Download PDF</a></br>';
                echo '<a href="javascript:void(0);" onclick="generate_pdf(' . $id . ',\'0\');">Delete PDF</a>';
          //  }
        } 
        else {
            echo "pdf already generated";
        }
    }

    function setTextQuery($value, $imageDestName,$nameNumber) {

        if ($value->color == '000000')
        $txtColor = '#000002';
        else
        $txtColor="#".$value->color;
        $garvity = $value->align;
        $fontId = $value->fontId;
        $myfont = $value->font;
		$shapeType=$value->shapeType;
        $fontTTF = $value->fontTTF;
		//echo$value->flipY.$value->flipX; 
		$flipStr  = ($value->flipY=='false') ? '' : '-flip';//old
		$flopStr  = ($value->flipX=='false') ? '' : '-flop';//old
		//echo $flipStr."jdhj".$flopStr;
        //$fontFileName = Mage::getBaseDir('media')."/designer_tool_fonts/font_ttf/" . $fontTTF;
		$text=$value->text;
		$File = "TextFile.txt";
        $Handle = fopen($File, 'w');
        fwrite($Handle, $text);
        fclose($Handle);
		if($nameNumber=="nameNumber")
		{
			$flipStr='';
			$flopStr='';
			$garvity = "center";
			$query = '  -gravity "'.$garvity.'" -background transparent -depth 8  "'.$flipStr.'" "'.$flopStr.'"  -fill  "' . $txtColor . '" -font "' . $fontTTF . '"  -quality 100   -size 2000x2000 -dither None +antialias label:@"' . $File . '"  -trim png32:' . $imageDestName;
		}
		if($shapeType=='normal')
		{
       		
		}
		if($shapeType=="arcUp")
		{
			$distortValue = $value->distortValue;
			$step = 360 / 7;
		    $rotate = 0;
			$arc = $distortValue * $step;
			$query = '  -gravity "'.$garvity.'" -background transparent -depth 8 "'.$flipStr.'" "'.$flopStr.'" -fill "' . $txtColor . '" -font "' . $fontTTF . '" -rotate "'.$rotate.'" -distort Arc "' . $arc . ' ' . $rotate . '" -density 300   -quality 100   -size 2000x2000 -dither None +antialias label:@"' . $File . '"  -trim png32:' . $imageDestName;
		}
		if($shapeType=="arcDown")
		{
			// echo "<pre>";
			// print_r($value);
			// exit;

			$distortValue = $value->distortValue;
			$step = 360 / 7;
		    $rotate = 180;
			$arc = $distortValue * $step;
			$query = '  -gravity "'.$garvity.'" -background transparent -depth 8 "'.$flipStr.'" "'.$flopStr.'" -fill "' . $txtColor . '" -font "' . $fontTTF . '" -rotate "'.$rotate.'" -distort Arc "' . $arc . ' ' . $rotate . '" -density 300  -quality 100   -size 2000x2000 -dither None +antialias label:@"' . $File . '"  -trim png32:' . $imageDestName;
		}
        return $query;
    }

    function RGBToHex($r, $g, $b) {
//String padding bug found and the solution put forth by Pete Williams (http://snipplr.com/users/PeteW)
        $hex = "#";
        $hex.= str_pad(dechex($r), 2, "0", STR_PAD_LEFT);
        $hex.= str_pad(dechex($g), 2, "0", STR_PAD_LEFT);
        $hex.= str_pad(dechex($b), 2, "0", STR_PAD_LEFT);

        return $hex;
    }

}

$GenPDFobj = new GenPDF();
if ($_GET['flag']) {
	$GenPDFobj->generatePDF($_GET['orderId'], $_GET['url']);
} else {
   // $GenPDFobj->deletePDF($_GET['orderId'], $_GET['url']);
}
//$GenPDFobj->generatePDF(8, '');
?>
