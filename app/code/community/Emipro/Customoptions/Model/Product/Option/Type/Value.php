<?php
class Emipro_Customoptions_Model_Product_Option_Type_Value extends Mage_Core_Model_Abstract {

    public function _construct() {
	
        $this->_init('emipro_customoptions/product_option_type_value');
    }
    public function deleteValue($option_id)
    {
        $this->getResource()->deleteValue($option_id);
        return $this;
    }

    public function deleteValues($option_type_id)
    {
        $this->getResource()->deleteValues($option_type_id);
        return $this;
    }
    public function saveValues($optionValues)
    {
		$optionId=$optionValues["option_id"];
			if(!isset($optionValues["store_id"]))
			{
				$storeId=0;
			}
		$storeId=$optionValues["store_id"];
		unset($optionValues["option_id"]);
		unset($optionValues["store_id"]);
		
				foreach($optionValues as $optVal){
					$valueModel=$this;
					if(isset($optVal["option_type_id"]) && $optVal["option_type_id"]!="-1")
					{
						$valueModel->setOptionTypeId($optVal["option_type_id"]);								
					}
					else
					{
						$valueModel->setOptionTypeId(null);	
					}
					$valueModel->setOptionId($optionId);
					$valueModel->setData("sku",$optVal["sku"]);
					$valueModel->setData("sort_order",$optVal["sort_order"]);
					$valueModel->setData("store_id",$storeId);
					if ($optVal["is_delete"] == '1') {
						if ($valueModel->getOptionTypeId()) {
							$valueModel->deleteValues($valueModel->getOptionTypeId());
							$valueModel->delete();
						}
					}
					else {
							$valueModel->save();
							if($optVal["option_type_id"]=="-1" || !isset($optVal["option_type_id"]))
							{
					
								$optVal["option_type_id"]=$valueModel->getId();
								$optVal["store_id"]=$storeId;
							}

							$this->saveOptionTitle($optVal);
							$this->saveOptionsPrice($optVal);
				}
		}
	}
	public function saveOptionTitle($optVal)
	{
			if($optVal["option_type_id"]=="-1")
			{
				unset($optVal["option_type_id"]);
			}
			$valuesTitle=Mage::getModel("emipro_customoptions/product_option_type_title");
			$valuesTitle->load($optVal["option_type_id"],"option_type_id");
			if($valuesTitle->getId())
			{
				$valuesTitle->setId($valuesTitle->getId());
			}
			$valuesTitle->setOptionTypeId($optVal["option_type_id"]);
			$valuesTitle->setStoreId($optVal["store_id"]);
			$valuesTitle->setData("title",$optVal["title"]);
			
			$valuesTitle->save();
	}
	public function saveOptionsPrice($optVal)
	{
		if($optVal["option_type_id"]=="-1")
			{
				unset($optVal["option_type_id"]);
			}
			$valuesPrice=Mage::getModel("emipro_customoptions/product_option_type_price");
			$valuesPrice->load($optVal["option_type_id"],"option_type_id");
			if($valuesPrice->getId())
			{
				$valuesPrice->setId($valuesPrice->getId());
			}
			$valuesPrice->setOptionTypeId($optVal["option_type_id"]);
			$valuesPrice->setStoreId($optVal["store_id"]);
			$valuesPrice->setData("price",$optVal["price"]);
			$valuesPrice->setData("price_type",$optVal["price_type"]);
			$valuesPrice->save();
	}
	public function getOptionValues()
	{

		$optionsArr = Mage::registry("option")->getData();
		$optionId=$optionsArr["option_id"];
	  if(array_key_exists(0,$optionsArr))
	  {
		  $optionId=$optionsArr[0]["option_id"];
		}
		
		Mage::unregister("option");
		$optionCollection=Mage::getSingleton("emipro_customoptions/product_option_type_value")->getCollection()->addFieldToFilter("main_table.option_id",$optionId);
        $optionCollection->getSelect()
        ->join(
			Mage::getConfig()->getTablePrefix() . 'emipro_product_option_type_title', 
			'main_table.option_type_id =' . Mage::getConfig()->getTablePrefix() . 'emipro_product_option_type_title.option_type_id', 
			array("title" => "title"))			
        ->join(
			Mage::getConfig()->getTablePrefix() . 'emipro_product_option_type_price', 
			Mage::getConfig()->getTablePrefix(). 'emipro_product_option_type_title.option_type_id =' . Mage::getConfig()->getTablePrefix() . 'emipro_product_option_type_price.option_type_id', 
			array("price_type" => "price_type","price"=>"price"))			
				->group(Mage::getConfig()->getTablePrefix()."emipro_product_option_type_title.option_type_id");
			return $optionCollection;
		
	}
}
