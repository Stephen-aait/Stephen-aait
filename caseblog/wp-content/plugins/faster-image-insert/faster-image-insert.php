<?php
/*
Plugin Name: Faster Image Insert
Plugin URI: http://blog.ticktag.org/2009/02/19/2765/
Description: Fully integrates media manager into editing interface, avoid having to reload it separately in thickbox pop-up; comes with enhanced features, suitable for precise image control.
Author: David Frank
Version: 1.5.1
Author URI: http://blog.ticktag.org/
*/

//forbidden direct access to plugin
if (eregi(basename(__FILE__),$_SERVER['PHP_SELF'])) {
  if (!headers_sent()) {
    header('HTTP/1.1 403 Forbidden');
    ?>
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title>403 Forbidden</title>
</head><body>
<h1>Forbidden</h1>
<p>You don't have permission to access this resource.</p>
</body></html>
    <?php
  }
  exit;
}

//iframe for media manager.
function fast_insert_form() {
  global $post_ID, $temp_ID;
  $id = (int) (0 == $post_ID ? $temp_ID : $post_ID);

  $load_iframe = 'faster_insert_load_iframe';
  $nojquery = get_option( $load_iframe );

  $upload_form = 'faster_insert_upload_form';
  $noflash = get_option( $upload_form );
  
  if(!$nojquery) {

?>
<script type="text/javascript">
/* <![CDATA[ */
  jQuery(function($) {
    //intialize
    $('#screen-meta').ready(function() {
      var view = $('#fastinsert-hide').is(':checked');
      if(view) {
	    <?php if($id > 0) { ?>
        $('#fastinsert > .inside').html('<iframe frameborder="0" name="fast_insert" id="fast_insert" src="<?php echo get_option("siteurl") ?>/wp-admin/media-upload.php?post_id=<?php if($noflash) echo $id.'&#038;flash=0'; else echo $id; ?>&#038;type=image&#038;tab=type" hspace="0"> </iframe>');
        <?php } else { ?>
        $('#fastinsert > .inside').html('<p><?php _e('Click here to reload after autosave. Or manually save the draft.', 'faster-image-insert') ?></p>');
        <?php } ?>
      }
    });
    //toggle metabox
    $('#screen-meta #fastinsert-hide').click(function() {
      var view = $('#fastinsert-hide').is(':checked');
      if(view) {
	    <?php if($id > 0) { ?>
        $('#fastinsert > .inside').html('<iframe frameborder="0" name="fast_insert" id="fast_insert" src="<?php echo get_option("siteurl") ?>/wp-admin/media-upload.php?post_id=<?php if($noflash) echo $id.'&#038;flash=0'; else echo $id; ?>&#038;type=image&#038;tab=type" hspace="0"> </iframe>');
        <?php } else { ?>
        $('#fastinsert > .inside').html('<p><?php _e('Click here to reload after autosave. Or manually save the draft.', 'faster-image-insert') ?></p>');
        <?php } ?>
      }
    });
    <?php if($id < 0) { ?>
	//update state after autosave, bind load event.
    $('#fastinsert').click(function() {
	  var newid = $('#post_ID').val();
	  if(notSaved == false && newid > 0) {
	    $('#fastinsert > .inside').html('<iframe frameborder="0" name="fast_insert" id="fast_insert" src="<?php echo get_option("siteurl") ?>/wp-admin/media-upload.php?post_id='+newid+'<?php if($noflash) echo '&#038;flash=0'; ?>&#038;type=image&#038;tab=type" hspace="0"> </iframe>');
        $('#fast_insert').bind("load", function() {
	      if($(this).contents().find('#media-upload').length < 1) {
	        document.getElementById('fast_insert').contentWindow.location.href = document.getElementById('fast_insert').contentWindow.location.href;
          }
        });
      }
    });
	<?php } ?>
    <?php if($id > 0) { ?>
	//update state on insert
    $('#fast_insert').bind("load", function() {
	  if($(this).contents().find('#media-upload').length < 1) {
	    document.getElementById('fast_insert').contentWindow.location.href = document.getElementById('fast_insert').contentWindow.location.href;
      }
    });
	<?php } ?>
  });
/* ]]> */
</script>
<?php
  //metabox without javascript
  } else {
    if($noflash && $id > 0)
      echo '<iframe frameborder="0" name="fast_insert" id="fast_insert" src="'.get_option("siteurl").'/wp-admin/media-upload.php?post_id='.$id.'&#038;flash=0&#038;type=image&#038;tab=type" hspace="0"> </iframe>';
    elseif ($id > 0)
      echo '<iframe frameborder="0" name="fast_insert" id="fast_insert" src="'.get_option("siteurl").'/wp-admin/media-upload.php?post_id='.$id.'&#038;type=image&#038;tab=type" hspace="0"> </iframe>';
    else
	  _e('Click here to reload after autosave. Or manually save the draft.', 'faster-image-insert');
  }
}

//replace several scripts for new functions.
function fast_image_insert() 
{
  $plugindebug = 'faster_insert_plugin_debug';
  $debug = get_option( $plugindebug );
  
  //since 1.4.0: spot wordpress 2.8+ automagically
  global $wp_version;
  if (version_compare($wp_version, '2.8.0', '>=')) {
    $compat = true;
  } else {
    $compat = false;
  }
  if(!$compat && !$debug) {
  //fix a quicktag bug that cause textarea scrollbar reset to top. It's fixed in 2.8+
  wp_deregister_script('quicktags');
  wp_register_script('quicktags',get_option("siteurl").'/wp-content/plugins/faster-image-insert/quicktags.dev.js');
  }
  
  //integrates manager into post/page edit inferface.
  add_meta_box('fastinsert', 'Fast Insert', 'fast_insert_form', 'post', 'normal', 'high');
  add_meta_box('fastinsert', 'Fast Insert', 'fast_insert_form', 'page', 'normal', 'high');
}

// various javascript / css goodies for:
// 1. quicktags i18n
// 2. selected insert
// 3. mass-editing
// 4. styling for iframe and mass-edit table
function faster_insert_local() {
  
  //since 1.4.0: spot wordpress 2.8+ automagically
  global $wp_version;
  if (version_compare($wp_version, '2.8.0', '>=')) {
    $compat = true;
  } else {
    $compat = false;
  }
  
?>
<script type="text/javascript">
/* <![CDATA[ */
  <?php if(!$compat) { ?>
  quicktagsL10n = {
    quickLinks: "<?php _e('(Quick Links)'); ?>",
    wordLookup: "<?php _e('Enter a word to look up:'); ?>",
    dictionaryLookup: "<?php echo attribute_escape(__('Dictionary lookup')); ?>",
    lookup: "<?php echo attribute_escape(__('lookup')); ?>",
    closeAllOpenTags: "<?php echo attribute_escape(__('Close all open tags')); ?>",
    closeTags: "<?php echo attribute_escape(__('close tags')); ?>",
    enterURL: "<?php _e('Enter the URL'); ?>",
    enterImageURL: "<?php _e('Enter the URL of the image'); ?>",
    enterImageDescription: "<?php _e('Enter a description of the image'); ?>"
  }
  try{convertEntities(quicktagsL10n);}catch(e){};
  <?php } ?>
  
  jQuery(function($) {
  
    //$('#fastinsert').resizable();
    $('#media-items .new').each(function(e) {
      var id = $(this).parent().attr('id');
      id = id.split("-")[2];
      $(this).prepend('<input type="checkbox" class="item_selection" title="<?php _e('Select items you want to insert','faster-image-insert'); ?>" id="attachments[' + id.substring() + '][selected]" name="attachments[' + id + '][selected]" value="selected" /> ');
    });

    //buttons for enhanced functions
    $('.ml-submit:first').append('<input type="submit" class="button savebutton" name="insertall" id="insertall" value="<?php echo attribute_escape( __( 'Insert selected images', 'faster-image-insert') ); ?>" /> ');  
    $('.ml-submit:first').append('<input type="submit" class="button savebutton" name="invertall" id="invertall" value="<?php echo attribute_escape( __( 'Invert selection', 'faster-image-insert') ); ?>" /> ');
    <?php if(!$compat) { ?>
    if($('#media-search').length == 0) {
      $('.ml-submit:first').append('<input type="submit" class="button savebutton" name="orderreverse" id="orderreverse" value="<?php echo attribute_escape( __( 'Reversed ordering', 'faster-image-insert') ); ?>" /> ');
    }
    <?php } ?>
    $('.ml-submit:first').append('<input type="submit" class="button savebutton" name="expandall" id="expandall" value="<?php echo attribute_escape( __( 'Toggle items', 'faster-image-insert') ); ?>" /> ');
    $('.ml-submit #invertall').click(
      function(){
        $('#media-items .item_selection').each(function(e) {
          if($(this).is(':checked')) $(this).attr("checked","");
          else $(this).attr("checked","checked");
        });
        return false;
      }
    );
    //500 should be enough for everyone :->
    <?php if(!$compat) { ?>
    $('.ml-submit #orderreverse').click(
      function(){
        var i = 500;
        $('#media-items .menu_order_input').each(function(e) {
          $(this).val(i); i--;
        });
        return false;
      }
    );
    <?php } ?>
    $('.ml-submit #expandall').click(
      function(){
        $('#media-items .media-item').each(function(e) {
          $(this).find(".describe-toggle-on").toggle();
          $(this).find(".describe-toggle-off").toggle();
          $(this).find(".slidetoggle").toggle();
        });
        return false;
      }
    );
    
<?php 
  $mass_edit = 'faster_insert_mass_edit';
  $mass = get_option( $mass_edit );
  if($mass) {
  //mass editing
?>
    
    if($('#gallery-settings').length > 0) {
      $('#gallery-settings').before('<div id="mass-edit"><div class="title"><?php _e('Mass Image Edit','faster-image-insert'); ?></div></div>');
      $('#gallery-settings .describe').clone().appendTo('#mass-edit');
      $('#mass-edit').append('<p class="ml-submit"><input type="button" class="button" name="massedit" id="massedit" value="<?php _e('Apply changes','faster-image-insert'); ?>" /> <span><?php _e('Press "Save all changes" to save. Only Title/Captions can be permanently saved.','faster-image-insert'); ?></span></p>');

      $('#mass-edit tr:eq(0) .alignleft').html('<?php _e('Image Titles','faster-image-insert'); ?>');
      $('#mass-edit tr:eq(1) .alignleft').html('<?php _e('Image Captions / Alt-Texts','faster-image-insert'); ?>');
      $('#mass-edit tr:eq(2) .alignleft').html('<?php _e('Image Alignment','faster-image-insert'); ?>');
      $('#mass-edit tr:eq(3) .alignleft').html('<?php _e('Image Sizes','faster-image-insert'); ?>');
    
      $('#mass-edit tr:eq(0) .field').html('<input type="text" name="title_edit" id="title_edit" value="" />');
      $('#mass-edit tr:eq(1) .field').html('<input type="text" name="captn_edit" id="captn_edit" value="" />');
      $('#mass-edit tr:eq(2) .field').html('<input type="radio" name="align_edit" id="align_none" value="none" />\n<label for="align_none" class="radio"><?php _e('None') ?></label>\n<input type="radio" name="align_edit" id="align_left" value="left" />\n<label for="align_left" class="radio"><?php _e('Left') ?></label>\n<input type="radio" name="align_edit" id="align_center" value="center" />\n<label for="align_center" class="radio"><?php _e('Center') ?></label>\n<input type="radio" name="align_edit" id="align_right" value="right" />\n<label for="align_right" class="radio"><?php _e('Right') ?></label>');
      $('#mass-edit tr:eq(3) .field').html('<input type="radio" name="size_edit" id="size_thumb" value="thumbnail" />\n<label for="size_thumb" class="radio"><?php _e('Thumbnail') ?></label>\n<input type="radio" name="size_edit" id="size_medium" value="medium" />\n<label for="size_medium" class="radio"><?php _e('Medium') ?></label>\n<input type="radio" name="size_edit" id="size_large" value="large" />\n<label for="size_large" class="radio"><?php _e('Large') ?></label>\n<input type="radio" name="size_edit" id="size_full" value="full" />\n<label for="size_full" class="radio"><?php _e('Full size') ?></label>');

      $('#massedit').click(function() {
        var massedit = new Array();
        massedit[0] = $('#mass-edit .describe #title_edit').val();
        massedit[1] = $('#mass-edit .describe #captn_edit').val();
        massedit[2] = $('#mass-edit tr:eq(2) .field input:checked').val();
        massedit[3] = $('#mass-edit tr:eq(3) .field input:checked').val();
        //alert(massedit);
        $('.media-item').each(function(e) {
          if(typeof massedit[0] !== "undefined" && massedit[0].length > 0) $(this).find('.post_title .field input').val(massedit[0]);
          if(typeof massedit[1] !== "undefined" && massedit[1].length > 0) $(this).find('.post_excerpt .field input').val(massedit[1]);
          if(typeof massedit[2] !== "undefined" && massedit[2].length > 0) {
            $(this).find('.align .field input[value='+massedit[2]+']').attr("checked","checked");
          }
          if(typeof massedit[3] !== "undefined" && massedit[3].length > 0) {
            $(this).find('.image-size .field input[value='+massedit[3]+']').attr("checked","checked");
          }
        });
      });
    }
<?php } ?>
  });

/* ]]> */
</script>
<style type="text/css" media="screen">
#fast_insert{width:100%;height:500px;}
<?php if($mass) { ?>
#mass-edit th.label{width:160px;}
#mass-edit #basic th.label{padding:5px 5px 5px 0;}
#mass-edit .title{clear:both;padding:0 0 3px;border-bottom-style:solid;border-bottom-width:1px;font-family:Georgia,"Times New Roman",Times,serif;font-size:1.6em;border-bottom-color:#DADADA;color:#5A5A5A;}
#mass-edit .describe td{vertical-align:middle;height:3.5em;}
#mass-edit .describe th.label{padding-top:.5em;text-align:left;}
#mass-edit .describe{padding:5px;width:615px;clear:both;cursor:default;}
#mass-edit .describe select,#mass-edit .describe input[type=text]{width:15em;border:1px solid #dfdfdf;}
#mass-edit label,#mass-edit legend{font-size:13px;color:#464646;margin-right:15px;}
#mass-edit .align .field label{margin:0 1.5em 0 0;}
#mass-edit p.ml-submit{border-top:1px solid #dfdfdf;}
#mass-edit select#columns{width:6em;}
<?php } ?>
</style>
<?php
}

//used for passing content to edit panel.
function fast_insert_to_editor($html) {
?>
<script type="text/javascript">
/* <![CDATA[ */
var win = window.dialogArguments || opener || parent || top;
win.send_to_editor('<?php echo str_replace('\\\n','\\n',addslashes($html)); ?>');
/* ]]> */
</script>
  <?php
  exit;
}

//catches the insert all images post request.
function faster_insert_form_handler() {
  global $post_ID, $temp_ID;
  $post_id = (int) (0 == $post_ID ? $temp_ID : $post_ID);
  check_admin_referer('media-form');
  
  $customstring = 'faster_insert_plugin_custom';
  $cstring = get_option( $customstring );
  
  $line_number = 'faster_insert_line_number';
  $number = get_option( $line_number );
  
  $image_line = 'faster_insert_image_line';
  $oneline = get_option( $image_line );
  
  if(!is_numeric($number)) $number = 4;

  if ( !empty($_POST['attachments']) ) {
    $result = '';
    foreach ( $_POST['attachments'] as $attachment_id => $attachment ) {
      $attachment = stripslashes_deep( $attachment );
      if (!empty($attachment['selected'])) {
        $html = $attachment['post_title'];
        if ( !empty($attachment['url']) ) {
          if ( strpos($attachment['url'], 'attachment_id') || false !== strpos($attachment['url'], get_permalink($post_id)) )
            $rel = " rel='attachment wp-att-".attribute_escape($attachment_id)."'";
          $html = "<a href='{$attachment['url']}'$rel>$html</a>";
        }
        $html = apply_filters('media_send_to_editor', $html, $attachment_id, $attachment);
        //since 1.5.0: &nbsp; is the same as a blank space, but can be passed onto TinyMCE
        if(!$oneline) $result .= $html.str_repeat("\\n".$cstring."\\n",$number);
        else $result .= $html.str_repeat($cstring,$number);
      }
    }
    return fast_insert_to_editor($result);
  }

  return $errors;
}

//filter for media_upload_gallery, recognize insertall request.
function faster_insert_media_upload_gallery() {
  if ( isset($_POST['insertall']) ) {
    $return = faster_insert_form_handler();
    
    if ( is_string($return) )
      return $return;
    if ( is_array($return) )
      $errors = $return;
  }
}

function faster_insert_media_upload_library() {
  if ( isset($_POST['insertall']) ) {
    $return = faster_insert_form_handler();
    
    if ( is_string($return) )
      return $return;
    if ( is_array($return) )
      $errors = $return;
  }
}

//for disabling captions
function caption_off() {
  $no_caption = 'faster_insert_no_caption';
  $nocaption = get_option( $no_caption );
  if($nocaption)
    return true;
}

//adds a new submenu for options
function faster_insert_option() {
    add_options_page(__('Faster Image Insert - User Options','faster-image-insert'), 'Faster Image Insert', 8, __FILE__, 'faster_insert_option_detail');
}

//display the actual content of option page.
function faster_insert_option_detail() {  

  $faster_insert_update = 'faster_insert_update';
  $faster_insert_delete = 'faster_insert_delete';
  $faster_insert_valid = 'faster_insert_valid';

  //all the options
  $load_iframe = 'faster_insert_load_iframe';
  $upload_form = 'faster_insert_upload_form';
  $image_line = 'faster_insert_image_line';
  $line_number = 'faster_insert_line_number';
  $mass_edit = 'faster_insert_mass_edit';
  $no_caption = 'faster_insert_no_caption';
  $plugindebug = 'faster_insert_plugin_debug';
  $customstring = 'faster_insert_plugin_custom';
  
  $iframe = get_option( $load_iframe );
  $flash = get_option( $upload_form );
  $image = get_option( $image_line );
  $number = get_option( $line_number );
  $mass = get_option( $mass_edit );
  $caption = get_option( $no_caption );
  $debug = get_option( $plugindebug );
  $cstring = get_option( $customstring );
  
  //update options
    if( isset($_POST[ $faster_insert_update ]) && $_POST[ $faster_insert_valid ] == 'V' ) {
  
    $_POST[ $load_iframe ] == 'selected' ? $iframe = true : $iframe = false;
    $_POST[ $upload_form ] == 'selected' ? $flash = true : $flash = false;
    $_POST[ $image_line ] == 'selected' ? $image = true : $image = false;
    if(is_numeric($_POST[ $line_number ])) $number = $_POST[ $line_number ]; else $number = 4;
    $_POST[ $mass_edit ] == 'selected' ? $mass = true : $mass = false;
    $_POST[ $no_caption ] == 'selected' ? $caption = true : $caption = false;
    $_POST[ $plugindebug ] == 'selected' ? $debug = true : $debug = false;
	if(is_string($_POST[ $customstring ]) && !empty($_POST[ $customstring ])) $cstring = $_POST[ $customstring ]; else $cstring = "<p>&nbsp;</p>";
    
    update_option( $load_iframe, $iframe );
    update_option( $upload_form, $flash );
    update_option( $image_line, $image );
    update_option( $line_number, $number );
    update_option( $mass_edit, $mass );
    update_option( $no_caption, $caption );
    update_option( $plugindebug, $debug );
	update_option( $customstring, $cstring );

    echo '<div class="updated"><p><strong>'.__('Settings saved.', 'faster-image-insert').'</strong></p></div>';  
  }

  //delete options
    if( isset($_POST[ $faster_insert_delete ]) && $_POST[ $faster_insert_valid ] == 'V' ) {
    
    delete_option( $load_iframe );
    delete_option( $upload_form );
    delete_option( $image_line );
    delete_option( $line_number );
    delete_option( $mass_edit );
    delete_option( $no_caption );
    delete_option( $backcompat );
    delete_option( $plugindebug );
	delete_option( $customstring );

    $iframe = get_option( $load_iframe );
    $flash = get_option( $upload_form );
    $image = get_option( $image_line );
    $number = get_option( $line_number );
    $mass = get_option( $mass_edit );
    $caption = get_option( $no_caption );
    $debug = get_option( $plugindebug );
	$cstring = get_option( $customstring );

    echo '<div class="updated"><p><strong>'.__('Settings deleted.', 'faster-image-insert').'</strong></p></div>';  
  }

  echo '<div class="wrap">'."\n".
     '<div id="icon-options-general" class="icon32"><br /></div>'."\n".
     '<h2>'.__('Faster Image Insert - User Options','faster-image-insert').'</h2>'."\n".
     '<h3>'.__('Updates your settings here', 'faster-image-insert').'</h3>';
?>

<form name="faster-insert-option" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
<input type="hidden" name="<?php echo $faster_insert_valid; ?>" value="V">

  <table width="100%" cellspacing="2" cellpadding="5" class="form-table">
    <tr valign="top">
      <th scope="row"><?php _e("Load media manager WITHOUT jQuery ?", 'faster-image-insert' ); ?></th>
      <td><label for="<?php echo $load_iframe; ?>"><input type="checkbox" name="<?php echo $load_iframe; ?>" id="<?php echo $load_iframe; ?>" value="selected" <?php if($iframe) echo 'checked="checked"' ?> /> <?php _e("Enable this if you're having trouble getting media manager to load in editing interface (ie. blank metabox). Needed for WordPress 2.6", 'faster-image-insert' ); ?></label></td>
    </tr>
    
    <tr valign="top">
      <th scope="row"><?php _e("Load HTML submit form instead of FLASH uploader ?", 'faster-image-insert' ); ?></th>
      <td><label for="<?php echo $upload_form; ?>"><input type="checkbox" name="<?php echo $upload_form; ?>" id="<?php echo $upload_form; ?>" value="selected" <?php if($flash) echo 'checked="checked"' ?> /> <?php _e("Enable this if you're having trouble with flash uploader.", 'faster-image-insert' ); ?></label></td>
    </tr>
    
    <tr valign="top">
      <th scope="row"><?php _e("Insert multiple images in ONE line ?", 'faster-image-insert' ); ?></th>
      <td><label for="<?php echo $image_line; ?>"><input type="checkbox" name="<?php echo $image_line; ?>" id="<?php echo $image_line; ?>" value="selected" <?php if($image) echo 'checked="checked"' ?> /> <?php _e("Enable this if you just want to insert a serial of thumbnails without newlines.", 'faster-image-insert' ); ?></label></td>
    </tr>
	
    <tr valign="top">
      <th scope="row"><?php _e("Set custrom string", 'faster-image-insert' ); ?></th>
      <td><label for="<?php echo $customstring; ?>"><input type="text" name="<?php echo $customstring; ?>" id="<?php echo $customstring; ?>" value="<?php echo get_option( $customstring ); ?>" size="20" /> <?php _e("Edit this to change the custom string inserted between images; defaults to newline.", 'faster-image-insert' ); ?></label></td>
    </tr>

    <tr valign="top">
      <th scope="row"><?php _e("Number of separators between each image", 'faster-image-insert' ); ?></th>
      <td><label for="<?php echo $line_number; ?>"><input type="text" name="<?php echo $line_number; ?>" id="<?php echo $line_number; ?>" value="<?php echo get_option( $line_number ); ?>" size="10" /> <?php _e("Depends on previous option; it either means blank line or blank space. Default is 4.", 'faster-image-insert' ); ?></label></td>
    </tr>
    
    <tr valign="top">
      <th scope="row"><?php _e("Disable Captions ?", 'faster-image-insert' ); ?></th>
      <td><label for="<?php echo $no_caption; ?>"><input type="checkbox" name="<?php echo $no_caption; ?>" id="<?php echo $no_caption; ?>" value="selected" <?php if($caption) echo 'checked="checked"' ?> /> <?php _e("WordPress use caption as alternative text, but it also appends [caption] if set manually, Enable this if you want to set alternative text without appending caption. works in WordPress 2.6.1 or above.", 'faster-image-insert' ); ?></label></td>
    </tr>

    <tr valign="top">
      <th scope="row"><?php _e("Enable mass-editing ?", 'faster-image-insert' ); ?></th>
      <td><label for="<?php echo $mass_edit; ?>"><input type="checkbox" name="<?php echo $mass_edit; ?>" id="<?php echo $mass_edit; ?>" value="selected" <?php if($mass) echo 'checked="checked"' ?> /> <?php _e("Enable this if you want to use the mass-edit function, works well with WordPress 2.7+", 'faster-image-insert' ); ?></label></td>
    </tr>
    
    <tr valign="top">
      <th scope="row"><?php _e("Enable debug mode ?", 'faster-image-insert' ); ?></th>
      <td><label for="<?php echo $plugindebug; ?>"><input type="checkbox" name="<?php echo $plugindebug; ?>" id="<?php echo $plugindebug; ?>" value="selected" <?php if($debug) echo 'checked="checked"' ?> /> <?php _e("Enable this option to remove registered javascript for this plugin, FOR DEBUGGING.", 'faster-image-insert' ); ?></label></td>
    </tr>
    
  </table>

<p class="submit">
<input type="submit" name="<?php echo $faster_insert_update; ?>" class="button-primary" value="<?php _e('Save Changes', 'faster-image-insert' ) ?>" />
<input type="submit" name="<?php echo $faster_insert_delete; ?>" value="<?php _e('Uninstall', 'faster-image-insert' ) ?>" />
</p>

</form>

<?php     
  echo '</div>'."\n";
}

//load languages file for i18n
function faster_insert_textdomain() {
  if (function_exists('load_plugin_textdomain')) {
    if ( !defined('WP_PLUGIN_DIR') ) {
      load_plugin_textdomain('faster-image-insert', str_replace( ABSPATH, '', dirname(__FILE__) ) . '/languages');
    } else {
      load_plugin_textdomain('faster-image-insert', false, dirname( plugin_basename(__FILE__) ) . '/languages');
    }
  }
}

add_action('init', 'faster_insert_textdomain');
add_action('admin_menu', 'faster_insert_option');
add_action('admin_menu', 'fast_image_insert', 20);
add_action('admin_head', 'faster_insert_local');
add_filter('media_upload_gallery', 'faster_insert_media_upload_gallery');
add_filter('media_upload_library', 'faster_insert_media_upload_library');
add_filter('disable_captions', 'caption_off');
?>