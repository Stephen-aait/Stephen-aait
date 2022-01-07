<?php
if ( function_exists('register_sidebar') ) {
    register_sidebar(array(
        'name'=>'sidebar',
        'before_widget' => '<li>',
        'after_widget' => '</li>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name'=>'footer',
        'before_widget' => '<div>',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ));    
}


if (!is_admin()) {    
    wp_enqueue_script( 'blackurban-theme', get_template_directory_uri().'/theme.js', array('jquery'));
    
    
    $blackurban = get_option('blackurban');
    
    if (!$blackurban) {
        $blackurban = array(
                            "links" => array(0,0,0)
                        );
                        
        $blackurban_links = array_fill(0,3, array('href'=>get_option('home').'/', 'title'=>get_bloginfo('name')));
    } else {
        $blackurban_links = array();
        foreach ($blackurban['links'] as $link => $id) {
            if ((int)$id === 0) {
                $blackurban_links[$link] = 
                        array(
                            'href'=>get_option('home').'/',
                            'title'=>get_bloginfo('name')
                        );
            } else {
                $blackurban_links[$link] = 
                        array(
                            'href'=>get_page_link($id),
                            'title'=>get_the_title($id)
                        );
            }
        }
    }    
    
    /**
     * Get Link by ID
     */
    function blackurban_link($id, $attr) {
        global $blackurban_links;
        echo $blackurban_links[$id][$attr];
    }
} else {
    add_action('admin_menu', 'blackurban_theme_page_add');
    
    /**
     * Add configuration page
     *
     */
    function blackurban_theme_page_add()
    {
        
		if ( isset( $_REQUEST['action'] ) && 'save' == $_REQUEST['action'] ) {
			//var_dump($_REQUEST);
			check_admin_referer('blackurban');
			//var_dump($_REQUEST);
			if (isset($_REQUEST['blackurban'])) {
			    // show text message
            	update_option('blackurban', $_REQUEST['blackurban']);
			}
			wp_redirect("themes.php?page=functions.php&saved=true");
			die;
		}
	    add_theme_page(__('Customize Header'), __('Links'), 'edit_themes', basename(__FILE__), 'blackurban_theme_page');
    }
    
    /**
     * Configuration page
     *
     */
    function blackurban_theme_page()
    {
        $pages = get_pages();
        
        $blackurban = get_option('blackurban');
        
        if (!$blackurban) {
            $blackurban = array(
                                "links" => array(0,0,0)
                            );
        }
        
        ?>        
    <div class='wrap'>
	   <h2><?php _e('Customize Header'); ?></h2>	
	   <?php if ( isset( $_REQUEST['saved'] ) ) echo '<div id="message" class="updated fade"><p><strong>'.__('Options saved.').'</strong></p></div>'; ?>
	   <div class="">	   
			<form style="display:inline;" method="post" action="<?php echo attribute_escape($_SERVER['REQUEST_URI']); ?>">
				<?php wp_nonce_field('blackurban'); ?>
				<input type="hidden" name="action" value="save" />
            	<table class="form-table">
            	<tr>
            	   <th colspan="2">
            	       Change links under header
            	   </th>
            	</tr>
            	<tr>
            		<th scope="row" valign="top"><?php _e('First Link'); ?></th>
            		<td>
                		<select name="blackurban[links][0]" >
                            <option value="0"><?php echo attribute_escape(__('Home page')); ?></option>
                            <?php  
                            foreach ($pages as $page) {
                                $option = '<option value="'.$page->ID.'"';
                                $option .= ($page->ID == $blackurban['links'][0] ? 'selected="selected"' : '');
                                $option .= '>';
                                $option .= $page->post_title;
                                $option .= '</option>'."\n";
                                echo $option;
                            }
                            ?>
                        </select>
            		</td>
            	</tr>
            	<tr>
            		<th scope="row" valign="top"><?php _e('Second Link'); ?></th>
            		<td>
            		
                		<select name="blackurban[links][1]" > 
                            <option value="0"><?php echo attribute_escape(__('Home page')); ?></option>
                            <?php  
                            foreach ($pages as $page) {
                                $option = '<option value="'.$page->ID.'"';
                                $option .= ($page->ID == $blackurban['links'][1] ? 'selected="selected"' : '');
                                $option .= '>';
                                $option .= $page->post_title;
                                $option .= '</option>'."\n";
                                echo $option;
                            }
                            ?>
                        </select>
            		</td>
            	</tr>
            	<tr>
            		<th scope="row" valign="top"><?php _e('Third Link'); ?></th>
            		<td>
            		
                		<select name="blackurban[links][2]" > 
                            <option value="0"><?php echo attribute_escape(__('Home page')); ?></option>
                            <?php  
                            foreach ($pages as $page) {
                                $option = '<option value="'.$page->ID.'"';
                                $option .= ($page->ID == $blackurban['links'][2] ? 'selected="selected"' : '');
                                $option .= '>';
                                $option .= $page->post_title;
                                $option .= '</option>'."\n";
                                echo $option;
                            }
                            ?>
                        </select>
            		</td>
            	</tr>
            	</table>
            	<p class="submit">
                	<input type="submit" name="Submit" class="button-primary" value="<?php _e('Save Changes')?>" />
                </p>
            </form>
	   </div>
	</div>
	<?php
    }
}