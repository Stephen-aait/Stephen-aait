<?php

/**
 * Iksanika llc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.iksanika.com/products/IKS-LICENSE.txt
 *
 * @category   Iksanika
 * @package    Iksanika_Productupdater
 * @copyright  Copyright (c) 2013 Iksanika llc. (http://www.iksanika.com)
 * @license    http://www.iksanika.com/products/IKS-LICENSE.txt
 */

include_once "Mage/Adminhtml/controllers/Catalog/ProductController.php";

class Iksanika_Productupdater_Adminhtml_Catalog_ProductController
    extends Mage_Adminhtml_Catalog_ProductController
{
    
    
    /**
     * Save product action
     */
    public function saveAction() 
    {
        if(!Mage::getStoreConfig('productupdater/columns/redirectAdvancedProductManager'))
        {
            parent::saveAction();
        }else
        {

            /**
             * Save product action - ORIGINAL FROM STANDARD MANAGE PRODUCTS
             */
            $storeId        = $this->getRequest()->getParam('store');
            $redirectBack   = $this->getRequest()->getParam('back', false);
            $productId      = $this->getRequest()->getParam('id');
            $isEdit         = (int)($this->getRequest()->getParam('id') != null);

            $data = $this->getRequest()->getPost();
            if ($data) {
                $this->_filterStockData($data['product']['stock_data']);

                $product = $this->_initProductSave();

                try {
                    $product->save();
                    $productId = $product->getId();

                    /**
                     * Do copying data to stores
                     */
                    if (isset($data['copy_to_stores'])) {
                        foreach ($data['copy_to_stores'] as $storeTo=>$storeFrom) {
                            $newProduct = Mage::getModel('catalog/product')
                                ->setStoreId($storeFrom)
                                ->load($productId)
                                ->setStoreId($storeTo)
                                ->save();
                        }
                    }

                    Mage::getModel('catalogrule/rule')->applyAllRulesToProduct($productId);

                    $this->_getSession()->addSuccess($this->__('The product has been saved.'));
                } catch (Mage_Core_Exception $e) {
                    $this->_getSession()->addError($e->getMessage())
                        ->setProductData($data);
                    $redirectBack = true;
                } catch (Exception $e) {
                    Mage::logException($e);
                    $this->_getSession()->addError($e->getMessage());
                    $redirectBack = true;
                }
            }

            if ($redirectBack) {
                $this->_redirect('*/*/edit', array(
                    'id'    => $productId,
                    '_current'=>true
                ));
            } elseif($this->getRequest()->getParam('popup')) {
                $this->_redirect('*/*/created', array(
                    '_current'   => true,
                    'id'         => $productId,
                    'edit'       => $isEdit
                ));
            } else {
                $this->_redirect('adminhtml/productupdater/index', array('store'=>$storeId));
            }

        }
    }
    
    
    
    /**
     * Delete product action
     */
    public function deleteAction()
    {
        if(!Mage::getStoreConfig('productupdater/columns/redirectAdvancedProductManager'))
        {
            parent::deleteAction();
        }else
        {

            /**
             * Delete product action - ORIGINAL FROM STANDARD MANAGE PRODUCTS
             */
            if ($id = $this->getRequest()->getParam('id')) {
                $product = Mage::getModel('catalog/product')
                    ->load($id);
                $sku = $product->getSku();
                try {
                    $product->delete();
                    $this->_getSession()->addSuccess($this->__('The product has been deleted.'));
                } catch (Exception $e) {
                    $this->_getSession()->addError($e->getMessage());
                }
            }
            $this->getResponse()
                ->setRedirect($this->getUrl('adminhtml/productupdater/index', array('store'=>$this->getRequest()->getParam('store'))));
        }

    }

}
