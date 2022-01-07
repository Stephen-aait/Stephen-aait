<?php
/*
	Plugin Name: Images Gallery
	Plugin URI: http://www.webpsilon.com/wordpress-plugins/
	Description: Flash Images Gallery. Very easy to use. Load your images(jpg, gif, png) in: wp-content/plugins/images-gallery/images/
	Version: 1.1
	Author: Webpsilon
	Author URI: http://www.webpsilon.com/wordpress-plugins
*/	
$contador=0;
function images_gallery_head() {
	
	$site_url = get_option( 'siteurl' );
			echo '<script src="' . $site_url . '/wp-content/plugins/images-gallery/Scripts/swfobject_modified.js" type="text/javascript"></script>';
			
}
function images_gallery($content){
	$content = preg_replace_callback("/\[images_gallery ([^]]*)\/\]/i", "images_gallery_render", $content);
	return $content;
	
}

function images_gallery_render($tag_string){
$contador=rand(9, 9999999);
	$site_url = get_option( 'siteurl' );
global $wpdb; 	
$table_name = $wpdb->prefix . "images_gallery";	


if(isset($tag_string[1])) {
	$auxi1=str_replace(" ", "", $tag_string[1]);
	$myrows = $wpdb->get_results( "SELECT * FROM $table_name WHERE id = ".$auxi1.";" );
}
if(count($myrows)<1) $myrows = $wpdb->get_results( "SELECT * FROM $table_name;" );
	$conta=0;
	$id= $myrows[$conta]->id;
	$folder = $myrows[$conta]->folder;
	$timeback = $myrows[$conta]->timeback;
	$onover = $myrows[$conta]->onover;
	$width = $myrows[$conta]->width;
	$height = $myrows[$conta]->height;
	$links = $myrows[$conta]->links;
	$title = $myrows[$conta]->title;
	
		$type 		= 'png';
		$type1 		= 'jpg';
		$type2 		= 'gif';
		
		$files	= array();
		$images	= array();

		$dir = $folder;

		// check if directory exists
		if (is_dir($dir))
		{
			if ($handle = opendir($dir)) {
				while (false !== ($file = readdir($handle))) {
					if ($file != '.' && $file != '..' && $file != 'CVS' && $file != 'index.html' ) {
						$files[] = $file;
					}
				}
			}
			closedir($handle);

			$i = 0;
			foreach ($files as $img)
			{
				if (!is_dir($dir .DS. $img))
				{
					if (eregi($type, $img) || eregi($type1, $img)|| eregi($type2, $img)) {
						$images[$i]->name 	= $img;
						$images[$i]->folder	= $folder;
						++$i;
					}
				}
			}
			$cantidad=$i;
		}
		else $cantidad=0;




	$texto='';
	$texto='cantidad='.$cantidad.'&carpeta='.$folder.'&escala='.$onover.'&link='.$links.'&timeback='.$timeback;
	$imagesc=split("\n", $links);
	$conta=0;

	sort($images);
			foreach ($images as $img)
			{
						$auxilink="";

					if(isset($imagesc[$conta])) $auxilink=$imagesc[$conta];
					
					$texto.='&imagen'.$conta.'='.$site_url.'/'.$folder.''.$img->name;
					$texto.='&buton'.$conta.'='.$auxilink;
				
					$conta++;

			}
	
	
	$table_name = $wpdb->prefix . "images_gallery";
	$saludo= $wpdb->get_var("SELECT id FROM $table_name ORDER BY RAND() LIMIT 0, 1; " );
	$output='
	<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="'.$width.'" height="'.$height.'" id="Gallery'.$id.'-'.$contador.'" title="'.$title.'">
  <param name="movie" value="'.$site_url.'/wp-content/plugins/images-gallery/images-gallery.swf" />
  <param name="quality" value="high" />
  <param name="wmode" value="transparent" />
   <param name="scale" value="exactfit" />
  	<param name="flashvars" value="'.$texto.'" />
  <param name="swfversion" value="9.0.45.0" />
  <!-- This param tag prompts users with Flash Player 6.0 r65 and higher to download the latest version of Flash Player. Delete it if you don’t want users to see the prompt. -->
  <param name="expressinstall" value="'.$site_url.'/wp-content/plugins/images-gallery/Scripts/expressInstall.swf" />
  <!-- Next object tag is for non-IE browsers. So hide it from IE using IECC. -->
  <!--[if !IE]>-->
  <object type="application/x-shockwave-flash" data="'.$site_url.'/wp-content/plugins/images-gallery/images-gallery.swf" width="'.$width.'" height="'.$height.'">
    <!--<![endif]-->
    <param name="quality" value="high" />
    <param name="wmode" value="transparent" />
	  <param name="scale" value="exactfit" />
    	<param name="flashvars" value="'.$texto.'" />
    <param name="swfversion" value="9.0.45.0" />
    <param name="expressinstall" value="'.$site_url.'/wp-content/plugins/images-gallery/Scripts/expressInstall.swf" />
    <!-- The browser displays the following alternative content for users with Flash Player 6.0 and older. -->
    <div>
      <h4>Content on this page requires a newer version of Adobe Flash Player.</h4>
      <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" width="112" height="33" /></a></p>
    </div>
    <!--[if !IE]>-->
  </object>
  <!--<![endif]-->
</object>
<script type="text/javascript">
<!--
swfobject.registerObject("Gallery'.$id.'-'.$contador.'");
//-->
</script>
<br/>
<h6><a href="http://www.webpsilon.com/wordpress-plugins/" title="Images galleries and more wordpress plugins">Webpsilon Wordpress plugins</a></h6>
<br/>
';
	return $output;
}
function images_gallery_instala(){
	global $wpdb; 
	$table_name= $wpdb->prefix . "images_gallery";
   $sql = " CREATE TABLE $table_name(
		id mediumint( 9 ) NOT NULL AUTO_INCREMENT ,
		folder tinytext NOT NULL ,
		timeback tinytext NOT NULL ,
		onover tinytext NOT NULL ,
		links tinytext NOT NULL ,
		width tinytext NOT NULL ,
		height tinytext NOT NULL ,
		title tinytext NOT NULL ,
		PRIMARY KEY ( `id` )	
	) ;";
	$wpdb->query($sql);
	$sql = "INSERT INTO $table_name (folder, timeback,  onover, links, width, height, title) VALUES ('wp-content/plugins/images-gallery/images/', '5', '1', '', '100%', '250px', 'Webpsilon WP Plugins');";
	$wpdb->query($sql);
}
function images_gallery_desinstala(){
	global $wpdb; 
	$table_name = $wpdb->prefix . "images_gallery";
	$sql = "DROP TABLE $table_name";
	$wpdb->query($sql);
}	
function images_gallery_panel(){
	global $wpdb; 
	$table_name = $wpdb->prefix . "images_gallery";	
	
	if(isset($_POST['crear'])) {
		$re = $wpdb->query("select * from $table_name");
//autos  no existe
if(empty($re))
{
  $sql = " CREATE TABLE $table_name(
		id mediumint( 9 ) NOT NULL AUTO_INCREMENT ,
		folder tinytext NOT NULL ,
		timeback tinytext NOT NULL ,
		onover tinytext NOT NULL ,
		links tinytext NOT NULL ,
		width tinytext NOT NULL ,
		height tinytext NOT NULL ,
		title tinytext NOT NULL ,
		PRIMARY KEY ( `id` )		
	) ;";
	$wpdb->query($sql);

}
		
		$sql = "INSERT INTO $table_name (folder, timeback,  onover, links, width, height, title) VALUES ('wp-content/plugins/images-gallery/images/', '5', '1', '', '100%', '250px', 'Webpsilon flash easy gallery');";
	$wpdb->query($sql);
	}
	
