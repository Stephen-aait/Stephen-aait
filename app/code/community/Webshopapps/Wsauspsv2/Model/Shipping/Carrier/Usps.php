<?php

/**
 * WebShopApps Shipping Module
 *
 * @category    WebShopApps
 * @package     WebShopApps_USPSV2
 * User         Joshua Stewart
 * Date         24/07/2013
 * Time         12:14
 * @copyright   Copyright (c) 2013 Zowta Ltd (http://www.WebShopApps.com)
 *              Copyright, 2013, Zowta, LLC - US license
 * @license     http://www.WebShopApps.com/license/license.txt - Commercial license
 *
 */
class Webshopapps_Wsauspsv2_Model_Shipping_Carrier_Usps extends Mage_Usa_Model_Shipping_Carrier_Usps
{

    /**
     * Form XML request object and submit request
     *
     */
    protected function _getXmlQuotes()
    {
        $r = $this->_rawRequest;
        // The origin address(shipper) must be only in USA

        if ($r->getSize() == 'LARGE') {
            if($r->getWidth() + $r->getLength() + $r->getHeight() == 0){
                $r->setWidth(Mage::getStoreConfig('carriers/usps/width'));
                $r->setLength(Mage::getStoreConfig('carriers/usps/length'));
                $r->setHeight(Mage::getStoreConfig('carriers/usps/height'));
                $r->setGirth(Mage::getStoreConfig('carriers/usps/girth'));
            }
        }

        if ($this->_isUSCountry($r->getDestCountryId())) {
            $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><RateV4Request/>');
            $xml->addAttribute('USERID', $r->getUserId());
            // according to usps v4 documentation
            $xml->addChild('Revision', '2');

            $package = $xml->addChild('Package');
            $package->addAttribute('ID', 0);
            $service = $this->getCode('service_to_code', $r->getService());
            if (!$service) {
                $service = $r->getService();
            }
            if ($r->getContainer() == 'FLAT RATE BOX' || $r->getContainer() == 'FLAT RATE ENVELOPE') {
                $service = 'PRIORITY';
            }
            $package->addChild('Service', $service);

            // no matter Letter, Flat or Parcel, use Parcel
            if ($r->getService() == 'FIRST CLASS' || $r->getService() == 'FIRST CLASS HFP COMMERCIAL') {
                $package->addChild('FirstClassMailType', 'PARCEL');
            }
            $package->addChild('ZipOrigination', $r->getOrigPostal());
            //only 5 chars avaialble
            $package->addChild('ZipDestination', substr($r->getDestPostal(), 0, 5));
            $package->addChild('Pounds', $r->getWeightPounds());
            $package->addChild('Ounces', $r->getWeightOunces());
            // Because some methods don't accept VARIABLE and (NON)RECTANGULAR containers
            $package->addChild('Container', $r->getContainer());
            $package->addChild('Size', $r->getSize());
            if ($r->getSize() == 'LARGE') {
                $package->addChild('Width', $r->getWidth());
                $package->addChild('Length', $r->getLength());
                $package->addChild('Height', $r->getHeight());
                if ($r->getContainer() == 'NONRECTANGULAR' || $r->getContainer() == 'VARIABLE') {
                    $package->addChild('Girth', $r->getGirth());
                }
            }
            $package->addChild('Machinable', $r->getMachinable());

            $api = 'RateV4';
        } else {
            $xml = new SimpleXMLElement('<?xml version = "1.0" encoding = "UTF-8"?><IntlRateV2Request/>');
            $xml->addAttribute('USERID', $r->getUserId());
            // according to usps v4 documentation
            $xml->addChild('Revision', '2');

            $package = $xml->addChild('Package');
            $package->addAttribute('ID', 0);
            $package->addChild('Pounds', $r->getWeightPounds());
            $package->addChild('Ounces', $r->getWeightOunces());
            $package->addChild('MailType', 'All');
            $package->addChild('ValueOfContents', $r->getValue());
            $package->addChild('Country', $r->getDestCountryName());
            $package->addChild('Container', $r->getContainer());
            $package->addChild('Size', $r->getSize());
            $width = $length = $height = $girth = '';
            if ($r->getSize() == 'LARGE') {
                $width = $r->getWidth();
                $length = $r->getLength();
                $height = $r->getHeight();
                if ($r->getContainer() == 'NONRECTANGULAR') {
                    $girth = $r->getGirth();
                }
            }
            $package->addChild('Width', $width);
            $package->addChild('Length', $length);
            $package->addChild('Height', $height);
            $package->addChild('Girth', $girth);
            $package->addChild('OriginZip', $r->getOrigPostal());

            $api = 'IntlRateV2';
        }
        $request = $xml->asXML();

        $debugData = array('request' => $request);

        try {
            $url = $this->getConfigData('gateway_url');
            if (!$url) {
                $url = $this->_defaultGatewayUrl;
            }
            $client = new Zend_Http_Client();
            $client->setUri($url);
            $client->setConfig(array('maxredirects'=>0, 'timeout'=>30));
            $client->setParameterGet('API', $api);
            $client->setParameterGet('XML', $request);
            $response = $client->request();
            $responseBody = $response->getBody();
            $debugData['result'] = $responseBody;
        }
        catch (Exception $e) {
            $debugData['result'] = array('error' => $e->getMessage(), 'code' => $e->getCode());
            $responseBody = '';
        }

        $this->_debug($debugData);

        //  $this->_debug($debugData);
        return $this->_parseXmlResponse($responseBody);;
    }

