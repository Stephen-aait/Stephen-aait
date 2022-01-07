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

class Iksanika_Productupdater_Helper_Category 
    extends Mage_Core_Helper_Abstract
{
    protected $categoryPath = array();
    
    public function getOptionsForFilter()
    {
        $parentCategory = Mage::getModel('catalog/category')->load((Mage::helper('productupdater')->getStore()->getRootCategoryId() != 0 ? Mage::helper('productupdater')->getStore()->getRootCategoryId() : Mage_Catalog_Model_Category::TREE_ROOT_ID));
        $this->generateCategoryPath($parentCategory);
        $options    =   array(0 => $this->__('[NO CATEGORY]'));
        foreach ($this->categoryPath as $i => $path)
        {
            $string = str_repeat(". ", max(0, ($path['level'] - 1) * 3)) . $path['name'];
            $options[$path['id']] = $string;
        }
        return $options;
    }
    
    public function generateCategoryPath($category)
    {
        if ($category->getName())
        {
            $this->categoryPath[] = array(
                'id'    => $category->getId(),
                'level' => $category->getLevel(),
                'name'  => $category->getName(),
            );
        }
        if ($category->hasChildren())
        {
            foreach ($category->getChildrenCategories() as $child)
            {
                $this->generateCategoryPath($child);
            }
        }
    }
       
    /**
     * Genarate category structure with all categories
     *
     * @param int $rootId root category id
     * @return array sorted list category_id=>title
     */
    public function getTree($rootId)
    {
        $tree               =   array();
        $categoryCollection =   Mage::getModel('catalog/category')->getCollection()->addNameToResult();
        
        $position = array();
        foreach ($categoryCollection as $category)
        {
            $path = explode('/', $category->getPath());
            if ((!$rootId || in_array($rootId, $path)) && $category->getLevel() && $category->getName())
            {
                $tree[$category->getId()] = array(
                    'label' => str_repeat('..', $category->getLevel()) . $category->getName() . ' ['.$category->getId().']',
                    'value' => $category->getId(),
                    'path'  => $path,
                );
            }
            $position[$category->getId()] = $category->getPosition();
        }
        
        foreach ($tree as $catId => $category)
        {
            $order = array();
            foreach ($category['path'] as $id)
            {
		$order[] = isset($position[$id]) ? $position[$id] : 0;
            }
            $tree[$catId]['order'] = $order;
        }
        
        usort($tree, array($this, 'compare'));
        
        return $tree;
    }
    
    /**
     * Compares category data
     *
     * @return int 0, 1 , or -1
     */
    public function compare($a, $b)
    {
        foreach ($a['path'] as $index => $id)
        {
            if (!isset($b['path'][$index]))
                return 1; // B path is shorther then A, and values before were equal
            if ($b['path'][$index] != $id)
                return ($a['order'][$index] < $b['order'][$index]) ? -1 : 1; // compare category positions at the same level
        }
        return ($a['value'] == $b['value']) ? 0 : -1; // B path is longer or equal then A, and values before were equal
    }      
    
}