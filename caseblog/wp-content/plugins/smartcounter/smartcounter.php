<?php
/*
Plugin Name: SmartCounter
Plugin URI: http://www.royakhosravi.com/?p=159
Description:  Smart Counter is a simple counter which can track downloads, clicks or hits.
Author: Roya Khosravi
Version: 1.1
Author URI: http://www.royakhosravi.com/

Updates:
-none

To-Doo: 
-none
*/
$smartcounter_localversion="1.1";
$wp_dcp_plugin_url = trailingslashit( get_bloginfo('wpurl') ).PLUGINDIR.'/'. dirname( plugin_basename(__FILE__) );

function get_pars($dcp_params) {
$all = explode("|", $dcp_params);
$my_data_array = array(	'par4' => $all[2],
			'par5' => $all[3],
			'par6' => $all[4],
			'par7' => $all[5]);
return $my_data_array;
}
function update_my_counter($dcp_id,$dcp_params) {
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
function get_dcp_disp_posts($dcp_params) {
	$all = explode("|", $dcp_params);
	return $all[0]; 
}
function get_dcp_string($params='type',$id=0, $uid=0, $purl='') {
$return_me='';
switch ($params) {
case "type":
    if ($id==1) $return_me='Downloads';
    if ($id==2) $return_me='Clicks';
    if ($id==3) $return_me='Hits';
    break;
case "show":
    if ($id==1) $return_me='Yes';
    if ($id==2) $return_me='No';
    break;
case "all":
	$dcp_params = get_option('smartcounter_params');
	$tab=get_pars($dcp_params);
	$par4=$tab['par4']; 
	$par5=$tab['par5']; 
	$par6=$tab['par6']; 
	$par7=$tab['par7'];
	$all = explode("*", $dcp_params);
	$all2=explode("|", $all[1]); 
	foreach ($all2 as $key => $value) {
	$all3=explode(":", $value);
	if ($all3[1]==$uid){
	$file_name= $all3[0];
	$file_id= $all3[1];
	$file_counter= $all3[2];
	$file_title= $all3[3];
	$file_type= $all3[4];
	$file_show= $all3[5];
	}}

if ($file_type==1) { /// download
$return_me='<a href="'.$purl. '/redirect.php?dcp_action=goto&dcp_id='.$uid.'" target="_blank"><img alt="'.$par4 . ' ' . $file_title.'" border="0" src="'.$purl. '/download.jpg"></a>';
if ($file_show==1) $return_me .= '<br />'. $file_counter. ' '.$par5.'';
}
if ($file_type==2) { /// clicks
$return_me='<a href="'.$purl. '/redirect.php?dcp_action=goto&dcp_id='.$uid.'" target="_blank"><u>'.$file_title.'</u></a>';
if ($file_show==1) $return_me .= '  ('. $file_counter. ' '.$par7.')';
}

if ($file_type==3) { /// hits
$dcp_params = update_my_counter($uid,$dcp_params);
update_option('smartcounter_params', $dcp_params);
if ($file_show==1) {
$return_me=$file_counter . ' '.$par6;
}}
    break;
default:
}
return $return_me;
}
function get_dcp_files($dcp_params) {
	if (strstr($dcp_params, '*')=='*') return 'No Link found. To add a new Link please use the form below.';
	$string="";
	$string.='<table width="100%">';
	$string.='<tr>';
	$string.='<td bgcolor="#CCCCFF" align="center">Title</td>';
	$string.='<td bgcolor="#CCCCFF" align="center">URL</td>';
	$string.='<td bgcolor="#CCCCFF" align="center">Unique ID</td>';
	$string.='<td bgcolor="#CCCCFF" align="center">Counter</td>';
	$string.='<td bgcolor="#CCCCFF" align="center">Show</td>';
	$string.='</tr>';
	$all = explode("*", $dcp_params);
	$all2=explode("|", $all[1]); 
	foreach ($all2 as $key => $value) {
	$all3=explode(":", $value);
	$file_name= $all3[0];
	if(strpos($file_name, "www.")!==FALSE) $file_name= str_replace("www.","",$file_name);
	$file_id= $all3[1];
	$file_counter= $all3[2];
	$file_title= $all3[3];
	$file_type= $all3[4];
	$file_show= $all3[5];
	if ($file_name!=''){
	$string.='<tr>';
	$string.='<td align="center">'.$file_title.'</td>';
	$string.='<td align="center">'.$file_name.'</td>';
	$string.='<td align="center">'.$file_id.'</td>';
	$string.='<td align="center">'.$file_counter. ' '.get_dcp_string('type',$file_type).'</td>';
	$string.='<td align="center">'.get_dcp_string('show',$file_show).'</td>';
	$string.='</tr>';
	}
	}
	$string.='</table>';
return $string;
}

function get_dcp_counter($dcp_params) {
	$all = explode("|", $dcp_params);
return $all[1]; 
}

function delete_this_one($only_files, $dcfileid) {
	$string='';
	$all=explode("|", $only_files);
	foreach ($all as $key => $value) {
	$all1=explode(":", $value);
	$file_name= $all1[0];
	(int)$file_id= $all1[1];
	$file_counter= $all1[2];
	$file_title= $all1[3];
	$file_type= $all1[4];
	$file_show= $all1[5];
	if (($file_id!=$dcfileid)&&($file_name!='')) 
	$string .= $file_name.':'.$file_id.':'.$file_counter.':'. $file_title.':'.$file_type.':'.$file_show.'|';
	}
return $string;
}

function this_file_exists($dcfilename) {
$return_me = false;
	if (file_exists($dcfilename)) {
    	$return_me = true;
	} else {
    	$return_me = false;
	}
return $return_me;
}
function dcp_clean($some) {
if(strpos($some, "http://")!==FALSE) $some= str_replace("http://","",$some);
$some=trim($some);
if ( (strstr($some, "\n") != false) || (strstr($some, "\r") != false) ) $some=str_replace(array("\n","\r"), "", $some);
if(strpos($some, "|")!==FALSE) $some= str_replace("|","",$some);
if(strpos($some, "*")!==FALSE) $some= str_replace("*","",$some);
if(strpos($some, ":")!==FALSE) $some= str_replace(":","",$some);
return $some;
}

function update_dcp_params($dcp_params,$disp_posts='on',$dcfilename='',$dcfileid=0, $par1=0, $par2='', $par3=0, $par4='', $par5='', $par6='', $par7='', $par8=0) {
$all='';
	$only= explode("*", $dcp_params);
	$only_files= $only[1];
	$onoff=get_dcp_disp_posts($dcp_params);
	if ($onoff!=$disp_posts) $onoff=$disp_posts;
	$new_counter = (int)get_dcp_counter($dcp_params);
	if (!empty($dcfilename)) {
		$new_counter++;
		$only_files .= $dcfilename.':'. $new_counter.':'.$par8.':'.$par2.':'.$par1.':'.$par3.'|';
	}
	if ($dcfileid>0) {
		$only_files = delete_this_one($only_files, $dcfileid);
	}

$all=$onoff."|".$new_counter."|".$par4."|".$par5."|".$par6."|".$par7."|*".$only_files;
return $all;
}
 // Admin Panel   
function smartcounter_add_pages()
{
	add_options_page('SmartCounter options', 'SmartCounter', 8, __FILE__, 'smartcounter_options_page');            
}
// Options Page
function smartcounter_options_page()
{ 
	global $smartcounter_localversion;
	$status = smartcounter_getinfo();	
	$theVersion = $status[1];
	$theMessage = $status[3];	
	
			if( (version_compare(strval($theVersion), strval($smartcounter_localversion), '>') == 1) )
			{
				$msg = 'Latest version available '.' <strong>'.$theVersion.'</strong><br />'.$theMessage;	
				  _e('<div id="message" class="updated fade"><p>' . $msg . '</p></div>');			
			
			}
			
			

			if (isset($_POST['submitted'])) 
	{	

			$dcp_params = get_option('smartcounter_params');
			$disp_posts = !isset($_POST['disp_posts'])? 'off': 'on';
			$dcfilename = dcp_clean($_POST['dcfilename']);
			$dcfileid = dcp_clean((int)$_POST['dcfileid']);
			$par1 = dcp_clean((int)$_POST['par1']);
			$par2 = dcp_clean($_POST['par2']);
			$par3 = dcp_clean((int)$_POST['par3']);
			$par4 = dcp_clean($_POST['par4']);
			$par5 = dcp_clean($_POST['par5']);
			$par6 = dcp_clean($_POST['par6']);
			$par7 = dcp_clean($_POST['par7']);
			$par8 = dcp_clean((int)$_POST['par8']);
			$dcp_params =update_dcp_params($dcp_params, $disp_posts, $dcfilename, $dcfileid, $par1, $par2, $par3, $par4, $par5, $par6, $par7, $par8);
			update_option('smartcounter_params', $dcp_params);
			$msg_status = 'SmartCounter options saved.';

										
		   _e('<div id="message" class="updated fade"><p>' . $msg_status . '</p></div>');
		
	} 
		// vas me chercher le truc dans la base!
		$dcp_params = get_option('smartcounter_params');
		$disp_posts = (get_dcp_disp_posts($dcp_params)=='on') ? 'checked' :'' ;



	global $wp_version;	
	global $wp_dcp_plugin_url;
	$actionurl=$_SERVER['REQUEST_URI'];
    // Configuration Page
    echo <<<END
<div class="wrap" style="max-width:950px !important;">
	<h2>SmartCounter $smartcounter_localversion</h2>
				
	<div id="poststuff" style="margin-top:10px;">
	
	<div id="sideblock" style="float:right;width:220px;margin-left:10px;"> 
		 <h3>Related</h3>

<div id="dbx-content" style="text-decoration:none;">
<ul>
<li><a style="text-decoration:none;" href="http://www.royakhosravi.com/?p=159">SmartCounter</a></li>
</ul><br />
</div>
 	</div>
	
	 <div id="mainblock" style="width:710px">
	 
		<div class="dbx-content">
		 	<form name="rkform" action="$action_url" method="post">
					<input type="hidden" name="submitted" value="1" /> 
<h3>Description</h3>  
SmartCounter is a simple counter which can track downloads, clicks or hits. 
<br />
<br />   
<h3>Usage</h3>  
<strong>[smartcounter:</strong>unique_id<strong>]</strong><br />
Examples: <strong>[smartcounter:</strong>12<strong>]</strong>
<br />
<br />
<h3>Options</h3>
<div><input id="check3" type="checkbox" name="disp_posts" $disp_posts />
<label for="check3"><strong>Activate SmartCounter</strong></label></div>
<br/>
<hr />
<br/>
<h3>Your Links</h3>
<div>
END;
echo get_dcp_files($dcp_params);
    echo <<<END
</div>
<br/>
<hr />
<br/>
<strong>Add New Link</strong><br/><br/>
<div><label for="dcfilename">Full URL : </label><input id="dcfilename"  name="dcfilename" value="" size="20"/></div>
<div><label for="par1">Type : </label>
<select name="par1">
	<option value="1">Download</option>
	<option value="2">Click</option>
	<option value="3">Hits</option>
</select></div>
<div><label for="par2">Title : </label><input id="par2"  name="par2" value="" size="20"/></div>
<div><label for="par3">Show/Hide : </label>
<select name="par3">
	<option value="1">Show counter</option>
	<option value="2">Hide Counter</option>
</select></div>
<div><label for="par8">Counter initial value : </label><input id="par8"  name="par8" value="0" size="4"/></div>
<br/>
<hr />
<br/>
<strong>Delete a Link</strong>
<div><label for="dcfileid">Unique ID : </label><input id="dcfileid"  name="dcfileid" value="" size="7"/></div>
<br/>
<hr />
<br/>
<strong>Displayed Texts</strong>
<div>
END;
$tab=get_pars($dcp_params);
foreach ($tab as $key => $value) {
	echo '<div><input id="'.$key.'" name="'.$key.'" value="'.$value.'" size="25"/></div>';
}
    echo <<<END
</div>
<br/>
<hr />
<br/>
<div class="submit"><input type="submit" name="Submit" value="Update options" /></div>
			</form>
		</div>
					
		<br/><br/><h3>&nbsp;</h3>	
	 </div>

	</div>
<h5>SmartCounter plugin by <a href="http://www.royakhosravi.com/">Roya Khosravi</a></h5>
</div>
END;
}
// Add Options Page
add_action('admin_menu', 'smartcounter_add_pages');

function smartcounter_tag($files_id) {
	$dcp_tag='';
	if ($files_id!=0) {
	$plugin_url = trailingslashit( get_bloginfo('wpurl') ).PLUGINDIR.'/'. dirname( plugin_basename(__FILE__) );
	$dcp_tag = get_dcp_string('all',0,$files_id,$plugin_url);
	}
	return $dcp_tag;
}

function smartcounter_check($the_content) {
	if(strpos($the_content, "[smartcounter:")!==FALSE) {
 		preg_match_all('/\[smartcounter:(?<digit>\d+)\]/', $the_content, $matches, PREG_SET_ORDER); 
		foreach($matches as $match) { 
	$the_content = preg_replace("/\[smartcounter:(?<digit>\d+)\]/", smartcounter_tag($match[digit]), $the_content,1);
		}	
	}
    return $the_content;
}

function smartcounter_install(){
  if(get_option('smartcounter_params' == '') || !get_option('smartcounter_params')){
    $heute=date('Y-m-d');
    add_option('smartcounter_params', 'on|0|Download|Downloads Since '. $heute .'|Visits Since '. $heute .'|Clicks Since '. $heute .'|*');
  }
}
function smartcounter_uninstall(){
 delete_option('smartcounter_params');
}
if (isset($_GET['activate']) && $_GET['activate'] == 'true') {
    smartcounter_install();
}
if ((isset($_GET['deactivate']) && $_GET['deactivate'] == 'true')||(isset($_POST['deactivate']) && $_POST['deactivate'] == 'true')) {
    smartcounter_uninstall();
}

if (get_dcp_disp_posts(get_option('smartcounter_params'))=='on')  {
	add_filter('the_content', 'smartcounter_check', 100);
	add_filter('the_excerpt','smartcounter_check', 100);
	

}

add_action( 'plugins_loaded', 'smartcounter_install' );

add_action( 'after_plugin_row', 'smartcounter_check_plugin_version' );

function smartcounter_getinfo()
{
		$checkfile = "http://www.royakhosravi.com/pub/SmartCounter_wordpress_plugin_version.txt";
		
		$status=array();
		return $status;
		$vcheck = wp_remote_fopen($checkfile);
				
		if($vcheck)
		{
			$version = $smartcounter_localversion;
									
			$status = explode('@', $vcheck);
			return $status;				
		}					
}

function smartcounter_check_plugin_version($plugin)
{
	global $plugindir,$smartcounter_localversion;
	
 	if( strpos($plugin,'smartcounter.php')!==false )
 	{
			

			$status=smartcounter_getinfo();
			
			$theVersion = $status[1];
			$theMessage = $status[3];	
	
			if( (version_compare(strval($theVersion), strval($smartcounter_localversion), '>') == 1) )
			{
				$msg = 'Latest version available '.' <strong>'.$theVersion.'</strong><br />'.$theMessage;				
				echo '<td colspan="5" class="plugin-update" style="line-height:1.2em;">'.$msg.'</td>';
			} else {
				return;
			}
		
	}
}
?>
