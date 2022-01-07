<?php
ini_set("max_execution_time", 0);
class Emipro_Customoptions_Adminhtml_ManageController extends Mage_Adminhtml_Controller_Action {

    public function indexAction() {
        //Mage::helper("emipro_customoptions")->validCustomOptionsData();
        $this->loadLayout();
        $this->_setActiveMenu('catalog/emipro_manageoptions');
        $this->_title($this->__("Custom Options Manager"));
        $this->_title($this->__("Manage Custom Options of product"));
		$this->_title($this->__("Select Product"));   
        $this->renderLayout();
    }
    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('catalog/emipro_manageoptions');
    }
    public function editAction() {
        $productId = $this->getRequest()->getParam("id");
        if ($productId) {
            $product = Mage::getModel("catalog/product")->load($productId);
            Mage::register("product_name",$product->getName());
            $options = $product->getOptions();
            $optionSku = array();
            foreach ($options as $option) {
                $optionSku[] = $option->getSku();
            }
            Mage::getSingleton("core/session")->setOldOptions($optionSku);
        }
        $this->loadLayout();
        $this->_setActiveMenu('catalog/emipro_manageoptions');
        $this->_title($this->__("Custom Options Manager"));
        $this->_title($this->__("Manage Custom Options of product"));
		$this->_title($this->__("Manage Options"));           
        $this->renderLayout();
    }
    public function saveAction() {

        $data = $this->getRequest()->getPost();
        $productId = $this->getRequest()->getParam("id");
        $oldOptions = Mage::getSingleton("core/session")->getOldOptions();
        $newOptions = array_diff($data["custom_option"], $oldOptions);
        $optionModel = Mage::getModel("emipro_customoptions/customoptions");
        $back = $this->getRequest()->getParam("back");
        try {
            foreach ($oldOptions as $option) {
                if (!in_array($option, $data["custom_option"])) {
                    $optionModel->deleteCustomOption($option, $productId);
                }
            }
            foreach ($newOptions as $newOption) {
                $optionModel->saveCustomOptionBySku($newOption, $productId);
            }
            Mage::getSingleton("core/session")->addSuccess($this->__("Option has been assigned/removed."));
            if (isset($back)) {

                $this->_redirect('*/*/edit', array('id' => $productId));
                return;
            }
            $this->_redirect("*/*/");
            return;
        } catch (Exception $e) {
            Mage::getSingleton("core/session")->addError($this->__($e->getMessage()));
        }
    }

}
