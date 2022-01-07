/*************************************************************** 
 * Application Name: DM FileManager									
 * Application URI: http://www.dutchmonkey.com/
 * Description: AJAX Web Based File Management System.
 * Author: Frank D. Strack
 * Author Email: development@dutchmonkey.com
 * Author URI: http://www.dutchmokney.com
 ***************************************************************/

var g_Browser = new Browser();

document.onmousemove 	= __mouseMove;
document.onmouseup   	= __mouseUp;

var dragObject  = null;
var mouseOffset = null;

function mouseCoords(ev){
	if(ev.pageX || ev.pageY){
		return {x:ev.pageX, y:ev.pageY};
	}
	return {
		x:ev.clientX + document.body.scrollLeft - document.body.clientLeft,
		y:ev.clientY + document.body.scrollTop  - document.body.clientTop
	};
}

function getMouseOffset(target, ev){
	ev = ev || window.event;

	var docPos    = getPosition(target);
	var mousePos  = mouseCoords(ev);
	return {x:mousePos.x - docPos.x, y:mousePos.y - docPos.y};
}

function getPosition(e){
	var left = 0;
	var top  = 0;

	while (e.offsetParent){
		left += e.offsetLeft;
		top  += e.offsetTop;
		e     = e.offsetParent;
	}

	left += e.offsetLeft;
	top  += e.offsetTop;

	return {x:left, y:top};
}

function __mouseMove(ev){
	ev           = ev || window.event;
	var mousePos = mouseCoords(ev);

	if(dragObject)
	{
		dragObject.style.position = 'absolute';
		
		var top = mousePos.y - mouseOffset.y;
		var left = mousePos.x - mouseOffset.x;
		
		if((top + dragObject.clientHeight) >= (g_Browser.Height() - 25))	top 	= (g_Browser.Height() - 25) - dragObject.clientHeight;
		if((left + dragObject.clientWidth) >= (g_Browser.Width() - 25))		left 	= (g_Browser.Width() - 25) - dragObject.clientWidth;
		
		if(top <= 25)	top = 25;
		if(left <= 25) 	left = 25;
		
		dragObject.style.top  = top;
		dragObject.style.left = left;

		return false;
	}
}

function __mouseUp(){
	dragObject = null;
}

function MakeDraggable(item, obj){
	if(!item) return;
	item.onmousedown = function(ev){
		dragObject  = obj;
		mouseOffset = getMouseOffset(this, ev);
		return false;
	}
}

function makeClickable(object){
	object.onmousedown = function(){
		dragObject = this;
	}
}