if(isset($_POST['borrar'])) {
		$sql = "DELETE FROM $table_name WHERE id = ".$_POST['borrar'].";";
	$wpdb->query($sql);
	}
	if(isset($_POST['id'])){	
	if($_POST["onover".$_POST['id']]=="") $_POST["onover".$_POST['id']]=0;
			$sql= "UPDATE $table_name SET `folder` = '".$_POST["folder".$_POST['id']]."', `timeback` = '".$_POST["timeback".$_POST['id']]."', `onover` = '".$_POST["onover".$_POST['id']]."', `links` = '".$_POST["links".$_POST['id']]."', `width` = '".$_POST["width".$_POST['id']]."', `height` = '".$_POST["height".$_POST['id']]."', `title` = '".$_POST["title".$_POST['id']]."' WHERE `id` =  ".$_POST["id"]." LIMIT 1";
			$wpdb->query($sql);
	}
	$myrows = $wpdb->get_results( "SELECT * FROM $table_name" );
$conta=0;

include('template/cabezera_panel.html');
while($conta<count($myrows)) {
	$id= $myrows[$conta]->id;
	$folder = $myrows[$conta]->folder;
	$timeback = $myrows[$conta]->timeback;
	$onover = $myrows[$conta]->onover;
	$width = $myrows[$conta]->width;
	$height = $myrows[$conta]->height;
	$links = $myrows[$conta]->links;
	$title = $myrows[$conta]->title;
	include('template/panel.html');			
	$conta++;
	}

}
function images_gallery_add_menu(){	
	if (function_exists('add_options_page')) {
		//add_menu_page
		add_options_page('images_gallery', 'Images Gallery', 8, basename(__FILE__), 'images_gallery_panel');
	}
}
if (function_exists('add_action')) {
	add_action('admin_menu', 'images_gallery_add_menu'); 
}
add_action('wp_head', 'images_gallery_head');
add_filter('the_content', 'images_gallery');
add_action('activate_images_gallery/images_gallery.php','images_gallery_instala');
add_action('deactivate_images_gallery/images_gallery.php', 'images_gallery_desinstala');
?>