    /**
     * Get allowed shipping methods - Override to make sure WSA model is used to get allowed methods.
     *
     * @return array
     */
    public function getAllowedMethods()
    {
        $allowed = explode(',', $this->getConfigData('allowed_methods'));
        $arr = array();
        foreach ($allowed as $k) {
            $arr[$k] = $this->getCode('method', $k);
        }
        return $arr;
    }

    /**
     * Parse calculated rates
     *
     * @link http://www.usps.com/webtools/htm/Rate-Calculators-v2-3.htm
     * @param string $response
     * @return Mage_Shipping_Model_Rate_Result
     */
    protected function _parseXmlResponse($response)
    {
        $costArr = array();
        $priceArr = array();
        if (strlen(trim($response)) > 0) {
            if (strpos(trim($response), '<?xml') === 0) {
                if (strpos($response, '<?xml version="1.0"?>') !== false) {
                    $response = str_replace(
                        '<?xml version="1.0"?>',
                        '<?xml version="1.0" encoding="ISO-8859-1"?>',
                        $response
                    );
                }

                $xml = simplexml_load_string($response);

                if (is_object($xml)) {
                    if (is_object($xml->Number) && is_object($xml->Description) && (string)$xml->Description!='') {
                        $errorTitle = (string)$xml->Description;
                    } elseif (is_object($xml->Package)
                        && is_object($xml->Package->Error)
                        && is_object($xml->Package->Error->Description)
                        && (string)$xml->Package->Error->Description!=''
                    ) {
                        $errorTitle = (string)$xml->Package->Error->Description;
                    }

                    $r = $this->_rawRequest;
                    $allowedMethods = explode(",", $this->getConfigData('allowed_methods'));
                    $allMethods = $this->getCode('method');
                    $newMethod = false;

                    /**
                     * WSA Changes START - strip time in transit estimate to compare with allowed methods
                     */

                    $magentoVersion = Mage::helper('wsalogger')->getNewVersion();

                    if($magentoVersion > 8) {
                        $isUs = $this->_isUSCountry($r->getDestCountryId());
                    } else {
                        $isUs = $r->getDestCountryId() == self::USA_COUNTRY_ID || $r->getDestCountryId() == self::PUERTORICO_COUNTRY_ID;
                    }

                    if ($isUs) {
                        if (is_object($xml->Package) && is_object($xml->Package->Postage)) {
                            foreach ($xml->Package->Postage as $postage) {

                                if($magentoVersion > 9) {
                                    $basicName = $this->_filterServiceName((string)$postage->MailService);
                                } else {
                                    $basicName = Mage::helper('webshopapps_wsauspsv2')->filterServiceName((string)$postage->MailService);
                                }

                                $serviceName = $this->stripTimeStamp($basicName);

                                /**
                                 * WSA Changes END
                                 */

                                $postage->MailService = $serviceName;
                                if (in_array($serviceName, $allowedMethods)) {
                                    $costArr[$serviceName] = (string)$postage->Rate;
                                    $priceArr[$serviceName] = $this->getMethodPrice(
                                        (string)$postage->Rate,
                                        $serviceName
                                    );
                                } elseif (!in_array($serviceName, $allMethods)) {
                                    $allMethods[] = $serviceName;
                                    $newMethod = true;
                                }
                            }
                            asort($priceArr);
                        }
                    } else {
                        /*
                         * International Rates
                         */
                        if (is_object($xml->Package) && is_object($xml->Package->Service)) {
                            foreach ($xml->Package->Service as $service) {

                                if($magentoVersion > 9) {
                                    $serviceName = $this->_filterServiceName((string)$service->SvcDescription);
                                } else {
                                    $serviceName = Mage::helper('webshopapps_wsauspsv2')->filterServiceName((string)$service->SvcDescription);
                                }
                                $service->SvcDescription = $serviceName;
                                if (in_array($serviceName, $allowedMethods)) {
                                    $costArr[$serviceName] = (string)$service->Postage;
                                    $priceArr[$serviceName] = $this->getMethodPrice(
                                        (string)$service->Postage,
                                        $serviceName
                                    );
                                } elseif (!in_array($serviceName, $allMethods)) {
                                    $allMethods[] = $serviceName;
                                    $newMethod = true;
                                }
                            }
                            asort($priceArr);
                        }
                    }
                    /**
                     * following if statement is obsolete
                     * we don't have adminhtml/config resoure model
                     */
                    if (false && $newMethod) {
                        sort($allMethods);
                        $insert['usps']['fields']['methods']['value'] = $allMethods;
                        Mage::getResourceModel('adminhtml/config')->saveSectionPost('carriers','','',$insert);
                    }
                }
            }

        }

        $result = Mage::getModel('shipping/rate_result');
        if (empty($priceArr)) {
            $error = Mage::getModel('shipping/rate_result_error');
            $error->setCarrier('usps');
            $error->setCarrierTitle($this->getConfigData('title'));
            $error->setErrorMessage($this->getConfigData('specificerrmsg'));
            $result->append($error);
        } else {
            foreach ($priceArr as $method=>$price) {
                $rate = Mage::getModel('shipping/rate_result_method');
                $rate->setCarrier('usps');
                $rate->setCarrierTitle($this->getConfigData('title'));
                $rate->setMethod($method);
                $rate->setMethodTitle(Mage::helper('usa')->__($method));
                $rate->setCost($costArr[$method]);
                $rate->setPrice($price);
                $result->append($rate);
            }
        }

        return $result;
    }

