<?php
class Inky_Designersoftware_CustomerController extends Mage_Core_Controller_Front_Action
{
   /**
     * Output Directory name
     * @var type 
     */
    var $dirName;
   
    /**
     *
     * @var type Name of current designe pdf
     */
    var $pdfFileName;	
    
    public function createAction(){		
		$styleDesignCode = $this->getRequest()->getParam('code');
		 
		//$styleDesignCode = '55ac-ce32-2e79';
		$designCollection = Mage::getModel('designersoftware/designersoftware')->getCollectionByCode($styleDesignCode);
		
		// All Angles Name 
		$anglesCollection = Mage::getModel('designersoftware/angles')->getAnglesCollection();
		
		$designId 			= $designCollection->getId();
		$designCode			= $designCollection->getStyleDesignCode();
		$productId 			= $designCollection->getProductId();		
		$priceInfoArr 		= unserialize($designCollection->getPriceInfoArr()); 
		$logo 				= Mage::getStoreConfig('design/header/logo_src');		
		$logoFullPath 		= Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN) . DS . "frontend" . DS . "rwd" . DS . "inky1003" . DS . $logo;
		
		//Customer Name
		$customerId 		= $designCollection->getCustomerId();
		$customerCollection = Mage::getModel('customer/customer')->load($customerId);
		$customerName 		= $customerCollection->getName();
		
		$dirName = Mage::getBaseDir('media') . "/inky/customer";
		Mage::helper('designersoftware/customer_pdf')->createDirectory($dirName); // Create First Level Directory	
		$dirName = Mage::getBaseDir('media') . "/inky/customer/pdf";
		Mage::helper('designersoftware/customer_pdf')->createDirectory($dirName); // Create second Level Directory			
		
		$counterV = 1;
		$myCon=1;			
		
		foreach($anglesCollection as $angles):
			$images[] = Mage::getBaseDir('media')."/inky/designs/".$styleDesignCode."/original/".$angles->getTitle().".png";
		endforeach;		
		
		$pageCreator	= 612;	
		$printWidth		= 792;
		$printHeight	= 612;
		$size			= array($printWidth, $printHeight);
		$pdf			= new Inky_Pdf("P", "pt", $size);
		$pdf->AddPage(); 
		//$pdfFileName =$myCon. "_.pdf";
		$pdfFileName = $designId . "_".$customerName.".pdf";
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
			$posX=$pdfCounter*107;
			$posY=220;
			//list($posW, $posH) = getimagesize($image);
			$posW=147;
			$posH=274;
			
