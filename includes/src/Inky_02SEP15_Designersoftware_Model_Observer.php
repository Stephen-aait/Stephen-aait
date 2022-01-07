<?php
class Inky_Designersoftware_Model_Observer  {	
	
	public function changePrice(Varient_Event_Observer $observer) {			
		$designId 	= Mage::app()->getRequest()->getParam('designId');
		//Mage::log('fgfdgfd'.$designId,null,'designersoftware.log');
					
		$designersoftwareModel = Mage::getModel('designersoftware/designersoftware');
		
							
		$item = $observer->getQuoteItem();       		
		$product = $item->getProduct();		
		
        $collection = $designersoftwareModel->getCollection()->addFieldToFilter('designersoftware_id',$designId)->addFieldToFilter('status',1)->getFirstItem();        
        		
        $item->setCustomPrice($collection->getTotalPrice());
        $item->setOriginalCustomPrice($collection->getTotalPrice());
        $item->setQty($totalQty);
        
        $item->getProduct()->setIsSuperMode(true);	
        $item->save();          
               
    } 
}
