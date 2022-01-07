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

    function deletePDF($id, $url) {
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
        if (!is_dir($dirName)) {
            @mkdir($dirName);
            @chmod($dirName, 0777);
        }
    }

    function generatePDF($id, $sitePath) {
		
		echo "amit dwivedi";
        $imageMagickPath = IMAGEMAGICKPATH;
        $db = Mage::getSingleton('core/resource')->getConnection('core_write');
        $jsonClass = new JSON;
        
        $order = Mage::getModel('sales/order')->load($id);
        $items = $order->getAllItems();
        $itemcount = count($items);
        
		//echo $itemcount;
		
        //echo "<pre>";print_r($id);exit;
        /*foreach ($items as $itemId => $item) {
			echo "<pre>";print_r($item->getData());
		}
		exit;
		*/
		 
        $_totalData = $order->getData();
        $orderId = $_totalData['increment_id'];

        $ordZipFileName = $orderId . ".zip";
        $directoryName = Mage::getBaseDir('media') . "/files/pdf/" . $ordZipFileName;
        
       // echo $directoryName;
        if (!is_file($directoryName)) {
            if ($itemcount > 0) {
            	//echo "hgsd";
                $zipArr = array();
                foreach ($items as $itemId => $item) 
                {
                	//echo "ana".$item['parent_item_id'];
				//if(!empty($item['parent_item_id']))
				if($item['product_type']=='simple')
				{
                	//echo"am"." ".$item->getId();
					$itemInfoRequest = $item->getProductOptions();
					$designId = $itemInfoRequest['info_buyRequest']['designId'];
					 
					$designCollection = Mage::getModel('designersoftware/designersoftware')->getCollection()
											->addFieldToFilter('designersoftware_id',$designId)
											->addFieldToFilter('status',1)											
											->getFirstItem();
											
					//echo '<pre>';print_r($itemInfoRequest['info_buyRequest']['designId']);exit;					
					$productId = $designCollection->getProductId();
                    
                    
                    //$catalog_product=Mage::getModel('catalog/product')->load($productId);
                    //$productSku=$catalog_product->getSku();
                    
                    //$dataArr = stripslashes(Mage::getResourceModel('catalog/product')->getAttributeRawValue($productId, 'data_array', 0));
                    //$rawProductId = stripslashes(Mage::getResourceModel('catalog/product')->getAttributeRawValue($productId, 'rawproduct_id', 0));
                    //$colorId = stripslashes(Mage::getResourceModel('catalog/product')->getAttributeRawValue($productId, 'rawproduct_color_id', 0));
                    //$jsonEncodedData = 
                    $jsonEncodedData 	= unserialize($designCollection->getPdfDataArr());
					$nameNumTable		= unserialize($designCollection->getNameNumArr());
					/// echo "<pre>"; print_r($nameNumTable);exit;
					//echo $productId; 
                    ///echo "<pre>"; print_r($jsonEncodedData);exit;
					        $dirName = Mage::getBaseDir('media') . "/files/pdf/" . $orderId;
                            $this->createDirectory($dirName); // Create First Level Directory	
                            $dirName = Mage::getBaseDir('media') . "/files/pdf/" . $orderId . "/" . $productId;
                            $this->createDirectory($dirName); // Create second Level Directory	
                            $designDirName = Mage::getBaseDir('media') . "/files/pdf/" . $orderId . "/designimage";
                            $this->createDirectory($designDirName); // Create Second Level Directory
					        $counterV = 1;
					        $myCon=1;
                    foreach ($jsonEncodedData as $datavalue) 
                    {
						//$myCon++;
						//echo $myCon;
                    	$datavalue = (object) $datavalue;
                    	///echo "jdsjdds".$datavalue->viewId;
                    	//exit;
                    	
						$printAreaW = $datavalue->viewWidth;
						$printAreaH = $datavalue->viewHeight;
	                    $printW = (($datavalue->printWidth)?$datavalue->printWidth * 72:3.97*72);
	                    $printH = (($datavalue->printHeight)?$datavalue->printHeight * 72:4.94*72);
						$ratioW = $printW / $printAreaW;
	                    $ratioH = $printH / $printAreaH;
	               //    echo $ratioW."y".$ratioH;
	                    $viewPrintX = $datavalue->printX;
	                   	$viewPrintY = $datavalue->printY;
						$objectScale=$datavalue->objectScale;
						if($objectScale==1.4)
						{
							$objectScale=1/1.4;
							
						}
						
					   $size		= array($printW, $printH);
	                   $pdf		= new PDF("P", "pt", $size);
	                   $pdf->AddPage(); 
	                   //$pdfFileName =$myCon. "_.pdf";
					   $pdfFileName = $datavalue->viewId . "_.pdf";
                        	foreach ($datavalue->data as $value) {
                        		$value = (object) $value;
                                if ($value->name == 'Text') 
                                {
                                    $rotation = (-($value->rotation));
									$newposX = $value->x - $viewPrintX;
	                                $newposY = $value->y - $viewPrintY;
									$posX = ($newposX * $ratioW*$objectScale);
	                                $posY = ($newposY * $ratioH*$objectScale);
	                                $posW = ($value->width * $ratioW*$objectScale);
	                                $posH = ($value->height * $ratioH*$objectScale);
	                                $textNo = rand();
	                                //echo $designDirName;exit;
	                                $imageDestName = $designDirName . "/" . $textNo . "normal.png";
	                                $query = $this->setTextEffectQuery($value, $imageDestName,$imageMagickPath);
									
									exec($imageMagickPath . ' ' . $query);
									//echo $imageMagickPath.' '.' -quality 100   -size 2000x2000 -resize 2000x2000 -dither None +antialias trim png32: '.$imageDestName;
									//exec($imageMagickPath.' '.$imageDestName.' -quality 100   -size 2000x2000  -resize 2000x2000 -dither None +antialias trim png32: '.$imageDestName);
									
                                	$pdf->RotatedImage($imageDestName, $posX, $posY, $posW, $posH, $rotation);
                                	//echo $query;exit;
								}// end text if
                                if($value->name == 'Number' || $value->name=="Name" )
								{
									


									$rotation = (-($value->rotation));
									$newposX = $value->x - $viewPrintX;
	                                $newposY = $value->y - $viewPrintY;
									$posX = ($newposX * $ratioW*$objectScale);
	                                $posY = ($newposY * $ratioH*$objectScale);
	                                $posW = ($value->width * $ratioW*$objectScale);
	                                $posH = ($value->height * $ratioH*$objectScale);
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
                                	$posX = ($newposX * $ratioW*$objectScale);
                               	 	$posY = ($newposY * $ratioH*$objectScale);
                                	$posW = ($value->width * $ratioW*$objectScale);
                               	 	$posH = ($value->height * $ratioH*$objectScale);
									$colorable=($value->colorable);
                                	$designImgSource = str_replace(Mage::getBaseUrl(), Mage::getBaseDir() . '/', $value->src);
									$designImgSource=str_replace('.png','.png',$designImgSource);
									

									$txtColor ='#'.$value->color;//$this->RGBToHex(255, 0, 0);
											
                                	$usrimg = $designDirName . '/' . rand() . 'usrimg.png';
                                		if($colorable==1)
										{
			                                if ($value->flipX == 'false' && $value->flipY == 'false') 
			                                {
			                        			
												 exec($imageMagickPath .' -alpha off -fill "'.$txtColor.'" -colorize 100% -alpha on '. '  "' . $designImgSource . '"  -trim  ' . $usrimg);
												//exec($imageMagickPath .' -alpha off -fill "#ff0000" -colorize 100% -alpha on'.$designImgSource.$usrimg);
											    	//$pdf->Rotate($rotation);
			
			                                	 //$pdf->Rotate($rotation);
												//$pdf->flipFlopEps($designImgSource, $posW, $posH, $posX, $posY, '', $txtColor4, true);
												//$pdf->ImageEpsColorable($designImgSource, $posX, $posY, $posW, $posH, $link='', true , $txtColor4);
			                                    $pdf->RotatedImage($usrimg, $posX, $posY, $posW, $posH,$rotation);
			                                } else if ($value->flipX == 'true' && $value->flipY == 'false') {
			                                    exec($imageMagickPath .' -alpha off -fill "'.$txtColor.'" -colorize 100% -alpha on '. '  "' . $designImgSource . '"  -flop  ' . $usrimg);
			                                   // $pdf->Rotate($rotation);
			                                    $pdf->RotatedImage($usrimg, $posX, $posY, $posW, $posH,$rotation);
			                                    //unlink($usrimg);
			                                } else if ($value->flipX == 'false' && $value->flipY == 'true') {
			                                    exec($imageMagickPath .' -alpha off -fill "'.$txtColor.'" -colorize 100% -alpha on '. '  "' . $designImgSource . '"  -flip  ' . $usrimg);
			                                  //  $pdf->Rotate($rotation);
			                                    $pdf->RotatedImage($usrimg, $posX, $posY, $posW, $posH,$rotation);
			                                    //unlink($usrimg);
			                                } else if ($value->flipX == 'true' && $value->flipY == 'true') {
			                                	exec($imageMagickPath .' -alpha off -fill "'.$txtColor.'" -colorize 100% -alpha on '. '  "' . $designImgSource . '"  -flip -flop  ' . $usrimg);
			                                    //exec($imageMagickPath . '  "' . $designImgSource . '"  -flip  -flop  ' . $usrimg);
			                                    //$pdf->Rotate($rotation);
			                                    $pdf->RotatedImage($usrimg, $posX, $posY, $posW, $posH,$rotation);
			                                    // unlink($usrimg);
			                                }
		                                }
                                       else
									   	{
									   		if ($value->flipX == 'false' && $value->flipY == 'false') 
			                                {
			                        			
												 exec($imageMagickPath . '  "' . $designImgSource . '"  -trim  ' . $usrimg);
												//exec($imageMagickPath .' -alpha off -fill "#ff0000" -colorize 100% -alpha on'.$designImgSource.$usrimg);
											    	//$pdf->Rotate($rotation);
			
			                                	 //$pdf->Rotate($rotation);
												//$pdf->flipFlopEps($designImgSource, $posW, $posH, $posX, $posY, '', $txtColor4, true);
												//$pdf->ImageEpsColorable($designImgSource, $posX, $posY, $posW, $posH, $link='', true , $txtColor4);
			                                    $pdf->RotatedImage($usrimg, $posX, $posY, $posW, $posH,$rotation);
			                                } else if ($value->flipX == 'true' && $value->flipY == 'false') {
			                                    exec($imageMagickPath . '  "' . $designImgSource . '"  -flop  ' . $usrimg);
			                                    //$pdf->Rotate($rotation);
			                                    $pdf->RotatedImage($usrimg, $posX, $posY, $posW, $posH,$rotation);
			                                    //unlink($usrimg);
			                                } else if ($value->flipX == 'false' && $value->flipY == 'true') {
			                                    exec($imageMagickPath .'  "' . $designImgSource . '"  -flip  ' . $usrimg);
			                                   // $pdf->Rotate($rotation);
			                                    $pdf->RotatedImage($usrimg, $posX, $posY, $posW, $posH,$rotation);
			                                    //unlink($usrimg);
			                                } else if ($value->flipX == 'true' && $value->flipY == 'true') {
			                                	exec($imageMagickPath . '  "' . $designImgSource . '"  -flip -flop  ' . $usrimg);
			                                    //exec($imageMagickPath . '  "' . $designImgSource . '"  -flip  -flop  ' . $usrimg);
			                                    //$pdf->Rotate($rotation);
			                                    $pdf->RotatedImage($usrimg, $posX, $posY, $posW, $posH,$rotation);
			                                    // unlink($usrimg);
			                                }
									   	}
								}//  end clipart ********************
									
							if($value->name=='UploadImage')
							{
								
								$newposX = $value->x - $viewPrintX;
	                            $newposY = $value->y - $viewPrintY;
								$rotation = (-($value->rotation));
	                        	$posX = ($newposX * $ratioW*$objectScale);
	                       	 	$posY = ($newposY * $ratioH*$objectScale);
	                        	$posW = ($value->width * $ratioW*$objectScale);
	                       	 	$posH = ($value->height * $ratioH*$objectScale);
								
								$colorable=($value->colorable);
	                        	$designImgSource = str_replace(Mage::getBaseUrl(), Mage::getBaseDir() . '/', $value->src);
								$convert1Flag=$value->convert1Flag;
								$clipUpload=$value->clipUpload;
								$convertSrc=$value->convertSrc;
								//echo $clipUpload;
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
											// $pdf->Rotate($rotation);
											 $pdf->RotatedImage($usrimg, $posX, $posY, $posW, $posH,$rotation);
		                                } else if ($value->flipX == 'true' && $value->flipY == 'false') {
		                                    exec($imageMagickPath . '  "' . $designImgSource . '"  -flop  ' . $usrimg);
		                                   // $pdf->Rotate($rotation);
		                                    $pdf->RotatedImage($usrimg, $posX, $posY, $posW, $posH,$rotation);
		                                    //unlink($usrimg);
		                                } else if ($value->flipX == 'false' && $value->flipY == 'true') {
		                                    exec($imageMagickPath .'  "' . $designImgSource . '"  -flip  ' . $usrimg);
		                                   // $pdf->Rotate($rotation);
		                                    $pdf->RotatedImage($usrimg, $posX, $posY, $posW, $posH,$rotation);
		                                    //unlink($usrimg);
		                                } else if ($value->flipX == 'true' && $value->flipY == 'true') {
		                                	exec($imageMagickPath . '  "' . $designImgSource . '"  -flip -flop  ' . $usrimg);
		                                    //exec($imageMagickPath . '  "' . $designImgSource . '"  -flip  -flop  ' . $usrimg);
		                                    //$pdf->Rotate($rotation);
		                                    $pdf->Image($usrimg, $posX, $posY, $posW, $posH,$rotation);
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
                        $pdfFileName = $orderId."-nameANDnumberINFO.pdf";
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
                        $pdf->Text($x,$y,'NameSide');
						$x = $x+190;
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
							//echo $nameNumValue->size."newline";
							if($nameNumValue->size)
							{
								//echo $nameNumValue->name;
								if($nameNumValue->name!='' || $nameNumValue->number!='')
								{
									//echo "anadi";
                                //echo $nameNumValue->size;exit;
                                //echo $nameNumValue->nameCheck;
                                if($nameNumValue->nameCheck==true)
								{
									//echo $y;
									   $pdf->Text($x, $y, strtoupper($nameNumValue->name));
								}
                             
								else
								$pdf->Text($x, $y, "");	 
                                $x = $x + 170;
								if($nameNumValue->numberCheck==true)
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
								if($nameNumValue->numberCheck==true)
								{
									if($nameNumValue->numSide==0)
									
	                                $pdf->Text($x, $y, "Front");
									else
									$pdf->Text($x, $y, "Back");	
								}
								else
                                $pdf->Text($x, $y, "");	 
                                $x = 110;

                                $y = $y + 50;
                            }
                            
                            }
                            }
							$zipArr[] = $dirName . "/" . $pdfFileName;
                            $pdf->Output($dirName . "/" . $pdfFileName);
					}//End Name Number Table
				}
                }
                $fileName = Mage::getBaseDir('media') . "/files/pdf/" . $orderId . ".zip";
                $fd = fopen($fileName, "wb");
                $createZip = new ZipFile($fd);
                foreach ($zipArr as $filzip) {
                    $zipfileName = substr($filzip, strrpos($filzip, "/") + 1);
                    $createZip->addFile($filzip, $zipfileName, true);
                }
                $createZip->close();
                $urlPath = $sitePath . 'designertool/pdffiles/download.php?fileName=' . $orderId . ".zip";
                echo '<a style="display: block;" title="Order Detail" href="' . $urlPath . '">Download PDF</a></br>';
                echo '<a href="javascript:void(0);" onclick="generate_pdf(' . $id . ',\'0\');">Delete PDF</a>';
            }
        } else {
            echo "pdf already generated";
        }
    }
	function setTextEffectQuery($value, $imageName,$imgMagick)
	{
		
		// echo '<pre>';
		// print_r($value);
		// exit;		$this->magick=$imgMagick;
		$this -> text = $value->text;
		$this -> File = "TextFile.txt";
		$this -> Handle = fopen($this -> File, 'w');
		fwrite($this -> Handle, $this -> text);
		fclose($this -> Handle);
		$this -> m = rand();
		
		$this -> imageDestName = $imageName;
		$this -> fontFileName = $value->fontTTF;
		$this -> ArcValue = $value->distortValue;
		$this -> txtColor = '#' . $value->color;
		
		$this->rotate=$value->arcRotate;
		$this->roofGravity=$value->roofGravity;
		$this -> textAlign = $value->align;
		
		$this -> outlineColor = '#' . $value->outlineColor;
		//this is for maintaining stroke width when change point size;
		$this -> strokeWidth = $value->outlineWidth*500/200;
		
		
		if ($this -> txtColor == '')
			$this -> txtColor = '#000000';
		$this -> effectName = $value->effectName;
		$this -> commonProperties();
		//echo $this->effectName;
		switch ($this->effectName) {
			case 'Normal' :
				{
					$this -> normal();
					
				}
				break;
			case 'Curve' :
				{
					$this -> curve();
					
				}
				break;
			case 'Arch' :
				{
					$this -> normal();
					exec($this -> magick . ' ' . $this -> query);
					$this -> arc($this -> imageDestName);
					
				}
				break;
				case 'Bridge' :
				{
					$this -> normal();
					exec($this -> magick . ' ' . $this -> query);
					$this -> bridge($this -> imageDestName);
					
				}
				break;
				case 'Valley' :
				{
					$this -> normal();
					exec($this -> magick . ' ' . $this -> query);
					$this -> valley($this -> imageDestName);
					
				}
				break;
				case 'Pinch' :
				{
					$this -> normal();
					exec($this -> magick . ' ' . $this -> query);
					$this -> pinch($this -> imageDestName);
					
				}
				break;
				case 'Bulge' :
				{
					$this -> normal();
					exec($this -> magick . ' ' . $this -> query);
					$this -> bulge($this -> imageDestName);
					
				}
				break;
				case 'Roof' :
				{
					$this -> normal();
					exec($this -> magick . ' ' . $this -> query);
					$this -> roof($this -> imageDestName);
					
				}
				break;
				case 'Wedge' :
				{
					$this -> normal();
					exec($this -> magick . ' ' . $this -> query);
					$this -> wedge($this -> imageDestName);
					
				}
				break;
			default :
				echo "anything wrong condition";
				break;
		}
		return $this->query;
		
		
	}
	function commonProperties()
	{
		$this -> start = '-background none -depth 8 ';
		if ($this -> strokeWidth > 0) {
			$this -> strokeText = ' -strokewidth "' . $this -> strokeWidth . '"  -stroke "' . $this -> outlineColor . '"';
		} else {
			$this -> strokeText = '';
		}
		$this -> textNormalProperties = '  -font "' . $this -> fontFileName . '" -gravity "' . $this -> textAlign . '"  ';
		$this -> textMainProperties = ' -fill "' . $this -> txtColor . '" -pointsize 500 -virtual-pixel Transparent ';
		$this -> writeTextFile = ' -dither None label:@"' . $this -> File . '"';
		$this -> IncludeBorderFile = $this -> textNormalProperties . $this -> textMainProperties . $this -> strokeText . $this -> writeTextFile;
		$this -> WithoutBorderFile = $this -> textNormalProperties . $this -> textMainProperties . ' -strokewidth  0 -stroke "' . $this -> txtColor . '" ' . $this -> writeTextFile;
		$this -> compositeImagesCommand = ' -composite ';
		$this -> outputImage = ' -trim png32: ' . $this -> imageDestName;
	}
	public function temp($w, $h, $gravity)
	{
		$start = ' -background transparent -depth 8';
		if ($this -> strokeWidth > 0)
		{
			$this -> strokeWidth=2;
			$normalText = ' -fill "' . $this -> txtColor . '" ' . '-size "' . $w . '"x"' . $h . '!" -strokewidth ' . $this -> strokeWidth . '  -stroke "' . $this -> outlineColor . '" -dither None  label:@"' . $this -> File . '"';
		}
		else
			$normalText = ' -fill "' . $this -> txtColor . '" ' . '-size "' . $w . '"x"' . $h . '!"  -dither None  label:@"' . $this -> File . '"';
		$query = $start . ' -font "' . $this -> fontFileName . '" ' . '-gravity "' . $gravity . '" ' . $normalText . ' ';
		
		 return $query;

	}
	function normal()
	{
		
		$this -> query = $this -> start . $this -> IncludeBorderFile . $this -> WithoutBorderFile . $this -> compositeImagesCommand . $this -> outputImage;
		return $this -> query;
	}
	public function curve() 
	{
		//echo 'a=========  '.$this -> ArcValue;
		//$arcString = ' -distort Arc "'.$this -> ArcValue.'" ';
		$distortValue = $this -> ArcValue;
		$step = 360 / 15;
		$rotate = $this->rotate;
		$arc = $distortValue * $step;
		$arcString = ' -rotate ' . $rotate . ' -distort Arc "' . $arc . ' ' . $rotate . '"';
		$this -> query = $this -> start . $this -> IncludeBorderFile . $this -> WithoutBorderFile . $arcString . $this -> compositeImagesCommand . $this -> outputImage;
		return $this -> query;
	}
	public function arc($resultImage)
	{
		$distortValue = $this -> ArcValue;
		$size = getimagesize($resultImage);
		$w = $size[0];
		$distort = 8 *$distortValue;
		$wavelength = $w * 2;
		$this -> query = $resultImage.' -matte -channel RGBA -virtual-pixel transparent -background none -wave "' . $distort . '"x"' . $wavelength . '" -gravity South -trim '.$this -> outputImage;
		return $this -> query;
	}
	
	public function bridge($resultImage)
	{
		$size = getimagesize($resultImage);
		$w = $size[0];
		$h = $size[1];

		$this -> query = ' ' . $this -> temp($w, $h * 3, 'South') . ' ' . $resultImage;
		exec($this -> magick . '  ' . $this -> query);


		
		$distort = $this -> ArcValue;

		$b = $distort * (pow(2 * min($w, $h) / (1.3 * ($w + $h)), 2));
		$a = (1 - $distort);
		$this -> query = ' -matte -channel RGBA -virtual-pixel transparent -monitor ' . $resultImage . ' -distort barrelinverse "0 0 0 1   0 ' . $b . ' 0 ' . $a . '" -trim  ' . $this -> outputImage;
		return $this -> query;
		//exec($this -> magick . '  ' . $this -> query);
	}
	public function valley($resultImage)
	{
		$size = getimagesize($resultImage);
		$w = $size[0];
		$h = $size[1];

		$this -> query = ' ' . $this -> temp($w, $h * 3, 'North') . ' ' . $resultImage;
		exec($this -> magick . '  ' . $this -> query);



		$distort = $this -> ArcValue;

		$b = $distort * (pow(2 * min($w, $h) / (1.4 * ($w + $h)), 2));
		$a = (1 - $distort);
		$this -> query = ' -matte -channel RGBA -virtual-pixel transparent -monitor ' . $resultImage . ' -distort barrelinverse "0 0 0 1   0 ' . $b . ' 0 ' . $a . '" -trim  ' . $this -> outputImage;
		return $this -> query;
	}
	public function pinch($resultImage)
	{
		$size = getimagesize($resultImage);
		
		$w = $size[0];
		$h = $size[1];
		$distort = $this -> ArcValue;
		

		$b=$distort * (pow(2 * min($w, $h) / (1.6 * ($w + $h)), 2));
		$a= (1 - $distort);
		$this -> query=' -matte -channel RGBA -virtual-pixel transparent  -monitor '. $resultImage.' -distort barrelinverse "0 0 0 1   0 ' . $b . ' 0 ' . $a . '" -trim png:'  .$this -> outputImage;
		return $this -> query;
	}
	public function bulge($resultImage)
	{
		$distort = $this -> ArcValue;
		$this -> query = '-monitor ' . $resultImage . ' -matte -channel RGBA -virtual-pixel transparent -distort Barrel "0.0 0.0 0.0 1   0.0 0.0 '.$distort.' 0.80"  -trim ' . $this -> outputImage;
		return $this -> query;
	}
	public function roof($resultImage)
	{
		$size = getimagesize($resultImage);
		$w = $size[0];
		$h = $size[1];

		// $distortValue = 5;
		// $distort = .3 / 7;
		$distortValue = $this -> ArcValue;
		$roofGravity=$this->roofGravity;
		if($roofGravity=="south")
		{
			$distort = .3 / 5;
			$distort = $distort * $distortValue;
	
			$tmp0 = $resultImage;
			exec("identify -ping -format %w " . $tmp0 . "", $wd);
			exec("identify -ping -format %h " . $tmp0 . "", $ht);
			$dim = $wd[0] . 'x' . $ht[0];
			exec('convert xc: -format "%[fx:' . $w . '/2]" info:', $halfw);
			exec('convert xc: -format "%[fx:' . $h . '*1.5]" info:', $newhh);
			exec('convert xc: -format "%[fx:1+' . $distort . '+.7]" info:', $a);
			exec('convert xc: -format "%[fx:-' . $distort . ']" info:', $b);
	
			$this -> query = '' . $resultImage . '  -matte -channel RGBA -virtual-pixel transparent  -background none  -gravity '.$roofGravity.' -extent ' . $w . 'x' . $newhh[0] . ' -distort barrelinverse "0 0 0 1   0 0 ' . $b[0] . ' ' . $a[0] . '  ' . $halfw[0] . ' ' . $newhh[0] . '" -trim  ' . $this -> outputImage;
		}
		else if($roofGravity=="bottom")
		{
			$distort = $this -> ArcValue;
			$this -> query = '-monitor ' . $resultImage . ' -matte -channel RGBA -virtual-pixel transparent -distort Barrel "0.0 0.0 0.0 1   0.0 0.0 '.$distort.' 0.80"  -trim ' . $this -> outputImage;
			return $this -> query;
		} 
		else{
			
			$distort = .3 / 5;
			$distort = $distort * $distortValue;
	
			$tmp0 = $resultImage;
			exec("identify -ping -format %w " . $tmp0 . "", $wd);
			exec("identify -ping -format %h " . $tmp0 . "", $ht);
			$dim = $wd[0] . 'x' . $ht[0];
			exec('convert xc: -format "%[fx:' . $w . '/2]" info:', $halfw);
			exec('convert xc: -format "%[fx:' . $h . '*1.5]" info:', $newhh);
			exec('convert xc: -format "%[fx:1+' . $distort . '+.7]" info:', $a);
			exec('convert xc: -format "%[fx:-' . $distort . ']" info:', $b);
	
			$this -> query = '' . $resultImage . ' -matte -channel RGBA -virtual-pixel transparent -background none  -gravity north -extent ' . $w . 'x' . $newhh[0] . ' -distort barrelinverse "0 0 0 1   0 0 ' . $b[0] . ' ' . $a[0] . '   ' . $halfw[0] . ' 0 " -trim   ' . $this -> outputImage;
			
		}
		
		
		return $this -> query;
		
	}
	public function wedge($resultImage)
	{
		$size = getimagesize($resultImage);
		$w = $size[0];
		$h = $size[1];
		$distortValue = $this -> ArcValue;
		$roofGravity=$this->roofGravity;
		if($roofGravity=="right")
		{
			$dMax = $h / $distortValue;
			$dPer = $dMax / 8;
			$d = $dPer * 5;
	
			$x11 = 0;
			$x12 = 0;
			$y11 = 0;
			$y12 = 0;
	
			$x21 = 0;
			$x22 = $h;
			$y21 = 0;
			$y22 = $h;
	
			$x31 = $w;
			$x32 = $h;
			$y31 = $w;
			$y32 = $h - $d;
	
			$x41 = $w;
			$x42 = -50;
			$y41 = $w;
			$y42 = $d;
	
			$this -> query = $resultImage . ' -matte -channel RGBA -virtual-pixel transparent -interpolate Spline -distort Bilinear "' . $x11 . ',' . $x12 . ',' . $y11 . ',' . $y12 . '  ' . $x21 . ',' . $x22 . ',' . $y21 . ',' . $y22 . '  ' . $x31 . ',' . $x32 . ',' . $y31 . ',' . $y32 . ' ' . $x41 . ',' . $x42 . ',' . $y41 . ',' . $y42 . '"' . "   -trim  " . $this -> outputImage;
		}
		else if($roofGravity=="left")
		{
			$dMax = $h / $distortValue;
			$dPer = $dMax / 8;
			$d = $dPer * 5;
	
			$x11 = 0;
			$y11 = -50;
			$x12 = 0;
			$y12 = $d;
	
			$x21 = 0;
			$y21 = $h;
			$x22 = 0;
			$y22 = $h - $d;
	
			$x31 = $w;
			$y31 = $h;
			$x32 = $w;
			$y32 = $h;
	
			$x41 = $w;
			$y41 = 0;
			$x42 = $w;
			$y42 = 0;
	
			$this -> query = $resultImage . ' -matte -channel RGBA -virtual-pixel transparent -interpolate Spline -distort Bilinear "' . $x11 . ',' . $y11 . ',' . $x12 . ',' . $y12 . '  ' . $x21 . ',' . $y21 . ',' . $x22 . ',' . $y22 . '  ' . $x31 . ',' . $y31 . ',' . $x32 . ',' . $y32 . ' ' . $x41 . ',' . $y41 . ',' . $x42 . ',' . $y42 . '"' . " -trim  " . $this -> outputImage;
			
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
		
		
        return $query;
    }

    function RGBToHex($r, $g, $b) 
    {
//String padding bug found and the solution put forth by Pete Williams (http://snipplr.com/users/PeteW)
        $hex = "#";
        $hex.= str_pad(dechex($r), 2, "0", STR_PAD_LEFT);
        $hex.= str_pad(dechex($g), 2, "0", STR_PAD_LEFT);
        $hex.= str_pad(dechex($b), 2, "0", STR_PAD_LEFT);

        return $hex;
    }

}

$GenPDFobj = new GenPDF();
//$_GET['flag'] = 1;
if ($_GET['flag']) {	
	$GenPDFobj->generatePDF($_GET['orderId'], $_GET['url']);
} else {
    $GenPDFobj->deletePDF($_GET['orderId'], $_GET['url']);
}
//$GenPDFobj->generatePDF(8, '');
?>
