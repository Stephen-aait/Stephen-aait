<?php
/*
Plugin Name: IM Online
PLugin URI: http://www.mutube.com/projects/wordpress/im-online/
Description: Display status for MSN, Yahoo!, AOL, Jabber and ICQ via <a href="http://www.onlinestatus.org">onlinestatus.org</a>.
Author: Martin Fitzpatrick
Version: 4.3
Author URI: http://www.mutube.com
*/

@define("IMONLINE_VERSION", "4.1");
@define('IMONLINE_DIRPATH','/wp-content/plugins' . strrchr(dirname(__FILE__),'/') . '/');

@define("IMONLINE_MAX_ACCOUNTS", 7);

@define("IMONLINE_DEBUG", false);

/*  Copyright 2006  MARTIN FITZPATRICK  (email : martin.fitzpatrick@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

/*
	CONSTANTS FOR CONFIGURATION
*/

/*

   STANDARD OUTPUT FUNCTIONS
   These are out of the main function block below so they can be called
   from outside "widget-space".  This means we can re-use code for widget
   and non-widget versions

*/

//Kept external for backward compatibility
if(!function_exists('imonline_status')) {

function imonline_status() {
	global $imonline;
	
	$imonline->display();

}

}



/*

  INITIALISATION
  All functions in here called at startup (after other plugins have loaded, in case
  we need to wait for the widget-plugin.
  Both versions (widget & none) handled in here to allow for re-use of code

*/

class imonline {

	var $servers=array(
		"http://www.the-server.net:8000/",
		"http://www.the-server.net:8001/",
		"http://www.the-server.net:8002/",
		"http://www.the-server.net:8003/",
		"http://onlinestatus.chrissearle.org:54345/",
		"http://imstatus.msitgroup.co.uk:81/",
		"http://osi.techno-st.net:8000/",
		"http://osi.danvic.co.uk/",
		"http://msgstatus.joker-solutions.net:8080/"
	);


	var $networks=array("aim"=>"AIM",
					"icq"=>"ICQ",
					"irc"=>"IRC",
					"jabber"=>"Jabber",
					"msn"=>"MSN",
					"skype"=>"Skype",
					"yahoo"=>"Yahoo!"
				);

	var $seconds;
	var $debug=array();

	function debug($msg=false)
	{
			if($msg){ $this->debug[]=(time()- $this->seconds) . 'secs > ' . $msg; }
			else {	foreach ($this->debug as $line) {$content.=$line . "\n"; } return $content; }
	}

	function display() {

			$options = get_option('widget_imonline');
			
			$options = $this->update_status($options);

		    //Generate base url to this local server
            echo '<!-- Begin Online Status Indicator code -->' . "\n" . '<!-- http://www.onlinestatus.org/ -->' . "\n" . '<!-- IM ONLINE ' . IMONLINE_VERSION .  '-->' . "\n" . '<div id="imonline">';

			$options = get_option('widget_imonline');

			if(is_array($options['accounts'])) {
				foreach($options['accounts'] as $account)
				{
					$this->display_icon($account);
				}
			}

			?><p id="imonline-powered" style="text-align:right"><small>Powered by <a href="http://www.mutube.com/projects/wordpress/im-online/?utm_source=plugin&amp;utm_medium=sidebar">IM Online</a></small></p></div>
            <!-- End Online Status Indicator code --><?php 

    }



	function display_icon($account) {

		$onclick_send="";
		$url_send="#";
		$extra_send="";

		switch ($account['network']) {
			case 'aim':$url_send="aim:goim?screenname=" . $account['userid']; break;
			case 'irc':$url_send="irc://" . trim(strstr($account['userid'],'@'),'@'); break;
			case 'icq':$url_send="http://www.icq.com/people/about_me.php?uin=" . $account['userid']; break;
			case 'jabber':$url_send="xmpp:" . $account['userid']; break;
			case 'skype':$url_send="skype:" . $account['userid'] . '?chat'; break;
			case 'yahoo':$url_send="ymsgr:sendIM?" . $account['userid']; break;
			case 'msn':$onclick_send="MsgrObj.InstantMessage('" . $account['userid'] . "'); return false;"; $extra_send='<object classid="clsid:B69003B3-C55E-4B48-836C-BC5946FC3B28" codetype="application/x-oleobject" id="MsgrObj" width="0" height="0"></object>'; break;
		}

		$icons_baseurl = get_bloginfo('wpurl') . IMONLINE_DIRPATH . "images/";

		switch ($account['status']) {
			case 'online':break;
			case 'offline':break;
			default:$account['status']='unknown';
		}

		echo $extra_send;
		$imageurl=$icons_baseurl . $account['network'] . $account['status'] . ".gif";

		?><a href="<?php echo $url_send ?>" onclick="<?php echo $onclick_send ?>" style="display:inline" title="<?php echo '[' . $this->networks[$account['network']] . '] ' . $account['userid']; ?> (Update: <?php echo round( max(0,($account['status-update']-time())/60) ) ; ?>mins)"><img src="<?php echo $imageurl; ?>" style="margin-left:5px;border:0px" alt="<?php echo $this->networks[$account['network']] . " : " . ucfirst($account['status']); ?> "/></a><?php

	}



