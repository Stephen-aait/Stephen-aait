<?php
require_once "Mage/Adminhtml/controllers/CustomerController.php";
class A2bizz_Customerdesign_Adminhtml_DesignsController extends Mage_Adminhtml_CustomerController
{
	public function indexAction(){
		echo "Amit ";exit;
	}
	
    public function designsAction () {
        $this->loadLayout();
        $this->getLayout()->getBlock('edit.tab.designs');        
        $this->renderLayout();   

    }
}

