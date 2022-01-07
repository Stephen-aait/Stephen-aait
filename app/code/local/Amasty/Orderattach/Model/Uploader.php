<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattach
 */


class Amasty_Orderattach_Model_Uploader extends Varien_File_Uploader
{
    protected function _moveFile($tmpPath, $destPath)
    {
        if (is_uploaded_file($tmpPath)) {
            return copy($tmpPath, $destPath);
        }
        else
            return false;
    }
}
