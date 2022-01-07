<?php

include_once(__DIR__ . '/../JSON.php');
include_once("pdf.php");
include_once("zip.class.php");
require_once ('chinese.php');
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
        $orderRecId = $id;
        $order = Mage::getModel('sales/order')->load($id);
        $_totalData = $order->getData();
        $orderId = $_totalData['increment_id'];
        @unlink(Mage::getBaseDir('media').'/inky/pdf/' . $orderId . ".zip");
        $this->DELETE_RECURSIVE_DIRS(Mage::getBaseDir('media').'/inky/pdf/' . $orderId);
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
		
		
        $imageMagickPath = IMAGEMAGICKPATH;//"/usr/local/bin/convert";//
		//echo $imageMagickPath;
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
        $directoryName = Mage::getBaseDir('media') . "/inky/pdf/" . $ordZipFileName;
        
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
					//echo '<pre>';print_r($order->getData());exit;
					//$designCode = $itemInfoRequest['info_buyRequest']['options']['7'];
					$designCode = $itemInfoRequest['options']['0']['value'];
					 
					$designCollection = Mage::getModel('designersoftware/orders_design')->getCollection()
											->addFieldToFilter('order_id',$id)
											->addFieldToFilter('style_design_code',$designCode)
											->addFieldToFilter('status',1)											
											->getFirstItem();
						
					$anglesCollection = Mage::getModel('designersoftware/angles')->getAnglesCollection();						
											
					//echo '<pre>';print_r($itemInfoRequest['info_buyRequest']['designId']);exit;
					//echo '<pre>';print_r($designCollection);exit;
					//$customerId = $designCollection->getCustomerId();
					//$customerCollection = Mage::getModel('customer/customer')->load($customerId);
					//$customerName = $customerCollection->getName();
					$customerName = $order->getCustomerName();
					
					$logo = Mage::getStoreConfig('design/header/logo_src');
					$logoFullPath = Mage::getBaseDir('skin'). DS . "frontend" . DS . "rwd" . DS . "inky1003" . DS . $logo;
					 	//echo $logoFullPath;
						//echo $customerName;	
					
					$designId  = $designCollection->getId();				
					$productId = $designCollection->getProductId();
					$copyRightText 			= Mage::getStoreConfig('designerfront/footer/copyright');
					 if(!$copyRightText)
						$copyRightText="All Images rendered though our design tool are copyrighted by JB Cases. (C) 2015";
					 //echo $copyRightText;
					 //echo $designId;
					 //echo "next";
					 
					$priceInfoArr = unserialize($designCollection->getPriceInfoArr());
					$chineseInfoArr = unserialize($designCollection->getChineseInfoArr());  
                  //echo '<pre>';print_r($priceInfoArr);
					$dirName = Mage::getBaseDir('media') . DS . "inky" . DS . "pdf" . DS . $orderId;
					$this->createDirectory($dirName); // Create First Level Directory	
					$dirName = Mage::getBaseDir('media') . DS . "inky" . DS . "pdf" . DS . $orderId . DS . $productId;
					$this->createDirectory($dirName); // Create second Level Directory	
					$designDirName = Mage::getBaseDir('media') . DS . "inky" . DS . "pdf" . DS . $orderId . DS . "designimage";
					$this->createDirectory($designDirName); // Create Second Level Directory
					$counterV = 1;
					$myCon=1;
                    // $color="#000000";
					 // $font="Arial.ttf";
					 // $align="west";
					 // $text_val=$copyRightText;
					 // $randCopywright = $designDirName.'/'.rand();
					// exec($imageMagickPath.' -pointsize 16 -background none -fill "'.$color.'" -font "'.$font.'" -gravity '.$align.'   label:"'.$text_val.'" -trim '.$randCopywright.'.png');
                   	$images=array();
                    foreach($anglesCollection as $angles):
						$images[] = Mage::getBaseDir('media') . DS . "inky" . DS . "orders" . DS . $designCollection->getStyleDesignCode() . DS . "original" . DS . $angles->getTitle().'.png';
                    endforeach;
                  //  echo '<pre>';print_r($images);
                   // exit;						
						//echo "beforeAddPage";
					$pageCreator=612;	
					$printWidth=792;
					$printHeight=612;
					   $size		= array($printWidth, $printHeight);
	                   $pdf		= new PDF("P", "pt", $size);
	                   $pdf->AddPage(); 
	                   //$pdfFileName =$myCon. "_.pdf";
	                  // echo $orderId;
					   //echo "next";
					   $pdfFileName = $designId ."_".$customerName."_Workshop.pdf";
					   $pdfCounter=0;
					   list($posW, $posH) = getimagesize($logoFullPath);
					  // echo $posW;
					   $posX=(792-$posW)/2;
					   $posY=20;
					    $pdf->Image($logoFullPath, $posX, $posY, $posW, $posH);
	                    foreach ($images as $image) 
	                    {
	                    	//echo $image;
							//exit;
							$posX=$pdfCounter*90;
							$posY=220;
	                    	//list($posW, $posH) = getimagesize($image);
							$posW=147;
							$posH=274;
							
	                        $pdf->Image($image, $posX, $posY, $posW, $posH);
							$pdfCounter++;	
								
	                       
	                    }
							
						  $pdf->SetTextColor(0, 0, 0);
						  $pdf->SetFont('Arial', '', 11);
						  $x1=100;
						  $y1=100;
						  $pdf->Text($x1, $y1, 'Customer:');
						  $x1=$x1+150;
						  $pdf->Text($x1, $y1, $customerName);
						  $x1=100;
						  $y1=$y1+30;
						  $pdf->Text($x1, $y1, 'Order#');
						  $x1=$x1+150;
						  $pdf->Text($x1, $y1, $orderId);
						  $x1=100;
						  $y1=$y1+30;
						  $pdf->Text($x1, $y1, 'DesignCode:');
						  $x1=$x1+150;
						  $pdf->Text($x1, $y1, $designCode);
						  
						if(count($chineseInfoArr)>0)
						{
							
							$pdf->SetFont('Arial', '', 9);
							$pdf->Text(200, 600, $copyRightText);
							$x = 10;
	                        $y = 520;
	                        $pdf->SetTextColor(0, 0, 0);
							$pdf->SetFont('Arial', '', 15);
							//echo "font";
							$pdf->Text($x, $y, 'Id');
	                        $x = $x + 30;
	                        $pdf->Text($x, $y, 'Area');
	                        $x = $x + 140;
	                        $pdf->Text($x, $y, 'Part');
	                        
							$x = $x + 180;
							$pdf->AddGBFont();
							$pdf->SetFont('GB', '', 20);
							//$pdf->SetFont('hanwangkantan', '', 40);
							
							//part
							$color="#000000";
							$font="font_chinese.ttf";
							$align="west";
							$text_val="范围";
							$rand = $designDirName.'/'.rand();
							exec($imageMagickPath.' -pointsize 16 -background none -fill "'.$color.'" -font "'.$font.'" -gravity '.$align.'   label:"'.$text_val.'" -trim '.$rand.'.png');
							//echo $imageMagickPath.' -pointsize 16 -background none -fill "'.$color.'" -font "'.$font.'" -gravity '.$align.'   label:"'.$text_val.'" -trim '.$rand.'.png';
	                      // 	exit;
	                       	list($posW, $posH) = getimagesize($rand.'.png');
							$pdf->Image($rand.'.png', $x, $y-10, $posW, $posH);
	                        $x = $x + 100;
							
							$color="#000000";
							$font="font_chinese.ttf";
							$align="west";
							$text_val="部分";
							$rand = $designDirName.'/'.rand();
							exec($imageMagickPath.' -pointsize 16 -background none -fill "'.$color.'" -font "'.$font.'" -gravity '.$align.'   label:"'.$text_val.'" -trim '.$rand.'.png');
	                       // $pdf->Text($x, $y, '部分');
	                       	list($posW, $posH) = getimagesize($rand.'.png');
							$pdf->Image($rand.'.png', $x, $y-10, $posW, $posH);
							$x = $x + 140;
							$pdf->SetFont('Arial', '', 15);
							$pdf->Text($x, $y, 'Color');
							$x = $x + 100;
							$pdf->SetFont('GB', '', 15);
							//$pdf->SetFont('hanwangkantan', '', 40);
							$color="#000000";
							$font="font_chinese.ttf";
							$align="west";
							$text_val="颜色";
							$rand = $designDirName.'/'.rand();
							exec($imageMagickPath.' -pointsize 16 -background none -fill "'.$color.'" -font "'.$font.'" -gravity '.$align.'   label:"'.$text_val.'" -trim '.$rand.'.png');
							//$pdf->Text($x, $y, '颜色');
							list($posW, $posH) = getimagesize($rand.'.png');
							$pdf->Image($rand.'.png', $x, $y-10, $posW, $posH);
						    $pdf->SetLineWidth(3);
                    	    $pdf->SetDrawColor(221, 221, 221);
                    	    $pdf->Line(10, 530, 740, 530);
						    $pdf->SetTextColor(0, 0, 0);
	                        $pdf->SetFont('Arial', '', 11);
	                        $x = 10;
	                        $y = 550;
							 foreach ($chineseInfoArr as $priceInfoArrValue) 
                        	{
                        	$priceInfoArrValue=(object) $priceInfoArrValue;                        	
							//echo $nameNumValue->size."newline";
							//echo $priceInfoArrValue->colorId;
							//if($priceInfoArrValue->colorId)
							//{
								
								$pdf->SetFont('Arial', '', 9);
								//echo '<pre>';print_r($priceInfoArrValue);exit;
								if($priceInfoArrValue->layerId)
								$pdf->Text($x, $y, $priceInfoArrValue->layerId);
								else if($priceInfoArrValue->clipartId)
								$pdf->Text($x, $y, $priceInfoArrValue->clipartId);
								else if($priceInfoArrValue->optionId)
								$pdf->Text($x, $y, $priceInfoArrValue->optionId);
								else if($priceInfoArrValue->text)
								$pdf->Text($x, $y, $priceInfoArrValue->colorId);
								
                            	$x = $x + 30;
								//Area
								if($priceInfoArrValue->layerId)
								$pdf->Text($x, $y, $this->getTranslation($priceInfoArrValue,'1',"partStyleId"));
								else if($priceInfoArrValue->clipartId)
								$pdf->Text($x, $y, $this->getTranslation($priceInfoArrValue,'1',"clipartId"));
								else if($priceInfoArrValue->optionId)
								$pdf->Text($x, $y, $this->getTranslation($priceInfoArrValue,'1',"optionId"));
								else if($priceInfoArrValue->text)
								$pdf->Text($x, $y, $priceInfoArrValue->text);
                            	$x = $x + 140;
								//parts
                               	if($priceInfoArrValue->layerId)
								$pdf->Text($x, $y, $this->getTranslation($priceInfoArrValue,'1',"layerId"));
								else if($priceInfoArrValue->clipartId)
								$pdf->Text($x, $y, $this->getTranslation($priceInfoArrValue,'1',"clipartId"));
								else if($priceInfoArrValue->optionId)
								$pdf->Text($x, $y, $this->getTranslation($priceInfoArrValue,'1',"optionId"));
								else if($priceInfoArrValue->text)
								{
									if($priceInfoArrValue->printingType)
									{
										$printingType=$priceInfoArrValue->printingType;
									}
									else 
									{
										
										$printingType=" ";
									}
									
									 $pdf->Text($x, $y,$printingType);
								}
								$x = $x + 180;
								
								$pdf->SetFont('GB', '', 9);
								//area
								if($priceInfoArrValue->layerId)
								{
									$color="#000000";
									$font="font_chinese.ttf";
									$align="west";
									$text_val=$this->getTranslation($priceInfoArrValue,'2',"partStyleId");
									$rand = $designDirName.'/'.rand();
									
									if($text_val)
									{
										exec($imageMagickPath.' -pointsize 16 -background none -fill "'.$color.'" -font "'.$font.'" -gravity '.$align.'   label:"'.$text_val.'" -trim '.$rand.'.png');
									
									list($posW, $posH) = getimagesize($rand.'.png');
									$pdf->Image($rand.'.png', $x, $y-10, $posW, $posH);
									}
									
									//$pdf->Text($x, $y, $this->getTranslation($priceInfoArrValue,'2',"partStyleId"));
								}
								else if($priceInfoArrValue->clipartId)
								{
									$color="#000000";
									$font="font_chinese.ttf";
									$align="west";
									$text_val=$this->getTranslation($priceInfoArrValue,'2',"clipartId");
									$rand = $designDirName.'/'.rand();
									
									if($text_val)
									{
										exec($imageMagickPath.' -pointsize 16 -background none -fill "'.$color.'" -font "'.$font.'" -gravity '.$align.'   label:"'.$text_val.'" -trim '.$rand.'.png');
									
									list($posW, $posH) = getimagesize($rand.'.png');
									$pdf->Image($rand.'.png', $x, $y-10, $posW, $posH);
									}
									
									//echo $priceInfoArrValue->clipartId;
									//$pdf->SetFont('Arial', '', 9);
									//$pdf->Text($x, $y, $this->getTranslation($priceInfoArrValue,'2',"clipartId"));
								}
								
								else if($priceInfoArrValue->optionId)
								{
									$pdf->SetFont('Arial', '', 9);
									$pdf->Text($x, $y, $this->getTranslation($priceInfoArrValue,'2',"optionId"));
								}
								else if($priceInfoArrValue->text)
								{
									$color="#000000";
									$font="font_chinese.ttf";
									$align="west";
									$text_val="文本";
									$rand = $designDirName.'/'.rand();
									
									
									exec($imageMagickPath.' -pointsize 16 -background none -fill "'.$color.'" -font "'.$font.'" -gravity '.$align.'   label:"'.$text_val.'" -trim '.$rand.'.png');
									
									list($posW, $posH) = getimagesize($rand.'.png');
									$pdf->Image($rand.'.png', $x, $y-10, $posW, $posH);
									//$pdf->SetFont('Arial', '', 9);
									//$pdf->Text($x, $y, $priceInfoArrValue->text);
								}
								
                            	$x = $x + 100;
								
                            	
								$pdf->SetFont('GB', '', 9);
                                if($priceInfoArrValue->layerId)
								{
									$color="#000000";
									$font="font_chinese.ttf";
									$align="west";
									$text_val=$this->getTranslation($priceInfoArrValue,'2',"layerId");
									$rand = $designDirName.'/'.rand();
									
									if($text_val)
									{
										exec($imageMagickPath.' -pointsize 16 -background none -fill "'.$color.'" -font "'.$font.'" -gravity '.$align.'   label:"'.$text_val.'" -trim '.$rand.'.png');
									
									list($posW, $posH) = getimagesize($rand.'.png');
									$pdf->Image($rand.'.png', $x, $y-10, $posW, $posH);
									}
									
									//$pdf->Text($x, $y, $this->getTranslation($priceInfoArrValue,'2',"layerId"));
								}
								
								else if($priceInfoArrValue->clipartId)
								{
									$color="#000000";
									$font="font_chinese.ttf";
									$align="west";
									$text_val=$this->getTranslation($priceInfoArrValue,'2',"clipartId");
									if($text_val)
									{
										$rand = $designDirName.'/'.rand();
									
									
									exec($imageMagickPath.' -pointsize 16 -background none -fill "'.$color.'" -font "'.$font.'" -gravity '.$align.'   label:"'.$text_val.'" -trim '.$rand.'.png');
									
									list($posW, $posH) = getimagesize($rand.'.png');
									$pdf->Image($rand.'.png', $x, $y-10, $posW, $posH);
									}
									
									//$pdf->SetFont('Arial', '', 9);
									//$pdf->Text($x, $y, $this->getTranslation($priceInfoArrValue,'2',"clipartId"));
								}
								
								else if($priceInfoArrValue->optionId)
								{
									$pdf->SetFont('Arial', '', 9);
									$pdf->Text($x, $y, $this->getTranslation($priceInfoArrValue,'2',"optionId"));
								}
								
								
                            	$x = $x + 140;
                            	$pdf->SetFont('Arial', '', 9);
								if($priceInfoArrValue->colorId)
								$pdf->Text($x, $y, $this->getTranslation($priceInfoArrValue,'1',"colorId"));
								$x = $x + 100;
                            	$pdf->SetFont('GB', '', 9);
								if($priceInfoArrValue->colorId)
								{
									$color="#000000";
									$font="font_chinese.ttf";
									$align="west";
									$text_val=$this->getTranslation($priceInfoArrValue,'2',"colorId");
									if($text_val)
									{
										$rand = $designDirName.'/'.rand();
									
									
									exec($imageMagickPath.' -pointsize 16 -background none -fill "'.$color.'" -font "'.$font.'" -gravity '.$align.'   label:"'.$text_val.'" -trim '.$rand.'.png');
									
									list($posW, $posH) = getimagesize($rand.'.png');
									$pdf->Image($rand.'.png', $x, $y-10, $posW, $posH);
									}
									
									//$pdf->Text($x, $y, $this->getTranslation($priceInfoArrValue,'2',"layerId"));
									//$pdf->Text($x, $y, $this->getTranslation($priceInfoArrValue,'2',"colorId"));
								}
								
                                $x = 10;
                                $y = $y + 30;
								//echo $pageCreator;
								//echo $y;
								if($y>($pageCreator-20))
								{
									//echo "nextPage";
									//$pageCreator=$pageCreator+612;
								
									 $y=100;
									 $pdf->AddPage(); 
									 $pdf->SetFont('Arial', '', 9);
									$pdf->Text(200, 600, $copyRightText);
								}
                            
                            
                           // }
                            }
							$zipArr[] = $dirName."/".$pdfFileName;						
			            	$pdf->Output($dirName."/".$pdfFileName);
						}
						if(count($priceInfoArr)>0)
						{
							
							$pageCreator=612;	
							$printWidth=792;
							$printHeight=612;
						    $size		= array($printWidth, $printHeight);
		                   $pdf		= new PDF("P", "pt", $size);
		                   $pdf->AddPage(); 
		                   //$pdfFileName =$myCon. "_.pdf";
						   $pdfFileName = $designId . "_".$customerName.".pdf";
						   $pdfCounter=0;
						   list($posW, $posH) = getimagesize($logoFullPath);
						  // echo $posW;
						  $pdf->SetFont('Arial', '', 11);
							$pdf->Text(200, 600, $copyRightText);
						   $posX=(792-$posW)/2;
						   $posY=20;
						    $pdf->Image($logoFullPath, $posX, $posY, $posW, $posH);
		                    foreach ($images as $image) 
		                    {
		                    	//echo $image;
								//exit;
								$posX=$pdfCounter*90;
								$posY=220;
		                    	//list($posW, $posH) = getimagesize($image);
								$posW=147;
								$posH=274;
								
		                        $pdf->Image($image, $posX, $posY, $posW, $posH);
								$pdfCounter++;	
									
		                       
		                    }
							
								$pdf->SetTextColor(0, 0, 0);
							  $pdf->SetFont('Arial', '', 11);
							  $x1=100;
							  $y1=100;
							  $pdf->Text($x1, $y1, 'Customer:');
							  $x1=$x1+150;
							  $pdf->Text($x1, $y1, $customerName);
							  $x1=100;
							  $y1=$y1+30;
							  $pdf->Text($x1, $y1, 'Order#');
							  $x1=$x1+150;
							  $pdf->Text($x1, $y1, $orderId);
							  $x1=100;
							  $y1=$y1+30;
							  $pdf->Text($x1, $y1, 'DesignCode:');
							  $x1=$x1+150;
							  $pdf->Text($x1, $y1, $designCode);
							  
							$x = 50;
	                        $y = 520;
	                        $pdf->SetTextColor(0, 0, 0);
							$pdf->SetFont('Arial', '', 15);
							//echo "font";
							$pdf->Text($x, $y, 'Id');
	                        $x = $x + 230;
	                        $pdf->Text($x, $y, 'Area');
	                        $x = $x + 230;
	                        $pdf->Text($x, $y, 'Price');
	                        $pdf->SetLineWidth(3);
                    	    $pdf->SetDrawColor(221, 221, 221);
                    	    $pdf->Line(40, 530, 740, 530);
						    $pdf->SetTextColor(0, 0, 0);
	                        $pdf->SetFont('Arial', '', 11);
	                        $x = 50;
	                        $y = 550;
							 foreach ($priceInfoArr as $priceInfoArrValue) 
                        	{
                        	$priceInfoArrValue=(object) $priceInfoArrValue;  
							                      	
							//echo $nameNumValue->size."newline";
							//echo $priceInfoArrValue->colorId;
							//if($priceInfoArrValue->colorId)
							//{
								//echo $priceInfoArrValue->partId;
								//$pdf->SetFont('Arial', '', 11);
								//echo '<pre>';print_r($priceInfoArrValue);exit;
								if($priceInfoArrValue->partId)
								$pdf->Text($x, $y, $priceInfoArrValue->partId);
								
								
                            	$x = $x + 230;
								//Area
								if($priceInfoArrValue->partName)
								$pdf->Text($x, $y, $priceInfoArrValue->partName);
								
                            	$x = $x + 230;
								//parts
                               	if($priceInfoArrValue->partPrice)
								$pdf->Text($x, $y, $priceInfoArrValue->partPrice);
								
								
								
                                $x = 50;
                                $y = $y + 30;
								if($y>($pageCreator-20))
								{
									//$pageCreator=$pageCreator+612;
									 $y=100;
									 $pdf->AddPage(); 
									 $pdf->SetFont('Arial', '', 11);
									$pdf->Text(200, 600, $copyRightText);
								}
                            
                            
                            //}
                            }
							$zipArr[] = $dirName."/".$pdfFileName;						
			            	$pdf->Output($dirName."/".$pdfFileName);
						}
									
						
					
				}
                }
                $fileName = Mage::getBaseDir('media') . "/inky/pdf/" . $orderId . ".zip";
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
	
	// Get Title field in Different Stores
	public function getTranslation($priceInfoArrValue, $storeId,$partIden)
	{
					if($partIden=="partStyleId")
					{
						if(isset($priceInfoArrValue->partStyleId) && !empty($priceInfoArrValue->partStyleId) && $priceInfoArrValue->partStyleId > 0)
						{
							$partsStyleId = $priceInfoArrValue->partStyleId;
							$value = Mage::helper('designersoftware/store_parts_style')->getValue($partsStyleId, $storeId);
							//echo $value['title'];
							return $value['title'];
						}
					}
					else if($partIden=="layerId")
					{
						if(isset($priceInfoArrValue->layerId) && !empty($priceInfoArrValue->layerId) && $priceInfoArrValue->layerId > 0)
						{
							$partsLayersId = $priceInfoArrValue->layerId;
							$value = Mage::helper('designersoftware/store_parts_layers')->getValue($partsLayersId, $storeId);
							return $value['title'];
						}
					}
					else if($partIden=="colorId")
					{
						if(isset($priceInfoArrValue->colorId) && !empty($priceInfoArrValue->colorId) && $priceInfoArrValue->colorId > 0)
						{
							$colorId = $priceInfoArrValue->colorId;
							$value = Mage::helper('designersoftware/store_color')->getValue($colorId, $storeId);
							return $value['title'];
						}
					}
					else if($partIden=="optionId")
					{
						if(isset($priceInfoArrValue->optionId) && !empty($priceInfoArrValue->optionId) && $priceInfoArrValue->optionId > 0)
						{
							$sizesId = $priceInfoArrValue->optionId;
							$value = Mage::getModel('designersoftware/sizes')->getCollection()->addFieldToSelect('title')->addFieldToFilter('sizes_id',$sizesId)->getFirstItem()->getData();			
							return $value['title'];
						}	
					}
					else if($partIden=="clipartId")
					{
						if(isset($priceInfoArrValue->clipartId) && !empty($priceInfoArrValue->clipartId) && $priceInfoArrValue->clipartId > 0)
						{
							$clipartId = $priceInfoArrValue->clipartId;
							$value = Mage::helper('designersoftware/store_clipart')->getValue($clipartId, $storeId);				
							return $value['title'];
						}
					}
			
		
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
//echo $_GET['flag'];
if ($_GET['flag']) {	
	$GenPDFobj->generatePDF($_GET['orderId'], $_GET['url']);
} else {
    $GenPDFobj->deletePDF($_GET['orderId'], $_GET['url']);
}
//$GenPDFobj->generatePDF(8, '');
?>
