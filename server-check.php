<?PHP
//echo phpinfo();
@error_reporting(0);
//		SYSTEM , EXEC  , SHELL EXEC Command Execution  //
$disFuncArr = explode(',', str_replace(' ' , '' , ini_get('disable_functions')));
$execVal = 0;
$sysVal = 0;
$shelExecVal = 0;
foreach ($disFuncArr as $disableFunc)
	{
	switch ($disableFunc)
		{
		case "exec" :
			$execVal = 1;
			$execCommand = 'exec';
			break;
		case "system" :
			$sysVal = 1;
			$sysCommand = 'system';
			break;
		case "shell_exec" :
			$shelExecVal = 1;
			$shelExecCommand = 'shell_exec';
			break;	
		}
	}	
	
//		SYSTEM , EXEC  , SHELL EXEC Command Execution END //

if ($execVal==0)
	$execCommand = 'exec';
elseif ($sysVal==0)
	$execCommand = 'system';
elseif($shelExecVal==0)
	$execCommand = 'shell_exec';
else
	$execCommand = ' ';

//			IMAGEMAGICK ENABALITY CHECKING			//

$execCommand('/usr/bin/convert -version' , $res);
if (preg_match('/Version: ImageMagick ([0-9]*\.[0-9]*\.[0-9]*)/' , $res[0] , $arrVal))
	{
	$curVersion = $arrVal[1];
	$imgPath = '/usr/bin/';
	$versionA   = 1;
	}

$execCommand('/usr/local/bin/convert -version' , $res1);
if (preg_match('/Version: ImageMagick ([0-9]*\.[0-9]*\.[0-9]*)/' , $res1[0] , $arrVal1))
	{
	$curVersion = $arrVal1[1];
	$versionB   = 1;
	$imgPath = '/usr/local/bin/';
	}

if ($versionA==0 && $versionB==0)
	$imageMagick = 0;
else
	{
	$imageMagick = 1;
	$currentVersion = $curVersion;
	
	$imgVerCheck  = (version_compare($curVersion, '6.6.0', '>=')) ? 1 : 0;	
	}

//			IMAGEMAGICK ENABALITY CHECKING END			//


$ionCube = (extension_loaded('ionCube Loader')) ? 1 : 0;    // FIND IONCUBE LOADER
$perlEnable = (extension_loaded('pcre')) ? 1 : 0;    // FIND IONCUBE LOADER
$domCheck = (extension_loaded('dom')) ? 1 : 0;    // FIND IONCUBE LOADER
$gdCheck = (extension_loaded('gd')) ? 1 : 0;    // FIND IONCUBE LOADER
$phpVal  = (version_compare(phpversion(), '5.0.0', '>=')) ? 1 : 0;
function find_SQL_Version($execCommand) {
   $output = $execCommand('mysql -V');
   preg_match('@[0-9]+\.[0-9]+\.[0-9]+@', $output, $version);
   $version = $version[0];
   return $version;
}

