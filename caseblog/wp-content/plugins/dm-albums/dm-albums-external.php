<?php

require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/wp-load.php");
	
function dm_printalbum($directory)
{
	$directory = str_replace(get_option("DM_HOME_DIR"), "", $directory);
	
	print(dm_getalbum("?currdir=$directory"));
}

?>