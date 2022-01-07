<?php
/*
Plugin Name: Category Visibility-RH Rev
Plugin URI: http://ryowebsite.com/?cat=11
Description: Alter the visibility settings for categories, for WordPress 2.1, 2.0. Based on an earlier Category Visibility by Keith McDuffee.
Author: Rich Hamilton
Version: 1.1.b9 (beta 9)
Author URI: http://ryowebsite.com
*/

/*
Original author: Keith McDuffee http://www.gudlyf.com/
http://www.gudlyf.com/archives/2005/03/08/wordpress-plugin-category-visibility/

Rather heavily modified by Rich Hamilton to
	- Return the full number of requests rather than just filtering out non-qualified posts. (With thanks to Mattias Päivärinta)
	- Reflect WP 2.0 Roles and equivalent numeric User Levels.
    - Return category archives when called by cat ID
    - Handle empty entries, that is, categories where no Category Visibility rule has been set.
    - NOT exclude PAGES.
    - Filter single view properly
    - Allow administration only to those who can Manage Categories
    - Upgrade from the older WP 1.5 Category Visibility (0.31 by McDuffee)
    - Function cv_visible_cats('list') returns the query array of categories visible to the passed area, 'list', 'front', etc.

    1.0.0a: -Corrected error that left Child Categories off the admin list; Restored cat_ID number to listing
    1.0.0b: -Added static array storage to cv_visible_cats and removed mysql query from top of cv_alter_vis_catlist
    		 to reduce repetitive queries. On my high-overhead theme it reduced queries on a post page by 18.
    1.0.0c: -Fixed problem displaying categories with apostrophes in sidebar. Change approach to
    		 archives, improving results when archives is unchecked.
    		 Possible fix for [Unknown table 'wp_post2cat' in on clause] error but uncertain as to side effects.
    		 (See line below "Avoid collisions")
	1.0.0d: -Adds a function, cv_get_posts(), which duplicates the WordPress get_posts() function except that when
			 a category is not passed, it returns posts in visible categories only. Defaults to those visible in
			 'list,' but can be changed using the 'visibleto' parameter.
			-Fixed a problem displaying categories with apostrophes in sidebar. Also, changed approach to archives,
			 improving results when archives is unchecked. And a fix for an 'Unknown table wp_post2cat' in on clause
			 error that few people encounter, but uncertain as to side effects.
	1.0.1   -Fixed archives so posts show up on category pages but not in other archive pages.
	1.1.0	-Updated to work with WP 2.1.
				- Revised the query
				- Moved wp_list_cats to wp_list_categories
				- Revised the admin test
			-Using mySql subqueries to better handle posts with multiple categories.
			-Changed admin test to return full query to all admin panel pages.
			-Added admin switch to allow "Exclusion  Logic." Exclusion  Logic requires mySql 4.1+.


*/
$cat_visibility = $table_prefix . 'catt_visibility';

function cv_category_vis_menu () {

    add_submenu_page('edit.php', 'Category Visibility', 'Category Visibility', 8, basename(__FILE__), 'category_visibility');

}