$msqlVerCheck = (version_compare(find_SQL_Version($execCommand) , '4.0.0', '>=')) ? 1 : 0;
?>
<style>
body{
	margin:0px 0px 0px 0px;
	padding:0px 0px 0px 0px;
	font-family:"Lucida Sans Unicode";
}
</style>
<center>
<div style="display:block; height:50px; line-height:50px; background:#CCCCCC; border-bottom:4px solid #999999; letter-spacing:13px; font-size:16px;"><h1>SERVER COMPATIBILITY TEST</h1></div>
<br /><br />
<table align="center" border="0" cellpadding="5" cellspacing="0" width="779">
	<tr>
    	<td width="279" valign="top" align="right"><h2>Server Commands:&nbsp;&nbsp;&nbsp;</h2></td>
      <? if ($execVal==1) {?>  <td valign="top">
        
        	<table border="0" width="480px">
            	
            	<tr>
                	
                	<td>
                    	<b><span style="color:#FF0000">oh my bad!</span> EXEC() is <span style="color:#FF0000">disabled</span> on your server, Please enabled it to work no-refresh's software without any problem.</b>
                    </td>
                    <? } else {?>
                    <td>
                    	<b><span style="color:#00CC00;">Congrats!</span> EXEC() is <span style="color:#00CC00">enabled</span> on your server</b>                   
                    </td>
                    <? } ?>
                </tr>
                <tr>
                	<? if ($sysVal==1) {?>
                	<td colspan="2">
                    	<b><span style="color:#FF0000">oh my bad!</span> SYSTEM() is <span style="color:#FF0000">disabled</span> on your server, Please enabled it to work no-refresh's software without any problem.</b>                   
                    </td>
                    <? } else {?>
                    <td colspan="2">
                    	<b><span style="color:#00CC00;">Congrats!</span> SYSTEM() is <span style="color:#00CC00">enabled</span> on your server</b>
                    </td>
                    <? } ?>
                </tr>
                <tr>
                	<? if ($shelExecVal==1) {?>
                	<td colspan="2">
                    	<b><span style="color:#FF0000">oh my bad!</span> shell_exec() is <span style="color:#FF0000">disabled</span> on your server, Please enabled it to work no-refresh's software without any problem.</b>                   
                    </td>
                    <? } else {?>
                    <td colspan="2">
                    	<b><span style="color:#00CC00;">Congrats!</span> shell_exec() is <span style="color:#00CC00">enabled</span> on your server</b>
                    </td>
                   
                </tr>             
            </table>   
        </td><? } ?> 
    </tr>
    <tr>
    <td height="20px">&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
    
    <tr>
    	<td valign="top" align="right"><h2>ImageMagick:&nbsp;&nbsp;&nbsp;</h2></td>
        <td valign="top">
        	<table>
            	<tr>
                	<? if ($imageMagick==0){?>
                	<td><b><span style="color:#FF0000">Awwww...</span> Imagemagick is <span style="color:#FF0000">not installed</span> on your server.</b> Please install <b>Imagemagick 6.6.0 (or greater)</b> to work no-refresh's software without any problem.</td>
                    <? } else if($imgVerCheck==0) {?>
                    <td><b><span style="color:#FF0000">Well done,</span> Imagemagick is <span style="color:#FF0000">installed</span> on your server.</b> But the current version is <?=$currentVersion?> which is not compatible with our software. Please instal <b>Imagemagick 6.6.0 (or greater)</b> to work no-refresh's software without any problem.</td>
                    <? } else {?>
                    <td><b><span style="color:#00CC00;">Congrats!</span> Imagemagick is <span style="color:#00CC00">installed</span> on your server</b>. Installed version of Imagemagick on your server is <?= $currentVersion?> And Execution path is <?=$imgPath?></td>
                    <? } ?>
                </tr>
            </table>	
        </td>
    </tr>
    <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
    
    <tr>
    	<td valign="top" align="right"><h2>ActivePerl:&nbsp;&nbsp;&nbsp;</h2></td>
        <td valign="top">
        	<table>
            	<tr>
                	<? if ($perlEnable==0){?>
                	<td><b><span style="color:#FF0000">Awwww...</span> Active Perl is <span style="color:#FF0000">not installed</span> on your server.</b> Please install <b>ActivePerl-5.12.x or higher</b> to work no-refresh's software without any problem.</td>
                    <? } else {?>
                    <td><b><span style="color:#00CC00;">Congrats!</span> ActivePerl is <span style="color:#00CC00">installed</span> on your server</b>.</td>
                    <? } ?>
                </tr>
            </table>	
        </td>
    </tr>
    <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
    
    
    <tr>
    	<td valign="top" align="right"><h2>IonCube Loader:&nbsp;&nbsp;&nbsp;</h2></td>
        <td valign="top">
        	<table>
            	<tr>
                	<? if ($ionCube==0){?>
                	<td><b><span style="color:#FF0000">oh my bad!</span> IonCube Loader is <span style="color:#FF0000">not installed</span> on your server</b>. Please install it to work no-refresh's software without any problem.</td>
                    <? } else {?>
                    <td><b><span style="color:#00CC00;">Congrats!</span> IonCube Loader is <span style="color:#00CC00">installed</span> on your server</b>.</td>
                    <? } ?>
                </tr>
            </table>	
        </td>
    </tr>
    <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
    <tr>
    	<td valign="top" align="right"><h2>GD Library:&nbsp;&nbsp;&nbsp;</h2></td>
        <td valign="top">
        	<table>
            	<tr>
                	<? if ($gdCheck==0){?>
                	<td><b><span style="color:#FF0000">oh my bad!</span> GD Library is <span style="color:#FF0000">not installed</span> on your server</b>. Please install it to work no-refresh's software without any problem. is not enable on server</td>
                    <? } else {?>
                    <td><b><span style="color:#00CC00;">Congrats!</span> GD Library is <span style="color:#00CC00">installed</span> on your server</b>.</td>
                    <? } ?>
                </tr>
            </table>	
        </td>
    </tr>
    <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
    <tr>
    	<td valign="top" align="right"><h2>DOM Check:&nbsp;&nbsp;&nbsp;</h2></td>
        <td valign="top">
        	<table>
            	<tr>
                	<? if ($domCheck==0){?>
                	<td><b><span style="color:#FF0000">oh my bad!</span> DOM is <span style="color:#FF0000">not installed</span> on your server</b>. Please install it to work no-refresh's software without any problem. is not enable on server</td>
                    <? } else {?>
                    <td><b><span style="color:#00CC00;">Congrats!</span> DOM is <span style="color:#00CC00">installed</span> on your server</b>.</td>
                    <? } ?>
                </tr>
            </table>	
        </td>
    </tr>
    
    <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
    <tr>
    	<td align="right"><h2>PHP Version:&nbsp;&nbsp;&nbsp;</h2></td>
        <td>
        	<table>
            	<tr>
                	<? if ($phpVal==0){?>
                	<td><b><span style="color:#FF0000">oh my bad!</span> PHP version is <span style="color:#FF0000">not compatible</span></b>. PHP version installed on your server is <?=phpversion()?></td>
                    <? } else {?>
                    <td><b><span style="color:#00CC00;">Congrats!</span> PHP version is <span style="color:#00CC00">compatible</span>.</b> PHP version installed on your server is <?=phpversion()?></td>
                    <? } ?>
                </tr>
            </table>	
        </td>
    </tr>
    <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
    <tr>
    	<td align="right"><h2>MySql Version:&nbsp;&nbsp;&nbsp;</h2></td>
        <td>
        	<table>
            	<tr>
                	<? if ($msqlVerCheck==0){?>
                	<td><b><span style="color:#FF0000">oh my bad!</span> MySql version is <span style="color:#FF0000">not compatible</span></b>. MySql version installed on your server is <? echo find_SQL_Version($execCommand)?></td>
                    <? } else {?>
                    <td><b><span style="color:#00CC00;">Congrats!</span> MySql version is <span style="color:#00CC00">compatible</span>.</b> MySql version installed on your server is <? echo find_SQL_Version($execCommand)?></td>
                    <? } ?>
                </tr>
            </table>	
        </td>
    </tr>
    
    
    
