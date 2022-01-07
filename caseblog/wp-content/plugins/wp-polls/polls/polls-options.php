<?php
/*
+----------------------------------------------------------------+
|																							|
|	WordPress 2.1 Plugin: WP-Polls 2.14										|
|	Copyright (c) 2007 Lester "GaMerZ" Chan									|
|																							|
|	File Written By:																	|
|	- Lester "GaMerZ" Chan															|
|	- http://www.lesterchan.net													|
|																							|
|	File Information:																	|
|	- Configure Poll Options															|
|	- wp-content/plugins/polls/polls-options.php								|
|																							|
+----------------------------------------------------------------+
*/


### Check Whether User Can Manage Polls
if(!current_user_can('manage_polls')) {
	die('Access Denied');
}


### Variables Variables Variables
$base_name = plugin_basename('polls/polls-options.php');
$base_page = 'admin.php?page='.$base_name;
$id = intval($_GET['id']);


### If Form Is Submitted
if($_POST['Submit']) {
	$poll_bar_style = strip_tags(trim($_POST['poll_bar_style']));
	$poll_bar_background = strip_tags(trim($_POST['poll_bar_bg']));
	$poll_bar_border = strip_tags(trim($_POST['poll_bar_border']));
	$poll_bar_height = intval($_POST['poll_bar_height']);
	$poll_bar = array('style' => $poll_bar_style, 'background' => $poll_bar_background, 'border' => $poll_bar_border, 'height' => $poll_bar_height);
	$poll_ans_sortby = strip_tags(trim($_POST['poll_ans_sortby']));
	$poll_ans_sortorder = strip_tags(trim($_POST['poll_ans_sortorder']));
	$poll_ans_result_sortby = strip_tags(trim($_POST['poll_ans_result_sortby']));
	$poll_ans_result_sortorder = strip_tags(trim($_POST['poll_ans_result_sortorder']));
	$poll_template_voteheader =trim($_POST['poll_template_voteheader']);
	$poll_template_votebody = trim($_POST['poll_template_votebody']);
	$poll_template_votefooter = trim($_POST['poll_template_votefooter']);
	$poll_template_resultheader = trim($_POST['poll_template_resultheader']);
	$poll_template_resultbody = trim($_POST['poll_template_resultbody']);
	$poll_template_resultbody2 = trim($_POST['poll_template_resultbody2']);
	$poll_template_resultfooter = trim($_POST['poll_template_resultfooter']);
	$poll_template_resultfooter2 = trim($_POST['poll_template_resultfooter2']);
	$poll_template_disable = trim($_POST['poll_template_disable']);
	$poll_template_error = trim($_POST['poll_template_error']);
	$poll_archive_perpage = intval($_POST['poll_archive_perpage']);
	$poll_archive_url = strip_tags(trim($_POST['poll_archive_url']));
	$poll_archive_show = intval($_POST['poll_archive_show']);
	$poll_currentpoll = intval($_POST['poll_currentpoll']);
	$poll_close = intval($_POST['poll_close']);
	$poll_logging_method = intval($_POST['poll_logging_method']);
	$poll_allowtovote = intval($_POST['poll_allowtovote']);
	$update_poll_queries = array();
	$update_poll_text = array();	
	$update_poll_queries[] = update_option('poll_bar', $poll_bar);
	$update_poll_queries[] = update_option('poll_ans_sortby', $poll_ans_sortby);
	$update_poll_queries[] = update_option('poll_ans_sortorder', $poll_ans_sortorder);
	$update_poll_queries[] = update_option('poll_ans_result_sortby', $poll_ans_result_sortby);
	$update_poll_queries[] = update_option('poll_ans_result_sortorder', $poll_ans_result_sortorder);
	$update_poll_queries[] = update_option('poll_template_voteheader', $poll_template_voteheader);
	$update_poll_queries[] = update_option('poll_template_votebody', $poll_template_votebody);
	$update_poll_queries[] = update_option('poll_template_votefooter', $poll_template_votefooter);
	$update_poll_queries[] = update_option('poll_template_resultheader', $poll_template_resultheader);
	$update_poll_queries[] = update_option('poll_template_resultbody', $poll_template_resultbody);
	$update_poll_queries[] = update_option('poll_template_resultbody2', $poll_template_resultbody2);
	$update_poll_queries[] = update_option('poll_template_resultfooter', $poll_template_resultfooter);
	$update_poll_queries[] = update_option('poll_template_resultfooter2', $poll_template_resultfooter2);
	$update_poll_queries[] = update_option('poll_template_disable', $poll_template_disable);
	$update_poll_queries[] = update_option('poll_template_error', $poll_template_error);
	$update_poll_queries[] = update_option('poll_archive_perpage', $poll_archive_perpage);
	$update_poll_queries[] = update_option('poll_archive_url', $poll_archive_url);
	$update_poll_queries[] = update_option('poll_archive_show', $poll_archive_show);
	$update_poll_queries[] = update_option('poll_currentpoll', $poll_currentpoll);
	$update_poll_queries[] = update_option('poll_close', $poll_close);
	$update_poll_queries[] = update_option('poll_logging_method', $poll_logging_method);
	$update_poll_queries[] = update_option('poll_allowtovote', $poll_allowtovote);
	$update_poll_text[] = __('Poll Bar Style', 'wp-polls');
	$update_poll_text[] = __('Sort Poll Answers By Option', 'wp-polls');
	$update_poll_text[] = __('Sort Order Of Poll Answers Option', 'wp-polls');
	$update_poll_text[] = __('Sort Poll Results By Option', 'wp-polls');
	$update_poll_text[] = __('Sort Order Of Poll Results Option', 'wp-polls');
	$update_poll_text[] = __('Voting Form Header Template', 'wp-polls');
	$update_poll_text[] = __('Voting Form Body Template', 'wp-polls');
	$update_poll_text[] = __('Voting Form Footer Template', 'wp-polls');
	$update_poll_text[] = __('Result Header Template', 'wp-polls');
	$update_poll_text[] = __('Result Body Template', 'wp-polls');
	$update_poll_text[] = __('Result Body2 Template', 'wp-polls');
	$update_poll_text[] = __('Result Footer Template', 'wp-polls');
	$update_poll_text[] = __('Result Footer2 Template', 'wp-polls');
	$update_poll_text[] = __('Poll Disabled Template', 'wp-polls');
	$update_poll_text[] = __('Poll Error Template', 'wp-polls');
	$update_poll_text[] = __('Archive Polls Per Page Option', 'wp-polls');
	$update_poll_text[] = __('Polls Archive URL Option', 'wp-polls');
	$update_poll_text[] = __('Show Polls Achive Link Option', 'wp-polls');
	$update_poll_text[] = __('Current Active Poll Option', 'wp-polls');
	$update_poll_text[] = __('Poll Close Option', 'wp-polls');
	$update_poll_text[] = __('Logging Method', 'wp-polls');
	$update_poll_text[] = __('Allow To Vote Option', 'wp-polls');
	$i=0;
	$text = '';
	foreach($update_poll_queries as $update_poll_query) {
		if($update_poll_query) {
			$text .= '<font color="green">'.$update_poll_text[$i].' '.__('Updated', 'wp-polls').'</font><br />';
		}
		$i++;
	}
	if(empty($text)) {
		$text = '<font color="red">'.__('No Poll Option Updated', 'wp-polls').'</font>';
	}
	wp_clear_scheduled_hook('polls_cron');
	if (!wp_next_scheduled('polls_cron')) {
		wp_schedule_event(time(), 'daily', 'polls_cron');
	}
}
?>
<script type="text/javascript">
/* <![CDATA[*/
	function poll_default_templates(template) {
		var default_template;
		switch(template) {
			case "voteheader":
				default_template = "<p style=\"text-align: center;\"><strong>%POLL_QUESTION%</strong></p>\n<div id=\"polls-%POLL_ID%-ans\" class=\"wp-polls-ans\">\n<ul class=\"wp-polls-ul\">";
				break;
			case "votebody":
				default_template = "<li><input type=\"radio\" id=\"poll-answer-%POLL_ANSWER_ID%\" name=\"poll_%POLL_ID%\" value=\"%POLL_ANSWER_ID%\" /> <label for=\"poll-answer-%POLL_ANSWER_ID%\">%POLL_ANSWER%</label></li>";
				break;
			case "votefooter":
				default_template = "</ul>\n<p style=\"text-align: center;\"><input type=\"button\" name=\"vote\" value=\"   <?php _e('Vote', 'wp-polls'); ?>   \" class=\"Buttons\" onclick=\"poll_vote(%POLL_ID%);\" /></p>\n<p style=\"text-align: center;\"><a href=\"#ViewPollResults\" onclick=\"poll_result(%POLL_ID%); return false;\" title=\"<?php _e('View Results Of This Poll', 'wp-polls'); ?>\"><?php _e('View Results', 'wp-polls'); ?></a></p>\n</div>";
				break;
			case "resultheader":
				default_template = "<p style=\"text-align: center;\"><strong>%POLL_QUESTION%</strong></p>\n<div id=\"polls-%POLL_ID%-ans\" class=\"wp-polls-ans\">\n<ul class=\"wp-polls-ul\">";
				break;
			case "resultbody":
				default_template = "<li>%POLL_ANSWER% <small>(%POLL_ANSWER_PERCENTAGE%%)</small><div class=\"pollbar\" style=\"width: %POLL_ANSWER_IMAGEWIDTH%%;\" title=\"%POLL_ANSWER_TEXT% (%POLL_ANSWER_PERCENTAGE%% | %POLL_ANSWER_VOTES% <?php _e('Votes', 'wp-polls'); ?>)\"></div></li>";
				break;
			case "resultbody2":
				default_template = "<li><strong><i>%POLL_ANSWER% <small>(%POLL_ANSWER_PERCENTAGE%%)</small></i></strong><div class=\"pollbar\" style=\"width: %POLL_ANSWER_IMAGEWIDTH%%;\" title=\"<?php _e('You Have Voted For This Choice', 'wp-polls'); ?> - %POLL_ANSWER_TEXT% (%POLL_ANSWER_PERCENTAGE%% | %POLL_ANSWER_VOTES% <?php _e('Votes', 'wp-polls'); ?>)\"></div></li>";
				break;
			case "resultfooter":
				default_template = "</ul>\n<p style=\"text-align: center;\"><?php _e('Total Votes', 'wp-polls'); ?>: <strong>%POLL_TOTALVOTES%</strong></p>\n</div>";
				break;
			case "resultfooter2":
				default_template = "</ul>\n<p style=\"text-align: center;\"><?php _e('Total Votes', 'wp-polls'); ?>: <strong>%POLL_TOTALVOTES%</strong></p>\n<p style=\"text-align: center;\"><a href=\"#VotePoll\" onclick=\"poll_booth(%POLL_ID%); return false;\" title=\"<?php _e('Vote For This Poll', 'wp-polls'); ?>\"><?php _e('Vote', 'wp-polls'); ?></a></p>\n</div>";
				break;
			case "disable":
				default_template = "<?php _e('Sorry, there are no polls available at the moment.', 'wp-polls'); ?>";
				break;
			case "error":
				default_template = "<?php _e('An error has occurred when processing your poll.', 'wp-polls'); ?>";
				break;
		}
		document.getElementById("poll_template_" + template).value = default_template;
	}
	function set_pollbar_height(height) {
			document.getElementById('poll_bar_height').value = height;
	}
	function update_pollbar(where) {
		pollbar_background = '#' + document.getElementById('poll_bar_bg').value;
		pollbar_border = '#' + document.getElementById('poll_bar_border').value;
		pollbar_height = document.getElementById('poll_bar_height').value + 'px';
		if(where  == 'background') {
			document.getElementById('wp-polls-pollbar-bg').style.backgroundColor = pollbar_background;			
		} else if(where == 'border') {
			document.getElementById('wp-polls-pollbar-border').style.backgroundColor = pollbar_border;
		} else if(where == 'style') {
			pollbar_style_options = document.getElementById('poll_options_form').poll_bar_style;
			for(i = 0; i < pollbar_style_options.length; i++) {
				 if(pollbar_style_options[i].checked)  {
					pollbar_style = pollbar_style_options[i].value;
				 }
			}
			if(pollbar_style == 'use_css') {
				document.getElementById('wp-polls-pollbar').style.backgroundImage = "";
			} else {
				document.getElementById('wp-polls-pollbar').style.backgroundImage = "url('<?php echo get_option('siteurl'); ?>/wp-content/plugins/polls/images/" + pollbar_style + "/pollbg.gif')";
			}
		}
		document.getElementById('wp-polls-pollbar').style.backgroundColor = pollbar_background;
		document.getElementById('wp-polls-pollbar').style.border = '1px solid ' + pollbar_border;
		document.getElementById('wp-polls-pollbar').style.height = pollbar_height;
	}	
