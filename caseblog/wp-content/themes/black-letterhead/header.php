<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head profile="http://gmpg.org/xfn/11">

<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php bloginfo('name'); ?> <?php if ( is_single() ) { ?> &raquo; Blog Archive <?php } ?> <?php wp_title(); ?></title>

<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" /> 

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />

<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />

<link rel="alternate" type="text/xml" title="RSS .92" href="<?php bloginfo('rss_url'); ?>" />

<link rel="alternate" type="application/atom+xml" title="Atom 0.3" href="<?php bloginfo('atom_url'); ?>" />

<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<?php wp_get_archives('type=monthly&format=link'); ?>

<?php wp_head(); ?>

 <link rel="stylesheet" href="/menus/menustyles.css">
   <script src="/menus/jQuery.js" type="text/javascript"></script>
   <script>
$(document).ready(function(){

	$("ul.subnav").parent().append("<span></span>"); //Only shows drop down trigger when js is enabled (Adds empty span tag after ul.subnav*)
	
	$("ul.topnav li span").click(function() { //When trigger is clicked...
		
		//Following events are applied to the subnav itself (moving subnav up and down)
		$(this).parent().find("ul.subnav").slideDown('fast').show(); //Drop down the subnav on click

		$(this).parent().hover(function() {
		}, function(){	
			$(this).parent().find("ul.subnav").slideUp('slow'); //When the mouse hovers out of the subnav, move it back up
		});

		//Following events are applied to the trigger (Hover events for the trigger)
		}).hover(function() { 
			$(this).addClass("subhover"); //On hover over, add class "subhover"
		}, function(){	//On Hover Out
			$(this).removeClass("subhover"); //On hover out, remove class "subhover"
	});

});
</script>
</head>

<body>

<div id="page">

<div id="header" onclick="location.href='<?php bloginfo('url');?>';" style="cursor:pointer;">





<img border="0" src="http://www.jbcases.com/images/logo_ban1.jpg" width="731" height="105"></td>



</div>

<div id='cssmenu'><ul>
  <li><a href="http://www.jbcases.com/">HOME</a>
  </li>
  <li><a href="#">ORDERING</a>
    <ul>
      <li><a href="http://www.jbcases.com/ordering.html">How To Order</a></li>
      <li><a href="http://www.jbcases.com/ordering.html#Design">Design Guide</a></li>
      <li><a href="#">Design Options</a>
        <ul>
          <li><a href="http://www.jbcases.com/coloroptions.html">Color Options</a></li>
          <li><a href="http://www.jbcases.com/snakeskins.html">Snake Skins</a></li>
          <li><a href="http://www.jbcases.com/contrastoptions.html">Contrast Options</a></li>
          <li><a href="http://www.jbcases.com/leatheroptions.html">Leather Options</a></li>
          <li><a href="http://www.jbcases.com/toolingoptions.html">Tooling Options</a></li>
        </ul>
      </li>
      <li><a href="http://www.jbcases.com/ordering.html#Pricing">Pricing</a></li>
      <li><a href="http://www.jbcases.com/faq.html">Frequent Questions</a></li>
    </ul>
  </li>
  <li><a href="#">CASES</a>
    <ul>
      <li><a href="http://www.jbcases.com/casesbyname.html">Gallery</a></li>
      <li><a href="http://www.jbcases.com/casesbyname.html">Cases By Name</a></li>
      <li><a href="http://www.jbcases.com/fullcustom.html">Full Custom</a></li>
      <li><a href="#">Semi-Custom</a>
        <ul>
          <li><a href="http://www.jbcases.com/buffalostyle.html">Buffalo</a></li>
          <li><a href="#">Butterfly</a></li>
          <li><a href="http://www.jbcases.com/jflowers.html">J. Flowers</a></li>
          <li><a href="http://www.jbcases.com/rollsroycestyle.html">Rolls Royce</a></li>
          <li><a href="http://www.jbcases.com/scallopstyle.html">Scallop</a></li>
          <li><a href="http://www.jbcases.com/traditionalstyle.html">Traditional</a></li>
        </ul>
      </li>
      <li><a href="http://www.jbcases.com/gallery/index.php">Picture Album</a></li>
    </ul>
  </li>
  <li><a href="http://www.jbcases.com/forsale.html">FOR SALE</a>
  </li>
  <li><a href="http://www.jbcases.com/testimonials.html">TESTIMONIALS</a>
  </li>
  <li><a href="http://www.jbcases.com/caseblog">BLOG</a></li>
  <li><a href="#">LINKS</a>
	<ul>
	<li><a href="http://www.jbcases.com/contact.html">Contact Us</a></li>

    <li><a href="http://www.jbcases.com/links.html">Links</a></li>
	</ul>
  </li>
</ul></div>