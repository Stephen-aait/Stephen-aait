<?php
/**
 * Storelocator data installation script
 * 
 * @category    Clarion
 * @package     Clarion_Storelocator
 * @author      Clarion Magento Team <magento@clariontechnologies.co.in>
 * 
 */
/**
 * @var $installer Mage_Core_Model_Resource_Setup
 */
$installer = $this;
/**
 * Fill table clarion_storelocator/storelocator
 */

//Get current timestamp
$currentTimestamp = Mage::getModel('core/date')->timestamp(time());

$stores = array(
    array(
        'name'           => 'Demo1',
        'status'         => 1,
        'street_address' =>'NY Road',
        'country'        =>'US',
        'state'          =>'New York',
        'city'           =>'New York',
        'zipcode'        =>'10007',
        'phone'          =>'111-111-1111',
        'fax'            =>'1-111-111 1111',
        'url'            =>'http://www.magento.com',
        'email'          =>'test@gmail.com',
        'store_logo'     =>'demo1.png',
        'description'    =>'This is for testing.',
        'trading_hours'  =>'10AM-1PM 2PM-10PM',
        'radius'         =>'100',
        'latitude'       =>'40.712784',
        'longitude'      =>'-74.005941',
        'zoom_level'     =>'6',
        'created_at'     =>$currentTimestamp,
        'stores'        => array(0)
    ),
    array(
        'name'           => 'Demo2',
        'status'         => 1,
        'street_address' =>'LA Road',
        'country'        =>'US',
        'state'          =>'California',
        'city'           =>'Los Angeles',
        'zipcode'        =>'90012',
        'phone'          =>'111-111-1111',
        'fax'            =>'1-111-111 1111',
        'url'            =>'http://www.magento.com',
        'email'          =>'test@gmail.com',
        'store_logo'     =>'demo2.png',
        'description'    =>'This is for testing.',
        'trading_hours'  =>'10AM-1PM 2PM-10PM',
        'radius'         =>'100',
        'latitude'       =>'34.052234',
        'longitude'      =>'-118.243685',
        'zoom_level'     =>'6',
        'created_at'     =>$currentTimestamp,
        'stores'        => array(0)
    ),
    array(
        'name'           => 'Demo3',
        'status'         => 1,
        'street_address' =>'CH Road',
        'country'        =>'US',
        'state'          =>'Illinois',
        'city'           =>'Chicago',
        'zipcode'        =>'60602',
        'phone'          =>'111-111-1111',
        'fax'            =>'1-111-111 1111',
        'url'            =>'http://www.magento.com',
        'email'          =>'test@gmail.com',
        'store_logo'     =>'demo3.png',
        'description'    =>'This is for testing.',
        'trading_hours'  =>'10AM-1PM 2PM-10PM',
        'radius'         =>'100',
        'latitude'       =>'41.878114',
        'longitude'      =>'-87.629798',
        'zoom_level'     =>'6',
        'created_at'     =>$currentTimestamp,
        'stores'        => array(0)
    ),
    array(
        'name'           => 'Demo4',
        'status'         => 1,
        'street_address' =>'HO Road',
        'country'        =>'US',
        'state'          =>'Texas',
        'city'           =>'Houston',
        'zipcode'        =>'77002',
        'phone'          =>'111-111-1111',
        'fax'            =>'1-111-111 1111',
        'url'            =>'http://www.magento.com',
        'email'          =>'test@gmail.com',
        'store_logo'     =>'demo4.png',
        'description'    =>'This is for testing.',
        'trading_hours'  =>'10AM-1PM 2PM-10PM',
        'radius'         =>'100',
        'latitude'       =>'29.760193',
        'longitude'      =>'-95.369390',
        'zoom_level'     =>'6',
        'created_at'     =>$currentTimestamp,
        'stores'        => array(0)
    ),
    array(
        'name'           => 'Demo5',
        'status'         => 1,
        'street_address' =>'LV Road',
        'country'        =>'US',
        'state'          =>'Nevada',
        'city'           =>'Las Vegas',
        'zipcode'        =>'89101',
        'phone'          =>'111-111-1111',
        'fax'            =>'1-111-111 1111',
        'url'            =>'http://www.magento.com',
        'email'          =>'test@gmail.com',
        'store_logo'     =>'demo5.png',
        'description'    =>'This is for testing.',
        'trading_hours'  =>'10AM-1PM 2PM-10PM',
        'radius'         =>'100',
        'latitude'       =>'36.255123',
        'longitude'      =>'-115.238349',
        'zoom_level'     =>'6',
        'created_at'     =>$currentTimestamp,
        'stores'        => array(0)
    )
);

/**
 * Insert data into storelocator table for testing/demo
 */
foreach ($stores as $data) {
    Mage::getModel('clarion_storelocator/storelocator')->setData($data)->save();
}