/* ]]> */
</script>
<?php if(!empty($text)) { echo '<!-- Last Action --><div id="message" class="updated fade"><p>'.$text.'</p></div>'; } ?>
<div class="wrap"> 
	<h2><?php _e('Poll Options', 'wp-polls'); ?></h2> 
	<form id="poll_options_form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>"> 
		<fieldset class="options">
			<legend><?php _e('Poll Bar Style', 'wp-polls'); ?></legend>
			<table width="100%"  border="0" cellspacing="3" cellpadding="3">
				 <tr valign="top">
					<th align="left" width="20%"><?php _e('Poll Bar Style', 'wp-polls'); ?></th>
					<td align="left" colspan="2">
						<?php
							$pollbar = get_option('poll_bar');
							$pollbar_url = get_option('siteurl').'/wp-content/plugins/polls/images';
							$pollbar_path = ABSPATH.'/wp-content/plugins/polls/images';
							if($handle = @opendir($pollbar_path)) {     
								while (false !== ($filename = readdir($handle))) {  
									if ($filename != '.' && $filename != '..') {
										if(is_dir($pollbar_path.'/'.$filename)) {
											$pollbar_info = getimagesize($pollbar_path.'/'.$filename.'/pollbg.gif');
											if($pollbar['style'] == $filename) {
												echo '<input type="radio" name="poll_bar_style" value="'.$filename.'" checked="checked" onblur="set_pollbar_height('.$pollbar_info[1].'); update_pollbar(\'style\');" />';										
											} else {
												echo '<input type="radio" name="poll_bar_style" value="'.$filename.'" onblur="set_pollbar_height('.$pollbar_info[1].'); update_pollbar(\'style\');" />';
											}
											echo '&nbsp;&nbsp;&nbsp;';
											echo '<img src="'.$pollbar_url.'/'.$filename.'/pollbg.gif" height="'.$pollbar_info[1].'" width="100" alt="pollbg.gif" />';
											echo '&nbsp;&nbsp;&nbsp;('.$filename.')';
											echo '<br /><br />'."\n";
										}
									} 
								} 
								closedir($handle);
							}
						?>
						<input type="radio" name="poll_bar_style" value="use_css"<?php checked('use_css', $pollbar['style']); ?> onblur="update_pollbar('style');" /> <?php _e('Use CSS Style', 'wp-polls'); ?>
					</td>
				</tr>
				<tr valign="top">
					<th align="left" width="20%"><?php _e('Poll Bar Background', 'wp-polls'); ?></th>
					<td align="left" width="10%">#<input type="text" id="poll_bar_bg" name="poll_bar_bg" value="<?php echo $pollbar['background']; ?>" size="6" maxlength="6" onblur="update_pollbar('background');" /></td>
					<td align="left"><div id="wp-polls-pollbar-bg" style="background-color: #<?php echo $pollbar['background']; ?>;"></div></td>
				</tr>
				<tr valign="top">
					<th align="left" width="20%"><?php _e('Poll Bar Border', 'wp-polls'); ?></th>
					<td align="left" width="10%">#<input type="text" id="poll_bar_border" name="poll_bar_border" value="<?php echo $pollbar['border']; ?>" size="6" maxlength="6" onblur="update_pollbar('border');" /></td>
					<td align="left"><div id="wp-polls-pollbar-border" style="background-color: #<?php echo $pollbar['border']; ?>;"></div></td>
				</tr>
				<tr valign="top">
					<th align="left" width="20%"><?php _e('Poll Bar Height', 'wp-polls'); ?></th>
					<td align="left" colspan="2"><input type="text" id="poll_bar_height" name="poll_bar_height" value="<?php echo $pollbar['height']; ?>" size="2" maxlength="2" onblur="update_pollbar('height');" />px</td>
				</tr>
				<tr valign="top">
					<th align="left" width="20%"><?php _e('Your poll bar will look like this', 'wp-polls'); ?></th>
					<td align="left" >
						<?php
							if($pollbar['style'] == 'use_css') {
								echo '<div id="wp-polls-pollbar" style="width: 100px; height: '.$pollbar['height'].'px; background-color: #'.$pollbar['background'].'; border: 1px solid #'.$pollbar['border'].'"></div>'."\n";
							} else {
								echo '<div id="wp-polls-pollbar" style="width: 100px; height: '.$pollbar['height'].'px; background-color: #'.$pollbar['background'].'; border: 1px solid #'.$pollbar['border'].'; background-image: url(\''.get_option('siteurl').'/wp-content/plugins/polls/images/'.$pollbar['style'].'/pollbg.gif\');"></div>'."\n";
							}
						?>
					</td>
				</tr>
			</table>
		</fieldset>
		<fieldset class="options">
			<legend><?php _e('Sorting Of Poll Answers', 'wp-polls'); ?></legend>
			<table width="100%"  border="0" cellspacing="3" cellpadding="3">
				 <tr valign="top">
					<th align="left" width="30%"><?php _e('Sort Poll Answers By:', 'wp-polls'); ?></th>
					<td align="left">
						<select name="poll_ans_sortby" size="1">
							<option value="polla_aid"<?php selected('polla_aid', get_option('poll_ans_sortby')); ?>><?php _e('Exact Order', 'wp-polls'); ?></option>
							<option value="polla_answers"<?php selected('polla_answers', get_option('poll_ans_sortby')); ?>><?php _e('Alphabetical Order', 'wp-polls'); ?></option>
						</select>
					</td>
				</tr>
				<tr valign="top"> 
					<th align="left" width="30%"><?php _e('Sort Order Of Poll Answers:', 'wp-polls'); ?></th>
					<td align="left">
						<select name="poll_ans_sortorder" size="1">
							<option value="asc"<?php selected('asc', get_option('poll_ans_sortorder')); ?>><?php _e('Ascending', 'wp-polls'); ?></option>
							<option value="desc"<?php selected('desc', get_option('poll_ans_sortorder')); ?>><?php _e('Descending', 'wp-polls'); ?></option>
						</select>
					</td> 
				</tr>
			</table>
		</fieldset>
		<fieldset class="options">
			<legend><?php _e('Sorting Of Poll Results', 'wp-polls'); ?></legend>
			<table width="100%"  border="0" cellspacing="3" cellpadding="3">
				 <tr valign="top">
					<th align="left" width="30%"><?php _e('Sort Poll Results By:', 'wp-polls'); ?></th>
					<td align="left">
						<select name="poll_ans_result_sortby" size="1">
							<option value="polla_votes"<?php selected('polla_votes', get_option('poll_ans_result_sortby')); ?>><?php _e('Votes', 'wp-polls'); ?></option>
							<option value="polla_aid"<?php selected('polla_aid', get_option('poll_ans_result_sortby')); ?>><?php _e('Exact Order', 'wp-polls'); ?></option>
							<option value="polla_answers"<?php selected('polla_answers', get_option('poll_ans_result_sortby')); ?>><?php _e('Alphabetical Order', 'wp-polls'); ?></option>
						</select>
					</td>
				</tr>
				<tr valign="top"> 
					<th align="left" width="30%"><?php _e('Sort Order Of Poll Results:', 'wp-polls'); ?></th>
					<td align="left">
						<select name="poll_ans_result_sortorder" size="1">
							<option value="asc"<?php selected('asc', get_option('poll_ans_result_sortorder')); ?>><?php _e('Ascending', 'wp-polls'); ?></option>
							<option value="desc"<?php selected('desc', get_option('poll_ans_result_sortorder')); ?>><?php _e('Descending', 'wp-polls'); ?></option>
						</select>
					</td> 
				</tr>
			</table>
		</fieldset>
		<fieldset class="options">
			<legend><?php _e('Allow To Vote', 'wp-polls'); ?></legend>
			<table width="100%"  border="0" cellspacing="3" cellpadding="3">
				 <tr valign="top">
					<th align="left" width="30%"><?php _e('Who Is Allowed To Vote?', 'wp-polls'); ?></th>
					<td align="left">
						<select name="poll_allowtovote" size="1">
							<option value="0"<?php selected('0', get_option('poll_allowtovote')); ?>><?php _e('Guests Only', 'wp-polls'); ?></option>
							<option value="1"<?php selected('1', get_option('poll_allowtovote')); ?>><?php _e('Registered Users Only', 'wp-polls'); ?></option>
							<option value="2"<?php selected('2', get_option('poll_allowtovote')); ?>><?php _e('Registered Users And Guests', 'wp-polls'); ?></option>
						</select>
					</td>
				</tr>
			</table>
		</fieldset>
		<fieldset class="options">
			<legend><?php _e('Logging Method', 'wp-polls'); ?></legend>
			<table width="100%"  border="0" cellspacing="3" cellpadding="3">
				 <tr valign="top">
					<th align="left" width="30%"><?php _e('Poll Logging Method:', 'wp-polls'); ?></th>
					<td align="left">
						<select name="poll_logging_method" size="1">
							<option value="0"<?php selected('0', get_option('poll_logging_method')); ?>><?php _e('Do Not Log', 'wp-polls'); ?></option>
							<option value="1"<?php selected('1', get_option('poll_logging_method')); ?>><?php _e('Logged By Cookie', 'wp-polls'); ?></option>
							<option value="2"<?php selected('2', get_option('poll_logging_method')); ?>><?php _e('Logged By IP', 'wp-polls'); ?></option>
							<option value="3"<?php selected('3', get_option('poll_logging_method')); ?>><?php _e('Logged By Cookie And IP', 'wp-polls'); ?></option>
							<option value="4"<?php selected('4', get_option('poll_logging_method')); ?>><?php _e('Logged By Username', 'wp-polls'); ?></option>
						</select>
					</td>
				</tr>
			</table>
		</fieldset>
		<fieldset class="options">
			<legend><?php _e('Poll Archive', 'wp-polls'); ?></legend>
			<table width="100%"  border="0" cellspacing="3" cellpadding="3">
				 <tr valign="top">
					<th align="left" width="30%"><?php _e('Polls Per Page:', 'wp-polls'); ?></th>
					<td align="left"><input type="text" name="poll_archive_perpage" value="<?php echo intval(get_option('poll_archive_perpage')); ?>" size="2" /></td>
				</tr>
				<tr valign="top">
					<th align="left" width="30%"><?php _e('Polls Archive URL:', 'wp-polls'); ?></th>
					<td align="left"><input type="text" name="poll_archive_url" value="<?php echo get_option('poll_archive_url'); ?>" size="50" /></td>
				</tr>
				<tr valign="top">
					<th align="left" width="30%"><?php _e('Display Polls Archive Link Below Poll?', 'wp-polls'); ?></th>
					<td align="left">
						<select name="poll_archive_show" size="1">
							<option value="0"<?php selected('0', get_option('poll_archive_show')); ?>><?php _e('No', 'wp-polls'); ?></option>
							<option value="1"<?php selected('1', get_option('poll_archive_show')); ?>><?php _e('Yes', 'wp-polls'); ?></option>
						</select>
					</td>
				</tr>
			</table>
		</fieldset>
		<fieldset class="options">
			<legend><?php _e('Current Active Poll', 'wp-polls'); ?></legend>
			<table width="100%"  border="0" cellspacing="3" cellpadding="3">
				 <tr valign="top">
					<th align="left" width="30%"><?php _e('Current Active Poll', 'wp-polls'); ?>:</th>
					<td align="left">
						<select name="poll_currentpoll" size="1">
							<option value="-1"<?php selected(-1, get_option('poll_currentpoll')); ?>><?php _e('Do NOT Display Poll (Disable)', 'wp-polls'); ?></option>
							<option value="-2"<?php selected(-2, get_option('poll_currentpoll')); ?>><?php _e('Display Random Poll', 'wp-polls'); ?></option>
							<option value="0"<?php selected(0, get_option('poll_currentpoll')); ?>><?php _e('Display Latest Poll', 'wp-polls'); ?></option>
							<option value="0">&nbsp;</option>
							<?php
								$polls = $wpdb->get_results("SELECT pollq_id, pollq_question FROM $wpdb->pollsq ORDER BY pollq_id DESC");
								if($polls) {
									foreach($polls as $poll) {
										$poll_question = stripslashes($poll->pollq_question);
										$poll_id = intval($poll->pollq_id);
										if($poll_id == intval(get_option('poll_currentpoll'))) {
											echo "<option value=\"$poll_id\" selected=\"selected\">$poll_question</option>\n";
										} else {
											echo "<option value=\"$poll_id\">$poll_question</option>\n";
										}
									}
								}
							?>
						</select>
					</td>
				</tr>
				 <tr valign="top">
					<th align="left" width="30%"><?php _e('When Poll Is Closed', 'wp-polls'); ?>:</th>
					<td align="left">
						<select name="poll_close" size="1">
							<option value="1"<?php selected(1, get_option('poll_close')); ?>><?php _e('Display Poll\'s Results', 'wp-polls'); ?></option>
							<option value="2"<?php selected(2, get_option('poll_close')); ?>><?php _e('Do Not Display Poll In Post/Sidebar', 'wp-polls'); ?></option>
						</select>
					</td>
				</tr>
			</table>
		</fieldset>
		<fieldset class="options">
			<legend><?php _e('Template Variables', 'wp-polls'); ?></legend>
			<table width="100%"  border="0" cellspacing="0" cellpadding="5">
				<tr>
					<td>
						<strong>%POLL_ID%</strong><br />
						<?php _e('Display the poll\'s ID', 'wp-polls'); ?>
					</td>
					<td>
						<strong>%POLL_ANSWER_ID%</strong><br />
						<?php _e('Display the poll\'s answer ID', 'wp-polls'); ?>
					</td>
				</tr>
				<tr>
					<td>
						<strong>%POLL_QUESTION%</strong><br />
						<?php _e('Display the poll\'s question', 'wp-polls'); ?>
					</td>
					<td>
						<strong>%POLL_ANSWER%</strong><br />
						<?php _e('Display the poll\'s answer', 'wp-polls'); ?>
					</td>
				</tr>
				<tr>
					<td>
						<strong>%POLL_TOTALVOTES%</strong><br />
						<?php _e('Display the poll\'s total votes', 'wp-polls'); ?>
					</td>
					<td>
						<strong>%POLL_ANSWER_TEXT%</strong><br />
						<?php _e('Display the poll\'s answer without HTML formatting.', 'wp-polls'); ?>
					</td>
				</tr>
				<tr>
					<td>
						<strong>%POLL_RESULT_URL%</strong><br />
						<?php _e('Displays URL to poll\'s result', 'wp-polls'); ?>
					</td>
					<td>
						<strong>%POLL_ANSWER_VOTES%</strong><br />
						<?php _e('Display the poll\'s answer votes', 'wp-polls'); ?>
					</td>
				</tr>
				<tr>
					<td>
						<strong>%POLL_MOST_ANSWER%</strong><br />
						<?php _e('Display the poll\'s most voted answer', 'wp-polls'); ?>
					</td>
					<td>
						<strong>%POLL_ANSWER_PERCENTAGE%</strong><br />
						<?php _e('Display the poll\'s answer percentage', 'wp-polls'); ?>
					</td>
				</tr>
				<tr>
					<td>
						<strong>%POLL_MOST_VOTES%</strong><br />
						<?php _e('Display the poll\'s answer votes for the most voted answer', 'wp-polls'); ?>
					</td>
					<td>
						<strong>%POLL_ANSWER_IMAGEWIDTH%</strong><br />
						<?php _e('Display the poll\'s answer image width', 'wp-polls'); ?>
					</td>
				</tr>
				<tr>
					<td>
						<strong>%POLL_MOST_PERCENTAGE%</strong><br />
						<?php _e('Display the poll\'s answer percentage for the most voted answer', 'wp-polls'); ?>
					</td>
					<td>
						<strong>%POLL_LEAST_ANSWER%</strong><br />
						<?php _e('Display the poll\'s least voted answer', 'wp-polls'); ?>
					</td>
				</tr>
				<tr>
					<td><strong>%POLL_START_DATE%</strong><br />
					<?php _e('Display the poll\'s start date/time', 'wp-polls'); ?></td>
					<td><strong>%POLL_LEAST_VOTES%</strong><br />
					<?php _e('Display the poll\'s answer votes for the least voted answer', 'wp-polls'); ?>
				</td>
				</tr>
				<tr>
					<td><strong>%POLL_END_DATE%</strong><br />
					<?php _e('Display the poll\'s end date/time', 'wp-polls'); ?></td>
					<td>
						<strong>%POLL_LEAST_PERCENTAGE%</strong><br />
						<?php _e('Display the poll\'s answer percentage for the least voted answer', 'wp-polls'); ?>
					</td>
				</tr>
			</table>
		</fieldset>
		<fieldset class="options">
			<legend><?php _e('Poll Voting Form Templates', 'wp-polls'); ?></legend>
			<table width="100%"  border="0" cellspacing="3" cellpadding="3">
				 <tr valign="top">
					<td width="30%" align="left">
						<strong><?php _e('Voting Form Header:', 'wp-polls'); ?></strong><br /><br /><br />
						<?php _e('Allowed Variables:', 'wp-polls'); ?><br />
						- %POLL_ID%<br />
						- %POLL_QUESTION%<br />
						- %POLL_START_DATE%<br />
						- %POLL_END_DATE%<br />
						- %POLL_TOTALVOTES%<br /><br />
						<input type="button" name="RestoreDefault" value="<?php _e('Restore Default Template', 'wp-polls'); ?>" onclick="javascript: poll_default_templates('voteheader');" class="button" />
					</td>
					<td align="left"><textarea cols="80" rows="10" id="poll_template_voteheader" name="poll_template_voteheader"><?php echo htmlspecialchars(stripslashes(get_option('poll_template_voteheader'))); ?></textarea></td>
				</tr>
				<tr valign="top"> 
					<td width="30%" align="left">
						<strong><?php _e('Voting Form Body:', 'wp-polls'); ?></strong><br /><br /><br />
						<?php _e('Allowed Variables:', 'wp-polls'); ?><br />
						- %POLL_ID%<br />
						- %POLL_ANSWER_ID%<br />
						- %POLL_ANSWER%<br />
						- %POLL_ANSWER_VOTES%<br /><br />
						<input type="button" name="RestoreDefault" value="<?php _e('Restore Default Template', 'wp-polls'); ?>" onclick="javascript: poll_default_templates('votebody');" class="button" />
					</td>
					<td align="left"><textarea cols="80" rows="10" id="poll_template_votebody" name="poll_template_votebody"><?php echo htmlspecialchars(stripslashes(get_option('poll_template_votebody'))); ?></textarea></td> 
				</tr>
				<tr valign="top"> 
					<td width="30%" align="left">
						<strong><?php _e('Voting Form Footer:', 'wp-polls'); ?></strong><br /><br /><br />
							<?php _e('Allowed Variables:', 'wp-polls'); ?><br />
							- %POLL_ID%<br />
							- %POLL_RESULT_URL%<br /><br />
						<input type="button" name="RestoreDefault" value="<?php _e('Restore Default Template', 'wp-polls'); ?>" onclick="javascript: poll_default_templates('votefooter');" class="button" />
					</td>
					<td align="left"><textarea cols="80" rows="10" id="poll_template_votefooter" name="poll_template_votefooter"><?php echo htmlspecialchars(stripslashes(get_option('poll_template_votefooter'))); ?></textarea></td> 
				</tr>
			</table>
		</fieldset>
		<fieldset class="options">
			<legend><?php _e('Poll Result Templates', 'wp-polls'); ?></legend>
			<table width="100%"  border="0" cellspacing="3" cellpadding="3"> 
				 <tr valign="top">
					<td width="30%" align="left">
						<strong><?php _e('Result Header:', 'wp-polls'); ?></strong><br /><br /><br />
						<?php _e('Allowed Variables:', 'wp-polls'); ?><br />
						- %POLL_ID%<br />
						- %POLL_QUESTION%<br />
						- %POLL_START_DATE%<br />
						- %POLL_END_DATE%<br />
						- %POLL_TOTALVOTES%<br /><br />
						<input type="button" name="RestoreDefault" value="<?php _e('Restore Default Template', 'wp-polls'); ?>" onclick="javascript: poll_default_templates('resultheader');" class="button" />
					</td>
					<td align="left"><textarea cols="80" rows="10" id="poll_template_resultheader" name="poll_template_resultheader"><?php echo htmlspecialchars(stripslashes(get_option('poll_template_resultheader'))); ?></textarea></td>
				</tr>
				<tr valign="top"> 
					<td width="30%" align="left">
						<strong><?php _e('Result Body:', 'wp-polls'); ?></strong><br /><?php _e('Normal', 'wp-polls'); ?><br /><br />
						<?php _e('Allowed Variables:', 'wp-polls'); ?><br />
						- %POLL_ANSWER_ID%<br />
						- %POLL_ANSWER%<br />
						- %POLL_ANSWER_TEXT%<br />
						- %POLL_ANSWER_VOTES%<br />
						- %POLL_ANSWER_PERCENTAGE%<br />
						- %POLL_ANSWER_IMAGEWIDTH%<br /><br />
						<input type="button" name="RestoreDefault" value="<?php _e('Restore Default Template', 'wp-polls'); ?>" onclick="javascript: poll_default_templates('resultbody');" class="button" />
					</td>
					<td align="left"><textarea cols="80" rows="10" id="poll_template_resultbody" name="poll_template_resultbody"><?php echo htmlspecialchars(stripslashes(get_option('poll_template_resultbody'))); ?></textarea></td> 
				</tr>
				<tr valign="top"> 
					<td width="30%" align="left">
						<strong><?php _e('Result Body:', 'wp-polls'); ?></strong><br /><?php _e('Displaying Of User\'s Voted Answer', 'wp-polls'); ?><br /><br />
						<?php _e('Allowed Variables:', 'wp-polls'); ?><br />
						- %POLL_ANSWER_ID%<br />
						- %POLL_ANSWER%<br />
						- %POLL_ANSWER_TEXT%<br />
						- %POLL_ANSWER_VOTES%<br />
						-  %POLL_ANSWER_PERCENTAGE%<br />
						- %POLL_ANSWER_IMAGEWIDTH%<br /><br />
						<input type="button" name="RestoreDefault" value="<?php _e('Restore Default Template', 'wp-polls'); ?>" onclick="javascript: poll_default_templates('resultbody2');" class="button" />
					</td>
					<td align="left"><textarea cols="80" rows="10" id="poll_template_resultbody2" name="poll_template_resultbody2"><?php echo htmlspecialchars(stripslashes(get_option('poll_template_resultbody2'))); ?></textarea></td> 
				</tr>
				<tr valign="top"> 
					<td width="30%" align="left">
						<strong><?php _e('Result Footer:', 'wp-polls'); ?></strong><br /><?php _e('Normal', 'wp-polls'); ?><br /><br />
						<?php _e('Allowed Variables:', 'wp-polls'); ?><br />
						- %POLL_ID%<br />
						- %POLL_START_DATE%<br />
						- %POLL_END_DATE%<br />
						- %POLL_TOTALVOTES%<br />
						- %POLL_MOST_ANSWER%<br />
						- %POLL_MOST_VOTES%<br />
						- %POLL_MOST_PERCENTAGE%<br />
						- %POLL_LEAST_ANSWER%<br />
						- %POLL_LEAST_VOTES%<br />
						- %POLL_LEAST_PERCENTAGE%<br /><br />
						<input type="button" name="RestoreDefault" value="<?php _e('Restore Default Template', 'wp-polls'); ?>" onclick="javascript: poll_default_templates('resultfooter');" class="button" />
					</td>
					<td align="left"><textarea cols="80" rows="10" id="poll_template_resultfooter" name="poll_template_resultfooter"><?php echo htmlspecialchars(stripslashes(get_option('poll_template_resultfooter'))); ?></textarea></td> 
				</tr>
				<tr valign="top"> 
					<td width="30%" align="left">
						<strong><?php _e('Result Footer:', 'wp-polls'); ?></strong><br /><?php _e('Displaying Of Vote Poll Link If User Has Not Voted', 'wp-polls'); ?><br /><br />
						<?php _e('Allowed Variables:', 'wp-polls'); ?><br />
						- %POLL_ID%<br />
						- %POLL_START_DATE%<br />
						- %POLL_END_DATE%<br />
						- %POLL_TOTALVOTES%<br />
						- %POLL_MOST_ANSWER%<br />
						- %POLL_MOST_VOTES%<br />
						- %POLL_MOST_PERCENTAGE%<br />
						- %POLL_LEAST_ANSWER%<br />
						- %POLL_LEAST_VOTES%<br />
						- %POLL_LEAST_PERCENTAGE%<br /><br />
						<input type="button" name="RestoreDefault" value="<?php _e('Restore Default Template', 'wp-polls'); ?>" onclick="javascript: poll_default_templates('resultfooter2');" class="button" />
					</td>
					<td align="left"><textarea cols="80" rows="10" id="poll_template_resultfooter2" name="poll_template_resultfooter2"><?php echo htmlspecialchars(stripslashes(get_option('poll_template_resultfooter2'))); ?></textarea></td> 
				</tr>
			</table>
		</fieldset>
		<fieldset class="options">
			<legend><?php _e('Poll Misc Templates', 'wp-polls'); ?></legend>
			<table width="100%"  border="0" cellspacing="3" cellpadding="3"> 
				 <tr valign="top">
					<td width="30%" align="left">
						<strong><?php _e('Poll Disabled', 'wp-polls'); ?></strong><br /><br /><br />
						<?php _e('Allowed Variables:', 'wp-polls'); ?><br />
						- <?php _e('N/A', 'wp-polls'); ?><br /><br />
						<input type="button" name="RestoreDefault" value="<?php _e('Restore Default Template', 'wp-polls'); ?>" onclick="javascript: poll_default_templates('disable');" class="button" />
					</td>
					<td align="left"><textarea cols="80" rows="10" id="poll_template_disable" name="poll_template_disable"><?php echo htmlspecialchars(stripslashes(get_option('poll_template_disable'))); ?></textarea></td>
				</tr>
				<tr valign="top">
					<td width="30%" align="left">
						<strong><?php _e('Poll Error', 'wp-polls'); ?></strong><br /><br /><br />
						<?php _e('Allowed Variables:', 'wp-polls'); ?><br />
						- <?php _e('N/A', 'wp-polls'); ?><br /><br />
						<input type="button" name="RestoreDefault" value="<?php _e('Restore Default Template', 'wp-polls'); ?>" onclick="javascript: poll_default_templates('error');" class="button" />
					</td>
					<td align="left"><textarea cols="80" rows="10" id="poll_template_error" name="poll_template_error"><?php echo htmlspecialchars(stripslashes(get_option('poll_template_error'))); ?></textarea></td>
				</tr>
			</table>
		</fieldset>
		<div align="center">
			<input type="submit" name="Submit" class="button" value="<?php _e('Update Options', 'wp-polls'); ?>" />&nbsp;&nbsp;<input type="button" name="cancel" value="<?php _e('Cancel', 'wp-polls'); ?>" class="button" onclick="javascript:history.go(-1)" /> 
		</div>
	</form> 
</div> 