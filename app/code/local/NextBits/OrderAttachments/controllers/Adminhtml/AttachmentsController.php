<?php
class NextBits_OrderAttachments_Adminhtml_AttachmentsController extends Mage_Adminhtml_Controller_Action
{	
	public function saveAttachmentsAction()	{
		$orderAttachmentsCollection = array();
        try {
            $orderId = $this->getRequest()->getParam('order_id');
            $i = 0;
			$attachment = $this->getRequest()->getPost();
            foreach ($attachment as $id => $postData) {
                $orderAttachmentId = substr($id, strlen('order_attachment_'));
                if (empty($orderAttachmentId)) continue;

                // -------------- upload files
                $newFileName = $postData['file'];
				
				// ---------------- set the model data and save

                $orderAttachmentModel = Mage::getModel('orderattachments/orderattachments');
                if ($this->validId($orderId, $orderAttachmentId)) {
                    $orderAttachmentModel->load($orderAttachmentId);
                }
                if (!empty($postData['is_delete']) && $postData['is_delete'] == 1) {
                    $orderAttachmentModel->delete();
                    continue;
                }

                $orderAttachmentModel->setData('order_id', $orderId);
                if (isset($postData['comment'])) {
                    $orderAttachmentModel->setData('comment', $postData['comment']);
                }
                $orderAttachmentModel->setData('visible_customer_account', empty($postData['show'])? 0 : 1);
				$orderAttachmentModel->setData('customer_id', $attachment['customer_id']);
                if (!empty($newFileName)) {
                    $orderAttachmentModel->setData('old_file', $orderAttachmentModel->getData('file'));
                    $orderAttachmentModel->setData('file', $newFileName);
                }
				if($postData['comment'] != "#comment#"){
					$orderAttachmentModel->save();
					$orderAttachmentsCollection[] = $orderAttachmentModel;
				}
                $i++;
            }
			$update = "admin";
			if(Mage::helper('orderattachments')->canNotifyCustomer()) {
				Mage::helper('orderattachments')->sendMail($orderAttachmentsCollection,$update);
			}
            $msg = Mage::helper('orderattachments')->__('Your attachments have been saved');
            $this->getSession()->addSuccess($msg);

        } catch (Exception $e) {
            $msg = Mage::helper('orderattachments')->__('An error occured: %s ', $e->getMessage());
            $this->getSession()->addError($msg);
        }
		Mage::app()->getResponse()->setRedirect(Mage::helper('adminhtml')->getUrl("adminhtml/sales_order/view", array('order_id'=>$orderId)));
	}
	
	private function validId($orderId,$id) {
		$collection = Mage::getModel('orderattachments/orderattachments')->getCollection()
			->addFieldToFilter('order_id', $orderId)
			->addFieldToFilter('order_attachment_id', $id);

		if ($collection) return $collection;
        return false;	 
	}
	
	private function getSession() {
        return Mage::getSingleton('orderattachments/session');
    }
	
	public function showAction() {
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");

		$targetDir = Mage::helper('orderattachments')->getFilePath();
		$cleanupTargetDir = true; // Remove old files
		$maxFileAge = 5 * 3600; // Temp file age in seconds
		if (!file_exists($targetDir)) {
		@mkdir($targetDir);
		}	
		$fileName = rand(1, 10000) . "_";
		if (isset($_REQUEST["name"])) {
			$fileName .= $_FILES["file"]["name"];
		} elseif (!empty($_FILES)) {
			$fileName .= $_FILES["file"]["name"];
		} else {
			$fileName .= $_FILES["file"]["name"];
		}
		
		$filePath = $targetDir . DS . $fileName;
		// Chunking might be enabled
		$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
		$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
		// Remove old temp files	
		if ($cleanupTargetDir) {
			if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
				die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
			}
			while (($file = readdir($dir)) !== false) {
				$tmpfilePath = $targetDir . DS . $file;
				// If temp file is current file proceed to the next
				if ($tmpfilePath == "{$filePath}.part") {
					continue;
				}
				// Remove temp file if it is older than the max age and is not the current file
				if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge)) {
					@unlink($tmpfilePath);
				}
			}
			closedir($dir);
		}	
		// Open temp file
		if (!$out = @fopen("{$filePath}.part", $chunks ? "ab" : "wb")) {
			die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
		}
		if (!empty($_FILES)) {
			if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
				die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
			}
			// Read binary input stream and append it to temp file
			if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
				die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
			}
		} else {	
			if (!$in = @fopen("php://input", "rb")) {
				die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
			}
		}
		while ($buff = fread($in, 4096)) {
			fwrite($out, $buff);
		}
		@fclose($out);
		@fclose($in);
		// Check if file has been uploaded
		if (!$chunks || $chunk == $chunks - 1) {
			// Strip the temp .part suffix off 
			rename("{$filePath}.part", $filePath);
		}
		// Return Success JSON-RPC response
		die($fileName);
	}
}
?>