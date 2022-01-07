<?php

class Inky_Designersoftware_Helper_Save_Json extends Mage_Core_Helper_Abstract
{		
	const DESIGNERSOFTWRE_MODE_EDIT 			= 'edit';
	const DESIGNERSOFTWRE_MODE_TEMPLATE 		= 'template';
	
	const DESIGNERSOFTWRE_ADD_TO_CART 			= 'addToCart';
	const DESIGNERSOFTWRE_SAVE_DESIGN 			= 'save';
	
	const DESIGNERSOFTWRE_USER_LOGIN_CUSTOMER 	= 'customer';
	const DESIGNERSOFTWRE_USER_LOGIN_ADMIN 		= 'admin';
	
	const DESIGNERSOFTWRE_STATUS_ENABLE 		= 1;
	const DESIGNERSOFTWRE_STATUS_DISABLE 		= 2;	
	
	public function load($data){										
		// Set Designed Data information to Database
		try 
		{
			// Designer Product ID
			$designerProductId = Mage::getModel('core/variable')->loadByCode('DESIGNER_TOOL_PRODUCT_ID')->getValue('plain');
				
			if($data['eventType']==self::DESIGNERSOFTWRE_ADD_TO_CART):
			
				// Generate Unique code for Designs  
				$code = $this->designCode();
															
				if($data['mode']!=self::DESIGNERSOFTWRE_MODE_EDIT):
					$data['styleDesignCode'] = $code;				
				endif;
				
				// Save Designersoftware Data to Database
				$data['sessionId'] = Mage::helper('designersoftware/customer')->getCurrentSessionId();
				$data['customerId'] = Mage::helper('designersoftware/customer')->getCurrentCustomerId();				
				
				// Now Designer Data is saved in Database
				$designerData 	= $this->setDesignerData($data);				
				$customerId 	= $designerData['customer_id'];
				$productId 		= $designerData['product_id'];
									
				//echo '<pre>';print_r($designerData);exit;
				Mage::register('current_design', $designerData);
				Mage::register('current_design_code', $designerData['style_design_code']);				
								
				//Redirect Customer to Wishlist Tab (Mydesign) Module
				if($data['customerId']>0):
					$customOptionsArray = Mage::helper('designersoftware/product_customoption')->sendCustomOptionArray($designerData['product_id'], $designerData['style_design_code']);							
					// Add to Wishlist as save Design
					$this->addProductToWishlist($customerId, $productId, $customOptionsArray, $quantity=1);
				else:
					$session = Mage::getSingleton("designersoftware/session");										
					foreach($session->getDesignData() as $key=>$value):
						$sessionData[$key] = $value;
					endforeach;																		
					$sessionData[$designerData['designId']] = $designerData;							
					$session->setDesignData($sessionData);
				endif;				
				
				//Generate Custom option for Magento Designer Product 
				//Add a new Design as an Option to make Product Unique in Shopping Cart
				$customOptionsUrl = Mage::helper('designersoftware/product_customoption')->sendCustomOptionUrl($designerData['product_id'], $designerData['style_design_code']);				
				
				// Load Designersoftware predefined Magento Product which will used for mapping with new Designs
				$_product = Mage::getModel('catalog/product')->load($designerData['product_id']);
				// Generate Cart Url
				$cartUrl=Mage::helper('checkout/cart')->getAddUrl($_product).'?qty=1&designId='.$designerData['designId'] . $customOptionsUrl;
				
				
				$returnData['eventType']		= self::DESIGNERSOFTWRE_ADD_TO_CART;				
				$returnData['sessionId']	= Mage::helper('designersoftware/customer')->getCurrentSessionId();
				$returnData['customerId']	= Mage::helper('designersoftware/customer')->getCurrentCustomerId();				
				$returnData['url']			= $cartUrl;			
			else:				
				// Generate Unique code for Designs  
				//Mage::log($data,null,'designersoftware.log');			
				$code = $this->designCode();
															
				if($data['mode']!=self::DESIGNERSOFTWRE_MODE_EDIT):
					$data['styleDesignCode'] = $code;				
				endif;
				
				// Save Designersoftware Data to Database
				$data['sessionId'] 	= Mage::helper('designersoftware/customer')->getCurrentSessionId();
				$data['customerId'] = Mage::helper('designersoftware/customer')->getCurrentCustomerId();
				
				// Now Designer Data is saved in Database
				$designerData = $this->setDesignerData($data);				
				$customerId = $designerData['customer_id'];
				$productId = $designerData['product_id'];
				
				//Generate Custom option for Magento Designer Product 
				//Add a new Design as an Option to make Product Unique in Shopping Cart				
				//echo '<pre>';print_r($customOptionsArray);exit;				
				
				//Redirect Customer to Wishlist Tab (Mydesign) Module
				if($data['customerId']>0):
					$customOptionsArray = Mage::helper('designersoftware/product_customoption')->sendCustomOptionArray($designerData['product_id'], $designerData['style_design_code']);							
					// Add to Wishlist as save Design
					$this->addProductToWishlist($customerId, $productId, $customOptionsArray, $quantity=1);
				else:
					$session = Mage::getSingleton("designersoftware/session");										
					foreach($session->getDesignData() as $key=>$value):
						$sessionData[$key] = $value;
					endforeach;																		
					$sessionData[$designerData['designId']] = $designerData;							
					$session->setDesignData($sessionData);
				endif;
					
					//$url = Mage::getBaseUrl() . 'savedesign';
					$url = $this->getProductDetailUrl($designerData['designId']);
				//else:
					//$url = Mage::getBaseUrl() . 'customer/account';
					//$url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB).'/checkout/cart/add?product='.$entity_id.'&qty='.$quantity.$customOptionsUrl;								
				//endif;
												
				$returnData['eventType']		= self::DESIGNERSOFTWRE_SAVE_DESIGN;	
				$returnData['sessionId']		= Mage::helper('designersoftware/customer')->getCurrentSessionId();
				$returnData['designId'] 		= $designerData['designId'];
				$returnData['styleDesignCode'] 	= $designerData['style_design_code'];
				$returnData['encUrlCode']		= $this->getDesignerProductParams($designerData['designId']);
				$returnData['customerId']		= Mage::helper('designersoftware/customer')->getCurrentCustomerId();	
				$returnData['url'] 				= $url;				
				$returnData['status'] 			= 'saved';				
			endif;			
						
			return $returnData;
		} 
		catch (Exception $e) 
		{
			 Mage::logException($e);
             return false;
		}		
	}
	  
