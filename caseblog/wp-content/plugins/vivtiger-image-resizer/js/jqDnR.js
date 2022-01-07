/*
 * jqDnR - Minimalistic Drag'n'Resize for jQuery.
 *
 * Copyright (c) 2007 Brice Burgess <bhb@iceburg.net>, http://www.iceburg.net
 * Licensed under the MIT License:
 * http://www.opensource.org/licenses/mit-license.php
 * 
 * $Version: 2007.08.19 +r2
 * Modified by Ronald Huereca on 10/08/2007 for limits
 */

(function($){
var left;
var top;
var width;
var height;
var cropon;
var cropRatio;
$.fn.jqDrag=function(h, leftPos, topPos, maxWidth, maxHeight){left = leftPos; top=topPos; width=maxWidth; height=maxHeight; return i(this,h,'d');};
$.fn.jqResize=function(h, leftPos, topPos, maxWidth, maxHeight, ratio, crop){left = leftPos; top=topPos; width=maxWidth; height=maxHeight; cropon = crop; cropRatio = ratio; return i(this,h,'r');};
$.jqDnR={dnr:{},e:0,
drag:function(v){
 if(M.k == 'd') { 
 		var cssLeft = Math.max(M.X+v.pageX-M.pX,0);
		var cssTop = Math.max(M.Y+v.pageY-M.pY,0);
 		if (cssLeft >= 0 && cssLeft <= left) {  E.css({left:cssLeft}); }
		if (cssTop >= 0 && cssTop <= top) { E.css({top:cssTop}); }
 } else {
	 var cssWidth = Math.round((v.pageX-M.pX+M.W)*1000)/1000;
	 if (cropon == "true") {
		 cssHeight = Math.round(cssWidth * cropRatio*1000)/1000;
		 if (parseInt(E.css("top").match(/(\d+)/)[1]) + cssHeight > parseInt(height)+1) { return; }
		 if (cropRatio < 1) { cssWidth = cssHeight / cropRatio; }
	 } else { cssHeight = Math.round((v.pageY-M.pY+M.H)*1000)/1000; }
	 if (parseInt(E.css("left").match(/(\d+)/)[1]) + cssWidth <= parseInt(width)-1) {  E.css({width:cssWidth});}
		if (parseInt(E.css("top").match(/(\d+)/)[1]) + cssHeight <= parseInt(height)-1) {  E.css({height:cssHeight});}
		left = width - parseInt(E.css("width").match(/(\d+)/)[1]) -1;
		top = height - parseInt(E.css("height").match(/(\d+)/)[1]) -1;
 }
  return false;},
stop:function(){E.css('opacity',M.o);$().unbind('mousemove',J.drag).unbind('mouseup',J.stop);}
};
var J=$.jqDnR,M=J.dnr,E=J.e,
i=function(e,h,k){return e.each(function(){h=(h)?$(h,e):e;
 h.bind('mousedown',{e:e,k:k},function(v){var d=v.data,p={};E=d.e;
 // attempt utilization of dimensions plugin to fix IE issues
 if(E.css('position') != 'relative'){try{E.position(p);}catch(e){}}
 M={X:p.left||f('left')||0,Y:p.top||f('top')||0,W:f('width')||E[0].scrollWidth||0,H:f('height')||E[0].scrollHeight||0,pX:v.pageX,pY:v.pageY,k:d.k,o:E.css('opacity')};
 E.css({opacity:0.8});$().mousemove($.jqDnR.drag).mouseup($.jqDnR.stop);
 return false;
 });
});},
f=function(k){return parseInt(E.css(k))||false;};
})(jQuery);