			$pdf->Image($image, $posX, $posY, $posW, $posH);
			$pdfCounter++;	
				
		   
		}
			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 11);
			
			if(!empty($customerName)):
				$x1=100;
				$y1=100;
				$pdf->Text($x1, $y1, 'Customer:');
				$x1=$x1+150;
				$pdf->Text($x1, $y1, $customerName);
			endif;
			/*$x1=100;
			$y1=$y1+30;
			$pdf->Text($x1, $y1, 'Order#');
			$x1=$x1+150;
			$pdf->Text($x1, $y1, $orderId);*/
			if(!empty($customerName)):
				$x1=100;
				$y1=$y1+30;
				$pdf->Text($x1, $y1, 'DesignCode:');
				$x1=$x1+150;
				$pdf->Text($x1, $y1, $designCode);
			endif;

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
				$y = $y + 50;
				if($y>$pageCreator)
				{
					//$pageCreator=$pageCreator+612;
					 $y=30;
					 $pdf->AddPage(); 
				}


				//}
			}
									
			$pdf->Output($dirName."/".$pdfFileName, 'I');			
		
	}
	
	public function testAction(){
		
		$styleDesignCode = $this->getRequest()->getParam('code');
		 
		//$styleDesignCode = '55ac-ce32-2e79';
		$designCollection = Mage::getModel('designersoftware/designersoftware')->getCollectionByCode($styleDesignCode);
		
		// All Angles Name 
		$anglesCollection = Mage::getModel('designersoftware/angles')->getAnglesCollection();
		
		$productId 		= $designCollection->getProductId();  
		$priceInfoArr 	= unserialize($designCollection->getPriceInfoArr());  
		
		$dirName = Mage::getBaseDir('media') . "/inky/customer";
		Mage::helper('designersoftware/customer_pdf')->createDirectory($dirName); // Create First Level Directory	
		$dirName = Mage::getBaseDir('media') . "/inky/customer/pdf";
		Mage::helper('designersoftware/customer_pdf')->createDirectory($dirName); // Create second Level Directory			
		
		$counterV = 1;
		$myCon=1;			
		
		foreach($anglesCollection as $angles):
			$images[] = Mage::getBaseDir('media')."/inky/designs/".$styleDesignCode."/original/".$angles->getTitle().".png";
		endforeach;	
		
		
		$printWidth=1900;
		if(count($priceInfoArr)>0):
			$printHeight=500+count($priceInfoArr)*80;
		else:
			$printHeight=500;
		endif;
		
		$size		= array($printWidth, $printHeight);
		$pdf 		= new Inky_Pdf("P", "pt", $size);
		$pdf->AddPage(); 		
		
		$pdfFileName = $styleDesignCode . "_.pdf";
		$pdfCounter=0;
		foreach ($images as $image) 
		{
			//echo $image;
			//exit;
			$posX=$pdfCounter*235;
			$posY=20;
			list($posW, $posH) = getimagesize($image);
			//echo $posW;
			$pdf->Image($image, $posX, $posY, $posW, $posH);
			$pdfCounter++;	   
		}
		
		if(count($priceInfoArr)>0)
		{
			$x = 100;
			$y = 600;
			$pdf->SetTextColor(131, 129, 129);
			$pdf->SetFont('Arial', '', 30);
			//echo "font";
			$pdf->Text($x, $y, 'Name');
			$x = $x + 260;
			$pdf->Text($x, $y, 'Price');
			$x = $x + 170;
			
			$pdf->SetLineWidth(6);
			$pdf->SetDrawColor(221, 221, 221);
			$pdf->Line(40, 560, 500, 560);
			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 16);
			$x = 110;
			$y = 650;
			foreach ($priceInfoArr as $priceInfoArrValue) 
			{
				$priceInfoArrValue=(object) $priceInfoArrValue;
				//echo $nameNumValue->size."newline";
				if($priceInfoArrValue->partName)
				{
					$pdf->Text($x, $y, $priceInfoArrValue->partName);
					$x = $x + 270;
					$pdf->Text($x, $y, '$'. ' '.$priceInfoArrValue->partPrice);
					$x = 110;
					$y = $y + 50;
				}
			}
			//$pdf->Output($dirName."/".$pdfFileName, 'F');			
			$pdf->Output($dirName."/".$pdfFileName, 'I');			
		}
	}
	    
    public function indexAction(){
		if($data = $this->getRequest()->getPost()){
			if($data['userInfo']['customerType'] == 'login'):
				$this->loginCustomer($data['userInfo']);
			else:
				$this->registerCustomer($data['userInfo']);
			endif;			
		}
	}
	
    public function loginCustomer($data){		
		if($data){
						
			//echo '<pre>fsdf==>>';print_r($data);exit;
			$email = trim($data['email']);
			$password = $data['password'];
			$objLogin = (object) $objLogin;
			
			$customer = Mage::getModel('customer/customer')->setWebsiteId(Mage::app()->getStore()->getWebsiteId())->loadByEmail($email);
			
			$hash_in_db = $customer->getPasswordHash();
			
			$a = explode(':', $hash_in_db);
			$hash = $a[0];
			$salt = $a[1];

			$computed_password = md5($salt . $password);

			if ($computed_password === $hash) {
				Mage::getSingleton('customer/session')->loginById($customer->getId());
				$objLogin->login = 1;
				$objLogin->customerId = $customer->getId();
				$objLogin->name = $customer->getName();
				$objLogin->message = $this->__('Customer Logged In Successfully.');
			} else {
				$objLogin->login = 0;
				$objLogin->customerId = 0;				
				$objLogin->message = $this->__('Invalid login or password.');
			}
			echo json_encode($objLogin);
		}
	}
	
	public function registerCustomer($data){		
		if($data){
						
			$objRegister = (object) $objRegister;
			
			$customer_email = trim($data['email']);
			$customer_fname = trim($data['firstName']);
			$customer_lname = trim($data['lastName']);
			//$customer_contact = trim($data['cNumber']);
			//$customer_zip = trim($data['zipcode']);
			//$customer_country = trim($data['country']);
			$password = $data['password'];
			//$comment = $data['commentText'];
			
			$customer = Mage::getModel('customer/customer')->setWebsiteId(Mage::app()->getStore()->getWebsiteId())->loadByEmail($customer_email);

			if (!$customer->getId() && !empty($customer_email)):
				$customer->setEmail($customer_email);
				$customer->setFirstname($customer_fname);
				$customer->setLastname($customer_lname);
				$customer->setPassword($password);
				//$customer->setTelephone($customer_contact);
				//$customer->setPostcode($customer_zip);
				$customer->save();
				$customer->setConfirmation(null);
				$customer->save();
				$customer->sendNewAccountEmail();

				Mage::getSingleton('customer/session')->loginById($customer->getId());
				$objRegister->login 		= 1;
				$objRegister->customerId 	= $customer->getId();
				$objRegister->name 			= $customer->getName();
				$objRegister->message 		= $this->__('Customer Registered Successfully.');
			else:
				$objRegister->login 		= 0;
				$objRegister->customerId 	= 0;				
				$objRegister->message 		= $this->__('Email id  is already exist.');
			endif;

			echo json_encode($objRegister);
		}
	}
}
