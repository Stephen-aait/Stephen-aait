<?php
/**
 * @package WordPress
 * @subpackage Urban_Theme
 */
?>
	<ul>
	    <?php if (!is_404()) : ?>
	    <li>
			<?php get_search_form(); ?>
		</li>
		<?php endif; ?>
		<?php 	/* Widgetized sidebar, if you have the plugin installed. */
				if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar') ) : ?>
		
		
		<?php // wp_list_pages('title_li=<h2>Pages</h2>' ); ?>

		<?php wp_list_categories('show_count=1&title_li=<h3>'.__('Categories').'</h3>'); ?>
		
        <li><h3><?php _e('Tags')?></h3>
            <div class="tagcloud">
    	    <?php if(function_exists('wp_tag_cloud')) { wp_tag_cloud('smallest=8&largest=18&number=40'); } ?>
    	    </div>
	    </li>


		<?php /* If this is the frontpage */ if ( is_home() || is_page() ) { ?>
			<li><h3><?php _e('Meta') ?></h3>
			<ul>
				<?php wp_register(); ?>
				<li><?php wp_loginout(); ?></li>
				<?php wp_meta(); ?>
			</ul>
			</li>
		<?php } ?>

		<?php endif; ?>
	</ul>