function category_visibility () {

    global $wpdb;
    global $cat_visibility;
	global $wp_roles;

	$res = mysql_query('SELECT version() AS server_version');
	$tmp = mysql_fetch_assoc($res);
	mysql_free_result($res);
	$mysqlversion = $tmp['server_version'];
	/*
	$serverversion = explode('.', $mysqlversion);
	$mysqlversion = (empty($serverversion)) ? 0 : $serverversion[0].'.'.$serverversion[1];
	*/

?>
<?php if ($_POST["action"] == "editcatvis"): ?>
<div id="message" class="updated fade"><p><strong><?php _e('Changes Submitted.'); ?></strong></p></div>
<?php endif; ?>
<div class="wrap">
<?php if ( current_user_can('manage_categories') ) { ?>
    <h2><?php _e('Category Visibility') ?> </h2>
<?php } ?>
<form name="catvis" id="catvis" action="edit.php?page=<?php echo basename(__FILE__); ?>" method="post">
<table width="100%" cellpadding="3" cellspacing="3">
        <tr>
        <th scope="col"><?php _e('ID') ?></th>
        <th scope="col"><?php _e('Name') ?></th>
        <th scope="col"><?php _e('Visibility') ?></th>
        </tr>
<?php
if ($_POST["action"] == "editcatvis") {
    cv_edit_cat_vis();
}

$cv_set = get_settings('catvis_settings');
$exclude = ( isset($cv_set['exclude']) && $cv_set['exclude'] == '1' ) ? '1' : '0';

cv_my_cat_rows();

?>
</table>
<div style="float:right;"><p class="submit"><input type="hidden" name="action" value="editcatvis" /><input type="submit" name="submit" value="<?php _e('Save Changes &raquo;') ?>" />
</p></div>
<p>
<strong>Front:</strong> Visibility on the front/main page.<br />
<strong>List:</strong> Visibility on the list of categories on the sidebar.<br />
<strong>Search:</strong> Visibility in search results.<br />
<strong>Feed:</strong> Visibility in RSS/RSS2/Atom feeds.<br />
<strong>Archive:</strong> Visibility in archive links (i.e., calendar links).<br />
<strong>User Level:</strong> Visibility on user level basis. All users this level and higher can see categories as checked. Does not affect feed visibility, obviously.<br />
<strong>Numeric User Levels:</strong> <?php
	// Get Role List
	$userlist = '';
	foreach($wp_roles->role_objects as $key => $role) {
		foreach($role->capabilities as $cap => $grant) {
			//$capnames[$cap] = $cap;
			$role_user_level = array_reduce(array_keys($role->capabilities), array('WP_User', 'level_reduction'), 0);
		}
		$userlist .= ucfirst($role->name).': '.$role_user_level.', ';
	}
	echo rtrim($userlist,' ,');
?>
<?php if ($mysqlversion > '4.1') { ?>
<br><br>
<input type='checkbox' name='exclude' id='exclude' value='1'<?php if ($exclude) echo ' checked="checked"'; ?> /><label for='exclude'><strong> Exclusion Logic.</strong> (Check and Save to enable Exclusion logic)</label><br>
For Posts attached to multiple categories:
By default, Posts will be displayed if <u>any</u> post category is <u>checked</u> to be visible on that page or list.
By selecting Exclusion logic, Posts will be displayed only if <u>no</u> Post category is <u>unchecked</u>.<br />
<br>Note: Exclusion logic works only with mySql versions 4.1 and later.
<?php } else {
	if ( isset($cv_set['exclude']) && $cv_set['exclude'] == '1' ) {
		unset( $cv_set['exclude'] );
		update_option('catvis_settings', $cv_set);
	}
}
echo '<br><u>System Information</u><br>PHP version: '.phpversion().'<br>MySql version: '.$mysqlversion;
$plugins = get_plugins();
echo '<br>Category Visibility version: '.strip_tags($plugins['category_vis-rh.php']['Version']);
?>
<br>
</p>
</form>

</div>
<?php
}