    protected function stripTimeStamp($name){
        $search = array(' 1-Day',' 2-Day',' 3-Day',' Military',' DPO');

        $name = str_replace($search, '', $name);

        return $name;
    }

    /**
     * Get configuration data of carrier - WSA updated with new methods from July API update
     *
     * @param string $type
     * @param string $code
     * @return array|bool
     */
    public function getCode($type, $code='')
    {
        $codes = array(

            'service'=>array(
                'FIRST CLASS' => Mage::helper('usa')->__('First-Class'),
                'PRIORITY'    => Mage::helper('usa')->__('Priority Mail'),
                'EXPRESS'     => Mage::helper('usa')->__('Express Mail'),
                'BPM'         => Mage::helper('usa')->__('Bound Printed Matter'),
                'PARCEL'      => Mage::helper('usa')->__('Parcel Post'),
                'MEDIA'       => Mage::helper('usa')->__('Media Mail Parcel'),
                'LIBRARY'     => Mage::helper('usa')->__('Library Mail Parcel'),
            ),

            'service_to_code'=>array(
                'First-Class'                                                           => 'FIRST CLASS',
                'First-Class Mail International Large Envelope'                         => 'FIRST CLASS',
                'First-Class Mail International Letter'                                 => 'FIRST CLASS',
                'First-Class Mail International Package'                                => 'FIRST CLASS',
                'First-Class Mail International Parcel'                                 => 'FIRST CLASS',
                'First-Class Mail'                                                      => 'FIRST CLASS',
                'First-Class Mail Flat'                                                 => 'FIRST CLASS',
                'First-Class Mail Large Envelope'                                       => 'FIRST CLASS',
                'First-Class Mail International'                                        => 'FIRST CLASS',
                'First-Class Mail Letter'                                               => 'FIRST CLASS',
                'First-Class Mail Parcel'                                               => 'FIRST CLASS',
                'First-Class Mail Package'                                              => 'FIRST CLASS',
                'Standard Post'                                                         => 'STANDARD POST',//Todo: Remove in next release
                'USPS Retail Ground'                                                    => 'STANDARD POST',
                'Bound Printed Matter'                                                  => 'BPM',
                'Media Mail Parcel'                                                     => 'MEDIA',
                'Library Mail Parcel'                                                   => 'LIBRARY',
                'Priority Mail Express'                                                 => 'EXPRESS',
                'Priority Mail Express PO to PO'                                        => 'EXPRESS',
                'Priority Mail Express Flat Rate Envelope'                              => 'EXPRESS',
                'Priority Mail Express Flat-Rate Envelope Sunday/Holiday Guarantee'     => 'EXPRESS',
                'Priority Mail Express Sunday/Holiday Guarantee'                        => 'EXPRESS',
                'Priority Mail Express Flat Rate Envelope Hold For Pickup'              => 'EXPRESS',
                'Priority Mail Express Hold For Pickup'                                 => 'EXPRESS',
                'Global Express Guaranteed (GXG)'                                       => 'EXPRESS',
                'Global Express Guaranteed Non-Document Rectangular'                    => 'EXPRESS',
                'Global Express Guaranteed Non-Document Non-Rectangular'                => 'EXPRESS',
                'USPS GXG Envelopes'                                                    => 'EXPRESS',
                'Priority Mail Express International'                                   => 'EXPRESS',
                'Priority Mail Express International Flat Rate Envelope'                => 'EXPRESS',
                'Priority Mail Express International Legal Flat Rate Envelope'          => 'EXPRESS',
                'Priority Mail Express International Padded Flat Rate Envelope'         => 'EXPRESS',
                'Priority Mail Express International Flat Rate Boxes'                   => 'EXPRESS',
                'Priority Mail'                                                         => 'PRIORITY',
                'Priority Mail Small Flat Rate Box'                                     => 'PRIORITY',
                'Priority Mail Medium Flat Rate Box'                                    => 'PRIORITY',
                'Priority Mail Large Flat Rate Box'                                     => 'PRIORITY',
                'Priority Mail Flat Rate Box'                                           => 'PRIORITY',
                'Priority Mail Flat Rate Envelope'                                      => 'PRIORITY',
                'Priority Mail International'                                           => 'PRIORITY',
                'Priority Mail International Flat Rate Envelope'                        => 'PRIORITY',
                'Priority Mail International Small Flat Rate Box'                       => 'PRIORITY',
                'Priority Mail International Medium Flat Rate Box'                      => 'PRIORITY',
                'Priority Mail International Large Flat Rate Box'                       => 'PRIORITY',
                'Priority Mail International Flat Rate Box'                             => 'PRIORITY',
            ),

            'first_class_mail_type'=>array(
                'LETTER'      => Mage::helper('usa')->__('Letter'),
                'FLAT'        => Mage::helper('usa')->__('Flat'),
                'PARCEL'      => Mage::helper('usa')->__('Parcel'),
            ),

            'container'=>array(
                'VARIABLE'           => Mage::helper('usa')->__('Variable'),
                'FLAT RATE BOX'      => Mage::helper('usa')->__('Flat-Rate Box'),
                'FLAT RATE ENVELOPE' => Mage::helper('usa')->__('Flat-Rate Envelope'),
                'RECTANGULAR'        => Mage::helper('usa')->__('Rectangular'),
                'NONRECTANGULAR'     => Mage::helper('usa')->__('Non-rectangular'),
            ),

            'containers_filter' => array(
                array(
                    'containers' => array('VARIABLE'),
                    'filters'    => array(
                        'within_us' => array(
                            'method' => array(
                                'First-Class Mail Large Envelope',
                                'First-Class Mail Letter',
                                'First-Class Mail Parcel',
                                'First-Class Mail Postcards',
                                'Priority Mail',
                                'Priority Mail Express Hold For Pickup',
                                'Priority Mail Express',
                                'Standard Post',
                                'USPS Retail Ground',
                                'Media Mail Parcel',
                                'Library Mail Parcel',
                                'Priority Mail Express Flat Rate Envelope',
                                'First-Class Mail Large Postcards',
                                'Priority Mail Flat Rate Envelope',
                                'Priority Mail Medium Flat Rate Box',
                                'Priority Mail Large Flat Rate Box',
                                'Priority Mail Express Sunday/Holiday Delivery',
                                'Priority Mail Express Sunday/Holiday Delivery Flat Rate Envelope',
                                'Priority Mail Express Flat Rate Envelope Hold For Pickup',
                                'Priority Mail Small Flat Rate Box',
                                'Priority Mail Padded Flat Rate Envelope',
                                'Priority Mail Express Legal Flat Rate Envelope',
                                'Priority Mail Express Legal Flat Rate Envelope Hold For Pickup',
                                'Priority Mail Express Sunday/Holiday Delivery Legal Flat Rate Envelope',
                                'Priority Mail Hold For Pickup',
                                'Priority Mail Large Flat Rate Box Hold For Pickup',
                                'Priority Mail Medium Flat Rate Box Hold For Pickup',
                                'Priority Mail Small Flat Rate Box Hold For Pickup',
                                'Priority Mail Flat Rate Envelope Hold For Pickup',
                                'Priority Mail Gift Card Flat Rate Envelope',
                                'Priority Mail Gift Card Flat Rate Envelope Hold For Pickup',
                                'Priority Mail Window Flat Rate Envelope',
                                'Priority Mail Window Flat Rate Envelope Hold For Pickup',
                                'Priority Mail Small Flat Rate Envelope',
                                'Priority Mail Small Flat Rate Envelope Hold For Pickup',
                                'Priority Mail Legal Flat Rate Envelope',
                                'Priority Mail Legal Flat Rate Envelope Hold For Pickup',
                                'Priority Mail Padded Flat Rate Envelope Hold For Pickup',
                                'Priority Mail Regional Rate Box A',
                                'Priority Mail Regional Rate Box A Hold For Pickup',
                                'Priority Mail Regional Rate Box B',
                                'Priority Mail Regional Rate Box B Hold For Pickup',
                                'First-Class Package Service Hold For Pickup',
                                'Priority Mail Express Flat Rate Boxes',
                                'Priority Mail Express Flat Rate Boxes Hold For Pickup',
                                'Priority Mail Express Sunday/Holiday Delivery Flat Rate Boxes',
                                'Priority Mail Regional Rate Box C',
                                'Priority Mail Regional Rate Box C Hold For Pickup',
                                'First-Class Package Service',
                                'Priority Mail Express Padded Flat Rate Envelope',
                                'Priority Mail Express Padded Flat Rate Envelope Hold For Pickup',
                                'Priority Mail Express Sunday/Holiday Delivery Padded Flat Rate Envelope',
                            )
                        ),
                        'from_us' => array(
                            'method' => array(
                                'Priority Mail International Flat Rate Envelope',
                                'Priority Mail International Large Flat Rate Box',
                                'Priority Mail International Medium Flat Rate Box',
                                'Priority Mail International Small Flat Rate Box',
                                'Global Express Guaranteed (GXG)',
                                'USPS GXG Envelopes',
                                'Priority Mail International',
                                'First-Class Mail International Package',
                                'First-Class Mail International Large Envelope',
                                'First-Class Mail International Parcel',
                                'Priority Mail Express International',
                                'Priority Mail Express International Flat Rate Envelope',
                                'Priority Mail Express International Legal Flat Rate Envelope',
                                'Priority Mail Express International Padded Flat Rate Envelope',
                                'Priority Mail Express International Flat Rate Boxes',
                            )
                        )
                    )
                ),
                array(
                    'containers' => array('FLAT RATE BOX'),
                    'filters'    => array(
                        'within_us' => array(
                            'method' => array(
                                'Priority Mail Large Flat Rate Box',
                                'Priority Mail Medium Flat Rate Box',
                                'Priority Mail Small Flat Rate Box',

                                'Priority Mail Regional Rate Box A',
                                'Priority Mail Regional Rate Box B',
                                'Priority Mail Regional Rate Box C',
                            )
                        ),
                        'from_us' => array(
                            'method' => array(
                                'Priority Mail International Large Flat Rate Box',
                                'Priority Mail International Medium Flat Rate Box',
                                'Priority Mail International Small Flat Rate Box',
                            )
                        )
                    )
                ),
                array(
                    'containers' => array('FLAT RATE ENVELOPE'),
                    'filters'    => array(
                        'within_us' => array(
                            'method' => array(
                                'Priority Mail Flat Rate Envelope',
                                'Priority Mail Padded Flat Rate Envelope',
                                'Priority Mail Small Flat Rate Envelope',
                                'Priority Mail Legal Flat Rate Envelope',
                                'Priority Mail Express Flat Rate Envelope',
                                'Priority Mail Express Padded Flat Rate Envelope',
                                'Priority Mail Express Legal Flat Rate Envelope',
                            )
                        ),
                        'from_us' => array(
                            'method' => array(
                                'Express Mail International Flat Rate Envelope',
                                'Priority Mail International Flat Rate Envelope',
                            )
                        )
                    )
                ),
                array(
                    'containers' => array('RECTANGULAR'),
                    'filters'    => array(
                        'within_us' => array(
                            'method' => array(
                                'Priority Mail Express',
                                'Priority Mail',
                                'Standard Post',
                                'USPS Retail Ground',
                                'Media Mail Parcel',
                            )
                        ),
                        'from_us' => array(
                            'method' => array(
                                'USPS GXG Envelopes',
                                'Express Mail International',
                                'Priority Mail International',
                                'First-Class Mail International Package',
                                'First-Class Mail International Parcel',
                            )
                        )
                    )
                ),
                array(
                    'containers' => array('NONRECTANGULAR'),
                    'filters'    => array(
                        'within_us' => array(
                            'method' => array(
                                'Priority Mail Express',
                                'Priority Mail',
                                'Standard Post',
                                'USPS Retail Ground',
                                'Media Mail Parcel',
                            )
                        ),
                        'from_us' => array(
                            'method' => array(
                                'Global Express Guaranteed (GXG)',
                                'USPS GXG Envelopes',
                                'Express Mail International',
                                'Priority Mail International',
                                'First-Class Mail International Package',
                                'First-Class Mail International Parcel',
                            )
                        )
                    )
                ),
            ),

            'size'=>array(
                'REGULAR'     => Mage::helper('usa')->__('Regular'),
                'LARGE'       => Mage::helper('usa')->__('Large'),
            ),

            'machinable'=>array(
                'true'        => Mage::helper('usa')->__('Yes'),
                'false'       => Mage::helper('usa')->__('No'),
            ),

            'delivery_confirmation_types' => array(
                'True' => Mage::helper('usa')->__('Not Required'),
                'False'  => Mage::helper('usa')->__('Required'),
            ),
        );

        $methods = $this->getConfigData('methods');
        if (!empty($methods)) {
            $methods = explode(",", $methods);
            $finalMethods = array();

            foreach ($methods as $method) {
                $finalMethods[$method] = $method;
            }

            $codes['method'] = $finalMethods;
        } else {
            $codes['method'] = array();
        }

        if (!isset($codes[$type])) {
            return false;
        } elseif (''===$code) {
            return $codes[$type];
        }

        if (!isset($codes[$type][$code])) {
            return false;
        } else {
            return $codes[$type][$code];
        }
    }

