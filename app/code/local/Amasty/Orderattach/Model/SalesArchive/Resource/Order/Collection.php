<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattach
 */


class Amasty_Orderattach_Model_SalesArchive_Resource_Order_Collection extends Enterprise_SalesArchive_Model_Resource_Order_Collection
{
    /**
     * Generate select based on order grid select for getting archived order fields.
     *
     * @param Zend_Db_Select $gridSelect
     * @return Zend_Db_Select
     */
    public function getOrderGridArchiveSelect(Zend_Db_Select $gridSelect)
    {

        $fromAndJoins = $gridSelect->getPart(Zend_Db_Select::FROM);
        $joinsLeft = array_filter($fromAndJoins, array($this, '_leftJoinFilter'));

        $select = parent::getOrderGridArchiveSelect($gridSelect);

        foreach ($joinsLeft as $alias => $values) {
            $select->joinLeft(
                array($alias => $values['tableName']),
                $values['joinCondition'],
                array(),
                $values['schema']
            );
        }

        return $select;
    }

    private function _leftJoinFilter($var)
    {
        return $var['joinType'] == Zend_Db_Select::LEFT_JOIN;
    }
}