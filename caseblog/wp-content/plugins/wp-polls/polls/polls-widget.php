<?php
/*
Plugin Name: WP-Polls Widget
Plugin URI: http://www.lesterchan.net/portfolio/programming.php
Description: Adds a Sidebar Widget To Display Poll From WP-Polls Plugin. You Need To Activate WP-Polls First.
Version: 2.14
Author: GaMerZ
Author URI: http://www.lesterchan.net
*/


/*  
	Copyright 2007  Lester Chan  (email : gamerz84@hotmail.com)

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


### Function: Init WP-Polls Widget
function widget_polls_init() {
	if (!function_exists('register_sidebar_widget')) {
		return;
	}

	### Function: WP-Polls Widget
	function widget_polls($args) {
		global $in_pollsarchive;
		extract($args);
		$options = get_option('widget_polls');
		$title = htmlspecialchars($options['title']);		
		if (function_exists('vote_poll') && !$in_pollsarchive) {
			echo $before_widget.$before_title.$title.$after_title;
			get_poll();
			display_polls_archive_link();
			echo $after_widget;
		}		
	}

	### Function: WP-Polls Widget Options
	function widget_polls_options() {
		global $wpdb;
		$options = get_option('widget_polls');
		$current_poll = get_option('poll_currentpoll');
		if (!is_array($options)) {
			$options = array('title' => __('Polls', 'wp-polls'));
		}
		if ($_POST['polls-submit']) {
			$poll_currentpoll = intval($_POST['poll_currentpoll']);
			$poll_archive_show = intval($_POST['poll_archive_show']);		
			$options['title'] = strip_tags(stripslashes($_POST['polls-title']));
			update_option('widget_polls', $options);
			update_option('poll_currentpoll', $poll_currentpoll);
			update_option('poll_archive_show', $poll_archive_show);
		}
		echo '<p style="text-align: left;"><label for="polls-title">';
		_e('Title');
		echo ': </label><input type="text" id="polls-title" name="polls-title" value="'.htmlspecialchars($options['title']).'" />'."\n";
		echo '<p style="text-align: left;"><label for="polls-displayarchive">';
		_e('Display Polls Archive Link Below Poll?', 'wp-polls');
		echo ' </label>'."\n";
		echo '<select id="polls-displayarchive" name="poll_archive_show" size="1">'."\n";
		echo '<option value="0"';
		selected('0', get_option('poll_archive_show'));
		echo '>';
		_e('No');
		echo '</option>'."\n";
		echo '<option value="1"';
		selected('1', get_option('poll_archive_show'));
		echo '>';
		_e('Yes');
		echo '</option>'."\n";
		echo '</select></p>'."\n";
		echo '<p style="text-align: left;"><label for="poll_currentpoll">';
		_e('Current Active Poll', 'wp-polls');
		echo ': </label>'."\n";
		echo '<select id="poll_currentpoll" name="poll_currentpoll" size="1">'."\n";
		echo '<option value="-1"';
		selected(-1, $current_poll);
		echo '>';
		_e('Do NOT Display Poll (Disable)', 'wp-polls');
		echo '</option>'."\n";
		echo '<option value="-2"';
		selected(-2, $current_poll);
		echo '>';
		_e('Display Random Poll', 'wp-polls');
		echo '</option>'."\n";
		echo '<option value="0"';
		selected(0, $current_poll);
		echo '>';
		_e('Display Latest Poll', 'wp-polls');
		echo '</option>'."\n";
		echo '<option value="0">&nbsp;</option>'."\n";
		$polls = $wpdb->get_results("SELECT pollq_id, pollq_question FROM $wpdb->pollsq ORDER BY pollq_id DESC");
		if($polls) {
			foreach($polls as $poll) {
				$poll_question = stripslashes($poll->pollq_question);
				$poll_id = intval($poll->pollq_id);
				if($poll_id == intval($current_poll)) {
					echo "<option value=\"$poll_id\" selected=\"selected\">$poll_question</option>\n";
				} else {
					echo "<option value=\"$poll_id\">$poll_question</option>\n";
				}
			}
		}
		echo '</select>'."\n";
		echo '</p>'."\n";
		echo '<input type="hidden" id="polls-submit" name="polls-submit" value="1" />'."\n";
	}

	// Register Widgets
	register_sidebar_widget(array('Polls', 'wp-polls'), 'widget_polls');
	register_widget_control(array('Polls', 'wp-polls'), 'widget_polls_options', 400, 150);
}


### Function: Load The WP-Polls Widget
add_action('plugins_loaded', 'widget_polls_init');
?>