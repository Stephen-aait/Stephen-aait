<?php

class Modulesgarden_Teamandtaskorganizer_Controller_Action extends Mage_Adminhtml_Controller_Action {

    public function preDispatch() {
        parent::preDispatch();

        if (!Mage::helper('core')->isModuleEnabled('Modulesgarden_Base')) {
            $this->setFlag('', 'no-dispatch', true);
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('modulesgarden_teamandtaskorganizer')->__('Team And Task Organizer requires Modulesgarden Base module'));
            $this->_redirect('adminhtml/dashboard');
            return;
        }
    }

    public function renderLayout($output = '') {
        if ($this->getRequest()->isAjax()) {
            return $this->renderAjaxLayout();
        } else {
            return parent::renderLayout($output);
        }
    }

    public function renderAjaxLayout() {
        $blocks = $this->getLayout()->getBlock('content');
        $blocks->unsetChild('default_task_list');
        echo $blocks->toHtml();
        exit;
    }

    protected function toAjaxResponse($data) {
        $success = true;
        if (isset($data['success'])) {
            $success = $data['success'];
            unset($data['success']);
        }
        $data = array('success' => $success) + $data;
        header('Content-type: application/json');
        echo json_encode($data);
        exit;
    }

}
