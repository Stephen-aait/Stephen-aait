<?php
/**
 * @package WordPress
 * @subpackage Urban_Theme
 */
// Stupid Parser This is Theme support the Gravatar Service
// get_avatar();
?>
<?php get_header(); ?>
    <?php if (have_posts()) : ?>
        <div id="posts">
        <?php while (have_posts()) : the_post(); ?>
            <div class="post" id="post-<?php the_ID() ?>">
                <div class="post-title">
                    <h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
                </div>
                <div class="post-entry">
                    <?php the_content(__('Read the rest of this entry &raquo;')) ?>
                </div>
                <div class="post-footer">
                    <div class="post-footer-links right">
                    <?php the_time('F jS, Y') ?> | 
                    <?php the_tags('Tags: ', ', ', '|'); ?>
                    <?php edit_post_link('Edit', '', ' | '); ?>
                    <?php comments_popup_link(__('No Comments &#187;'), __('1 Comment &#187;'), __('% Comments &#187;')); ?>
                    </div>
                    <div class="post-footer-line clear"></div>
                </div>
            </div>
        <?php endwhile; ?>
        </div>
        <div class="navigation">
            <div class="alignleft"><?php next_posts_link(__('Older Entries')) ?></div>
            <div class="alignright"><?php previous_posts_link(__('Newer Entries')) ?></div>
            <div class="clear">&nbsp;</div>
        </div>
	<?php else : ?>
        <div id="posts">
		<h2 class="center">No posts found. Try a different search?</h2> 
        </div>
    <?php endif; ?>
<?php get_footer(); ?>