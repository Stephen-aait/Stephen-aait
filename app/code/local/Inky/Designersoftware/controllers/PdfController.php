<?php
class Inky_Designersoftware_PdfController extends Mage_Core_Controller_Front_Action
{    
    private function __getImageMagickPath(){				
		
		$imageMagickPath = Mage::getModel('core/variable')->loadByCode('IMAGE_MAGICK_PATH')->getValue('plain');			
		//exit;
		return $imageMagickPath;		
		
	}
	
	public function testAction(){
		echo "Testing Image Magic Chinese :";
		$mediaPath = Mage::getBaseDir('media') . DS . "inky" . DS ;
		/*exec('/usr/bin/convert 
				-pointsize 12 -background none -fill "#000000" 
				-font "/var/www/html/jbcases/media/inky/font/ttf/font_chinese.ttf" 
				-gravity west label:"范围" 
				-trim /var/www/html/jbcases/media/inky/pdf/designimage/area.png');	
		*/
		
		//exec("/usr/bin/convert -size 100x100 xc:white /var/www/html/jbcases/media/canvas.jpg");
		
		//exec('/usr/bin/convert -pointsize 16 -background none -fill "#000000" -font "/var/www/html/jbcases/media/inky/font/ttf/font_chinese.ttf" -gravity west label:"范围" -trim /var/www/html/jbcases/media/inky/pdf/designimage/area.png');	
		
		exec('/usr/bin/convert -background lightblue -fill blue -font Candice -pointsize 72 label:Amit '. $mediaPath . 'label.gif');
		
	
	}
	   
    public function generateAction(){
		// Image Magick Path	
		$imageMagickPath 	= $this->__getImageMagickPath();
		
		// Data to be posted 
		$data 				= $this->getRequest()->getParams();
		//echo '<pre>';print_r($data);exit;	
		
		//$styleDesignCode	= '55e6-6a3d-a4bc';//$data['styleDesignCode'];
		//$languageId 		= '2';//$data['lang'];
		$styleDesignCode	= $data['designCode'];
		$languageId 		= $data['storeId'];
		$orderId			= $data['orderId'];
		
		if(!empty($orderId) && $orderId>0){
			// Design Collection
			$designCollection = Mage::getModel('designersoftware/orders_design')->getCollection()
											->addFieldToFilter('order_id',$orderId)
											->addFieldToFilter('style_design_code',$styleDesignCode)
											->addFieldToFilter('status',1)											
											->getFirstItem();
			$designId  			= $designCollection->getDesignersoftwareId();
			//Increment ID
			$order = Mage::getModel('sales/order')->load($orderId);
			$increment_id = $order->getData('increment_id');
			$created_at	= $order->getData('created_at');
			
			
		} else {		
			// Design Collection
			$designCollection 	= Mage::getModel('designersoftware/designersoftware')->getCollectionByCode($styleDesignCode);
			$designId  			= $designCollection->getId();
		}
		
		Mage::register('designCollection',$designCollection);	
		//echo '<pre>';print_r($designCollection->getData());exit;	
		$designId  			= $designCollection->getId();				
		$productId 			= $designCollection->getProductId();		 
		$priceInfoArr 		= unserialize($designCollection->getPriceInfoArr());
		$chineseInfoArr 	= unserialize($designCollection->getChineseInfoArr()); 
		$customerId		 	= $designCollection->getCustomerId();					
		$totalPrice 		= Mage::helper('core')->currency($designCollection->getTotalPrice(), true, false);
		
		// Design's Customer Name, who has design it 		
		$customerName 		= Mage::helper('designersoftware/customer')->getCustomerNameById($customerId);
		
		// Path of logo 
		$logo 				= Mage::getStoreConfig('design/header/logo_src');
		$logoFullPath 		= Mage::getBaseDir('skin'). DS . "frontend" . DS . "rwd" . DS . "inky1003" . DS . $logo;
		
		// Designed Images 
		$images 			= $this->designImages($styleDesignCode, $orderId);		
		
		$pageCreator=612;
		Mage::register('pageCreator',$pageCreator);
			
		$printWidth=792;
		$printHeight=612;
		
		$size		= array($printWidth, $printHeight);
		
		$pdf		= new Inky_Pdf("P", "pt", $size);		
		
		// Register PDF Data Class to Cache
		Mage::register('pdf',$pdf);		
		
		$pdf 		= Mage::registry('pdf');		
		$pdf->AddPage();				
		
		$pdfFileName = $designId ."_".$customerName."_Workshop.pdf";
		$pdfCounter=0;
		list($posW, $posH) = getimagesize($logoFullPath);

		$posX=(792-$posW)/2;
		$posY=20;
		$pdf->Image($logoFullPath, $posX, $posY, $posW, $posH);
		foreach ($images as $image) 
		{
			$posX=$pdfCounter*107;
			$posY=220;
			$posW=147;
			$posH=274;
			
			$pdf->Image($image, $posX, $posY, $posW, $posH);
			$pdfCounter++;			   
		}
			
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', '', 11);
		if(!empty($customerName) && $customerId>0):
			$x1=100;
			$y1=100;
			$pdf->Text($x1, $y1, 'Customer:');
			$x1=$x1+150;
			$pdf->Text($x1, $y1, $customerName);
		else:
			$x1=100;
			$y1=100;
			$pdf->Text($x1, $y1, 'Customer:');
			$x1=$x1+150;
			$pdf->Text($x1, $y1, 'Guest');
		endif;
		
		if(!empty($orderId)):
			$x1=100;
			$y1=$y1+30;
			$pdf->Text($x1, $y1, 'Order#');
			$x1=$x1+150;
			$pdf->Text($x1, $y1, $increment_id);
			
			$x1=100;
			$y1=$y1+30;
			$pdf->Text($x1, $y1, 'Purchase Date:');
			$x1=$x1+150;
			$pdf->Text($x1, $y1, $created_at);
		endif;
		
		if(!empty($styleDesignCode)):
			$x1=100;
			$y1=$y1+30;
			$pdf->Text($x1, $y1, $this->__('Design Code:'));
			$x1=$x1+150;
			$pdf->Text($x1, $y1, $styleDesignCode);
		endif;
		
		if(!empty($totalPrice) && $languageId==1):
			$x1=100;
			$y1=$y1+30;
			$pdf->Text($x1, $y1, $this->__('Design Quote:'));
			$x1=$x1+150;
			$pdf->Text($x1, $y1, $totalPrice);
		endif;
		
			
		if(count($chineseInfoArr)>0 && $languageId==2){
			// Chinese (Other) Language Mix Info for PDF
			$this->otherLanguagePdf($chineseInfoArr);				
		} 
										
		if(count($priceInfoArr)>0 && $languageId==1){
			// Chinese (Other) Language Mix Info for PDF
			$this->englishPdf($priceInfoArr);
		}
		
	}
	
