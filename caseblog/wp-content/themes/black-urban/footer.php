<?php
/**
 * @package WordPress
 * @subpackage Urban_Theme
 */
?>
        </div><!-- id='container' -->	
    </div><!-- id='wrapper' -->
    <div id="sidebar">
        <?php get_sidebar(); ?>
    </div>
	<div id="footer">
	
		<?php 	/* Widgetized sidebar, if you have the plugin installed. */
			if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer') ) : ?>
	    	
        	<div>
        	    <h3><?php _e('Categories')?></h3>
        		<ul>                       
        			<?php wp_list_categories('title_li='); ?>
        		</ul>
    		</div>
			<div>
	    	    <h3><?php _e('Archives') ?></h3>
    			<ul>
    			<?php wp_get_archives('type=monthly&limit=12'); ?>
    			</ul>
        	</div>     
        	<div>
        	    <h3><?php _e('Blogroll')?></h3>
        	   <ul>
               <?php wp_list_bookmarks('title_li=&categorize=0'); ?>
               </ul>
        	</div>
		<?php endif; ?>
    	<p class="clear copy">
        	&copy; 2009 <?php bloginfo('name'); ?> is proudly powered by <a href="http://wordpress.org/" title="Wordpress">WordPress</a> | 
        	<a href="http://anton.shevchuk.name/web20/creative-design-in-15-minutes/">Black Urban Theme</a> by <a href="http://anton.shevchuk.name" title="Anton Shevchuk">Anton Shevchuk</a>
    	</p>
	</div>
</div>
<?php wp_footer(); ?>
</body>
</html>