function cv_my_cat_rows($parent = 0, $level = 0, $categories = 0) {

    global $wpdb, $class;
    global $cat_visibility;

	// $pagenow is edit.php

	if (!$categories)
		$categories = $wpdb->get_results("SELECT * FROM $wpdb->categories ORDER BY cat_name");

	if ($categories) {
		foreach ($categories as $category) {
			if ($category->category_parent == $parent) {
				$category->cat_name = wp_specialchars($category->cat_name);
				$pad = str_repeat('&#8212; ', $level);
				if ( current_user_can('manage_categories') ) {
					$catvis = $wpdb->get_results("SELECT * FROM $cat_visibility WHERE catt_ID=$category->cat_ID LIMIT 1");
					$catvis = $catvis[0];
					if ($catvis->front == 1 || $catvis == 0) $catvis_front = "checked"; else $catvis_front = "";
					if ($catvis->list == 1 || $catvis == 0) $catvis_list = "checked"; else $catvis_list = "";
					if ($catvis->search == 1 || $catvis == 0) $catvis_search = "checked"; else $catvis_search = "";
					if ($catvis->feed == 1 || $catvis == 0) $catvis_feed = "checked"; else $catvis_feed = "";
					if ($catvis->archives == 1 || $catvis == 0) $catvis_archives = "checked"; else $catvis_archives = "";
                    $catvis_user_level = ($catvis->cv_user_level) ? $catvis->cv_user_level : 0;
					$edit  = "<label for='" . $category->cat_ID . "_front'>Front:</label> <input name='" . $category->cat_ID . "_front' id='" . $category->cat_ID . "_front' class='edit' type='checkbox' $catvis_front />&nbsp;&nbsp;";
					$edit .= "<label for='" . $category->cat_ID . "_list'>List:</label> <input name='" . $category->cat_ID . "_list' id='" . $category->cat_ID . "_list' class='edit' type='checkbox' $catvis_list />&nbsp;&nbsp;";
					$edit .= "<label for='" . $category->cat_ID . "_search'>Search:</label> <input name='" . $category->cat_ID . "_search' id='" . $category->cat_ID . "_search' class='edit' type='checkbox' $catvis_search />&nbsp;&nbsp;";
					$edit .= "<label for='" . $category->cat_ID . "_feed'>Feed:</label> <input name='" . $category->cat_ID . "_feed' id='" . $category->cat_ID . "_feed' class='edit' type='checkbox' $catvis_feed />&nbsp;&nbsp;";
					$edit .= "<label for='" . $category->cat_ID . "_archives'>Archives:</label> <input name='" . $category->cat_ID . "_archives' id='" . $category->cat_ID . "_archives' class='edit' type='checkbox' $catvis_archives />&nbsp;&nbsp;";
                    $edit .= "<label for='" . $category->cat_ID . "_cv_user_level'>User Level:</label> <input name='" . $category->cat_ID . "_cv_user_level' id='" . $category->cat_ID . "_cv_user_level' class='edit' type='text' size='3' value='" . $catvis_user_level . "' />";
				} else
					$edit = '';
				$class = ('alternate' == $class) ? '' : 'alternate';
				echo "<tr class='$class'><th scope='row'>$category->cat_ID</th><td>$pad $category->cat_name</td>
	 					<td style='text-align: center'>$edit</td>
						</tr>";
				cv_my_cat_rows($category->cat_ID, $level + 1, $categories);
			}
		}
	} else {
		return false;
	}
}

function cv_edit_cat_vis () {

    global $wpdb;
    global $cat_visibility;

	$cv_set = get_settings('catvis_settings');
	$exclude = ( isset($_POST['exclude']) ) ? $_POST['exclude']: '0';
	if ( $exclude != $cv_set['exclude'] ) {
		$cv_set['exclude'] = $exclude;
		update_option('catvis_settings', $cv_set);
	}

	if (!$categories)
			$categories = $wpdb->get_results("SELECT cat_ID FROM $wpdb->categories");

	if ($categories) {
		foreach ($categories as $category) {
			$front = $category->cat_ID . "_front";
			$list = $category->cat_ID . "_list";
			$search = $category->cat_ID . "_search";
			$feed = $category->cat_ID . "_feed";
			$archives = $category->cat_ID . "_archives";
            $cv_user_level = $category->cat_ID . "_cv_user_level";

			if ($_POST["$front"] == "on") $front = 1; else $front = 0;
			if ($_POST["$list"] == "on") $list = 1; else $list = 0;
			if ($_POST["$search"] == "on") $search = 1; else $search = 0;
			if ($_POST["$feed"] == "on") $feed = 1; else $feed = 0;
			if ($_POST["$archives"] == "on") $archives = 1; else $archives = 0;
            $cv_user_level = $_POST["$cv_user_level"];

            if (empty($cv_user_level)) $cv_user_level = 0;
            if(!is_numeric($cv_user_level)) $cv_user_level = 0;

			if ( $front==1 && $list==1 && $search==1 && $feed==1 && $archives==1 && $cv_user_level==0 ) {
				$wpdb->query("DELETE FROM $cat_visibility WHERE catt_ID=$category->cat_ID LIMIT 1");
			} else {
				$wpdb->query("REPLACE INTO $cat_visibility SET catt_ID=$category->cat_ID, front=$front, list=$list, search=$search, feed=$feed, archives=$archives, cv_user_level=$cv_user_level");
			}
		}
	}
}


