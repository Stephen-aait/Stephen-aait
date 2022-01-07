<?php
/**
 * Mas_Masonotes extension by Makarovsoft.com
 * 
 * @category   	Mas
 * @package		Mas_Masonotes
 * @copyright  	Copyright (c) 2014
 * @license		http://makarovsoft.com/license.txt
 * @author		makarovsoft.com
 */
/**
 *
 * @category	Mas
 * @package		Mas_Masonotes
 * 
 */
class Mas_Masonotes_IndexController extends Mage_Core_Controller_Front_Action {
	
	public function preDispatch()
    {
        parent::preDispatch();
        
		if (!Mage::getStoreConfigFlag('masonotes/customer/enabled')) {
            $this->norouteAction();
            return;
        } 
        
        $session = Mage::getSingleton('customer/session');
        if (!$session->authenticate($this)) {
            $this->setFlag('', 'no-dispatch', true);
        }
    } 
    
	public function indexAction()
	{
        $this->loadLayout();
        $this->_initLayoutMessages('customer/session');
        
        $block = $this->getLayout()->getBlock('customer_account_navigation');
        
        if ($block) {
            $block->setActive('ordersnotes/index');
        }
        
        $this->_title(Mage::helper('masonotes')->__('Orders Notes'));
			 
        $this->renderLayout();
	}
	
	public function acceptAction()
	{
		$params = $this->getRequest()->getParams();
		
		$customerId =  Mage::getSingleton('customer/session')->getCustomer()->getId();
		$order = Mage::getModel('sales/order')->load($params['order_id']);
		
		if (!$order || $order->getCustomerId() != $customerId) {
			$this->getResponse()->setBody('Hacker? Ha-Ha');
			return;
		}
		
		$note = array(
			'note' => $params['note'],
			'order_id' => $params['order_id'],
			'stores' => $params['store_id'],
			'customer_id' => $customerId,
			'status' => 1,
		);
		
		$newNote = Mage::getModel('masonotes/note')
			->addData($note)
			->save();
		
		$data = array(
			array(
				'customer_id' => $params['order_id'],
				'note_id' => $newNote->getId(),
				'position' => 0,
			)
		);
		/* @var $model Mas_Masonotes_Model_Resource_Note_Customer */
		$model = Mage::getResourceSingleton('masonotes/note_customer');
		$model->saveSingleProductRelation($data);
		
		$html = $this->getLayout()->createBlock('masonotes/notes')
			->setData('order_id', $params['order_id'])
			->setTemplate('mas_masonotes/list.phtml')
			->toHtml();
		
		$this->getResponse()->setBody($html);
				
	}
}