	public function setDesignerData($data){
		// Designer Product ID
		$designerProductId = Mage::getModel('core/variable')->loadByCode('DESIGNER_TOOL_PRODUCT_ID')->getValue('plain');
		
		//Mage::log($data,null,'designersoftware.log');
		//$this->createDesignMagentoThumb($data);//exit;
		$this->createDesignImages($data);
		
		$model = Mage::getModel('designersoftware/designersoftware');		
				
		// General Data
		$designerData['designId']				= $data['designId']; 	// This is an virtual variable will not set in Model/ Database
		$designerData['customer_id']			= $data['customerId'];
		$designerData['session_id']				= $data['sessionId'];
		//$designerData['title']				= Mage::getModel('designersoftware/style')->getTitleByStyleDesignCode($data['styleDesignCode']);
		$designerData['style_design_code'] 		= $data['styleDesignCode'];
		
		
		// Price Data							
		$designerData['total_price']			= $data['totalPrice'];
		
		
		//Cue Designer Data	
		$designerData['side_data_array']		= serialize($data['sideDataArray']);
		$designerData['design_data_array']		= serialize($data['designDataArray']);	
		$designerData['price_info_arr']			= serialize($data['priceInfoArr']);
		$designerData['chinese_info_arr']		= serialize($data['chineseInfoArr']);
		$designerData['part_dropdown_arr']		= serialize($data['partDropDownArr']);
		
		// Data Status	
		$designerData['status'] 				= self::DESIGNERSOFTWRE_STATUS_ENABLE; 		
		
		
		if(isset($data['designId']) && $data['designId']>0 && !empty($data['designId'])):
		
			//$magentoProductId 					= $this->getMagentoProduct($designerData);
			//$designerData['product_id']			= $magentoProductId;
			$designerData['product_id']			= $designerProductId;
			$designerData['update_time']		= now();						
			
			$model->setData($designerData);
			$model->setId($data['designId']);
			
		else:
			//$magentoProductId 					= $this->getMagentoProduct($designerData);
			//$designerData['product_id']			= $magentoProductId;
			$designerData['product_id']			= $designerProductId;
			$designerData['update_time']		= now();		
			$designerData['created_time']		= now();			
				
			$model->setData($designerData);
			
		endif;		
		
		$model->save();		
		$designerData['designId']				= $model->getId();
		
		//echo '<pre>';print_r($designerData);exit;
		return $designerData;				
		
	}	
	
