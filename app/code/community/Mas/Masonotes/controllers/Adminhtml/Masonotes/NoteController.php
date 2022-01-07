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
 * Note admin controller
 *
 * @category	Mas
 * @package		Mas_Masonotes
 * 
 */
class Mas_Masonotes_Adminhtml_Masonotes_NoteController extends Mas_Masonotes_Controller_Adminhtml_Masonotes {
	/**
	 * init the note
	 * @access protected
	 * @return Mas_Masonotes_Model_Note
	 */
	protected function _initNote(){
		$noteId  = (int) $this->getRequest()->getParam('id');
		$note	= Mage::getModel('masonotes/note');
		if ($noteId) {
			$note->load($noteId);
		}
		Mage::register('current_note', $note);
		return $note;
	}
 	/**
	 * default action
	 * @access public
	 * @return void
	 * 
	 */
	public function indexAction() {
		$this->loadLayout();
		$this->_title(Mage::helper('masonotes')->__('Notes on Orders'));
		$this->renderLayout();
	}
	/**
	 * grid action
	 * @access public
	 * @return void
	 * 
	 */
	public function gridAction() {
		$this->loadLayout()->renderLayout();
	}
	/**
	 * edit note - action
	 * @access public
	 * @return void
	 * 
	 */
	public function editAction() {
		$noteId	= $this->getRequest()->getParam('id');
		$note  	= $this->_initNote();
		if ($noteId && !$note->getId()) {
			$this->_getSession()->addError(Mage::helper('masonotes')->__('This note no longer exists.'));
			$this->_redirect('*/*/');
			return;
		}
		$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
		if (!empty($data)) {
			$note->setData($data);
		}
		Mage::register('note_data', $note);
		$this->loadLayout();
		$this->_title(Mage::helper('masonotes')->__('Notes On Orders'));
		
		if ($note->getId()){
			$this->_title(strip_tags($note->getNote()));
		}
		else{
			$this->_title(Mage::helper('masonotes')->__('Add note'));
		}
		if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) { 
			$this->getLayout()->getBlock('head')->setCanLoadTinyMce(true); 
		}
		$this->renderLayout();
	}
	/**
	 * new note action
	 * @access public
	 * @return void
	 * 
	 */
	public function newAction() {
		$this->_forward('edit');
	}
	/**
	 * save note - action
	 * @access public
	 * @return void
	 * 
	 */
	public function saveAction() {
		if ($data = $this->getRequest()->getPost('note')) {
			try {
				$note = $this->_initNote();
				$note->addData($data);
				$products = $this->getRequest()->getPost('customers', -1);
				if ($products != -1) {
					$note->setProductsData(Mage::helper('adminhtml/js')->decodeGridSerializedInput($products));
				}
				$note->save();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('masonotes')->__('Note was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);
				if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $note->getId()));
					return;
				}
				$this->_redirect('*/*/');
				return;
			} 
			catch (Mage_Core_Exception $e){
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				Mage::getSingleton('adminhtml/session')->setFormData($data);
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
				return;
			}
			catch (Exception $e) {
				Mage::logException($e);
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('masonotes')->__('There was a problem saving the note.'));
				Mage::getSingleton('adminhtml/session')->setFormData($data);
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
				return;
			}
		}
		Mage::getSingleton('adminhtml/session')->addError(Mage::helper('masonotes')->__('Unable to find note to save.'));
		$this->_redirect('*/*/');
	}
	/**
	 * delete note - action
	 * @access public
	 * @return void
	 * 
	 */
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0) {
			try {
				$note = Mage::getModel('masonotes/note');
				$note->setId($this->getRequest()->getParam('id'))->delete();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('masonotes')->__('Note was successfully deleted.'));
				$this->_redirect('*/*/');
				return; 
			}
			catch (Mage_Core_Exception $e){
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
			catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('masonotes')->__('There was an error deleteing note.'));
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
				Mage::logException($e);
				return;
			}
		}
		Mage::getSingleton('adminhtml/session')->addError(Mage::helper('masonotes')->__('Could not find note to delete.'));
		$this->_redirect('*/*/');
	}
	/**
	 * mass delete note - action
	 * @access public
	 * @return void
	 * 
	 */
	public function massDeleteAction() {
		$noteIds = $this->getRequest()->getParam('note');
		if(!is_array($noteIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('masonotes')->__('Please select notes to delete.'));
		}
		else {
			try {
				foreach ($noteIds as $noteId) {
					$note = Mage::getModel('masonotes/note');
					$note->setId($noteId)->delete();
				}
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('masonotes')->__('Total of %d notes were successfully deleted.', count($noteIds)));
			}
			catch (Mage_Core_Exception $e){
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			}
			catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('masonotes')->__('There was an error deleteing notes.'));
				Mage::logException($e);
			}
		}
		$this->_redirect('*/*/index');
	}
	/**
	 * mass status change - action
	 * @access public
	 * @return void
	 * 
	 */
	public function massStatusAction(){
		$noteIds = $this->getRequest()->getParam('note');
		if(!is_array($noteIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('masonotes')->__('Please select notes.'));
		} 
		else {
			try {
				foreach ($noteIds as $noteId) {
				$note = Mage::getSingleton('masonotes/note')->load($noteId)
							->setStatus($this->getRequest()->getParam('status'))
							->setIsMassupdate(true)
							->save();
				}
				$this->_getSession()->addSuccess($this->__('Total of %d notes were successfully updated.', count($noteIds)));
			}
			catch (Mage_Core_Exception $e){
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			}
			catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('masonotes')->__('There was an error updating notes.'));
				Mage::logException($e);
			}
		}
		$this->_redirect('*/*/index');
	}
	
	public function massSaveAction()
	{
		$storeId = $this->getRequest()->getParam('store_id');
		$productId = $this->getRequest()->getParam('order_id');
		
		/* @var $model Mas_Masonotes_Model_Resource_Note_Customer */
		$model = Mage::getResourceSingleton('masonotes/note_customer');
		
		/* @var $model Mas_Masonotes_Model_Resource_Note */
		$noteModel = Mage::getResourceSingleton('masonotes/note');
		
		$new = Mage::app()->getRequest()->getParam('mascnotes_new', array());
		
		$userId = Mage::getSingleton('admin/session')->getUser()->getUserId();
		$add = array();
		foreach ($new as $note) {
			if ($note['note'] != '') {
				$note['user_id'] = $userId;
				$add[] = $note;
			}
		}
		
		/*
		 * Add Note
		 */
		$data = array();
		foreach ($add as $note) {
			$newNote = Mage::getModel('masonotes/note');
			$newNote
				->addData($note)
				->setOrderId($productId)
				->setStores($storeId)
				->save();
			$data[] = array(
				'customer_id' => $productId,
				'note_id' => $newNote->getId(),
				'position' => 0,
			);
		}
		$model->saveSingleProductRelation($data);
		
		/* @var $updateModel Mas_Masonotes_Model_Note */
		$updateModel = Mage::getModel('masonotes/note');
		$existing = Mage::app()->getRequest()->getParam('mascnotes_note', array());
		foreach ($existing as $noteId => $note) {
			if (isset($note['delete'])) {
				$model->deleteCustomerRelation($noteId, $productId);
				continue;
			}
			$found = $updateModel->load($noteId);
			if ($found) {
				if ($found->getNote() != $note['note'] || $found->getStatus() != $note['status']) {
					$found->setOrderId($productId);
					$found->addData($note)->save();
				}	
			}
		}
		
		$html = $this->getLayout()->createBlock('masonotes/adminhtml_customer_edit_tab_note')
			->setTemplate('mas_masonotes/list.phtml')
			->toHtml();
		
		$this->getResponse()->setBody($html);
	}
	
	
	
	/**
	 * get grid of products action
	 * @access public
	 * @return void
	 * 
	 */
	public function customersAction(){
		$this->_initNote();
		$this->loadLayout();
		$this->getLayout()->getBlock('note.edit.tab.customer')
			->setNoteCustomers($this->getRequest()->getPost('note_customers', null));
		$this->renderLayout();
	}
	/**
	 * get grid of products action
	 * @access public
	 * @return void
	 * 
	 */
	public function customersgridAction(){
		$this->_initNote();
		$this->loadLayout();
		$this->getLayout()->getBlock('note.edit.tab.customer')
			->setNoteCustomers($this->getRequest()->getPost('note_customers', null));
		$this->renderLayout();
	}
	/**
	 * export as csv - action
	 * @access public
	 * @return void
	 * 
	 */
	public function exportCsvAction(){
		$fileName   = 'note.csv';
		$content	= $this->getLayout()->createBlock('masonotes/adminhtml_note_grid')->getCsv();
		$this->_prepareDownloadResponse($fileName, $content);
	}
	/**
	 * export as MsExcel - action
	 * @access public
	 * @return void
	 * 
	 */
	public function exportExcelAction(){
		$fileName   = 'note.xls';
		$content	= $this->getLayout()->createBlock('masonotes/adminhtml_note_grid')->getExcelFile();
		$this->_prepareDownloadResponse($fileName, $content);
	}
	/**
	 * export as xml - action
	 * @access public
	 * @return void
	 * 
	 */
	public function exportXmlAction(){
		$fileName   = 'note.xml';
		$content	= $this->getLayout()->createBlock('masonotes/adminhtml_note_grid')->getXml();
		$this->_prepareDownloadResponse($fileName, $content);
	}
}