</table>
<br />
<br />
<br />
<?php
//====================== Starts Designer tool server configuration values =======================
$displayData='Server Configuration Details<table border="1" width="1000" align="center"><tr><th colspan="2" rowspan="2">Directive (Configuration) Name</th><th colspan="2">Current Server Configuration</th><th colspan="2">Require Server Configuration</th></tr>
<tr><th>Local Value</th><th>Master Value</th><th>Local Value</th><th>Master Value</th></tr>';

$iniVariable = ini_get_all();
$configArray=array();

//======================= Starts max_execution_time ======================================
$customArr=array();
$customArr['require_local_value']='600';
$customArr['require_global_value']='600';
$configArray['max_execution_time']=array_merge($iniVariable['max_execution_time'],$customArr);
unset($customArr);
//======================= Ends max_execution_time ======================================

//======================= Starts max_input_time ======================================
$customArr=array();
$customArr['require_local_value']='600';
$customArr['require_global_value']='600';
$configArray['max_input_time']=array_merge($iniVariable['max_input_time'],$customArr);
unset($customArr);
//======================= Ends max_input_time ======================================

//======================= Starts max_file_uploads ======================================
$customArr=array();
$customArr['require_local_value']='100';
$customArr['require_global_value']='100';
$configArray['max_file_uploads']=array_merge($iniVariable['max_file_uploads'],$customArr);
unset($customArr);
//======================= Ends max_file_uploads ======================================


//======================= Starts max_input_time ======================================
$customArr=array();
$customArr['require_local_value']='100M';
$customArr['require_global_value']='100M';
$configArray['post_max_size']=array_merge($iniVariable['post_max_size'],$customArr);
unset($customArr);
//======================= Ends max_input_time ======================================

//======================= Starts upload_max_filesize ======================================
$customArr=array();
$customArr['require_local_value']='100M';
$customArr['require_global_value']='100M';
$configArray['upload_max_filesize']=array_merge($iniVariable['upload_max_filesize'],$customArr);
unset($customArr);
//======================= Ends upload_max_filesize ======================================

//======================= Starts memory_limit ======================================
$customArr=array();
$customArr['require_local_value']='512M';
$customArr['require_global_value']='512M';
$configArray['memory_limit']=array_merge($iniVariable['memory_limit'],$customArr);
unset($customArr);
//======================= Ends memory_limit ======================================

if(count($configArray)>0){
	foreach($configArray as $key=>$configVal){
		$backgoundColorLocal=(str_replace('M','',$configVal['local_value'])<str_replace('M','',$configVal['require_local_value']))?'style="background-color:#ff0000;"':'';
		$backgoundColorGlobal=(str_replace('M','',$configVal['global_value'])<str_replace('M','',$configVal['require_global_value']))?'style="background-color:#ff0000;"':'';
		
		$astriskErrorLocal=!empty($backgoundColorLocal)?'*':'';
		$astriskErrorGlobal=!empty($backgoundColorGlobal)?'*':'';
		
		$displayData.='<tr><td colspan="2">'.$key.'</td><td '.$backgoundColorLocal.'>'.$astriskErrorLocal.' '.$configVal['local_value'].'</td><td '.$backgoundColorGlobal.'>'.$astriskErrorGlobal.' '.$configVal['global_value'].'</td><td>'.$configVal['require_local_value'].'</td><td>'.$configVal['require_global_value'].'</td></tr>';
		}
	
	}
$displayData.='</table>Note: <span style="background-color:#ff0000;">*</span> Shows Server Configuration is not match';	
echo $displayData;

//====================== Ends Designer tool server configuration values =======================

?>
<br />
<br />
<br />
<div style="font-size:10px; border-top:1px solid #000; height:40px; padding:10px 0px 0px 0px; display:block;">Copyright 2009-<?php echo Date('Y');?>, Sparx IT Solutions Pvt Ltd<br />
powered by <a href="http://www.sparxitsolutions.com" target="_blank">www.sparxitsolutions.com</a></div>
</center>