	public function addProductToWishlist($customerId, $productId, $options, $quantity){
		
			
			//$wishlist=Mage::helper('wishlist')->getWishlist();
			$wishlist = Mage::getModel('wishlist/wishlist')->loadByCustomer($customerId, true);
			$storeId = Mage::app()->getStore()->getId();
			$model = Mage::getModel('catalog/product');
			$_product = $model->load($productId); 
			$params = array('product' => $productId,
							'qty' => $quantity,
							'store_id' => $storeId,
							'options' => array($options['id']=>$options['value'])
							);
							
			 $request = new Varien_Object();
			 $request->setData($params);
			 $result = $wishlist->addNewItem($_product, $request);
			 
	}
	
	public function designCode(){
		$designCodeValue=substr(uniqid(''),0,4).'-'.substr(uniqid(''),3,4).'-'.substr(uniqid(''),7,4);
		return $designCodeValue;
	}
	
	public function getDesignCode($designId){
		$collection = Mage::getModel('designersoftware/designersoftware')->getCollection()->addFieldToFilter('design_id',$designId)->getFirstItem();
		//echo '<pre>';print_r($collection);exit;
		return $collection->getDesignCode();		
	}
	
	public function createDesignImages($data){
		//echo '<pre>';print_r($data);exit;
		$designcode = $data['styleDesignCode'];
		$previewArr = $data['previewArr'];
		
		$count=0;
		foreach($previewArr as $key=>$preview):
			$anglesCollection = Mage::getModel('designersoftware/angles')->getAnglesCollectionById($key+1);
			if($count==0):
				$corePath = Mage::getBaseDir('media') . DS . 'inky';
				Mage::helper('designersoftware/image')->createDirectory($corePath);
				$corePath = $corePath . DS . 'designs';
				Mage::helper('designersoftware/image')->createDirectory($corePath);
				$corePath = $corePath . DS . $designcode;
				Mage::helper('designersoftware/image')->createDirectory($corePath);			
				$orgPath = $corePath . DS . 'original';
				Mage::helper('designersoftware/image')->createDirectory($orgPath);			
				$count++;
			else:
				$corePath = Mage::getBaseDir('media') . DS . 'inky' . DS . 'designs' . DS . $designcode;
				$orgPath = Mage::getBaseDir('media') . DS . 'inky' . DS . 'designs' . DS . $designcode . DS . 'original';
			endif;
			
			$filename = $anglesCollection->getTitle().'.png';
			$orgPathFile = $orgPath . DS . $filename;			
			
			if($this->getPreviewImage($preview,$orgPathFile)):
				$canvas_image_width=35;							
				$canvas_image_height=138;
				Mage::helper('designersoftware/image')->resizeImage($orgPathFile,$corePath.DS.$canvas_image_width.'x'.$canvas_image_height.DS.$filename,35,138);
				$canvas_image_width=82;							
				$canvas_image_height=318;
				Mage::helper('designersoftware/image')->resizeImage($orgPathFile,$corePath.DS.$canvas_image_width.'x'.$canvas_image_height.DS.$filename,82,318);
				$canvas_image_width=82;							
				$canvas_image_height=318;
				Mage::helper('designersoftware/image')->resizeImage($orgPathFile,Mage::getBaseDir('media') . DS . 'catalog/product/designersoftware/'.$designcode.'/'.$filename,350,440);
				Mage::getModel('catalog/product_image')->clearCache();
			else:
				break;
				return false;
			endif;			
		endforeach;		
		
	}
	
	public function getProductDetailUrl($designId){
		// Designer Product ID
		$designerProductId = Mage::getModel('core/variable')->loadByCode('DESIGNER_TOOL_PRODUCT_ID')->getValue('plain');
						
		$_productCollection = Mage::getModel('catalog/product')->load($designerProductId);		
		$params = $this->getDesignerProductParams($designId);	
		
		return $_productCollection->getUrlPath().'?'.$params;
	} 
	
	public function getDesignerProductParams($designId){				
		if($designId>0):
								
			$params='';			
			$params .='did='.$designId;
			$params .='&';
			$params .='mode=edit'; 
			//$params .='&';
			//$params .='code='.$designCode;
			
			$params = base64_encode($params);			
			return $params;
		endif;
	}
	
