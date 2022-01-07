<?php
class Inky_Designersoftware_TestController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
		$designersoftwareModel = Mage::getModel('designersoftware/designersoftware');
		
		
				
		$collection = $designersoftwareModel->getCollectionByCode('5e59-9750-36da');
		
		$data = $collection->getData();
		
		$designerId = $data['designersoftware_id'];
		
		$partDropdownArr = json_decode(unserialize($data['part_dropdown_arr']));
		
		foreach ( $partDropdownArr as $part ){
			foreach ( $part->layers as  $layer){
				if ( $layer->layerName=='LAYER7' ){
					$layer->colorTitle = 'Black 3';
					$layer->colorId = '8';
					$layer->hexColorCode = '000000';
				}
				
				if ( $layer->layerName=='LAYER13' ){
					$layer->colorTitle = 'Black 3';
					$layer->colorId = '8';
					$layer->hexColorCode = '000000';
				}
			}
		}
		
		$partDropdownArr = serialize(json_encode($partDropdownArr));
		
		$designersoftwareModel->setPartDropdownArr($partDropdownArr)
							  ->setId($designerId)
							  ->save();
		    	
    }  
	
    public function thumbAction(){			
		Mage::helper('designersoftware/composite_image')->getImage($data);	
	}
	
	public function imporveAction(){
		
		$collection = Mage::getModel('designersoftware/designersoftware')->getCollectionByCode('5e57-7f56-61bc');
		echo "<pre>dss";print_r($collection->getData());exit;
		
	}   
}