	function update_status($options) {
		
		$this->debug('Updating Status.');

		//Select accounts to update based on timeout values
		$va=array();
		for($a=0;$a<sizeof($options['accounts']);$a++)
			{ if($options['accounts'][$a]['status-update'] <= time()){array_push($va,$a);} }

		$this->debug(sizeof($va) . ' statuses to update');

		//More than the defined wait has passed for at least one.  Update.		
		if( sizeof($va)>0 ){			

			$account=$va[rand(0,sizeof($va)-1)];

			$plugin_baseurl = get_bloginfo('wpurl') . IMONLINE_DIRPATH;
			$plugin_baseurl = str_replace("http://","",$plugin_baseurl);

				if (!is_array($options['servers'])){
					$options['servers']=$this->servers; //Reset server preference?
					shuffle($options['servers']);
				}

			//Extract servers (listed in order of pref)			
			for($s=0;$s<sizeof($options['servers']);$s++) //try each one
			{
				$this->debug('Attempting server #' . $s);

				$server = $options['servers'][$s];
		    	$url_request =	$server . 
								$options['accounts'][$account]['network'] . '/'  .
								$options['accounts'][$account]['userid'] .
								'/onurl=' . $plugin_baseurl . 'online' . 
								'/offurl=' . $plugin_baseurl . 'offline' .
								'/unknownurl=' . $plugin_baseurl . 'unknown';				

				$this->debug('URL:' . $url_request);

				//We only update status if it is not unknown.
				//Make sure we don't result in blank status.
				$result=$this->fetch_url($url_request);

				if ( ($result==='online') || ($result==='offline') ) { 

					$this->debug('Valid result: ' . $result);
					//Success, move this server to top preference
					unset($options['servers'][$s]);
					array_unshift($options['servers'],$server);

					$options['accounts'][$account]['status'] = $result;			
					$success = true;
					break;
					
				} else {					
					//Limit to loop of 
					//$s = $options['accounts'][$account]['server'];
					//$options['accounts'][$account]['server'] = ($s>=sizeof($this->servers)-1)?0:$s+1;
					$options['accounts'][$account]['status'] = 'unknown';
					$success = false;
					$this->debug('Invalid result. Retrying.');
				}

			}

			//Delay time for next check.
			$options['accounts'][$account]['status-update'] = time()+(60*$options['update']);

			//Save the result for next time
			update_option('widget_imonline', $options);
		}

		return $success;
	}


	/*	Because PHP now blocks cURL redirects under certain conditions, this function
		provides that functionality in the plugin itself. */
	/* 	Note that this function is broken to speed up Online Status checking, does not
		return file but end of URL. Do not use for any other purposes.
		See online php.net documentation for working version of this function */

	function curl_exec_redirect($ch)
	{
		$data = false;
		
		$data = curl_exec($ch);

		list($header, $data) = explode("\r\n\r\n", $data, 2);

		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		if ($http_code == 301 || $http_code == 302)
		{
				$matches = array();
				preg_match('/Location:(.*?)\n/', $header, $matches);
				$redir = @parse_url(trim(array_pop($matches)));
				$this->debug('Redirect: ' . $redir);

				if ($redir) {

					$data = substr(strrchr($redir['path'],"/"),1);

				} else { return false; /* Invalid redirection URL */  }

		} else { return false; /* Not redirecting. Boo. */ }

		return $data;

	}


