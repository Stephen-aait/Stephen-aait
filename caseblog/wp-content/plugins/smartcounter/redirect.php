<?php
	require_once('../../../wp-config.php');	
	$dcp_action = $_GET['dcp_action'];
	$dcp_id = $_GET['dcp_id'];
function find_my_url_from_id($dcp_id,$dcp_params) {
	$url='';
	$all = explode("*", $dcp_params);
	$all2=explode("|", $all[1]); 
	foreach ($all2 as $key => $value) {
	$all3=explode(":", $value);
	$file_name= $all3[0];
	$file_id= $all3[1];
	$file_counter= $all3[2];
	if ((int)$file_id==(int)$dcp_id){ $url=$file_name;}
	}
$url="http://".$url;
return $url;
}

function update_red_counter($dcp_id,$dcp_params) {
	$string='';
	$all = explode("*", $dcp_params);
	$string.=$all[0].'*';
	$all2=explode("|", $all[1]); 
	foreach ($all2 as $key => $value) {
	$all3=explode(":", $value);
	$file_name= $all3[0];
	$file_id= $all3[1];
	$file_counter= $all3[2];
	$file_title= $all3[3];
	$file_type= $all3[4];
	$file_show= $all3[5];
	if ((int)$file_id==(int)$dcp_id){ $file_counter=$file_counter+1;}
	if($file_name!='')  $string.=$file_name.':'.$file_id.':'.$file_counter.':'. $file_title.':'.$file_type.':'.$file_show.'|';		
	}

return $string;
}

if (($dcp_action =='goto')&&($dcp_id >0)) {

	$dcp_params = get_option('smartcounter_params');
	$url = find_my_url_from_id($dcp_id,$dcp_params);
	$dcp_params = update_red_counter($dcp_id,$dcp_params);
	update_option('smartcounter_params', $dcp_params);
	header('Location: ' . $url);
	exit();


}
?>