function cv_distinct($distinct='') {   // WP 2.1 only
	if ( !$distinct && (is_home() || is_feed() || is_archive() || is_category() || is_search()) )
		$distinct = 'DISTINCT';
	return $distinct;
}

/*
function cv_fields($fields='') {   // WP 2.1 only
	//if ( is_home() || is_feed() || is_archive() || is_category() || is_search() )
	//	$fields = '*';
	return $fields;
}
*/


function cv_posts_join($join) {
	global $wpdb;
	global $cat_visibility;
	global $wp_query;
	if (cv_is_admin()) return $join;  // If we are in the admin menu, return unfiltered
	$cv_set = get_settings('catvis_settings');
	$checkval = ( isset($cv_set['exclude']) && $cv_set['exclude'] == '1' ) ? '0' : '1';
	if ( $checkval=='1' && (is_home() || is_feed() || is_archive() || is_category() || is_search()) ) {
		//if (! (isset($wp_query->query_vars['category_name']) || isset($wp_query->query_vars['cat'])) ) // Avoid collisions
		if (! strstr($join, $wpdb->post2cat))   // Testing this line
		   $join .= " LEFT JOIN $wpdb->post2cat ON ($wpdb->posts.ID = $wpdb->post2cat.post_id)";
		$join .= " LEFT JOIN $cat_visibility ON ($wpdb->post2cat.category_id = $cat_visibility.catt_ID)";
	}
	//echo '<br>'.$join.'<br>';
	return $join;
}

function cv_posts_where($where) {
	global $user_level;
	global $wpdb;
	global $cat_visibility;
	global $wp_db_version;
	$thisis = (!isset($wp_db_version)) ? '1.5' : ($wp_db_version < 4000) ? '2.0' : '2.1';
	//echo "<br>$thisis<br>";  // Testing

	// post_status = 'publish' AND post_type = 'post'

	if (cv_is_admin()) return $where;  // If we are in the admin menu, return unfiltered

	get_currentuserinfo();
	if (!is_numeric($user_level)) $user_level = 0;

	$cv_set = get_settings('catvis_settings');
	$checkval = ( isset($cv_set['exclude']) && $cv_set['exclude'] == '1' ) ? '0' : '1';

	if ('1' == $checkval ) {
		$ret = '';
		if ('2.0' == $thisis)
			$lc = "$cat_visibility.cv_user_level<='$user_level') OR post_status='static' OR $cat_visibility.catt_ID IS NULL";
		else
			$lc = "$cat_visibility.cv_user_level<='$user_level') OR post_type='page' OR $cat_visibility.catt_ID IS NULL";
	} else {  // Exclude logic
		$ret = '';
		$lc = "$cat_visibility.cv_user_level<='$user_level')";
	}

	if (is_category()) $thiscat = cv_the_category();

	// front list search feed archives
	if (is_home())
		$ret .= "AND (($cat_visibility.front='$checkval' AND $lc)";
	elseif (is_archive() && !is_category())
		$ret .= "AND (($cat_visibility.archives='$checkval' AND $lc)";
	elseif (is_archive() && is_category())
		$ret .= ('1' == $checkval ) ? "AND (($lc)" : "AND $wpdb->post2cat.category_id<>'$thiscat' AND (($cat_visibility.archives='$checkval' AND $lc)";
	elseif (is_search())
		$ret .= "AND (($cat_visibility.search='$checkval' AND $lc)";
	elseif (is_feed() && is_category())
		$ret .= ('1' == $checkval ) ? "AND (($lc)" : "AND $wpdb->post2cat.category_id<>'$thiscat' AND (($cat_visibility.feed='$checkval' AND $lc)";
	elseif (is_feed())
		$ret .= "AND (($cat_visibility.feed='$checkval' AND $lc)"; // OR $cat_visibility.catt_ID IS NULL);
	else
		return $where;

	// Subquery. Others seem not to like these due to mySql version restrictions so will try workarounds.
	if ('1' == $checkval ) {
		//$ret = "AND ID = ANY (SELECT post_id FROM $wpdb->post2cat
		//		LEFT JOIN $cat_visibility ON ($wpdb->post2cat.category_id = $cat_visibility.catt_ID)
		//		WHERE ($ret))";
	} else { // Exception logic. Return any where NOT catvis does returns a 0, ignore NULL, cause that is like a 1
		$ret = "AND NOT EXISTS (SELECT post_id FROM $wpdb->post2cat
				RIGHT JOIN $cat_visibility ON ($wpdb->post2cat.category_id = $cat_visibility.catt_ID)
				WHERE $wpdb->posts.ID = $wpdb->post2cat.post_id $ret)";
	}

	//return "$where BOGUS $ret";  // to cause an error for Testing
	return "$where $ret";


}

