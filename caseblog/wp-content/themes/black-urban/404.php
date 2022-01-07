<?php
/**
 * @package WordPress
 * @subpackage Urban_Theme
 */
?>
<?php get_header(); ?>
	<div id="posts">
	    <div class="post">
	        <div class="post-title"> 
                <h2><span>404. Page Not Found. Try a search?</span></h2>
		    </div>
		    <div class="post-entry">
		        <p><?php get_search_form() ?></p>
		    </div>
            <div class="post-footer">
                <div class="post-footer-line clear"></div>
            </div>
	    </div>
	</div>
		
    <div class="navigation">
        <div class="alignleft"><?php next_posts_link(__('Older Entries')) ?></div>
        <div class="alignright"><?php previous_posts_link(__('Newer Entries')) ?></div>
        <div class="clear">&nbsp;</div>
    </div>            
<?php get_footer(); ?>