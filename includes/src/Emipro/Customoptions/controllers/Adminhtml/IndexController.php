<?php
ini_set("max_execution_time", 0);
class Emipro_Customoptions_Adminhtml_IndexController extends Mage_Adminhtml_Controller_Action {
	protected function _initAction()
		{
				$this->loadLayout()->_setActiveMenu("customer/facility")->_addBreadcrumb(Mage::helper("adminhtml")->__("Facility  Manager"),Mage::helper("adminhtml")->__("Facility Manager"));
				return $this;
		}

    public function indexAction() {
		
		Mage::helper("emipro_customoptions")->validCustomOptionsData();
		
        $this->loadLayout();
        $this->_setActiveMenu('catalog/emipro_customoptions');
        $this->renderLayout();
        $this->_title($this->__("CustomerFacility"));
		$this->_title($this->__("Custom Options Manager"));
		
    }
	protected function _isAllowed()
	{ 
		return Mage::getSingleton('admin/session')->isAllowed('catalog/emipro_customoptions');  
    }
    public function newAction() {
        $this->_forward("edit");
    }
    public function categoriesAction(){
		
		$this->loadLayout();
		$this->renderLayout();
	}
	public function categoriesJsonAction() {
		$this->getResponse()->setBody($this->getLayout()->createBlock('emipro_customoptions/adminhtml_categoryoptions_edit_tab_categories')->getCategoryChildrenJson($this->getRequest()->getParam('category')));
	}
	public function assigntoproductAction() {
		$this->loadLayout();
		$this->_setActiveMenu('catalog/emipro_customoptionsforproducts');
        $this->_title($this->__("Custom Options Manager"));
		$this->_title($this->__("Assign/remove multiple custom options to product(s)"));   
        $this->_addContent($this->getLayout()->createBlock('emipro_customoptions/adminhtml_assigntoproduct_edit'));
        $this->_addLeft($this->getLayout()->createBlock('emipro_customoptions/adminhtml_assigntoproduct_edit_tabs'));
        $this->renderLayout();
    }
	public function assignsaveAction() {
		$product = Mage::getModel('catalog/product');
		$isDelete=$this->getRequest()->getParam("sku");
		$data=$this->getRequest()->getParams();
		
		$skuvalue = str_replace(array("\r\n", "\r"), ",", $data["skulist"]["sku_list"]);
        $skuarray = explode(",", $skuvalue);
        $optionids=$data['custom_option'];
        
			if (count($skuarray) > 0 && !empty($skuarray[0])) {
				try {
					if (!$isDelete)
					{
						$optionModel = Mage::getModel("emipro_customoptions/customoptions");
						foreach ($skuarray as $prodsku) {
							if (!empty($prodsku)) {
								$productId = $product->getIdBySku(trim($prodsku));
								foreach($optionids as $optid){
									if (!empty($productId) && !empty($optid)) {
										$optionModel->saveCustomOption($optid, array($productId));
									}
								}
							}
						}
						Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('emipro_customoptions')->__('Custom Options have been assigned to products.'));
						Mage::getSingleton('adminhtml/session')->setFormData(false);

						$this->_redirect('*/*/assigntoproduct');
						return;
					}
					else
					{
						$optionModel = Mage::getModel("emipro_customoptions/customoptions");
						foreach ($skuarray as $prodsku) {
							if (!empty($prodsku)) {
								$productId = $product->getIdBySku(trim($prodsku));
								foreach($optionids as $optid)
								{
									$optsku = Mage::getModel("emipro_customoptions/product_option")->load($optid)->getData("sku");
									if (!empty($productId) && !empty($optsku)){
										$optionModel->deleteCustomOption($optsku, array($productId));
									}
								}
							}
						}
						Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('emipro_customoptions')->__('Custom options have been removed from products.'));
						Mage::getSingleton('adminhtml/session')->setFormData(false);

						$this->_redirect('*/*/assigntoproduct');
						return;
					}
				}
				catch (Exception $e)
				{
					Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
					Mage::getSingleton('adminhtml/session')->setFormData($data);
					$this->_redirect('*/*/');
					return;
				}
			}
	}
    public function editAction() {
        $optionId = $this->getRequest()->getParam("id");
        $sku=$this->getRequest()->getParam("sku");
        if (isset($optionId)) {
            $optionCollection = Mage::getSingleton("emipro_customoptions/product_option")->getCollection()->addFieldToFilter("main_table.option_id", $optionId);
            $optionCollection->getSelect()
                    ->join(
                            Mage::getConfig()->getTablePrefix() . 'emipro_product_option_title', 'main_table.option_id =' . Mage::getConfig()->getTablePrefix() . 'emipro_product_option_title.option_id', array("title" => "title"))
                    ->join(
                            Mage::getConfig()->getTablePrefix() . 'emipro_product_option_price', 'main_table.option_id =' . Mage::getConfig()->getTablePrefix() . 'emipro_product_option_price.option_id', array("price_type" => "price_type", "price" => "price"))
                    ->group(Mage::getConfig()->getTablePrefix() . "emipro_product_option_title.option_id");

            Mage::register("option_data", $optionCollection);

        }
        $title="Edit Option";
        if(!$optionId)
			{
			  $title="Add new option";
			}
		if($sku)
			{
				$title="Assign/Remove Option";
			}
		
        $this->loadLayout();
        $this->_setActiveMenu('catalog/emipro_customoptions');
        $this->_title($this->__("Custom Options Manager"));
		$this->_title($this->__($title));
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);   
        $this->renderLayout();
       
    }

    public function editSkuAction() {
        $optionId = $this->getRequest()->getParam("id");
        if (isset($optionId)) {
            $optionCollection = Mage::getSingleton("emipro_customoptions/product_option")->getCollection()->addFieldToFilter("main_table.option_id", $optionId);
            $optionCollection->getSelect()
                    ->join(
                            Mage::getConfig()->getTablePrefix() . 'emipro_product_option_title', 'main_table.option_id =' . Mage::getConfig()->getTablePrefix() . 'emipro_product_option_title.option_id', array("title" => "title"))
                    ->join(
                            Mage::getConfig()->getTablePrefix() . 'emipro_product_option_price', 'main_table.option_id =' . Mage::getConfig()->getTablePrefix() . 'emipro_product_option_price.option_id', array("price_type" => "price_type", "price" => "price"))
                    ->group(Mage::getConfig()->getTablePrefix() ."emipro_product_option_title.option_id");

            Mage::register("option_data", $optionCollection);
        }
        $this->loadLayout();
        $this->_setActiveMenu('catalog/emipro_customoptions');
        $this->_title($this->__("Custom Options Manager"));
		$this->_title($this->__("Assign/Remove option to sku list"));
        $this->renderLayout();
    }

    public function editcategoryAction() {
        $optionId = $this->getRequest()->getParam("id");
        if (isset($optionId)) {
            $optionCollection = Mage::getSingleton("emipro_customoptions/product_option")->getCollection()->addFieldToFilter("main_table.option_id", $optionId);
            $optionCollection->getSelect()
                    ->join(
                            Mage::getConfig()->getTablePrefix() . 'emipro_product_option_title', 'main_table.option_id =' . Mage::getConfig()->getTablePrefix() . 'emipro_product_option_title.option_id', array("title" => "title"))
                    ->join(
                            Mage::getConfig()->getTablePrefix() . 'emipro_product_option_price', 'main_table.option_id =' . Mage::getConfig()->getTablePrefix() . 'emipro_product_option_price.option_id', array("price_type" => "price_type", "price" => "price"))
                    ->group(Mage::getConfig()->getTablePrefix() ."emipro_product_option_title.option_id");

            Mage::register("option_data", $optionCollection);
        }

        $this->loadLayout();
        $this->_setActiveMenu('catalog/emipro_customoptions');
        $this->_title($this->__("Custom Options Manager"));
		$this->_title($this->__("Assign/Remove option from category"));
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        $this->renderLayout();
    }

    public function productsAction() {


        $this->loadLayout();
        $this->getLayout()->getBlock('product.grid')
                ->setSelectedProduct($this->getRequest()->getPost('products_id', null));
        $this->renderLayout();
    }

    public function productsgridAction() {
        $this->loadLayout();
        $this->getLayout()->getBlock('product.grid')
                ->setSelectedProduct($this->getRequest()->getPost('products_id', null));
        $this->renderLayout();
    }

    public function deleteAction() {
        $id = $this->getRequest()->getParam("id");
        try {
            $option = Mage::getModel("emipro_customoptions/product_option")->load($id);
			$sku=$option->getSku();
			 $optionModel = Mage::getModel("emipro_customoptions/customoptions");
            $sql = "select product_id from " . Mage::getConfig()->getTablePrefix() . "catalog_product_option where sku='$sku'";
            $results = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($sql);
             foreach ($results as $result) {                   
                        $optionModel->deleteCustomOption($sku, $result["product_id"]);
                    }
            $option->delete();
            Mage::getSingleton("core/session")->addSuccess($this->__("Option has been deleted."));
            $this->_redirect("*/*/");
            return;
        } catch (Exception $e) {
            Mage::getSingleton("core/session")->addError($this->__($e->getMessage()));
        }
    }

    public function saveCategoryAction() {
        $data = $this->getRequest()->getPost();
        $categories = array_unique(explode(",", $data["category_ids"]));
        unset($categories[0]);
        $optionId = $this->getRequest()->getParam("id");
        $sku = Mage::getModel("emipro_customoptions/product_option")->load($optionId)->getData("sku");
        if (isset($categories) && empty($categories)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('emipro_customoptions')->__('Please select category.'));
            Mage::getSingleton('adminhtml/session')->setFormData(false);

            $this->_redirect('*/*/editcategory', array('id' => $optionId, 'sku' => $sku));
            return;
        }
        if ($this->getRequest()->getParam("del")) {
            $this->_redirect('*/*/deletecategory', array('data' => implode(",", $categories), "id" => $optionId, "sku" => $sku));
            return;
        }
        $optionModel = Mage::getModel("emipro_customoptions/customoptions");
        try {
            if (isset($data["category_ids"])) {
                $optionModel->saveToCategory($optionId, $categories, $optionId);
            }
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('emipro_customoptions')->__('Option has been saved.'));
            Mage::getSingleton('adminhtml/session')->setFormData(false);
            if ($this->getRequest()->getParam('back')) {
                $this->_redirect('*/*/editcategory', array('id' => $optionId, 'sku' => $sku));
                return;
            }
            $this->_redirect('*/*/');
            return;
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            Mage::getSingleton('adminhtml/session')->setFormData($data);
            $this->_redirect('*/*/editcategory', array('id' => $this->getRequest()->getParam('id')));
            return;
        }
    }

    public function saveSkuAction() {
        $data = $this->getRequest()->getPost();
        $isDelete=$this->getRequest()->getParam("sku");        
        $optionId = $this->getRequest()->getParam("id");        
        $sku = Mage::getModel("emipro_customoptions/product_option")->load($optionId)->getData("sku");
        if (!$isDelete) {
            $skuvalue = str_replace(array("\r\n", "\r"), ",", $data["skulist"]["sku_list"]);
            $skuarray = explode(",", $skuvalue);
            if (count($skuarray) > 0 && !empty($skuarray[0])) {
                try {
                    $optionModel = Mage::getModel("emipro_customoptions/customoptions");
                    foreach ($skuarray as $prodsku) {
                        if (!empty($prodsku)) {
                            $product = Mage::getModel('catalog/product');
                            $productId = $product->getIdBySku(trim($prodsku));
                            if (!empty($productId)) {
                                $optionModel->saveCustomOption($optionId, array($productId));
                            }
                        }
                    }
                    Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('emipro_customoptions')->__('Option has been assigned.'));
                    Mage::getSingleton('adminhtml/session')->setFormData(false);

                    $this->_redirect('*/*/');
                    return;
                } catch (Exception $e) {
                    Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                    Mage::getSingleton('adminhtml/session')->setFormData($data);
                    $this->_redirect('*/*/');
                    return;
                }
            } else {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('emipro_customoptions')->__('Please enter sku list.'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                $this->_redirect('*/*/edit', array('id' => $optionId));
                return;
            }
        }
        $skurmv = str_replace(array("\r\n", "\r"), ",", $data["skulist"]["sku_list"]);
        $skurmvarray = explode(",", $skurmv);
        if (count($skurmvarray) > 0 && !empty($skurmvarray[0])) {
            try {
                $optionModel = Mage::getModel("emipro_customoptions/customoptions");
                foreach ($skurmvarray as $rmsku) {
                    if (!empty($rmsku)) {
                        $product = Mage::getModel('catalog/product');
                        $productId = $product->getIdBySku(trim($rmsku));
                        if (!empty($productId)) {
                            $optionModel->deleteCustomOption($sku, array($productId));
                        }
                    }
                }


                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('emipro_customoptions')->__('Option has been removed.'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/adminhtml_remove/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        $this->_redirect('*/*/editsku', array('id' => $this->getRequest()->getParam('id')));
        return;
    }

    public function deleteCategoryAction() {
        $data = $this->getRequest()->getParams();
        $categories = array_unique(explode(",", $data["data"]));
        $sku = $data["sku"];
        $optionId = $data["id"];
        try {
            $optionModel = Mage::getModel("emipro_customoptions/customoptions");
            $optionModel->deleteCategory($sku, $categories);
            $write = mage::getsingleton('core/resource')->getconnection('core_write');
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('emipro_customoptions')->__('Option has been deleted from category'));
            Mage::getSingleton('adminhtml/session')->setFormData(false);
            $this->_redirect('*/*/');
            return;
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            Mage::getSingleton('adminhtml/session')->setFormData($data);
            $this->_redirect('*/*/deletecategory', array('data' => $categoryid, "id" => $optionId, "sku" => $sku));
            return;
        }
    }

    public function saveAction() {
        
        $data = $this->getRequest()->getPost();
        $optionId = $this->getRequest()->getParam("id");
        $isSku=$this->getRequest()->getParam("sku");
        $productOption = Mage::getModel("emipro_customoptions/product_option")->load($optionId);
		$sku=$productOption->getData("sku");
	
		 $back = $this->getRequest()->getParam("back");
        $optionModel = Mage::getModel("emipro_customoptions/customoptions");
            $key = array_keys($data["product"]["options"]);
            $data1 = $data["product"]["options"][$key[0]];

            $isAvailable = Mage::getModel("emipro_customoptions/product_option")->load($data1["sku"], "sku")->getId();
            if ($isAvailable && $isAvailable!=$optionId) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('emipro_customoptions')->__($data1['sku'] . " is already available."));
                Mage::getSingleton('adminhtml/session')->setFormData(false);
				if(isset($isSku) || isset($data["links"]))
				{
					$this->_redirect('*/*/edit', array('id' => $optionId, 'sku' => $sku));
                    return;
				}
				
                if(isset($optionId))
				{
					  $this->_redirect('*/*/edit', array('id' => $optionId));
					  return;
				}
                $this->_redirect('*/*/edit');
                Mage::getSingleton('adminhtml/session')->setFormData(true);
                return;
            }
        
			if (isset($data['links'])) {
			
            $products = Mage::helper('adminhtml/js')->decodeGridSerializedInput($data['links']['products']);
            $sql = "select product_id from " . Mage::getConfig()->getTablePrefix() . "catalog_product_option where sku='$sku'";
            $results = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($sql);
            $productsId = array();

            if (!empty($products)) {
                $productsId = array_keys($products);
                $optionModel->saveCustomOption($optionId, $productsId);
                foreach ($results as $result) {

                    if (!in_array($result["product_id"], $productsId)) {
                        $optionModel->deleteCustomOption($sku, $result["product_id"]);
                    }
                }
            } else {

                foreach ($results as $result) {
                    $optionModel->deleteCustomOption($sku, $result["product_id"]);
                }
            }
            Mage::getSingleton("core/session")->addSuccess($this->__("Option has been assigned/remove."));
             if (isset($back)) 
				 {
					 
                    $this->_redirect('*/*/edit', array('id' => $optionId, 'sku' => $sku));
                    return;
                }
                $this->_redirect("*/*/");
                return;
					 
					
        }
		else 
		{
			try {
				$option = Mage::getModel("emipro_customoptions/product_option")->saveOptions($data);
				Mage::dispatchEvent('emipro_customoption_save_after', array("options" => $option));
				$back = $this->getRequest()->getParam("back");
				Mage::getSingleton("core/session")->addSuccess($this->__("Option has been added."));
				if (isset($back)) {
					if (isset($data['links'])) {

						$this->_redirect('*/*/edit', array('id' => $optionId, 'sku' => $sku));
						return;
					}
					$this->_redirect('*/*/edit', array('id' => $option->getId()));
					return;
				}
				$this->_redirect("*/*/");
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				Mage::getSingleton('adminhtml/session')->setFormData($data);
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
				return;
			}
		}
    }

}
