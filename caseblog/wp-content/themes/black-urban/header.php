<?php
/**
 * @package WordPress
 * @subpackage Urban_Theme
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
    <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
    <title><?php wp_title(''); ?></title>
    <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
    <link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />    
    <link rel="alternate" type="application/atom+xml" title="<?php bloginfo('name'); ?> Atom Feed" href="<?php bloginfo('atom_url'); ?>" />
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    <?php wp_get_archives('type=monthly&format=link'); ?>
    <?php wp_head(); ?>
</head>
<body>
<div id="body">
   <div id="header">
        <div class="logo">
            <h1><a href="<?php echo get_option('home'); ?>/" title="<?php bloginfo('name'); echo " &raquo; "; bloginfo('description');?>"><?php bloginfo('name'); ?></a></h1>
            <h2><?php bloginfo('description');?></h2>
        </div>
        <div class="links">
            <a id="home"    href="<?php blackurban_link(0, 'href') ?>" title="<?php blackurban_link(0, 'title') ?>"><?php blackurban_link(0, 'title') ?></a>
            <a id="online"  href="<?php blackurban_link(1, 'href') ?>" title="<?php blackurban_link(1, 'title') ?>"><?php blackurban_link(1, 'title') ?></a>
            <a id="archive" href="<?php blackurban_link(2, 'href') ?>" title="<?php blackurban_link(2, 'title') ?>"><?php blackurban_link(2, 'title') ?></a>
            <a id="rss" href="<?php bloginfo('rss2_url'); ?>"  title="RSS Feed">RSS Feed</a>
        </div>
   </div>
   
   
   <div id="wrapper">
   <div id="container">
                    