function cv_the_category ($category = '') {
	global $wp_query;
	if (is_category()) {
		$cat_obj = $wp_query->get_queried_object();
		if ( $cat_obj->cat_ID )
			return $cat_obj->cat_ID;
	}
	return false;
}

/*
function cv_visible_cats($visibleto = 'front') {
    global $wpdb, $user_level, $cat_visibility;
    static $cats = array();
	if (!isset($cats[$visibleto])) {
		get_currentuserinfo();
		if (empty($user_level)) $user_level = 0;
		if (!is_numeric($user_level)) $user_level = 0;
		$my_query  = "SELECT * FROM $wpdb->categories";
		$my_query .= " LEFT JOIN $cat_visibility ON ($wpdb->categories.cat_ID = $cat_visibility.catt_ID)";
		if ($visibleto == 'none') {
			$my_query .= " WHERE ( $cat_visibility.cv_user_level<=$user_level OR $cat_visibility.catt_ID IS NULL)";
		} else {
			$my_query .= " WHERE ( ($cat_visibility." . $visibleto . "=1 AND $cat_visibility.cv_user_level<=$user_level ) OR $cat_visibility.catt_ID IS NULL)";
		}
		$my_query .= " ORDER BY $wpdb->categories.cat_name";
		$cats[$visibleto] = ($wpdb->get_results($my_query));
    }
    return $cats[$visibleto];
}
*/

/*
function cv_visible_cats($visibleto = 'front') {
    global $wpdb, $user_level, $cat_visibility;
    private static $cv_cats = array();
	if (!isset($cv_cats[$visibleto])) {
		get_currentuserinfo();
		if (empty($user_level)) $user_level = 0;
		if (!is_numeric($user_level)) $user_level = 0;
		$my_query  = "SELECT * FROM $wpdb->categories";
		$my_query .= " LEFT JOIN $cat_visibility ON ($wpdb->categories.cat_ID = $cat_visibility.catt_ID)";
		if ($visibleto == 'none') {
			$my_query .= " WHERE ( $cat_visibility.cv_user_level<=$user_level OR $cat_visibility.catt_ID IS NULL)";
		} else {
			$my_query .= " WHERE ( ($cat_visibility." . $visibleto . "=1 AND $cat_visibility.cv_user_level<=$user_level ) OR $cat_visibility.catt_ID IS NULL)";
		}
		$my_query .= " ORDER BY $wpdb->categories.cat_name";
		$cv_cats[$visibleto] = ($wpdb->get_results($my_query));
    }
    return $cv_cats[$visibleto];
}
*/

