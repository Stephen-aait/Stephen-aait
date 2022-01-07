<?php

class Wyomind_Estimateddeliverydate_Adminhtml_ManageleadtimesController extends Mage_Adminhtml_Controller_Action {

    protected function _initAction() {

        $this->loadLayout()
                ->_setActiveMenu("catalog/attributes");
        return $this;
    }

    public function indexAction() {

        $this->_initAction()
                ->renderLayout();
    }
	protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('catalog/attributes/attribute_leadtimes');
    }
    public function saveAction() {

        $attributes = $this->getRequest()->getPost('leadtime');
        $resource = Mage::getSingleton('core/resource');
        $write = $resource->getConnection('core_write');
        $tableEal = Mage::getSingleton("core/resource")->getTableName("eav_attribute_leadtime");
       
        foreach ($attributes as $attribute_id => $values) {
            foreach ($values as $value_id => $leadtime) {
                $sql[] = "REPLACE INTO $tableEal (attribute_id,value_id,value) VALUES ($attribute_id, $value_id,'$leadtime');";
            }
        }
        
        $commit = true;

        foreach ($sql as $s) {

            try {
                $write->exec($s);
            } catch (Mage_Core_Exception $e) {
                $commit = false;
            } catch (Exception $e) {

                $commit = false;
            }
        }
        if (!$commit) {
            $write->rollback();
            die("{status:error}");
        } else {
            $write->commit();
            die("{status:success}");
        }
    }

}
