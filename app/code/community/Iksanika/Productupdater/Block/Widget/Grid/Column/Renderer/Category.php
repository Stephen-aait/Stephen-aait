<?php

class Iksanika_Productupdater_Block_Widget_Grid_Column_Renderer_Category 
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $categoriesHtml = '';
        $categories     = $row->getCategoryCollection()->addNameToResult();
        if ($categories)
        {
            $categoriesCount= count($categories);
            $categoriesIdx  = 0;
            foreach ($categories as $category)
            {
                $path        =  '';
                $pathInStore =  $category->getPathInStore();
                $pathIds     =  array_reverse(explode(',', $pathInStore));

                $categories =   $category->getParentCategories();

                foreach ($pathIds as $categoryId) 
                {
                    if (isset($categories[$categoryId]) && $categories[$categoryId]->getName()) 
                    {
                        $path .= (($path != '') ? ' / ': '').$categories[$categoryId]->getName();
                    }
                }
//border: 1px dotted #bcbcbc;
//                $categoriesHtml .= '<div style="font-size: 90%;margin-bottom: 2px;border-radius: 2px;padding: 2px;background-color: #ffffff;-webkit-box-shadow: 0 1px 2px rgba(0, 0, 0, .4);-moz-box-shadow: 0 1px 2px rgba(0,0,0,.4);box-shadow: 0 1px 2px rgba(0, 0, 0, .4);">' . $path . '</div>';
                $delimiter =    (++$categoriesIdx != $categoriesCount) ? 'border-bottom: 1px dotted #bcbcbc;margin-bottom: 2px;'
                                : '';
//'border: 1px dotted #bcbcbc;'
//                                : 'background-color: #ffffff;-webkit-box-shadow: 0 1px 2px rgba(0, 0, 0, .4);-moz-box-shadow: 0 1px 2px rgba(0,0,0,.4);box-shadow: 0 1px 2px rgba(0, 0, 0, .4);';
                $categoriesHtml .= '<div style="font-size: 90%;border-radius: 2px;padding: 2px;'.($delimiter).'">' . $path . '</div>';
            }
        }
        return $categoriesHtml;
    }
}