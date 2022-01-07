<?php


function team_and_task_organizer_for_magento_license_1635()
{
    $results = array('status' => "Unknown Error",'description'=>'');
    $team_and_task_organizer_for_magento_licensekey = "";
    if(!file_exists(Mage::getModuleDir('', 'Modulesgarden_Teamandtaskorganizer').DIRECTORY_SEPARATOR.'license.php'))
    {
        $results['status'] = 'Error';
        $results['description'] = 'Team & Task Organizer For Magento: Unable to find license.php file. Please rename file license_RENAME.php to license.php';
        return $results;
    }

    require Mage::getModuleDir('', 'Modulesgarden_Teamandtaskorganizer').DIRECTORY_SEPARATOR.'license.php';
    $licensekey = $team_and_task_organizer_for_magento_licensekey;
	$localkey = Mage::getStoreConfig('modulesgarden/localkey/team_and_task_organizer_for_magento');
    
    $whmcsurl = 'http://modulesgarden.com/manage/';
    $whmcshostname = 'modulesgarden.com';
    $licensing_secret_key = '9fbd4c520958fd780b0db09727fe183c';
    $check_token = time() . md5(mt_rand(1000000000, 9999999999) . $licensekey);
    $checkdate = date("Ymd"); # Current date
    $usersip = isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : $_SERVER['LOCAL_ADDR'];
    $localkeydays = 1;
    $allowcheckfaildays = 4;
    $domain = $_SERVER['SERVER_NAME'];
    $dirpath = Mage::getModuleDir('', 'Modulesgarden_Teamandtaskorganizer');
    $verifyfilepath = 'modules/servers/licensing/verify.php';

    $localkeyvalid = false;
    if ($localkey) {
        $localkey = str_replace("\n", '', $localkey); # Remove the line breaks
        $localdata = substr($localkey, 0, strlen($localkey) - 32); # Extract License Data
        $md5hash = substr($localkey, strlen($localkey) - 32); # Extract MD5 Hash
        if ($md5hash == md5($localdata . $licensing_secret_key)) {
            $localdata = strrev($localdata); # Reverse the string
            $md5hash = substr($localdata, 0, 32); # Extract MD5 Hash
            $localdata = substr($localdata, 32); # Extract License Data
            $localdata = base64_decode($localdata);
            $localkeyresults = unserialize($localdata);
            $originalcheckdate = $localkeyresults['checkdate'];
            if ($md5hash == md5($originalcheckdate . $licensing_secret_key)) {
                $localexpiry = date("Ymd", mktime(0, 0, 0, date("m"), date("d") - $localkeydays, date("Y")));
                if ($originalcheckdate > $localexpiry) {
                    $localkeyvalid = true;
                    $results = $localkeyresults;
                    $validdomains = explode(',', $results['validdomain']);
                    if (!in_array($_SERVER['SERVER_NAME'], $validdomains)) {
                        $localkeyvalid = false;
                        $localkeyresults['status'] = "Invalid";
                        $results = array();
                    }
                    $validips = explode(',', $results['validip']);
                    if (!in_array($usersip, $validips)) {
                        $localkeyvalid = false;
                        $localkeyresults['status'] = "Invalid";
                        $results = array();
                    }
                    $validdirs = explode(',', $results['validdirectory']);
                    if (!in_array($dirpath, $validdirs)) {
                        $localkeyvalid = false;
                        $localkeyresults['status'] = "Invalid";
                        $results = array();
                    }
                }
            }
        }
    }
    if (!$localkeyvalid) {
        $postfields = array(
            'licensekey' => $licensekey,
            'domain' => $domain,
            'ip' => $usersip,
            'dir' => $dirpath,
        );
        if ($check_token) $postfields['check_token'] = $check_token;
        $query_string = http_build_query($postfields);
        $http_code = 0;
        if (function_exists('curl_exec')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $whmcsurl . $verifyfilepath);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $query_string);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $data = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
        } else {
            $fp = fsockopen($whmcshostname, 80, $errno, $errstr, 5);
            if ($fp) {
                $newlinefeed = "\r\n";
                $header = "POST ".$whmcsurl . $verifyfilepath . " HTTP/1.0" . $newlinefeed;
                $header .= "Host: ".$whmcsurl . $newlinefeed;
                $header .= "Content-type: application/x-www-form-urlencoded" . $newlinefeed;
                $header .= "Content-length: ".@strlen($query_string) . $newlinefeed;
                $header .= "Connection: close" . $newlinefeed . $newlinefeed;
                $header .= $query_string;
                $data = '';
                @stream_set_timeout($fp, 10);
                @fputs($fp, $header);
                $status = @socket_get_status($fp);
                while (!@feof($fp)&&$status) {
                    $data .= @fgets($fp, 1024);
                    $status = @socket_get_status($fp);
                }
                @fclose ($fp);
            }
        }
        
        list($headerline,) = explode("\r\n",$data,2);
        if(preg_match("/(?<http>[A-Z]{4,5})\/(?<http_version>[0-9\.]+) (?<http_code>[0-9]{3})/", trim($headerline), $headers)){
        $http_code = $headers['http_code'];
        }
            
        if (!$data || $http_code!='200') {
            $localexpiry = date("Ymd", mktime(0, 0, 0, date("m"), date("d") - ($localkeydays + $allowcheckfaildays), date("Y")));
            
            $lenght = strlen($localkeyresults['checktoken'])-32;            
            $timestamp = substr($localkeyresults['checktoken'],0,$lenght);

            $originalcheckdate = date('Ymd',$timestamp);
                        
            if ($originalcheckdate > $localexpiry) {
                $results = $localkeyresults;
                $checkdate = $results['checkdate'];
                $check_token = $results['checktoken'];
            } else {
                $results['status'] = "Invalid";
                $results['description'] = "Remote Check Failed";
                return $results;
            }
        } else {
            preg_match_all('/<(.*?)>([^<]+)<\/\\1>/i', $data, $matches);
            $results = array();
            foreach ($matches[1] AS $k=>$v) {
                $results[$v] = $matches[2][$k];
            }
        }
        if (!is_array($results)) {
            $results['status'] = "Invalid";
            $results['description'] = "Invalid License Server Response";
            return $results;
        }
        if ($results['md5hash']) {
            if ($results['md5hash'] != md5($licensing_secret_key . $check_token)) {
                $results['status'] = "Invalid";
                $results['description'] = "MD5 Checksum Verification Failed";
                return $results;
            }
        }
        if ($results['status'] == "Active") {
            $results['checkdate'] = $checkdate;
            $results['checktoken'] = $check_token;
            $data_encoded = serialize($results);
            $data_encoded = base64_encode($data_encoded);
            $data_encoded = md5($checkdate . $licensing_secret_key) . $data_encoded;
            $data_encoded = strrev($data_encoded);
            $data_encoded = $data_encoded . md5($data_encoded . $licensing_secret_key);
            $data_encoded = wordwrap($data_encoded, 80, "\n", true);
            $results['localkey'] = $data_encoded;
        }
        $results['remotecheck'] = true;
    }
    unset($postfields,$data,$matches,$whmcsurl,$licensing_secret_key,$checkdate,$usersip,$localkeydays,$allowcheckfaildays,$md5hash);
    if(isset($results['localkey']) && $results['localkey'] != '')
    {
		Mage::getModel('core/config')->saveConfig('modulesgarden/localkey/team_and_task_organizer_for_magento', $results['localkey']);
		Mage::app()->getCacheInstance()->cleanType('config');
    }
    
    switch($results["status"]){
        case 'Active':
            $results['description'] = 'Your module license is active.';
            break;
        case 'Invalid':
            $results['description'] = 'Your module license is invalid.';
            break;
        case 'Expired':
            $results['description'] = 'Your module license has expired.';
            break;
        case 'Suspended':
            $results['description'] = 'Your module license is suspended.';
            break;
        case 'Error':
            if(!$results['description']){
                $results['description'] = 'Connection not possible. Please report your server IP to contact@modulesgarden.com';
            }
            break;
        default:
            $results['description'] = 'Connection not possible. Please report your server IP to contact@modulesgarden.com';
            break;
    }

    return $results;
}
