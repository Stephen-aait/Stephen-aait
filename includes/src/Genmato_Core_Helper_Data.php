<?php

/**
 * @category    Genmato
 * @package     Genmato_Core
 * @copyright   Copyright (c) 2013 Genmato BV (http://www.genmato.net)
 */
class Genmato_Core_Helper_Data extends Mage_Adminhtml_Helper_Data
{

    protected $_versiondata = false;

    const CACHE_KEY = 'genmato_core_extension_list';
    const CACHE_TAG = 'GENMATO_CORE';
    const CACHE_TIME= 86400;

    public function debug($msg, $file = 'genmato.log', $level = null, $forceLog = false)
    {
        if (Mage::getStoreConfigFlag('genmato_core/debug/active') || $forceLog) {
            Mage::log($msg, $level, $file, $forceLog);
        }
    }

    public function getLatestExtensionData($name)
    {
        if (!$this->_versiondata) {
            $cache = Mage::app()->getCache();
            $xmldata = $cache->load(self::CACHE_KEY);
            if (!$xmldata) {
                try {
                    $xmldata = $this->requestSslUrl(Mage::getStoreConfig('genmato_core/extension/url'));
                    $cache->save($xmldata, self::CACHE_KEY, array(self::CACHE_TAG), self::CACHE_TIME);
                } catch (Exception $ex) {
                    return false;
                }
            }
            $this->_versiondata = simplexml_load_string($xmldata);
        }
        return (array)$this->_versiondata->$name;
    }

    public function sendJSON($content = array())
    {
        $action = Mage::app()->getFrontController()->getAction();

        $action->getResponse()
            ->setHttpResponseCode(200)
            ->setHeader('Pragma', 'public', true)
            ->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true)
            ->setHeader('Content-type', 'application/json', true)
            ->setHeader('Last-Modified', date('r'));

        $action->getResponse()->setBody(json_encode($content));

        return $action;
    }

    public function sendXML($content = array())
    {
        $action = Mage::app()->getFrontController()->getAction();

        $action->getResponse()
            ->setHttpResponseCode(200)
            ->setHeader('Pragma', 'public', true)
            ->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true)
            ->setHeader('Content-type', 'application/xml', true)
            ->setHeader('Last-Modified', date('r'));

        $action->getResponse()->setBody($content);

        return $action;
    }

    public function registerExtensions()
    {
        try {
            $edition = method_exists('Mage', 'getEdition') ? Mage::getEdition():false;

            if (!$edition) {
                // No function getEdition found, this function is available since CE1.7
                if (version_compare(Mage::getVersion(), '1.7', '>=')) {
                    $edition = 'enterprise';
                } else {
                    $edition = 'community';
                }
            }

            $data = array();
            $data['installation_id'] = Mage::getModel('core/encryption')->encrypt('Genmato');
            $data['version'] = Mage::getVersion();
            $data['edition'] = $edition;
            $data['domain'] = $this->getUrl('/', array('_secure'=>1));
            $data['extensions'] = array();

            $modules = (array)Mage::getConfig()->getNode('modules')->children();
            foreach ($modules as $name => $module) {
                if (substr($name, 0, 7)=='Genmato') {
                    $data['extensions'][$name] = (string)$module->version;
                }
            }
            $url = Mage::getStoreConfig('genmato_core/extension/data');
            $push = (base64_encode(serialize($data)));
            $this->postData($url, array('data'=>$push));
        } catch (Exception $ex) {
            Mage::log($ex->getMessage());
        }
    }

    public function postData($url, $data = array())
    {
        $client = new Zend_Http_Client();
        $client->setUri($url);
        $client->setMethod('POST');
        $client->setConfig(
            array(
                'maxredirects' => 0,
                'timeout'      => 2,
                'adapter' => 'Zend_Http_Client_Adapter_Curl',
                'curloptions' => array(CURLOPT_SSL_VERIFYPEER => false),
            )
        );
        foreach ($data as $param => $val) {
            $client->setParameterPost($param, $val);
        }
        $response = $client->request();

        return $response->getBody();
    }

    public function requestSslUrl($url)
    {
        $client = new Zend_Http_Client();
        $client->setUri($url);
        $client->setMethod('GET');
        $client->setConfig(
            array(
                'maxredirects' => 0,
                'timeout'      => 2,
                'adapter' => 'Zend_Http_Client_Adapter_Curl',
                'curloptions' => array(CURLOPT_SSL_VERIFYPEER => false),
            )
        );
        $response = $client->request();
        return $response->getBody();
    }
}