	public function getPreviewImage($preview, $previewImgPath = ''){		
		$previewData = base64_decode(substr($preview, 22));		
		
		if(!empty($previewData)):			
			@unlink($previewImgPath);
			if (!file_exists($previewImgPath)):
				$fp = fopen($previewImgPath, 'w');
				fwrite($fp, $previewData);
				fclose($fp);				
				
				return true;
			endif;
		endif;			
		
		return false;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	public function createDesignMagentoThumb($data){
		$angleImageDirPath = Mage::getBaseDir(). DS . $data['shoePath'];
		
		if(file_exists($angleImageDirPath.'000.png')):
			$path = Mage::helper('designersoftware/image')->getThumbByFullImageDirPath($angleImageDirPath,'000',250);				
			if(file_exists($path)):			
				$path = $data['shoe_path'].'thumb'.DS.'000.png';
				return $path; 		
			else:
				return false;
			endif;										
		endif;					
	}
	public function getMagentoProduct($designerData){
		
		if(isset($designerData['designId']) && $designerData['designId']>0):
			$designersoftwareCollection = Mage::getModel('designersoftware/designersoftware')->getCollection()
												->addFieldToFilter('designersoftware_id', $designerData['designId'])
												->addFieldToFilter('status',1)
												->getFirstItem();
												
			$_product = Mage::getModel('catalog/product')->load($designersoftwareCollection->getProductId());
			$this->removeImages($_product);			
		else:
			$_product = Mage::getModel('catalog/product');
		endif;		
		
		$_product->setAttributeSetId($this->getAttributeSetId()); // #4 is for default
		$_product->setTypeId('simple');
		$_product->setName($designerData['title']);
		$_product->setDescription('Description');
		$_product->setShortDescription('Short Description');		
		$_product->setSku($this->getSku($designerData['title']));
		$_product->setWeight(1);		// 1.0 Weight
		$_product->setStatus(1);		// Status Enabled
		$_product->setVisibility(2); 	// for Catalog only
		$_product->setPrice($designerData['base_price']);
		$_product->setStockData(array(
				'is_in_stock' => 1,
				'qty' => 99999
			));
		$_product->setWebsiteIds(array(1));	 // 1 is Default
		$_product->setCreatedAt(strtotime('now'));	
		$_product->setTaxClassId(0);	 // 0 for None, 2 for Taxable Goods
		$_product = $this->addImages($_product, $designerData['shoe_path']);
		
		//echo '<pre>';print_r($designerData);
		//print_r($_product->getData());exit;
		
		$_product->save();
		//echo Mage::getResourceModel('catalog/product')->getAttributeRawValue($_product->getId(), 'attribute_code', Mage::app()->getStore());exit;
		//echo '<pre>';print_r($_product->getData());exit;
		
		return $_product->getId();
	}
	
	public function getSku($productName){
		$productSku=strtolower(str_replace(' ','-',$productName)).'#'.time().rand(1,100);
		return $productSku;
	}
	
	public function addImages($_product, $shoePath){

		 // Add product images
		$_product->setMediaGallery(array('images' => array(), 'values' => array()));
				
		$pathDir = Mage::getBaseDir(). DS . $shoePath;
		
		if (file_exists($pathDir.'000'. self::IMAGE_EXT)){
			$anglesCollection  = Mage::getModel('designersoftware/angles')->getCollection()->addFieldToFilter('status',1);
			$i = 0;
			foreach($anglesCollection as $angle){
				$imagePath = $pathDir . $angle->getTitle() . self::IMAGE_EXT;
				if($i == 0){
					$firstImagePath = $imagePath;					
				} else {
					$_product->addImageToMediaGallery($imagePath, array('image', 'small_image', 'thumbnail'), false, false);
					break;
				}
				$i++;
			}
			$_product->addImageToMediaGallery($firstImagePath, array('image', 'small_image', 'thumbnail'), false, false);
		}
		
		return $_product;				
	}
	
	public function removeImages($_product){
		
		// Remove images
		$entityTypeId 			= Mage::getModel('eav/entity')
										->setType('catalog_product')->getTypeId();
		$mediaGalleryAttribute 	= Mage::getModel('catalog/resource_eav_attribute')
										->loadByCode($entityTypeId, 'media_gallery');
		
		$gallery = $_product->getMediaGalleryImages();		
		foreach ($gallery as $image)
			$mediaGalleryAttribute->getBackend()->removeImage($_product, $image->getFile());
			
		$_product->save();		
	}	
	
	public function getAttributeSetId($attrSetName='Default'){
		$entityType = Mage::getModel('eav/entity_type')
							->getCollection()
							->addFieldToSelect('entity_type_id')
							->addFieldToFilter('entity_type_code','catalog_product')
							->getData();
	
		$entityTypeId = $entityType[0]['entity_type_id'];			
		$attributeSet = Mage::getModel('eav/entity_attribute_set')
						->getCollection()
						->addFieldToFilter('entity_type_id',$entityTypeId)      // For catalog_product
						->addFieldToFilter('attribute_set_name',$attrSetName)
						->getData();
		
		$attributeSetId = $attributeSet[0]['attribute_set_id'];
		
		return $attributeSetId;			
	}
}
