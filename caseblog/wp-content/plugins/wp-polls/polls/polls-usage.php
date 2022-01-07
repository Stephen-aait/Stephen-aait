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
|	- How To Use WP-Polls															|
|	- wp-content/plugins/polls/polls-usage.php								|
|																							|
+----------------------------------------------------------------+
*/


### Check Whether User Can Manage Polls
if(!current_user_can('manage_polls')) {
	die('Access Denied');
}
?>
<div class="wrap"> 
	<h2><?php _e("General Usage (Without Widget)", 'wp-polls'); ?></h2>
	<ol>
		<li>
			<?php _e("Open ", 'wp-polls'); ?><b>wp-content/themes/&lt;<?php _e("YOUR THEME NAME", 'wp-polls'); ?>&gt;/sidebar.php</b>
		</li>
		<li>
			<?php _e("Add:", 'wp-polls'); ?>
			<blockquote>
				<pre class="wp-polls-usage-pre">&lt;?php if (function_exists('vote_poll') &amp;&amp; !$in_pollsarchive): ?&gt;
&lt;li&gt;
&nbsp;&nbsp;&nbsp;&lt;h2&gt;Polls&lt;/h2&gt;
&nbsp;&nbsp;&nbsp;&lt;ul&gt;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;li&gt;&lt;?php get_poll();?&gt;&lt;/li&gt;
&nbsp;&nbsp;&nbsp;&lt;/ul&gt;
&nbsp;&nbsp;&nbsp;&lt;?php display_polls_archive_link(); ?&gt;
&lt;/li&gt;
&lt;?php endif; ?&gt;	</pre>
			</blockquote>
			<?php _e("To show specific poll, use :", 'wp-polls'); ?>
			<blockquote><pre class="wp-polls-usage-pre">&lt;?php get_poll(<b>2</b>);?&gt;</pre></blockquote>
			<?php _e("where <b>2</b> is your poll id.", 'wp-polls'); ?>
			<?php _e("To embed a specific poll in your post, use :", 'wp-polls'); ?>
			<blockquote><pre class="wp-polls-usage-pre">[poll=<b>2</b>]</pre></blockquote>
			<?php _e("where <b>2</b> is your poll id.", 'wp-polls'); ?>
		</li>
		<li>
			<?php _e("Scroll down for instructions on how to create <b>Polls Archive</b>.", 'wp-polls'); ?>
		</li>
	</ol>
</div>
<div class="wrap"> 
	<h2><?php _e("General Usage (With Widget)", 'wp-polls'); ?></h2>
	<ol>
		<li>
			<?php _e("<b>Activate</b> WP-Polls Widget Plugin", 'wp-polls'); ?>
		</li>
		<li>
			<?php _e("Go to 'WP-Admin -> Presentation -> Sidebar Widgets'", 'wp-polls'); ?>
		</li>
		<li>
			<?php _e("<b>Drag</b> the Polls Widget to your sidebar", 'wp-polls'); ?>
		</li>
		<li>
			<?php _e("You can <b>configure</b> the Polls Widget by clicking on the configure icon", 'wp-polls'); ?>
		</li>
		<li>
			<?php _e("Click 'Save changes'", 'wp-polls'); ?>
		</li>
		<li>
			<?php _e(" down for instructions on how to create a <b>Polls Archive</b>.", 'wp-polls'); ?>
		</li>
	</ol>
</div>
<div class="wrap"> 
	<h2><?php _e("Polls Archive", 'wp-polls'); ?></h2>
	<ol>
		<li>
			<?php _e("Go to 'WP-Admin -> Write -> Write Page'", 'wp-polls'); ?>
		</li>
		<li>
			<?php _e("Type any title you like in the post's title area", 'wp-polls'); ?>
		</li>
		<li>
			<?php _e("Type '<b>[page_polls]</b>' in the post's content area (without the quotes)", 'wp-polls'); ?>
		</li>
		<li>
			<?php _e("Type '<b>pollsarchive</b>' in the post's slug area (without the quotes)", 'wp-polls'); ?>
		</li>
		<li>
			<?php _e("Click 'Publish'", 'wp-polls'); ?>
		</li>
		<li>
			<?php _e("If you <b>ARE NOT</b> using nice permalinks, you need to go to 'WP-Admin -> Polls -> Poll Option' and under '<b>Poll Archive -> Polls Archive URL</b>', you need to fill in the URL to the Polls Archive Page you created above.", 'wp-polls'); ?>
		</li>
	</ol>
</div>
<div class="wrap"> 
	<h2><?php _e('Polls Stats', 'wp-polls'); ?></h2> 
	<h3><?php _e("To Display Total Polls", 'wp-polls'); ?></h3>
	<blockquote>
		<pre class="wp-polls-usage-pre">&lt;?php if (function_exists('get_pollquestions')): ?&gt;
&nbsp;&nbsp;&nbsp;&lt;?php get_pollquestions(); ?&gt;
&lt;?php endif; ?&gt;	</pre>
	</blockquote>
	<h3><?php _e("To Display Total Poll Answers", 'wp-polls'); ?></h3>
	<blockquote>
		<pre class="wp-polls-usage-pre">&lt;?php if (function_exists('get_pollanswers')): ?&gt;
&nbsp;&nbsp;&nbsp;&lt;?php get_pollanswers(); ?&gt;
&lt;?php endif; ?&gt;	</pre>
	</blockquote>
	<h3><?php _e("To Display Total Poll Votes", 'wp-polls'); ?></h3>
	<blockquote>
		<pre class="wp-polls-usage-pre">&lt;?php if (function_exists('get_pollvotes')): ?&gt;
&nbsp;&nbsp;&nbsp;&lt;?php get_pollvotes(); ?&gt;
&lt;?php endif; ?&gt;	</pre>
	</blockquote>
</div>
<div class="wrap"> 
	<h2><?php _e("Note", 'wp-polls'); ?></h2>
	<ul>
		<li>
			<?php _e("In IE, some of the poll's text may appear jagged (this is normal in IE). To solve this issue,", 'wp-polls'); ?>
			<ol>
				<li>
					<?php _e("Open <b>poll-css.css</b>", 'wp-polls'); ?>
				</li>
				<li>
					<?php _e("Find:", 'wp-polls'); ?>
					<blockquote><pre class="wp-polls-usage-pre">/* background-color: #ffffff; */</pre></blockquote>
				</li>
				<li>
					<?php _e("Replace:", 'wp-polls'); ?>
					<blockquote><pre class="wp-polls-usage-pre">background-color: #ffffff;</pre></blockquote>
					<?php _e("Where <b>#ffffff</b> should be your background color for the poll.", 'wp-polls'); ?>
				</li>
			</ol>
		</li>
	</ul>
</div>