function cv_visible_cats($visibleto = 'front') {
    global $wpdb, $user_level, $cat_visibility;
    static $cv_cats = 0;
	if ($cv_cats == 0) {
		get_currentuserinfo();
		if (empty($user_level)) $user_level = 0;
		if (!is_numeric($user_level)) $user_level = 0;
		$my_query  = "SELECT * FROM $wpdb->categories";
		$my_query .= " LEFT JOIN $cat_visibility ON ($wpdb->categories.cat_ID = $cat_visibility.catt_ID)";
		$my_query .= " ORDER BY $wpdb->categories.cat_name";
		$cv_cats = ($wpdb->get_results($my_query));
	}
	$callback = create_function('$obj', 'return (('
	. (  ($visibleto == 'none') ? '' : ('$obj->'.$visibleto.'==1 && '))
	. '$obj->cv_user_level<='.$user_level.') || $obj->catt_ID==0 );');
	return array_filter( $cv_cats,$callback );
}

function cv_alter_vis_catlist ($thelist) {
    global $wpdb;
    global $cat_visibility;

	if (cv_is_admin()) return $thelist;  // If we are in the admin menu, return unfiltered

	$categories = cv_visible_cats('list');
    if (preg_match("/href/", $thelist)) {

        $newlist = "";
        $children = 0;
        $linklist = preg_split('/\t/', $thelist);
        foreach ($linklist as $link) {
            if(preg_match("/class.*children/", $link)) {
                $children = 1;
                $newlist .= $link;
            } elseif(preg_match("/<\/ul>/", $link) && $children) {
                $children = 0;
                $newlist .= $link;
            } else {
                $thiscatname = strip_tags($link);
                $thiscatname = preg_replace("/\s+\(\d+\)\s+/", "", $thiscatname);
                $thiscatname = trim($thiscatname);
                if(!empty($thiscatname)) {
					foreach ($categories as $category) {
						if ($category->cat_name == $thiscatname || wptexturize($category->cat_name) == $thiscatname) {
                            $newlist .= $link;
							break;
						}
					}
                }
            }
        }
        return $newlist;
    }
    $thiscatname = $thelist;
	foreach ($categories as $category) {
		if ($category->cat_name == $thiscatname || wptexturize($category->cat_name) == $thiscatname) {
			return $thelist;
		}
	}

	return;
}

function cv_get_posts($args) {
	global $wpdb;
	parse_str($args, $r);
	if ( !isset($r['numberposts']) )
		$r['numberposts'] = 5;
	if ( !isset($r['offset']) )
		$r['offset'] = 0;
	if ( !isset($r['orderby']) )
		$r['orderby'] = 'post_date';
	if ( !isset($r['order']) )
		$r['order'] = 'DESC';
	if ( !isset($r['visibleto']) )
		$r['visibleto'] = 'list';
	if ( !isset($r['category']) || $r['category']==FALSE) {
		$acats = cv_visible_cats($r['visibleto']); // cats visible, default list
		if (!empty($acats)) {
			$r['category'] = '';
			foreach ($acats as $category) {
				$r['category'] .= ((''!=$r['category']) ? ',':'') . $category->cat_ID;
			}
		}
	}
	$r['category'] = '('.$r['category'].')';
	$now = current_time('mysql');
	$posts = $wpdb->get_results(
		"SELECT DISTINCT * FROM $wpdb->posts " .
		( empty( $r['category'] ) ? "" : ", $wpdb->post2cat " ) .
		" WHERE post_date <= '$now' AND (post_status = 'publish') ".
		( empty( $r['category'] ) ? "" : "AND $wpdb->posts.ID = $wpdb->post2cat.post_id AND $wpdb->post2cat.category_id in " . $r['category']. " " ) .
		" GROUP BY $wpdb->posts.ID ORDER BY " . $r['orderby'] . " " . $r['order'] . " LIMIT " . $r['offset'] . ',' . $r['numberposts'] );
	update_post_caches($posts);
	return $posts;
}

