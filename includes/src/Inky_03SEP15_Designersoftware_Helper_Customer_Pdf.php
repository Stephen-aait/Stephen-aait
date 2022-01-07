<?php

class Inky_Designersoftware_Helper_Customer_Pdf extends Mage_Core_Helper_Abstract
{   
	public function createDirectory($dirName) {
        if (!is_dir($dirName)) {
            @mkdir($dirName);
            @chmod($dirName, 0777);
        }
    }
}