	public function englishPdf($priceInfoArr){
		$copyRightText 			= Mage::getStoreConfig('designerfront/footer/copyright');
		$pdf 		= Mage::registry('pdf');		
		
		$pdf->SetFont('Arial', '', 11); 
		$pdf->Text(200, 600, $copyRightText);
		
		$x = 50;
		$y = 520;
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', '', 15);		
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
			$y = $y + 50;
			$pageCreator = Mage::registry('pageCreator');
			if($y>($pageCreator-20))
			{
				//$pageCreator=$pageCreator+612;
				 $y=100;
				 $pdf->AddPage(); 
				 $pdf->Text(200, 600, $copyRightText);
			}		
		}									
		$pdf->Output($dirName."/".$pdfFileName, 'I');
	}
	
	public function otherLanguagePdf($chineseInfoArr){					
		//echo '<pre>';print_r($chineseInfoArr);exit;
		$copyRightText 		= Mage::getStoreConfig('designerfront/footer/copyright');
		$imageMagickPath 	= $this->__getImageMagickPath();
		$pdf 				= Mage::registry('pdf');
		
		$pdf->SetFont('Arial', '', 11); 
		$pdf->Text(200, 600, $copyRightText);
		
		$designDirName 	= Mage::getBaseDir('media') . DS . "inky" . DS . "pdf"  . DS . "designimage";
		$fontDirName 	= Mage::getBaseDir('media') . DS . "inky" . DS . "font" . DS . "ttf";
		
		$this->createDirectory($designDirName); // Create Second Level Directory
			
		$x = 50;
		$y = 520;
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', '', 15);		
		$pdf->Text($x, $y, 'Id');
		
		$x = $x + 30;
		$pdf->Text($x, $y, 'Area');
		
		$x = $x + 100;
		$pdf->Text($x, $y, 'Part');
		
		$x = $x + 150;
		$pdf->AddGBFont();		
		$pdf->SetFont('GB', '', 20);		
		$rand = $this->getHeaderImage($designDirName,'area',"范围");		
		list($posW, $posH) = getimagesize($rand);
		$pdf->Image($rand, $x, $y-10, $posW, $posH);
				
		$x = $x + 100;		
		$rand = $this->getHeaderImage($designDirName,'part',"部分");				
		list($posW, $posH) = getimagesize($rand);
		$pdf->Image($rand, $x, $y-10, $posW, $posH);
		
		$x = $x + 140;
		$pdf->SetFont('Arial', '', 15);
		$pdf->Text($x, $y, 'Color');
		
		$x = $x + 100;
		$pdf->SetFont('GB', '', 15);				
		$rand = $this->getHeaderImage($designDirName,'color',"颜色");						
		list($posW, $posH) = getimagesize($rand);
		$pdf->Image($rand, $x, $y-10, $posW, $posH);
		
		$pdf->SetLineWidth(3);
		$pdf->SetDrawColor(221, 221, 221);
		$pdf->Line(40, 530, 740, 530);
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', '', 11);
		$x = 50;
		$y = 550;
		foreach ($chineseInfoArr as $priceInfoArrValue){
			$priceInfoArrValue=(object) $priceInfoArrValue;                        						
			if($priceInfoArrValue->colorId){
				
				$pdf->SetFont('Arial', '', 11);
				
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
				$x = $x + 100;
				
				//parts
				if($priceInfoArrValue->layerId)
				$pdf->Text($x, $y, $this->getTranslation($priceInfoArrValue,'1',"layerId"));
				else if($priceInfoArrValue->clipartId)
				$pdf->Text($x, $y, $this->getTranslation($priceInfoArrValue,'1',"clipartId"));
				else if($priceInfoArrValue->optionId)
				$pdf->Text($x, $y, $this->getTranslation($priceInfoArrValue,'1',"optionId"));
				$x = $x + 150;

				$pdf->SetFont('GB', '', 15);
				
				//area
				if($priceInfoArrValue->layerId)
				{				
					$rand = $this->getFontImage($designDirName, $priceInfoArrValue, '2', "partStyleId");				
					if(file_exists($rand)):
						list($posW, $posH) = getimagesize($rand);					
						$pdf->Image($rand, $x, $y-10, $posW, $posH);					
					endif;
				} else if($priceInfoArrValue->clipartId){
					$pdf->SetFont('Arial', '', 11);
					$pdf->Text($x, $y, $this->getTranslation($priceInfoArrValue,'2',"clipartId"));
				} else if($priceInfoArrValue->optionId){
					$pdf->SetFont('Arial', '', 11);
					$pdf->Text($x, $y, $this->getTranslation($priceInfoArrValue,'2',"optionId"));
				} else if($priceInfoArrValue->text){
					$pdf->SetFont('Arial', '', 11);
					$pdf->Text($x, $y, $priceInfoArrValue->text);
				}
				
				$x = $x + 100;

				$pdf->SetFont('GB', '', 15);
				if($priceInfoArrValue->layerId)
				{				
					$rand = $this->getFontImage($designDirName, $priceInfoArrValue, '2', "layerId");									
					if(file_exists($rand)):
						list($posW, $posH) = getimagesize($rand);
						$pdf->Image($rand, $x, $y-10, $posW, $posH);					
					endif;
				} else if($priceInfoArrValue->clipartId) {
					$pdf->SetFont('Arial', '', 11);
					$pdf->Text($x, $y, $this->getTranslation($priceInfoArrValue,'2',"clipartId"));
				} else if($priceInfoArrValue->optionId) {
					$pdf->SetFont('Arial', '', 11);
					$pdf->Text($x, $y, $this->getTranslation($priceInfoArrValue,'2',"optionId"));
				}

				$x = $x + 140;
				$pdf->SetFont('Arial', '', 11);
				
				if($priceInfoArrValue->colorId)
					$pdf->Text($x, $y, $this->getTranslation($priceInfoArrValue,'1',"colorId"));
				
				$x = $x + 100;
				$pdf->SetFont('GB', '', 15);
				if($priceInfoArrValue->colorId)
				{
					$rand = $this->getFontImage($designDirName, $priceInfoArrValue, '2', "colorId");									
					if(file_exists($rand)):
						list($posW, $posH) = getimagesize($rand);
						$pdf->Image($rand, $x, $y-10, $posW, $posH);					
					endif;
				}

				$x = 50;
				$y = $y + 50;
				$pageCreator = Mage::registry('pageCreator');
				if($y>($pageCreator-20))
				{
					//$pageCreator=$pageCreator+612;
					 $y=100;
					 $pdf->AddPage();
					 $pdf->SetFont('Arial', '', 11); 
					 $pdf->Text(200, 600, $copyRightText);
				}
			}
		}									
		$pdf->Output($dirName."/".$pdfFileName, 'I');
	}
	
	
	public function designImages($styleDesignCode, $orderId=0){
		// Design Angles Collection
		$anglesCollection 	= Mage::getModel('designersoftware/angles')->getAnglesCollection();														
		
		foreach($anglesCollection as $angles):
			if($orderId>0){
				$images[] = Mage::getBaseDir('media') . DS . "inky" . DS . "orders" . DS . $styleDesignCode . DS . "original" . DS . $angles->getTitle().'.png';
			} else {
				$images[] = Mage::getBaseDir('media') . DS . "inky" . DS . "designs" . DS . $styleDesignCode . DS . "original" . DS . $angles->getTitle().'.png';
			}
		endforeach;
		
		return $images;
	} 
	
	function createDirectory($dirName) {
        if (!is_dir($dirName)) {
            @mkdir($dirName);
            @chmod($dirName, 0777);
        }
    } 
    
    public function getHeaderImage($basePath, $title, $titleInOtherLang){
		$imageMagickPath= $this->__getImageMagickPath();
		$fontDirName 	= Mage::getBaseDir('media') . DS . "inky" . DS . "font" . DS . "ttf";
		
		$id 			= $priceInfoArrValue->$partIden;
		$color			= "#000000";
		$font			= $fontDirName . DS . "font_chinese.ttf";
		$align			= "west";
		$text_val		= $titleInOtherLang;
				
		$imageFilePath 	= $basePath . DS . $title .'.png';
		
		if(file_exists($imageFilePath)):					
			unlink($imageFilePath);		
		endif;
		
		//echo $imageMagickPath.' -pointsize 16 -background none -fill "'.$color.'" -font "'.$font.'" -gravity '.$align.'   label:"'.$text_val.'" -trim '. $imageFilePath;exit;
		exec($imageMagickPath.' -pointsize 16 -background none -fill "'.$color.'" -font "'.$font.'" -gravity '.$align.'   label:"'.$text_val.'" -trim '. $imageFilePath);
		
		return $imageFilePath;
	}
    
    // Return Image Path of a Language
    public function getFontImage($basePath, $priceInfoArrValue, $storeId, $partIden){
		$imageMagickPath= $this->__getImageMagickPath();
		$fontDirName 	= Mage::getBaseDir('media') . DS . "inky" . DS . "font" . DS . "ttf";
		
		$id 			= $priceInfoArrValue->$partIden;
		$color			= "#000000";
		$font			= $fontDirName . DS . "font_chinese.ttf";
		$align			= "west";
		$text_val		= $this->getTranslation($priceInfoArrValue, $storeId, $partIden);
		
		if(!empty($partIden)):
			$imagePath 		= $basePath . DS . $partIden;
			$this->createDirectory($imagePath);		
		endif;
		
		$imageFilePath 	= $imagePath . DS . $id .'.png';
		//if($partIden=='layerId' && $id==36):
			//echo $storeId;exit;
			//echo $imageMagickPath.' -pointsize 16 -background none -fill "'.$color.'" -font "'.$font.'" -gravity '.$align.'   label:"'.$text_val.'" -trim '. $imageFilePath;exit;
		//endif;
		if(file_exists($imageFilePath)):					
			unlink($imageFilePath);		
		endif;
		
		exec($imageMagickPath.' -pointsize 16 -background none -fill "'.$color.'" -font "'.$font.'" -gravity '.$align.'   label:"'.$text_val.'" -trim '. $imageFilePath);
		
		return $imageFilePath;
		
	}	
    
    // Get Title field in Different Stores
	public function getTranslation($priceInfoArrValue, $storeId, $partIden)
	{
		if($partIden=="partStyleId")
		{
			if(isset($priceInfoArrValue->partStyleId) && !empty($priceInfoArrValue->partStyleId) && $priceInfoArrValue->partStyleId > 0)
			{
				$partsStyleId = $priceInfoArrValue->partStyleId;
				$value = Mage::helper('designersoftware/store_parts_style')->getValue($partsStyleId, $storeId);				
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
}