function cv_delete_cat($cat_ID) {
	global $wpdb;
	global $cat_visibility;
	$wpdb->query("DELETE FROM $cat_visibility WHERE catt_ID=$cat_ID LIMIT 1");
}

// Servicing Functions
function cv_is_admin() {
	$adminurl = get_settings('siteurl');
	$adminurl = '/wp-admin/';
	if ( (strpos($_SERVER['SCRIPT_URL'],$adminurl) !==FALSE || strpos($_SERVER['SCRIPT_NAME'],$adminurl) !==FALSE )
		&& (strpos($_SERVER['SCRIPT_URL'],'/wp-admin/') !==FALSE || strpos($_SERVER['SCRIPT_NAME'],'/wp-admin/') !==FALSE )
		&& function_exists('wp_write_post') )
			return 1;
	return 0;
}


// Installs the tables needed for Category Visibility on Activation
// Check the codex article for more info
// http://codex.wordpress.org/creating_tables_with_plugins
function cv_install() {
   global $wpdb, $cat_visibility;

	// Check to see if table exists, check for old column name, change if necessary
    foreach ($wpdb->get_col("SHOW TABLES",0) as $table ) {
        if ($table == $cat_visibility) {
			// Found table
			foreach ($wpdb->get_col("DESC $cat_visibility", 0) as $column ) {
				if ($column == 'cat_ID') {
					// Found column, run query to change column name
						$wpdb->query("ALTER TABLE `$cat_visibility` CHANGE `cat_ID` `catt_ID` BIGINT( 20 ) NOT NULL DEFAULT '0', DROP INDEX `cat_ID`");
				} elseif ($column == 'user_level') {
					// Found column, run query to change column name
						$wpdb->query("ALTER TABLE `$cat_visibility` CHANGE `user_level` `cv_user_level` INT( 4 ) NOT NULL DEFAULT '0'");
				}
			}
            break;
        }
    }

   // PRIMARY KEY has 2 spaces on purpose ... some weird dbDelta thing...

   $qry = "CREATE TABLE ".$cat_visibility." (
			catt_ID bigint(20) NOT NULL,
			front int(4) NOT NULL default 1,
			list int(4) NOT NULL default 1,
			search int(4) NOT NULL default 1,
			feed int(4) NOT NULL default 1,
			archives int(4) NOT NULL default 1,
			cv_user_level int(4) NOT NULL default 0,
			PRIMARY KEY  (catt_ID)
           ); ";

    require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
    dbDelta($qry);

}

// Make sure WP is running
if (function_exists('add_action')) {

	add_filter('posts_join', 'cv_posts_join');
	add_filter('posts_where', 'cv_posts_where');
	add_action('admin_menu', 'cv_category_vis_menu');
	add_filter('list_cats', 'cv_alter_vis_catlist');
	add_filter('wp_list_categories', 'cv_alter_vis_catlist');	// wp 2.1
	add_action('delete_category', 'cv_delete_cat');
	add_action('posts_distinct', 'cv_distinct');  // wp 2.1
	// add_action('posts_fields', 'cv_fields');  // wp 2.1

	/* These actions are run through 'init' for security */

    // Run the install script when/if the plugin is activated manually or through RYO Quick Start
    if (isset($_GET['activate']) && $_GET['activate'] == 'true')
       add_action('init', 'cv_install');
} else {
?><!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<HTML><HEAD><TITLE>Invalid Address</TITLE></HEAD>
<BODY><H1>Invalid Address</H1><HR>This is not a valid address at this website.<P>Try <a href="/">here</a>.<HR></BODY>
</HTML><?php
}
?>