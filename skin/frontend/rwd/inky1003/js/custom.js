jQuery(window).load(function(){

       
     jQuery(".home_section2 .more-desc").hover(function(e) {         
            if (e.type == "mouseenter") {
                jQuery(this).addClass('overlay');   
            }
            else { // mouseleave
                jQuery(this).removeClass('overlay');
            }
        });

});