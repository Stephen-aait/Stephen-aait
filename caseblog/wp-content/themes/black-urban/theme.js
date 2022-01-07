jQuery(document).ready(function(){
    // Headers hints
    jQuery('#header .links a').append('<span></span>');
    
    // prepend span tag
	jQuery("#header h1 a").prepend("<span></span>");
    
    jQuery('#header .links a').hover(function(){
        var title = jQuery(this).attr('title');
        
        jQuery(this).find('span').html(title);
        jQuery(this).find('span').css('display', 'block').animate({opacity:1});
    },function(){
        jQuery(this).find('span').animate({opacity:0},'fast').css('display', 'none');
    });
    
});