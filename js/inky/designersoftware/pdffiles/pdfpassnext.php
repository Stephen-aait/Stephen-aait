<?php

require('genpdf.php');
$toolval = new GenPDF();
$action = $_REQUEST['action'];
if(!empty($action)){
    $fva2 = $toolval->$action($_REQUEST);
	echo $fva2;
}
?>