    /**
     * Return USPS county name by country ISO 3166-1-alpha-2 code
     * Return false for unknown countries
     *
     * @param string $countryId
     * @return string|false
     */
    protected function _getCountryName($countryId)
    {
        $countries = array (
            'AD' => 'Andorra',
            'AE' => 'United Arab Emirates',
            'AF' => 'Afghanistan',
            'AG' => 'Antigua and Barbuda',
            'AI' => 'Anguilla',
            'AL' => 'Albania',
            'AM' => 'Armenia',
            'AN' => 'Netherlands Antilles',
            'AO' => 'Angola',
            'AR' => 'Argentina',
            'AT' => 'Austria',
            'AU' => 'Australia',
            'AW' => 'Aruba',
            'AX' => 'Aland Island (Finland)',
            'AZ' => 'Azerbaijan',
            'BA' => 'Bosnia-Herzegovina',
            'BB' => 'Barbados',
            'BD' => 'Bangladesh',
            'BE' => 'Belgium',
            'BF' => 'Burkina Faso',
            'BG' => 'Bulgaria',
            'BH' => 'Bahrain',
            'BI' => 'Burundi',
            'BJ' => 'Benin',
            'BM' => 'Bermuda',
            'BN' => 'Brunei Darussalam',
            'BO' => 'Bolivia',
            'BR' => 'Brazil',
            'BS' => 'Bahamas',
            'BT' => 'Bhutan',
            'BW' => 'Botswana',
            'BY' => 'Belarus',
            'BZ' => 'Belize',
            'CA' => 'Canada',
            'CC' => 'Cocos Island (Australia)',
            'CD' => 'Congo, Democratic Republic of the',
            'CF' => 'Central African Republic',
            'CG' => 'Congo, Republic of the',
            'CH' => 'Switzerland',
            'CI' => 'Ivory Coast (Cote d Ivoire)',
            'CK' => 'Cook Islands (New Zealand)',
            'CL' => 'Chile',
            'CM' => 'Cameroon',
            'CN' => 'China',
            'CO' => 'Colombia',
            'CR' => 'Costa Rica',
            'CU' => 'Cuba',
            'CV' => 'Cape Verde',
            'CW' => 'Curacao',
            'CX' => 'Christmas Island (Australia)',
            'CY' => 'Cyprus',
            'CZ' => 'Czech Republic',
            'DE' => 'Germany',
            'DJ' => 'Djibouti',
            'DK' => 'Denmark',
            'DM' => 'Dominica',
            'DO' => 'Dominican Republic',
            'DZ' => 'Algeria',
            'EC' => 'Ecuador',
            'EE' => 'Estonia',
            'EG' => 'Egypt',
            'ER' => 'Eritrea',
            'ES' => 'Spain',
            'ET' => 'Ethiopia',
            'FI' => 'Finland',
            'FJ' => 'Fiji',
            'FK' => 'Falkland Islands',
            'FM' => 'Micronesia, Federated States of',
            'FO' => 'Faroe Islands',
            'FR' => 'France',
            'GA' => 'Gabon',
            'GB' => 'Great Britain and Northern Ireland',
            'GD' => 'Grenada',
            'GE' => 'Georgia, Republic of',
            'GF' => 'French Guiana',
            'GH' => 'Ghana',
            'GI' => 'Gibraltar',
            'GL' => 'Greenland',
            'GM' => 'Gambia',
            'GN' => 'Guinea',
            'GP' => 'Guadeloupe',
            'GQ' => 'Equatorial Guinea',
            'GR' => 'Greece',
            'GS' => 'South Georgia (Falkland Islands)',
            'GT' => 'Guatemala',
            'GW' => 'Guinea-Bissau',
            'GY' => 'Guyana',
            'HK' => 'Hong Kong',
            'HN' => 'Honduras',
            'HR' => 'Croatia',
            'HT' => 'Haiti',
            'HU' => 'Hungary',
            'ID' => 'Indonesia',
            'IE' => 'Ireland',
            'IL' => 'Israel',
            'IN' => 'India',
            'IQ' => 'Iraq',
            'IR' => 'Iran',
            'IS' => 'Iceland',
            'IT' => 'Italy',
            'JM' => 'Jamaica',
            'JO' => 'Jordan',
            'JP' => 'Japan',
            'KE' => 'Kenya',
            'KG' => 'Kyrgyzstan',
            'KH' => 'Cambodia',
            'KI' => 'Kiribati',
            'KM' => 'Comoros',
            'KN' => 'Saint Kitts (Saint Christopher and Nevis)',
            'KP' => 'North Korea (Korea, Democratic People\'s Republic of)',
            'KR' => 'South Korea (Korea, Republic of)',
            'KW' => 'Kuwait',
            'KY' => 'Cayman Islands',
            'KZ' => 'Kazakhstan',
            'LA' => 'Laos',
            'LB' => 'Lebanon',
            'LC' => 'Saint Lucia',
            'LI' => 'Liechtenstein',
            'LK' => 'Sri Lanka',
            'LR' => 'Liberia',
            'LS' => 'Lesotho',
            'LT' => 'Lithuania',
            'LU' => 'Luxembourg',
            'LV' => 'Latvia',
            'LY' => 'Libya',
            'MA' => 'Morocco',
            'MC' => 'Monaco (France)',
            'MD' => 'Moldova',
            'ME' => 'Montenegro',
            'MG' => 'Madagascar',
            'MK' => 'Macedonia, Republic of',
            'ML' => 'Mali',
            'MM' => 'Burma',
            'MN' => 'Mongolia',
            'MO' => 'Macao',
            'MQ' => 'Martinique',
            'MR' => 'Mauritania',
            'MS' => 'Montserrat',
            'MT' => 'Malta',
            'MU' => 'Mauritius',
            'MV' => 'Maldives',
            'MW' => 'Malawi',
            'MX' => 'Mexico',
            'MY' => 'Malaysia',
            'MZ' => 'Mozambique',
            'NA' => 'Namibia',
            'NC' => 'New Caledonia',
            'NE' => 'Niger',
            'NG' => 'Nigeria',
            'NI' => 'Nicaragua',
            'NL' => 'Netherlands',
            'NO' => 'Norway',
            'NP' => 'Nepal',
            'NR' => 'Nauru',
            'NZ' => 'New Zealand',
            'OM' => 'Oman',
            'PA' => 'Panama',
            'PE' => 'Peru',
            'PF' => 'French Polynesia',
            'PG' => 'Papua New Guinea',
            'PH' => 'Philippines',
            'PK' => 'Pakistan',
            'PL' => 'Poland',
            'PM' => 'Saint Pierre and Miquelon',
            'PN' => 'Pitcairn Island',
            'PT' => 'Portugal',
            'PY' => 'Paraguay',
            'QA' => 'Qatar',
            'RE' => 'Reunion',
            'RO' => 'Romania',
            'RS' => 'Serbia',
            'RU' => 'Russia',
            'RW' => 'Rwanda',
            'SA' => 'Saudi Arabia',
            'SB' => 'Solomon Islands',
            'SC' => 'Seychelles',
            'SD' => 'Sudan',
            'SE' => 'Sweden',
            'SG' => 'Singapore',
            'SH' => 'Saint Helena',
            'SI' => 'Slovenia',
            'SK' => 'Slovak Republic',
            'SL' => 'Sierra Leone',
            'SM' => 'San Marino',
            'SN' => 'Senegal',
            'SO' => 'Somalia',
            'SR' => 'Suriname',
            'ST' => 'Sao Tome and Principe',
            'SV' => 'El Salvador',
            'SY' => 'Syrian Arab Republic',
            'SZ' => 'Swaziland',
            'TC' => 'Turks and Caicos Islands',
            'TD' => 'Chad',
            'TG' => 'Togo',
            'TH' => 'Thailand',
            'TJ' => 'Tajikistan',
            'TK' => 'Tokelau (Western Samoa)',
            'TL' => 'East Timor (Timor-Leste, Democratic Republic of)',
            'TM' => 'Turkmenistan',
            'TN' => 'Tunisia',
            'TO' => 'Tonga',
            'TR' => 'Turkey',
            'TT' => 'Trinidad and Tobago',
            'TV' => 'Tuvalu',
            'TW' => 'Taiwan',
            'TZ' => 'Tanzania',
            'UA' => 'Ukraine',
            'UG' => 'Uganda',
            'UY' => 'Uruguay',
            'UZ' => 'Uzbekistan',
            'VA' => 'Vatican City',
            'VC' => 'Saint Vincent and the Grenadines',
            'VE' => 'Venezuela',
            'VG' => 'British Virgin Islands',
            'VN' => 'Vietnam',
            'VU' => 'Vanuatu',
            'WF' => 'Wallis and Futuna Islands',
            'WS' => 'Western Samoa',
            'YE' => 'Yemen',
            'YT' => 'Mayotte (France)',
            'ZA' => 'South Africa',
            'ZM' => 'Zambia',
            'ZW' => 'Zimbabwe',
            'US' => 'United States',
        );

        if (isset($countries[$countryId])) {
            return $countries[$countryId];
        }

        return false;
    }

    /**
     * Check is Country U.S. Possessions and Trust Territories
     *
     * @param string $countyId
     * @return boolean
     */
    protected function _isUSCountry($countyId)
    {
        switch ($countyId) {
            case 'AS': // Samoa American
            case 'GU': // Guam
            case 'MP': // Northern Mariana Islands
            case 'PW': // Palau
            case 'PR': // Puerto Rico
            case 'VI': // Virgin Islands US
            case 'US'; // United States
                return true;
        }

        return false;
    }
}