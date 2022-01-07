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
 * Masonotes default helper
 *
 * @category	Mas
 * @package		Mas_Masonotes
 * 
 */
class Mas_Masonotes_Helper_Data extends Mage_Core_Helper_Abstract{
    public function getAdmins()
	{
		$admins = Mage::getModel('admin/user')->getCollection();
		$return = array();
		foreach($admins as $admin){
			$return[$admin->getUserId()] = $admin->getFirstname() . ' ' . $admin->getLastname(); 
		}
		return $return;
	}
	
	public function notify($email)
	{
		/* @var $tpl Mage_Core_Model_Email_Template */
        $tpl = Mage::getModel('core/email_template');
        
		$storeId = $email['store_id'];
		$tpl->setDesignConfig(array('area' => 'frontend', 'store' => $storeId));
		
		$tpl->sendTransactional(
				Mage::getStoreConfig('masonotes/general/mail_template', $storeId),
				array(
                	'email' => $email['sender_email'],
                    'name' => $email['sender_name']
				),
                $email['recepient'],
                $email['recepient'],
                array(
                	'email_text' => nl2br($email['email_text']),
                	'email_subject' => $email['subject'],
               )
		);	
	}
}