	/* Fetch remote url, using cURL if available or fallback to file_get_contents */
	function fetch_url($url)
	{

		/* Use cURL if it is available, otherwise attempt fopen */
		if(function_exists('curl_init'))
		{ 
			$this->debug('Using (Modified) cURL for request...');
	
			/*	
				Request data using cURL library
				With thanks to Marcin Juszkiewicz
				http://www.hrw.one.pl/
			*/
						
			$ch = curl_init();
	
			@curl_setopt($ch, CURLOPT_HEADER, true);
			@curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			@curl_setopt($ch, CURLOPT_FAILONERROR, true);	//Die if we received >404
			@curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);	//x seconds to give up this connect

			curl_setopt($ch, CURLOPT_URL, $url);

			// grab URL and pass it to the browser
			$data = $this->curl_exec_redirect($ch);
			
			$this->debug('Result: ' . $data);			

			if ((curl_errno($ch)) || ($data===true)) {
				$data=false;
			}

 			// close curl resource, and free up system resources
 			curl_close($ch);
			
			$this->debug('cURL closed.');			

		} else {
			$this->debug('cURL not installed. Using file_get_contents()...');
			$data=@file_get_contents ( $url );
			$this->debug('Result: ' . $data);	
		} /* If cURL is not installed use file_get_contents */
			
 		return $data;
	}

/*
         STANDARD ADMIN FORM
         This form used by both widget & non widget forms (non-widget requires wrapper elsewhere,
         the widget wrapper is provided by the system

*/
		function control_form() {

		// Get our options and see if we're handling a form submission.
		$options = get_option('widget_imonline');

		if ( !is_array($options) )
		{
			$options = array(
								'title'=>'IM Online',
								'server'=>0,
								'accounts'=>array(),
								'update'=>5
                        );
		}

		//Upgrade code - copies details over from previous installation using old data layout.
		//Still requires saving to apply modifications to db
		if(!is_array($options['accounts']) && strlen($options['msn'].$options['yahoo'].$options['skype'].$options['icq'].$options['jabber'].$options['irc'].$options['aim'])>0)
		if(true)	
		{
			$n=0;
			foreach($this->networks as $net=>$netname)
			{
				if($options[$net]!=''){		
					$options['accounts'][$n]['network']=$net;
					$options['accounts'][$n]['userid']=$options[$net];
					$n++;
				}
			}
			
		}

		if ( $_POST['imonline-submit'] ) {

			// Remember to sanitize and format use input appropriately.
			$options['title'] = strip_tags(stripslashes($_POST['imonline-title']));
			$options['update'] = strip_tags(stripslashes($_POST['imonline-update']));
			
			if($options['update']<5){$options['update']=5;}

			$options['servers']=$this->servers; //Reset server preference?
			shuffle($options['servers']); //Do not favour any server (prevent hammering)


			$saveaccount=0;
			for($account=0;$account<IMONLINE_MAX_ACCOUNTS;$account++)
			{	
				if($_POST['account-'. $account .'-userid']!="")
				{
					$options['accounts'][$saveaccount]['network']=strip_tags(stripslashes($_POST['account-'.$account.'-network']));
					$options['accounts'][$saveaccount]['userid']=strip_tags(stripslashes($_POST['account-'.$account.'-userid']));
					$options['accounts'][$saveaccount]['status-update']=time(); //Update immediately

					//No valid status set yet... unknown status
					$options['accounts'][$saveaccount]['status'] = 'unknown';

					$saveaccount++;
					//Update status here? Probably best not to? Sloowww//
				}
			}

			array_splice($options['accounts'],$saveaccount,IMONLINE_MAX_ACCOUNTS-$saveaccount); //Clear end of account array

			update_option('widget_imonline', $options);

			if($_POST['imonline-debug']) 
			{
				$this->debug('IM Online v' . IMONLINE_VERSION . ': Debug Enabled');	
				$this->update_status($options,true);
				$this->debug('End.');	
				@wp_mail(get_settings('admin_email'), "IM Online Debug Information", $this->debug(), "");
			}

		}

		// Here is our little form segment. Notice that we don't need a
		// complete form. This will be embedded into the existing form.

		?>
                <div style="">
				<table>
                <tr><td style="font-weight:bold;text-align:right;"><label for="imonline-title" >Title:</label></td><td><input style="width: 200px;" id="imonline-title" name="imonline-title" type="text" value="<?php echo htmlspecialchars($options['title'], ENT_QUOTES);?>" /></td></tr>
				<tr><td>&nbsp;</td></tr>

                <?php

				for($account=0;$account<IMONLINE_MAX_ACCOUNTS;$account++)
				{	
					?><tr><td>
					<select name="account-<?php echo $account ?>-network">
					<?php

                	foreach($this->networks as $network_id=>$network_name)
                	{
						echo '<option value="' . $network_id . '"';
						if($network_id==$options['accounts'][$account]['network']) echo ' selected="selected" ';
						echo '>' . $network_name . '</option>';
                	}
                	?>
					</select></td>		
				<td><input style="width: 200px;" name="account-<?php echo $account; ?>-userid" type="text" value="<?php echo $options['accounts'][$account]['userid']?>" /></td></tr>
				<?php

				}

				?>
				<tr><td>&nbsp;</td></tr>
				<tr><td style="font-weight:bold;text-align:right;"><label for="imonline-update" >Update:</label></td><td><input style="width: 20px;" id="imonline-update" name="imonline-update" type="text" value="<?php echo htmlspecialchars($options['update'], ENT_QUOTES);?>" /> minutes</td></tr>
		</table>

<p style="text-align:justify;">If you have problems getting online status get a <input style="padding:0; margin:0; background: none; " type="submit" name="imonline-debug" value="connection report" onclick="alert('An message will be sent to the Admin email address with connection test information. Please check shortly.');"> and <a href="http://www.mutube.com/projects/wordpress/im-online">submit it here.</a></p>

		<input type="hidden" id="imonline-submit" name="imonline-submit" value="1" />

		</div>
		<?php
           }


