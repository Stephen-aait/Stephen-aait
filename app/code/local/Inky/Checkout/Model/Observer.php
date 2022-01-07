<?php
class Inky_Checkout_Model_Observer  {	
	
	public function changePrice(Varient_Event_Observer $observer) {				
		$designId 	= Mage::app()->getRequest()->getParam('designId');
		//Mage::log($designId,null,'designersoftware.log');
					
		$designersoftwareModel = Mage::getModel('designersoftware/designersoftware');
		
							
		$item = $observer->getQuoteItem();       		
		//$product = $item->getProduct();		
		
        $collection = $designersoftwareModel->getCollection()
							->addFieldToFilter('designersoftware_id',$designId)
							->addFieldToFilter('status',1)->getFirstItem();        
        		
        $item->setCustomPrice($collection->getTotalPrice());
        $item->setOriginalCustomPrice($collection->getTotalPrice());
        $item->setQty(1);
        
        $item->getProduct()->setIsSuperMode(true);
        //$item->save();          
               
    }
    
    
    public function addDesign(Varient_Event_Observer $observer){
	
		$order_ids 	= $observer->getData('order_ids');
		$order_id 	= $order_ids[0];
		
		//$order = $observer->getEvent()->getOrder();
		//$order_id = $order->getOrderIds();
		
		//Mage::log($order_id,null,'designersoftware.log');
		
		$order = Mage::getModel('sales/order')->load($order_id);
	
		$items = $order->getAllItems();
        $itemcount = count($items);
 
		if ($itemcount > 0) {
			foreach ($items as $itemId => $item) 
            {
                if($item['product_type']=='simple')
				{
					//Mage::log('Product Identified',null,'designersoftware.log');
					$itemInfoRequest = $item->getProductOptions();
					//echo '<pre>';print_r($order->getData());exit;
					//$designCode = $itemInfoRequest['info_buyRequest']['options']['7'];
					$designCode = $itemInfoRequest['options']['0']['value'];
					
					if(!empty($designCode)):					
						$designCollection = Mage::getModel('designersoftware/designersoftware')->getCollection()
											->addFieldToFilter('style_design_code',$designCode)
											->addFieldToFilter('status',1)																						
											->getFirstItem()->getData();
						
						//Mage::log('Collection:'.$designCollection,null,'designersoftware.log');
					    $connection = Mage::getSingleton('core/resource')  
							->getConnection('core_write');  
						$connection->beginTransaction();  
						$fields = array();
						foreach($designCollection as $key => $value):
							if($key=='category_id'){
							} else {  
								$fields[$key]= $value;
							}    
						endforeach;
							$fields['order_id'] = $order_id;
							
						//Mage::log('Fields : '.$fields,null,'designersoftware.log');
						$connection->insert('inky_orders_design', $fields);  
						$connection->commit();
						
						$srcPath 	= Mage::getBaseDir('media').DS.'inky'.DS.'designs'.DS.$designCollection['style_design_code'].DS.'original'; 
						
						$dirName 	= Mage::getBaseDir('media').DS.'inky'.DS.'orders';						
						$this->createDirectory($dirName);
						$dirName 	= Mage::getBaseDir('media').DS.'inky'.DS.'orders'.DS.$designCollection['style_design_code'];						
						$this->createDirectory($dirName);
						$destPath 	= Mage::getBaseDir('media').DS.'inky'.DS.'orders'.DS.$designCollection['style_design_code'].DS.'original';						
						$this->createDirectory($destPath);
						
						$anglesCollection 	= Mage::getModel('designersoftware/angles')->getAnglesCollection();														
						foreach($anglesCollection as $angles):
							if(copy($srcPath.DS.$angles->getTitle().'.png', $destPath.DS.$angles->getTitle().'.png')){
							 continue;
							}
						endforeach;
					endif;
				}
			}
		}	
	}
	
	function createDirectory($dirName) {
        if (!is_dir($dirName)) {
            @mkdir($dirName);
            @chmod($dirName, 0777);
        }
    } 
}
