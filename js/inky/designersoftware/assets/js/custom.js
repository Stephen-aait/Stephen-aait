function setupLabel() {

	
}




function maintainToolLayout(){
	var windowWidth = $(window).width();
	if ((windowWidth < 992) && (windowWidth >= 768)) 
	{
		$(".order-box").removeClass("text-center");
		$(".order-box > span").addClass("pull-left")
		
	}
	if ((windowWidth > 0) && (windowWidth < 768)) 
	{
		$(".order-box").addClass("text-center");
		$(".order-box > span").removeClass("pull-left");
		$(".order-box > span").css({"float":"none"});
		
	}
	else {
		$(".order-box").removeClass("text-center");
		$(".order-box > span").css({"float":"right"});
		
	}
}
$(window).resize(function(){
	if(window.innerWidth<=1024)
	{
		$(".remove-selected-obj").removeClass("pull-left")
    	$(".remove-selected-obj").removeClass("pull-right")
    	//$(".remove-selected-obj").addClass("pull-left")
		mobileMode=true;
	}
	else
	{
		$(".remove-selected-obj").removeClass("pull-left")
    	$(".remove-selected-obj").removeClass("pull-right")
    	$(".remove-selected-obj").addClass("pull-right")
		mobileMode=false;
	}
		if(window.innerWidth<767)
		var left=($(".previewBoxHolder").width()-previewWidth)/2-39;
		else
		var left=($(".previewBoxHolder").width()-previewWidth)/2;
		var top=($(".previewBoxHolder").height()-previeHeight)/2;
		$(".previewCanvas").css({"left":left+"px","top":top+"px","width":previewWidth+"px","position":"absolute"})
	maintainToolLayout();
})
$(document).ready(function() {
	maintainToolLayout();	$(".bg-cross").on(clickEventType, function(){
		$(".tab-content").css({"display":"none"});
    	$('.nav-tabs li').removeClass('active');
		$(".category-section .tab-content").hide();
	})
	$('.label_check').click(function() {
		setupLabel();
	});
	setupLabel();
	$(".texture-bg li a, .art-color li a, .color_text li a").append('<i class="arrow"></i>');
	// $('.flexslider').flexslider({
		// animation : "fade",
		// controlNav : "thumbnails",
		// directionNav : false
	// });
	$("ol.flex-control-nav li").append('<a href="javascript:void(0)"><i class="fa fa-search"></i></a>')
	$(".inky-items li a").append('<i class="fa fa-search" ng-click="openPopUp()"></i>');
	zoomIcon();

	// $(".inky-items li i").bind("click", function() {
		// $(".lightbox, .perfect-case-box").fadeIn(300);
	// })
	// $(".lightbox, .perfect-cross").bind("click", function() {
// 
		// $(".lightbox, .perfect-case-box").fadeOut(300);
	// })
$()

});
/*function zoomIcon() {
 var objects=$("ol.flex-control-nav.flex-control-thumbs li");
 for (var i = 0 ; i < objects.length; i++) {
 $(objects[i]).attr("class",i)
 //console.log()
 }
 }*/
function zoomIcon() {
	var objects = $(".inky-items li");
	n = objects.length;
	for (var i = 0; i < n; i++) {
		$(objects[i]).attr("class", "zoom-" + i);

	}
}