/*

           SWITCH: IS THE WIDGET PLUGIN LOADED?
           If it is, then we use the widget system for admin. If it isn't we use the old-style.
           Note, the "standard" output method is available regardless of where you're editing
           the admin options.

*/

 	    // This is the function that outputs imonline status.
	    function widget($args) {

		// $args is an array of strings that help widgets to conform to
		// the active theme: before_widget, before_title, after_widget,
		// and after_title are the array keys. Default tags: li and h2.
		extract($args);

		// Each widget can store its own options. We keep strings here.
		$options = get_option('widget_imonline');
                $title = $options['title'];

		// These lines generate our output. Widgets can be very complex
		// but as you can see here, they can also be very, very simple.
		echo $before_widget . $before_title . $title . $after_title;
			$this->display(); //main call to get imonline icon
		echo $after_widget;
	    }




	    function widget_control() {
                     //pass-thru (no extras required)
                     $this->control_form();
            }

        function standard_control()
        {
         ?><div class="wrap">
         <h2>IM Online Options</h2>
         <div style="margin-top:20px;">
         <form action="" method="post">
          <?php

               $this->control_form();

         ?>
         <p class="submit"><input type="submit" value="Save changes &raquo;"></p>
         </form></div></div><?php

        }

		function add_pages()
		{
         add_options_page("IM Online Options", "IM Online", 10, "im-online", array(&$this,'standard_control'));
		}


		function init()
		{
			$this->seconds = time(); //Reset execution time clock for debug

			if ( function_exists('register_sidebar_widget') )
			{   //Do Widget-specific code

				// This registers our widget so it appears with the other available
				// widgets and can be dragged and dropped into any active sidebars.
				register_sidebar_widget('IM Online Widget', 'imonline_widget');

				// This registers our optional widget control form. Because of this
				// our widget will have a button that reveals a 300x100 pixel form.
				register_widget_control('IM Online Widget', 'imonline_widget_control', 300, 420);

			} else { 
				add_action('admin_menu', array(&$this,'add_pages'));
			}

		}


}


$imonline = new imonline();


/*	
	SIDEBAR MODULES COMPATIBILITY KLUDGE 
	These functions are external to the class above to allow compatibility with SBM
	which does not allow calls to be passed to a class member.
	These functions are dummy passthru's for the real functions above
*/

	function imonline_widget($args){
		global $imonline;
		$imonline->widget($args);
	}

	function imonline_widget_control(){
		global $imonline;
		$imonline->widget_control();
	}

/*
	END DUMMY KLUDGE
*/


// Run our code later in case this loads prior to any required plugins.
add_action('plugins_loaded', array(&$imonline,'init'));

?>
