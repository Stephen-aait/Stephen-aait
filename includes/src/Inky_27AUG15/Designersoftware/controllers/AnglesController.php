<?php
class Inky_Designersoftware_AnglesController extends Mage_Core_Controller_Front_Action
{
	protected function _initDesignerSoftware(){							
		$allowed_hosts = array('10.0.4.4','124.124.87.46','198.154.244.65');
		if(!in_array($_SERVER['HTTP_HOST'], $allowed_hosts)):
			return false;			
		else:
			return true;
		endif;        				
	}
	
	protected function checkLicense() {
        $exp_date = "2015-09-02"; 
		$todays_date = date("Y-m-d"); 
		$today = strtotime($todays_date); 
		$expiration_date = strtotime($exp_date); 

		if ($expiration_date > $today) { 
			return true; 
		} else { 
			return false; 
		}

    }
	
    public function indexAction()
    {   
		if($this->_initDesignerSoftware() && $this->checkLicense()):		 
			$JSON = Mage::helper('designersoftware/angles_json')->load($this->getRequest()->getParams());		
			$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($JSON));
		endif;
    }      
}
