<?php
Namespace Sparx\Designersoftware\Model\Indexer;
class Flat implements \Magento\Indexer\Model\ActionInterface, \Magento\Framework\Mview\ActionInterface
{
    public function executeFull(); //Should take into account all placed orders in the system
    public function executeList($ids); //Works with a set of placed orders (mass actions and so on)
    public function executeRow($id); //Works in runtime for a single order using plugins
    public function execute($ids); //Used by mview, allows you to process multiple placed orders in the “Update on schedule" mode
}
	
