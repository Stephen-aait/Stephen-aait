<?php
/*
Template Name: Archives Template
*/
?>
<?php
/**
 * @package WordPress
 * @subpackage Urban_Theme
 */
?>
<?php get_header(); ?>
        <div id="posts">
        <?php while (have_posts()) : the_post(); ?>
            <div class="post" id="post-<?php the_ID() ?>">
                <div class="post-title">
                    <h2>Archive</h2>
                </div>
                <div class="post-entry">
                    <ul>
                       <?php wp_get_archives('type=monthly&show_post_count=1'); ?>
                    </ul>
                </div>
                <div class="post-footer">
                    <div class="post-footer-links right">
                    <?php edit_post_link('Edit', '', ' | '); ?>
                    </div>
                    <div class="post-footer-line clear"></div>
                </div>
            </div>
        <?php endwhile; ?>
        </div>
<?php get_footer(); ?>

                    