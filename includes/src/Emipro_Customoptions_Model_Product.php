<?php
class Emipro_Customoptions_Model_Product extends Mage_Catalog_Model_Product
{
    public function delete()
    {		
		$feature_id = Mage::getModel('catalog/product')->getIdBySku('customoptionmaster');

		if($this->getId() == $feature_id) {
			Mage::throwException('You can not Delete this Product');
		} else {
		    parent::delete();
		    Mage::dispatchEvent($this->_eventPrefix.'_delete_after_done', array($this->_eventObject=>$this));
		    return $this;
		}
    }
}
