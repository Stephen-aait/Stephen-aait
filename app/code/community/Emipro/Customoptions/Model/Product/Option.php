<?php

class Emipro_Customoptions_Model_Product_Option extends Mage_Core_Model_Abstract {
	
	  const OPTION_GROUP_TEXT   = 'text';

    /**
     * Option group file
     */
    const OPTION_GROUP_FILE   = 'file';

    /**
     * Option group select
     */
    const OPTION_GROUP_SELECT = 'select';

    /**
     * Option group date
     */
    const OPTION_GROUP_DATE   = 'date';

    /**
     * Option type field
     */
    const OPTION_TYPE_FIELD     = 'field';

    /**
     * Option type area
     */
    const OPTION_TYPE_AREA      = 'area';

    /**
     * Option group file
     */
    const OPTION_TYPE_FILE      = 'file';

    /**
     * Option type drop down
     */
    const OPTION_TYPE_DROP_DOWN = 'drop_down';

    /**
     * Option type radio
     */
    const OPTION_TYPE_RADIO     = 'radio';

    /**
     * Option type checkbox
     */
    const OPTION_TYPE_CHECKBOX  = 'checkbox';

    /**
     * Option type multiple
     */
    const OPTION_TYPE_MULTIPLE  = 'multiple';

    /**
     * Option type date
     */
    const OPTION_TYPE_DATE      = 'date';

    /**
     * Option type date/time
     */
    const OPTION_TYPE_DATE_TIME = 'date_time';

    /**
     * Option type time
     */
    const OPTION_TYPE_TIME      = 'time';
     protected $_valueInstance;

    public function _construct() {
	
        $this->_init('emipro_customoptions/product_option');
    }
    public function deletePrices($option_id)
    {
        $this->getResource()->deletePrices($option_id);
        return $this;
    }
    public function getValueInstance()
    {
        if (!$this->_valueInstance) {
            $this->_valueInstance = Mage::getSingleton('emipro_customoptions/product_option_type_value');
        }
        return $this->_valueInstance;
    }
    public function saveOptions($option)
    {
		if(isset($option["product"]))
		{
			$key=array_keys($option["product"]["options"]);
			$data=$option["product"]["options"][$key[0]];
		}
		else
		{
			$data=$option;
		}


		if($data)
		unset($data["id"]);	
		if($data["option_id"]==0)
		{
			unset($data["option_id"]);
		}
		else
		{
			$this->setOptionId($data["option_id"]);
		}
		 $isEdit = (bool)$this->getId()? true:false;
			$this->setData($data);
			if ($this->getData('previous_type') != '') {
				$previousType = $this->getData('previous_type');
                    /**
                     * if previous option has different group from one is came now
                     * need to remove all data of previous group
                     */
                    if ($this->getGroupByType($previousType) != $this->getGroupByType($this->getData('type'))) {

                        switch ($this->getGroupByType($previousType)) {
                            case self::OPTION_GROUP_SELECT:
                                $this->unsetData('values');
                                if ($isEdit) {
                                    $this->getValueInstance()->deleteValue($this->getId());
                                }
                                break;
                            case self::OPTION_GROUP_FILE:
                                $this->setData('file_extension', '');
                                $this->setData('image_size_x', '0');
                                $this->setData('image_size_y', '0');
                                break;
                            case self::OPTION_GROUP_TEXT:
                                $this->setData('max_characters', '0');
                                break;
                            case self::OPTION_GROUP_DATE:
                                break;
                        }
                        if ($this->getGroupByType($this->getData('type')) == self::OPTION_GROUP_SELECT) {
                           // $this->setData('sku', '');
                            $this->unsetData('price');
                            $this->unsetData('price_type');
                            if ($isEdit) {
                                $this->deletePrices($this->getId());
                            }
                        }
                    }
                }
	
					$this->save();
		
		$optionPrice=Mage::getModel("emipro_customoptions/product_option_price")->load($this->getId(),"option_id");
		if($optionPrice->getId())
		{

			$optionPrice->setId($optionPrice->getId());
		}
		$optionPrice->setOptionId($this->getId());
		$optionPrice->setStoreId(0);
		$optionPrice->setData("price",$data["price"]);
		$optionPrice->setData("price_type",$data["price_type"]);
		$optionPrice->save();
		
		$optionTitle=Mage::getModel("emipro_customoptions/product_option_title")->load($this->getId(),"option_id");
		if($optionTitle->getId())
		{
			$optionTitle->setId($optionTitle->getId());
		}
		
		$optionTitle->setOptionId($this->getId());
		$optionTitle->setStoreId(0);
		$optionTitle->setData("title",$data["title"]);
		$optionTitle->save();
	if($this->getGroupByType($this->getData('type')) == self::OPTION_GROUP_SELECT)
	{
		
		if(isset($option["product"]["options"][$key[0]]["values"]) || $option["values"])
		{
			if(isset($option["product"]))
			{
			$optionValues=$option["product"]["options"][$key[0]]["values"];
			}
			else
			{
				$optionValues=$option["values"];
			}

			$optionValues["store_id"]=0;
			$optionValues["option_id"]=$this->getId();
			$this->saveOptionsValue($optionValues);

		}
	}
		return $this;
		
	}
	public function saveOptionsValue($values)
	{
		
			$options=Mage::getModel("emipro_customoptions/product_option_type_value");
			$options->saveValues($values);
			
	}
	public function getOptionValues()
	{	$data=Mage::registry("option_data");

		if(!isset($data))
		{

			Mage::register("option",$this);
		}
		else
		{
			Mage::register("option",$data);
		}
		$options=Mage::getModel("emipro_customoptions/product_option_type_value");
			return $options->getOptionValues();
	}
	public function getOption()
	{
		            $optionCollection = $this->getCollection()->addFieldToFilter("main_table.option_id", $this->getId());
            $optionCollection->getSelect()
                    ->join(
                            Mage::getConfig()->getTablePrefix() . 'emipro_product_option_title', 'main_table.option_id =' . Mage::getConfig()->getTablePrefix() . 'emipro_product_option_title.option_id', array("title" => "title"))
                    ->join(
                            Mage::getConfig()->getTablePrefix() . 'emipro_product_option_price', 'main_table.option_id =' . Mage::getConfig()->getTablePrefix() . 'emipro_product_option_price.option_id', array("price_type" => "price_type", "price" => "price"))
                    ->group(Mage::getConfig()->getTablePrefix(). "emipro_product_option_title.option_id");
                  return $optionCollection;
	}
	public function getGroupByType($type = null)
    {

        if (is_null($type)) {
            $type = $this->getType();
        }
        $optionGroupsToTypes = array(
            self::OPTION_TYPE_FIELD => self::OPTION_GROUP_TEXT,
            self::OPTION_TYPE_AREA => self::OPTION_GROUP_TEXT,
            self::OPTION_TYPE_FILE => self::OPTION_GROUP_FILE,
            self::OPTION_TYPE_DROP_DOWN => self::OPTION_GROUP_SELECT,
            self::OPTION_TYPE_RADIO => self::OPTION_GROUP_SELECT,
            self::OPTION_TYPE_CHECKBOX => self::OPTION_GROUP_SELECT,
            self::OPTION_TYPE_MULTIPLE => self::OPTION_GROUP_SELECT,
            self::OPTION_TYPE_DATE => self::OPTION_GROUP_DATE,
            self::OPTION_TYPE_DATE_TIME => self::OPTION_GROUP_DATE,
            self::OPTION_TYPE_TIME => self::OPTION_GROUP_DATE,
        );

        return isset($optionGroupsToTypes[$type])?$optionGroupsToTypes[$type]:'';
    }
}
